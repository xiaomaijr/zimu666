<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao
 * Date: 15/7/16
 * Time: 下午3:20
 */
namespace mis\controllers;

use common\models\ApiConfig;
use common\models\ApiErrorDescs;
use common\models\ApiUtils;
use common\models\Paging;
use front\models\CmsData;
use common\models\ApiBaseException;
use mis\controllers\MisBaseController;
use mis\models\Constant;
use mis\models\SystemUsers;
use mis\models\Util;

class CmsController extends MisBaseController{

    private $source = '/cms/view';

    public function actionList(){
        try{
            $data = [];
            $request = $_REQUEST;
            $query = isset($request['query'])?$request['query']:[];
            foreach($query as $con=>$dition){
                if(empty($dition))
                    unset($query[$con]);
            }
            $filter['orderby'] =  $orderBy = isset($request['order'])?trim($request['order']):'id';
            $filter['sortway'] = $sortWay = isset($request['sortway'])?trim($request['sortway']):'DESC';
            $filter = array_merge($filter, $query);
            $pageSize = PAGESIZE;
            $p = isset($request['p'])?intval($request['p']):1;
            if(!empty($query)){
                $cmsInfo = CmsData::find()
                    ->where($query)
                    ->orderBy($orderBy . ' ' . $sortWay)
                    ->limit($pageSize)
                    ->offset(($p-1)*$pageSize)
                    ->asArray()
                    ->all();
                $total = CmsData::find()
                    ->where($query)
                    ->count();
            }else{
                $cmsInfo = CmsData::find()
                    ->orderBy($orderBy . ' ' . $sortWay)
                    ->limit($pageSize)
                    ->offset(($p-1)*$pageSize)
                    ->asArray()
                    ->all();
                $total = CmsData::find()
                    ->count();
            }
            $url = \Yii::$app->request->baseUrl . '?' . ApiUtils::getMapping($request,'p') . '&';
            $objPage = new Paging($total, $pageSize, $url , $p);
            $pageLink = $objPage->output();

//            $data['roleArray'] = \RiskConfig::$moneyRoles;
            $data['list'] = $cmsInfo;
            $data['filter'] = $filter;

            $data['arrPager'] = array(
                'count'     => $total,
                'pagesize'  => $pageSize,
                'page'      => $p,
                'pagelink'  => $pageLink,
            );
            return $this->render('list.tpl',$data);
        }catch(ApiBaseException $e){
            Util::pageNotFund();
        }
    }

    public function actionAdd(){
        try{
            $request = $_REQUEST;
            $query = isset($request['query'])?$request['query']:[];
            if(!empty($query)){
                $arr_lines = explode("\r\n", $query['data']);
                if(!empty($arr_lines)){
                    foreach($arr_lines as $line){
                        if(trim($line)){
                            $row = explode(",", $line);
                            $data_arr[] = $row;
                        }
                    }
                }
                $query['data'] = json_encode($data_arr);
                unset($query['id']);
                $objCms = new CmsData();
                $objCms->attributes = $query;
                $objCms->create_time = time();
                $objCms->is_del  = 0;
                $ret = $objCms->save();
                if($ret){
                    return $this->redirect("/cms/list" );
                }
            }
            $data['action'] = 1;
            return $this->render('add.tpl',$data);
        }catch(ApiBaseException $e){
            Util::pageNotFund();
        }
    }

