<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao-pc
 * Date: 2016/3/23
 * Time: 15:19
 */

namespace api\models;


use common\models\ApiBaseException;
use common\models\ApiErrorDescs;
use yii\base\Component;

class RequestFilter extends Component implements ParamsFilter
{
    public function filterStr($param, $request){
        $addon_filter = "^\\+\/v(8|9)|";

        $filter = "(<|%3C|%253C)\\s*(script|iframe|object)\\b";
        $filter .= "|\b(alert|confirm|prompt|expression)\\s*\(|(onerror|onmouseover|onload)\\s*=";
        $filter .= "|.+?\b(OR|AND)\b.+?|GARANT.+?ON|INSERT.+?INTO|(CREATE|DROP).+?TABLE|(SELECT|DELETE).+?FROM|\bORD|IFNULL|\b(SELECT|UNION|ExtractValue|UpdateXml|SLEEP)\b|UPDATE.+?SET|(ALTER|CREATE|DROP|TRUNCATE)\\s+(DATABASE|USER)";
//        if (strcasecmp($method, 'COOKIE') !== 0)
//        {
//            $filter = $addon_filter.$filter;
//        }

        if (preg_match("/".$filter."/is", $request[$param], $match))
        {
            throw new ApiBaseException(ApiErrorDescs::ERR_UNKNOW_ERROR, '参数中含有非法字符，你的操作已被记入网监日志');
        }
    }

    public function getStrParam($param, $request){
        if(!isset($request[$param])){
            return '';
        }
        $this->filterStr($param, $request);

        return $request[$param];
    }
}