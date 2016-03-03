<?php
namespace Home\Controller;
use Think\Controller;

class PersonController extends Controller {
    public function index(){
         header("Content-type: text/html; charset=utf-8");      
         echo 123;
    }

    public function reg(){
        $this->display('Person:index');
    }
    public function save(){
        $data['username']=  trim($_POST['username']);
        $data['password']= md5(trim($_POST['pass']));
        $data['truename']=  trim($_POST['truename']);
        $data['province']=  trim($_POST['province']);
        $data['city']=  trim($_POST['city']);
        $data['phone']=  trim($_POST['phone']);
        $data['address']=  trim($_POST['address']); 
        $user=D('user'); 
         if(strlen($data['username']) <6||strlen($_POST['pass'])<6||strlen($_POST['notpass'])<6||strlen($_POST['phone'])<11||strlen($_POST['truename'])<2){
             exit();
         }
          $ret=$user->add($data);
        if($ret){
           $data['status']  = 1;
           $data['info'] ='恭喜您，注册成功，请首页登录';
           $this->ajaxReturn($data);
        } else {
           $data['status']  = 0;
           $data['info'] ='注册失败，请重新注册';
           $this->ajaxReturn($data);
        }
    }
    public function checkname(){
       $data=$_POST['username'];
       $map['username']=$data;
       $user=D('user');
       $ret=$user->where($map)->select();
       if($ret){
          echo 1;
       } else {
           echo 0;    
       }
    }
    public function logout(){
        $user=D('user');
        session(null); 
        $this->success('登出成功', '/',1);
    }
  
    //普通用户登录
    public function login(){
        header("Content-type: text/html; charset=utf-8");   
         $username=  trim($_POST['username']);
         $password=  trim($_POST['password']);
         if($username==null||$password==null){
             echo "用户名密码输入不完整，请重新输入";
         }  else {
             $user=D('user');
             $map['username']=$username;
             $map['password']=md5($password);
             $map['type']=0;
             $ret=$user->where($map)->select();
             if(!$ret){
                // echo $map['password'];
                echo '用户名或者密码不正确';
             } else {
                 $_SESSION['username']=$username;
                 $_SESSION['date']=$ret[0]['login_date']; 
                 $data['login_date']=date('Y-m-d H:i:s');
                 $user->where($map)->save($data);
                 echo "恭喜您，登录成功";
             }
         }
     }
         //专家登录
       public function login2(){
         $username=$_POST['username'];
         $password=$_POST['password'];
         if($username==null||$password==null){
             echo "用户名密码输入不完整，请重新输入";
         }  else {
             $user=D('user');
             $map['username']=$username;
             $map['password']=md5($password);
             $map['type']='1';
             $ret=$user->where($map)->select();
             if(!$ret){
                 $data['status']  = 0;
                 $data['info'] = '用户名或者密码不正确,请重新登录！';
                 $this->ajaxReturn($data);
             } else {
                 $_SESSION['name']=$username;
                 $_SESSION['date']=$ret[0]['login_date'];
                 $data2['login_date']=date('Y-m-d H:i:s');
                 $user->where($map)->save($data2);
                 $data['status']  = 1;
                 $data['info'] ='恭喜您，登录成功';
                 $this->ajaxReturn($data);
             }
         }
     }
     public function professor(){
         
         $this->display('Person:professor');
     }

}