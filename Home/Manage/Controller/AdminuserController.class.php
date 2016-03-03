<?php
namespace Manage\Controller;
use Think\Controller;
class AdminuserController extends Controller {
    public function chgpasswd(){
		$this->display();
    }

    public function login(){
        if(IS_POST){
            $Admin = M('manager');
            $admininfo = $Admin->getByUsername(I('post.user'));
            if($admininfo != null){
                if((int)$admininfo['status'] == 1){
                    $this->errmsg  = '账户被冻结无法登陆！';
                }else{
                    $tpass = TransPassUseSalt(I('post.pass'), $admininfo['salt']);
                    if($tpass == $admininfo['password']){
                        session('expire',300);
                        session('name', $admininfo['nickname']);
                        session('adminid', $admininfo['uid']);
                        session('issuper', (int)$admininfo['issuper']);
                        if($admininfo['privgid']){
                            $Admingroup = M('role');
                            $privinfo = $Admingroup->getById($admininfo['privgid']);
                            $spriv = explode(',',$privinfo['priv']);
                            $this->initmenu($spriv);
                            session('privs', $spriv);
                        }else if(session('issuper')){
                            $this->initmenu(array());
                        }
                        $this->redirect('Index/index',0);
                    }else{
                        $this->errmsg  = '账户名或密码错误！';
                    }
                }
            }else{
                $this->errmsg  = '你不是管理员吧？';
            }
        }
        $this->display();
    }

    private function initmenu($privarr){
        $allmenu = array('1_1_0','1_2_0','1_3_0','2_0','3_0','9_1_0','9_2_0');
        $truemenu = array();
        if(session('issuper')){
            $truemenu = $allmenu;
        }else{
            foreach($allmenu as $m){
                if (in_array($m, $privarr)) {
                    $truemenu[] = $m;
                }
            }
        }
        session('menupriv',$truemenu);
        return true;
    }

    public function logout(){
        session('[destroy]');
        $this->redirect('Adminuser/login',0);
    }
}