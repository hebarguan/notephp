<?php
/*
 * Editer hebarguan in 2016-2-10
 * Email hebarguan@hotmail.com
 * 框架日志记录类
 */
class Log {
    // 错误要显示的模板文件
    private static $FullErrMsg = "";
    // 错误日志文件
    private static $LogFileHandle = "";
    // 日志记录方法
    public static function record ($LogFile ,$ErrFile ,$msg ,$file ,$line) {
        // 是否开启调试模式
        // 不开启则直接发送HTTP/1.1 400 NOT FOUND 错误
        if( DEBUG_ON ) {
            $LogFile = __ROOT__.$GLOBALS['PROJECT_REQUEST_MODULE'].$LogFile;
            self::$LogFileHandle = fopen($LogFile , "a+");
            $recordMsg = "[".date('Y-m-d H:i:s')."]"."[ERROR]:".$msg."in File".$file." line ".$line."\n";
            fwrite(self::$LogFileHandle ,$recordMsg);
            fclose(self::$LogFileHandle);
            $printMsg = "<h1>>_<</h1><br/>"."<h2>{$msg}</h2><br/>"."File:{$file} in line <strong>{$line}</strong>";
            exit($printMsg);
        }else{
            header("Content-language : en");
            header($_SERVER['SERVER_PROTOCOL']." 404 Not Found");
        }
    }
}
