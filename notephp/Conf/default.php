<?php
 // The config should be return a array 
 // Like array("host" => "localhost","dbtype" => "mysql", "password" => "123");

return array(
    "DB_TYPE"              =>  "" , //　数据库类型
    "DB_USER"              =>  "" , //  数据库用户
    "DB_PASSWORD"          =>  "" , // 　数据库密码
    "DB_NAME"              =>  "" , //  数据库名
    "TEMP_METHOD"          =>  "1" , // 模板方式1,在html文件夹中是index/index.html . 2,是html下index_index.html
    "TEMP_SUFFIX"          =>  "html", // 模板后缀
    "DEFAULT_INDEX"        =>  "index", // 默认控制器
    "DEFAULT_HANDLE"       =>  "index", // 默认操作方法
    "EXCEPTION_FILE"       =>  __NOTEPHP__."/Tpl/exception.html",// 默认异常文件
    "ERROR_FILE"           =>  __NOTEPHP__."/Tpl/error.html" , // 默认错误文件
    "COOKIE_EXPIRES"       =>  "3600" , // 默认１小时
    "COOKIE_PATH"          =>  "/" ,
    "SESSION_EXPIRES"      =>  "3600" ,
    "TEMP_L_DELIM"         =>  "{" , // 模板普通开始标签
    "TEMP_R_DELIM"         =>  "}" , // 模板普通结束标签
    "TEMP_L_LIB"           =>  "<" , // 模板类库起始标签
    "TEMP_R_LIB"           =>  ">" , // 模板类库结束标签
    "TEMPLATE_ENGINE"      =>  "NoteEng" , // 默认模板引擎,若使用Smarty引擎,则为Smarty
    "URL_HIDE_MODULE"      =>  false , // 自动隐藏路由模块
    "URL_CASE_INSENSITIVE" =>  "true" , // 路由不区分大小写
    "URL_MODE"             =>  "1" , // 有两种模式,1,2 . 默认为1
    "URL_MAP_RULES"        =>  array() , // 路由映射
    "MODULE_LIST"          =>  "" , // 例如 Home,Admin,Manager
    "DEFAULT_MODULE"       =>  "" , // 例如Home 为你的项目目录
    "SUB_DOMAIN_ON"        =>  false , // 是否开启子域名部署
    "SUB_DOMAIN_RULES"     =>  array() , // 子域名部署规则 例如: 模块 "Admin" => "admin.domain.com",
)
?>
