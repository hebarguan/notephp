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
    $userConfFile = __ROOT__.$GLOBALS['PROJECT_REQUEST_MODULE']."/conf/conf.php";
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
    switch ($outlinePath[0]) {
    case "@" :
        $ergodicPath = __ROOT__.$GLOBALS['PROJECT_REQUEST_MODULE']."/{$outlinePath[1]}/{$outlinePath[2]}";
        if(is_dir($ergodicPath)) {
            $fp = opendir($ergodicPath);
            while ($item = readdir($fp)) {
                $secondPath = $ergodicPath."/".$item;
                if(is_file($secondPath.EXTS)) {
                    require_once($secondPath.EXTS);
                }elseif( is_dir($) )
            }
        }
    }
}

?>
