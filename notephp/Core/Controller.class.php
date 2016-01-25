<?php
namespace NotePHP;

class Controller {

    public function show($str) {
        $text = htmlentities($str);
        echo $text;
    }
}
