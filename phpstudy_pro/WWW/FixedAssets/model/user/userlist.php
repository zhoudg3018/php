<?php
 include ("../conn/pdo_config.php");
 header("Content-Type:text/html;charset=utf-8");
 //获取变量
 $type=isset($_POST["type"])?$_POST["type"]:''; 
 $token=isset($_POST["token"])?$_POST["token"]:''; 

if( $type=='SELECT_TB'){
  echo selectFun($pdo);
}else
if( $type=='DELETE_T'){
  echo delFun($pdo);
}else
if( $type=='SELECT_E'){
  echo selectEditFun($pdo);
}else
if( $type=='UPDATE_E'|| $type=='INSERT_E'){
  echo EditFun($pdo,$type);
}
function EditFun($pdo,$type){
  $username=isset($_POST["username"])?$_POST["username"]:''; 
  $role=isset($_POST["role"])?$_POST["role"]:''; 
  $name=isset($_POST["name"])?$_POST["name"]:''; 
  $password=isset($_POST["password"])?$_POST["password"]:''; 
  $password=md5($password);
  $query='';
  if($type=='INSERT_E'){
    $query = "INSERT INTO 用户列表 (id,姓名,密码,用户类别,用户编号) values ("
    ."(SELECT CASE WHEN  MAX(id) IS NOT NULL THEN MAX(id)+1 ELSE 1 END  FROM  用户列表),'$name','$password','$role','$username')";
  }else{
    $query = "UPDATE 用户列表 SET  用户类别='$role', 密码='$password', 姓名='$name'  WHERE 用户编号 ='$username'";
  }
  $res = $pdo->prepare($query);
  $res->execute();
  $count=$res->rowCount();
  $str='';
  if($count>0){
    $str='{"code":0,"msg":"更新成功","data":[]}';
  }else{
    $str ='{"code":0,"msg":"更新成功","data":[]}';
  }
  return $str;
}
function selectEditFun($pdo){
  $username=isset($_POST["username"])?$_POST["username"]:''; 
  //更新
  $query = "SELECT * FROM  用户列表  WHERE 用户编号 ='$username' limit 1";
  $res = $pdo->prepare($query);
  $res->execute();
  $count=$res->rowCount();
  $str='';
  if($count>0){
      $str='{"code":0,"msg":"OK","data":[';
      $i=1;
      while($row = $res->fetch(PDO::FETCH_ASSOC)){
        $i++;
        $str=$str.json_encode($row);
        if($i<$count+1){
          $str=$str.",";
        }
      }
      $str=$str."]}";
  }else{
    $str='{"code":0,"msg":"数据获取失败","data":[]}';
  }
  return $str;
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
      $query_str=$query_str." AND  用户编号 like '$username%'";
    }

    if( $role!=''){
        $query_str=$query_str." AND  用户类别 = '$role'";
    }

    if( $name!=''){
        $query_str=$query_str." AND 姓名 like '$name%'";
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
   // $query = "SELECT * from 用户列表
  //  where 1=1 ".$query_str."
  //  order by 用户编号 desc";
    $res = $pdo->prepare($query);
    $res->execute();
    $count=$res->rowCount();
    $str='';
    if($count>0){    
      $str= '{"code":0,"msg":"OK","perpage":'.$perpage.',"count":'.$total.',"data":[';
      $i=1;
      while($row = $res->fetch(PDO::FETCH_ASSOC)){
        $i++;
        $str=$str.json_encode($row);
        if($i<$count+1){
          $str=$str.",";
        }
      }
      $str=$str."]}";
    }else{
       $str='{"code":1,"msg":"无数据","perpage":'.$perpage.',"count":'.$total.',"data":[]}';
    }
    
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
  $str= '{"code":0,"msg":"请选择数据","data":[]}';
  if(!empty($query)){
     //更新
     $query = "DELETE FROM 用户列表  where 用户编号 IN ($query)";
     $res = $pdo->prepare($query);
     $res->execute();
     if( $res->rowCount()>0){
      $str= '{"code":0,"msg":"删除成功","data":[]}';
     }else{
      $str='{"code":0,"msg":"删除失败","data":[]}';
     }
   }
   return $str;
}
?>
