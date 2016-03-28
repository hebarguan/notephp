<?php
return array(
    "DB_USER"               => "root" ,
    "DB_HOST"               => "localhost",
    "DB_NAME"               => "ik",
    "DB_PASSWORD"           => "guan",
    "URL_HIDE_MODULE"       => true,
    "URL_MODE"              => 1 ,
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
?>
