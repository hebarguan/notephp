<?php
/*
 * Editer hebarguan in 2016-2-10
 * Email hebarguan@hotmail.com
 * 框架日志记录类
 */

class Log
{
    // 错误要显示的模板文件
    private static $FullErrMsg = "";
    // 错误日志文件
    private static $LogFileHandle = "";
    // 日志记录方法
    public static function record($errType ,$debugData,$err)
    {
        // 是否开启调试模式
        // 不开启则直接发送HTTP/1.1 404 NOT FOUND 错误
        $ignoreErrType = explode(",",ERROR_IGNORE_TYPE);
        if (in_array($errType ,$ignoreErrType)) {
            return;
        }

        list($code ,$msg ,$file,$line) = $err;
        if (!is_dir($logPath = DOCUMENT_ROOT."/Webapp/Log")) {
            mkdir($logPath);
        }
        // 错误文本
        $recordMsg = "[".date('Y-m-d H:i:s')."]".
                "[ERROR][$code]:".
                $msg."in File".$file." line ".$line."\n";
            $printMsg = "<h1>>_<</h1>  <h2>{$msg}</h2><br/>".
                "[$errType] File:{$file} in line <strong>{$line}</strong><br/>";

        if (DEBUG_ON) {
            // 打印错误
            print($printMsg);
            NotePHP::printDebugMsg($debugData);
        } else {
            // 响应404错误
            http_response_code(404);
            // php 5.3.3版本下的用header
            header(SERVER_PROTOCOL.' 404 Not Found');
            header("Content-type: text/html; charset=utf-8");
            echo "<center><h2>404 Not Found</h3></center><hr/>";
        }
        // 退出进程
        self::logMessage($logPath, $recordMsg);
    }

    /**
     * 写入日志记录
     * 
     * @param string $logPath 日志目录
     * @param string $errorMsg 错误文本
     * @return void
     */
    public static function logMessage($logPath, $errorMsg)
    {
        $logFile = $logPath.'/log-'.date('Y-m-d').'.php';
        if ( ! is_file($logFile)) {
            self::$LogFileHandle = fopen($logFile,"a+");
            fwrite(self::$LogFileHandle, '<?php </br>');
        }
        self::$LogFileHandle = fopen($logFile,"a+");
        fwrite(self::$LogFileHandle, $errorMsg);
        fclose(self::$LogFileHandle);
    }

}
