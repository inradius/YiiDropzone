<?php
/* @var $this ImageController */
/* @var $data Image */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
	<?php echo CHtml::encode($data->user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('long_id')); ?>:</b>
	<?php echo CHtml::encode($data->long_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('image_resolution')); ?>:</b>
	<?php echo CHtml::encode($data->image_resolution); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('image_created')); ?>:</b>
	<?php echo CHtml::encode($data->image_created); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('image_updated')); ?>:</b>
	<?php echo CHtml::encode($data->image_updated); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('image_file_type')); ?>:</b>
	<?php echo CHtml::encode($data->image_file_type); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('image_file_size')); ?>:</b>
	<?php echo CHtml::encode($data->image_file_size); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('image_file_name')); ?>:</b>
	<?php echo CHtml::encode($data->image_file_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('image_file_content')); ?>:</b>
	<?php echo CHtml::encode($data->image_file_content); ?>
	<br />

	*/ ?>

</div>