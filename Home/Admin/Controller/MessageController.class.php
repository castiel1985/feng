<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Page;

class NewsController extends Controller {
    public function __construct(){
        parent::__construct();
        $this->newsLogic =  D('News','Logic');
        $this->news =  M('News');
    }
    private $news ;
    private $newsLogic ;
    
    public function index(){
        echo 123;
    }
    private function checkPriv($priv){
        $adminid = session('adminid');
        if(empty($adminid)) $this->redirect('Adminuser/login',0);
        if(!session('issuper')){
            if(!empty($priv) && !in_array($priv,session('privs'))) $this->error('您没有此权限!.');
        }
        $this->assign('adname', session('name'));
    }
    public function newsmgr(){
       // $this->checkPriv('2_1');
        $p = C('ADMIN_REC_PER_PAGE');     
        $count=$this->newsLogic->getNewsTotal();
        $vid=D('news');
        $page=new Page($count, $p);
    	$res=$vid->where('isdel is null')->order(array('date'=>desc))->limit($page->firstRow,$page->listRows)->select();
        $this->data = $res;  
        $this->total = $this->newsLogic->getNewsTotal(); 
        $show=$page->show(); 
        $this->assign('page',$show);
        $this->display();
    }
    public function addnews(){
        //$this->checkPriv('2_2');
        $this->assign('act','add');
        $this->assign('errcode','0');
        if(I('post.act')=='add'){
            $newdata = array();
            $newdata['title'] = I('post.title');
            $newdata['type'] = I('post.type');
            $newdata['author'] = session('name'); //I('post.author');
            $newdata['content'] = I('post.ct');
            $area= I('post.area');
            foreach($area as $value){ 
                if(!$str){
                  $str=$value;  
                }else{
                  $str=$str.','.$value;  
                }
            }
            $newdata['province']=$str;

            $ret = $this->news->add($newdata);
             
            if($ret){
                $this->redirect('News/newsmgr');
                var_dump($newdata['ct']);
            }else{
                $this->error('插入数据错误');
            }
        }else{
            $this->display("News/newsedit");
        }
            
            
    }
    
    
    public function checktitle(){
       $data=$_POST['title'];
       $map['title']=$data;
       $news=D('news');
       $ret=$news->where($map)->select();
       if($ret){
          echo 1;
       } else {
           echo 0;    
       }
    }
    public function editnews(){
        header("Content-type: text/html; charset=utf-8");  
        $this->checkPriv('2_3');
        $this->assign('act','edit');
        $this->assign('errcode','0');
        if(I('post.act')=='edit'){
            $newdata = array();
            $id = I('post.id','','int');
            $newdata['title'] = I('post.title');
            $newdata['type'] = I('post.type');
            $newdata['author'] = I('post.author');
           // $areainfo= I('post.province');
            $newdata['content'] = I('post.ct');   
            $area= I('post.area');
            foreach($area as $value){ 
                if(!$str){
                  $str=$value;  
                }else{
                  $str=$str.','.$value;  
                }
            }
            $newdata['province']=$str;

            $ret = $this->news->where('id='.$id)->save($newdata);
          
            if($ret){
                $this->redirect('News/newsmgr');
            }else{
                $this->assign('errcode','1');  // 修改失败
                $this->display('News/newsedit');
            }
        }else{
            $id = I('get.id','','int');
            if($id){
             $this->data = $this->news->getById($id);
            $areainfo=$this->data ;
            $areas= explode(',',$areainfo['province']);
           // var_dump($areas);
            unset($areainfo['area']);
            foreach($areas as $v){
                $areainfo['area'][$v] = 'checked';
            }
            $this->assign('areainfo',$areainfo);                    
                        
                $this->display("News/newsedit");
            }else{
                $this->error('没有该记录');
            }
        }
    }
    public function delnews(){
      //  $this->checkPriv('2_4');
        $id = I('get.id','','int');
        if($id){
            $data['isdel']= date("Y-m-d H:i:s");;
            $this->news->where('id='.$id)->save($data);
            $from = I('server.HTTP_REFERER');
            redirect($from);
        }else{
            $this->error('该记录不存在');
        }
    }
    public function chgnewsstatus(){
        $id = I('get.id','','int');
        $status = I('get.status','','int');
        if($id){
            if($status == 1){
                $this->news->where('id='.$id)->save(array('status'=>1));
                $from = I('server.HTTP_REFERER');
                redirect($from);
            }else if($status == 2){
                $this->news->where('id='.$id)->save(array('status'=>2));
                $from = I('server.HTTP_REFERER');
                redirect($from);
            }else{
                $this->error('无该状态设置');
            }
        }else{
            $this->error('该记录不存在');
        }
    }
}