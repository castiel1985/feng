<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Page;

class UserController extends Controller {
    public function __construct(){
        parent::__construct();
        $this->userLogic =  D('User','Logic');
        $this->user =  M('User');
    }
    private $user ;
    private $userLogic ;
    private function checkPriv($priv){
        $adminid = session('adminid');
        if(empty($adminid)) $this->redirect('Adminuser/login',0);
        if(!session('issuper')){
            if(!empty($priv) && !in_array($priv,session('privs'))) $this->error('您没有此权限!.');
        }
        $this->assign('adname', session('name'));
    }
    public function usermgr(){
        $this->checkPriv('5_1_1');
        $p = C('ADMIN_REC_PER_PAGE');     
        $map['type']=0;
        $v=D('user');
        $count=$v->where($map)->where('isdel is null')->count();
        $page=new Page($count, $p);
    	$res=$v->where($map)->where('isdel is null')->order(array('date'=>desc))->limit($page->firstRow,$page->listRows)->select();
        $this->data = $res;  
        $this->total =$count ;
        $page->setConfig('header', '项记录'); 
        $show=$page->show(); 
        $this->assign('page',$show);
        $this->display();
    }
    public function professormgr(){
        $this->checkPriv('5_2_1');
        $p = C('ADMIN_REC_PER_PAGE'); 
        $map['type']=1;
        $v=D('user');
        $count=$v->where($map)->where('isdel is null')->count();
        $page=new Page($count, $p);
    	$res=$v->where($map)->where('isdel is null')->order(array('date'=>desc))->limit($page->firstRow,$page->listRows)->select();
        $this->data = $res;  
        $this->total = $count;
        $page->setConfig('header', '项记录'); 
        $show=$page->show(); 
        $this->assign('page',$show);
        $this->display();
    }
    public function adduser(){
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

            $ret = $this->user->add($newdata);
             
            if($ret){
                $this->redirect('User/usermgr');
                //var_dump($newdata['ct']);
            }else{
                $this->error('插入数据错误');
            }
        }else{
            $this->display("User/useredit");
        }      
    }
    public function check(){
       $data=$_POST['username'];
       $map['username']=$data;
       $map['type']=1;
       $class=D('user');
       $ret=$class->where($map)->select();
       if($ret){
          echo 1;
       } else {
           echo 0;    
       }
    }
    public function upload(){
        $config     = array(
            'maxSize'  => 0,
            'exts'     => array('jpg','gif', 'jpeg', 'png'),
            'rootPath' => '/',   
         );
        $oss_config = array(
            'bucket'     => 'lim-upload',
         );
        $upload =new \Think\Upload($config, 'Oss', $oss_config);; //实例化上传类
        $upload->autoSub = false;
        $upload->savePath = 'professor/'; 
        $upload->replace = true;
        $upload->saveName = date("YmdHis");    
        $info = $upload->upload();  
        if (!$info) {
             $this->error($upload->getError()); 
        } else {
             $this->success('上传成功！'); 
        }
        foreach ($info as $file) {
            $arr['key']=$file['key'];
            $arr['savepath']=$file['savepath'];
            $arr['name']=$file['name'];
            $arr['savename']=$file['savename'];
        }
       $img=$file['savepath'].$file['savename'];
       $_SESSION['img']=$img;    
    }
    public function addprofessor(){
        $this->checkPriv('5_2_2');
        $this->assign('act','add');
        $this->assign('errcode','0');
        if(I('post.act')=='add'){
            $newdata = array();
            $newdata['username'] = I('post.username');
            $newdata['intro'] = I('post.intro');
            $newdata['job'] = I('job');
            $newdata['skilled'] = I('post.skilled');
            $newdata['job_unit'] = I('job_unit');
            $newdata['phone'] = I('post.phone');
            $newdata['mail'] = I('post.mail');
            $newdata['type'] = 1;
            $newdata['img'] = session('img');
            $newdata['truename'] = I('post.truename');
            $_SESSION['img']=null;
            $ret = $this->user->add($newdata);
            if($ret){    
                $this->redirect('User/professormgr');
                
            }else{
                $this->error('插入数据错误');
            }
        }else{
            $this->display("User/professoredit");
        }      
    }    
    public function checkusername(){
       $data=$_POST['title'];
       $map['title']=$data;
       $user=D('user');
       $ret=$user->where($map)->select();
       if($ret){
          echo 1;
       } else {
           echo 0;    
       }
    }
    public function edituser(){
        $this->checkPriv('5_1_3');
        $this->assign('act','edit');
        $this->assign('errcode','0');
        if(I('post.act')=='edit'){
            $newdata = array();
            $userid = I('post.userid','','int');
            $newdata['title'] = I('post.title');
            $newdata['type'] = I('post.type');
            $ret = $this->user->where('userid='.$userid)->save($newdata);   
            if($ret){
                $this->redirect('User/usermgr');
            }else{
                $this->assign('errcode','1');  
                $this->display('User/useredit');
            }
        }else{
            $userid = I('get.userid','','int');
            if($userid){
             $this->data = $this->user->getByUserid($userid);
         
                $this->display("User/useredit");
            }else{
                $this->error('没有该记录');
            }
        }
    }
     public function editprofessor(){
        $this->checkPriv('5_2_3');
        $this->assign('act','edit');
        $this->assign('errcode','0');
        if(I('post.act')=='edit'){
            $newdata = array();
            $map['userid'] = I('post.userid','','int');
            $newdata['intro'] = I('post.intro');
            $newdata['job'] = I('post.job');
            $newdata['skilled'] = I('post.skilled');
            $newdata['job_unit'] = I('post.job_unit');
            $newdata['phone'] = I('post.phone');
            $newdata['mail'] = I('post.mail');
            if($_SESSION['img']){
              $newdata['img'] =  $_SESSION['img'];  
            }
            $newdata['truename'] = I('post.truename');
            $ret = $this->user->where($map)->save($newdata);  
            $_SESSION['img']=null;
            if($ret){
                 $this->success('修改成功','/Admin/User/professormgr',2);
            }else{
                $this->error('修改失败','/Admin/User/professormgr',2);
            }
        }else{
            $userid = I('get.userid','','int');
            session('userid',$userid );
            if($userid){
             $this->data = $this->user->getByUserid($userid);
             $img=$this->user->where('userid='.$userid)->getfield('img');
             $this->assign('img',$img);
                $this->display("User/professoredit");
            }else{
                $this->error('没有该记录');
            }
        }
    }
    public function deluser(){
        $this->checkPriv('5_1_4');
        $userid = I('get.userid','','int');
        if($userid){
            $data['isdel']= date("Y-m-d H:i:s");
            $this->user->where('userid='.$userid)->save($data);
            $from = I('server.HTTP_REFERER');
            redirect($from);
        }else{
            $this->error('该记录不存在');
        }
    }
   public function deluser2(){
        $this->checkPriv('5_2_4');
        $userid = I('get.userid','','int');
        if($userid){
            $data['isdel']= date("Y-m-d H:i:s");
            $this->user->where('userid='.$userid)->save($data);
            $from = I('server.HTTP_REFERER');
            redirect($from);
        }else{
            $this->error('该记录不存在');
        }
    }
    public function chguserstatus(){
        $this->checkPriv('5_1_5');
        $userid = I('get.userid','','int');
        $status = I('get.status','','int');
        if($userid){
            if($status == 0){
                $this->user->where('userid='.$userid)->save(array('status'=>0));
                $from = I('server.HTTP_REFERER');
                redirect($from);
            }else if($status == 1){
                $this->user->where('userid='.$userid)->save(array('status'=>1));
                $from = I('server.HTTP_REFERER');
                redirect($from);
            }else{
                $this->error('无该状态设置');
            }
        }else{
            $this->error('该记录不存在');
        }
    }
        public function chguserstatus2(){
        $this->checkPriv('5_2_5');
        $userid = I('get.userid','','int');
        $status = I('get.status','','int');
        if($userid){
            if($status == 0){
                $this->user->where('userid='.$userid)->save(array('status'=>0));
                $from = I('server.HTTP_REFERER');
                redirect($from);
            }else if($status == 1){
                $this->user->where('userid='.$userid)->save(array('status'=>1));
                $from = I('server.HTTP_REFERER');
                redirect($from);
            }else{
                $this->error('无该状态设置');
            }
        }else{
            $this->error('该记录不存在');
        }
    }
    
    public function setupkeyword(){
       $this->checkPriv('5_1_2');
       $userid = I('get.userid','','int');
       $this->assign('act','edit');
       $this->assign('errcode','0');
       if(I('post.act')=='edit'){
            $newdata = array();
            $from = I('server.HTTP_REFERER');
            $newdata['username'] = I('post.username');
            $newdata['password'] =  md5(I('post.password'));
            $notpass =  md5(I('post.notpass'));
            if($notpass==$newdata['password']&&$notpass){
                $map['username']=$newdata['username'] ;
               $ret=$this->user->where($map)->setfield('password',$newdata['password']);
               if($ret){
                   $this->success("密码修改成功",'/admin/user/usermgr',2) ;     
               }  else {
                   $this->error('密码修改失败','/admin/user/usermgr',2);    
               }
            }
           
       }else{
            if($userid){
                $this->data = $this->user->getByUserid($userid);
                $this->display();
            }else{
                $this->error('没有该记录');
            }  
       } 
    }
     public function setupkeyword2(){
       $this->checkPriv('5_2_2');
       $userid = I('get.userid','','int');
       $this->assign('act','edit');
       $this->assign('errcode','0');
       if(I('post.act')=='edit'){
            $newdata = array();
            $from = I('server.HTTP_REFERER');
            $newdata['username'] = I('post.username');
            $newdata['password'] =  md5(I('post.password'));
            $notpass =  md5(I('post.notpass'));
            if($notpass==$newdata['password']&&$notpass){
                $map['username']=$newdata['username'] ;
               $ret=$this->user->where($map)->setfield('password',$newdata['password']);
               if($ret){
                   $this->success("密码修改成功",'/admin/user/usermgr',2) ;     
               }  else {
                   $this->error('密码修改失败','/admin/user/usermgr',2);    
               }
            }
           
       }else{
            if($userid){
                $this->data = $this->user->getByUserid($userid);
                $this->display('setupkeyword');
            }else{
                $this->error('没有该记录');
            }  
       } 
    }
    
}
