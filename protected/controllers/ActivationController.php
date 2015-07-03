<?php

class ActivationController extends Controller {

    public $defaultAction = 'activation';

    /**
     * Activate the users account when the correct hash is matched up.
     */
    public function actionActivation() {
        if (count($_REQUEST) != 0) {
            $activkey = $_GET['activate'];
            $find = User::model()->findByAttributes(array('activate' => $activkey));
            if (isset($find) && $find->email_verified) {
                throw new CHttpException(201, 'Account Has Already Been Activated.');
            } elseif (isset($find->activate) && ($find->activate == $activkey)) {
                //$find->activkey = null;
                $find->email_verified = 1;
                $find->save();
                throw new CHttpException(200, 'Account Activated.');
            } else {
                throw new CHttpException(400, 'Bad Activation Code.');
            }
        } else {
            throw new CHttpException(403, 'Access Denied.');
        }
    }

}