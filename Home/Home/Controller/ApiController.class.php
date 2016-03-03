<?php
namespace Home\Controller;
use Think\Controller;

class ApiController extends Controller {
    public function index(){
         header("Content-type: text/html; charset=utf-8");  
         
    }
    public function test(){
         $a=A('report');
       // var_dump($a);
        echo $a->uid();
    }
    
}