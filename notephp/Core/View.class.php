<?php
/*
 * Editer hebarguan in 2016-2-6
 * Email hebarguan@hotmail.com
 * 视图模型处理数据赋值
 * 与模板引擎选择和编译
 */
class View {
    // 模板赋值数据
    private $assginArr = array();
    // 模板引擎
    private $tempEngine = "";
    // 初始化视图模型
    public function __construct () {
        $tempEngineSelect = C('TEMPLATE_ENGINE');
        if( "NoteEng" == $tempEngineSelect ) {
            $this->tempEngine = new Templates();
        }else{
            loadFile('Notephp.Vendor.'.$tempEngineSelect);
            $this->tempEngine = new $tempEngineSelect();
        }
    }
}
