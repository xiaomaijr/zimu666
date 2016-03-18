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
    public static function getFloatParam($param, $arr, $decimal = 2){
        if(!is_array($arr)){
            $arr = (array)$arr;
        }
        return sprintf("%.0" . $decimal  . "f", self::getIntParam($param, $arr));
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
            return trim($arr[$param]);
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
        return date('YmdHis', $time);
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
    /*
     *
     */
    public static function EqualMonth( $data = array( ) ){
        if ( isset( $data['money'] ) && 0 < $data['money'] ) {
            $account = $data['money'];
        }else {
            return "";
        }
        if ( isset( $data['year_apr'] ) && 0 < $data['year_apr'] ){
            $year_apr = $data['year_apr'];
        }else{
            return "";
        }
        if ( isset( $data['duration'] ) && 0 < $data['duration'] ){
            $duration = $data['duration'];
        }
        if ( isset( $data['borrow_time'] ) && 0 < $data['borrow_time'] ){
            $borrow_time = $data['borrow_time'];
        }else{
            $borrow_time = time( );
        }
        $month_apr = $year_apr / 1200;
        $_li = pow( 1 + $month_apr, $duration );
        $repayment = round( $account * ( $month_apr * $_li ) / ( $_li - 1 ), 4 );
        $_result = array( );
        if ( isset( $data['type'] ) && $data['type'] == "all" ){
            $_result['repayment_money'] = $repayment * $duration;
            $_result['monthly_repayment'] = $repayment;
            $_result['month_apr'] = round( $month_apr * 100, 4 );
        }else{
            $i = 0;
            for ( ;	$i < $duration;	++$i){
                if ( $i == 0 ){
                    $interest = round( $account * $month_apr, 4 );
                }else{
                    $_lu = pow( 1 + $month_apr, $i );
                    $interest = round( ( $account * $month_apr - $repayment ) * $_lu + $repayment, 4 );
                }
                $_result[$i]['repayment_money'] = number_format( $repayment, 4 );
                $_result[$i]['repayment_time'] = self::get_times( array( "time" => $borrow_time, "num" => $i + 1 ) );
                $_result[$i]['interest'] = number_format( $interest, 4 );
                $_result[$i]['capital'] = number_format( $repayment - $interest, 4 );
            }
        }
        return $_result;
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
    public static function EqualSeason( $data = array( ) )
    {
        if ( isset( $data['month_times'] ) && 0 < $data['month_times'] ){
            $month_times = $data['month_times'];
        }
        if ( $month_times % 3 != 0 ){
            return false;
        }
        if ( isset( $data['account'] ) && 0 < $data['account'] ){
            $account = $data['account'];
        }else{
            return "";
        }
        if ( isset( $data['year_apr'] ) && 0 < $data['year_apr'] ){
            $year_apr = $data['year_apr'];
        }else{
            return "";
        }
        if ( isset( $data['borrow_time'] ) && 0 < $data['borrow_time'] ){
            $borrow_time = $data['borrow_time'];
        }else{
            $borrow_time = time( );
        }
        $month_apr = $year_apr / 1200;
        $_season = $month_times / 3;
        $_season_money = round( $account / $_season, 4 );
        $_yes_account = 0;
        $repayment_account = 0;
        $_all_interest = 0;
        $i = 0;
        for ( ;	$i < $month_times;	++$i	)
        {
            $repay = $account - $_yes_account;
            $interest = round( $repay * $month_apr, 4 );
            $repayment_account += $interest;
            $capital = 0;
            if ( $i % 3 == 2 ){
                $capital = $_season_money;
                $_yes_account += $capital;
                $repay = $account - $_yes_account;
                $repayment_account += $capital;
            }
            $_result[$i]['repayment_money'] = number_format( $interest + $capital, 4 );
            $_result[$i]['repayment_time'] = self::get_times( array( "time" => $borrow_time, "num" => $i + 1 ) );
            $_result[$i]['interest'] = number_format( $interest, 4 );
            $_result[$i]['capital'] = number_format( $capital, 4 );
            $_all_interest += $interest;
        }
        if ( isset( $data['type'] ) && $data['type'] == "all" ){
            $_resul['repayment_money'] = $repayment_account;
            $_resul['monthly_repayment'] = round( $repayment_account / $_season, 4 );
            $_resul['month_apr'] = round( $month_apr * 100, 4 );
            $_resul['interest'] = $_all_interest;
            return $_resul;
        }else{
            return $_result;
        }
    }

    public static function equalEndMonth( $data = array( ) ){
        if ( isset( $data['month_times'] ) && 0 < $data['month_times'] ){
            $month_times = $data['month_times'];
        }
        if ( isset( $data['account'] ) && 0 < $data['account'] ){
            $account = $data['account'];
        }else{
            return "";
        }
        if ( isset( $data['year_apr'] ) && 0 < $data['year_apr'] ){
            $year_apr = $data['year_apr'];
        }else{
            return "";
        }
        if ( isset( $data['borrow_time'] ) && 0 < $data['borrow_time'] ){
            $borrow_time = $data['borrow_time'];
        }else{
            $borrow_time = time( );
        }
        $month_apr = $year_apr / 1200;
        $_yes_account = 0;
        $repayment_account = 0;
        $_all_interest = 0;
        $interest = round($account * $month_apr, 4 );
        $i = 0;
        for ( ;	$i < $month_times;++$i){
            $capital = 0;
            if ( $i + 1 == $month_times ){
                $capital = $account;
            }
            $_result[$i]['repayment_account'] = $interest + $capital;
            $_result[$i]['repayment_time'] = self::get_times( array( "time" => $borrow_time, "num" => $i + 1 ) );
            $_result[$i]['interest'] = $interest;
            $_result[$i]['capital'] = $capital;
            $_all_interest += $interest;
        }
        if ( isset( $data['type'] ) && $data['type'] == "all" ){
            $_resul['repayment_account'] = $account + $interest * $month_times;
            $_resul['monthly_repayment'] = $interest;
            $_resul['month_apr'] = round( $month_apr * 100, 4 );
            $_resul['interest'] = $_all_interest;
            return $_result;
        }else{
            return $_result;
        }
    }

    public static function EqualEndMonthOnly( $data = array( ) ){
        if ( isset( $data['month_times'] ) && 0 < $data['month_times'] ){
            $month_times = $data['month_times'];
        }
        if ( isset( $data['account'] ) && 0 < $data['account'] ){
            $account = $data['account'];
        }else{
            return "";
        }
        if ( isset( $data['year_apr'] ) && 0 < $data['year_apr'] ){
            $year_apr = $data['year_apr'];
        }else{
            return "";
        }
        if ( isset( $data['borrow_time'] ) && 0 < $data['borrow_time'] ){
            $borrow_time = $data['borrow_time'];
        }else{
            $borrow_time = time( );
        }
        $month_apr = $year_apr / 1200;
        $interest = number_format( $account * $month_apr * $month_times, 4 );
        if ( isset( $data['type'] ) && $data['type'] == "all" ){
            $_resul['repayment_account'] = $account + $interest;
            $_resul['monthly_repayment'] = $interest;
            $_resul['month_apr'] = round( $month_apr * 100, 4 );
            $_resul['interest'] = $interest;
            $_resul['capital'] = $account;
            return $_resul;
        }
    }
    /*
     * 获取年月日时分秒
     */
    public static function getStrTimeByUnix($unixTime = 0){
        $unixTime = $unixTime?$unixTime:time();
        return date("Y-m-d H:i:s");
    }
}