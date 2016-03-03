<?php
namespace Admin\Logic;


class UserLogic extends \Think\Model{
    public function __construct(){
        $this->User = M('User');
    }
    private $User;

    public function getUserTotal($cond = array()){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $num = $this->User->where($mycond)->where('isdel is null')->count();
        return $num;
    }

    public function getUserList($cond=array(), $p){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $pstr = $p.','.C('ADMIN_REC_PER_PAGE');
        $data = $this->User->where($mycond)->where('isdel is null')->page($pstr)->select();
        return $data;
    }
}