    public function actionEdit(){
        $request = $_REQUEST;
        $id = isset($request['id'])?intval($request['id']):0;
        $query = isset($request['query'])?$request['query']:[];
        try{
            if(!$id){
                throw new ApiBaseException;
            }
            if(!empty($query)){
                if(empty($query['p_sign'])||empty($query['data'])){
                    throw new ApiBaseException;
                }
                $objCms = CmsData::findOne(['id'=>$id]);
                if(!$objCms){
                    throw new ApiBaseException;
                }
                $arr_lines = explode("\r\n", $query['data']);
                if(!empty($arr_lines)){
                    foreach($arr_lines as $line){
                        if(trim($line)){
                            $row = explode(",", $line);
                            $data_arr[] = $row;
                        }
                    }
                }
                $query['data'] = json_encode($data_arr);
                $objCms->attributes = $query;
                $objCms->update_time = time();
                $ret = $objCms->update();
                if(!$ret){
                    $str = '修改失败';
                    Util::pageNotFund($str);
                }
                return $this->redirect("/cms/view?id=" . $id);
            }
            $info = CmsData::find()
                ->where(['id' => $id])
                ->asArray()
                ->one();
            if(!$info){
                throw new ApiBaseException;
            }
            $datas = json_decode($info['data'],true);
            $data_str = '';
            if(!empty($datas)){
                foreach($datas as $data){
                    $data_str .= implode(',',$data) . "\r\n";
                }

            }
            $info['data'] = $data_str;
            $data['info'] = $info;
            $data['action'] = 2;

            return $this->render('add.tpl',$data);
        }catch(ApiBaseException $e){
            Util::pageNotFund();
        }
    }

    public function actionView(){
        $request = $_REQUEST;
        $id = isset($request['id'])?intval($request['id']):0;
        $query = isset($request['query'])?$request['query']:[];
        try{
            if(!$id){
                throw new ApiBaseException;
            }
            $info = CmsData::find()
                ->where(['id' => $id])
                ->asArray()
                ->one();
            if(!$info){
                throw new ApiBaseException;
            }
            $datas = json_decode($info['data'],true);
            $data_str = '';
            if(!empty($datas)){
                foreach($datas as $data){
                    $data_str .= implode(',',$data) . "\r\n";
                }
            }
            $info['data'] = $data_str;
            $data['info'] = $info;
            return $this->render('add.tpl',$data);
        }catch(ApiBaseException $e){
            Util::pageNotFund();
        }
    }

