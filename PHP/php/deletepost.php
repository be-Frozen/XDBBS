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
$params=[];

$conn->query("set names utf8mb4;");

$sql="select authorid from post where id={$id};";
$result = $conn->query($sql);
$row = mysqli_fetch_assoc($result);
$authorid=$row['authorid'];

$sql = "delete from post where id={$id} ;";
$result = $conn->query($sql);
$sql = "update user set postnum=user.postnum-1 where id={$authorid};";
$result = $conn->query($sql);
$conn->close();
echo 'success';