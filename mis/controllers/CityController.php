<?php
/**
 * Created by PhpStorm.
 * User: panbook
 * Date: 10/15/15
 * Time: 10:10 AM
 */

namespace mis\controllers;


use common\models\ApiUtils;
use common\models\C2BConfig;
use common\models\City;
use common\models\Paging;
use common\models\Shop;
use yii\filters\PageCache;

class CityController extends MisBaseController{


    /**
     * 获取销售顾问列表
     * */
    public function actionList()
    {
        //过滤条件
        $request = $_REQUEST;
        $arrFilter = isset($request['query'])?$request['query']:[];
        foreach($arrFilter as $key=>$val){
            if(empty($val))
                unset($arrFilter[$key]);
        }
        $intPage = isset($request['p'])?intval($request['p']):1;
        $filter['orderby'] =  $orderBy = isset($request['order'])?trim($request['order']):'id';
        $filter['sortway'] = $sortWay = isset($request['sortway'])?trim($request['sortway']):'DESC';
        $intPageSize = PAGESIZE;
        $arrList = [];
        $total = 0;

        if (!empty($arrFilter)) {
            $filter = array_merge($filter, $arrFilter);
            if (!empty($arrFilter['id'])) {
                $arrList = City::find()->where(['id' => intval($arrFilter['id'])])->asArray()->all();
                $total = 1;
            } elseif (!empty($arrFilter['name'])) {
                $arrList = City::find()->where(['name' => $arrFilter['name']])->asArray()->all();
                $total = City::find()->where(['name' => $arrFilter['name']])->count();
            }
        } else {
            $arrList = City::find()
                ->where(['is_del' => 0])
                ->orderBy($orderBy . ' ' . $sortWay)
                ->limit($intPageSize)
                ->offset(($intPage - 1) * $intPageSize)
                ->asArray()
                ->all();
            $total = City::find()->where(['is_del' => 0])->count();
        }

        if (!empty($arrList)) {
            foreach ($arrList as $k => $one) {
                $arrList[$k]['status'] = C2BConfig::$arrCityStatus[$one['status']];
            }
        }

        $url = \Yii::$app->request->baseUrl . '?' . ApiUtils::getMapping($request,'p') . '&';
        $objPage = new Paging($total, $intPageSize, $url , $intPage);
        $pageLink = $objPage->output();

        $arrTplData['adminList'] = $arrList;

        $arrTplData['arrPager'] = array(
            'count'     => $total,
            'pagesize'  => $intPageSize,
            'page'      => $intPage,
            'pagelink'  => $pageLink,
        );
        $arrTplData['filter'] = $filter;

        return $this->render('city_list.tpl',$arrTplData);
    }


    /**
     * 查看销售顾问信息
     * @return string
     */
    public function actionView(){
        $request = $_REQUEST;
        $id = $request['id'];
        $info = City::find()->where(['id' => $id])->asArray()->one();
        $arrTplData['info'] = $info;
        $arrTplData['arrStatus'] = C2BConfig::$arrCityStatus;

        return $this->render('view.tpl', $arrTplData);
    }


    /**
     * 编辑一个销售顾问
     * @return string
     */
    public function actionEdit(){
        $request = $_REQUEST;
        $query = !empty($request['query']) ? $request['query'] : [];
        if(!empty($query)){
            $objProduct = City::findOne(['id' => $request['id']]);
            $objProduct->attributes = $query;
            $objProduct->update_time = date("Y-m-d H:i:s");
            $objProduct->is_del = 0;
            $ret = $objProduct->save();
            if($ret){
                $this->redirect('/seller/list');
            }else{
                echo "<script>alert('添加失败');window.location.href='/seller/list';</script>";
            }
        }else{
            $id = $request['id'];
            $shop = City::find()->where(['id' => $id])->asArray()->one();
            $arrTplData['info'] = $shop;
            $arrTplData['action'] = 1;
            $arrTplData['arrStatus'] = C2BConfig::$arrCityStatus;
            return $this->render('view.tpl', $arrTplData);
        }
    }


    /**
     * 添加一个销售顾问
     * @return string
     */
    public function actionAdd(){
        $request = $_REQUEST;
        $query = !empty($request['query'])?$request['query']:[];
        if(!empty($query)){
            $objProduct = new City();
            $objProduct->attributes = $query;
            $objProduct->update_time = date("Y-m-d H:i:s");
            $objProduct->create_time = date("Y-m-d H:i:s");
            $objProduct->is_del = 0;
            $ret = $objProduct->save();
            if($ret){
                $this->redirect('/seller/list');
            }else{
                echo "<script>alert('添加失败');window.location.href='/seller/add';</script>";
            }
        }else{
            $arrTplData['action'] = 2;
            $arrTplData['arrStatus'] = C2BConfig::$arrCityStatus;
            return $this->render('view.tpl', $arrTplData);
        }
    }


    /**
     * 逻辑删除销售顾问
     * @return string
     */
    public function actionDel(){
        $request = $_REQUEST;
        $id = $request['id'];
        $ret = City::updateAll(['is_del' => 1, 'update_time' => date('Y-m-d H:i:s')], ['id' => $id]);

        if ($ret) {
            echo 'success';
        } else {
            echo 'failed';
        }
    }



}