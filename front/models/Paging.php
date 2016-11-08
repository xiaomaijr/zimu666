<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao
 * Date: 15/6/
 * Time: 下午3:54
 * 通用分页
 * usage:
 * $paging = new paging(99, 10, "/profile/favorite?lv=1&", 1);
 * echo $paging->output();
 */
namespace front\models;

class Paging{
    private $_total; //总条数
    private $_perpage; //每页显示数量
    private $_prefix_url; //url前缀，分页参数会以p=1的形式追加在其之后
    private $_current_page; //当前页
    private $_flag; //利于url重写参数 去掉(p=)

    public function  __construct($total, $perpage, $prefix_url=0, $current_page,$flag=0) {
        $this->_total = $total;
        $this->_perpage = $perpage;
        $this->_prefix_url = $prefix_url;
        $this->_current_page = $current_page;
        $this->_flag = $flag;
    }

    /*
     * <a class='PageLink' href='javascript:void(0)'>1</a>
     * <b>2</b>
     * <a class='PageLink' href='javascript:void(0)'>3</a>
     * <a class='PageLink' href='javascript:void(0)'>4</a>
     * <a class='PageLink' href='javascript:void(0)'>5</a>
     * <a class='PageLink' href='javascript:void(0)'>6</a>
     * <a class='PageLink' href='javascript:void(0)'>7</a>
     * <b>...</b>
     * <a class='PageLink' href='javascript:void(0)'>235</a>
     */
    public function output(){
        $total_page = $this->_total%$this->_perpage==0
            ?$this->_total/$this->_perpage:$this->_total/$this->_perpage+1;
        $total_page = intval($total_page);
        if($this->_current_page<1){
            $this->_current_page = 1;
        }
        if($this->_current_page>$total_page){
            $this->_current_page = $total_page;
        }

        $paging = "";
        $begin = 1;
        $end = $total_page;
        if($total_page>10){
            $begin = $this->_current_page-5>0?
                $this->_current_page-2:1;
            $end = $this->_current_page+5<$total_page?
                $this->_current_page+2:$total_page;
            if($begin==1){
                $end = $begin+6<$total_page?$begin+6:$total_page;
            }
            if($end==$total_page){
                $begin = $total_page-6>0?$total_page-6:1;
            }
        }
        if($this->_current_page==1){
            $paging .= "<b>1</b>";
        }
        else{
            if($this->_flag){
                $paging .= "<a class='PageLink' href='$this->_prefix_url" . "1'>1</a>";
            }else{
                $paging .= "<a class='PageLink' href='$this->_prefix_url" . "p=1'>1</a>";
            }
        }
        if($begin>1){
            $paging .= "<b>...</b>";
        }
        else{
            $begin = 2;
        }
        $i = $begin;
        for(;$i<=$end;$i++){
            if($i==$this->_current_page){
                $paging .= "<b>$this->_current_page</b>";
            }
            else{
                if($this->_flag){
                    $paging .= "<a class='PageLink' href='$this->_prefix_url" . "$i'>$i</a>";
                }else{
                    $paging .= "<a class='PageLink' href='$this->_prefix_url" . "p=$i'>$i</a>";
                }

            }
        }
        if($end<$total_page){
            $paging .= "<b>...</b>";
            if($this->_current_page==$total_page){
                $paging .= "<b>$total_page</b>";
            }
            else{
                if($this->_flag){
                    $paging .= "<a class='PageLink' href='$this->_prefix_url" . "$total_page'>$total_page</a>";
                }else{
                    $paging .= "<a class='PageLink' href='$this->_prefix_url" . "p=$total_page'>$total_page</a>";
                }
            }
        }

        return $paging;
    }
    //适用于js控制页面的分页  zx
    public function pageout(){
        $total_page = $this->_total%$this->_perpage==0
            ?$this->_total/$this->_perpage:$this->_total/$this->_perpage+1;
        $total_page = intval($total_page);
        if($this->_current_page<1){
            $this->_current_page = 1;
        }
        if($this->_current_page>$total_page){
            $this->_current_page = $total_page;
        }

        $paging = "";
        $begin = 1;
        $end = $total_page;
        if($total_page>10){
            $begin = $this->_current_page-5>0?
                $this->_current_page-2:1;
            $end = $this->_current_page+5<$total_page?
                $this->_current_page+2:$total_page;
            if($begin==1){
                $end = $begin+6<$total_page?$begin+6:$total_page;
            }
            if($end==$total_page){
                $begin = $total_page-6>0?$total_page-6:1;
            }
        }
        if($this->_current_page==1){
            $paging .= "<b>1</b>";
        }
        else{
            $paging .= "<a href='javascript:void(0)'>1</a>";
        }
        if($begin>1){
            $paging .= "<b>...</b>";
        }else{
            $begin = 2;
        }

        $i = $begin;
        for(;$i<=$end;$i++){
            if($i==$this->_current_page){
                $paging .= "<b>$this->_current_page</b>";
            }
            else{
                $paging .= "<a href='javascript:void(0)'>$i</a>";
            }
        }
        if($end<$total_page){
            $paging .= "<b>...</b>";
            if($this->_current_page==$total_page){
                $paging .= "<b>$total_page</b>";
            }
            else{
                $paging .= "<a href='javascript:void(0)'>$total_page</a>";
            }
        }

        return $paging;
    }

}
?>
