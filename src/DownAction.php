<?php

namespace orlac\sortable;

use yii\rest\Action;

/**
* 
*/
class DownAction extends Action{
    
    public $modelClass;

    public $findFn;

    function run($id){
        if($this->findFn){
            $model = call_user_func($this->findFn, $id);    
        }else{
            $class = $this->modelClass;
            $model = $class::find()->where(['id' => $id])->one();    
        }
        $model->down()->save();
        // return $model;
    }
}