<?php

/**
 * Verify that the entered password matches that stored as a hash in database
 * Used for the user password change form (not for forgotten password reset)
 */
class ECurrentPassword extends CValidator {

    protected function validateAttribute($object, $attribute) {
        $value = $object->$attribute;
        if (!$this->validateValue($value)) {
            $this->addError($object, $attribute, 'The current password you\'ve entered is incorrect.');
        }
    }
    
    public function validateValue($password){
        if(app()->user->isAdmin()){
            return true;
        } else {
            $user = app()->user->getUser();
            $model = User::model()->findByPk($user->id);
            if($model['password'] != crypt($password, $model['password'])) {
                return false;
            } else {
                return true;
            }
            return false;
        }
    }

}

?>
