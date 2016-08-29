<?php

namespace orlac\sortable;


use Yii;
use yii\grid\ActionColumn as BaseActionColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use orlac\sortable\assets\SortableAsset;

class ActionColumn extends BaseActionColumn
{
    /**
     * @var string
     */
    public $template = '{up} {down} {view} {update} {delete}';

    public $buttonOptions = [
        'class' => 'btn btn-default btn-xs'
    ];


    public function init(){
        parent::init();
        if (!Yii::$app->request->isAjax) {
            $this->registerJs();
        }
    }


    protected function initDefaultButtons()
    {

        parent::initDefaultButtons();
        
        if (!isset($this->buttons['up'])) {
            $this->buttons['up'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('sortable', 'Up'),
                    'aria-label' => Yii::t('sortable', 'Up'),
                    'data-method' => 'post',
                    'data-pjax' => '1',
                    'over-sortable' => '1',
                ], $this->buttonOptions);
                return Html::a('<span class="glyphicon glyphicon-arrow-up"></span>', $url, $options);
            };
        }

        if (!isset($this->buttons['down'])) {
            $this->buttons['down'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('sortable', 'Down'),
                    'aria-label' => Yii::t('sortable', 'Down'),
                    'data-method' => 'post',
                    'data-pjax' => '1',
                    'over-sortable' => '1',
                ], $this->buttonOptions);
                return Html::a('<span class="glyphicon glyphicon-arrow-down"></span>', $url, $options);
            };
        }
    }

    protected function renderDataCellContent($model, $key, $index){
        $this->initVisibleButtons($model, $key, $index);
        $html = parent::renderDataCellContent($model, $key, $index);
        return Html::tag('div', $html, [
                'class' => 'btn-group',
                'role' => 'group'
            ]);
    }

    protected function initVisibleButtons($model, $key, $index){
        $this->visibleButtons['up'] = $model->getIsUp();
        $this->visibleButtons['down'] = $model->getIsDown();
    }


    protected function registerJs(){
        SortableAsset::register(Yii::$app->view);
        Yii::$app->view->registerJs("$.fn.overSortable.bind('".$this->grid->id."')", \yii\web\View::POS_READY, 'over.sortable');
    }
    
}
