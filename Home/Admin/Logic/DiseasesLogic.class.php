<?php
namespace Admin\Logic;


class DiseasesLogic extends \Think\Model{
    public function __construct(){
        $this->Diseases = M('Diseases');
    }
    private $Diseases;

    public function getDiseasesTotal($cond = array()){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $num = $this->Diseases->where($mycond)->where('isdel is null')->count();
        return $num;
    }

    public function getDiseasesList($cond=array(), $p){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $pstr = $p.','.C('ADMIN_REC_PER_PAGE');
        $data = $this->Diseases->where($mycond)->where('isdel is null')->page($pstr)->select();
        return $data;
    }
}