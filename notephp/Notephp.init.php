<?php
 /*
  * edited by hebarguan in 2016-1-19
  * user email hebarguan@hotmail.com
  * 初始化文件
  */

// 定义notephp根目录
define('__NOTEPHP__',"./notephp");
// 默认app_name 为Home 
defined("APP_NAME") OR  define("APP_NAME" , "Home");
// 根目录找不到则退出程序
defined("APP_PATH") OR  die("找不到根目录，请在入口文件定义");
// 定义项目目录
define("PRO_PATH" , APP_PATH.APP_NAME);

// 加载用户配置
$Userconf = require(PRO_PATH."/conf/conf.php");
// 加载默认配置
$Defaultconf =require(__NOTEPHP__."/Conf/default.php");


// Notephp 初始化类
class Notephp {

    // 定义项目下面的基本目录结构
    private $_dir = array(
        "cac"     =>  "cache" ,    //"缓存目录,模板编译解析后的文件" ,
        "lib"     =>  array("model" ,"controller","view"),
        "log"     =>  "log" ,     //"错误日志记录目录",
        "ext"     =>  "extends" , //"扩展目录，自定义扩展",
        "htm"     =>  "html" ,    //"模板原始文件,未解析html文件",    例UserController->index(); 则为User_Index.html;
        "cof"     =>  "conf"
    );

    // 初始操作
    public static function init () {

        // 加载模型类
        @require_once(__NOTEPHP__."/Core/Model/Model.class.php");
        // 加载控制器模型
        @require_once(__NOTEPHP__."/Core/Controller/Controller.class.php";
        // 加载试图模型
        @require_once(__NOTEPHP__."/Core/View/View.class.php";
        // 加载公共函数
        @require_once(__NOTEPHP__."/Common/functions.php";
        // 加载路由处理类
        @require_once(__NOTEPHP__."/URL/URL.class.php";
        // 加载模板类
        @require_once(__NOTEPHP__."/Templates/Templates.class.php";
        
        // 检测项目结构目录是否存在，不存在则创建
        if( !is_dir(PRO_PATH) ) {

            // 不存在则为初始框架，创建所有结构目录
            mkdir(PRO_PATH);
            mkdir(PRO_PATH."/".$this->_dir("cac"));
            mkdir(PRO_PATH."/".$this->_dir("lib"));
            mkdir(PRO_PATH."/".$this->_dir("log"));
            mkdir(PRO_PATH."/".$this->_dir("ext"));
            mkdir(PRO_PATH."/".$this->_dir("htm"));
            mkdir(PRO_PATH."/".$this->_dir["cof"]);

            foreach($this->_dir("lib") as $k => $v) {

                mkdir(PRO_PATH."/".$k."/".$v);
            }
            
        }

    }
}
    // 是否开启调试模式
if(defined("DEBUG_ON")) {
    ini_set("display_errors" , "On");
}else{
    ini_set("display_errors" , "Off");
}
// 初始化notephp mvc
Notephp::init();


?>
