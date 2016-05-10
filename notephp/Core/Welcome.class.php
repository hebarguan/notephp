<?php
/*
 * Editer hebarguan in 2016-1-29
 * Email : hebarguan@hotmail.com
 * 本类输出欢迎界面
 */
class Welcome
{
    // 写入欢迎界面
    public static function outputWelcomePage($module, $controller, $action)
    {
        $ControllerName    = ucfirst($controller)."Controller";
        $ActionName        = $action;
        $welcomeClassFile  = PRO_PATH."/".ucfirst($module)."/Controller/".$ControllerName.EXTS;
        // 尝试创建欢迎界面文件
        $fileHandler       = fopen($welcomeClassFile ,"a+");
        fclose($fileHandler);
        // 写入简单欢迎类代码
        file_put_contents($welcomeClassFile,
        "<?php 
            class {$ControllerName} extends Controller
            { 
                public function {$ActionName} () 
                { 
                    \$this->show('<center><h2>欢迎使用notePHP框架 使用愉快 ^_^</h2></center>'); 
                } 
            } 
        ?>"
        );
        $newCtrl = new $ControllerName();
        $newCtrl->$ActionName();
    }
}

