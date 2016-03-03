<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller {
    public function index(){
        header("Content-type: text/html; charset=utf-8");   
        $new=D('news');
        $ret=$new->order('date desc')->limit(6)->select() ;
        $this->assign('last' , $ret);
        
        $map1['type']='工作动态';
        $map1['status']='1';
        $ret1=$new->where($map1)->order('date desc')->limit(6)->select() ;
        $this->assign('work' , $ret1);
        
        $map2['type']='政策法规';
        $map2['status']='1';
        $ret2=$new->where($map2)->order('date desc')->limit(6)->select() ;
        $this->assign('rule' , $ret2);
        
        $map3['type']='预测预报';
        $map3['status']='1';
        $ret3=$new->where($map3)->order('date desc')->limit(6)->select() ;
        $this->assign('test',$ret3);
        
         
        $this->display('Index:index');
    }
}