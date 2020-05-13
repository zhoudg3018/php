<?php
 include ("../conn/pdo_config.php");
 header("Content-Type:text/html;charset=utf-8");
 //获取变量
 $type=isset($_POST["type"])?$_POST["type"]:''; 

if( $type=='SELECT_TB'){
  echo selectFun($pdo);
}
if( $type=='DELETE_T'){
  echo delFun($pdo);
}


function selectFun($pdo){
  $page=isset($_POST["page"])?$_POST["page"]:1; 
  $limit=isset($_POST["limit"])?trim($_POST['limit']):10; 
  $role=isset($_POST["role"])?$_POST["role"]:''; 
  $username=isset($_POST["username"])?$_POST["username"]:''; 
  $name=isset($_POST["name"])?$_POST["name"]:''; 
    //查询条件
    $query_str='';

    if( $username!=''){
      $query_str=$query_str." AND  用户编号 = '$username'";
    }

    if( $role!=''){
        $query_str=$query_str." AND  用户类别 = '$role'";
    }

    if( $name!=''){
        $query_str=$query_str." AND 姓名 = '$name'";
    }

    //查询总数
    $query = "SELECT id from 用户列表 where 1=1 ".$query_str;
    $res = $pdo->prepare($query);
    $res->execute();
    $total=$res->rowCount();
    //分页页数计算
    $perpage=ceil($total/$limit);
    $offset=($page-1)*10;

    $query = "SELECT * from 用户列表
    where 1=1 ".$query_str."
    order by 用户编号 desc limit {$limit} offset {$offset} ";
    $res = $pdo->prepare($query);
    $res->execute();
    $count=$res->rowCount();
    $str= '{"code":0,"msg":"OK","perpage":'.$perpage.',"count":'.$total.',"data":[';
    if($count>0){    
      $i=1;
      while($row = $res->fetch(PDO::FETCH_ASSOC)){
        $i++;
        $str=$str.json_encode($row);
        if($i<$count+1){
          $str=$str.",";
        }
      }
    }
    $str=$str."]}";
    return $str;
}
function delFun($pdo){
  $checkData=isset($_POST["checkData"])?$_POST["checkData"]:''; 
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


}
?>

