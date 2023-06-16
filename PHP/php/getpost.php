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
$userid=$_GET["userid"];
$pramas=[];

$conn->query("set names utf8mb4;");

$sql = "update post set readcount=post.readcount+1 where id={$id};";
$result = $conn->query($sql);


$sql = "select * FROM post where id={$id}; ";
$result = $conn->query($sql);
$page=1;
$row = $result->fetch_assoc();
$p=[];
$p['id']=$row['id'];
$p['author']=$row['author'];
$p['boardid']=$row['boardid'];
$p['boardname']=$row['boardname'];
$p['subboardid']=$row['subboardid'];
$p['subboardname']=$row['subboardname'];
$p['likecount']=$row['likecount'];
$p['commentcount']=$row['commentcount'];
$p['content']=$row['content'];
$p['cover']=$row['cover'];
$p['time']=$row['time'];
$p['readcount']=$row['readcount'];
$p['summary']=$row['summary'];
$p['title']=$row['title'];
$p['authorid']=$row['authorid'];
$p['authorhead']=$row['authorhead'];
$p['page']=ceil($page/15);
$p['file']=$row['file'];
$p['top']=$row['top'];


$sql = "select * FROM postlike where userid={$userid} and postid={$id}; ";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $p['havelike']=$row['havelike'];
}
else{
    $sql = "insert into postlike(userid, postid) values ($userid,$id); ";
    $result = $conn->query($sql);
    $sql = "select * FROM postlike where userid={$userid} and postid={$id}; ";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $p['havelike']=$row['havelike'];
}
$pramas['forumArticle']=$p;

$page+=1;


$conn->close();
echo json_encode($pramas);