<?php 
  
 /*
  * edited in 2016-1-21 11 : 00 worker : hebarguan
  * Email hebarguan@hotmail.com 
  * 入口文件在开发阶段建议设置DEBUG_ON(调试)为TURE
  * define( 'DEBUG_ON' , TRUE );
  * 定义项目名称与目录 define('APP_PATH' , you_app_path);
  */

  define("APP_PATH", "./");
  // 定义项目名称
  define("APP_NAME", "Home");
  // 开启调试模式
  define("DEBUG_ON", true);
  // 定义不显实的错误类型
  define("ERROR_IGNORE_TYPE","E_DEPRECATED,E_STRICT,E_NOTICE");
  // 加载初始化文件
  require_once("./notephp/Tunnel.php");
