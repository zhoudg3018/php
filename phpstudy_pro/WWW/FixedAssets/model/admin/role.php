<?php
 include ("../conn/pdo_config.php");
 header("Content-Type:text/html;charset=utf-8");
 //获取变量
 $type=isset($_POST["type"])?$_POST["type"]:'SELECT_TB'; 
 $roleArr=isset($_POST["role"])?$_POST["role"]:''; 
 $roleId=isset($_POST["roleId"])?$_POST["roleId"]:''; 

if( $type=='SELECT_TB'){
    //大类
    $query='';
    if( $roleArr!=''){
        $role='';
        for($i=0;$i <count($roleArr); $i++){
            $role=$role."'".$roleArr[$i]."',";
            if($i==(count($roleArr)-1)){
                $role=substr($role,0,strlen($role)-1);
            break;
            }
        }
        $query=" AND  id in ($role)";
    }
    $query = "SELECT * from sy_role WHERE 1=1 $query";
    echo selectFun($pdo,$query);
}else
if( $type=='SELECT_R'){
    $query = "SELECT id,rolename from sy_role";
    echo selectFun($pdo,$query);
}else if( $type=='SELECT_M'){
   $query = "SELECT id,nm from sy_menu order by id";
    echo selectFun($pdo,$query);
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

?>

