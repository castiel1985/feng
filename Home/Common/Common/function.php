<?php
    function getShortContent($c){
  	$length=20;
  	if(mb_strlen($c)<=20){
  		$content=$c;
  	}else {
  		$content=mb_substr($c, 0 , $length ,'utf8');
  		
  	}
	return $content;
}

    function getShortDate($c){
  	$length=10;
  	if(mb_strlen($c)<=10){
  		$content=$c;
  	}else {
  		$content=mb_substr($c, 0 , $length ,'utf8');
  		
  	}
	return $content;
}
    function getdata($a){
    $a=  trim($a);
    
}
    function getuuid() {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
        mt_rand( 0, 0xffff ),
        mt_rand( 0, 0x0fff ) | 0x4000,
        mt_rand( 0, 0x3fff ) | 0x8000,
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
    function truename($id){
        $user=D('user');
        $map['id']=$id;
        $truename=$user->where($map)->getField('truename');
        return $truename;
    }
}







