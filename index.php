<?php
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');
//define('BIND_MODULE','Home');
define('APP_DEBUG',true);
define('APP_PATH','./Home/');
define('UPLOAD_PATH','Attachment/');
define('WEB_ROOT','http://192.168.1.188/');
define('THINK_PATH',realpath('./Think').'/');

define('ACCESS_ID','EwCA3b6nGDbKTjMg');
define('ACCESS_KEY','4u8W6PAW6G3fcaIDBea0Ff7g1LSr6B');
define('HOST_OSS','limpid.oss-cn-shanghai.aliyuncs.com');// 这里要写清楚
define('BUCKET','limpid');

require THINK_PATH.'ThinkPHP.php';
