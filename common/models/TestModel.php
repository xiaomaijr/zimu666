<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao-pc
 * Date: 2016/4/22
 * Time: 11:57
 */

namespace common\models;


use yii\base\Component;

class TestModel extends Component
{
    private $id;

    private $name;

    public function setId($id){
        $this->id = $id;
    }

    public function getId(){
        return $this->id;
    }
    public function setName($name){
        $this->name = $name;
    }

    public function getName(){
        return $this->name;
    }
}