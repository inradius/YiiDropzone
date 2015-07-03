<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<?php $form = $this->beginWidget('CActiveForm', array('htmlOptions' => array('role' => 'form'))); ?>

<?php echo (app()->user->isAdmin()&&$model->isNewRecord?'<div class="alert alert-info">
        <h4>Notice</h4>
        A random password will be generated and sent to the email specified along with an account activation link.
    </div>':''); ?>

<?php //echo $form->errorSummary($model); ?>

<div class="form-group<?php if($form->error($model, 'first_name')) echo ' has-error'; ?>">
    <?php echo $form->label($model, 'first_name'); ?>
    <?php echo $form->textField($model, 'first_name', array(
        'class' => 'form-control',
        'size' => 45,
        'maxlength' => 45,
    )); ?>
</div>

<div class="form-group<?php if($form->error($model, 'last_name')) echo ' has-error'; ?>">
    <?php echo $form->label($model, 'last_name'); ?>
    <?php echo $form->textField($model, 'last_name', array(
        'class' => 'form-control',
        'size' => 45,
        'maxlength' => 45,
    )); ?>
</div>

<div class="form-group<?php if($form->error($model, 'username')) echo ' has-error'; ?>">
    <?php echo $form->label($model, 'username'); ?>
    <?php echo $form->textField($model, 'username', array(
        'class' => 'form-control',
        'size' => 15,
        'maxlength' => 15,
    )); ?>
</div>

<div class="form-group<?php if($form->error($model, 'email')) echo ' has-error'; ?>">
    <?php echo $form->label($model, 'email'); ?>
    <?php echo $form->textField($model, 'email', array(
        'class' => 'form-control',
        'size' => 60,
        'maxlength' => 63,
    )); ?>
</div>

<?php if(!$model->isNewRecord): // Edit a record (password fields are not shown here, instead a link) ?>

    <div class="control-group form-link">
        <?php echo CHtml::link('<i class="fa fa-key"></i> Change Password', array('user/password', 'id' => $model->id)); ?>
    </div>

<?php else: // Create new record ?>

<?php if(!app()->user->isAdmin()): // Create new record as non admin user (password and captcha required) ?>

        <div class="form-group<?php if($form->error($model, 'password')) echo ' has-error'; ?>">
            <?php echo $form->label($model, 'password'); ?>
            <?php echo $form->passwordField($model, 'password', array(
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

    <?php endif; ?>

    <?php endif; ?>

    <?php if(app()->user->isAdmin()): // Create OR Edit a record as admin (extra options availible) ?>

        <?php if(app()->user->id !== $model->id): // admin cannot take privileges from themselves ?>

            <div class="checkbox">
                <label><?php echo $form->checkBox($model,'admin'); ?><?php echo $model->getAttributeLabel('admin'); ?></label>
            </div>

            <div class="checkbox">
                <label><?php echo $form->checkBox($model,'disabled'); ?><?php echo $model->getAttributeLabel('disabled'); ?></label>
            </div>

        <?php endif; ?>

    <?php endif; ?>

<?php echo CHtml::submitButton($model->isNewRecord ? app()->user->isAdmin() ? 'Create User' : 'Register' : 'Update', array('class' => 'btn btn-primary btn-block', 'style' => 'margin-top:10px;')); ?>

<?php $this->endWidget(); ?>