<?php

namespace orlac\sortable;

use yii\base\Behavior;

class SortableBehavior extends Behavior
{

    public $sortColumn = 'sort';
    public $scope = null;


    public function events()
    {
        return [
            \yii\db\ActiveRecord::EVENT_AFTER_DELETE => 'fixSort',
            \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'last',
            \yii\db\ActiveRecord::EVENT_AFTER_UPDATE => 'fixSort',
        ];
    }

    public function getIsDown()
    {
        return !!$this->getPrevDown();
    }

    public function getIsUp()
    {
        return !!$this->getPrevUp();
    }

    public function down()
    {
        $prev = $this->getPrevDown();
        if ($prev) {
            $prev->{$this->sortColumn}--;

            $class = get_class($this->owner);
            $class::updateAll([$this->sortColumn => $prev->{$this->sortColumn}], 'id = ' . $prev->id);
            // $prev->save();    
        }


        $this->owner->{$this->sortColumn}++;
        return $this->owner;
        // $this->save();
    }

    public function up()
    {
        $prev = $this->getPrevUp();
        if ($prev) {
            $prev->{$this->sortColumn}++;

            $class = get_class($this->owner);
            $class::updateAll([$this->sortColumn => $prev->{$this->sortColumn}], 'id = ' . $prev->id);

            // $prev->save();    
        }


        $this->owner->{$this->sortColumn}--;
        return $this->owner;
    }

    public function last()
    {
        $last = $this->getLastSort();
        $this->owner->{$this->sortColumn} = ($last) ? $last->{$this->sortColumn} + 1 : 0;
        return $this;
        // $this->save();
    }

    public function fixSort()
    {
        $data = $this->getQueryObject()
            ->orderBy([$this->sortColumn => SORT_ASC])
            ->all();
        $class = get_class($this->owner);
        foreach ($data as $key => $value) {
            $class::updateAll([$this->sortColumn => $key], 'id = ' . $value->id);
        }
    }

    protected function getPrevDown()
    {
        return $this->getQueryObject()
            ->orderBy([$this->sortColumn => SORT_ASC])
            ->andWhere($this->sortColumn . ' > ' . $this->owner->{$this->sortColumn})
            ->limit(1)
            ->one();
    }

    protected function getPrevUp()
    {
        return $this->getQueryObject()
            ->orderBy([$this->sortColumn => SORT_DESC])
            ->andWhere($this->sortColumn . ' < ' . $this->owner->{$this->sortColumn})
            ->limit(1)
            ->one();
    }

    protected function getLastSort()
    {
        return $this->getQueryObject()
            ->orderBy([$this->sortColumn => SORT_DESC])
            ->limit(1)
            ->one();
    }

    private function getQueryObject()
    {
        if (!$this->scope) {
            $class = get_class($this->owner);
            return $class::find();
        } else {
            $query = call_user_func($this->scope, $this->owner);
            return $query;
        }
    }
}
