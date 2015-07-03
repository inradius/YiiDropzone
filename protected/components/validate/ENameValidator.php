<?php

/**
 * Validates stings agains SQL injection and also removes numbers form the name
 * This validator should work with accents in names
 */
class ENameValidator extends CValidator {

    /**
     * @var string the regular expression used to validates the attribute value.
     */
    public $safetyPattern = "/[\^<,\"@\/\{\}\(\)\*\$%\?=>:\|;#]+/i";
    /**
     * @var boolean whether the attribute value can be null or empty. Defaults to true,
     * meaning that if the attribute is empty, it is considered valid.
     */
    public $allowNumbers = true;

    /**
     * Validates the attribute of the object.
     * If there is any error, the error message is added to the object.
     * @param CModel $object the object being validated
     * @param string $attribute the attribute being validated
     */
    protected function validateAttribute($object, $attribute) {
        $value = $object->$attribute;
        if (!$this->validateValue($value)) {
            $message = $this->message !== null ? $this->message : Yii::t('yii', '{attribute} contains invalid characters. Please do not use accents.');
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
        $output = is_string($value) && !preg_match($this->safetyPattern, $value);
        if ($output && !$this->allowNumbers) {
            $numberPattern = "/[0-9]+/i";
            return!preg_match($numberPattern, $value);
        }
        return $output;
    }

}

