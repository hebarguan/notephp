<?php 
/*
 * Editer hebarguan in 2016-1-29
 * Email : hebarguan@hotmail.com
 * URL 模式支持两种/id/1/name/hebar , 或?id=1&name=hebar
 */
class Url
{
    // 定义路由模式
    private $UrlMode         = "";
    // 请求路由
    private $RequestUri      = "";
    // 完整路由
    private $FullUrl         = "";
    // 路由重写
    private $UrlRewrite      = "";
    // 路由重写
    private $UrlMap          = "";
    //　定义GET参数数组
    private $HttpBuildQuery  = array();
    // 当前路由请求的模块
    private $RequestModule   = "";
    // 控制器名
    private $Controller      = "";
    // 操作方法
    private $Action          = "";
    // 定义构造函数
    public function __construct()
    {

        $this->UrlMode     = C('URL_MODE');
        $this->RequestUri  = $_SERVER['REQUEST_URI'];
        $this->UrlRewrite  = C('URL_REWRITE_RULES');
        $this->UrlMap      = C('URL_MAP_RULES');
        // 是否开启session驱动
        if (C("SESSION_DRIVER_OPEN")) {
             /*
              *require_once(__NOTEPHP__."/Core/Driver/Session".EXTS);
              */
            $sessionHandler = new Session();
             session_set_save_handler($sessionHandler, true);
        }
        // 自动开启session 
        session_start();
    }
    // 开始路由处理
    public function start ()
    {
        $this->FullUrl = str_replace("/index.php?", "", $this->RequestUri);
        // 检查是否开启路由映射
        // 是否开启路由重写,只使用模式１
        if (!empty($this->UrlRewrite) AND 1 == $this->UrlMode) {
            // 直接正则匹配
            $pattern = array_keys($this->UrlRewrite);
            $replacement = array_values($this->UrlRewrite);
            $this->FullUrl = preg_replace($pattern, $replacement, $this->FullUrl);
        }
        // 检测是否以开启自动路由隐藏模块
        if (C('URL_HIDE_MODULE')) {
            // 检测是否开启子域名部署
            if (!empty($DomainDeploy = C('SUB_DOMAIN_RULES'))) {
                foreach ($DomainDeploy as $subDomain => $mapModule) {
                    // 若部署子域名与当前服务器名不同，跳过本次循环
                    if (SERVER_HOST !== $subDomain) continue;
                    // 以全局变量定义请求模块
                    $GLOBALS['PROJECT_REQUEST_MODULE'] = $mapModule ;
                }
            }
            /*
             * 整合项目模块名，或APP_NAME
             * 判断子域名是否部署成功
             */
            if (isset($GLOBALS['PROJECT_REQUEST_MODULE'])) {
                $this->FullUrl = __ROOT__.$GLOBALS['PROJECT_REQUEST_MODULE'].$this->FullUrl;
            } else {
                $this->FullUrl = __ROOT__.APP_NAME.$this->FullUrl;
            }
        }

        //  处理$_GET与$_POST
        $this->urlDataSpilt();
       /*
        * 判断处理后的模块／控制器／操作方法是否为空
        * 以全球变量声明模块
        */
        $GLOBALS['PROJECT_REQUEST_MODULE'] = ucfirst($this->RequestModule ? $this->RequestModule : APP_NAME) ;
        $this->RequestModule = $GLOBALS['PROJECT_REQUEST_MODULE'] ;
        $GLOBALS['PROJECT_REQUEST_CONTROLLER'] = ucfirst($this->Controller);
        $GLOBALS['PROJECT_REQUEST_ACTION']     = $this->Action;
       /*
        * 加载特定模块的函数库
        * 以遍历的方式加载目录函数文件
        */
        $proLibsFuncPath = __COMMON__."/Function/";
        $funcPathHandler = opendir($proLibsFuncPath);
        while ($item = readdir($funcPathHandler)) {
            if (is_file($proLibsFuncPath.$item) AND 
                ("php" == pathinfo($item, PATHINFO_EXTENSION))
            ) {
                require_once($proLibsFuncPath.$item);
            }
        }
        // 将数据交给控制器驱动处理
        ControllerDriver::work($this->RequestModule, $this->Controller, $this->Action);
    }
    // 路由匹配返回完整路由
    public function urlPattern()
    {
        if (!empty($this->UrlMap) && ($this->UrlMode == 2)) {
            // 整合路由映射，返回完整路由
            $maarr = array();
            $patternString = "/(\/[^:]+)(:[^\/]+)";
            //限制get字段长度
            for ($i = 0; $i < C("GET_FIELDS_LENGTH"); $i++ ) {
                $patternString .= "(\/:[^\/]+)?";
                $patternFields .= "(\/[^/]+)?";
            }
            foreach ($this->UrlMap as $map => $val) {
                if (!preg_match_all($patternString."/", $map, $matches, PREG_SET_ORDER)) continue;
                $maarr = $matches[0];
                if (!empty($maarr)) {
                    $UrlPattern = "|({$maarr[1]})([^/]+)".$patternFields."|";
                    if (!preg_match_all($UrlPattern, $this->FullUrl, $valMatches, PREG_SET_ORDER)) continue;
                    for ($i = 2; $i < count($maarr); $i++) {
                        $GetKey = preg_replace("/[:|\/]*([\S]+)$/", "$1", $maarr[$i]);
                        $GetVal = preg_replace("/[:|\/]*([\S]+)$/", "$1", $valMatches[0][$i]);
                        $this->HttpBuildQuery[$GetKey] = $GetVal;
                    }
                    // 模式２的路由方式
                    $ModeQueryString = $val;
                    while (list($k ,$v) = each($this->HttpBuildQuery) ) {
                        if (!($k AND $v))  continue;
                        $ModeQueryString .= "/".$k."/".$v;
                    }
                    // 返回模式２的完整路由
                    $this->FullUrl = $ModeQueryString;
                }
            }
        }
    }
    // 路由数据分割
    public function urlDataSpilt()
    {
        switch ($this->UrlMode) {
        case 1 :
            $spiltQuery = explode("?", $this->FullUrl) ;
            $index_handle = array_shift($spiltQuery);
            // 获取控制器与动作
            $queryArr = explode("/", $index_handle);
            array_shift($queryArr);
            $C_A = $queryArr;
            $this->RequestModule = !empty($C_A[0]) ? $C_A[0] : null;
            $this->Controller    = !empty($C_A[1]) ? $C_A[1] : C('DEFAULT_CONTROLLER') ;
            $this->Action        = !empty($C_A[2]) ? $C_A[2] : C("DEFAULT_METHOD");
            // 只截取第一个?
            if (!empty($spiltQuery)) {
                $GetData = join("?", $spiltQuery);
                // 数据返回$_GET
                parse_str($GetData, $_GET);
            } else {
                $_GET = array();
            }
            break;
        case 2 :
            $queryArr = explode("/", $this->FullUrl);
            array_shift($queryArr);
            $this->RequestModule = !empty($queryArr[0]) ? $queryArr[0] : null;
            $this->Controller    = !empty($queryArr[1]) ? $queryArr[1] : C("DEFAULT_CONTROLLER");
            $this->Action        = !empty($queryArr[2]) ? $queryArr[2] : C('DEFAULT_METHOD');
            for ($k = 3; $k < count($queryArr); $k+=2) {
                $this->HttpBuildQuery[$queryArr[$k]] = $queryArr[$k+1];
            }
            // 返回$_GET数据
            if (!empty($this->HttpBuildQuery)) {
                $_GET = $this->HttpBuildQuery;
            } else {
                $_GET = array();
            }
            break;
        }
    }
}

