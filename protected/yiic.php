<?php

$dirname = dirname(__FILE__);
$yiic = $dirname . '/../vendor/yiisoft/yii/framework/yiic.php';

if(file_exists($dirname . '/config/console-production.php')) {
    $config = $dirname . '/config/console-production.php';
} else {
    if(file_exists($dirname . '/config/console-dev.php')) {
        $config = $dirname . '/config/console-dev.php';
    } else {
        $config = $dirname . '/config/console.php';
    }
}

require_once($yiic);