<?php
include ("pdo_config.php");
//获取变量
$branchNo=$_POST["branchNo"]; 

//更新
$query = "DELETE FROM 批次号表  where 批次号= '$branchNo'";
$res = $pdo->prepare($query);
$res->execute();
if( $res->rowCount() <=0){
   echo "删除失败！";//弹出对话框
 }else{
    echo " 删除成功";
 }
?>