<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class WebUser extends CWebUser {
    
    private $_user;
    
    /**
     * Is it an administrator? Should this guy have full rights?
     * @return type
     */
    function isAdmin() {
        return (!$this->isGuest() && $this->getUser()->admin);
    }
    
    /**
     * In other words, do we have anonymous user?
     */
    public function isGuest() {
        return $this->isGuest;
    }
    
    /**
     * Returns user object from database
     * @return type 
     */
    function getUser() {
        if ($this->isGuest)
            return false;
        if ($this->_user === null) {
            // should check the cache first, but this is still to-do
            //$this->_user = User::findInCache($this->id);
            $this->_user = User::model()->findByPk($this->id);
        }
        return $this->_user;
    }
    
    /**
     * Home url's for the 3 types of user. Unused for now.
     */
    public function getHomeUrl() {
        if ($this->IsAdmin()) {
             return url('/');
        } elseif ($this->IsGuest()) {
            return url('/');
        } else {
            return url('/');
        }
    }
}
?>
