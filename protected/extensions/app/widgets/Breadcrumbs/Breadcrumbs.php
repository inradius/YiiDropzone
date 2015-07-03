<?php
/**
 * Breadcrumbs class file.
 * This extends the CBreadcrumbs so I can add a bootstrap css class. Nothing special.
 *
 * @author Travis Stroud <travis@travisstroud.com>
 */

Yii::import('zii.widgets.CBreadcrumbs');

class Breadcrumbs extends CBreadcrumbs
{
    public function run()
    {
        if(empty($this->links))
            return;

        echo CHtml::openTag('div', array('class' => 'col-md-12'));
        echo CHtml::openTag($this->tagName,$this->htmlOptions)."\n";
        $links=array();
        if($this->homeLink===null)
            $links[]=CHtml::link(Yii::t('zii','Home'),Yii::app()->homeUrl);
        elseif($this->homeLink!==false)
            $links[]=$this->homeLink;
        foreach($this->links as $label=>$url)
        {
            if(is_string($label) || is_array($url))
                $links[]=strtr($this->activeLinkTemplate,array(
                    '{url}'=>CHtml::normalizeUrl($url),
                    '{label}'=>$this->encodeLabel ? CHtml::encode($label) : $label,
                ));
            else
                $links[]=str_replace('{label}',$this->encodeLabel ? CHtml::encode($url) : $url,$this->inactiveLinkTemplate);
        }
        echo implode($this->separator,$links);
        echo CHtml::closeTag($this->tagName);
        echo CHtml::closeTag('div');
    }
}