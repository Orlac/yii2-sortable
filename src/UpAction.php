<?php

namespace orlac\sortable;

use yii\rest\Action;
use Yii;

/**
* 
*/
class UpAction extends Action{
    
    public $modelClass;

    public $findFn;

    // public $serializer = 'yii\rest\Serializer';

    public function run($id){
        if($this->findFn){
            $model = call_user_func($this->findFn, $id);    
        }else{
            $class = $this->modelClass;
            $model = $class::find()->where(['id' => $id])->one();    
        }
        $model->up()->save();
        // return $this->serializeData($model);
    }


    protected function serializeData($data){
        return Yii::createObject($this->serializer)->serialize($data);
    }
}