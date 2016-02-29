<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao-pc
 * Date: 2016/2/25
 * Time: 15:05
 */

namespace common\models;


use yii\web\HttpException;

class ApiBaseException extends HttpException
{
    /**
     * Constructor.
     * @param integer $status HTTP status code, such as 404, 500, etc.
     * @param string $message error message
     * @param integer $code error code
     * @param \Exception $previous The previous exception used for the exception chaining.
     */
    public function __construct($code = 0, $message = null, $status = 200, \Exception $previous = null)
    {
        if (null === $message) {
            $message = isset(ApiErrorDescs::$arrApiErrDescs[$code]) ? ApiErrorDescs::$arrApiErrDescs[$code]: '';
        }
        parent::__construct($status, $message, $code, $previous);
    }
}