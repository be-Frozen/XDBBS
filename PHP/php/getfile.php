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
$id=$_GET["pramas"];
$pramas=[];

$conn->query("set names utf8mb4;");

$sql = "select * FROM file where id={$id}; ";
$result = $conn->query($sql);
if ($result->num_rows > 0){
    while($row = $result->fetch_assoc()) {
        $p=[];
        $p['id']=$row['id'];
        $p['name']=$row['name'];
        $p['size']=$row['size'];
        $p['address']=$row['address'];
        $p['downloadcount']=$row['downloadcount'];
        $pramas=$p;
    }
}
$conn->close();
echo json_encode($pramas);