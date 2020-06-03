<?php
 include ("../conn/pdo_config.php");
 header("Content-Type:text/html;charset=utf-8");
 //获取变量
 $type=isset($_POST["type"])?$_POST["type"]:'SELECT_TB'; 
if( $type=='SELECT_TB'){
    echo selectFunTB($pdo);
}else
if( $type=='SELECT_R'){
    $query = "SELECT id,rolename from sy_role order by id";
    echo selectFun($pdo,$query);
}else if( $type=='SELECT_M'){
   $query = "SELECT id,nm from sy_menu WHERE hr is not null order by id";
    echo selectFun($pdo,$query);
}else if( $type=='DELETE_T'){
    echo delFun($pdo);
  }
 else if( $type=='EDIT_D'){
    echo editInsert($pdo);
  }
function selectFun($pdo,$query){

    //查询总数
   
    $res = $pdo->prepare($query);
    $res->execute();
    $count=$res->rowCount();
    $str='';
    if($count>0){    
      $str= '{"code":0,"msg":"OK","count":'.$count.',"data":[';
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
       $str='{"code":1,"msg":"无数据","count":'.$count.',"data":[]}';
    }
    
    return $str;
}
function delFun($pdo){
    $checkData=isset($_POST["checkData"])?$_POST["checkData"]:''; 
    $query='';
  
    for($i = 0; $i < count($checkData); $i++) {
     if($i==0){
       $query="'".$checkData[$i]['id']."'";
     }else{
       $query=$query.",'".$checkData[$i]['id']."'";
     }
    }
    $str= '{"code":0,"msg":"请选择数据","data":[]}';
    if(!empty($query)){
       //更新
       $query = "DELETE FROM sy_role  where id IN ($query)";
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

  function editInsert($pdo){
    $data=isset($_POST["data"])?$_POST["data"]:''; 
    $str= '';
    $query=null;
      if(array_key_exists('id',$data)){
          $query = "UPDATE sy_role  SET rolename='".$data['rolename']."',descr='".$data['descr']."',limits='".$data['limits']."' where id='".$data['id']."'";
      }else{
        $query="SELECT * FROM  sy_role WHERE rolename='".$data['rolename']."'";
        $res = $pdo->prepare($query);
        $res->execute();
        if( $res->rowCount()>0){
          $str= '{"code":1,"msg":"角色已存在！请重新输入","data":[]}';
        }else{
          $query="INSERT INTO sy_role (id,rolename,limits,descr) VALUES ( (SELECT CASE WHEN MAX(id) is null THEN 1 ELSE MAX(id)+1 END FROM sy_role ),'".$data['rolename']."','".$data['limits']."','".$data['descr']."')"; 
        }
      }
      if(!empty($query)&&$str==''){
        //更新
        $res = $pdo->prepare($query);
        $res->execute();
        if( $res->rowCount()>0){
          $str= '{"code":0,"msg":"操作成功","data":[]}';
        }else{
          $str='{"code":0,"msg":"操作失败","data":[]}';
        }
      }
    
     return $str;
  }
  function selectFunTB($pdo){
    $page=isset($_POST["page"])?$_POST["page"]:1; 
    $limit=isset($_POST["limit"])?trim($_POST['limit']):10; 
    $roleArr=isset($_POST["role"])?$_POST["role"]:''; 
        //大类
      $sql='';
      if( $roleArr!=''){
          $role='';
          for($i=0;$i <count($roleArr); $i++){
              $role=$role."'".$roleArr[$i]."',";
              if($i==(count($roleArr)-1)){
                  $role=substr($role,0,strlen($role)-1);
              break;
              }
          }
          $sql=" AND  id in ($role)";
      }
      //查询条件
      $query = "SELECT * from sy_role WHERE 1=1 $sql Order by id ";
      //查询总数
      $res = $pdo->prepare($query);
      $res->execute();
      $total=$res->rowCount();
      //分页页数计算
      $perpage=ceil($total/$limit);
      $offset=($page-1)*10;
  
      $query = "SELECT * from sy_role WHERE 1=1 $sql Order by id  limit {$limit} offset {$offset} ";
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
?>

