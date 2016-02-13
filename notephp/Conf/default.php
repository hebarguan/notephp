<?php
 // The config should be return a array 
 // Like array("host" => "localhost","dbtype" => "mysql", "password" => "123");

return array(
    "DB_TYPE"                  =>  "" , //　数据库类型
    "DB_USER"                  =>  "" , //  数据库用户
    "DB_PASSWORD"              =>  "" , // 　数据库密码
    "DB_NAME"                  =>  "" , //  数据库名
    "DEFAULT_INDEX"            =>  "Index", // 默认控制器
    "DEFAULT_HANDLE"           =>  "index", // 默认操作方法
    "EXCEPTION_FILE"           =>  __NOTEPHP__."/Tpl/exception.tpl",// 默认异常文件
    "ERROR_FILE"               =>  __NOTEPHP__."/Tpl/error.tpl" , // 默认错误文件
    "REDIRECT_FILE"            =>  __NOTEPHP__."/Tpl/redirect.tpl",// 重定向文件
    "DEFAULT_ERROR_MESSAGE"    =>  "发生错误....", // 默认错误提示
    "COOKIE_EXPIRES"           =>  "3600" , // 默认１小时
    "COOKIE_PATH"              =>  "/" ,
    "SESSION_EXPIRES"          =>  "3600" ,
    "TEMP_METHOD"              =>  "1" , // 模板方式1,在html文件夹中是index/index.html . 2,是html下index_index.html
    "TEMP_DEFAULT_SUFFIX"      =>  "tpl", // 模板后缀
    "TEMP_L_DELIM"             =>  "{" , // 模板普通开始标签
    "TEMP_R_DELIM"             =>  "}" , // 模板普通结束标签
    "TEMP_L_LIB"               =>  "<" , // 模板类库起始标签
    "TEMP_R_LIB"               =>  ">" , // 模板类库结束标签
    "TEMPLATE_ENGINE"          =>  "Smarty" , // 默认模板引擎,若使用Smarty引擎,则为Smarty
    "URL_HIDE_MODULE"          =>  false , // 自动隐藏路由模块
    "URL_CASE_INSENSITIVE"     =>  "true" , // 路由不区分大小写
    "URL_MODE"                 =>  "1" , // 有两种模式,1,2 . 默认为1
    "URL_MAP_RULES"            =>  array() , // 路由映射
    "URL_REWRITE_RULES"        =>  array() , // 路由重写规则
    "URL_STATIC_SUFFIX"        =>  "html", // 路由伪静态后缀
    "MODULE_LIST"              =>  "" , // 例如 Home,Admin,Manager
    "MODULE_DEFAULT"           =>  "" , // 例如Home 为你的项目目录
    "SUB_DOMAIN_ON"            =>  false , // 是否开启子域名部署
    "SUB_DOMAIN_RULES"         =>  array() , // 子域名部署规则 例如: 模块 "Admin" => "admin.domain.com",
    "DATA_RETURN_TYPE"         =>  "json", // 默认数据返回类型,json,xml,jason
    "SMARTY_LEFT_DELIMITER"    =>  "{", // smarty 模板引擎开始标签
    "SMARTY_RIGHT_DELIMITER"   =>  "}", // smarty 模板引擎结束标签
    "SMARTY_TEMPLATE_CACHE"    =>  FALSE , // smarty 模板缓存
    "SMARTY_CACHE_LIFETIME"    =>  "3600", // smarty模板缓存时间
)
?>
