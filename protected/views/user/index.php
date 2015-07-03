<?php
/* @var $this UserController */
/* @var $dataProvider CActiveDataProvider */

$this->layout = 'column2';
$this->pageTitle = app()->name . ' - Users';
$this->breadcrumbs = array(
    'Users',
);

$this->menu = array(
    array('label'=>'List User', 'url'=>array('index'), 'active' => true),
    array('label' => 'Create User', 'url' => array('create')),
);

$columns = array(
    array('id' => 'selected', 'class' => 'CCheckBoxColumn'),
    array('filter' => false, 'name' => 'first_name', 'header' => 'Name', 'type' => 'raw', 'value' => 'User::model()->findByPk($data->id)->getFullName()', 'htmlOptions' => array('style' => 'width: 30%')),
    array('name' => 'email', 'header' => 'Email', 'htmlOptions' => array('style' => 'width: 45%')),
    array('filter' => false, 'name' => 'last_login', 'header' => 'Last Login', 'type' => 'raw', 'value' => 'Shared::formatShortUSDate($data->last_login)', 'htmlOptions' => array('style' => 'width: 25%')),
    array('type' => 'raw', 'value' => array($this, 'renderButtons')),
);

$dataArray = array(
    'id'                => 'user-gridview',
    'filter'            => $model,
    'filterPosition'    => 'hide',
    'filterSelector'    => '#email-filter',
    'columns'           => $columns,
    'template'          => "{items}\n{pager}",
    'dataProvider'      => $dataProvider,
    'itemsCssClass'     => 'table table-bordered table-striped table-hover',
    'selectableRows'    => 2,
);
?>

<div class="col-md-12">
    <h1 class="page-header">Users</h1>

    <?php echo CHtml::textField('User[email]','',array('id' => 'email-filter', 'class' => 'form-action')); ?>
    <?php $this->widget('app.widgets.GridView.GridView', $dataArray); ?>
</div>