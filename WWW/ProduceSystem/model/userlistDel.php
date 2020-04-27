<?php
 include ("./pdo_config.php");
 header("Content-Type:text/html;charset=utf-8");
 //获取变量
 $checkData=isset($_GET["checkData"])?$_GET["checkData"]:''; 
 $query='';
 for($i = 0; $i < count($checkData); $i++) {
  if($i==0){
    $query="'".$checkData[$i]['用户编号']."'";
  }else{
    $query=$query.",'".$checkData[$i]['用户编号']."'";
  }
 }
 if(!empty($query)){
    //更新
    $query = "DELETE FROM 用户表  where 用户编号 IN ($query)";
    $res = $pdo->prepare($query);
    $res->execute();
    if( $res->rowCount()>0){
      echo '{"code":0,"msg":"删除成功","data":[]}';
    }else{
     echo '{"code":0,"msg":"删除失败","data":[]}';
    }
  }
  else{
    echo '{"code":0,"msg":"请选择数据","data":[]}';
  }
?>


