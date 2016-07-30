<?php
 // The config should be return a array 
 // Like array("host" => "localhost","dbtype" => "mysql", "password" => "123");

return array(
    "DB_TYPE"                  =>  "" , //　数据库类型
    "DB_HOST"                  =>  "" , //  数据库主机
    "DB_USER"                  =>  "" , //  数据库用户
    "DB_PASSWORD"              =>  "" , // 　数据库密码
    "DB_NAME"                  =>  "" , //  数据库名
    "DB_PERSISTENT_LINK"       =>  false, // 数据库持久连接
    "MYSQL_CONNECT_ENCODING"   =>  "utf8" , // 数据库链接编码
    "CURD_TYPE"                =>  "mysql", // curd处理类型mysql,DatabaseObject两种，默认是DOject 
    "DEFAULT_CONTROLLER"       =>  "Index", // 默认控制器
    "DEFAULT_METHOD"           =>  "index", // 默认操作方法
    "COOKIE_EXPIRES"           =>  "3600" , // 默认１小时
    "COOKIE_PATH"              =>  "/" ,
    "SESSION_EXPIRES"          =>  "3600" ,
    "SESSION_AUTO_START"       =>  true , // session自动开启
    "TEMP_METHOD"              =>  "1" , // 模板方式1,在html文件夹中是index/index.html . 2,是html下index_index.html
    "TEMP_DEFAULT_SUFFIX"      =>  "tpl", // 模板后缀
    "TEMP_L_DELIM"             =>  "{" , // 模板普通开始标签
    "TEMP_R_DELIM"             =>  "}" , // 模板普通结束标签
    "TEMP_L_LIB"               =>  "<" , // 模板类库起始标签
    "TEMP_R_LIB"               =>  ">" , // 模板类库结束标签
    "TEMPLATE_ENGINE"          =>  "Smarty.class" , // 模板引擎入口文件名
    "URL_HIDE_MODULE"          =>  false , // 自动隐藏路由模块
    "URL_CASE_INSENSITIVE"     =>  false , // 路由不区分大小写
    "URL_MODE"                 =>  1,  // 有两种模式,1,2 . 默认为1
    "URL_MAP_RULES"            =>  array() , // 路由映射适合路由2模式
    "URL_REWRITE_RULES"        =>  array() , // 路由重写规则适合路由1模式
    "URL_STATIC_SUFFIX"        =>  "html", // 路由伪静态后缀
    "MODULE_LIST"              =>  "" , // 例如 Home,Admin,Manager
    "MODULE_DEFAULT"           =>  "" , // 例如Home 为你的项目目录
    "SUB_DOMAIN_ON"            =>  false , // 是否开启子域名部署
    "SUB_DOMAIN_RULES"         =>  array() , // 子域名部署规则 例如: 模块 "Admin" => "admin.domain.com",
    "DATA_RETURN_TYPE"         =>  "json", // 默认数据返回类型,json,xml,jason
    "SMARTY_LEFT_DELIMITER"    =>  "{", // smarty 模板引擎开始标签
    "SMARTY_RIGHT_DELIMITER"   =>  "}", // smarty 模板引擎结束标签
    "SMARTY_TEMPLATE_CACHE"    =>  false , // smarty 模板缓存
    "SMARTY_CACHE_LIFETIME"    =>  3600, // smarty模板缓存时间
    "GET_FIELDS_LENGTH"        =>  6 , // 路由模式二路由重写$_GET的最大字段长度
    "SESSION_DRIVER_OPEN"      =>  false, // 是否开始session驱动
    "SESSION_EXPIRE"           =>  3600, // session默认过期时间
    "SESSION_TABLE"            =>  "", // session驱动的数据库表
    "REDIRECT_FILE"            =>  "./notephp/Tpl/redirect.tpl", // 页面跳转页面
    "REDIS_CONF"               =>  array( // Redis缓存配置
        "REDIS_KEYS_EXPIRE" => -1,
        "REDIS_HOST"        => "localhost", 
        "REDIS_PORT"        => 6379,
        "REDIS_TIMEOUT"     => 0 
    ),
    "MEMCACHED_CONF"           =>  array( // Mencached配置
        "SERVERS"    => array(array("localhost", 11211, 100)), // Mencached服务器连接池
        "EXPIRATION" => 0       // 数据键过期时间，0为持久有效
    ),
);
