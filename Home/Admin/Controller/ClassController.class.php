<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Page;

class ClassController extends Controller {
    public function __construct(){
        parent::__construct();
        $this->classLogic =  D('Class','Logic');
        $this->class =  M('Class');
    }
    private $class ;
    private $classLogic ;
    private function checkPriv($priv){
        $adminid = session('adminid');
        if(empty($adminid)) $this->redirect('Adminuser/login',0);
        if(!session('issuper')){
            if(!empty($priv) && !in_array($priv,session('privs'))) $this->error('您没有此权限!.');
        }
        $this->assign('adname', session('name'));
    }
    public function classmgr(){
        $this->checkPriv('1_1');
        $p = C('ADMIN_REC_PER_PAGE');     
        $count=$this->classLogic->getClassTotal();
        $vid=D('class');
        $page=new Page($count, $p);
    	$res=$vid->where('isdel is null')->order(array('date'=>desc))->limit($page->firstRow,$page->listRows)->select();
        $this->data = $res;  
        $this->total = $this->classLogic->getClassTotal(); 
        $page->setConfig('header', '项记录'); 
        $show=$page->show(); 

        $this->assign('page',$show);
        $this->display();
    }
    public function categorymgr(){
        $p = C('ADMIN_REC_PER_PAGE');     
        $count=$this->classLogic->getClassTotal();
        $vid=D('class');
        $page=new Page($count, $p);
    	$res=$vid->where('isdel is null')->order(array('date'=>desc))->limit($page->firstRow,$page->listRows)->select();
        $this->data = $res;  
        $this->total = $this->classLogic->getClassTotal(); 
        $show=$page->show(); 
        $this->assign('page',$show);
        $this->display();
    }
    public function addclass(){
        $this->checkPriv('1_2');
        $this->assign('act','add');
        $this->assign('errcode','0');
        if(I('post.act')=='add'){
            $newdata = array();
            $newdata['classname'] = I('post.classname');
            $newdata['typeid'] = I('post.typeid');
            $newdata['water'] = I('post.water');
            $ret = $this->class->add($newdata);   
            if($ret){
                $this->redirect('Class/classmgr');
                var_dump($newdata['ct']);
            }else{
                $this->error('插入数据错误');
            }
        }else{
            $this->display("Class/classedit");
        }    
    }
    
    
    public function checkclassname(){
       $data=$_POST['classname'];
       $map['classname']=$data;
       $class=D('class');
       $ret=$class->where($map)->select();
       if($ret){
          echo 1;
       } else {
           echo 0;    
       }
    }
    public function editclass(){
        $this->checkPriv('1_3');
        $this->assign('act','edit');
        $this->assign('errcode','0');
        if(I('post.act')=='edit'){
            $newdata = array();
            $id = I('post.classid','','int');
            $newdata['classname'] = I('post.classname');
            $newdata['typeid'] = I('post.typeid');
            $newdata['water'] = I('post.water');
            $ret = $this->class->where('classid='.$id)->save($newdata);
            if($ret){
                $this->redirect('Class/classmgr');
            }else{
                $this->assign('errcode','1');  // 修改失败
                $this->display('Class/classedit');
            }
        }else{
            $classid = I('get.classid','','int');
            if($classid){
             $this->data = $this->class->getByClassid($classid);
             //var_dump($this->data);
             $this->display('Class/classedit');
            }else{
                $this->error('没有该记录');
            }
        }
    }
    public function delclass(){
        $this->checkPriv('1_4');
        $classid = I('get.classid','','int');
        if(classid){
            $data['isdel']= date("Y-m-d H:i:s");;
            $this->class->where('classid='.$classid)->save($data);
            $from = I('server.HTTP_REFERER');
            redirect($from);
        }else{
            $this->error('该记录不存在');
        }
    }
    public function chgclassstatus(){
        $this->checkPriv('1_5');
        $classid = I('get.classid','','int');
        $status = I('get.status','','int');
        if(classid){
            if($status == 1){
                $this->class->where('classid='.$classid)->save(array('status'=>1));
                $from = I('server.HTTP_REFERER');
                redirect($from);
            }else if($status == 2){
                $this->class->where('classid='.$classid)->save(array('status'=>2));
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