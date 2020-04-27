<?php
  include ("../model/pdo_config.php");
header('Access-Control-Allow-Methods:OPTIONS, GET, POST');
header('Access-Control-Allow-Headers: *');
header("Access-Control-Allow-Credentials:true");
header('Access-Control-Allow-Origin: *');
    $username = isset($_GET['username']) ? $_GET['username'] :'';
    $password = isset($_GET['password']) ? $_GET['password'] : '';

    //查询条件
    $query_str='';
   
    if( $username!=''){
     $query_str=$query_str." AND  用户名 = '$username'";
   }else{
    echo '{"code":0,"msg":"请输入用户名","data":[]}';

   }
   
    if( $password!=''){
       $query_str=$query_str." AND  密码 = '$password'";
    }else{
        echo '{"code":0,"msg":"请输入密码","data":[]}';
    }
   
    $query_all = "select 用户名,密码,用户类别 from 用户表
    where 1=1 ".$query_str." limit 1"; 
    $result = $pdo->prepare($query_all);
    $result->execute();
    $count=$result->rowCount();
    
    if($count>0){    
        
        echo '{"code":0,"msg":"登入成功","data":[';
            $i=1;
            while($row = $result->fetch(PDO::FETCH_ASSOC)){
                session_start();
                $_SESSION['username']=$row['用户名'];
                $_SESSION['password']=$row['密码'];
                $_SESSION['usertype']=$row['用户类别'];
            $i++;
            echo json_encode($row);
            if($i<$count+1){
                echo ",";
            }
        }
        echo "]}";
    }else{
        echo '{"code":0,"msg":"登入失败","data":[]}';
    }
   
?>