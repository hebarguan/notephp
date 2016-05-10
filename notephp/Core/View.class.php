<?php
/*
 * Editer hebarguan in 2016-2-6
 * Email hebarguan@hotmail.com
 * 视图模型处理数据赋值
 * 与模板引擎选择和编译
 */

class View
{
    // 模板赋值数据
    private $assginArr = array();
    // 模板引擎
    private $tempEngine = "";
    // 初始化视图模型
    public function __construct()
    {
        $tempEngineSelect = C('TEMPLATE_ENGINE');
        if ("NoteEng" == $tempEngineSelect) {
            $this->tempEngine = new Templates();
        } else {
            loadFile('Notephp.Vendor.'.$tempEngineSelect);
            $this->tempEngine = new $tempEngineSelect();
        }
        $this->tempEngine->left_delimiter = C('SMARTY_LEFT_DELIMITER');
        $this->tempEngine->right_delimiter = C('SMARTY_RIGHT_DELIMITER');
        $this->tempEngine->caching = C('SMARTY_TEMPLATE_CACHE');
        $this->tempEngine->cache_lifetime = C('SMARTY_CACHE_LIFETIME');
        $this->tempEngine->cache_dir = PRO_PATH."/".ucfirst($GLOBALS['PROJECT_REQUEST_MODULE'])."/Runtime/Cache";
        $this->tempEngine->compile_dir = PRO_PATH."/".ucfirst($GLOBALS['PROJECT_REQUEST_MODULE'])."/Runtime/Compile";
    }
    // 模板赋值
    public function set($assginmentArr)
    {
        foreach ($assginmentArr as $var => $val) {
            $this->assginArr[trim(strip_tags($var))] = $val;
        }
    }
    // 模板显示
    public function asHtml($tempFile = '')
    {
        // 变量赋值
        $this->tempEngine->assign($this->assginArr);
        if (is_file($tempFile)) {
            $this->tempEngine->display($tempFile);
        } else {
            trigger_error("模板文件{$tempFile}不存在", E_USER_ERROR);
        }
        
    }
}

