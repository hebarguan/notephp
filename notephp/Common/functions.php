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
    $defaultConfFile = __NOTEPHP__."/Conf/default.php"; 
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
        trigger_error("类目录{$path} 不存在" ,E_USER_ERROR);
    }
}
// 实例化模型
function M ($model ,$bool = true) {
    // 如$boo 为true 表示模型存在
    if ($bool) {
        $modelName = $model."Model";
        if (is_file(__ROOT__.$GLOBALS['PROJECT_REQUEST_MODULE']."/model/".$modelName.EXTS)) {
            return new $model();
        }else{
            trigger_error($modelName."模型类不存在" ,E_USER_ERROR);
        }
    }else{
        // 表示不存在模型
        return new Model($model);
    }
}
?>
