<?php 
/*
 * Editer hebarguan in 2016-1-29
 * Email : hebarguan@hotmail.com
 * URL 模式支持两种/id/1/name/hebar , 或?id=1&name=hebar
 */
class Url
{
    // 定义路由模式
    private $urlMode         = "";
    // 请求路由
    private $requestUri      = "";
    // 完整路由
    private $fullUrl         = "";
    // 路由重写
    private $urlRewrite      = "";
    // 路由重写
    private $urlMap          = "";
    //　定义GET参数数组
    private $httpBuildQuery  = array();
    // 当前路由请求的模块
    private $requestModule   = "";
    // 控制器名
    private $controller      = "";
    // 操作方法
    private $action          = "";
    // 路由后缀
    private $urlSuffix       = "";
    // 定义构造函数
    public function __construct()
    {
        $this->urlMode     = C('URL_MODE');
        $this->requestUri  = $_SERVER['REQUEST_URI'];
        $this->urlRewrite  = C('URL_REWRITE_RULES');
        $this->urlMap      = C('URL_MAP_RULES');
        $this->urlSuffix   = C('URL_STATIC_SUFFIX');
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
        $this->fullUrl = $this->checkUrlSuffix(str_replace("/index.php?", "", $this->requestUri));
        // 检查是否开启路由映射
        // 是否开启路由重写,只使用模式１
        if (!empty($this->urlRewrite) AND 1 == $this->urlMode) {
            // 直接正则匹配
            $pattern = array_keys($this->urlRewrite);
            $replacement = array_values($this->urlRewrite);
            $this->fullUrl = preg_replace($pattern, $replacement, $this->fullUrl);
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
                $this->fullUrl = __ROOT__.$GLOBALS['PROJECT_REQUEST_MODULE'].$this->fullUrl;
            } else {
                $this->fullUrl = __ROOT__.APP_NAME.$this->fullUrl;
            }
        }

        //  处理$_GET与$_POST
        $this->urlDataSpilt();
       /*
        * 判断处理后的模块／控制器／操作方法是否为空
        * 以全球变量声明模块
        */
        $GLOBALS['PROJECT_REQUEST_MODULE'] = ucfirst($this->requestModule ? $this->requestModule : APP_NAME) ;
        $this->requestModule = $GLOBALS['PROJECT_REQUEST_MODULE'] ;
        $GLOBALS['PROJECT_REQUEST_CONTROLLER'] = ucfirst($this->controller);
        $GLOBALS['PROJECT_REQUEST_ACTION']     = $this->action;
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
        ControllerDriver::work($this->requestModule, $this->controller, $this->action);
    }
    // 检测是否有路由后缀
    public function checkUrlSuffix($url) 
    {
        $suffixLen = strlen($this->urlSuffix) + 1;
        if (substr($url, -$suffixLen) === ".{$this->urlSuffix}") {
            $fullUrl =  substr($url, 0, strlen($url) - $suffixLen);
            return $fullUrl;
        }
        return $url;
    }
    // 路由匹配返回完整路由
    public function urlPattern()
    {
        if (!empty($this->urlMap) && ($this->urlMode == 2)) {
            // 整合路由映射，返回完整路由
            $maarr = array();
            $patternString = "/(\/[^:]+)(:[^\/]+)";
            //限制get字段长度
            for ($i = 0; $i < C("GET_FIELDS_LENGTH"); $i++ ) {
                $patternString .= "(\/:[^\/]+)?";
                $patternFields .= "(\/[^/]+)?";
            }
            foreach ($this->urlMap as $map => $val) {
                if (!preg_match_all($patternString."/", $map, $matches, PREG_SET_ORDER)) continue;
                $maarr = $matches[0];
                if (!empty($maarr)) {
                    $UrlPattern = "|({$maarr[1]})([^/]+)".$patternFields."|";
                    if (!preg_match_all($UrlPattern, $this->fullUrl, $valMatches, PREG_SET_ORDER)) continue;
                    for ($i = 2; $i < count($maarr); $i++) {
                        $GetKey = preg_replace("/[:|\/]*([\S]+)$/", "$1", $maarr[$i]);
                        $GetVal = preg_replace("/[:|\/]*([\S]+)$/", "$1", $valMatches[0][$i]);
                        $this->httpBuildQuery[$GetKey] = $GetVal;
                    }
                    // 模式２的路由方式
                    $ModeQueryString = $val;
                    while (list($k ,$v) = each($this->httpBuildQuery) ) {
                        if (!($k AND $v))  continue;
                        $ModeQueryString .= "/".$k."/".$v;
                    }
                    // 返回模式２的完整路由
                    $this->fullUrl = $ModeQueryString;
                }
            }
        }
    }
    // 路由数据分割
    public function urlDataSpilt()
    {
        switch ($this->urlMode) {
        case 1 :
            $spiltQuery = explode("?", $this->fullUrl) ;
            $index_handle = array_shift($spiltQuery);
            // 获取控制器与动作
            $queryArr = explode("/", $index_handle);
            array_shift($queryArr);
            $C_A = $queryArr;
            $this->requestModule = !empty($C_A[0]) ? $C_A[0] : null;
            $this->controller    = !empty($C_A[1]) ? $C_A[1] : C('DEFAULT_CONTROLLER') ;
            $this->action        = !empty($C_A[2]) ? $C_A[2] : C("DEFAULT_METHOD");
            // 只截取第一个?
            if (!empty($spiltQuery)) {
                $GetData = join("?", $spiltQuery);
                // 数据返回$_GET
                parse_str($GetData, $_GET);
                $_GET = array_unique($_GET);
            } else {
                $_GET = array();
            }
            break;
        case 2 :
            $queryArr = explode("/", $this->fullUrl);
            array_shift($queryArr);
            $this->requestModule = !empty($queryArr[0]) ? $queryArr[0] : null;
            $this->controller    = !empty($queryArr[1]) ? $queryArr[1] : C("DEFAULT_CONTROLLER");
            $this->action        = !empty($queryArr[2]) ? $queryArr[2] : C('DEFAULT_METHOD');
            for ($k = 3; $k < count($queryArr); $k+=2) {
                $this->httpBuildQuery[$queryArr[$k]] = $queryArr[$k+1];
            }
            // 返回$_GET数据
            if (!empty($this->httpBuildQuery)) {
                $_GET = array_unique($this->httpBuildQuery);
            } else {
                $_GET = array();
            }
            break;
        }
    }
}

