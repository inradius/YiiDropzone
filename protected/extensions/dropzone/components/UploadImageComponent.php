<?php

class UploadImageComponent
{
    public static $configuration = array(
        'default' => array(
            'forceCrop' => false,
            'base64Response' => false,
            'maxDimensions' => array('width' => 1920, 'height' => 1080),
            'previewDimensions' => array('width' => 125, 'height' => 100),
            'requireMinSize' => false,
            'extensions' => array("jpeg", "jpg", "png"),
            'sizeLimit' => 5242880 // 5MB
        ));

    function __construct($config = 'default')
    {
        $this->config = self::getConfig($config);
        $this->file = new AjaxUploadedFile();
    }

    public static function getConfig($config)
    {
        $default = self::$configuration['default'];
        if (isset(self::$configuration[$config]) && $config != 'default'){
            foreach (self::$configuration[$config] as $attr => $val){
                $default[$attr] = $val;
            }
        }
        return $default;
    }

    public function handleUpload(&$model)
    {
        if (!$this->file) {
            return array('error' => 'No files were uploaded.');
        }

        $size = $this->file->getSize();

        if ($size == 0) {
            return array('error' => 'File is empty');
        }

        if ($size > $this->config['sizeLimit']) {
            return array('error' => 'File is too large');
        }

        $pathinfo = pathinfo($this->file->getName());

        $ext = $pathinfo['extension'];

        if ($this->config['extensions'] && !in_array(strtolower($ext), $this->config['extensions'])) {
            $these = implode(', ', $this->config['extensions']);
            return array('error' => 'File has an invalid extension, it should be one of ' . $these . '.');
        }

        if ($this->file->save($model, $pathinfo, $this->config)) {

            $response = array(
                'success' => true
            );

            if ($this->config['requireMinSize']){
                if ($model->getWidth() < $this->config['previewDimensions']['width'] || $model->getHeight() < $this->config['previewDimensions']['height']){

                    $response['error'] = 'Uploaded image is too small. Please get one in higher resolution (at least ' . $this->config['previewDimensions']['width'] . 'x' . $this->config['previewDimensions']['height'] . ')';
                    $response['success'] = false;
                }
            }

            // saved, lets see if it needs additional processing
            if ($response['success'] && $this->config['forceCrop'] && ($model->getWidth() > $this->config['cropDimensions']['width'] || $model->getHeight() > $this->config['cropDimensions']['height'])){
                $response = array(
                    'success' => true,
                    'url' => $model->getUrl($this->config['maxDimensions']['width'], $this->config['maxDimensions']['height'], true),
                    'previewUrl' => $model->getUrl($this->config['previewDimensions']['width'], $this->config['previewDimensions']['height'], true),
                    'crop' => 1 // make it crop
                );

            } elseif ($response['success']){
                $response = array(
                    'success' => true,
                    'url' => $model->getUrl($this->config['previewDimensions']['width'], $this->config['previewDimensions']['height']),
                    'previewUrl' => $model->getUrl($this->config['previewDimensions']['width'], $this->config['previewDimensions']['height'], true),
                );
            }

            $response['fullUrl'] = $model->getUrl();
            $response['imageId'] = $model->long_id;

            if ($this->config['base64Response']){
                $response['base64img'] = $model->getBase64Src();
            }

            $response['width'] = $model->getWidth();
            $response['height'] = $model->getHeight();

            // notice that there might be no image id yet, only after upload
            return $response;
        } else {
            return array('error' => 'Could not save uploaded file.' .
                'The upload was cancelled, or server error encountered');
        }
    }
}

class AjaxUploadedFile
{
    function save(&$model, $fileinfo, $config)
    {
        $input = fopen($_FILES['file']['tmp_name'], "r");
        $temp = tmpfile();
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);
        if ($realSize != $this->getSize())
            return false;

        $tempFile = Yii::getPathOfAlias('application.runtime.temp') . DIRECTORY_SEPARATOR . $this->getName();
        $target = fopen($tempFile, "w");
        fseek($temp, 0, SEEK_SET);
        stream_copy_to_stream($temp, $target);
        fclose($target);
        if (is_object($model)) {
            $model->image_file_size = $this->getSize();
            $model->image_file_name = preg_replace("/[^A-Za-z0-9-_\.]/", '', str_replace(" ", "-", strtolower($this->getName())));
            $model->image_updated = Shared::toDatabase(time());

            switch ($fileinfo['extension']) {
                case 'png':
                    $model->image_file_type = 'image/png';
                    break;
                case 'jpg':
                case 'jpeg':
                    $model->image_file_type = 'image/jpeg';
                    break;
                case 'gif':
                    $model->image_file_type = 'image/gif';
            }

            $model->image_file_content = file_get_contents($tempFile);

            $original = imagecreatefromstring($model->image_file_content);
            imagealphablending($original, false);
            imagesavealpha($original, true);

            $canvas = $model->resize($config['maxDimensions']['width'], $config['maxDimensions']['height'], $original);
            $model->image_resolution = imagesx($canvas) . 'x' . imagesy($canvas);

            if (file_exists($tempFile)){
                unlink($tempFile);
            }
        }

        return true;
    }

    function getName()
    {
        return $_FILES['file']['name'];
    }

    function getSize()
    {
        if (isset($_FILES['file']['size'])) {
            return (int) $_FILES['file']['size'];
        } else {
            throw new Exception('Getting content length is not supported.');
        }
    }
}