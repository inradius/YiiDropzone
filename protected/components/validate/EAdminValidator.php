<?php

/**
 * Only admin can change value of this field. This validator requires history 
 * behavior (default in CActiveRecord) to set original value if user is not admin
 */
class EAdminValidator extends CValidator {
    protected function validateAttribute($object, $attribute) {
        
        if (!app()->user->isAdmin()){
            $value = $object->$attribute;
            // it might be handled outside
            if ($value == null) return true;
            $original = $object->getOriginalAttribute($attribute);
            $value = ($value == 0 && $original == null ? null : $value);
            
            // just force back the original value if we have it
            // it is more efficient this way than searching list of dirty attributes
            if ($original != $value){
                //Shared::debug($original);
                //Shared::debug($value);
                // watch out, we have a bad guy here! Reset it back
                $object->$attribute = $original;
                $this->addError($object, $attribute, "You do not have a permission to change $attribute.");
            }
        }
    }
}
?>
