<?php
/*
 * Editer hebarguan in 2016-4-26
 * Email hebarguan@hotmail.com
 * 该类为Redis缓存扩展提供支持
 * 并支持三种数据类型，String(字符串)，List(列表)，Set(集合)
 * 该类同时提供自定义操作链接柄RedisStorage::init();$redisHandle = RedisStorage::$redisHandle;
 * 常见的phpredis命令将在该类使用
 * 更多命令请参考https://github.com/phpredis/phpredis
 */
class RedisStorage 
{
    // 定义自定义操作链接柄
    public static $redisHandle = false;
    // 定义数据键过期时间
    public static $redisKeysExpire = -1;
    // 定义缓存数据设置操作方法
    public static $setTypeArray = array("str_" => "string", "list_" => "list", "set_" => "gather");
    // 定义设置数据缓存命令
    public static $redisSetCmd = array("string" => "SET", "list" => "LPUSH", "gather" => "SADD");
    // 定义初始化函数
    public static function init() 
    {
        $redisConf = C("REDIS_CONF");
        // 数据键过期时间
        self::$redisKeysExpire = $redisConf['REDIS_KEYS_EXPIRE'];
        self::$redisHandle = new Redis;
        self::$redisHandle->connect($redisConf['REDIS_HOST'], $redisConf['REDIS_PORT'], $redisConf['REDIS_TIMEOUT']);
    }
    // 设置缓存数据
    public static function set($key, $cacheData = null, $expire = null)
    {
        if (is_array($key)) {
            while(list($keys,$value) = each($key)) {
                if (is_numeric($keys)) {
                    if (self::clear($value)) $affectedRows++;
                } else {
                    if (is_array($value)) {
                        $cacheData = $value[0];
                        $keyExpire = $value[1];
                    } else {
                        $cacheData = $value;
                        $keyExpire = null;
                    }
                    if (self::add($keys, $cacheData, $keyExpire)) $affectedRows++;
                }
            }
            return $affectedRows;
        } else {
            // 如果$cache缓存数据为空，则删除该键
            if (is_null($cacheData)) {
                return self::clear($key);
            }
            return self::add($key, $cacheData, $expire);
        }
    }
    // 添加缓存数据
    public static function add($key, $cacheData, $expire) 
    {
        // 设置过期时间
        $expire = is_null($expire) ? self::$redisKeysExpire : $expire;
        $keyPrefixPos = strpos($key, "_");
        $setType = self::$setTypeArray[substr($key, 0, $keyPrefixPos+1)];
        $setCmd = self::$redisSetCmd[$setType];
        $cacheResult = self::$redisHandle->$setCmd($key, $cacheData);
        // 设置过期时间,以秒开始-1为一直有效
        if ($expire !== -1) {
            self::$redisHandle->expire($key, $expire);
        }
        return $cacheResult;
    }
    /*
     * 获取键数据
     * @param $dataKey要获取数据的键
     * @param $dataIndex 获取的表值的索引
     * @param $endIndex 结束索引-1表示最后一个数据
     */
    public static function mget($keysArray) 
    {
        while(list(, $value) = each($keysArray)) {
            $dataContainer[$value] = self::get($value);
        }
        return $dataContainer;
    }
    public static function get($dataKey, $start = 0, $stop = -1) 
    {
        // 判断键是否存在
        if (self::$redisHandle->exists($dataKey)) {
            // 获取缓存类型
            $getKeyType = self::$redisHandle->type($dataKey);
            switch ($getKeyType) {
            case 0 :
                $data = false;
                break;
            case 1 :
                $data = self::$redisHandle->GET($dataKey);
                break;
            case 2 :
                $data = self::$redisHandle->SMEMBERS($dataKey);
                break;
            case 3 :
                $data = self::$redisHandle->LRANGE($dataKey, $start, $stop);
                break;
            }
        }
        // 键不存在返回空
        return $data;
    }
    /*
     * 删除指定数据键
     * 如果键不存在则忽视该命令
     * @param $delKey可以是数组，表示删除多个键
     */
    public static function clear($delKey) 
    {
        // 检测键是否存在，防止进程阻塞
        if (self::$redisHandle->exists($delKey)) {
            // 若键不存在强制删除服务器将导致502错误
            return self::$redisHandle->del($delKey);
        }
        return false;
    }
    // 删除当前缓存数据库
    public static function clearDB() 
    {
        return self::$redisHandle->flushDB();
    }
    // 删除所有缓存数据库
    public static function clearAllDB() 
    {
        return self::$redisHandle->flushAll();
    }
}
