<?php
/*
 * Editer hebarguan in 2016-1-30
 * Email hebarguan@hotmail.com
 * 该类为项目控制类的基类
 * 将处理用户逻辑与模板显示
 */

class Controller
{
    // 请求模块
    private $module      = "";
    // 请求控制器
    private $controller  = "";
    // 请求操作
    private $action      = "";
    // 视图类
    private $view        = "";
    // 模板值
    private $templateVal = array();
    // 请求不存在参数与方法
    private $data        = [];
    // 模板缓存 
    public $caching      = null;
    // 模板缓存时间i
    public $cacheLifeTime= null;
    // 初始化控制器类
    public  function __construct()
    {
        $this->module     = ucfirst($GLOBALS['PROJECT_REQUEST_MODULE']) ;
        $this->controller = ucfirst($GLOBALS['PROJECT_REQUEST_CONTROLLER']) ;
        $this->action     = $GLOBALS['PROJECT_REQUEST_ACTION'] ;
        // 封装模式控制视图类
        $this->view = new View();
    }
    // 模板赋值处理
    protected function assign()
    {
        // 获取函数参数
        $argsNum  = func_num_args();
        // 参数数组
        $argsArr  = func_get_args();
        //　如果参数列表为０则发出错误警告
        if (!$argsNum) trigger_error("assgin参数列表不能为空",E_USER_NOTICE);
        switch ($argsNum) {
            case 1:
                foreach($argsArr[0] as $var => $val) {
                    $this->templateVal[$var]  = $val;
                }
                break;
            case 2:
                $this->templateVal[$argsArr[0]] = $argsArr[1];
                break;
        }
        return true;
    }
    // 数据返回
    protected function dataReturn($data)
    {
        // 数据类型
        $dataType = C('DATA_RETURN_TYPE');
        // 发送http头信息
        header("Content-Type:text/$dataType");
        switch ($dataType) {
            case 'json':
                $jsonEncode = json_encode($data);
                echo $jsonEncode;
                break;
            case 'xml':
                $xmlData = '<?xml version="1.0"?>';
                foreach ($data as $node => $val) {
                    $childNode = '';
                    if (is_array($val)) {
                        while (list($secondNode ,$secondVal) = each($val)) {
                            $childNode .= "<{$secondNode}>{$secondVal}</{$secondNode}>";
                        }
                    } else {
                        $childNode = $val;
                    }
                    $xmlData .= $childNode;
                }
                echo $xmlData;
                break;
        }
        // 退出程序
        exit;
    }
    // 模板显示
    protected function display($Template = null)
    {
        // 默认模板后缀
        $TemplateSuffix = C("TEMP_DEFAULT_SUFFIX");
        $viewPath = PRO_PATH."/".$this->module."/View/".$this->controller;
        // 模板文件
        $tempFile = (C('TEMP_METHOD') == 1) ? 
            $viewPath."/".$this->action.".".$TemplateSuffix :
            $viewPath."_".$this->action.".".$TemplateSuffix; 
        // 编译模板文件
        $complierFile = !is_null($Template) ? $Template : $tempFile;
        $this->view->set($this->templateVal);
        $this->view->asHtml($complierFile, $this->caching, $this->cacheLifeTime);
        exit;
    }
    // 显示文本或html
    protected function show($str = null)
    {
        echo htmlspecialchars_decode($str);
        exit;
    }
    // 重定向处理
    protected function redirect($url, $msg = '', $time = 0)
    {
        if ($msg) {
            $redirectFile = C("REDIRECT_FILE");
            $assginment = array("redirectMsg" => $msg ,"delayedTime" => $time ,"redirectUrl" => $url) ;
            $this->view->set($assginment);
            // 设置不缓存重定向文件
            $this->view->asHtml($redirectFile, false, 0);
        } else {
            // 直接重定向
            header("Location:".$url);
        }
        exit;
    }
    public function __set($name, $value) 
    {
        $this->data[$name] = $value;
    }

    public function __get($name) 
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
        // 找不到返回错误
        trigger_error('Undefined property via __get(): '.$name, E_USER_NOTICE);
    }

    /**  PHP 5.1.0之后版本 */
    public function __isset($name) 
    {
        return isset($this->data[$name]);
    }

    /**  PHP 5.1.0之后版本 */
    public function __unset($name) 
    {
        unset($this->data[$name]);
    }
    // 对不存在的操作与成员错误处理
    public function __call($method, $arguments)
    {
        if (array_key_exists($method, $this->data)) {
            return $this->data[$method]($arguments);
        }
        // 找不到返回错误
        trigger_error('Call to Undefined method via __call(): '.$method.' With Arguments: '.json_encode($arguments), E_USER_NOTICE);
    }
}
