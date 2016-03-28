<?php
$imagePath = "../Public/image/test.jpg";
$dataOri = file_get_contents($imagePath);
header("Content-type:image/jpg");
echo $dataOri;

?>
