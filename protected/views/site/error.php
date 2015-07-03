<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle = app()->name . ' - Error';
$this->breadcrumbs=array(
	'Error',
);
?>

<h1>Error <?php echo $code; ?></h1>

<p><?php echo $message; ?></p>