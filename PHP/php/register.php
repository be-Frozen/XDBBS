<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bbs";

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检测连接
if ($conn->connect_error) {
die("连接失败: " . $conn->connect_error);
}
$conn->query("set names utf8mb4;");
$name='"'.$_GET["name"].'"';
$password='"'.$_GET["password"].'"';
if($password=='""'){
$sql = "select * FROM user where name={$name};";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
// 输出数据
echo "true";
} else {
echo "false";
}
}
else{

$sql = "insert into user(name,password) values({$name} ,{$password});";
$result = $conn->query($sql);
echo "true";
}

$conn->close();
//return "success";