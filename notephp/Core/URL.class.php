<?php 
/*
 * Editer hebarguan in 2016-1-29
 * Email : hebarguan@hotmail.com
 * URL 模式支持两种/id/1/name/hebar , 或?id=1&name=hebar
 */
class URL {
    // 定义路由模式
    private $UrlMode = '';
    // 查询字段
    private $QueryString = '';
    // 完整路由
    private $FullUrl = '';
    // 路由重写
    private $UrlRewrite = '';
    // 路由重写
    private $UrlMap = '';
    //　定义GET参数数组
    private $HttpBuildQuery = array();
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
            // 只截取第一个?
            $GetData = join("?",array_shift($spiltQuery));
            // 数据返回$_GET
            parse_str($GetData ,$_GET);
        }

    }
}
