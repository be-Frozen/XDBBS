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
$pramas=[];

$conn->query("set names utf8mb4;");

$sql = "select * FROM board;";
$result = $conn->query($sql);

if ($result->num_rows > 0){
    while($row = $result->fetch_assoc()) {
        $p=[];
        $p['id']=$row['id'];
        $p['name']=$row['name'];
        $p['subboard']=[];
        $sql1 = "select * FROM subboard where pid={$p['id']};";
        $result1 = $conn->query($sql1);
        if ($result1->num_rows > 0) {
            while ($row1 = $result1->fetch_assoc()) {
                $p1=[];
                $p1['id'] = $row1['id'];
                $p1['pid'] = $row1['pid'];
                $p1['name'] = $row1['name'];
                $p1['pname']=$p['name'];
                $pramas[]=$p1;
            }
        }
    }
}
$conn->close();
echo json_encode($pramas);