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
$params=json_decode($_GET["params"],true);
//function replace_unicode_escape_sequence($match) {
//    return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
//}
//$content=$_GET["content"];
//
//$content=str_replace("%u","\\u",$content);
//$content=preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $content);
//echo $content;
//echo $content;
//echo $_GET["params"];
//echo $content;
//print_r($params);
//echo $params['boardIds'][0];
//echo $params['boardIds'][1];
$conn->query("set names utf8mb4;");
//$sql="select board.id 'pid',subboard.id 'id' from board,subboard where
//                                                               board.name='{$params['boardIds'][0]}'
//                                                             and subboard.name='{$params['boardIds'][1]}'
//        and board.id=subboard.pid;";
//$result = $conn->query($sql);
//$row = mysqli_fetch_assoc($result);
//$boardid=$row['pid'];
//$subboardid=$row['id'];
//$sql="select id,head from user where name={$params['author']};";
//$result = $conn->query($sql);
//$row = mysqli_fetch_assoc($result);
//$authorid=$row['id'];
//$authorhead=$row['head'];
//
//$file=0;
//if($params['attachment']!=null)
//{
//    $sql="insert into file(name, size, address) values ('{$params['attachment']['name']}',{$params['attachment']['size']},'{$params['attachment']['address']}');";
//    $result = $conn->query($sql);
//    $sql="select id from file where name='{$params['attachment']['name']}' and size={$params['attachment']['size']} and address='{$params['attachment']['address']}';";
//    $result = $conn->query($sql);
//    $row = mysqli_fetch_assoc($result);
//    $file=$row['id'];
//}

$sql = "insert into comment(userid,postid,content,imgpath,username,userhead)
        values ('{$params['userid']}','{$params['postid']}','{$params['content']}','{$params['imgpath']}','{$params['username']}','{$params['userhead']}');";




$result = $conn->query($sql);

$sql = "update post set commentcount=commentcount+1 where id={$params['postid']};";
$result = $conn->query($sql);


$conn->close();
//echo "success";