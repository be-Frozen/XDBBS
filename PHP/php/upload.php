<?php
//echo "ok";
if(!empty($_FILES)){
$filename=$_FILES["file"]["name"];
//$ans=$_FILES["file"];
//$ans=json_encode($ans,JSON_UNESCAPED_UNICODE);
//echo $ans;
//$filetype=$_FILES["file"]["type"];
$temppath=$_FILES["file"]["tmp_name"];
//$temppath="";
//echo $temppath;
//echo $filename;
//echo $filetype;
$filetype=substr(strrchr($filename,'.'),1);
$file=substr(strrchr($temppath,'\\'),1);
//$filename=str_replace(strrchr($file, "."),".".$filetype,$file);
//$filename=$lastModified.".".$filetype;
$fp = fopen("file.txt", 'r');
$filename = fread($fp, 1024);
// 读取完事后关闭文件
fclose($fp);


copy($_FILES["file"]["tmp_name"],'./'.$filename);
echo $filename;

}
