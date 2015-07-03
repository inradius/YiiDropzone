<?php
/* @var $this ImageController */
/* @var $model Image */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'image-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'user_id'); ?>
		<?php echo $form->textField($model,'user_id'); ?>
		<?php echo $form->error($model,'user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'long_id'); ?>
		<?php echo $form->textField($model,'long_id',array('size'=>31,'maxlength'=>31)); ?>
		<?php echo $form->error($model,'long_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'image_resolution'); ?>
		<?php echo $form->textField($model,'image_resolution',array('size'=>12,'maxlength'=>12)); ?>
		<?php echo $form->error($model,'image_resolution'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'image_created'); ?>
		<?php echo $form->textField($model,'image_created'); ?>
		<?php echo $form->error($model,'image_created'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'image_updated'); ?>
		<?php echo $form->textField($model,'image_updated'); ?>
		<?php echo $form->error($model,'image_updated'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'image_file_type'); ?>
		<?php echo $form->textField($model,'image_file_type',array('size'=>60,'maxlength'=>63)); ?>
		<?php echo $form->error($model,'image_file_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'image_file_size'); ?>
		<?php echo $form->textField($model,'image_file_size'); ?>
		<?php echo $form->error($model,'image_file_size'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'image_file_name'); ?>
		<?php echo $form->textField($model,'image_file_name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'image_file_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'image_file_content'); ?>
		<?php echo $form->textField($model,'image_file_content'); ?>
		<?php echo $form->error($model,'image_file_content'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->