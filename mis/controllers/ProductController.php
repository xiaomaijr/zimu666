<?php
/**
 * Created by PhpStorm.
 * User: panbook
 * Date: 6/22/15
 * Time: 8:52 PM
 */

namespace mis\controllers;


use common\models\ApiBaseException;
use common\models\ApiErrorDescs;
use common\models\ApiUtils;
use common\models\BfAttachment;
use common\models\Paging;
use common\models\Product;
use front\models\Category;
use front\models\Goods;
use front\models\IndianaGoods;
use mis\models\Util;
use yii\base\Exception;

class ProductController extends MisBaseController{
    /**
     * 获取司机列表
     * */
    public function actionList()
    {
        //过滤条件
        $request = $_REQUEST;
        $query = isset($request['query'])&&!empty($request['query'])?$request['query']:[];
        foreach($query as $k=>$v){
            if(empty($v))
                unset($query[$k]);
        }
        $filter['orderby'] =  $orderBy = isset($request['order'])?trim($request['order']):'id';
        $filter['sortway'] = $sortWay = isset($request['sortway'])?trim($request['sortway']):'DESC';
        $filter = array_merge($filter, $query);
        $intPage = isset($request['p'])?intval($request['p']):1;
        $intPageSize = PAGESIZE;
        if(empty($query)) {
            $arrList = Goods::find()
                ->orderBy($orderBy . ' ' . $sortWay)
                ->limit($intPageSize)
                ->offset(($intPage - 1) * $intPageSize)
                ->asArray()
                ->all();

            $total = Goods::find()->count();
        }else{
            $arrList = Goods::find()
                ->where($query)
                ->orderBy($orderBy . ' ' . $sortWay)
                ->limit($intPageSize)
                ->offset(($intPage - 1) * $intPageSize)
                ->asArray()
                ->all();
            $total = Goods::find()
                ->where($query)->count();

        }

        $url = \Yii::$app->request->baseUrl . '?' . ApiUtils::getMapping($request,'p') . '&';
        $objPage = new Paging($total, $intPageSize, $url , $intPage);
        $pageLink = $objPage->output();

//        $arrTplData['roleArray'] = \RiskConfig::$moneyRoles;
        $arrTplData['list'] = $arrList;
        $arrTplData['filter'] = $filter;
        $arrTplData['categorys'] = [];
        if ($arrList) {
            $cateIds = ApiUtils::getCols($arrList, 'category_id');
            $arrTplData['categorys'] = Category::gets(array_unique($cateIds));
        }
        $arrTplData['arrPager'] = array(
            'count'     => $total,
            'pagesize'  => $intPageSize,
            'page'      => $intPage,
            'pagelink'  => $pageLink,
        );
//        echo json_encode($arrTplData);exit;
        return $this->render('list.tpl',$arrTplData);
    }
    /*
     * 新产品添加
     */
    public function actionAdd(){
        $request = $_REQUEST;
        $query = !empty($request['query'])?$request['query']:[];
        if(!empty($query)){
            $objProduct = new Goods();
            $objProduct->attributes = $query;
            $objProduct->price = intval($query['price']) * 100;
            $objProduct->min_price = intval($query['min_price']) * 100;
            $objProduct->image = !empty($query['image'])?trim($query['image']):'';
            $objProduct->total_inputs = ceil(intval($query['price'])/intval($query['min_price']));
            $objProduct->create_time = time();
            $objProduct->is_del = 0;
            $ret = $objProduct->save();
            if($ret){
                $this->redirect('/product/list');
            }else{
                echo "<script>alert('添加失败');window.location.href='/product/add';</script>";
            }
        }else{
            $data['action'] = 1;
            $data['categorys'] = ApiUtils::getMap(Category::getAll());
            return $this->render('view.tpl',$data);
        }
    }
    /*
     * 司机修改
     */
    public function actionEdit(){
        $request = $_REQUEST;
        $id = isset($request['id'])?intval($request['id']):0;
        $query = isset($request['query'])?$request['query']:[];
        try{
            if(!$id){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN,'参数错误');
            }
            $objProduct = Goods::findOne(['id' => $id]);
            if(!$objProduct){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN,'数据不存在');
            }
            if(!empty($query)){
                $objProduct->attributes = $query;
                $objProduct->price = intval($query['price']) * 100;
                $objProduct->min_price = intval($query['min_price']) * 100;
                $objProduct->image = !empty($query['image'])?trim(current(explode(',', $query['image']))):'';
                $objProduct->total_inputs = ceil(intval($query['price'])/intval($query['min_price']));
                $objProduct->update_time = time();
                $ret = $objProduct->save();
                if(!$ret){
                    throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN,'修改失败');
                }
                Util::pageNotFund('修改成功',"/product/list");
            }
            $data['action'] = 2;
            $info = Goods::find()
                ->where(['id' => $id])
                ->asArray()
                ->one();
            if(!$info){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN,'数据不存在');
            }
            $info['img_urls'] = !empty($info['img_urls'])?implode(',' , json_decode($info['img_urls'],true)):'';
            $data['categorys'] = ApiUtils::getMap(Category::getAll());
            $data['info'] = $info;
            return $this->render('view.tpl',$data);

        }catch(ApiBaseException $e){
            $err = $e->getMessage();
            Util::pageNotFund($err);
        }
    }
    /*
     * 产品详情
     */
    public function actionView(){
        $request = $_REQUEST;
        $id = $request['id']?intval($request['id']):0;
        try{
            if(!$id){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN,'参数错误');
            }
            $info = Product::find()
                ->where(['id' => $id])
                ->asArray()
                ->one();
            if(!$info){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN,'数据不存在');
            }
            $info['colors'] = !empty($info['colors'])?implode(',' , json_decode($info['colors'],true)):'';
            $info['cities'] = !empty($info['cities'])?implode(',' , json_decode($info['cities'],true)):'';
            $info['img_urls'] = !empty($info['img_urls'])?implode(',' , json_decode($info['img_urls'],true)):'';
            $data['info'] = $info;
            return $this->render('view.tpl',$data);
        }catch(ApiBaseException $e){
            $err = $e->getMessage();
            Util::pageNotFund($err);
        }
    }

    /**
     * 产品删除恢复
     */
    public function actionDelete(){
        try{
            $request = $_REQUEST;
            $id = $request['id']?intval($request['id']):0;
            $isDel = ApiUtils::getIntParam('is_del', $request);
            if(!$id){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '参数错误');
            }
            $objDri = Goods::findOne(['id' => $id]);
            $objDri->is_del = $isDel;
            $objDri->update_time = time();
            $ret = $objDri->save();
            if(!$ret){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '操作失败');
            }
            $result = [
                'code' => ApiErrorDescs::SUCCESS,
                'message'=> 'success',
            ];
        }catch(ApiBaseException $e){
            $result = [
                'code'=>$e->getCode(),
                'message'=>$e->getMessage()
            ];
        }
        echo json_encode($result);
    }

    /**
     * 产品上架
     */
    public function actionShelves(){
        $transaction = \Yii::$app->getDb()->beginTransaction();
        try{
            $request = $_REQUEST;
            $id = $request['id']?intval($request['id']):0;
            if(!$id){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '参数错误');
            }
            $objDri = Goods::findOne(['id' => $id]);
            $objDri->status = 1;
            $objDri->update_time = time();
            $ret = $objDri->save();
            if(!$ret){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '操作失败');
            }
            IndianaGoods::create($objDri->toArray());
            $transaction->commit();
            $result = [
                'code' => ApiErrorDescs::SUCCESS,
                'message'=> 'success',
            ];
        }catch(ApiBaseException $e){
            $transaction->rollBack();
            $result = [
                'code'=>$e->getCode(),
                'message'=>$e->getMessage()
            ];
        }
        echo json_encode($result);
    }


    public function actionUpload(){
    $request = $_REQUEST;
    $files = $_FILES;
    try{
        if(!isset($request['type'])||empty($request['type'])){
            throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN,'参数错误');
        }
        if(!isset($files['myfile'])||empty($files['myfile'])){
            throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN,'参数错误');
        }
        $uploadDir = dirname(dirname(dirname(__FILE__))) . "/front/web/static/";
        switch(trim($request['type'])){
            case 'img':
                $tmpDir = 'img/about/';
                break;
            case 'banner':

            case 'product';

            case 'logo':

            case 'smalllogo':

            case 'code':

            case 'userlicense':

            case 'shijiatuan':

            case 'contract':

                $tmpDir = trim($request['type']) . "/";
                break;
            default :
                $tmpDir = 'other/';
                break;
        }
        $uploadDir .= $tmpDir;
        file_exists($uploadDir) || (mkdir($uploadDir,0775,true) && chmod($uploadDir,0775));
        if(!is_array($files['myfile']['name'])){
            $filename = time() . uniqid() . strstr($files['myfile']['name'],'.');
            move_uploaded_file($files['myfile']['tmp_name'], $uploadDir . $filename);
            $urlPrefix = '';
            $result = [
                'code' => 0,
                'src'  => rtrim($urlPrefix, '\/') . '/static/' . $tmpDir . $filename,
            ];
            echo json_encode($result);
            exit;
        }

    }catch(ApiBaseException $e){
        $result = [
            'code'=>$e->getCode(),
            'message'=>$e->getMessage()
        ];
        echo json_encode($result);
        exit;
    }

}



    public function actionNewUpload(){
        $request = $_REQUEST;
        $files = $_FILES;
        try{
            if(!isset($request['type'])||empty($request['type'])){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN,'参数错误');
            }
            if(!isset($files['myfile'])||empty($files['myfile'])){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN,'参数错误');
            }
            $uploadDir = dirname(dirname(dirname(__FILE__))) . "/api/web/static/";
            switch(trim($request['type'])){
                case 'img':
                    $tmpDir = 'img/about/';
                    break;
                case 'vehicletype':
                    if(strpos($files['myfile']['name'],'_') !== false){
                        $tmp = explode('_',$files['myfile']['name']);
                        $tmpDir = 'vehicletype/' . $tmp[0] . "/";
                    }else{
                        throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN,'文件名错误');
                    }
                    break;
                case 'banner':

                case 'product';

                case 'logo':

                case 'smalllogo':

                case 'code':

                case 'userlicense':

                case 'shijiatuan':

                case 'contract':

                    $tmpDir = trim($request['type']) . "/";
                    break;
                default :
                    $tmpDir = 'other/';
                    break;
            }
            $uploadDir .= $tmpDir;
            file_exists($uploadDir) || (mkdir($uploadDir,0775,true) && chmod($uploadDir,0775));
            if(!is_array($files['myfile']['name'])){
                $filename = time() . uniqid() . strstr($files['myfile']['name'],'.');
                move_uploaded_file($files['myfile']['tmp_name'], $uploadDir . $filename);
                $urlPrefix = ApiUtils::getMainUrlPrefix();
                $result = [
                    'code' => 0,
                    'src'  => $urlPrefix . 'static/' . $tmpDir . $filename,
                ];

                if($request['exist']==1){
                    $objProduct = BfAttachment::findOne(['order_id' => $request['order_id'],'type'=>$request['oldtype'],'sort'=>$request['sort']]);
                    //$objProduct->attributes = $query;
                    $objProduct->url = $urlPrefix . 'static/' . $tmpDir . $filename;
                    $ret = $objProduct->save();
                }else{
                    $objProduct = new BfAttachment();
                    $objProduct->order_id = $request['order_id'];
                    $objProduct->url = $urlPrefix . 'static/' . $tmpDir . $filename;
                    $objProduct->type = $request['oldtype'];
                    $objProduct->sort = $request['sort'];
                    $ret = $objProduct->save();
                }
                echo json_encode($result);
                exit;
            }

        }catch(ApiBaseException $e){
            $result = [
                'code'=>$e->getCode(),
                'message'=>$e->getMessage()
            ];
            echo json_encode($result);
            exit;
        }

    }


    public function actionVehline(){
        $request = $_REQUEST;
        $type = isset($request['type'])?intval($request['type']):0;
        $id = isset($request['id'])?intval($request['id']):0;
        switch($type){
            case 0:
                self::getVehicleInfo($id);
                break;
            case 1:
                self::getGroupInfo($id);
                break;
            case 2:
                self::getVehicleTypeInfo($id);
                break;
            case 3:
                self::getSubBrandAndLine($id);
                break;
            case 4:
                self::getLineBySubBrand($id);
                break;
        }

    }

    public static function getVehicleInfo($id){
        $res = "<option value='0'>------请选择车辆-----</option>";
        if(!$id){
            echo $res;
            exit;
        }
        $vehicles = Vehicle::getLineVehicles($id);
        if(!$vehicles){
            echo $res;
            exit;
        }
        foreach($vehicles as $vehicle){
            $res .= "<option value='" . $vehicle['id']. "'>" . $vehicle['number']. "</option>";
        }
        echo $res;
        exit;
    }

    public static function getGroupInfo($id){
        $res = "<option value='0'>------请选择四S店-----</option>";
        if(!$id){
            echo $res;
            exit;
        }
        $groups = Groups::getInfoByIds($id);
        if(!$groups){
            echo $res;
            exit;
        }
        foreach($groups as $group){
            $res .= "<option value='" . $group['id']. "'>" . $group['name']. "</option>";
        }
        echo $res;
        exit;
    }

    public static function getVehicleTypeInfo($id){
        $res = "<option value='0'>------请选择车型-----</option>";
        if(!$id){
            echo $res;
            exit;
        }
        $types = VehicleType::getInfoByLids($id);
        if(!$types){
            echo $res;
            exit;
        }
        foreach($types as $type){
            $res .= "<option value='" . $type['id']. "'>" . $type['vehicle_type_name']. "</option>";
        }
        echo $res;
        exit;
    }

    public static function getSubBrandAndLine($id){
        $res['subbrand'] = "<option value='0'>------请选择子品牌-----</option>";
        $res['line'] = "<option value='0'>------请选择车系-----</option>";
        if(!$id){
            echo json_encode($res);
            exit;
        }
        $subbrands = VehicleBrand::getInfoByUid($id);
        if($subbrands){
            foreach($subbrands as $brand){
                $res['subbrand'] .= "<option value=" . $brand['id']. ">" . $brand['name']. "</option>";
            }
        }
        $lines = VehicleLine::getInfoByBId($id);
        if($lines){
            foreach($lines as $line){
                $res['line'] .= "<option value=" . $line['id']. ">" . $line['name']. "</option>";
            }
        }
        echo json_encode($res);
        exit;

    }

    public static function getLineBySubBrand($id){
        $res = "<option value='0'>------请选择车系-----</option>";
        if(!$id){
            echo 0;
            exit;
        }
        $lines = VehicleLine::getInfoBySubBId($id);
//        echo json_encode($lines);exit;
        if($lines){
            foreach($lines as $line){
                $res .= "<option value=" . $line['id']. ">" . $line['name']. "</option>";
            }
        }else{
            echo 0;
            exit;
        }
        echo $res;
        exit;
    }



}
