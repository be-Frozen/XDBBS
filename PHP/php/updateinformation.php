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
//echo $_GET["params"];
$params=json_decode($_GET["params"],true);
$id=$params["id"];
$name=$params["name"];
$head=$params["head"];
$sex=$params["sex"];
$sign=$params["sign"];


$conn->query("set names utf8mb4;");

$sql = "update user set name='{$name}',head='{$head}',sex='{$sex}',sign='{$sign}' where id={$id};";
$result = $conn->query($sql);
$sql="select * from user where id={$id};";
$result = $conn->query($sql);
//print_r($result);
$row = mysqli_fetch_assoc($result);
$params['id']=$row['id'];
$params['ok']=true;
$params['sex']=$row['sex'];
$params['sign']=$row['sign'];
$params['level']=$row['level'];
$params['likenum']=$row['likenum'];
$params['postnum']=$row['postnum'];
$params['jointime']=$row['jointime'];
$params['logintime']=$row['logintime'];
$params['head']=$row['head'];
$params['job']=$row['job'];
$conn->close();
echo json_encode($params);