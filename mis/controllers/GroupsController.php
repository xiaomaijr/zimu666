<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao
 * Date: 15/6/24
 * Time: 下午3:34
 */
namespace mis\controllers;

use common\models\ApiBaseException;
use common\models\ApiErrorDescs;
use common\models\ApiUtils;
use common\models\ArrayUtil;
use common\models\City;
use common\models\DriverInfo;
use common\models\DriverPassport;
use common\models\Paging;
use common\models\Groups;
use common\models\ServiceSchedule;
use common\models\Vehicle;
use common\models\VehicleBrand;
use mis\models\Util;
use yii\web\Controller;

class GroupsController extends MisBaseController{

    public $enableCsrfValidation = false;



    /*
     * 四S店详情页
     */

    public function actionList(){
        $data = [];
        $request = $_REQUEST;
        $query = isset($request['query'])?$request['query']:[];
        foreach($query as $k=>$v){
            if(empty($v))
                unset($query[$k]);
        }
        $filter['orderby'] =  $orderBy = isset($request['order'])?trim($request['order']):'id';
        $filter['sortway'] = $sortWay = isset($request['sortway'])?trim($request['sortway']):'DESC';
        $filter = array_merge($filter, $query);
        $page = isset($request['p'])&&$request['p']?intval($request['p']):1;
        $offset = PAGESIZE;
        if(!empty($query)){
            $condition = [];
            if(!empty($query['id'])){
                $condition['id'] = intval($query['id']);
            }
            if(!empty($query['name'])){
                $condition['name'] = trim($query['name']);
            }
            $total = Groups::find()
                ->where($condition)
                ->count();
            $groupList = Groups::find()
                ->where($condition)
                ->orderby($orderBy . ' ' . $sortWay)
                ->limit($offset)
                ->offset(($page-1)*$offset)
                ->asArray()
                ->all();

        }else{
            $objGroups = new Groups();
            $total = $objGroups->find()
                ->count();
            $groupList = $objGroups->find()
                ->orderBy($orderBy . ' ' . $sortWay)
                ->limit($offset)
                ->offset(($page-1)*$offset)
                ->asArray()->all();
        }

        if($groupList){
            $upperIds = [];
            $brandIds = '';
            foreach($groupList as $list){
                if(!empty($list['upper_id'])&&!in_array($list['upper_id'],$upperIds)){
                    array_push($upperIds,$list['upper_id']);
                }
                if(!empty($list['brand_ids'])){
                    $brandIds .= $list['brand_ids'];
                }
            }
            $data['upperInfo'] = Groups::getInfoByIds($upperIds);
            $data['brandInfo'] = VehicleBrand::getInfoByIds($brandIds);
            foreach($groupList as $k=>$list){
                if(!empty($list['brand_ids'])){
                    $brandArr = explode(',',$list['brand_ids']);
                    $brandName = [];
                    foreach($brandArr as $row){
                        $brandName[] = isset($data['brandInfo'][$row])?$data['brandInfo'][$row]['name']:'';
                    }
                    $groupList[$k]['brand_names'] = implode(',',$brandName);
                }
            }
        }


        $total = $total?$total:0;
        $url = '/groups/list?' . ApiUtils::getMapping($request,'p') . "&";
        $objPage = new Paging($total,$offset,$url,$page);
        $outPage = $objPage->output();
        $data['roleArray'] = [];
//            $data['roleArray'] = \RiskConfig::$moneyRoles;
        $data['groupList'] = $groupList;
        $data['arrPager'] = array(
            'count'     => $total,
            'pagesize'  => $offset,
            'page'      => $page,
            'pagelink'  => $outPage,
        );

        $data['filter'] = $filter;
        return $this->render('list.tpl',$data);
    }

    /*
     * 详情页
     */
    public function actionView(){
        $request = $_REQUEST;
        $id = isset($request['id'])?intval($request['id']):0;
        try{
            if(!$id){
                throw new ApiBaseException;
            }
            $info = Groups::find()
                ->where(['id' => $id])
                ->asArray()
                ->one();
            if(!$info){
                throw new ApiBaseException;
            }
            if(!empty($info['city_id'])){
                $city = City::getInfoByIds(intval($info['city_id']));
                $info['city_name'] = isset($city[$info['city_id']]['name'])?$city[$info['city_id']]['name']:'';
            }
            if(!empty($info['upper_id'])){
                $upperGroup = Groups::getInfoByIds(intval($info['upper_id']));
                $info['upper_name'] = isset($upperGroup[$info['upper_id']]['name'])?$upperGroup[$info['upper_id']]['name']:'';
            }
            if(!empty($info['brand_ids'])){
                $bids = explode(',',$info['brand_ids']);
                $brand = VehicleBrand::getInfoByIds($bids);
                $brandName = [];
                foreach($bids as $row){
                    $brandName[] = isset($brand[$row])?$brand[$row]['name']:'';
                }
                $info['brand_names'] = implode(',',$brandName);
            }
            $data['info'] = $info;
            return $this->render('add.tpl',$data);
        }catch(ApiBaseException $e){
            Util::pageNotFund();
        }
    }

