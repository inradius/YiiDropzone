<?php

require_once Yii::getPathOfAlias('ext.google.assets').'/Google_Client.php';
require_once Yii::getPathOfAlias('ext.google.assets').'/contrib/Google_PlusService.php';

class GoogleController extends Controller {

    public $defaultAction = 'google';

    public function actionGoogle() {
        if (app()->request->isAjaxRequest) {
            if (app()->request->getParam('state') != app()->session['google-state']) {
                echo json_encode(array('error' => 'Invalid CSRF token. Try refreshing your browser.', 'code' => 'state'));
                app()->end();
            }
            $gplusId = app()->request->getParam('gplus_id');
            $code = app()->request->getParam('code');

            $client = new Google_Client();
            $client->setClientId(app()->google->clientId);
            $client->setClientSecret(app()->google->clientSecret);
            $client->setRedirectUri('postmessage');
            $plus = new Google_PlusService($client);
            $client->authenticate($code);
            $token = json_decode($client->getAccessToken());
            $reqUrl = 'https://www.googleapis.com/oauth2/v1/tokeninfo?access_token='.$token->access_token;
            $req = new Google_HttpRequest($reqUrl);
            $tokenInfo = json_decode($client::getIo()->authenticatedRequest($req)->getResponseBody());
            
            if ($tokenInfo->user_id != $gplusId) {
                echo json_encode(array('error' => 'Google ID mismatch', 'code' => 'user'));
                app()->end();
            }
            if ($tokenInfo->audience != app()->google->clientId) {
                echo json_encode(array('error' => 'Google Client ID mismatch', 'code' => 'client'));
                app()->end();
            }
            
            $model = User::model()->findByEmail($tokenInfo->email);
            if(!empty($model)){
                // google email matches one in the user database
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
                // To maintain consistency, the user will be prompted to register with the data we found, rather than just storing it blindly
                /*
                $user = $plus->people->get('me');
                $model = new User('create');
                $model->email = $tokenInfo->email;
                $model->first_name = ($user['name']['givenName']?$user['name']['givenName']:'');
                $model->last_name = ($user['name']['familyName']?$user['name']['familyName']:'');
                $model = User::create($model->attributes);
                $model->activate = User::encrypt(microtime() . $model->password);
                $model->save();
                $mail = new Mailer('gRegister', array('username' => $model->email, 'password' => $model->pass1, 'activate' => $model->activate));
                */
                /**
                 * Be sure to configure properly! Check https://github.com/Synchro/PHPMailer for documentation.
                 */
                /*
                $mail->render();
                $mail->From = app()->params['adminEmail'];
                $mail->FromName = app()->params['adminEmailName'];
                $mail->Subject = 'Your ' . app()->name . ' Account';
                $mail->AddAddress($model->email);
                if ($mail->Send()) {
                    $mail->ClearAddresses();
                }
                $identity = new UserIdentity( $model->email , null );
                $identity->_ssoAuth = true;
                $identity->authenticate();
                if($identity->errorCode === UserIdentity::ERROR_NONE){
                    app()->user->login($identity, null);
                    app()->user->setFlash('info', '<strong>Registration Success</strong> Google+ profile successfully registered. Check your email for activation instructions.');
                    echo json_encode(array('error' => false, 'success' => url('/')));
                    app()->end();
                } else {
                    echo json_encode(array('error' => 'System Authentication Failed', 'code' => 'auth'));
                    app()->end();
                }
                */
            }
        } else {
            throw new CHttpException(403);
        }
    }

}