<?php
  include ("../model/pdo_config.php");
    session_start();
    $username = $_SESSION['username'];
    $newpassword = isset($_POST['newpassword']) ? $_POST['newpassword'] : '';
    $oldPassword = isset($_POST['oldPassword']) ? $_POST['oldPassword'] : '';
    if(  $oldPassword !=$_SESSION['password']){
        echo "当前密码不正确";
    }else{

    $query_all = "UPDATE  用户表 SET 密码='$newpassword'
    where 1=1 AND  用户名 = '$username'  AND  密码 = '$oldPassword' "; 
    $res = $pdo->prepare($query_all);
    $res->execute();
    if( $res->rowCount() <=0){
        echo "更新失败！";//弹出对话框
      }else{
        $_SESSION['password']=$newpassword;
         echo " 更新成功";
      }
    }
?>