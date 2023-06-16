<?php
$params=$_GET["params"];
$filename = 'file.txt';
$fp = fopen($filename, "w");
$len = fwrite($fp, $params);
fclose($fp);
echo $params;