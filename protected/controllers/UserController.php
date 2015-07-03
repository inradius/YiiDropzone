<?php

class UserController extends Controller {

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl',
            'postOnly + delete',
        );
    }

    public function accessRules() {
        return array(
            // Actions: index, create, update, password, newPassword, forgotPassword, delete
            array('allow',
                'actions' => array('captcha', 'create', 'forgotPassword', 'newPassword'),
                'expression' => 'app()->user->isGuest()',
            ),
            array('allow',
                'actions' => array('password', 'update'),
                'users' => array('@'),
            ),
            array('allow',
                'actions' => array('index', 'create', 'register', 'delete'),
                'expression' => 'app()->user->isAdmin()',
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    public function actions() {
        return array(
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
                'foreColor' => 0x333333,
            ),
        );
    }

    public function actionIndex() {
        $criteria = new CDbCriteria();
        $model = new User('search');
        if (isset($_GET['User'])) {
            $model->attributes = $_GET['User'];
            $criteria->mergeWith($model->search());
        }
        $dataProvider = new CActiveDataProvider('User', array(
                    'pagination' => array('pageSize' => 10),
                    'criteria' => $criteria,
                ));
        $this->render('index', array(
            'model' => $model,
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionCreate() {
        if (app()->user->isAdmin()) {
            $model = new User('create');
            $email = 'adminCreate';
            $success = 'Account created and details sent to users inbox.';
            $redirect = array('/user/index');
        } else {
            $model = new User('register');
            $email = 'userCreate';
            $success = 'Account registered. Please check your email.';
            $redirect = array('/site/index');
        }
        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            if ($model->validate()) {
                $model = User::create($_POST['User']);
                $model->activate = User::encrypt(microtime() . $model->password);

                $model->save();
                app()->user->setFlash('success', $success);
                $mail = new Mailer($email, array('username' => $model->email, 'password' => $model->pass1, 'activate' => $model->activate));
                /**
                 * Be sure to configure properly! Check https://github.com/Synchro/PHPMailer for documentation.
                 */
                $mail->render();
                $mail->From = app()->params['adminEmail'];
                $mail->FromName = app()->params['adminEmailName'];
                $mail->Subject = 'Your ' . app()->name . ' Account';
                $mail->AddAddress($model->email);
                if ($mail->Send()) {
                    $mail->ClearAddresses();
                    $this->redirect($redirect);
                } else {
                    $this->redirect($redirect);
                }
            }
        }
        $this->render('create', array('model' => $model));
    }

    public function actionUpdate($id) {
        $user = app()->user->getUser();
        if (isset($user->id) && $user->id === $id || app()->user->isAdmin()) {
            // only accessable by id holder or admin
            $model = $this->loadModel($id);
            if (isset($_POST['User'])) {
                $model->attributes = $_POST['User'];
                if ($model->validate()) {
                    $model->save();
                    app()->user->setFlash('success', 'Saved');
                }
            }
            $model->password = '';
            $this->render('update', array('model' => $model));
        } else {
            // access denied for this user
            throw new CHttpException(403, 'Access Denied.');
        }
    }

    public function actionDelete($id) {
        //if($this->authAdminOnly()){
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        //}
    }

    public function actionPassword($id) {
        $user = app()->user->getUser();
        if (isset($user->id) && $user->id === $id || app()->user->isAdmin()) {
            $model = $this->loadModel($id);
            $model->setScenario('changePassword');
            if($data = app()->request->getPost('User')) {
                $model->attributes = $data;
                if ($model->validate()) {
                    $model->password = CPasswordHelper::hashPassword($data['pass1']);
                    if ($model->save()) {
                        app()->user->setFlash('success', 'Saved new password!');
                        $this->redirect(array('update', 'id' => $model->id));
                    }
                }
            }

            $this->render('/user/password', array('model' => $model));
        } else {
            // access denied for this user
            throw new CHttpException(403, 'Access Denied.');
        }
    }

    public function actionForgotPassword() {
        $model = new User('passwordReset');
        $model->setScenario('forgotPassword');
        $hash = '';
        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            if ($model->validate()) {
                $model = User::model()->findByEmail($_POST['User']['email']);
                $timestamp = time();
                $hash = User::encrypt($model->email . $model->password . $timestamp);
                if(YII_DEBUG) Shared::debug($hash);
                $model->pass_reset = $timestamp;
                // save the timestamp (password reset is good for 24 hours only)
                $model->save();

                $mail = new Mailer('forgotPass', array('hash' => $hash));
                /**
                 * Be sure to configure properly! Check https://github.com/Synchro/PHPMailer for documentation.
                 */
                $mail->render();
                $mail->From = app()->params['adminEmail'];
                $mail->FromName = app()->params['adminEmailName'];
                $mail->Subject = app()->name . ' Password Reset';
                $mail->AddAddress($model->email);
                if ($mail->Send()) {
                    $mail->ClearAddresses();
                    app()->user->setFlash('success', 'Please check your email for further instructions.');
                    $this->redirect(array('site/index'));
                } else {
                    app()->user->setFlash('error', 'Error while sending email: ' . $mail->ErrorInfo);
                }
            }
        }
        $this->render('forgot_password', array('model' => $model));
    }

    public function actionNewPassword($req) {
        // lookup users, who requested a password change in the past day
        $since = strtotime(Shared::toDatabase(time()) . " -1 day");
        $users = User::model()->findAllBySql("SELECT * FROM user WHERE pass_reset > $since");
        $found = null;
        foreach ($users as $model) {
            if ($req === User::encrypt($model->email . $model->password . $model->pass_reset)) {
                $found = $model;
                break;
            }
        }
        if ($found != null) {
            $model->setScenario('resetPass');
            // reset the password
            if($data = app()->request->getPost('User')) {
                $model->attributes = $data;
                if ($model->validate()) {
                    $found->setPassword($data['pass1']);
                    $model->pass_reset = null;
                    if ($found->save()) {
                        app()->user->setFlash('success', 'Your password has been reset.');
                        $this->redirect(array('site/index'));
                    }
                }
            }
            $this->render('new_password', array('model' => $found));
            app()->end();
        }
        // display not found screen
        throw new CHttpException(400, '<p>This password reset link is not valid. To reset your password, please <a href="' . url('user/forgotpassword') . '">initiate a new request</a>.</p>');
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = User::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
            echo CActiveForm::validate($model);
            app()->end();
        }
    }

    public static function renderButtons($model) {
        // todo - make this a widget since this is really sloppy for controller code
        // todo - also, use bootstrap modal rather than generic js alert
        $buttonGroup = CHtml::openTag('div', array('class' => 'btn-group'))
            . CHtml::htmlButton('Action <span class="caret"></span>', array('class' => 'btn btn-default dropdown-toggle', 'data-toggle' => 'dropdown'))
            . CHtml::openTag('ul', array('class' => 'dropdown-menu pull-right', 'role' => 'menu'))
            . CHtml::tag('li', array(), CHtml::link('<i class="fa fa-edit"></i> Edit User', array('user/update', 'id' => $model->id)))
            . CHtml::tag('li', array(), CHtml::link('<i class="fa fa-trash-o"></i> Delete User', array('user/delete', 'id' => $model->id), array("class" => "delete")))
            . CHtml::closeTag('ul')
            . CHtml::closeTag('div');

        $script = 'jQuery(document).on("click","#user-gridview a.delete",function() {
        if(!confirm("Are you sure you want to delete this item?")) return false;
        var th = this,
            afterDelete = function(){};
        jQuery("#user-gridview").yiiGridView("update", {
            type: "POST",
            url: jQuery(this).attr("href"),
            success: function(data) {
                jQuery("#user-gridview").yiiGridView("update");
                afterDelete(th, true, data);
            },
            error: function(XHR) {
                return afterDelete(th, false, XHR);
            }
        });
        return false;
        });';

        cs()->registerScript('edit-actions', $script, CClientScript::POS_END);


        return $buttonGroup;
    }
}