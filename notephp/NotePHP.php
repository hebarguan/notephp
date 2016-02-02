<?php
 /*
  * Edited By hebarguan in 2016-1-19
  * Email : hebarguan@hotmail.com
  * 框架初始化文件
  */

// 定义根目录
define("__ROOT__", "./");
// 定义notephp根目录
define('__NOTEPHP__',"./notephp");
// 默认app_name 为Home 
defined("APP_NAME") OR  define("APP_NAME" , "Home");
// 服务里主机名
define("SERVER_HOST" ,$_SERVER['SERVER_NAME']);
// 服务器主机地址
define("SERVER_IP" ,$_SERVER['SERVER_ADDR']);
// 根目录找不到则退出程序
defined("APP_PATH") OR  die("找不到根目录，请在入口文件定义");
// 定义项目目录
define("PRO_PATH" , APP_PATH.APP_NAME);

// 加载默认配置
$Defaultconf =  require(__NOTEPHP__."/Conf/default.php");
//　定义公共目录
define("__PUBLIC__","./Public");
// 定义类文件后缀
define("EXTS" , ".class.php");


// 加载框架运行文件
require_once(__NOTEPHP__."/Core/NotePHP".EXTS);
NotePHP::initialize();
?>
