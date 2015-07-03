<?php
/* @var $this UserController */
/* @var $model User */

if($model->id===app()->user->id){
    $breadcrumbs = array(
        'Profile' => array('/user/update', 'id' => app()->user->id),
        'Change Password'
    );
} else {
    $breadcrumbs = array(
        'Users' => array('/user/index'),
        $model->email => array('/user/update', 'id' => $model->id),
        'Change Password'
    );
}

$this->layout = app()->user->isAdmin()?'column2':'';
$this->pageTitle = app()->name . ' - Change Password';
$this->breadcrumbs = $breadcrumbs;

$this->menu=array(
	array('label'=>'List User', 'url'=>array('index')),
	array('label'=>'Create User', 'url'=>array('create')),
);
?>

<div class="col-md-12">
    <h1  class="page-header">Change <?php echo $model->username; ?> Password</h1>

<?php $form = $this->beginWidget('CActiveForm', array('htmlOptions' => array('role' => 'form'))); ?>

    <?php //echo $form->errorSummary($model); ?>

        <?php if(!app()->user->isAdmin()): ?>

            <div class="form-group<?php if($form->error($model, 'old_password')) echo ' has-error'; ?>">
                <?php echo $form->label($model, 'old_password'); ?>
                <?php echo $form->passwordField($model, 'old_password', array(
                    'class' => 'form-control',
                    'maxlength' => 63,
                )); ?>
            </div>

        <?php endif; ?>

        <div class="form-group<?php if($form->error($model, 'pass1')) echo ' has-error'; ?>">
            <?php echo $form->label($model, 'pass1'); ?>
            <?php echo $form->passwordField($model, 'pass1', array(
                'class' => 'form-control',
                'maxlength' => 63,
            )); ?>
        </div>

        <div class="form-group<?php if($form->error($model, 'pass2')) echo ' has-error'; ?>">
            <?php echo $form->label($model, 'pass2'); ?>
            <?php echo $form->passwordField($model, 'pass2', array(
                'class' => 'form-control',
                'maxlength' => 63,
            )); ?>
        </div>

    <?php echo CHtml::submitButton('Change Password', array('class' => 'btn btn-primary btn-block')); ?>
    
    <?php $this->endWidget(); ?>
</div>