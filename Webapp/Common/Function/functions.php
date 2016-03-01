<?php
function get_count() {
    static $count = 0;
    return $count++;
}
?>
