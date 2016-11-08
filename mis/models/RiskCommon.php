<?php

namespace app\models;

use Yii;

class RiskCommon
{
    /**
     * @brief   获取政策模板的详细信息，并按照一定的HTML格式输出
     * @author  caoxiaolin@rong360.com
     */
    public static function getPolicyValue($var_name, $type, $data, $arrConfigDetail, $op, $category_id)
    {

        $fieldName = \RiskConfig::$category_fields[$category_id];
        $oneDetail = json_decode($arrConfigDetail[$fieldName], true);
        $value = $oneDetail[$var_name];

        $arrSelect = array(PERMIT_TYPE_ENUMERATE, PERMIT_TYPE_IS);
        $arrText   = array(PERMIT_TYPE_COMPARE, PERMIT_TYPE_RANGE, PERMIT_TYPE_TEXT);
        $arrTextArea = array(PERMIT_TYPE_TEXT_AREA);

        if ($op == 'edit')
        {
            if (in_array($type, $arrSelect))
            {
                $strReturn = '<select class="sel" style="width:170px" id="apply_' . $var_name . '" name="apply[' . $fieldName . '][' . $var_name . ']">';
                $strReturn.= '<option value="">请选择</option>';
                foreach ((array)unserialize($data) as $item)
                {
                    $checked = ($value == $item['value']) ? 'selected' : '';
                    $strReturn.= '<option value="' . $item['value'] . '" ' . $checked . '>' . $item['desc'] . '</option>';
                }
                $strReturn.= '</select>';
            }
            elseif(in_array($type, $arrText))
            {
                $strReturn = '<input type="text" class="inpt" id="apply_' . $var_name . '" name="apply[' . $fieldName . '][' . $var_name . ']" value="' . $value . '" />';
            }
            elseif($type==PERMIT_TYPE_DATE){
            	$strReturn = '<input type="input" class="inpt datepicker" id="apply_'.$var_name.'" name="apply['.$fieldName.']['.$var_name.']" value="'.$value.'" autocomplete="off"/>';
            }
            elseif($type==PERMIT_TYPE_NUM){
                $strReturn = '<input type="text" class="inpt num" id="apply_'.$var_name.'" name="apply['.$fieldName.']['.$var_name.']" value="'.$value.'" />';
            }
            elseif($type==PERMIT_TYPE_TEXT_AREA) {
                $strReturn = '<textarea type="text" class="inpt" id="apply_'.$var_name.'" name="apply['.$fieldName.']['.$var_name.']" value= "' . $value.'"></textarea>';
            }
        }
        elseif ($op == 'view')
        {
            if (in_array($type, $arrSelect))
            {
                foreach ((array)unserialize($data) as $item)
                {
                    if ($value == $item['value'])
                    {
                        $strReturn = $item['desc'];
                        break;
                    }
                }
            }
            elseif (in_array($type, $arrText) or in_array($type, $arrTextArea) or $type==PERMIT_TYPE_NUM or $type==PERMIT_TYPE_DATE)
            {
                $strReturn = $value;
            }
        }
        
        return $strReturn;
    }

}

