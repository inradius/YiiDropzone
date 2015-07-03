<?php

$this->pageTitle = app()->name . ' - Forgot Password';
$this->breadcrumbs = array(
    'Login' => array('/site/login'),
    'Forgot Password',
);
?>

<div class="col-md-4 col-md-offset-4">

    <h1 class="page-header">Forgot Password</h1>

    <?php $form = $this->beginWidget('CActiveForm', array('htmlOptions' => array('role' => 'form'))); ?>

    <?php //echo $form->errorSummary($model); ?>

    <div class="form-group<?php if($form->error($model, 'email')) echo ' has-error'; ?>">
        <?php echo $form->label($model, 'email'); ?>
        <?php echo $form->textField($model, 'email', array(
            'class' => 'form-control',
            'placeholder' => 'email address'
        )); ?>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group<?php if($form->error($model, 'verify')) echo ' has-error'; ?>">
                <?php echo CHtml::activeLabel($model, 'verify', array('required' => true)); ?>
                <?php echo $form->textField($model, 'verify', array(
                    'class' => 'form-control',
                )); ?>
            </div>
        </div>
        <div class="col-md-6" style="text-align: center;">
            <?php $this->widget('CCaptcha', array('clickableImage' => true, 'showRefreshButton' => false, 'imageOptions' => array('style' => 'margin-top: 10px; cursor: pointer;'))); ?>
        </div>
    </div>

    <?php echo CHtml::submitButton('Request Reset', array('class' => 'btn btn-primary btn-block', 'style' => 'margin-top:10px;')); ?>

    <?php $this->endWidget(); ?>
</div>