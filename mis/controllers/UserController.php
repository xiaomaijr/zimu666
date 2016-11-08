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
use common\models\Excel;
use common\models\Paging;
use common\models\UserInfo;
use common\models\UserPassport;



class UserController extends MisBaseController{


    /**
     * 获取用户列表
     * */
    public function actionList() {
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
            $arrList = UserInfo::find()
                ->orderBy($orderBy . ' ' . $sortWay)
                ->limit($intPageSize)
                ->offset(($intPage - 1) * $intPageSize)
                ->asArray()
                ->all();

            $total = UserInfo::find()->count();
        }else{
            $arrList = UserInfo::find()
                ->where($query)
                ->orderBy($orderBy . ' ' . $sortWay)
                ->limit($intPageSize)
                ->offset(($intPage - 1) * $intPageSize)
                ->asArray()
                ->all();
            $total = UserInfo::find()
                ->where($query)->count();

        }

        $url = \Yii::$app->request->baseUrl . '?' . ApiUtils::getMapping($request,'p') . '&';
        $objPage = new Paging($total, $intPageSize, $url , $intPage);
        $pageLink = $objPage->output();

        $arrTplData['roleArray'] = \RiskConfig::$moneyRoles;
        $arrTplData['arrList'] = $arrList;
        $arrTplData['filter'] = $filter;

        $arrTplData['arrPager'] = array(
            'count'     => $total,
            'pagesize'  => $intPageSize,
            'page'      => $intPage,
            'pagelink'  => $pageLink,
        );
        return $this->render('user_list.tpl',$arrTplData);
    }

    public function actionEdit(){
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $pre = 'jiadao';
        $mobile = 18679404219;
        $key = $pre . "_" . $mobile;
        if(!$redis->exists($key)){
            $time = time();
            $num = 1;
            $redis->hset($key,'time', $time);
            $redis->hset($key,'num', $num);
        }else{
            $num = $redis->hGet($key, 'num');
            $time = $redis->hGet($key, 'time');
            $duration = time() - $time;
            if($duration <= 60){
                if($num > 4){
                    return false;
                }else{
                    $num++;
                    $redis->hset($key, 'num', $num);
                }
            }else{
                $time = time();
                $num = 1;
                $redis->hset($key,'time', $time);
                $redis->hset($key,'num', $num);
            }
        }
        echo $num;
        exit;
    }

    /*
     * 用户删除
     */
    public function actionDelete(){
        try{
            $request = $_REQUEST;
            $id = $request['id']?intval($request['id']):0;
            $isDel = isset($request['is_del'])?0:1;
            $transaction = \Yii::$app->getDb()->beginTransaction();
            if(!$id){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN,'参数错误');
            }
            $objUser = UserInfo::findOne(['id' => $id]);
            $objUser->is_del = $isDel;
            $objUser->update_time = time();
            $ret = $objUser->update();
            if($ret === false){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN,'修改用户信息失败');
            }
            $objUserPst = UserPassport::findOne(['id' => $objUser->user_id]);
            $objUserPst->is_del = $isDel;
            $objUserPst->update_time = time();
            $res = $objUserPst->save();
            if(!$res){
                throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOWN,'修改用户passport信息失败');
            }
            $transaction->commit();
            $result = [
                'code' => ApiErrorDescs::SUCCESS,
                'message' => '修改成功'
            ];
            echo json_encode($result);
            exit;
        }catch(ApiBaseException $e){
            if($transaction != null){
                $transaction->rollBack();
            }
            $result = ['code'=>1,'message'=>$e->getMessage()];
            echo json_encode($result);
            exit;
        }
    }

} 