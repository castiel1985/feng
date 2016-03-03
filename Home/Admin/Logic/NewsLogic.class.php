<?php
namespace Admin\Logic;


class NewsLogic extends \Think\Model{
    public function __construct(){
        $this->News = M('News');
    }
    private $News;

    public function getNewsTotal($cond = array()){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $num = $this->News->where($mycond)->where('isdel is null')->count();
        return $num;
    }

    public function getNewsList($cond=array(), $p){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $pstr = $p.','.C('ADMIN_REC_PER_PAGE');
        $data = $this->News->where($mycond)->where('isdel is null')->page($pstr)->select();
        return $data;
    }
}