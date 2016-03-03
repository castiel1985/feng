<?php
return array(
	//'配置项'=>'配置值'
    'DB_TYPE'   => 'mysql', 
    'DB_HOST'   => 'localhost',
    'DB_NAME'   => 'onlinecheck',
    'DB_USER'   => 'root',
    'DB_PWD'    => '',
    'DB_PORT'   => 3306, 
    'DB_PREFIX' => 'pn_',
    'URL_CASE_INSENSITIVE' =>true,  
  //  'SHOW_PAGE_TRACE' =>true, 
    'SITE_NAME'	=>'Limpid',
    'MODULE_ALLOW_LIST' => array('Home','Admin','Manage'),
    'DEFAULT_MODULE' => 'Home',
     'ADMIN_REC_PER_PAGE'=>10,  
);