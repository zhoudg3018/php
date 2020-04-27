<?php
include ("pdo_config.php");
//获取变量
$branchNo=$_POST["branchNo"]; 
$type=$_POST["type"]; 

switch ($type) {
  case "check":branchNoCheck($pdo,$branchNo);
      break;
  case "insert":branchNoInsert($pdo,$branchNo);
      break;
  default :
      break;
  }


//检查批次号是否已存在
function branchNoCheck($pdo,$branchNo){
$query = "SELECT 批次号 FROM 批次号表  where 批次号= '$branchNo'";
$res = $pdo->prepare($query);
$res->execute();
if( $res->rowCount()>0){
   echo "该批次号已存在！";//弹出对话框
 }
}

 //更新
 function branchNoInsert($pdo,$branchNo){

    $query = "INSERT INTO 批次号表 (批次号) VALUES ('$branchNo')";
    $res = $pdo->prepare($query);
    $res->execute();
    if( $res->rowCount()<=0){
      echo "批次号增加失败！";//弹出对话框
    }
 }
?>