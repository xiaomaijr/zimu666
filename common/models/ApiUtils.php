<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao-pc
 * Date: 2016/2/25
 * Time: 15:32
 */

namespace common\models;


class ApiUtils
{
    /**
     * 检查必须参数是否都存在
     * @param array $params_needed
     * @param array $params
     * @return boolean
     */
    public static function checkParams(Array $paramsNeeded, Array $params = array())
    {
        $params = $params?$params:array_merge($_GET, $_POST);
//        if (!is_array($paramsNeeded) || !is_array($params)) {
//            return false;
//        }
        foreach ($paramsNeeded as $value) {
            if (empty($params[$value])) {
                return false;
            }
        }
        return true;
    }

    /**
     * 校验Sign
     * @param string $sign 待校验的sign
     * @param string $params 参数列表
     * @param string $secret 密钥
     * @return boolean
     */
    public static function checkSign($params, $secret)
    {

        if (!isset($params['sign']) || !is_string($params['sign'])) {
            return false;
        }
        $sign = $params['sign'];
        unset($params['sign']);
        $needSign = self::getSignature($params, $secret);
        if ($needSign !== $sign) {
            return false;
        }
        return true;
    }

    /**
     * 签名生成算法
     * @param array $params API调用的请求参数集合的关联数组，不包含sign参数
     * @return string 返回参数签名值
     */
    public static function getSignature($params, $secret)
    {
        $str = '';
        ksort($params);
        foreach ($params as $k => $v) {
            $str .= "$k=$v";
        }
        $str .= $secret;
        return md5($str);
    }
    /*
     * 从数组中获取指定key对应的value，value为int
     * @param $param
     * @param $arr array
     * @param $default 0
     * return int
     */
    public static function getIntParam($param, $arr, $default = 0){
        if(!is_array($arr)){
            $arr = (array)$arr;
        }
        if(isset($arr[$param])){
            return intval($arr[$param]);
        }
        return $default;
    }
    /*
     * 从数组中获取指定key对应的value，value为float
     * @param $param
     * @param $arr array
     * @param $default 0.0
     * return float
     */
    public static function getFloatParam($param, $arr, $decimal = 2, $dec_point = '.', $thousands_sep = ''){
        if(!is_array($arr)){
            $arr = (array)$arr;
        }
        if(isset($arr[$param])){
            return number_format($arr[$param], $decimal, $dec_point, $thousands_sep);
        }
        return number_format(0, $decimal);
    }
    /*
     * 从数组中获取指定key对应的value，value为string
     * @param $param
     * @param $arr array
     * @param $default ''
     * return string
     */
    public static function getStrParam($param, $arr, $default = ''){
        if(!is_array($arr)){
            $arr = (array)$arr;
        }
        if(isset($arr[$param])){
            return addslashes(htmlspecialchars(trim($arr[$param])));
        }
        return $default;
    }
    /*
     * 从数组中获取指定key对应的value，value为array
     * @param $param
     * @param $arr array
     * @param $default []
     * return array
     */
    public static function getArrParam($param, $arr, $default = []){
        if(!is_array($arr)){
            $arr = (array)$arr;
        }
        if(isset($arr[$param])){
            return $arr[$param];
        }
        return $default;
    }
    /*
     * 删除数组中值为空的元素
     */
    public static function clearArrEmpeyIndex($arr = []){
        foreach($arr as $k=>$v){
            if(empty($v)){
                unset($arr[$k]);
            }
        }
        return $arr;
    }
    /*
     * 获取当前函数调用者相关信息
     */
    public static function get_caller_info()
    {
        $c = '';
        $file = '';
        $func = '';
        $class = '';
        $trace = debug_backtrace();
        if (isset($trace[2])) {
            $file = $trace[1]['file'];
            $func = $trace[2]['function'];
            if ((substr($func, 0, 7) == 'include') || (substr($func, 0, 7) == 'require')) {
                $func = '';
            }
        } else if (isset($trace[1])) {
            $file = $trace[1]['file'];
            $func = '';
        }
        if (isset($trace[3]['class'])) {
            $class = $trace[3]['class'];
            $func = $trace[3]['function'];
            $file = $trace[2]['file'];
        } else if (isset($trace[2]['class'])) {
            $class = $trace[2]['class'];
            $func = $trace[2]['function'];
            $file = $trace[1]['file'];
        }
        if ($file != '') $file = basename($file);
        $c = $file . ": ";
        $c .= ($class != '') ? ":" . $class . "->" : "";
        $c .= ($func != '') ? $func . "(): " : "";
        return ($c);
    }

