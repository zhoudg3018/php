<?php
 header('Content-Type:application/json; charset=utf-8');
 $branchNo=isset($_GET["branchNo"])?$_GET["branchNo"]:'';
 
  insertSqlToPG($branchNo);

  function insertSqlToPG($branchNo){
    try{
      $host    = "host=localhost";
      $port    = "5432";
      $dbname   = "Productionprinting";
      $username = "postgres";
      $dbpassword="000000";
      $dsn = "pgsql:$host;port=$port;dbname=$dbname";
      $conn = new PDO($dsn, $username, $dbpassword);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //关闭自动提交
      //$conn-> setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
        //开始事务
      $conn->beginTransaction();
      $sql='';
      $j=0;
      $query = "SELECT 批次号 FROM 详单表 WHERE 1=1 AND 批次号='$branchNo'";
      $res = $conn->prepare($query);
      $res->execute();
      if($res->rowCount()>0){
        $query = "DELETE  FROM 详单表 WHERE 1=1 AND 批次号='$branchNo'";
        $res = $conn->prepare($query);
        $res->execute();
        if( $res->rowCount() <=0){
          $conn=null;
          die ('{ "code":0,"msg": "删除失败！请手动点【击删除详单】","data":[]}');
        }
      }

      //获取MMH数据
      $exfn=getDataFromMMh($branchNo);
      //for循环遍历数组
      for($i = 0; $i < count($exfn); $i++) {
          $arr =$exfn[$i];
          $j=$j+1;
          if($j>9999){
            $j=1;
          }
          $ID=substr(strval($j+10000),1,4);
          if(!empty($arr)){
            $sql="INSERT INTO 详单表 (批次号,客户姓名,详单编号,生产单号,组号,大类,型号,产品尺寸,数量,门做法,树种,油漆类型,油漆颜色,
            门玻璃,洞口类型,门长,门宽,门厚,封边类型,门锁孔,合页槽,详单条码,机器码)VALUES(
            '$arr[0]','$arr[1]','$arr[2]','$arr[3]','$arr[4]','$arr[5]','$arr[6]','$arr[7]','$arr[8]','$arr[9]','$arr[10]',
            '$arr[11]','$arr[12]','$arr[13]','$arr[14]','$arr[15]','$arr[16]','$arr[17]','$arr[18]','$arr[19]','$arr[20]','$arr[21]','$ID');";
            $conn->exec($sql);//预处理
          }
      }
      //提交事务
      $conn->commit(); 
      echo ('{ "code":0,"msg": "OK","data":[]}');
    } catch(PDOException $e){
      //回滚事务
      $conn->rollBack();
      $msg= 'PG'.$e->getMessage();
      echo ('{ "code":0,"msg": "插入失败","data":[]}');
    }
    $conn=null;  //关闭连接
    
  }
  function getDataFromMMh($branchNo){
    include ("pdo_configMMh.php");
    $query = " select c.生产批次号
      ,'' AS 客户姓名
      ,b.详单编号
      ,b.生产单号
      ,组号
      ,大类
      ,型号
      ,产品尺寸
      ,'1' AS 数量
      ,门做法
      ,b.树种
      ,b.油漆类型
      ,b.油漆颜色
      ,门玻璃
      ,'' AS  洞口类型
      --,门锁孔
      ,[dbo].[S_GetSubStr](产品尺寸,'*',0) 门长
      ,[dbo].[S_GetSubStr](产品尺寸,'*',1) 门宽
      ,[dbo].[S_GetSubStr](产品尺寸,'*',2) 门厚
      ,( select top 1 字段2 from 组单表 where 订单编号=a.订单编号 and 组号=b.组号) as 封边类型
      ,[dbo].[S_GetSubStr](门锁孔,'-',0) as 门锁孔
      ,'' AS 合页槽
      ,详单条码
      ,组序号
      ,c.产品类型
      ,详单条码
      ,详单备注
      ,开启方式
      ,墙垛
      from 详单条码表 a
      join 视图_详单表 b on a.详单编号=b.详单编号
      join 视图_订单表 c on a.订单编号=c.订单编号
      where  b.类别='门' AND c.生产批次号='$branchNo'
      AND c.产品类型 IN ('免漆门','圣都-免漆') --AND 大类 IN ('门','移动门')
      order by 生产单号,组号,组序号;";
      $res = $pdosql->query($query);
      $row_arr=array();
      while ($row = $res->fetch(PDO::FETCH_NUM)){
        $row_arr[]=$row;
      }
      $pdosql=null;
      return $row_arr;
  }
 
?>