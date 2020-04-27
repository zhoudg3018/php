<?php
 include ("./pdo_config.php");
 header("Content-Type:text/html;charset=utf-8");
 //获取变量
 $username=isset($_POST["username"])?$_POST["username"]:''; 
 $name=isset($_POST["name"])?$_POST["name"]:''; 
 $password=isset($_POST["password"])?$_POST["password"]:''; 
 $usertype=isset($_POST["usertype"])?$_POST["usertype"]:''; 

 $query = "SELECT 用户名 FROM 用户表  WHERE 用户名 = '$username' LIMIT 1";
 $res = $pdo->prepare($query);
 $res->execute();
 if($res->rowCount()>0){
  die ("该用户名已存在");
 }

//更新
$query = "INSERT INTO  用户表 (用户名,姓名,密码,用户类别) VALUES('$username','$name','$password','$usertype')";
$res = $pdo->prepare($query);
$res->execute();
  if( $res->rowCount()>0){
    echo "追加成功";
  }else{
    echo "追加失败";
  }
?>