    /*
     * 从一个二维数组中获取某指定的属性
     * @param array $arr  二维数组
     * @param string index
     * return array 一维数组
     */
    public static function getCols($arr, $col)
    {
        $ret = [];
        if (!is_array($arr)) {
            return $ret;
        }
        foreach ($arr as $rows) {
            foreach ($rows as $k => $v) {
                if ($k === $col)
                    array_push($ret, $v);
            }
        }
        return $ret;
    }
    /*
     * 从一个二维数组按照每个下表生成一个新的映射关系
     * @param array $arr  二维数组
     * @param string $key
     * return array 二维数组
     */
    public static function getMap($arr, $key = 'id')
    {
        if (!$arr) {
            return [];
        }
        $res = [];
        foreach ($arr as $row) {
            if (in_array($key, array_keys($row))) {
                $res[$row[$key]] = $row;
            }
        }
        return $res;
    }

    /**
     * @param $url
     * @param $data
     * @param array $header
     * @return mixed|string
     */
    public static function curlByPost($url,$data,$header=array()){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        if(!empty($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
        }
        $ret = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if($statusCode==200){
            return $ret;
        }else{
            return false;
        }
    }
    /*
     * 获取'YmdHis'格式时间
     * @param $time unix timestamp
     * return string
     */
    public static function getStrTime($time = 0){
        if(!$time){
            $time = time();
        }
        return date('Y-m-d H:i:s', $time);
    }
    /*
     * 验证手机号码格式
     * @param $phone
     * if false throw new ApiBaseException
     */
    public static function checkPhoneFormat($phone){
        $pattern = "/^1[3,5,7,8]\d{9}$/";
        if(!preg_match($pattern, $phone)){
            throw new ApiBaseException(ApiErrorDescs::ERR_PHONE_FORMAT_WRONG);
        }
    }
    /*
     * 密码格式校验
     */
    public static function checkPwd($pwd){
        if(preg_match( "/^[a-zA-Z0-9]{6,16}$/", $pwd)){
            return true;
        }else{
            throw new ApiBaseException(ApiErrorDescs::ERR_USER_PASSWORD_FORMART_WORNG);
        }
    }
    /*
     *求两个unix时间戳差的天数
     */
    public static function getDiffDay($preTime, $nextTime){
        if($preTime > $nextTime){
            return false;
        }
        $preTime = strtotime(date('Ymd', $preTime) . ' 0:0:0');
        $nextTime = strtotime(date('Ymd', $nextTime) . ' 0:0:0');
        return ($nextTime-$preTime)/24/3600;
    }
    

    //获得时间天数
    public static function get_times($data=array()){
        if (isset($data['time']) && $data['time'] != ""){
            $time = $data['time'];//时间
        }elseif (isset($data['date']) && $data['date'] != ""){
            $time = strtotime($data['date']);//日期
        }else{
            $time = time();//现在时间
        }
        if (isset($data['type']) && $data['type'] != ""){
            $type = $data['type'];//时间转换类型，有day week month year
        }else{
            $type = "month";
        }
        if (isset($data['num']) && $data['num']!=""){
            $num = $data['num'];
        }else{
            $num = 1;
        }

        if ($type=="month"){
            $month = date("m",$time);
            $year = date("Y",$time);
            $_result = strtotime("$num month",$time);
            $_month = (int)date("m",$_result);
            if ($month+$num>12){
                $_num = $month+$num-12;
                $year = $year+1;
            }else{
                $_num = $month+$num;
            }
            if ($_num!=$_month){
                $_result = strtotime("-1 day",strtotime("{$year}-{$_month}-01"));
            }
        }else{
            $_result = strtotime("$num $type",$time);
        }
        if (isset($data['format']) && $data['format']!=""){
            return date($data['format'],$_result);
        }else{
            return $_result;
        }
    }
    
    /**
     * 获取年月日时分秒
     */
    public static function getStrTimeByUnix($unixTime = 0){
        $unixTime = $unixTime?$unixTime:time();
        return date("Y-m-d H:i:s", $unixTime);
    }
    /**
     * 获取年月日
     */
    public static function getDateByUnix($unixTime = 0){
        $unixTime = $unixTime?$unixTime:time();
        return date("Y-m-d", $unixTime);
    }
    /**
     * replace by ****
     */
    public static function replaceByLength($str, $length, $start, $end, $replace = "*"){
        return substr_replace($str,str_repeat($replace, $length), $start, $end);
    }

    // 获取客户端IP地址
    public static function getClientIp() {
        static $ip = NULL;
        if ($ip !== NULL) return $ip;
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos =  array_search('unknown',$arr);
            if(false !== $pos) unset($arr[$pos]);
            $ip   =  trim($arr[0]);
        }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $ip = (false !== ip2long($ip)) ? ip2long($ip) : 0;
        return $ip;
    }
    /**
     * @param $params
     * @param string $method
     * @return mixed
     */
    public static function formatIp($ip)
    {
        return long2ip($ip);
    }
    //参数过滤，防sql、js注入
    public static function filter($params, $method = 'get'){
        if(is_array($params)){
            foreach($params as &$param){
                self::filterSpecialChars($param, $method);
            }
        }
        return $params;
    }
    /**
     * 字符串参数过滤：对单个或数组进行替换过滤
     * 1、替换字符串中的'和\
     * 2、检测js注入
     * 3、检测sql注入
     *
     * @param string $string	被替换、过滤的字符串
     * @param string $method	参数传递的方法
     *
     * @return string 替换后的字符串，或抛出异常
     */
    public static function filterSpecialChars($string, $method)
    {
        if (!is_array($string))
        {
            return self::filterSpecialChar($string, $method);
        }
        else
        {
            foreach ($string as $key => $value)
            {
                if($key !== '_URL_'){
                    $ret[$key] = self::filterSpecialChars($value, $method);
                }
            }
            return $ret;
        }
    }

