<?php

$this->pageTitle = app()->name . ' - Reset Password';
$this->breadcrumbs = array(
    'Login' => array('/site/login'),
    'Reset Password',
);
?>

<div class="col-md-4 col-md-offset-4">

    <h1 class="page-header">Reset Password</h1>

    <?php if (!$model->isNewRecord): ?>

        <p>Please provide your desired password in the fields below to complete the password reset.</p>

        <?php $form = $this->beginWidget('CActiveForm', array('htmlOptions' => array('role' => 'form'), 'focus' => array($model, 'pass1'))); ?>

        <?php //echo $form->errorSummary($model); ?>

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

        <?php echo CHtml::submitButton('Reset Password', array('class' => 'btn btn-primary btn-block', 'style' => 'margin-top:10px;')); ?>

        <?php $this->endWidget(); ?>

    <?php else: ?>

        <p>Please check your email for instructions on resetting your account password. The email was sent to <code><?php echo $model->email_address; ?></code>.</p>

    <?php endif; ?>
</div>