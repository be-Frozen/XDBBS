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
$sql = "select * FROM user;";
$result = $conn->query($sql);
$p=[];
if ($result->num_rows > 0) {
    // 输出数据
    while($row = mysqli_fetch_assoc($result)) {
        $params['id'] = $row['id'];
        $params['name'] =$row['name'];
        $params['sex'] = $row['sex'];
        $params['sign'] = $row['sign'];
        $params['level'] = $row['level'];
        $params['likenum'] = $row['likenum'];
        $params['postnum'] = $row['postnum'];
        $params['jointime'] = $row['jointime'];
        $params['logintime'] = $row['logintime'];
        $params['job'] = $row['job'];
        $params['head']=$row['head'];
        $p[]=$params;
    }
}

$conn->close();
echo json_encode($p);
//echo $params['ok'];