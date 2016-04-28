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
    // 定义数据键过期时间
    public $redisKeysExpire = -1;
    // 定义缓存数据设置操作方法
    public $setTypeArray = array("str_" => "string", "list_" => "list", "set_" = "gather");
    // 定义数据值获取命令
    public $redisGetCmd = array(1 => "GET", "SMEMBERS", "LRANDGE");
    // redis 命令列表
    /*
     *public $redisCmdList = array(
     *    "string"  => array(         // 缓存字符串形式
     *        "set"    => array("key", "string"),
     *        "mset"   => array("key", "array"),
     *        "getset" => array("key", "newString"),
     *        "get"    => array("key"),
     *        "mget"   => array("array")
     *    ),
     *    "list"    => array(         // 缓存列表形式
     *        "lpush"  => array("listKey", "listValue"),
     *        "lrange" => array("listKey", "stratIndex", "stopIndex"), // stopIndex为-1代表最后一个元素(-2为倒数第二个);
     *        "lget"   => array("listKey", "index"),
     *        "lset"   => array("listKey", "index", "newListValue"), 
     *    ),
     *    "set"     => array(         // 缓存集合形式
     *        "sadd"   => array("sKey", "sValue"), 
     *        "smembers" => array("sKey")
     *    )
     *);
     */
    // 定义构造函数
    public function __construct() {
        $redisConf = C("REDIS_CONF");
        // 数据键过期时间
        $this->redisKeysExpire = $redisConf['REDIS_KEYS_EXPIRE'];
        $this->redisDB = new Redis($redisConf['REDIS_HOST'], $redisConf['REDIS_PORT'], $redisConf['REDIS_TIMEOUT']);
    }
    // 设置缓存数据
    public function set($key, $cacheData = null, $expire = null) {
        // 设置过期时间
        $expire = is_null($expire) ? $this->redisKeysExpire : $expire;
        $keyPrefixPos = strpos($key, "_");
        $setType = $this->setTypeArray[substr($key, 0, $keyPrefixPos+1)];
        $setCmd = $this->redisSetCmd[$setType];
        // 如果$cache缓存数据为空，则删除该键
        if (is_null($cacheData)) {
            return self::clear($key);
        }
        $cacheResult = $this->$setCmd($key, $cacheData);
        // 设置过期时间,以秒开始-1为一直有效
        if ($expire !== -1) {
            $this->redisDB->expire($key, $expire);
        }
        return $cacheResult;
    }
    /*
     * 获取键数据
     * @param $dataKey要获取数据的键
     * @param $dataIndex 获取的表值的索引
     * @param $endIndex 结束索引-1表示最后一个数据
     */
    public function get($dataKey, $dataIndex = null, $endIndex = null) {
        $getKeyType = $this->redisDB->type($dataKey);
        // 获取值的Redis命令
        $getCmd = $this->redisGetCmd[$getKeyType];
        // 判断键是否存在
        if ($this->redisDB->exists($dataKey)) {
            return $this->redisDB->$getCmd($dataKey, $dataIndex, $endIndex);
        }
        return false;
    }
    // 添加字符串缓存
    public function setString($setKey, $setValue) {

    }
    /*
     * 删除指定数据键
     * 如果键不存在则忽视该命令
     * @param $delKey可以是数组，表示删除多个键
     */
    public function clear($delKey) {
        $this->redisDB->del($delkey);
    }


}
