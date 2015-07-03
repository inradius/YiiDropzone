<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController {

    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/column1';

    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();

    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();

    protected function afterRender($view, &$output) {
        parent::afterRender($view, $output);
        app()->facebook->renderOGMetaTags();
        return true;
    }

    /**
     * Authenticate Admin users only.
     * @return type
     */
    protected function authAdminOnly() {
        if (app()->user->isGuest()) {
            app()->user->loginRequired();
        } elseif (!app()->user->isAdmin()) {
            throw new CHttpException(403, 'Access Denied.');
        }
        return true;
    }

}