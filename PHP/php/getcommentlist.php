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

$sql = "select * FROM comment where postid={$id};";
$result = $conn->query($sql);
$page=1;
if ($result->num_rows > 0){
    while($row = $result->fetch_assoc()) {
        $p=[];
        $p['id']=$row['id'];
        $p['userid']=$row['userid'];
        $p['postid']=$row['postid'];
        $p['content']=$row['content'];
        $p['likecount']=$row['likecount'];
        $p['imgpath']=$row['imgpath'];
        $p['username']=$row['username'];
        $p['time']=$row['time'];
        $p['userhead']=$row['userhead'];
        $p['havelike']=$row['havelike'];
        $p['top']=$row['top'];
        $pramas['list'][]=$p;
        $page+=1;
    }

}
else{
    $pramas['list']=[];
}
$conn->close();
echo json_encode($pramas);