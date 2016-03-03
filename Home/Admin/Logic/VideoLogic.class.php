<?php
/**
 * Created by PhpStorm.
 * User: IceLight
 * Date: 15/11/20
 * Time: 上午9:02
 */

namespace Admin\Logic;


class VideoLogic extends \Think\Model{
    public function __construct(){
        $this->Video = M('Video');
    }
    private $Video;

    public function getVideoTotal($cond = array()){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $num = $this->Video->where($mycond)->where('isdel is null')->count();
        return $num;
    }

    public function getVideoList($cond=array(), $p){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $pstr = $p.','.C('ADMIN_REC_PER_PAGE');
        $data = $this->Video->where($mycond)->where('isdel is null')->page($pstr)->order('creatime desc')->select();
        return $data;
    }

    public function getVideoById($id){
        if($id){
            $data = $this->Video->getById($id);
            return $data;
        }else{
            return false;
        }
    }
    public function getUuid(){
        $uuid=genUuid();
        $con['uuid']=$uuid;
        $ret=$this->Video->where($con)->select();
        if($ret){
            $uuid2=genUuid();
            $map['uuid']=$uuid2;
            $ret2=$this->Video->where($map)->select();
            if(!$ret2){
                return $uuid2;
            } else {
                return false;
            }
        }  else {
                return $uuid;
        }
    }
}