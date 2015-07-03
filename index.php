<?php

$dirname = dirname(__FILE__);
$shortcuts = $dirname . '/protected/helpers/shortcuts.php';

if(file_exists($dirname . '/protected/config/production.php')) {
    $config = $dirname . '/protected/config/production.php';
    $yii = $dirname . '/vendor/yiisoft/yii/framework/yiilite.php';
} else {
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);
    $yii = $dirname . '/vendor/yiisoft/yii/framework/yii.php';
    if(file_exists($dirname . '/protected/config/dev.php')) {
        $config = $dirname . '/protected/config/dev.php';
    } else {
        $config = $dirname . '/protected/config/main.php';
    }
}

require_once($yii);
require_once($shortcuts);
Yii::createWebApplication($config)->run();