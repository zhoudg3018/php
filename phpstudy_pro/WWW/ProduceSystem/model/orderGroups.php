<?php
 include ("pdo_config.php");
 header('Content-Type:application/json; charset=utf-8');

  //获取变量
  $branchNo=isset($_GET["branchNo"])?$_GET["branchNo"]:''; 
  $customer=isset($_GET["customer"])?trim($_GET["customer"]):''; 
  $detailsNo=isset($_GET["detailsNo"])?trim($_GET["detailsNo"]):''; 
  $produceNo=isset($_GET["produceNo"])?trim($_GET["produceNo"]):''; 
  $type=isset($_GET["type"])?trim($_GET["type"]):''; 
  $id=isset($_GET["id"])?trim($_GET["id"]):''; 
 //查询条件
  //批次号
  $query_str='';
  if( $branchNo!=''){
      $query_str=$query_str." AND T.批次号 ='$branchNo' ";
  }
  if( $id!=''){
    $query_str=$query_str." AND T.pid ='$id' ";
  }
  if( $customer!=''){
      $query_str=$query_str." AND T.客户姓名 like '$customer%' ";
  }

  if( $detailsNo!=''){
    $query_str=$query_str." AND T.详单编号 like '$detailsNo%' ";
  }
 
  if( $produceNo!=''){
    $query_str=$query_str." AND T.生产单号 like '$produceNo%' ";
  }
  if($type!=''& $type =='SELECT_F'){

    $query = '	SELECT * FROM 组单表视图 T
                WHERE 1=1 '.$query_str.'
                ORDER BY T.生产单号, T.组单编号  ASC ';
        $res = $pdo->prepare($query);
        $res->execute();
        $count=$res->rowCount();
        $i=1;
        echo '{ "code": 0,"msg": "","id": "","count":'.$count.',"data":[';
        if($count>0){ 
          while($row = $res->fetch(PDO::FETCH_ASSOC)){
            $i++;
            echo json_encode($row);
            if($i<$count+1){
              echo ",";
            }
          }
        }
        echo "]}";
      }
      if($type!=''& $type =='SELECT_C'){

        $query = '	SELECT * FROM 详单表视图 T
                    WHERE 1=1 '.$query_str.'
                    ORDER BY T.生产单号, T.详单编号  ASC ';
            $res = $pdo->prepare($query);
            $res->execute();
            $count=$res->rowCount();
            $i=1;
            echo '{ "code": 0,"msg": "","id": '.$id.',"count":'.$count.',"data":[';
            if($count>0){ 
              while($row = $res->fetch(PDO::FETCH_ASSOC)){
                $i++;
                echo json_encode($row);
                if($i<$count+1){
                  echo ",";
                }
              }
            }
            echo "]}";
          }
    
  else if($type!=''& $type =='DELETE'){
    $query = 'DELETE FROM 详单表 T
    WHERE 1=1 '.$query_str;
    $res = $pdo->prepare($query);
    $res->execute();
    $count=$res->rowCount();
    echo '{ "code": 0,"msg": "OK","count":'.$count.',"data":[]}';
  }
?>

