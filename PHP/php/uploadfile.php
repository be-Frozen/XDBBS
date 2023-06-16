<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "album";

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检测连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}
$fp = fopen("file.txt", 'r');
$address = fread($fp, 1024);
// 读取完事后关闭文件
fclose($fp);


rename("./".$address,"./../文件/".$address);


$conn->close();
echo $address;
