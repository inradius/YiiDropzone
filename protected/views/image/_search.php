<?php
/* @var $this ImageController */
/* @var $model Image */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'user_id'); ?>
		<?php echo $form->textField($model,'user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'long_id'); ?>
		<?php echo $form->textField($model,'long_id',array('size'=>31,'maxlength'=>31)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'image_resolution'); ?>
		<?php echo $form->textField($model,'image_resolution',array('size'=>12,'maxlength'=>12)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'image_created'); ?>
		<?php echo $form->textField($model,'image_created'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'image_updated'); ?>
		<?php echo $form->textField($model,'image_updated'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'image_file_type'); ?>
		<?php echo $form->textField($model,'image_file_type',array('size'=>60,'maxlength'=>63)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'image_file_size'); ?>
		<?php echo $form->textField($model,'image_file_size'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'image_file_name'); ?>
		<?php echo $form->textField($model,'image_file_name',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'image_file_content'); ?>
		<?php echo $form->textField($model,'image_file_content'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->