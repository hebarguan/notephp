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
    // 定义构造函数
    public function __construct() {
        $this->UrlMode = C('URL_MODE');
        $this->QueryString = $_SERVER['REQUEST_URI'];
        $this->UrlRewrite  = C('URL_MAP_RULES');
        // 自动开启session 
        session_start();
    }
    // 开始路由处理
    public function start () {

    }
}
