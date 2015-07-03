<?php

class SiteController extends Controller {

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        $this->render('index');
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the login page
     */
    public function actionLogin() {
        if(app()->user->isGuest()){
            $model = new LoginForm;

            // if it is ajax validation request
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
                echo CActiveForm::validate($model);
                app()->end();
            }

            // collect user input data
            if (isset($_POST['LoginForm'])) {
                $model->attributes = $_POST['LoginForm'];
                // validate user input and redirect to the previous page if valid
                if ($model->validate() && $model->login()) {
                    $user = app()->user->getUser();
                    User::model()->updateByPk($user->id, array('last_login' => new CDbExpression('NOW()')));
                    if (isset($_POST['ajax'])) {
                        echo app()->user->getHomeUrl();
                        app()->end();
                    } else {
                        $this->redirect(app()->user->returnUrl);
                    }
                } else {
                    if (isset($_POST['ajax'])) {
                        echo "bad";
                        app()->end();
                    } else {
                        app()->user->setFlash('error', 'Login failed. Please try again.');
                    }
                }
            }
            // display the login form
            $this->render('login', array('model' => $model));
        } else {
            $this->redirect(array('/user/update', 'id' => app()->user->id));
        }
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        app()->user->logout();
        $this->redirect(app()->homeUrl);
    }

}