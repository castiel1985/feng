<?php
namespace Home\Controller;
use Think\Controller;

class AreaController extends Controller {
    public $a;
    public function index(){
         header("Content-type: text/html; charset=utf-8");  
         $this->a='1234586';
         //$this->display('Area:index');
        // $this->test();
    }
    public function test(){
         $this->index();
        var_dump($this->a);
    }
    
}