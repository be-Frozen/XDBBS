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
$userid=$params["userid"];
$postid=$params["postid"];
$havelike=$params["havelike"];

//echo $_GET["params"];
//echo $userid;
//echo $postid;
//echo "havelike:".$havelike;
$conn->query("set names utf8mb4;");

$sql = "update postlike set havelike={$havelike} where userid={$userid} and postid={$postid};";
$result = $conn->query($sql);
$add=$havelike?1:-1;
$sql = "update post set likecount=likecount+{$add} where id={$postid};";
$result = $conn->query($sql);
$sql = "update user set likenum=user.likenum+{$add} where id={$userid};";
$result = $conn->query($sql);
$sql = "select * FROM post where id={$postid}; ";
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
$sql = "select * FROM postlike where userid={$userid} and postid={$postid}; ";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $p['havelike']=$row['havelike'];
}
$pramas['forumArticle']=$p;

$conn->close();
echo json_encode($pramas);