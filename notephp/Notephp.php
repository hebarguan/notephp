<?php
 /*
  * Edited By hebarguan in 2016-1-19
  * Email : hebarguan@hotmail.com
  * 框架初始化文件
  */

// 定义根目录
define("__ROOT__", "./");
// 定义notephp根目录
define('__NOTEPHP__', "./notephp");
// 默认app_name 为Home 
defined("APP_NAME") OR  define("APP_NAME", "Home");
// 服务里主机名
define("SERVER_HOST", $_SERVER['HTTP_HOST']);
// 服务器名
define("SERVER_NAME", $_SERVER['SERVER_NAME']);
// 项目公共配置和函数库目录
define("__COMMON__" , "./Webapp/Common");
define("SERVER_PROTOCOL", $_SERVER['SERVER_PROTOCOL']);
// 定义本地项目根目录
define("DOCUMENT_ROOT", $_SERVER['DOCUMENT_ROOT']);
// 项目扩展目录
define("__EXTENDS__", "./Webapp/Extends");
// 定义项目目录
define("PRO_PATH", "./Webapp");
// 定义模块目录
define("MODULE_PATH", PRO_PATH."/".APP_NAME);
//　定义公共目录
define("__PUBLIC__", "./Public");
// 定义类文件后缀
define("EXTS", ".class.php");
// 加载框架运行文件
require_once(__NOTEPHP__."/Core/Notephp".EXTS);
NotePHP::initialize();
?>
