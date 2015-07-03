<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    public $_ssoAuth = false;
    private $_id;

    public function authenticate() {
        // find user by username or email
        $user = User::model()->findByAttributes(array('username' => $this->username));
        if($user === null)
            $user = User::model()->findByAttributes(array('email' => $this->username));

        // begin checking the user
        if ($user === null) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else if ($user->disabled == 1) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } elseif (!CPasswordHelper::verifyPassword($this->password, $user->password)&&!$this->_ssoAuth) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else {
            $this->errorCode = self::ERROR_NONE;
            if ($this->errorCode == self::ERROR_NONE) {
                // store their latest login time
                $user->last_login = Shared::timeNow();
                $user->save();

                $this->_id = $user->id;
            }
        }

        return !$this->errorCode;
    }

    public function getId() {
        return $this->_id;
    }

}
