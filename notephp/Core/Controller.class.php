<?php
/*
 * Editer hebarguan in 2016-1-30
 * Email hebarguan@hotmail.com
 * 该类为项目控制类的基类
 * 将处理用户逻辑与模板显示
 */
class Controller {

    public function show($str) {
        $text = htmlentities($str);
        echo $text;
    }
}
