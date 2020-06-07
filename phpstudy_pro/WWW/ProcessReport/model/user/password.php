<?php
  include ("../conn/pdo_config.php");
  $DBDA=new DBDA();
$pdo=$DBDA->DBLink();
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $newpassword = isset($_POST['newpassword']) ? $_POST['newpassword'] : '';
    $oldPassword = isset($_POST['oldPassword']) ? $_POST['oldPassword'] : '';
  
    $query_all = "UPDATE  用户列表 SET 密码='$newpassword'
    where 1=1 AND  用户编号 = '$username'  AND  密码 = '$oldPassword' "; 
    $res = $pdo->prepare($query_all);
    $res->execute();
    if( $res->rowCount() <=0){
        echo "更新失败！";//弹出对话框
      }else{
        $_SESSION['password']=$newpassword;
         echo " 更新成功";
      }
   
?>