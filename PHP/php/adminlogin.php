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
//$params=json_decode($_GET["params"],true);

$conn->query("set names utf8mb4;");
$name=$_GET["name"];
$password=$_GET["password"];
//echo "???";
//print_r($params);
if($password==''){
    $sql = "select * FROM user where name='{$name}';";
}
else{
    $sql = "select * FROM user where name='{$name}' and password='{$password}';";
}
$result = $conn->query($sql);
if ($result->num_rows > 0){
//    echo "ok";
    $row = mysqli_fetch_assoc($result);
    $id=$row['id'];
    $sql = "update user set logintime=now() where id=$id;";
    $result = $conn->query($sql);
    $sql = "select * FROM user where id=$id;";
    $result = $conn->query($sql);
}
if ($result->num_rows > 0) {
    // 输出数据
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
    $params['name']=$name;
    $params['password']=$password;
    $sql = "select * FROM user where name='{$name}' and password='{$password}';";
    $result = $conn->query($sql);
    $row = mysqli_fetch_assoc($result);
//    print_r($row);
    if($row['admin']==0){
        echo "false";
        return;
    }

} else {
    $params['ok']=false;
    echo "false";
    return;
}

$conn->close();
echo json_encode($params);
//echo $params['ok'];