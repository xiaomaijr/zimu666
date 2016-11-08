<?php
/**
 * Created by PhpStorm.
 * User: panbook
 * Date: 10/22/15
 * Time: 3:49 PM
 */

namespace mis\controllers;


use common\models\ApiBaseException;
use common\models\ApiErrorDescs;
use yii\web\Controller;

class ImageController extends Controller{


    /**
     * 图片上传
     */
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
                $result = [
                    'code' => 0,
                    'src'  => 'http://www.jiadao.cn/mall/static/' . $tmpDir . $filename,
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

}