<?php

include "../conn/pdo_config.php";
header('Access-Control-Allow-Methods:OPTIONS, GET, POST');
header('Access-Control-Allow-Headers: *');
header("Access-Control-Allow-Credentials:true");
header('Access-Control-Allow-Origin: *');

$username = isset($_GET['username']) ? $_GET['username'] :'';
$password = isset($_GET['password']) ? $_GET['password'] : '';


echo getToken($pdo,$username,$password);


function getToken($pdo,$username,$password){
        //查询条件
        $query_str='';
        if( $username!=''){
                $query_str=$query_str." AND  用户编号 = '$username'";
        }else{
                return  '{"code":0,"msg":"请输入账号","data":[]}';
        }
        if( $password!=''){
                $password=md5($password);
                $query_str=$query_str." AND  密码 = '$password'";
        }else{
                return '{"code":0,"msg":"请输入密码","data":[]}';
        }

        $query = "SELECT * FROM 用户列表
        WHERE 1=1 ".$query_str." LIMIT 1"; 
        $result = $pdo->prepare($query);
        $result->execute();
        $count=$result->rowCount();
        $msg='';
        $sStr='';
        $Str='';
        $token_str='';
        if($count>0){   
                $msg ='登入成功';
                $i=1;
                while($row = $result->fetch(PDO::FETCH_ASSOC)){
                        $i++;
                        $sStr=json_encode($row);
                        $token_str=md5($row['用户编号'].$row['密码']);
                        if($i>=$count+1){
                                break;
                        }
                }
        }else{
                $msg='登入失败';   
        }
        if($msg=='登入成功'&& $token_str !=''){
                $insertSql = "INSERT INTO 用户token表 (id,用户编号,access_token) VALUES ((SELECT CASE WHEN max(id) is null then 1 else max(id)+1 end from 用户token表),'$username','$token_str')"; 
               if(insertToken($pdo,$insertSql)=='token追加失败'){
                        $msg='token追加失败';
               }
        }    
        $Str='{"code":0,"msg":"'.$msg.'","access_token":"'.$token_str.'","data":['.$sStr.']}';  
        return $Str;
}
//插入数据记录
function insertToken($pdo,$insertSql){
        $result = $pdo->prepare($insertSql);
        $result->execute();
        $count=$result->rowCount();
        if($count<=0){   
            return 'token追加失败';   
        }
}
?>