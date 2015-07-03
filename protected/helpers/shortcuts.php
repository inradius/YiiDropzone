<?php

// Set of function, which will simplify typing

/**
 * This is the shortcut to Yii::app()
 */
function app() {
    return Yii::app();
}

/**
 * This is the shortcut to Yii::app()->clientScript
 */
function cs() {
    // You could also call the client script instance via Yii::app()->clientScript
    // But this is faster
    return Yii::app()->getClientScript();
}

/**
 * This is the shortcut to CHtml::encode
 */
function h($text) {
    return htmlspecialchars($text, ENT_QUOTES, Yii::app()->charset);
}

/**
 * This is the shortcut to Yii::app()->createUrl()
 */
function url($route, $params = array(), $ampersand = '&') {
    return Yii::app()->createUrl($route, $params, $ampersand);
}

function absUrl($route, $params = array(), $schema = '', $ampersand = '&') {
    return Yii::app()->createAbsoluteUrl($route, $params, $schema, $ampersand);
}

/**
 * This is the shortcut to Yii::app()->request->baseUrl
 * If the parameter is given, it will be returned and prefixed with the app baseUrl.
 */
function bu($url = null) {
    static $baseUrl = null;
    if ($baseUrl === null)
        $baseUrl = Yii::app()->getRequest()->getBaseUrl();
    return $url === null ? $baseUrl : $baseUrl . '/' . ltrim($url, '/');
}

/**
 * What method to use to send the emails.
 */
function getPhpMailer() {
    // SMTP is used for windows machines, use php mail otherwise
    $mail = new JPhpMailer;
    //$mail->IsSendmail();
    /* $mail->IsSMTP();
      $mail->Port = Yii::app()->params['email']['smtpPort'];
      $mail->Host = Yii::app()->params['email']['smtpHost'];
      $mail->SMTPAuth = true;
      //$mail->SMTPSecure = 'ssl';
      $mail->Username = Yii::app()->params['email']['smtpUser'];
      $mail->Password = Yii::app()->params['email']['smtpPassword']; */
    return $mail;
}

?>