    /**
     * 字符串参数过滤：仅针对单个字符串进行替换、过滤
     * 1、替换字符串中的'和\
     * 2、检测js注入
     * 3、检测sql注入
     *
     * @param string $string	被替换、过滤的字符串
     * @param string $method	参数传递的方法
     *
     * @return string 替换后的字符串，或抛出异常
     */
    public static function filterSpecialChar($string, $method = 'GET')
    {
        $string = get_magic_quotes_gpc() == 1 ? $string : addslashes($string);

        $addon_filter = "^\\+\/v(8|9)|";
        //		$filter = "\b(alert|confirm|prompt)\b|(<|%3C|%253C)\\s*(script|iframe|object)\\b|onerror|expression|onmouseover|onload|GARANT.+?ON|INSERT.+?INTO|(CREATE|DROP).+?TABLE|DELETE.+?FROM|UNION|SELECT|floor|ExtractValue|UpdateXml|UPDATE.+?SET|(ALTER|CREATE|DROP|TRUNCATE)\\s+(DATABASE|USER)";

        $filter = "(<|%3C|%253C)\\s*(script|iframe|object)\\b";
        $filter .= "|\b(alert|confirm|prompt|expression)\\s*\(|(onerror|onmouseover|onload)\\s*=";
        $filter .= "|.+?\b(OR|AND)\b.+?|GARANT.+?ON|INSERT.+?INTO|(CREATE|DROP).+?TABLE|(SELECT|DELETE).+?FROM|\bORD|IFNULL|\b(SELECT|UNION|ExtractValue|UpdateXml|SLEEP)\b|UPDATE.+?SET|(ALTER|CREATE|DROP|TRUNCATE)\\s+(DATABASE|USER)";
        if (strcasecmp($method, 'COOKIE') !== 0)
        {
            $filter = $addon_filter.$filter;
        }

        if (preg_match("/".$filter."/is", $string, $match))
        {
            throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '参数中含有非法字符，你的操作已被记入网监日志');
        }

        return $string;
    }

    /*
     * 统计字符串长度
     */
    public static function getStrLen($str){
        if(function_exists('mb_strlen')){
            return mb_strlen($str, 'utf-8');
        }elseif(function_exists('iconv_strlen')){
            return iconv_strlen($str);
        }else{
            return strlen($str);
        }
    }

    public static function email($to, $subject, $body, $flag = 0)
    {
        $mail = \Yii::$app->mailer->compose();
        if (is_string($to) && strpos($to, ';') !== false) {
            $to = explode(";", $to);
        }
        $mail->setTo($to);
        $mail->setSubject($subject);
        if (!$flag) {
            $mail->setTextBody($body);   //发布纯文字文本
        } else {
            $mail->setHtmlBody($body);    //发布可以带html标签的文本
        }
        return $mail->send() ? true : false;
    }

    /**
     * @param $businessType
     * @param $userAccount
     */
    public static function generateOrderNo($businessType, $userAccount)
    {
        if (!$businessType || !$userAccount) {
            return '';
        }
        $tradeNo = '';
        if ($businessType == ApiConfig::BUSINESS_TYPE_RECHARGE) {
            $tradeNo .= 'R' . date('YmdHis') . $userAccount . $businessType;
        } elseif ($businessType == ApiConfig::BUSINESS_TYPE_SHOPPING) {
            $tradeNo .= 'E' . date('YmdHis') . $userAccount . $businessType;
        }
        return $tradeNo . rand(100, 99999);
    }

    /*
     * url携带参数处理
     */
    public static function getMapping($conditions, $param)
    {
        $queryStr = '';
        if (empty($conditions)) {
            return $queryStr;
        }
        foreach ($conditions as $k => $v) {
            if (empty($k) || empty($v)) {
                continue;
            }
            if (is_string($param) && $k === $param) {
                continue;
            }
            if (is_array($param) && in_array($k, $param)) {
                continue;
            }
            if (is_array($v)) {
                foreach ($v as $key => $val) {
                    if (empty($key)) {
                        continue;
                    }
                    if (empty($val)) {
                        continue;
                    }
                    $queryStr .= $k . "[" . $key . "]" . "=" . $val . "&";
                }
            } else {
                $queryStr .= $k . "=" . $v . "&";
            }
        }
        return rtrim($queryStr, '&');
    }
}