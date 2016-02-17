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
    public static function record ($errType ,$debugData,$err) {
        // 是否开启调试模式
        // 不开启则直接发送HTTP/1.1 404 NOT FOUND 错误
        $ignoreErrType = explode(",",ERROR_IGNORE_TYPE);
        if( in_array($errType ,$ignoreErrType) ) return ;
        list($code ,$msg ,$file,$line) = $err;
        if( !is_dir($logPath = DOCUMENT_ROOT."/Webapp/Log") ) mkdir($logPath);
        if( DEBUG_ON ) {
            $recordMsg = "[".date('Y-m-d H:i:s')."]"."[ERROR][$code]:".$msg."in File".$file." line ".$line."\n";
            $printMsg = "<h1>>_<</h1>  <h2>{$msg}</h2><br/>"."[$errType] File:{$file} in line <strong>{$line}</strong><br/>";
            print($printMsg);
            NotePHP::printDebugMsg($debugData);
            self::$LogFileHandle = fopen($logPath."/error.log","a+");
            fwrite(self::$LogFileHandle ,$recordMsg);
            fclose(self::$LogFileHandle);
            exit();
        }else{
            header('HTTP/1.1 404 Not Found');
            header("Status:404 Not Found");
        }
    }
}
?>
