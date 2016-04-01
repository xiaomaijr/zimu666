<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao-pc
 * Date: 2016/4/1
 * Time: 14:42
 */

namespace common\models;


use yii\db\ActiveRecord;

class BaseModel extends ActiveRecord
{
    /*
     *根据指定条件批量返回数据,至少二维数据
     */
    public static function getDataByConditions($condition = [], $order = 'id desc', $limit = 10, $offset = 1, $select = '*'){
        $strConditions = [];
        if(!empty($condition)){
            foreach($condition as $key=>$con){
                if(is_int($key) && is_string($con)){
                    array_push($strConditions, $con);
                    unset($condition[$key]);
                }
            }
        }
        $query = self::find()->select($select)->where($condition);
        if(!empty($strConditions)){
            foreach($strConditions as $strCon){
                $query->andWhere($strCon);
            }
        }
        $query->orderBy($order);
        if($limit && $offset){
            $query->limit($limit)->offset(($offset - 1) * $limit);
        }elseif($limit){
            $query->limit($limit);
        }
//        echo $query->createCommand()->getRawSql();exit;
        $infos = $query->asArray()->all();
        return $infos?$infos:[];
    }
    /*
     * 根据id返回单一数据
     */
    public static function getDataByID($id, $param = 'id'){
        if(!$id){
            return [];
        }
        $info = self::find()
            ->where([$param => $id])
            ->asArray()
            ->one();
        return $info?$info:[];
    }
    /*
     * 返回总数
     */
    public static function getCountByCondition($condition = []){
        $strConditions = [];
        if(!empty($condition)){
            foreach($condition as $key=>$con){
                if(is_int($key) && is_string($con)){
                    array_push($strConditions, $con);
                    unset($condition[$key]);
                }
            }
        }
        $query = self::find()->where($condition);
        if(!empty($strConditions)){
            foreach($strConditions as $strCon){
                $query->andWhere($strCon);
            }
        }
        $total = $query->count();
        return $total?$total:0;
    }
    /*
     * 判断是否存在满足条件的数据
     * @param $condition array
     * return bool exists true not else false
     */
    public static function checkExistByCondition($condition){
        $strConditions = [];
        if(!empty($condition)){
            foreach($condition as $key=>$con){
                if(is_int($key) && is_string($con)){
                    array_push($strConditions, $con);
                    unset($condition[$key]);
                }
            }
        }
        $query = self::find()->where($condition);
        if(!empty($strConditions)){
            foreach($strConditions as $strCon){
                $query->andWhere($strCon);
            }
        }
        return $query->exists();
    }
}