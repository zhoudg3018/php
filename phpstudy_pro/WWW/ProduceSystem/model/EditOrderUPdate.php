<?php
include ("pdo_config.php");
//获取变量
$branchNo=$_POST["branchNo"]; 
$produceNo=$_POST["produceNo"];
$detailsNo=$_POST["detailsNo"];
//更新
$query = "update  批次号表 set 生产单号='$produceNo',订单编号='$detailsNo'
where 批次号= '$branchNo'";
$res = $pdo->prepare($query);
$res->execute();
if( $res->rowCount() <=0){
   echo "更新失败！";//弹出对话框
 }else{
    echo " 更新成功";
 }
?>