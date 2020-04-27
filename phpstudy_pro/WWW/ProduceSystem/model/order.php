<?php
 include ("pdo_config.php");
 header("Content-Type:text/html;charset=utf-8");
 //获取变量
 $page=isset($_GET["page"])?$_GET["page"]:1; 
 $limit=isset($_GET["limit"])?trim($_GET['limit']):10; 

 $branchNo=isset($_GET["branchNo"])?$_GET["branchNo"]:''; 
 $detailsNo=isset($_GET["detailsNo"])?$_GET["detailsNo"]:''; 
 $produceNo=isset($_GET["produceNo"])?$_GET["produceNo"]:''; 
 $createName=isset($_GET["createName"])?$_GET["createName"]:''; 

//查询条件
 $query_str='';

 if( $branchNo!=''){
  $query_str=$query_str." AND  批次号 = '$branchNo'";
}

 if( $produceNo!=''){
    $query_str=$query_str." AND  生产单号 = '$produceNo'";
 }

 if( $detailsNo!=''){
    $query_str=$query_str." AND 订单编号 = '$detailsNo'";
 }

 if( $createName!=''){
    $query_str=$query_str." AND 导入人员 = '$createName'";
 }

//查询总数
 $query = "select 批次号 from 批次号表 where 1=1 ".$query_str;
 $res = $pdo->prepare($query);
 $res->execute();
 $total=$res->rowCount();
 //分页页数计算
 $perpage=ceil($total/$limit);
 $offset=($page-1)*10;

 $query = "select * from 批次号表
 where 1=1 ".$query_str."
 order by 批次号 desc limit {$limit} offset {$offset} ";
 $res = $pdo->prepare($query);
 $res->execute();
 $count=$res->rowCount();
 echo '{"code":0,"msg":"","perpage":'.$perpage.',"count":'.$total.',"data":[';
if($count>0){    
  $i=1;
  while($row = $res->fetch(PDO::FETCH_ASSOC)){
    $i++;
    echo json_encode($row);
    if($i<$count+1){
      echo ",";
    }
  }
}
echo "]}";
?>

