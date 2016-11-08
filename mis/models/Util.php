<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao
 * Date: 15/7/16
 * Time: 上午11:58
 */

namespace mis\models;


class Util {

    /*
     * 404页面跳转
     */
    public static function pageNotFund($str = '',$url = ''){
        $url = !empty($url)?$url:(isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'/');
        $str = !empty($str)?$str:'请求页面不存在,页面即将跳转到上一级';
        echo "<script>alert('" . $str . "');window.location.href='" . $url . "';</script>";
        exit;
    }
}