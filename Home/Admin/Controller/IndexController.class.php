<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        if(session('name')==null){
            $this->redirect('Adminuser/login',0);
        }else{
            $this->adname  = session('name');
            $this->display();
        }
    }
}