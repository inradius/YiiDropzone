<?php
/**
 * GridView class file.
 * This extends the CGridView so I can do some more things.
 *
 * @author Travis Stroud <travis@travisstroud.com>
 */

Yii::import('zii.widgets.grid.CGridView');

class GridView extends CGridView
{
    const FILTER_POS_HIDDEN='hide';

    public $selVar;

    public $selBaseScriptUrl;

    /**
     * Register the js if selectableRows is set.
     */
    public function init()
    {
        if($this->selectableRows > 0) {
            if(empty($this->selVar)) {
                $id = $this->dataProvider->getId();
                if(empty($id)) $id = $this->id;
                $this->selVar = (empty($id)) ? 'sel' : $id.'_sel';
            }
            if($this->selBaseScriptUrl===null)
                $this->selBaseScriptUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('ext.app.widgets.GridView.assets'));

        }
        parent::init();
    }

    /**
     * Overwrite the default table so that my loader overlay works.
     */
    public function renderItems()
    {
        if($this->dataProvider->getItemCount()>0 || $this->showTableOnEmpty)
        {
            echo "<div class=\"table-holder\">";
            echo "<div class=\"loader-overlay\"></div>";
            echo "<table class=\"{$this->itemsCssClass}\">\n";
            $this->renderTableHeader();
            ob_start();
            $this->renderTableBody();
            $body=ob_get_clean();
            $this->renderTableFooter();
            echo $body;
            echo "</table>";
            echo "</div>";
        }
        else
            $this->renderEmptyText();
    }

    public function renderFilter()
    {
        if($this->filterPosition===self::FILTER_POS_HIDDEN)
            return;

        parent::renderFilter();
    }

    public function registerClientScript()
    {
        parent::registerClientScript();

        if($this->selectableRows > 0) {
            $id = $this->getId();

            $options=array(
                'selVar'=>$this->selVar,
            );

            $options=CJavaScript::encode($options);

            $cs=Yii::app()->getClientScript();
            $cs->registerScriptFile($this->selBaseScriptUrl.'/jquery.selgridview.js',CClientScript::POS_END);
            $cs->registerScript(__CLASS__.'#'.$id.'-sel',"jQuery('#$id').selGridView($options);");
        }
    }

    protected function initColumns()
    {
        parent::initColumns();

        if($this->selectableRows == 0) return;

        if($this->dataProvider instanceOf CActiveDataProvider) {
            $primaryKey = '$data->primaryKey';
        } else {
            $primaryKey = '$data["'.$this->dataProvider->keyField.'"]';
        }

        $checkedExpression = 'isset($_GET["'.$this->selVar.'"]) ? in_array('.$primaryKey.', is_array($_GET["'.$this->selVar.'"]) ? $_GET["'.$this->selVar.'"] : array($_GET["'.$this->selVar.'"])) : false;';

        foreach($this->columns as $col) {
            if($col instanceof CCheckBoxColumn) {
                $col->checked = $checkedExpression;
                $col->init();
                return;
            }
        }

        $checkboxColumn = new CCheckBoxColumn($this);
        $checkboxColumn->checked = $checkedExpression;
        $checkboxColumn->htmlOptions = array('style'=>'display:none');
        $checkboxColumn->headerHtmlOptions = array('style'=>'display:none');
        $checkboxColumn->init();

        $this->columns[] = $checkboxColumn;
    }
}