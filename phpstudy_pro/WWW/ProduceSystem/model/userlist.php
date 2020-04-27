<?php
 include ("pdo_config.php");
 header("Content-Type:text/html;charset=utf-8");
 //获取变量
 $page=isset($_GET["page"])?$_GET["page"]:1; 
 $limit=isset($_GET["limit"])?trim($_GET['limit']):10; 

 $userid=isset($_GET["userid"])?$_GET["userid"]:''; 
 $username=isset($_GET["username"])?$_GET["username"]:''; 
 $name=isset($_GET["name"])?$_GET["name"]:''; 


//查询条件
 $query_str='';

 if( $userid!=''){
  $query_str=$query_str." AND  用户编号 = '$userid'";
}

 if( $username!=''){
    $query_str=$query_str." AND  用户名 = '$username'";
 }

 if( $name!=''){
    $query_str=$query_str." AND 姓名 = '$name'";
 }

//查询总数
 $query = "select 用户编号,用户名,姓名,用户类别 from 用户表 where 1=1 ".$query_str;
 $res = $pdo->prepare($query);
 $res->execute();
 $total=$res->rowCount();
 //分页页数计算
 $perpage=ceil($total/$limit);
 $offset=($page-1)*10;

 $query = "select * from 用户表
 where 1=1 ".$query_str."
 order by 用户编号 desc limit {$limit} offset {$offset} ";
 $res = $pdo->prepare($query);
 $res->execute();
 $count=$res->rowCount();
 echo '{"code":0,"msg":"OK","perpage":'.$perpage.',"count":'.$total.',"data":[';
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

