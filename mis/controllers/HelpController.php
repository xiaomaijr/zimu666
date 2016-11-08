<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao
 * Date: 1/19/16
 * Time: 2:28 PM
 */

namespace mis\controllers;



use common\models\ApiBaseException;
use common\models\ApiErrorDescs;
use common\models\ApiUtils;
use common\models\CmsData;
use common\models\JDOssUtil;

class HelpController extends MisBaseController
{
    public  $enableCsrfValidation = false;


    public function actionUpload(){
        try{
            $request = $_REQUEST;
            $files = $_FILES;
            $type = ApiUtils::getStrParam('type', $request);
            if(!$files){
                throw new ApiBaseException(ApiErrorDescs::ERR_PARAM_INVALID, '请选择上传文件');
            }
            $fileName = ApiUtils::getStrParam('file_name', $request);
            if ($files['file']['error']) {
                throw new ApiBaseException(ApiErrorDescs::ERR_FILE_SIZE_TOO_LARGE);
            }
            $tmpFile = $files["file"]["tmp_name"];
            if(!$type){
                throw new ApiBaseException(ApiErrorDescs::ERR_PARAM_INVALID, '业务类型不能为空');
            }
            if(!$fileName){
                throw new ApiBaseException(ApiErrorDescs::ERR_PARAM_INVALID, '文件名不能为空');
            }
            $typeArr = array('jpg' => 1, 'png' => 1, 'gif' => 1, 'jpeg' => 1);
            $fileType = strtolower(substr(strrchr($files['file']['name'], '.'), 1));
            if (!isset($typeArr[$fileType])) {
                throw new ApiBaseException(ApiErrorDescs::ERR_PHOTO_TYPE_ERROR);
            }
            if(strrpos($fileName, '.' . $fileType) === 0){
                $targetPath = $type . '/' .  $fileName;
            }else{
                $targetPath = $type . '/' . $fileName . '.' . $fileType;
            }
            JDOssUtil::uploadObject($tmpFile, $targetPath);
            $result = [
                'code' => ApiErrorDescs::SUCCESS,
                'message' => 'success',
                'result' => JDOssUtil::getObjectPath($targetPath)
            ];
        }catch (ApiBaseException $e){
            $result = [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }
        echo json_encode($result);
    }

    public function actionGetUniqName(){

        try{
            $request = $_REQUEST;
            $preName = ApiUtils::getStrParam('pre_name', $request);
            $uniqName = $preName ? uniqid() . '_' . substr($preName, strrpos($preName, '\\')+1) : uniqid();
            $result = [
                'code' => ApiErrorDescs::SUCCESS,
                'message' => 'success',
                'result' => $uniqName,
        ];
        }catch(ApiBaseException $e){
            $result = [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }
        echo json_encode($result);
    }

    public function actionImgUpload(){
        $data = $types = [];
        $tmpTypes = CmsData::getInfoByPSignAndSSign('img_upload_types');
        foreach($tmpTypes as $type){
            $types = array_merge($types, $type);
        }
        foreach($types as $row){
            list($k, $val) = explode(':', $row);
            $data['types'][$k] = $val;
        }
        return $this->render('img_upload.tpl', $data);
    }
}