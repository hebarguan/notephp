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
function SysCrypt($data, $secretKey, $mode = "encode") {
    $algorithm = MCRYPT_BLOWFISH;
    $mcryptMode = MCRYPT_MODE_CBC;
    $size = mcrypt_get_iv_size($algorithm, $mcryptMode);
    $iv = mcrypt_create_iv($size, MCRYPT_DEV_URANDOM);
    if ($mode == "encode") {
        $mcryptData = mcrypt_encrypt($algorithm, $secretKey, $data, $mcryptMode, $iv);
        //$encodeData = base64_encode($mcryptData);
        return $mcryptData;
    } else {
        //$decodeData = base64_decode($data);
        $originData = mcrypt_decrypt($algorithm, $secretKey, $data, $mcryptMode, $iv);
        return $originData;
    }
    return false;
}
