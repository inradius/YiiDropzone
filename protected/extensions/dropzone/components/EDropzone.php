<?php

class EDropzone extends CWidget
{
    public $name = false;
    public $model = false;
    public $attribute = false;
    public $options = array();
    public $url = false;
    public $mimeTypes = array();
    public $events = array();
    public $htmlOptions = array();
    public $customStyle = false;

    public function run()
    {
        if(!$this->url || $this->url == '')
            $this->url = Yii::app()->createUrl('site/upload');

        if(!$this->name && ($this->model && $this->attribute) && $this->model instanceof CModel)
            $this->name = CHtml::activeName($this->model, $this->attribute);

        $this->mimeTypes = CJavaScript::encode($this->mimeTypes);

        $onEvent = '';
        foreach($this->events as $event => $func) {
            $onEvent .= "this.on('{$event}', function(param,param2,param3){{$func}});";
        }

        $options = CMap::mergeArray(array(
            'url' => $this->url,
            'parallelUploads' => 5,
            'paramName' => $this->name,
            'addRemoveLinks' => true,
            'accept' => "js:function(file,done){if(jQuery.inArray(file.type,{$this->mimeTypes})==-1){done('File type not allowed.')}else{done();}}",
            'init' => "js:function(){{$onEvent}}"
        ), $this->options);

        $options = CJavaScript::encode($options);

        $script = "Dropzone.options.{$this->name} = {$options}";

        $this->registerAssets();
        cs()->registerScript(__CLASS__ . '#' . $this->getId(), $script, CClientScript::POS_LOAD);
    }

    private function registerAssets()
    {
        $basePath = Yii::getPathOfAlias('ext.dropzone.assets');
        $baseUrl = Yii::app()->getAssetManager()->publish($basePath, false, 1, YII_DEBUG);
        cs()->registerScriptFile("{$baseUrl}/js/dropzone.js", CClientScript::POS_BEGIN);
        cs()->registerCssFile("{$baseUrl}/css/dropzone.css");
    }
}