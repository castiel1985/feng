<?php
namespace Home\Controller;
use Think\Controller;

class AboutusController extends Controller {
    public function index(){
         header("Content-type: text/html; charset=utf-8");      
         $this->display('Aboutus:index');
    }
    
}