<?php

$appRoot = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..';

return array(
    'aliases' => array(
        'app' => realpath($appRoot . '/extensions/app'),
    ),
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Yii Skeleton App',
    'preload' => array('app', 'less', 'log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'ext.mailer.components.*',
    ),
    'modules' => array(
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'password',
            'generatorPaths' => array(
                'bootstrap.gii',
            ),
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1', '*'),
        ),
    ),
    // application components
    'components' => array(
        'app' => array(
            'class' => 'ext.app.components.app',
        ),
        'clientScript' => array(
            'scriptMap' => array(
                'jquery.js' => false,
            ),
        ),
        'user' => array(
            'class' => 'WebUser',
            'allowAutoLogin' => true,
        ),
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => array(
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=testdrive',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
        ),
        'session' => array(
            'class' => 'system.web.CCacheHttpSession',
            'cacheID' => 'sessionCache',
            'timeout' => 3600,
        ),
        'sessionCache' => array(
            'class' => 'CFileCache',
        ),
        'cache' => array(
            'class' => 'CFileCache',
        ),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'facebook' => array(
            'class' => 'ext.facebook.components.SFacebook',
            'appId' => 'FACEBOOK_APP_ID',
            'secret' => 'FACEBOOK_SECRET_KEY',
        ),
        'google' => array(
            'class' => 'ext.google.components.SGoogle',
            'clientId' => 'GOOGLE_CLIENT_ID',
            'clientSecret' => 'GOOGLE_CLIENT_SECRET',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            // uncomment the following to show log messages on web pages
            /*
              array(
              'class'=>'CWebLogRoute',
              ),
             */
            ),
        ),
        'less' => array(
            'class' => 'ext.less.components.LessCompiler',
            'forceCompile' => true, // set false on production
            'paths' => array(
                'css/system.less' => 'css/system.css',
            ),
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        // this is used in contact page
        'adminEmail' => 'root@localhost',
        'adminEmailName' => 'root',
        'mailer' => array(
            'viewPath' => 'application.views.mail',
            'layoutPath' => 'application.views.layouts',
            'baseDirPath' => 'webroot.images.mail',
            'layout' => 'mail',
            'CharSet' => 'UTF-8',
            'AltBody' => Yii::t('YiiMailer', 'You need an HTML capable viewer to read this message.'),
            'language' => array(
                'authenticate' => Yii::t('YiiMailer', 'SMTP Error: Could not authenticate.'),
                'connect_host' => Yii::t('YiiMailer', 'SMTP Error: Could not connect to SMTP host.'),
                'data_not_accepted' => Yii::t('YiiMailer', 'SMTP Error: Data not accepted.'),
                'empty_message' => Yii::t('YiiMailer', 'Message body empty'),
                'encoding' => Yii::t('YiiMailer', 'Unknown encoding: '),
                'execute' => Yii::t('YiiMailer', 'Could not execute: '),
                'file_access' => Yii::t('YiiMailer', 'Could not access file: '),
                'file_open' => Yii::t('YiiMailer', 'File Error: Could not open file: '),
                'from_failed' => Yii::t('YiiMailer', 'The following From address failed: '),
                'instantiate' => Yii::t('YiiMailer', 'Could not instantiate mail function.'),
                'invalid_address' => Yii::t('YiiMailer', 'Invalid address'),
                'mailer_not_supported' => Yii::t('YiiMailer', ' mailer is not supported.'),
                'provide_address' => Yii::t('YiiMailer', 'You must provide at least one recipient email address.'),
                'recipients_failed' => Yii::t('YiiMailer', 'SMTP Error: The following recipients failed: '),
                'signing' => Yii::t('YiiMailer', 'Signing Error: '),
                'smtp_connect_failed' => Yii::t('YiiMailer', 'SMTP Connect() failed.'),
                'smtp_error' => Yii::t('YiiMailer', 'SMTP server error: '),
                'variable_set' => Yii::t('YiiMailer', 'Cannot set or reset variable: ')
            ),
        ),
    ),
);
