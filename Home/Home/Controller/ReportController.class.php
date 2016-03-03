<?php
namespace Home\Controller;
use Think\Controller;
use Think\Page;

class ReportController extends Controller {
    public function index(){

    }
    public function reportlist(){
        header("Content-type: text/html; charset=utf-8");    
        $news=D('report');
    	$count=$news->count();
    	$page=new Page($count, 10);
    	$list=$news->order(array('date'=>desc))->limit($page->firstRow,$page->listRows)->select();
    	$show=$page->show();	 
    	$this->assign('list', $list);
    	$this->assign('page',$show);	     
        $this->display('Report:reportlist');   
    }
    public function detail($id){ 
       header("Content-type: text/html; charset=utf-8");  
       $id=$id/9058;
       $report=D('report');
       $map['id']=$id;
       $names=$report->where($map)->getField('img');
       $img=$this->imgnames($names);
       $ret=$report->where($map)->select();
       $this->assign('img',$img);
       $this->assign('data',$ret[0]);
       $this->display('Report:detail');  
       
    }
    public function imgnames($names){
        $str='http://cdn.yuyehulian.com/'.$this->uid().'/';
        $array=explode(',',$names); 
        foreach ($array as $value){
            $arr[]=$str.$value;
        }
        return $arr;
    }
    public function uid(){
        $user=D('user');
        $condition['username']=$_SESSION['username'];
        $uid=$user->where($condition)->getField('userid');   
        if($uid==null){
           $this->error('您没有登录','/',2); 
        }  else {
            return $uid;
        }
    }

}