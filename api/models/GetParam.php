<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao-pc
 * Date: 2016/3/23
 * Time: 15:39
 */

namespace api\models;


use yii\base\Component;

class GetParam extends Component
{
    public function setParam($param){
        $this->param = $param;
    }

    public function setRequest($request){
        $this->request = $request;
    }

    public function getStrParam($obj){
        return $obj->getStrParam($this->param, $this->request);
    }
}