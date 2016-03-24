<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao-pc
 * Date: 2016/3/23
 * Time: 15:18
 */

namespace api\models;


interface ParamsFilter
{
//    public function filter($param, $request);

    public function getStrParam($param, $request);
}