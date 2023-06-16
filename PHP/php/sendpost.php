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
function replace_unicode_escape_sequence($match) {
    return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
}
$content=$_GET["content"];

$content=str_replace("%u","\\u",$content);
$content=preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $content);
//echo $content;
//echo $content;
//echo $_GET["params"];
//echo $content;
//print_r($params);
//echo $params['boardIds'][0];
//echo $params['boardIds'][1];
$conn->query("set names utf8mb4;");
$sql="select board.id 'pid',subboard.id 'id' from board,subboard where
                                                               board.name='{$params['boardIds'][0]}'
                                                             and subboard.name='{$params['boardIds'][1]}'
        and board.id=subboard.pid;";
$result = $conn->query($sql);
$row = mysqli_fetch_assoc($result);
$boardid=$row['pid'];
$subboardid=$row['id'];
$sql="select name,head from user where id='{$params['author']}';";
$result = $conn->query($sql);
$row = mysqli_fetch_assoc($result);
$authorid=$params['author'];
$authorname=$row['name'];
$authorhead=$row['head'];

$file=0;
if($params['attachment']!=null)
{
    $sql="insert into file(name, size, address) values ('{$params['attachment']['name']}',{$params['attachment']['size']},'{$params['attachment']['address']}');";
    $result = $conn->query($sql);
    $sql="select id from file where name='{$params['attachment']['name']}' and size={$params['attachment']['size']} and address='{$params['attachment']['address']}';";
    $result = $conn->query($sql);
    $row = mysqli_fetch_assoc($result);
    $file=$row['id'];
}

$sql = "insert into post(author, boardid, boardname, subboardid, subboardname, content,
                 cover,  summary, title, authorid, authorhead, file)
        values ('{$authorname}',{$boardid},'{$params['boardIds'][0]}',{$subboardid},'{$params['boardIds'][1]}',
                '{$content}','{$params['cover']}','{$params['summary']}','{$params['title']}',{$authorid},'{$authorhead}',
                $file);";

$result = $conn->query($sql);

$sql = "update user set postnum=user.postnum+1 where id={$authorid};";
$result = $conn->query($sql);


$sql="select max(id) 'id' from post;";
$result = $conn->query($sql);
$row = mysqli_fetch_assoc($result);


$conn->close();
//echo "success";
echo $row['id'];