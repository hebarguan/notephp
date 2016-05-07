<?php
 /*
  * This is a pulic common function library
  * functions.php will be loading in first initialize
  * can be use in web root !
  * from hebarguan editer hebarguan@hotmail.com
  */

// 实例化项目控制器
function A($ctrl) {
    $CtrlName = ucfirst(strtolower($ctrl))."Controller";
    $NewClassName = $CtrlName."\\".$CtrlName;
    spl_autoload_register(function ($ClassName) {
        $loadClass = explode('\\' , $ClassName);
        require_once(PRO_PATH."/lib/controller/".$loadClass[1].".class.php");
    });
    $instance = new $NewClassName();
    return $instance;
}
// 配置常量获取函数
function C ($val) {
    $split = explode(".",$val);
    $keyNum = count($split);
    $userConfFile = __COMMON__."/Conf/configure.php";
    $defaultConfFile = __NOTEPHP__."/Common/Conf/default.php"; 
    // 加载用户配置文件
    $userConf = is_file($userConfFile) ? require($userConfFile) : array();
    // 默认配置文件
    $defaultConf = is_file($defaultConfFile) ? require($defaultConfFile) : array();
    $conf = array_merge($defaultConf ,$userConf);
    if( 1 == $keyNum ) {
        return $conf[$split[0]];
    }else{
        return $conf[$split[0]][$split[1]];
    }
}
// 加载php扩展文件
function loadFile ($filePath) {
    $outlinePath = explode(".",$filePath);
    $ergodicPath = "";
    if( $outlinePath[0] == "Custom" ) {
        $ergodicPath = PRO_PATH."/Extends/".ucfirst($outlinePath[1])."/".$outlinePath[2];
    }else{
        $ergodicPath = __NOTEPHP__."/Extends/".ucfirst($outlinePath[1])."/".$outlinePath[2];
    }
    $rPath = ergodicPath($ergodicPath ,ucfirst($outlinePath[2]).EXTS);
    if( $rPath === false ) trigger_error("类扩展不存在" ,E_USER_ERROR);
    return $rPath;
}
// 遍历目录
function ergodicPath ($path ,$fileNameToSearch) {
    if(is_dir($path)) {
        $fp = opendir($path);
        while ($item = readdir($fp)) {
            if( $item == "." OR $item == ".." ) continue;
            $secondPath = $path."/";
            if($item == $fileNameToSearch) {
                return require_once($secondPath.$item);
            }elseif(is_dir($loopEgiPath = $secondPath.$item)) {
                ergodicPath($loopEgiPath ,$fileNameToSearch);
            }
        }
    }else{
        return false;
    }
}
// 实例化模型
function M ($model ,$bool = true) {
    // 如$boo 为true 表示模型存在
    if ($bool) {
        $modelName = $model."Model";
        if (is_file(PRO_PATH."/".$GLOBALS['PROJECT_REQUEST_MODULE']."/Model/".$modelName.EXTS)) {
            $modelClassName = $model."Model";
            return new $modelClassName();
        }else{
            trigger_error($modelName."模型类不存在" ,E_USER_ERROR);
        }
    }else{
        // 表示不存在模型
        return new Model($model);
    }
}
/*
 * 数据加密函数
 * 更多请参考php.net/mcrypt
 * @param $data 要加密的数据
 * @param $secretKey 加密密钥
 * @param $mode 加密或解密 encode 或 decode 默认加密
 */
function SysCrypt($data, $secretKey) {
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
    return $ciphertext_base64;
}

function SysDecrypt($cryptData, $secretKey) {
    // --- 解密 ---
    // $iv_size 按加密模式默认是16
    $iv_size = 16;
    $key = pack('H*', sha1($secretKey));
    $ciphertext_dec = base64_decode($cryptData);
    // 初始向量大小，可以通过 mcrypt_get_iv_size() 来获得
    $iv_dec = substr($ciphertext_dec, 0, $iv_size);
    //获取除初始向量外的密文
    $ciphertext_dec = substr($ciphertext_dec, $iv_size);
    // 可能需要从明文末尾移除 0
    $plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);
    return $plaintext_dec;
}
