<?php
    //直接供模版调用的函数

//查询用户表真实姓名
    function truename($id){
        $user=D('user');
        $map['userid']=$id;
        $truename=$user->where($map)->getField('truename');
        return $truename;
    }
    
//查询物种表 物种名称
    function classname($id){
        $user=D('class');
        $map['classid']=$id;
        $truename=$user->where($map)->getField('classname');
        return $truename; 
    }

 //报告 传值参数处理
    function handle($id){
        $a=$id*9058;
        return $a;
    }









