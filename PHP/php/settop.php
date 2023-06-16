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
//$params=$_GET["params"];
$id=$_GET["id"];
$top=$_GET["top"];
$params=[];

$conn->query("set names utf8mb4;");

$sql = "update comment set top={$top} where id={$id};";
$result = $conn->query($sql);
$conn->close();
echo 'success';