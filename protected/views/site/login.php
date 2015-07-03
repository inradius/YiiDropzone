<?php

$this->pageTitle = app()->name . ' - Login';
$this->breadcrumbs = array('Login');
?>

<div class="col-md-4 col-md-offset-4">
    <h1 class="page-header">Login</h1>

    <?php $form = $this->beginWidget('CActiveForm', array('htmlOptions' => array('role' => 'form'))); ?>

    <div class="form-group<?php if($form->error($model, 'username')) echo ' has-error'; ?>">
        <?php echo $form->label($model, 'username'); ?>
        <?php echo $form->textField($model, 'username', array(
            'class' => 'form-control',
            'size' => 45,
            'maxlength' => 45,
        )); ?>
    </div>

    <div class="form-group<?php if($form->error($model, 'password')) echo ' has-error'; ?>">
        <?php echo $form->label($model, 'password'); ?>
        <?php echo $form->passwordField($model, 'password', array(
            'class' => 'form-control',
            'size' => 45,
            'maxlength' => 45,
        )); ?>
    </div>

    <div class="checkbox row">
        <div class="col-md-6">
            <label><?php echo $form->checkBox($model,'rememberMe'); ?><?php echo $model->getAttributeLabel('rememberMe'); ?></label>
        </div>
        <div class="col-md-6">
            <?php echo CHtml::link('Forgot Password?', array('user/forgotPassword'), array('class' => 'pull-right')); ?>
        </div>
    </div>

    <?php echo CHtml::submitButton('Login', array('class' => 'btn btn-primary btn-block')); ?>

<!--
    <div class="form-actions">
        <?php //$this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'icon' => 'user', 'label' => 'Login')); ?>
        <?php //$this->widget('application.components.widgets.FBConnect'); ?>
        <?php //$this->widget('application.components.widgets.GConnect'); ?>
        <?php //$this->widget('application.components.widgets.LiveConnect'); ?>
    </div>

    <div id="processing" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="processingLabel" aria-hidden="true">
        <div class="modal-header">
            <h3 id="processingLabel"><i class="icon-spinner icon-spin"></i> Processing Request</h3>
        </div>
        <div class="modal-body">
            <p>One moment while we processes your request. This shouldn't take too long.</p>
        </div>
        <div class="modal-footer"></div>
    </div>
-->

    <?php $this->endWidget(); ?>

</div>