    /*
     * 四S店编辑页
     */
    public function actionEdit(){
        $request = $_REQUEST;
        $id = isset($request['id'])?intval($request['id']):0;
        $query = isset($request['query'])?$request['query']:[];
        try{
            if(!$id){
                throw new ApiBaseException;
            }
            if(!empty($query)){
                if(empty($query['city_id'])||empty($query['name'])||empty($query['address'])
                    ||empty($query['phone_number'])){
                    throw new ApiBaseException;
                }
                $objGroups = Groups::findOne(['id'=>$id]);
                if(!$objGroups){
                    throw new ApiBaseException;
                }
                $objGroups->attributes = $query;
                $objGroups->brand_ids = !empty($request['brand_ids'])?implode(',',$request['brand_ids']):'';
                $objGroups->update_time = time();
//                $objGroups->operator_id = $_SESSION['money_user_id'];
                $ret = $objGroups->update();
                if(!$ret){
                    $str = '修改失败';
                    Util::pageNotFund($str);
                }
                return $this->redirect("/groups/view?id=" . $id);
            }
            $info = Groups::find()
                ->where(['id' => $id])
                ->asArray()
                ->one();
            if(!$info){
                throw new ApiBaseException;
            }
            $data['groups'] = Groups::getAll();
            $brands = VehicleBrand::getAll();
            foreach($brands as $k=>$brand){
                if($brand['is_del'] == 1){
                    continue;
                }
                if(!empty($brand['level'])&&$brand['level'] == 1){
                    $data['firstBrands'][$brand['id']] = $brand;
                }elseif(!empty($brand['level'])&&$brand['level'] == 2){
                    $data['secondBrands'][$brand['id']] = $brand;
                }
            }
            $data['citys'] = ArrayUtil::getMap(City::getAll(),'id');
            $info['brand_ids'] = !empty($info['brand_ids'])?explode(',',$info['brand_ids']):[];
            $data['info'] = $info;
            $data['action'] = 2;
            return $this->render('add.tpl',$data);
        }catch(ApiBaseException $e){
            Util::pageNotFund();
        }
    }
    /*
     * 四S店添加
     */
    public function actionAdd(){
        $request = $_REQUEST;
        if(!empty($request)){
            $query = isset($request['query'])?$request['query']:[];
            $objGroups = new Groups();
            $objGroups->attributes = $query;
            $objGroups->create_time = time();
            $objGroups->update_time = time();
            $objGroups->brand_ids = !empty($request['brand_ids'])?implode(',',$request['brand_ids']):'';
            $ret = $objGroups->save();
            if($objGroups->id){
                $this->redirect('/groups/list');
            }else{
                echo "<script>alert('添加失败');window.location.href='/groups/add';</script>";
            }
        }else{
            $data['groups'] = Groups::getAll();
            $brands = VehicleBrand::getAll();
            foreach($brands as $k=>$brand){
                if($brand['is_del'] == 1){
                    continue;
                }
                if(!empty($brand['level'])&&$brand['level'] == 1){
                    $data['firstBrands'][$brand['id']] = $brand;
                }elseif(!empty($brand['level'])&&$brand['level'] == 2){
                    $data['secondBrands'][$brand['id']] = $brand;
                }
            }
//            echo json_encode($data['firstBrands']);exit;
            $data['citys'] = ArrayUtil::getMap(City::getAll(),'id');
            $data['action'] = 1;
            return $this->render('add.tpl',$data);
        }
    }

    /*
 * 删除
 */
    public function actionDelete(){
        $id = \Yii::$app->request->getQueryParam("id");
        $isDel = isset($_REQUEST['is_del'])?0:1;
        try{
            if(!$id){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN,'参数错误');
            }
            $transaction = \Yii::$app->getDb()->beginTransaction();
            if($objGroups = Groups::findOne(['id' => $id])){
                $objGroups->is_del = $isDel;
                $objGroups->update_time = time();
                $ret = $objGroups->update();
                if($ret === false){
                    throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN,'修改4S店失败');
                }
                //删除4S店下响应司机信息
                if($dids = ApiUtils::getCols(DriverInfo::find()->select('driver_id')->where(['group_id' => $id])->asArray()->all(),'driver_id')){
                    $ret = DriverInfo::updateAll(['is_del' => $isDel, 'update_time' => time()],['group_id'=>$id]);
                    if($ret === false){
                        throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN,'修改司机信息失败');
                    }
                    if(DriverPassport::find()->where("id in (" . implode(',',array_unique($dids)) . ")")){
                        $ret = DriverPassport::updateAll(['is_del' => $isDel, 'update_time' => time()],"id in (" . implode(',',array_unique($dids)). ")");
                        if($ret === false){
                            throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN,'修改司机登录信息失败');
                        }
                    }
                    if(ServiceSchedule::find()->where("driver_id in (" . implode(',',array_unique($dids)) . ")")){
                        $ret = ServiceSchedule::updateAll(['is_del' => $isDel, 'update_time' => time()],"driver_id in (" . implode(',',array_unique($dids)). ")");
                        if($ret === false){
                            throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN,'修改人车信息失败');
                        }
                    }
                }
                //删除4S店下相应车辆信息
                if($vids = ApiUtils::getCols(Vehicle::find()->select('id')->where(['group_id'=>$id])->asArray()->all(),'id')){
                    $ret = Vehicle::updateAll(['is_del' => $isDel, 'update_time'=> time()],['group_id'=>$id]);
                    if($ret === false){
                        throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN,'修改车辆信息失败');
                    }
                    if(ServiceSchedule::find()->where("vehicle_id in (" . implode(array_unique($vids)) . ")")){
                        $ret = ServiceSchedule::updateAll(['is_del' => $isDel, 'update_time' => time()],"vehicle_id in (" . implode(',',array_unique($vids)). ")");
                        if($ret === false){
                            throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN,'修改人车信息失败');
                        }
                    }
                }
                $transaction->commit();
                echo 'success';
                exit;
            }
        }catch(ApiBaseException $e){
            if($transaction != null){
                $transaction->rollBack();
            }
            $result = [
                'code' => $e->getMessage(),
                'message' => $e->getMessage(),
            ];
            echo json_encode($result);
            exit;
        }
    }
}