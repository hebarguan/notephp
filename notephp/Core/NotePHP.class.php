<?php
 /*
  * 框架核心运行文件
  * Edited By hebarguan 2016-1-27
  * Email : hebarguan@hotmail.com
  */

class NotePHP {
    // 定义组合配置文件
    private static $_conf = array();
    // 定义核心文件名
    private static $Core  = array("Controller" ,"URL" ,"View" ,"Log");
    // 定义调试参数
    private static $trace = array("log_file" => "", "error_file" => "");
    // 定义项目结构目录
    private static $struDir = array(
        "functions"  =>  "", // 项目公共函数目录
        "conf"       =>  "", // 项目配置文件
        "runtime"    =>  array("cache","compiler","log","data"),   // 项目缓存目录
        "model"      =>  "",   // 项目模型目录
        "controller" => "",  // 项目动作控制器
        "html"       => "",  // 项目模板目录
        "extends"    =>  "",   // 项目自定义扩展目录
    );

    // 框架初始运行
    public static function initialize() {
        // 定义错误与异常函数
        set_error_handler('NotePHP::MyError');
        set_exception_handler('NotePHP::MyException');
        // 定义自动加载类函数
        spl_autoload_register('NotePHP::autoLoad');

        // 加载核心文件
        foreach ( self::$Core as $file ) {
            $coreFile = __NOTEPHP__."/Core/".$file.EXTS;
            if( is_file( $coreFile  ) ) {
                require_once($coreFile);
            }
        }
        // 尝试创建项目目录结构
        if( !is_dir(PRO_PATH) ) {
            if(mkdir(PRO_PATH)) {
                while(list($sdir , $val) = each(self::$struDir)) {
                    $subPath = PRO_PATH."/".$sdir;
                    mkdir($subPath);
                    if(is_array($val) AND !empty($val)) {
                        for($i=0;$i<count($val);$i++) {
                            mkdir($subPath."/".$val[$i]);
                        }
                    }
                }
            }else{
                // 创建失败则提示手动创建
                trigger_error(PRO_PATH."目录不能创建，请手动创建＠" , E_USER_ERROR);
            }

        }
        // 定义全局请求模块索引,控制器，动作
        $GLOBALS['PROJECT_REQUEST_MODULE'] = null;
        $GLOBALS['PROJECT_REQUEST_CONTROLLER'] = null;
        $GLOBALS['PROJECT_REQUEST_ACTION'] = null;
        // 加载框架公共函数库
        require_once(__NOTEPHP__."/Common/functions.php");

        // 是否开启调试模式
        if(  DEBUG_ON ) {
            // 加载错误与日志文件
            self::$trace['log_file'] = "/runtime/log/error.log" ;
        }
        // 开启路由处理
        self::AppRun();
    }
    // 自定义错误处理函数
    public static function MyError( $code , $msg , $file , $line ) {
        if( !($code  AND error_reporting()) ) {
            return ;
        }
        switch ($code) {
        case 1 :
        case 2 :
        case 4 :
        case 8 :
        case 256 :
        case 512 :
        case 1024 :
            /*
             *if( $logfile = self::$trace['log_file'] AND  $errfile = self::$trace['error_file'] ) {
             *    Log::record($logfile ,$errfile ,$msg ,$file ,$line);
             *}
             */
            echo "<h2>{$msg}</h2>\n<h3>File :{$file} in line {$line}</h3>\n";
            $debugTrace = debug_backtrace();
            self::printDebugMsg($debugTrace);
            break;
        default :
            echo "UNKOWN ERROR : [{$code}] File: {$file} $msg in line $line ";
        }
        exit ;
    } 
    // 自动加载类函数
    public static function autoLoad ( $classname ) {
        // 自动加载项目类文件
        $classname = ucfirst($classname);
        $proPath   = __ROOT__.($GLOBALS['PROJECT_REQUEST_MODULE'] ? $GLOBALS['PROJECT_REQUEST_MODULE'] : APP_NAME);
        $pro_class = array($proPath."/controller/".$classname.EXTS , $proPath."/Model/".$classname."Model".EXTS);
        $core_class= array(__NOTEPHP__."/Core/".$classname.EXTS);
        if( is_file($p_c = $pro_class[0]) ) {
            include_once $p_c;
        }elseif( is_file($p_m = $pro_class[1]) ) {
            include_once $p_m;
        }elseif( is_file($c_c = $core_class[0]) ) {
            include_once $c_c;
        }
        else{
            return false;
            /*
             *trigger_error("未找到类{$classname}" , E_USER_ERROR);
             */
        }
    }
    // 自定义异常处理
    public static function MyException ($e) {
        // 获取异常模板文件
        $excptionFile = C('EXCEPTION_FILE'); 
        header("Location :".$_SERVER['SERVER_NAME'].$excptionFile);
        exit ;
    }
    // 打印调试跟踪信息
    public static function printDebugMsg ($debugData) {
        for( $i=0 ; $i<count($debugData) ; $i++ ) {
            if(!array_key_exists('file' ,$debugData[$i]) AND !array_key_exists('line',$debugData[$i])) continue;
            $echoMsg = '<span style="font-size:17px;">[ ';
            $file = $debugData[$i]['file'];
            $line = $debugData[$i]['line'];
            $function = $debugData[$i]['function'];
            $echoMsg .= "Function $function ] File : $file in line $line With ";
            foreach($debugData[$i]['args'] as $key => $val) {
                $echoMsg .= (is_object($val) ? "Object" : $val)." ";
            }
            echo "{$echoMsg} </span><br/>";
        }
    }
    public static function AppRun () {
        $URI = new URL();
        $URI->start();
    }
}
?>
