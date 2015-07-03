<?php

/**
 * Validates email format and domail
 */
class EEmailValidator extends CValidator {

    /**
     * Validates the attribute of the object.
     * If there is any error, the error message is added to the object.
     * @param CModel $object the object being validated
     * @param string $attribute the attribute being validated
     */
    protected function validateAttribute($object, $attribute) {
        $value = $object->$attribute;

        if (!$this->validateValue($value)) {
            $message = $this->message !== null ? $this->message : Yii::t('yii', 'Email address seems invalid - check the format and domain.');
            $this->addError($object, $attribute, $message);
        }
    }

    /**
     * Validates a static value to see if it is a valid URL.
     * Note that this method does not respect {@link allowEmpty} property.
     * This method is provided so that you can call it directly without going through the model validation rule mechanism.
     * @param mixed $value the value to be validated
     * @return boolean whether the value is a valid URL
     * @since 1.1.1
     */
    public function validateValue($value) {
        if ($value == null) return true; // allow empty
        if ($value == '') return true;
        
        if (Shared::isEmailValid($value)){
            return true;
        }
        return false;
    }

}

