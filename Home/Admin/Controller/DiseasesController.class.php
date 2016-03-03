<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Page;

class DiseasesController extends Controller {
    public function __construct(){
        parent::__construct();
        $this->diseasesLogic =  D('Diseases','Logic');
        $this->diseases =  M('Diseases');
    }
    private $diseases ;
    private $diseasesLogic ;
    private function checkPriv($priv){
        $adminid = session('adminid');
        if(empty($adminid)) $this->redirect('Adminuser/login',0);
        if(!session('issuper')){
            if(!empty($priv) && !in_array($priv,session('privs'))) $this->error('您没有此权限!.');
        }
        $this->assign('adname', session('name'));
    }
    public function diseasespid(){
        header("Content-type: text/html; charset=utf-8");  
        $ret=D('diseasespid')->distinct(true)->field('name')->select();
        //var_dump($ret) ;
       return $ret;
    }
    public function diseasesmgr(){
        $this->checkPriv('2_1_1');
        $p = C('ADMIN_REC_PER_PAGE');     
        $count=$this->diseasesLogic->getDiseasesTotal();
        $vid=D('diseases');
        $page=new Page($count, $p);
    	$res=$vid->where('isdel is null')->order(array('date'=>desc))->limit($page->firstRow,$page->listRows)->select();
        $this->data = $res;  
        $this->total = $this->diseasesLogic->getDiseasesTotal(); 
        $page->setConfig('header', '项记录'); 
        $show=$page->show(); 
        $this->assign('page',$show);
        $this->display();
    }
    public function adddiseases(){
        $this->checkPriv('2_1_2');
        $this->assign('act','add');
        $this->assign('errcode','0');
        if(I('post.act')=='add'){
            $newdata = array();
            $newdata['name'] = I('post.name');
            $newdata['type'] = I('post.type');
            $newdata['pId'] = I('post.pid');; //I('post.author');
            $newdata['content'] = I('post.ct');
            switch ($newdata['type'])
                {
                case '鱼类':
                  $newdata['typeid']=1;
                  break;
                case '甲壳类':
                  $newdata['typeid']=2;
                  break;
                case '贝类':
                  $newdata['typeid']=3;
                  break;
                default:
                  $newdata['typeid']=4;
                }
            $ret = $this->diseases->add($newdata);
             
            if($ret){
                $this->redirect('Diseases/diseasesmgr');
            }else{
                $this->error('插入数据错误');
            }
        }else{
             $diseasespid=$this->diseasespid();
             $this->assign('diseasepid',$diseasespid);   
            $this->display("Diseases/diseasesedit");
        }
            
            
    }   
    public function check(){
       $data=$_POST['name'];
       $map['name']=$data;
       $diseases=D('diseases');
       $ret=$diseases->where($map)->select();
       if($ret){
          echo 1;
       } else {
           echo 0;    
       }
    }
    public function editdiseases(){
        header("Content-type: text/html; charset=utf-8");  
        $this->checkPriv('2_1_3');
        $this->assign('act','edit');
        $this->assign('errcode','0');
        if(I('post.act')=='edit'){
           
            $newdata = array();
            $newdata['name'] = I('post.name');
            $newdata['type'] = I('post.type');
            $newdata['pId'] = I('post.pid');; //I('post.author');
            $newdata['content'] = I('post.ct');
            if($newdata['type']== '鱼类'){
                $newdata['typeid']='1';
            }elseif ($newdata['type']=='甲壳类') {
                $newdata['typeid']='2';
            }elseif ($newdata['type']=='贝类') {
                $newdata['typeid']='3';
            }  else {
                $newdata['typeid']='4';
            }   
           $id = I('post.id','','int');; // var_dump($newdata);
           $ret = $this->diseases->where('id='.$id)->save($newdata);
            if($ret){
                $this->redirect('Diseases/diseasesmgr');
            }else{
                $this->assign('errcode','1');  // 修改失败
                $this->display('Diseases/diseasesedit');
            }
        }else{
            $id = I('get.id','','int');
            if($id){
             $this->data = $this->diseases->getById($id);
             $diseasespid=$this->diseasespid();
             $this->assign('diseasepid',$diseasespid);                      
            $this->display("Diseases/diseasesedit");
            }else{
                $this->error('没有该记录');
            }
        }
    }
    public function deldiseases(){
        $this->checkPriv('2_1_4');
        $id = I('get.id','','int');
        if($id){
            $data['isdel']= date("Y-m-d H:i:s");;
            $this->diseases->where('id='.$id)->save($data);
            $from = I('server.HTTP_REFERER');
            redirect($from);
        }else{
            $this->error('该记录不存在');
        }
    }
    public function chgdiseasesstatus(){
        $this->checkPriv('2_1_5');
        $id = I('get.id','','int');
        $status = I('get.status','','int');
        if($id){
            if($status == 1){
                $this->diseases->where('id='.$id)->save(array('status'=>1));
                $from = I('server.HTTP_REFERER');
                redirect($from);
            }else if($status == 2){
                $this->diseases->where('id='.$id)->save(array('status'=>2));
                $from = I('server.HTTP_REFERER');
                redirect($from);
            }else{
                $this->error('无该状态设置');
            }
        }else{
            $this->error('该记录不存在');
        }
    }
    public function categorymgr(){
        $this->checkPriv('2_2_1');
        $p = C('ADMIN_REC_PER_PAGE');     
        $count=D('Diseasespid')->count();
        $vid=D('diseasespid');
        $page=new Page($count, $p);
    	$res=$vid->order(array('date'=>desc))->limit($page->firstRow,$page->listRows)->select();
        $this->data = $res;  
        $this->total = $count; 
        $page->setConfig('header', '项记录'); 
        $show=$page->show(); 
        $this->assign('page',$show);
        $this->display();
    }
    public function addcategoryitem(){
        $this->checkPriv('2_2_2'); 
        $this->assign('act','add');
        $this->assign('errcode','0');
        if(I('post.act')=='add'){
            $newdata = array();
            $newdata['name'] = I('post.name');
            $newdata['pname'] = I('post.name');
            $newdata['typeid'] = I('post.typeid');
            $ret = D('diseasespid')->add($newdata);
            if($ret){
                $this->redirect('Diseases/categorymgr');
            }else {
                 $this->error('插入数据错误');
            }
        }
       $this->display('categoryedit');
    }
    public function delcategory(){
        $this->checkPriv('2_2_3'); 
        $id = I('get.id','','int');
        if($id){
           // $data['isdel']= date("Y-m-d H:i:s");;
            D('diseasespid')->where('id='.$id)->delete();
            $from = I('server.HTTP_REFERER');
            redirect($from);
        }else{
            $this->error('该记录不存在');
        }
    }
}