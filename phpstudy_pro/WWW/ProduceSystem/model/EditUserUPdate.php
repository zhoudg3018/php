<?php
include ("pdo_config.php");
//获取变量
$username=$_POST["username"]; 
$name=$_POST["name"];
$password=$_POST["password"];
$usertype=$_POST["usertype"];
//更新
$query = "update  用户表 set 姓名='$name',密码='$password',用户类别='$usertype'
where 用户名='$username'";
$res = $pdo->prepare($query);
$res->execute();
if( $res->rowCount() <=0){
   echo "更新失败！";//弹出对话框
 }else{
    echo " 更新成功";
 }
?>