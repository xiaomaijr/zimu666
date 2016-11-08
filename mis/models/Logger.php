<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao
 * Date: 15/8/10
 * Time: 下午5:12
 */

namespace mis\models;
use yii\base\Component;

define('TRACE_LEVEL', 2);
define('DEBUG_LEVEL', 1);
define('NOTICE_LEVEL', 3);
define('WARNING_LEVEL', 4);
define('ERROR_LEVEL', 5);
define('FATAL_LEVEL', 6);


class Logger extends Component{
    private $_file;
    private $_level_min;

//    public function __construct($file, $level_min = 0) {
//        $this->_file = $file;
//        $this->_level_min = $level_min;
//    }

    private function _write($level, $message) {
        if ($level < $this->_level_min) {
            return;
        }
        $level_str = '';
        switch($level) {
            case TRACE_LEVEL:
                $level_str = 'TRACE';
                break;
            case DEBUG_LEVEL:
                $level_str = 'DEBUG';
                break;
            case NOTICE_LEVEL:
                $level_str = 'NOTICE';
                break;
            case WARNING_LEVEL:
                $level_str = 'WARNING';
                break;
            case ERROR_LEVEL:
                $level_str = 'ERROR';
                break;
            case FATAL_LEVEL:
                $level_str = 'FATAL';
                break;
            default:
                $level_str = '';
                break;
        }

        $now = date('Y-n-j H:i:s');
        $record = sprintf("%s %s# %s\n", $level_str, $now, $message);

        error_log($record, 3, $this->_file);

        $size = filesize($this->_file);
        if ($size === FALSE)
            return;
        if ($size > 3072 * 1024 * 1024)
            unlink($this->_file);
    }

    public function trace($message) {
        $this->_write(TRACE_LEVEL, $message);
    }
    public function debug($message) {
        $this->_write(DEBUG_LEVEL, $message);
    }
    public function notice($message) {
        $this->_write(NOTICE_LEVEL, $message);
    }
    public function warning($message) {
        $this->_write(WARNING_LEVEL, $message);
    }
    public function error($message) {
        $this->_write(ERROR_LEVEL, $message);
    }
    public function fatal($message) {
        $this->_write(FATAL_LEVEL, $message);
    }

    public function setFile($file){
        $this->_file = $file;
    }
}