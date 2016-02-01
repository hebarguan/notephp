<?php 
/*
 * Editer hebarguan in 2016-1-29
 * Email : hebarguan@hotmail.com
 * URL 模式支持两种/id/1/name/hebar , 或?id=1&name=hebar
 */
class URL {
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
    // 控制器名
    private $Controller      = "";
    // 操作方法
    private $Action          = "";
    // 定义构造函数
    public function __construct() {
        $this->UrlMode = C('URL_MODE');
        $this->QueryString = $_SERVER['REQUEST_URI'];
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
            foreach( $this->UrlMap as $map => $val ) {
                if(!preg_match_all("|(/[^:]*)*(\:[^/]+)(\/\:[^/]+)*(\/\:[^/]+)*|",$map ,$matches)) continue;
                $maarr = $matches;
                if(!empty($maarr)) {
                    $UrlPattern = "|({$maarr[1][0]})*([^/]+)(\/[^/]+)*(\/[^/]+)*(\/[^/]+)*|";
                    preg_match_all($UrlPattern,$this->QueryString , $valMatches);
                    for($i = 2;$i<count($valMatches); $i++) {
                        $GetKey = preg_replace("/[:|/]*([\S]+)$/","$1",$matches[$i][0]);
                        $GetVal = preg_replace("/[:|/]*([\S]+)$/","$1",$valMatches[$i][0]);
                        $this->HttpBuildQuery[$GetKey] = $GetVal;
                    }
                    // 模式２的路由方式
                    $ModeQueryString = $val;
                    while(list($k ,$v) = each($this->HttpBuildQuery) ) {
                        if( !($k AND $v) )  continue;
                        $ModeQueryString .= "/".$k."/",$v;
                    }
                    // 返回模式２的完整路由
                    $this->FullUrl = $ModeQueryString;
                }
            }
        }
        // 是否开启路由重写,只使用模式１
        if( !empty($this->UrlRewrite) AND 1 == $this->UrlMode ) {
            // 直接正则匹配
            while( list($pattern ,$object) = each($this->UrlRewrite) ) {
                if($afterReplace = preg_replace($pattern ,$object,$this->QueryString)) {
                    $this->FullUrl = $afterReplace;
                }
            }
        }
        //  处理$_GET与$_POST
        switch ($this->UrlMode) {
        case 1 :
            $spiltQuery = explode("?" ,$ths->FullUrl) ;
            $index_handle = $spiltQuery[0];
            // 获取控制器与动作
            if( "/" == $index_handle ) {
                $this->Controller = C("DEFAULT_INDEX");
                $this->Action     = C("DEFAULT_HANDLE");
            }else{
                $C_A = explode("/",$index_handle);
                $this->Controller = $C_A[1];
                $this->Action     = $C_A[2];
            }
            // 只截取第一个?
            $GetData = join("?",array_shift($spiltQuery));
            // 数据返回$_GET
            parse_str($GetData ,$_GET);
            break;
        case 2 :
            $spiltQuery = explode("/" ,$this->FullUrl);
            $this->Controller   = $spiltQuery[1];
            $this->Action       = $spiltQuery[2];
            for($k = 3 ; $k<count($spiltQuery) ; $k++) {
                $this->HttpBuildQuery[$spiltQuery[$k]] = $spiltQuery[$k+1];
            }
            // 返回$_GET数据
            $_GET = $this->HttpBuildQuery;
            break;
        }
        // 将数据交给控制器处理
        $this->WorkControllerClass();
    }
    // 控制器处理与检测
    public function WorkControllerClass () {
        // 控制器名
        $ControllerName = ucfirst(strtolower($this->Controller))."Controller";
        // 检查是否默认控制与动作
        if($this->Controller == C("DEFAULT_INDEX") AND $this->Action == C("DEFAULT_HANDLE")) {
            if( !file_exists(PRO_PATH."/controller/".$ControllerName.EXTS) ) {
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
        $allMethod = get_class_methods($ControllerHandle);
        // 检测是否开启路由大小写
        // 与匹配的方式检测，方便大小写规则
        $ActionPattern = C("URL_CASE_INSENSITIVE") ? "/{$ActionName}/" : "/{$ActionName}/i";
        if( !preg_match($ActionPattern ,join(" ",$allMethod)) ) {
            // 不存在操作方法，放回错误
            trigger_error("不存在动作{$ActionName}",E_USER_ERROR);
            exit;
        }
        // 否则运行操作方法
        $ControllerHandle->$ActionName();
    }
    // 写入欢迎界面
    public function outputWelcomePage () {
        $ControllerName = ucfirst(strtolower($this->Controller))."Controller";
        $welcomeClassName = PRO_PATH."/controller/".$ControllerName.EXTS;
        $fileHandler       = fopen($welcomeClassName ,"a+");
        fwrite("<?php class {$ControllerName} extends Controller { public function {$Action} () { $this->show("<center><h2>欢迎使用notephp,使用愉快，哈哈</h2></center>"); } } ?>" , $fileHandler);
        fclose($fileHandler);
        $newCtrl = new $ControllerName();
        $newCtrl->$Action();
        exit;
    }
}
?>
