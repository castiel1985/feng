<?php
namespace Home\Controller;
use Think\Controller;
use Think\Page;

class ListController extends Controller {
    public function index(){
        header("Content-type: text/html; charset=utf-8");    
        $news=D('news');
    	$count=$news->count();
    	$page=new Page($count, 15);
    	$list=$news->order(array('date'=>desc))->limit($page->firstRow,$page->listRows)->select();
    	$show=$page->show();	 
    	$this->assign('list', $list);
    	$this->assign('page',$show);	
        $this->display('List:index');
    }
    public function get($type){
        $news=D('news');
        $map['type']=$type;
    	$count=$news->count();
    	$page=new Page($count, 15);
    	$list=$news->where($map)->order(array('date'=>desc))->limit($page->firstRow,$page->listRows)->select();
    	$show=$page->show();	 
    	$this->assign('list', $list);
    	$this->assign('page',$show);	
        $this->display('List:index');  
    }
    public function article($id){
        $news=D('news');
        $map['id']=$id;
        $ret=$news->where($map)->select();
        if(!$ret){
            die('没有此文章');
        }
        $this->assign('title',$ret[0][title]);
        $this->assign('date', $ret[0][date]);
        $this->assign('author', $ret[0][author]);
        $this->assign('content', $ret[0][content]);
        $this->display('List:article');  
    }
    
}