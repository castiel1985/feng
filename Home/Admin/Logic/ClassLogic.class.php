<?php
namespace Admin\Logic;


class ClassLogic extends \Think\Model{
    public function __construct(){
        $this->Class = M('Class');
    }
    private $Class;

    public function getClassTotal($cond = array()){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $num = $this->Class->where($mycond)->where('isdel is null')->count();
        return $num;
    }

    public function getClassList($cond=array(), $p){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $pstr = $p.','.C('ADMIN_REC_PER_PAGE');
        $data = $this->Class->where($mycond)->where('isdel is null')->page($pstr)->select();
        return $data;
    }
}