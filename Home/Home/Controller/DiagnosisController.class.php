<?php
namespace Home\Controller;
use Think\Controller;
use Think\Upload;

class DiagnosisController extends Controller {
    public function index(){
         header("Content-type: text/html; charset=utf-8");      
         $class=D('class');
         $map1['typeid']=1;
         $map1['water']=0;
         $ret1=$class->where($map1)->select();
         $this->assign('ret1',$ret1);
         
         $map2['typeid']=1;
         $map2['water']=1;
         $ret2=$class->where($map2)->select();
         $this->assign('ret2',$ret2);         
         
         $map3['typeid']=2;
         $map3['water']=0;
         $ret3=$class->where($map3)->select();
         $this->assign('ret3',$ret3);  
         
         $map4['typeid']=2;
         $map4['water']=1;
         $ret4=$class->where($map4)->select();
         $this->assign('ret4',$ret4);   
         
         $map5['typeid']=3;
         $map5['water']=0;
         $ret5=$class->where($map5)->select();
         $this->assign('ret5',$ret5);          
         
         $map6['typeid']=3;
         $map6['water']=1;
         $ret6=$class->where($map6)->select();
         $this->assign('ret6',$ret6);   
         
         $map7['typeid']=4;
         $map7['water']=0;
         $ret7=$class->where($map7)->select();
         $this->assign('ret7',$ret7);          
         
         $map8['typeid']=4;
         $map8['water']=1;
         $ret8=$class->where($map8)->select();
         $this->assign('ret8',$ret8);            
      
         $this->display('Diagnosis:index');
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
    public function reportid(){
        $user=D('user');
        $condition['username']=$_SESSION['username'];
        $uid=$user->where($condition)->getField('userid');
        $reportid=$uid.'_'.report.'_'.date('YmdHis');
        return $reportid; 
        //echo $reportid;
    }
    public function selectprofessor($name,$expid=0){
         header("Content-type: text/html; charset=utf-8");  
         if($name==null){
            $this->error('选取渔业种类失败',$_SERVER["HTTP_REFERER"],2);
         }
         $user=D('user');
         $map['type']=1;
         $ret=$user->where($map)->select();
         $arr=array();
         for($i=0;$i<count($ret);$i++){
              $arr2 = explode(',',$ret[$i]['skilled']); 
              if(in_array($name, $arr2))
                {
                  $arr3[]=$ret[$i]['userid'];
              }
         }
         if($arr3[0]==null){
             $this->error('没有找到擅长此渔业物种的专家',$_SERVER["HTTP_REFERER"],3);
         }
         if($expid==0){
            $expid= $arr3[0];
         } 
         $cond['userid']=$expid;
         $ret2=$user->where($cond)->select();
         $this->assign('classname',$name);  
         $this->assign('user',$ret2[0]);  
         $condition['userid']=array('in',$arr3);
         $ret3=$user->where($condition)->select();
         $this->assign('list',$ret3); 
         //产生reportid,并且插入临时表
        if(session('tmp_id')==null){
           session('tmp_id',$this->reportid());
        }
         $tmp=D('tmpid');
         $data['tmp_id']= session('tmp_id');
         $ret=$tmp->add($data);
         $this->display('Diagnosis:professor');
     }
     public function upload() {
        // $report_id= date("YmdHis");           //$this->getreportid();
         $config     = array(
            'maxSize'  => 0,
            'exts'     => array('jpg','gif', 'jpeg', 'png'),
            'rootPath' => '/',   
         );
        $oss_config = array(
            'access_id'  => 'TyCHc67E2Bbc3eyl', //阿里云Access Key ID
            'access_key' => 'TyfB8IwQpTikdpPjqR6FE1hLE2VeiA', //阿里云Access Key Secret
            'bucket'     => 'tmp-lim',
         );
        $upload =new \Think\Upload($config, 'Oss', $oss_config);; //实例化上传类
        $upload->autoSub = false;
        $upload->savePath = 'tmp/'.session('tmp_id').'_'; //在根目录Uploads下
        $upload->saveName = $arr;
        $info = $upload->upload(); //执行上传方法     
        if (!$info) {
             $this->error($upload->getError()); //错误了
        } else {
             $this->success('上传成功！'); //成功了
        }

        foreach ($info as $file) {
            $arr['key']=$file['key'];
            $arr['savepath']=$file['savepath'];
            $arr['name']=$file['name'];
            $arr['savename']=$file['savename'];
        }
       $name=$file['savename'];
       $tmp=D('tmpid');
       $map['tmp_id']=session('tmp_id');
       $names=$tmp->where($map)->getField('names'); 
       if($names==null){
           $data['names']=$name;
       }  else {
          $data['names']=$names.','.$name;          
       }
       $tmp->where($map)->save($data);       
    }
    
    public function cleannamesnull(){
        $tmp=D('tmpid');
        $map['names']='';
        $tmp->where($map)->delete();
    }
    public function cleantmpid(){
        $tmp=D('tmpid');
        $map['tmp_id']=session('tmp_id');
        $tmp->where($map)->delete(); 
    }
    
    //生成报告
    public function makereport(){
         header("Content-type: text/html; charset=utf-8");  
         $this->cleannamesnull();
         $data['from_name']=$_SESSION['username'];
         if($data['from_name']==null){
             $this->error('您还没有登录，请先登录', $_SERVER["HTTP_REFERER"],2);
         } else {
             $data['from_userid']=$this->uid();
         }   
          if($_POST['to_userid']==null){
             $this->error('您还没有选择专家请先选择', $_SERVER["HTTP_REFERER"],2);
         } else {
             $data['to_userid']=$_POST['to_userid'];            
         }     
          if($_POST['classname']==null){
             $this->error('您还没有选择物种，请选择', $_SERVER["HTTP_REFERER"],2);
         } else {
             $map['classname']=$_POST['classname'];
             $data['classid']=D('class')->where($map)->getField('classid');       
         }  
         $data['deep']= trim($_POST['deep']);
         $data['size']= trim($_POST['size']);
         $data['area']= trim($_POST['area']);
         $data['unit']=$_POST['unit'];
         $data['uuid']=getuuid();  //生成唯一报告编号
         $data['tmp_id']= session('tmp_id');
         if($_POST['deep']==null||$_POST['size']==null||$_POST['area']==null){
            $this->error('您资料填写不完整，请写完整', $_SERVER["HTTP_REFERER"],2);
        }
         $data['water_desc']=$_POST['water_desc'];
         $data['disease_desc']=$_POST['disease_desc'];
         
         if($this->names()==null){
             $this->error('请上传报告', $_SERVER["HTTP_REFERER"],2);
         }  else {
            $data['img']=$this->names();  
         }
         $report=D('report');
         $ret=$report->add($data);
         if($ret){
            $this->success('生成报告成功',$_SERVER["HTTP_REFERER"],2);     
         } else {
            $this->error('请重新生成', $_SERVER["HTTP_REFERER"],2);
         }
         //查询存在report的uuid, 拷贝文件
         $condition['tmp_id']=session('tmp_id');
         $result=$report->where($condition)->select();
         if($result){
             foreach ($this->imgname() as $value) {
                 $this->copytmpfile($value);
             }
           $this->cleantmpid();
         }
    }
    public function names(){
        $tmp=D('tmpid');
        $map['tmp_id']=session('tmp_id');
        $names=$tmp->where($map)->getField('names'); 
        return $names;
    }

    public function imgname(){
        $names=$this->names();
        if(!$names){
           $this->error('您没有登录','/',2);  
        }
        $array=explode(',',$names); 
        return $array;
    }

    public function copytmpfile($value){
        //导入文件
        vendor('aliyuncs.oss-sdk-php.autoload');    
        $accessKeyId = "TyCHc67E2Bbc3eyl";
        $accessKeySecret = "TyfB8IwQpTikdpPjqR6FE1hLE2VeiA";
        $endpoint = "oss-cn-shanghai.aliyuncs.com";    
        //创建OSS实例
        try {
        $ossClient = new \OSS\OssClient($accessKeyId, $accessKeySecret, $endpoint);
        } catch (OssException $e) {
        print $e->getMessage();
        }    
        //拷贝文件
        $from_bucket = 'tmp-lim';
        $from_object = "tmp/".session('tmp_id').'_'.$value;
        $save_object = "tmp/".$value;
        $to_bucket = 'lim-upload';
        $to_object = $this->uid().'/'.substr($save_object, 4);
        $options = array();
        try{
        $ossClient->copyObject($from_bucket, $from_object, $to_bucket, $to_object, $options);
        } catch(OssException $e) {
      //  printf(__FUNCTION__ . ": FAILED\n");
       // printf($e->getMessage() . "\n");
        return;
        }
       // print(__FUNCTION__ . ": OK" . "\n");
    }
    public function test(){
        vendor('aliyuncs.oss-sdk-php.autoload');    
        $accessKeyId = "TyCHc67E2Bbc3eyl";
        $accessKeySecret = "TyfB8IwQpTikdpPjqR6FE1hLE2VeiA";
        $endpoint = "oss-cn-shanghai.aliyuncs.com";    
        //创建OSS实例
        try {
        $ossClient = new \OSS\OssClient($accessKeyId, $accessKeySecret, $endpoint);
        } catch (OssException $e) {
        print $e->getMessage();
        }    
        
        $bucket='castiel1985';
        //测试访问权限
        $acl = OssClient::OSS_ACL_TYPE_PRIVATE;
           var_dump($acl);
        try {
        $ossClient->putBucketAcl($bucket, $acl);
        } catch (OssException $e) {
        printf(__FUNCTION__ . ": FAILED\n");
        printf($e->getMessage() . "\n");
        return;
        }
        print(__FUNCTION__ . ": OK" . "\n");
     
        
    }

}  