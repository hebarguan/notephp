<?php 
/*
 * Editer hebarguan in 2016-1-29
 * Email : hebarguan@hotmail.com
 * URL 模式支持两种/id/1/name/hebar , 或?id=1&name=hebar
 */
class Url {
    // 定义路由模式
    private $UrlMode         = "";
    // 查询字段
    private $QueryString     = "";
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
    public function __construct() {

        $this->UrlMode     = C('URL_MODE');
        $this->QueryString = $_SERVER['QUERY_STRING'];
        $this->UrlRewrite  = C('URL_REWRITE_RULES');
        $this->UrlMap      = C('URL_MAP_RULES');
        // 自动开启session 
        session_start();
    }
    // 开始路由处理
    public function start () {
        // 检查是否开启路由映射
        if( empty($this->UrlMap) ) {
            $this->FullUrl = $this->QueryString ;
        }else{
            // 整合路由映射，返回完整路由
            $maarr = array();
            $patternString = "/(\/[^:]+)(:[^\/]+)";
            //限制get字段长度
            for( $i=0; $i < C("GET_FIELDS_LENGTH"); $i++ ) {
                $patternString .= "(\/:[^\/]+)?";
                $patternFields .= "(\/[^/]+)?";
            }
            foreach( $this->UrlMap as $map => $val ) {
                if(!preg_match_all($patternString."/",$map ,$matches, PREG_SET_ORDER)) continue;
                $maarr = $matches[0];
                var_dump($maarr);
                if(!empty($maarr)) {
                    $UrlPattern = "|({$maarr[1]})([^/]+)".$patternFields."|";
                    preg_match_all($UrlPattern,$this->QueryString , $valMatches, PREG_SET_ORDER);
                    var_dump($valMatches);
                    for($i = 2;$i<count($maarr); $i++) {
                        $GetKey = preg_replace("/[:|\/]*([\S]+)$/","$1",$maarr[$i]);
                        $GetVal = preg_replace("/[:|\/]*([\S]+)$/","$1",$valMatches[0][$i]);
                        $this->HttpBuildQuery[$GetKey] = $GetVal;
                    }
                    // 模式２的路由方式
                    $ModeQueryString = $val;
                    while(list($k ,$v) = each($this->HttpBuildQuery) ) {
                        if( !($k AND $v) )  continue;
                        $ModeQueryString .= "/".$k."/".$v;
                    }
                    // 返回模式２的完整路由
                    $this->FullUrl = $ModeQueryString;
                }
            }
        }
        // 是否开启路由重写,只使用模式１
        if( !empty($this->UrlRewrite) AND 1 == $this->UrlMode ) {
            // 直接正则匹配
            $pattern = array_keys($this->UrlRewrite);
            $replacement = array_values($this->UrlRewrite);
            $this->FullUrl = preg_replace($pattern ,$replacement ,$this->QueryString);
        }
        // 检测是否以开启自动路由隐藏模块
        if( C('URL_HIDE_MODULE') ) {
            // 检测是否开启子域名部署
            if( !empty($DomainDeploy = C('SUB_DOMAIN_RULES')) ) {
                foreach( $DomainDeploy as $subDomain => $mapModule ) {
                    // 若部署子域名与当前服务器名不同，跳过本次循环
                    if (SERVER_HOST !== $subDomain) continue;
                    // 以全局变量定义请求模块
                    $GLOBALS['PROJECT_REQUEST_MODULE'] = $mapModule ;
                    $this->FullUrl = __ROOT__.$mapModule.$this->FullUrl;
                }
            }else{
                // 整合项目模块名，或APP_NAME
                $this->FullUrl = __ROOT__.APP_NAME.$this->FullUrl;
            }
        }

        //  处理$_GET与$_POST
        switch ($this->UrlMode) {
        case 1 :
            $spiltQuery = explode("?" ,$this->FullUrl) ;
            $index_handle = array_shift($spiltQuery);
            // 获取控制器与动作
            $queryArr = explode("/",$index_handle);
            array_shift($queryArr);
            $C_A = $queryArr;
            $this->RequestModule = !empty($C_A[0]) ? $C_A[0] : null;
            $this->Controller    = !empty($C_A[1]) ? $C_A[1] : C('DEFAULT_INDEX') ;
            $this->Action        = !empty($C_A[2]) ? $C_A[2] : C("DEFAULT_HANDLE");
            // 只截取第一个?
            if( !empty($spiltQuery) ){
                $GetData = join("?",$spiltQuery);
                // 数据返回$_GET
                parse_str($GetData ,$_GET);
            }
            break;
        case 2 :
            $queryArr = explode("/" ,$this->FullUrl);
            array_shift($queryArr);
            $this->RequestModule = !empty($queryArr[0]) ? $queryArr[0] : null;
            $this->Controller    = !empty($queryArr[1]) ? $queryArr[1] : C("DEFAULT_INDEX");
            $this->Action        = !empty($queryArr[2]) ? $queryArr[2] : C('DEFAULT_HANDLE');
            for( $k=3; $k<count($queryArr); $k+=2 ) {
                $this->HttpBuildQuery[$queryArr[$k]] = $queryArr[$k+1];
            }
            // 返回$_GET数据
            $_GET = $this->HttpBuildQuery;
            break;
        }
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
            if (is_file($proLibsFuncPath.$item) AND ("php" == pathinfo($item ,PATHINFO_EXTENSION))) {
                require_once($proLibsFuncPath.$item);
            }
        }
        // 将数据交给控制器处理
        $this->WorkControllerClass();
    }
    // 控制器处理与检测
    public function WorkControllerClass () {
        // 模块名
        $ModuleName  = ucfirst($this->RequestModule) ;
        // 控制器名
        $ControllerName = ucfirst(strtolower($this->Controller))."Controller";
        // 模块目录
        $modulePath = PRO_PATH."/".$ModuleName;
        if( is_dir($modulePath) ) {
            // 检查是否默认控制与动作
            if($this->Controller == C("DEFAULT_INDEX") AND $this->Action == C("DEFAULT_HANDLE")) {
                if( !is_file($modulePath."/Controller/".$ControllerName.EXTS) ) {
                    // 加载欢迎界面
                    $this->outputWelcomePage();
                    exit;
                }
            }
            // 操作方法名
            $ActionName = $this->Action;
            // 实例化控制器
            $ControllerHandle = new $ControllerName();
            // 获取控制器所有操作方法
            $allMethod = get_class_methods($ControllerName);
            // 检测是否开启路由大小写
            // 与匹配的方式检测，方便大小写规则
            $ActionPattern = C("URL_CASE_INSENSITIVE") ? "/{$ActionName}/" : "/{$ActionName}/i";
            if( !preg_match($ActionPattern ,join(" ",$allMethod)) ) {
                // 不存在操作方法，放回错误
                trigger_error("不存在动作{$ActionName}",E_USER_ERROR);
            }else{
                // 否则运行操作方法
                call_user_func(array($ControllerHandle ,$ActionName));
            }
        }else{
            trigger_error("不存在模块{$ModuleName}",E_USER_ERROR);   
        }
    }
    // 写入欢迎界面
    public function outputWelcomePage () {
        $ControllerName = ucfirst(strtolower($this->Controller))."Controller";
        $ActionName = $this->Action;
        $welcomeClassName = PRO_PATH."/".ucfirst($this->RequestModule)."/Controller/".$ControllerName.EXTS;
        $fileHandler       = fopen($welcomeClassName ,"a+");
        fclose($fileHandler);
        file_put_contents($welcomeClassName,"<?php class {$ControllerName} extends Controller { public function {$this->Action} () { \$this->show('<center><h2>欢迎使用notePHP框架 使用愉快 ^_^</h2></center>'); } } ?>");
        $newCtrl = new $ControllerName();
        $newCtrl->$ActionName();
        exit;
    }
}
?>