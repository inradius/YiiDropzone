<?php
/* @var $this UserController */
/* @var $model User */

if(app()->user->isAdmin()){
    $pageName = 'Create User';
    $class = 'col-md-12';
    $this->layout = 'column2';
    $this->menu = array(
        array('label'=>'List User', 'url'=>array('index')),
        array('label' => 'Create User', 'url' => array('create'), 'active' => true),
    );
} else {
    $pageName = 'Register';
    $class = 'col-md-4 col-md-offset-4';
}

$this->pageTitle = app()->name . ' - ' . $pageName;
$this->breadcrumbs = array(
    $pageName,
);
?>

<div class="<?php echo $class; ?>">
    <h1  class="page-header"><?php echo $pageName; ?></h1>

    <?php echo $this->renderPartial('_form', array('model' => $model)); ?>
</div>