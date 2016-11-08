<?php
/**
 * Created by PhpStorm.
 * User: 58
 * Date: 2016/6/10
 * Time: 15:45
 */

namespace front\controllers;


use front\models\Apps;
use front\models\Category;
use yii\web\Controller;
use common\models\ApiUtils;

class IndexController extends Controller
{
    public $timeStart = 0;

    public $enableCsrfValidation=false;
    public $layout = false;

    public function actionIndex()
    {
		$request = array_merge($_GET, $_POST);
		$categoryId = ApiUtils::getIntParam( 'id', $request, 2);
        $data = [
			'currentId' => $categoryId,
			'baseUrl' => '/index/index',
			'categorys' => Category::getAll(),
			'apps' => Apps::getAll($categoryId),
		];
        return $this->render('index.tpl', $data);
    }
}