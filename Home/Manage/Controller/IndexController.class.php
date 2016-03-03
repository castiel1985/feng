<?php
namespace Manage\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        if(session('name')==null&&  session('username')==null){
                   $this->redirect('Adminuser/login',0);
               }else{
                   if(session('name')==null){
                     $this->adname  = session('username');  
                   }  else {
                      $this->adname  = session('name');   
                   }

                   $this->display();
               }
         }
}