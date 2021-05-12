# Yii2-sortable

This extension for sorting ActiveRecord models.

## Install

Via Composer

``` bash
$ composer require orlac/yii2-sortable
```

## Usage

Model:
``` php
use orlac\sortable\SortableBehavior;

public function behaviors(){
        return [
            [
                'class' => SortableBehavior::className(),
            ],
        ];
    }
    
public function behaviors(){
        return [
            [
                'class' => SortableBehavior::className(),
                'scope' => function($model){
                    return Model::find()->addWere('...');
                }
            ],
        ];
    }
    
public function behaviors(){
        return [
            [
                'class' => SortableBehavior::className(),
                'scope' => function($model){
                    return Model::find()->addWere('...');
                },
                'sortColumn' => 'sort'
            ],
        ];
    }    
```
Controller
```php
use orlac\sortable\UpAction;
use orlac\sortable\DownAction;

....

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'up' => ['POST'],
                    'down' => ['POST'],
                ],
            ],
        ];
    }

    public function actions(){
        return [
            'up' => [
                'class' => UpAction::className(),
                'modelClass' => Model::className()
            ],
            'down' => [
                'class' => DownAction::className(),
                'modelClass' => Model::className()
            ],
        ];
    }
```

View
```php
use orlac\sortable\ActionColumn;

<?php Pjax::begin(); ?>    <?= GridView::widget([
        ...
        'columns' => [
            ...
            [
                'class' => ActionColumn::className(),
            ],
            [
                'class' => ActionColumn::className(),
                'template' => ($dataProvider->sort->getAttributeOrder('priority')) ? '{up} {down} {update} {delete}' : '{update} {delete}',
                'order' => ($dataProvider->sort->getAttributeOrder('priority') === SORT_DESC) ? SORT_DESC : SORT_ASC,
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?>
```
