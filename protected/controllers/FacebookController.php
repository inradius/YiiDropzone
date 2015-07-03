<?php

class FacebookController extends Controller {

    public $defaultAction = 'facebook';

    public function actionFacebook() {
        if (app()->request->isAjaxRequest) {
            $user = app()->request->getParam('user');
            Shared::debug($user);
            // verify one last time that facebook knows this guy
            if($user['id'] === app()->facebook->getUser()){
                $model = User::model()->findByEmail($user['email']);
                if(!empty($model)){
                    // facebook email matches one in the user database
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
                    // nothing found, this person should register
                    // To abide by Facebook terms, this will ask the user if they wish to register before storing any data from Facebook
                }
            } else {
                // fb user id past from ajax does not match who facebook says they are...
                echo json_encode(array('error' => 'Facebook Authentication Failed', 'code' => 'fb_auth'));
                app()->end();
            }
        } else {
            throw new CHttpException(403);
        }
    }

}