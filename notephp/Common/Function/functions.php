<?php
 /*
  * This is a pulic common function library
  * functions.php will be loading in first initialize
  * can be use in web root !
  * from hebarguan editer hebarguan@hotmail.com
  */

// 实例化项目控制器
function Controller($ctrl)
{
    $ctrlName = ucfirst(strtolower($ctrl))."Controller";
    $ctrlFile = PRO_PATH."/{$GLOBALS['PROJECT_REQUEST_MODULE']}/Controller/$ctrlName".EXTS;
    if (is_file($ctrlFile)) {
        require_once($ctrlFile);
    } else {
        trigger_error('控制器类文件'.$ctrlFile.'不存在', E_USER_ERROR);
    }
    return new $ctrlName;
}
// 配置常量获取函数
function C($val)
{
    $split = explode(".", $val);
    $userConfFileName = "configure.php";
    $userConfPath = __COMMON__."/Conf/";
    $userConfFile = $userConfPath.$userConfFileName;
    $defaultConfFile = __NOTEPHP__."/Common/Conf/default.php"; 
    // 加载用户配置文件
    $userConf = is_file($userConfFile) ? require($userConfFile) : array();
    // 默认配置文件
    $defaultConf = is_file($defaultConfFile) ? require($defaultConfFile) : array();
    $conf = array_merge($defaultConf, $userConf);
    // 访问特定模块
    $moduleName = $GLOBALS['PROJECT_REQUEST_MODULE'];
    if (APP_NAME != $moduleName && $moduleName) {
        // 更改配置文件
        $specifiedModuleConfFile = $userConfPath.strtolower($moduleName).".php";
        if (is_file($specifiedModuleConfFile)) {
            $specifiedModuleConf = include($specifiedModuleConfFile);
            $conf = array_merge($conf, $specifiedModuleConf);
        }
    }
    while ($constName = array_shift($split)) {
        $tmpVal = $conf[$constName];
        $constVal = $tmpVal;
        $conf = $tmpVal;
    }
    return $constVal;
}
// 加载php扩展文件
function loadFile($filePath)
{
    $outlinePath = explode(".", $filePath, 3);
    $extendsRootPath = $outlinePath[0];
    $extendsDirName = $outlinePath[1];
    $entranceFile = $outlinePath[2].".php";
    $ergodicPath = "./$extendsRootPath/Extends/$extendsDirName";
    $rPath = ergodicPath($ergodicPath, $entranceFile);
    if ($rPath === false) trigger_error("扩展入口文件{$ergodicPath}/{$entranceFile}不存在" ,E_USER_ERROR);
    return $rPath;
}
// 遍历目录
function ergodicPath($path,$fileNameToSearch)
{
    if (is_dir($path)) {
        $fp = opendir($path);
        while ($item = readdir($fp)) {
            if ($item == "." OR $item == "..") continue;
            $secondPath = $path."/";
            if ($item == $fileNameToSearch) {
                return require_once($secondPath.$item);
            } elseif (is_dir($loopEgiPath = $secondPath.$item)) {
                ergodicPath($loopEgiPath ,$fileNameToSearch);
            }
        }
    } else {
        return false;
    }
}
// 实例化模型
function M($model ,$bool = true)
{
    // 如$boo 为true 表示模型存在
    if ($bool) {
        $modelName = $model."Model";
        if (is_file(PRO_PATH."/".$GLOBALS['PROJECT_REQUEST_MODULE']."/Model/".$modelName.EXTS)) {
            $modelClassName = $model."Model";
            return new $modelClassName();
        } else {
            trigger_error($modelName."模型类不存在" ,E_USER_ERROR);
        }
    } else {
        // 表示不存在模型
        return new Model($model);
    }
}
/*
 * 数据加密函数
 * 更多请参考php.net/mcrypt
 * @param $data 要加密的数据
 * @param $secretKey 加密密钥
 */
function SysCrypt($data, $secretKey)
{
    // 将加密字符串进行sha1加密为十六进制数字再转化为字符
    $key = pack('H*', sha1($secretKey));
    //为 CBC 模式创建随机的初始向量
    //注意 初始向量是随机唯一的
    //所以必须保存至加密后的字符串中，才能加密
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    //（因为默认是使用 0 来补齐数据）
    $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv);
    //将初始向量附加在密文之后，以供解密时使用
    $ciphertext = $iv . $ciphertext;
    //对密文进行 base64 编码
    $ciphertext_base64 = base64_encode($ciphertext);
    // 添加urlencode防止GET传输错误
    return urlencode($ciphertext_base64);
}

function SysDecrypt($cryptData, $secretKey)
{
    // --- 解密 ---
    // $iv_size 按加密模式默认是16
    $iv_size = 16;
    $key = pack('H*', sha1($secretKey));
    $ciphertext_dec = base64_decode(urldecode($cryptData));
    // 初始向量大小，可以通过 mcrypt_get_iv_size() 来获得
    $iv_dec = substr($ciphertext_dec, 0, $iv_size);
    //获取除初始向量外的密文
    $ciphertext_dec = substr($ciphertext_dec, $iv_size);
    // 可能需要从明文末尾移除 0
    $plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);
    return $plaintext_dec;
}
/*
 * 数据缓存函数
 * Memcached类将在该函数使用
 * Memcached适合缓存1024kb 下的数据(单个$item)
 * 若要存储更大的数据,请使用RedisStorage类
 * 该函数在内部获取参数列表
 */
function Cache()
{
    // 获取Memcached配置
    $memcachedConf = C("MEMCACHED_CONF");
    $memInstance = new Memcached();
    $memInstance->addServers($memcachedConf['SERVERS']);
    // 默认过期时间
    $expiration = $memcachedConf['EXPIRATION'];
    // 0参数返回实例
    $paramNum = func_num_args();
    if ($paramNum === 0)  return $memInstance;
    // 获取参数列表
    $paramList = func_get_args();
    // 检测第一个参数是否为数组
    $setKeyType = is_array($paramOne = $paramList[0]) ? "Multi" : "";
    if ($setKeyType) {
        // 检测参数一是添加还是删除数组
        $paramArrayKeys = array_keys($paramOne);
        $checkSetOrDel = is_numeric($paramArrayKeys[0]);
    }
    // 1个参数表示获取缓存数据，可以是数组
    if ($paramNum === 1 && ($checkSetOrDel OR !$setKeyType)) {
        $getCmd = "get".$setKeyType;
        //exit($paramList[0]);
        return $memInstance->$getCmd($paramOne);
    } else {
        list($setKey, $setValue, $expire) = $paramList;
        if (!is_null($setValue) && empty($setValue)) {

            // Cache($setKey, "", $delayed)模式为删除该键缓存数据
            $deleteCmd = "delete".$setKeyType;
            // 检测是否设置延迟删除
            $delayed = is_null($expire) ? 0 : $expire;
            if (!$memInstance->$deleteCmd($setKey, $delayed)) {
                return $memInstance->getResultCode();
            }
            return true;
        } else {
            // 若数据被设置且不为空则缓存
            //数组模式$setValue为
            $expire = $setKeyType ? $setValue : $expire;
            $expire = is_null($expire) ? $expiration : $expire;
            $setCmd = "set".$setKeyType;
            if ($setKeyType) {
                $setResult = $memInstance->$setCmd($setKey, $expire);
            } else {
                $setResult = $memInstance->$setCmd($setKey, $setValue, $expire);
            }
            return $setResult;
        }
    }
}

