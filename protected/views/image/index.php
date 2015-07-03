<?php
/* @var $this ImageController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Images',
);

$this->menu=array(
	array('label'=>'Create Image', 'url'=>array('create')),
	array('label'=>'Manage Image', 'url'=>array('admin')),
);
?>

<h1>Images</h1>

<?php $this->widget('ext.dropzone.components.EDropzone', array('name' => 'upload')); ?>
<form action="/image/process" class="dropzone" id="dropzone"></form>

<?php /*$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
));*/ ?>
