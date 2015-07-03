<?php

class LiveController extends Controller {

    public $defaultAction = 'live';

    public function actionLive() {
        if (app()->request->isAjaxRequest) {
            if (app()->request->getParam('state') != app()->session['live-state']) {
                echo json_encode(array('error' => 'Invalid CSRF token. Try refreshing your browser.', 'code' => 'state'));
                app()->end();
            }
            $user = app()->request->getParam('user');
            $model = User::model()->findByEmail($user['emails']['account']);
            if(!empty($model)){
                // windows live primary email matches one in the user database
                $identity = new UserIdentity( $model->email , null );
                $identity->_ssoAuth = true;
                $identity->authenticate();
                if($identity->errorCode === UserIdentity::ERROR_NONE){
                    app()->user->login($identity, null);
                    echo json_encode(array('error' => false, 'success' => url('/')));
                    app()->end();
                } else {
                    echo json_encode(array('error' => 'System Authentication Failed', 'code' => 'auth'));
                    app()->end();
                }
            } else {
                echo json_encode(array('error' => 'No account was found.', 'code' => 'none'));
                app()->end();
            }
        } else {
            throw new CHttpException(403);
        }
    }
}