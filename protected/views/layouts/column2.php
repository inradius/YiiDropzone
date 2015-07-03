<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="col-md-3 col-md-push-9">
    <?php $this->widget('zii.widgets.CMenu', array(
        'items'         => $this->menu,
        'htmlOptions'   => array('class' => 'nav nav-pills nav-stacked'),
    )); ?>
</div>

<div class="col-md-9 col-md-pull-3">
    <?php echo $content; ?>
</div>
<?php $this->endContent(); ?>