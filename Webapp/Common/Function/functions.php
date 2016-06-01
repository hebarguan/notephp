<?php
function get_count() {
    static $count = 0;
    return $count++;
}
function outText($str, $str1) {
    return $str.$str1;
}
?>
