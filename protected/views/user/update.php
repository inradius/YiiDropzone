<?php
/* @var $this UserController */
/* @var $model User */

if ($model->id === app()->user->id) {
    $breadcrumbs = array('Profile');
} else {
    $breadcrumbs = array(
        'Users' => array('/user/index'),
        $model->email
    );
}

$this->layout = app()->user->isAdmin() ? 'column2' : '';
$this->pageTitle = app()->name . ' - Profile';
$this->breadcrumbs = $breadcrumbs;

$this->menu = array(
    array('label' => 'List User', 'url' => array('index')),
    array('label' => 'Create User', 'url' => array('create')),
);
?>

<div class="col-md-12">
    <h1  class="page-header">Update <?php echo $model->username; ?></h1>

    <?php echo $this->renderPartial('_form', array('model' => $model, 'pageName' => 'Update')); ?>
</div>