    /*
     * 删除
     */
    public function actionDelete(){
        try{
            $id = $_REQUEST['id'];
            $isDel = !empty($_REQUEST['is_del']) ? 1 : 0;
            if(!$id) {
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '参数错误!');
            }
            if(!($objCms = CmsData::findOne(['id' => $id]))){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '数据不存在!');
            }
            $objCms->is_del = $isDel;
            $objCms->update_time = time();
            if(!$objCms->update()){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '操作失败!');
            }
            $result = [
                'code'  =>  ApiErrorDescs::SUCCESS,
                'message'   =>  'success',
            ];
        } catch (ApiBaseException $e) {
            $result = [
                'code'  =>  ApiErrorDescs::SUCCESS,
                'message'   =>  'success',
            ];
        }
        echo json_encode($result);
    }

    public function actionScoreFix(){
        $request = array_merge($_GET, $_POST);
        $query = ApiUtils::getArrParam('query', $request);
        $scoreKeys = ['quote', 'exam_fined', 'inquiry_lock_price', 'seller_activity_lock_price', 'seller_buy_order_lock'];
        $inputKeys = ['seller_activity_share_url', 'inquiry_detail_share_url'];
        try{
            if(empty($query)){
                $quoteInfo = CmsData::getInfoByPSignAndSSign('quote');
                $examFinedInfo = CmsData::getInfoByPSignAndSSign('exam_fined');
                $inyLockPreInfo = CmsData::getInfoByPSignAndSSign('inquiry_lock_price');
                $serActLockPreInfo = CmsData::getInfoByPSignAndSSign('seller_activity_lock_price');
                $adminMobiles = CmsData::getInfoByPSignAndSSign('admin_mobile');
                $adminEmails = CmsData::getInfoByPSignAndSSign('admin_email');
                $sellerBuyOrderLock = CmsData::getInfoByPSignAndSSign('seller_buy_order_lock');
                $sellerActivityShareUrl = CmsData::getInfoByPSignAndSSign('seller_activity_share_url');
                $inquiryDetailShareUrl = CmsData::getInfoByPSignAndSSign('inquiry_detail_share_url');
                $quoteInfo['title'] = '报价积分';
                $examFinedInfo['title'] = '审核积分';
                $inyLockPreInfo['title'] = '询价锁定价格';
                $serActLockPreInfo['title'] = '销售活动锁定价格';
                $sellerBuyOrderLock['title'] = '购车订单销售锁定价格';
                $adminMobiles['title'] = '管理员手机号';
                $adminEmails['title'] = '管理员邮箱';
                $sellerActivityShareUrl['title'] = '销售活动分享地址';
                $inquiryDetailShareUrl['title'] = '询价详情页分享地址';
                $infos = [
                    'quote'   => $quoteInfo,
                    'exam_fined'    => $examFinedInfo,
                    'inquiry_lock_price'    => $inyLockPreInfo,
                    'seller_activity_lock_price'    => $serActLockPreInfo,
                    'seller_buy_order_lock'    => $sellerBuyOrderLock,
                    'seller_activity_share_url' => $sellerActivityShareUrl,
                    'inquiry_detail_share_url' => $inquiryDetailShareUrl,
                    'admin_mobile' => $adminMobiles,
                    'admin_email' => $adminEmails,

                ];
                $data['infos'] = $infos;
                $data['score_keys'] = $scoreKeys;
                $data['input_keys'] = $inputKeys;
                return $this->render('score_fix.tpl', $data);
            }
            $pSign = ApiUtils::getStrParam('sign', $query);
            $data = ApiUtils::getStrParam('data', $query);

            if($tmp = CmsData::find()->where(['p_sign' => $pSign, 'is_del' => 0])->one()){
                $tmp->is_del = 1;
                $tmp->update_time = time();
                $tmp->operator_id = $_SESSION['money_user_id'];
                $tmp->update();
            }
            $obj = new CmsData();
            $obj->p_sign = $pSign;
            $obj->s_sign = '';
            $obj->data = in_array($pSign, $scoreKeys)?json_encode([[$data*100]]):json_encode([[$data]]);
            $obj->description = ApiUtils::getStrParam('desc', $query);
            $obj->operator_id = $_SESSION['money_user_id'];
            $obj->create_time = time();
            $obj->update_time = time();
            $ret = $obj->save();
            if(!$ret){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN);
            }
            $result = [
                'code' => ApiErrorDescs::SUCCESS,
                'message' => 'success',
            ];

        }catch (ApiBaseException $e){
            $result = [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }
        echo json_encode($result);
        exit;
    }

    public function actionHistory(){
        $request = $_REQUEST;
        $pSign = ApiUtils::getStrParam('p_sign', $request);
        $pageSize = PAGESIZE;
        $p = ApiUtils::getIntParam('p', $request, 1);
        $conditions = ['p_sign' => $pSign];
        $lists = CmsData::getDataByConditions($conditions, 'id desc', $pageSize, $p);
        $ops = SystemUsers::getUserNameMap();
        $data = [];
        if(!empty($lists)){
            foreach($lists as &$list){
                $tmps = json_decode($list['data'], true);
                $data_str = '';
                if(!empty($tmps)){
                    foreach($tmps as $tmp){
                        $data_str .= implode(',',$tmp) . "\r\n";
                    }
                }
                $list['data'] = $data_str;
            }
        }
        $total = CmsData::find()->where($conditions)->count();
        $data['ops'] = $ops;
        $data['lists'] = $lists;
        $url = \Yii::$app->request->baseUrl . '?' . ApiUtils::getMapping($request,'p') . '&';
        $objPage = new Paging($total, $pageSize, $url , $p);
        $pageLink = $objPage->output();

        $data['arrPager'] = array(
            'count'     => $total,
            'pagesize'  => $pageSize,
            'page'      => $p,
            'pagelink'  => $pageLink,
        );
        return $this->render('history.tpl', $data);
    }

}

