<?php
/*
 * Editer hebarguan in 2016-4-26
 * Email hebarguan@hotmail.com
 * 该类为Redis缓存扩展提供支持
 * 并支持三种数据类型，String(字符串)，List(列表)，Set(集合)
 * 该类同时提供自定义操作链接柄$redisDB = Cache::getConnect();
 * 常见的phpredis命令将在该类使用
 * 更多命令请参考http://http://www.cnblogs.com/ikodota/archive/2012/03/05/php_redis_cn.html
 */
class Cache 
{
    // 定义自定义操作链接柄
    public $redisDB = false;
    // redis 命令列表
    public $redisCmdList = array(
        "string"  => array(         // 缓存字符串形式
            "set"    => array("key", "string"),
            "mset"   => array("key", "array"),
            "getset" => array("key", "newString"),
            "get"    => array("key"),
            "mget"   => array("array")
        ),
        "list"    => array(         // 缓存列表形式
            "lpush"  => array("listKey", "listValue"),
            "lrange" => array("listKey", "stratIndex", "stopIndex"), // stopIndex为-1代表最后一个元素(-2为倒数第二个);
            "lget"   => array("listKey", "index"),
            "lset"   => array("listKey", "index", "newListValue"), 
        ),
        "set"     => array(         // 缓存集合形式
            "sadd"   => array("sKey", "sValue"), 
            "smembers" => array("sKey")
        )
    );

}
