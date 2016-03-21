<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao-pc
 * Date: 2016/2/25
 * Time: 15:50
 */

namespace api\controllers;



use common\models\Ad;
use common\models\BorrowInfo;
use common\models\BorrowInvest;
use common\models\ApiBaseException;
use common\models\ApiErrorDescs;
use common\models\MemberBanks;
use common\models\TimeUtils;
use common\models\UrlConfig;
use yii\redis\Cache;


class IndexController extends ApiBaseController

{

    /*
     * 首页
     */
    public function actionIndex()
    {
        try{
            $request = $_REQUEST;
            $objTimer = new TimeUtils();
            //推荐产品列表
            $objTimer->start('borrowInfo');
            $condition = [
                'borrow_status'  =>  2,
                'is_tuijian' => 0 ,
            ];
            $borrows = BorrowInfo::getList($request, $condition);
            $objTimer->stop('borrowInfo');
            //投资及收益累计总额
            $objTimer->start('borrowAndInvestTotal');
            $brwAndInvInfo = BorrowInvest::getBorrowAndInvestTotal();
            $objTimer->stop('borrowAndInvestTotal');
            //banner信息
            $objTimer->start('ad_banner');
            $banners = Ad::getAppBanners();
            $objTimer->stop('ad_banner');

            $ret = [
                'borrows' => $borrows,
                'brw_and_inv' => $brwAndInvInfo,
                'banners' => $banners,
            ];

            $result = [
                'code' => ApiErrorDescs::SUCCESS,
                'message' => 'success',
                'result'  => $ret
            ];
        }catch(ApiBaseException $e){
            $result = [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }
        header('Content-type: application/json');
        echo json_encode($result);

        $this->logApi(__CLASS__, __FUNCTION__, $result);
        \Yii::$app->end();
    }

    public function actionTest()
    {
//        $incode = BorrowInvest::getDataByConditions(['investor_uid' => intval(236716), "loanno != ''"], null, 0, 0, ['id', 'borrow_id', 'investor_interest', 'add_time', 'integral_days']);;exit;

//        echo \Yii::$app->redis->hostname;exit;
        $obj = new MemberBanks();
        var_dump($obj->encode('6225880141357777', 'lt63p'));
        exit;
    }
}