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

$sql = "select * FROM post;";
$result = $conn->query($sql);
$page=1;
if ($result->num_rows > 0){
    while($row = $result->fetch_assoc()) {
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

        $pramas['list'][]=$p;
        $page+=1;
    }
//    pageNo
//    :
//    1
//pageSize
//:
//15
//pageTotal
//:
//7
//totalCount
//:
//102
    $pramas['pageNo']=1;
    $pramas['pageSize']=15;
    $pramas['totalCount']=count($pramas['list']);
    $pramas['pageTotal']=ceil($pramas['totalCount']/$pramas['pageSize']);
}
else{
    $pramas['list']=[];
    $pramas['pageNo']=1;
    $pramas['pageSize']=15;
    $pramas['pageTotal']=1;
    $pramas['totalCount']=0;
}
$conn->close();
echo json_encode($pramas);