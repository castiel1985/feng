<?php
namespace Home\Controller;
use Think\Controller;

class DiseasesController extends Controller {
    public function index(){
         header("Content-type: text/html; charset=utf-8");      
         $this->display('Diseases:index');
    }
    public function getdisease($typeid){
        header("Content-type: text/html; charset=utf-8");     
        $dis=D('diseases');
        $map['typeid']=$typeid;
        $ret=$dis->where($map)->getField('id,pId,name',true);
        $dispid=D('diseasespid');
        $ret2=$dispid->where($map)->getField('pname,pId,name',true);
        $arr=array();
        foreach($ret as $k=>$v) {
            array_push($arr, $v);
        }
        foreach($ret2 as $k=>$v) {
            array_push($arr, $v);
        }
        $arr1=array('records'=> $arr);
        $json_string =json_encode($arr1);
        $json_string=str_replace("pname","id",$json_string);
        echo  $json_string;   
    }
    public function GetContent($id){ 
        $dis=D('diseases');
        $map['id']=$id;
        $name=$dis->where($map)->getField('content',true);
        echo $name[0];
    }
    public function test(){
        $a=array("red","green");
        array_push($a,"blue","yellow");
        print_r($a);
    }
    
    //  json 不转换汉字 功能
    public function decodeUnicode($str)
    {
        return preg_replace_callback('/\\\\u([0-9a-f]{4})/i',
        create_function(
            '$matches',
            'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");'
        ),
        $str);
    }
}