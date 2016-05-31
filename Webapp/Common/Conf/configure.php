<?php
return array(
    "DB_TYPE"               => "mysql",
    "DB_USER"               => "root" ,
    "DB_HOST"               => "localhost",
    "DB_NAME"               => "notephp",
    "DB_PASSWORD"           => "guan",
    "SESSION_TABLE"         => "session",
    "SESSION_DRIVER_OPEN"   => false,
    //"MODULE_LIST"           => "Home,Admin,Manage",
    //"MODULE_DEFAULT"        => "Home",
    "URL_HIDE_MODULE"       => true,
    "URL_MODE"              => 2 ,
    "URL_MAP_RULES"         => array(
        "/view/:id/:var/:ps/:oc/:cop"  => "/index/index",
    ),
    "URL_REWRITE_RULES"     => array(
        "/^(\/ps)/"  => "/Index/out",
        "/^(\/emp)/" => "/Index/index",
    ),
    "SUB_DOMAIN_RULES"     => array(
        "manage.stando.cn"  => "Admin"
    ),
);
