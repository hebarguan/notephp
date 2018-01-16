<?php
 /*
  * 框架核心运行文件
  * Edited By hebarguan 2016-1-27
  * Email : hebarguan@hotmail.com
  */

class NotePHP
{
    // 定义组合配置文件
    private static $_conf = array();
    // 定义核心文件名
    private static $Core  = array("Log" ,"Url");
    // 定义调试参数
    private static $errData = array();
    // 定义项目结构目录
    private static $struDir = array(
        "Runtime"    =>  array("Cache","Compile","Data"),   // 项目缓存目录
        "Model"      =>  "",   // 项目模型目录
        "Controller" => "",  // 项目动作控制器
        "View"       => "",  // 项目模板目录
    );

    // 框架初始运行
    public static function initialize()
    {
        // 定义错误与异常函数
        set_error_handler('NotePHP::MyError');
        register_shutdown_function('NotePHP::LastErr');
        // 定义自动加载类函数
        spl_autoload_register('NotePHP::autoLoad');

        // 加载核心文件
        foreach (self::$Core as $file ) {
            $coreFile = __NOTEPHP__."/Core/".$file.EXTS;
            if (is_file($coreFile)) {
                require_once($coreFile);
            }
        }
        // 尝试创建项目目录结构
        if(!is_dir(MODULE_PATH)) {
            if (mkdir(MODULE_PATH)) {
                while (list($sdir , $val) = each(self::$struDir)) {
                    $subPath = MODULE_PATH."/".$sdir;
                    mkdir($subPath);
                    if (is_array($val) AND !empty($val)) {
                        for ($i=0;$i<count($val);$i++) {
                            mkdir($subPath."/".$val[$i]);
                        }
                    }
                }
            } else {
                // 创建失败则提示手动创建
                trigger_error(PRO_PATH."目录不能创建，请手动创建＠" , E_USER_ERROR);
            }
        }
        // 加载框架公共函数库
        include_once(__NOTEPHP__."/Common/Function/functions.php");
        // 开启路由处理
        self::AppRun();
    }
    // 自定义错误处理函数
    public static function MyError($code , $msg , $file , $line)
    {
        if (!($code  AND error_reporting())) {
            return;
        }
        $err = array($code,$msg ,$file ,$line);
        $errType = self::selectErrorType($code);
        $debugTraceData = debug_backtrace();
        self::$errData = array($errType,$debugTraceData ,$err);
        self::writeLog();
    } 
    // 自动加载类函数
    public static function autoLoad($classname)
    {
        // 自动加载项目类文件
        $modulePath   = PRO_PATH."/".($GLOBALS['PROJECT_REQUEST_MODULE'] ?
            $GLOBALS['PROJECT_REQUEST_MODULE'] :
            APP_NAME);
        $corePath     = __NOTEPHP__."/Core";
        $autoLoadPath = array($modulePath, $corePath);
        foreach ($autoLoadPath as $path) {
            if ($fileHandler = ergodicPath($path, $classname.EXTS)) break;
        }
        // 加载失败则返回FALSE,让队列函数继续加载
        if($fileHandler === false ) return false;
    }
    // 获取脚本执行完后的最后一条错误
    public static function LastErr()
    {
        $err = error_get_last();
        if (empty($err))  return ;
        $errType = self::selectErrorType($err['type']);
        $debugTraceData = debug_backtrace();
        $err = array($err['type'], $err['message'], $err['file'], $err['line']);
        self::$errData = array($errType, $debugTraceData, $err);
        self::writeLog();
    }
    // 写入日志
    public static function writeLog()
    {
        if (!empty(self::$errData)) {
            Log::record(self::$errData[0] ,self::$errData[1] ,self::$errData[2]);
        }
    }
    // 打印调试跟踪信息
    public static function printDebugMsg($debugData)
    {
        header("Content-type: text/html; charset=utf-8");
        for ($i = 0; $i < count($debugData); $i++) {
            if (!array_key_exists('file', $debugData[$i]) AND 
                !array_key_exists('line',$debugData[$i])) continue;
            $echoMsg = '<span style="font-size:17px;">[ ';
            $file = $debugData[$i]['file'];
            $line = $debugData[$i]['line'];
            $function = $debugData[$i]['function'];
            $echoMsg .= "Function $function ] File : $file in line $line With ";
            foreach ($debugData[$i]['args'] as $key => $val) {
                $echoMsg .= (is_object($val) ? "Object" : is_array($val) ? "Array" : $val)." ";
            }
            echo "{$echoMsg} </span><br/>";
        }
    }
    // 选择错误类型
    public static function selectErrorType($code)
    {
        $errType = null;
        switch ($code) {
        case 1 :   $errType = "E_ERROR";            break;
        case 2 :   $errType = "E_WARNING";          break;
        case 4 :   $errType = "E_PARSE";            break;
        case 8 :   $errType = "E_NOTICE";           break;
        case 256 : $errType = "E_USER_ERROR";       break;
        case 512 : $errType = "E_USER_WARNING";     break;
        case 1024 :$errType = "E_USER_NOTICE";      break;
        case 2048 :$errType = "E_SCRICT";           break;
        case 4096 :$errType = "E_RECOVERABLE_ERROR";break;
        case 8192 :$errType = "E_DEPRECATED";       break;
        default :
            $errType = "UNKOWN ERROR";
        }
        return $errType;
    }
    public static function AppRun()
    {
        $url = new Url();
        $url->start();
    }
}
