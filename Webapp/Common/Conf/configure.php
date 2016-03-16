<?php
return array(
    "DB_USER"               => "root" ,
    "DB_NAME"               => "db_user",
    "DB_PASSWORD"           => "guan",
    "URL_HIDE_MODULE"       => true,
    "URL_MODE"              => 2 ,
    "URL_MAP_RULES"         => array(
        "/view/:id/:var/:ps/:oc/:cop"  => "/index/index",
    ),
    "URL_REWRITE_RULES"     => array(
        "/^(\/ps)/"  => "/Index/out",
        "/^(\/emp)/" => "/Index/index",
    ),
);
?>
