<?php
include ('../../PHPExcel/PHPExcel.php'); // 引入配置文件
include ("../conn/pdo_config.php");
header("Content-Type:text/html;charset=utf-8");
header("Access-Control-Allow-Origin: *"); //解决跨域
header('Access-Control-Allow-Methods:POST');// 响应类型  
header('Access-Control-Allow-Headers:*'); // 响应头设置 


$str='{ "code": 1,"msg": "请选择*.xlsx 或者 *.xls文件，重新上传！","data":[]}';

if (! empty ( $_FILES ['file'] ['name'] ))
 {
  set_time_limit(0); //设置页面等待时间
  ini_set('memory_limit', '-1');//不限制内存
   
   $tmp_file = $_FILES ['file'] ['tmp_name'];
   $file_types = explode ( ".", $_FILES ['file'] ['name'] );
   $file_type = $file_types [count ( $file_types ) - 1];
    
   if (strtolower($file_type) == "xls" || strtolower($file_type) == "xlsx")      
   {
      _readExcel($tmp_file);
   }else{
   echo $str; 
  }
 }
//创建一个读取excel数据，可用于入库  
function _readExcel($filename)  
{      
    //引用PHPexcel 类  
    include '../../PHPExcel/PHPExcel/IOFactory.php'; 
    try{
      $field=isset($_POST["field"])?$_POST["field"]:'';
      $type=isset($_POST["type"])?$_POST["type"]:'';
      $arr = explode(",", $field); 
      //加载excel文件  
      $objPHPExcelReader = PHPExcel_IOFactory::load($filename);    
      $sheet = $objPHPExcelReader->getSheet(0);        // 读取第一个工作表(编号从 0 开始)  
      $highestRow = $sheet->getHighestRow();           // 取得总行数  
      //$highestColumn = $sheet->getHighestColumn();     // 取得总列数  
      // 一次读取一列  
      $res_arr = array();  
      for ($row = 2; $row <= $highestRow; $row++) {  
          $row_arr = array();  
          for ($column = 0; $column<count($arr); $column++) {  
              $val = $sheet->getCellByColumnAndRow($column, $row)->getValue();  
              $row_arr[] = $val;  
          }  
          $res_arr[] = $row_arr;  
      } 
      //return $res_arr;
      if($type=='SBB'){
        insertToDB_SBB($res_arr);
      }else if($type=='SBW'){
        insertToDB_SBW($res_arr);
      }else{
        echo '{ "code": 0,"msg": "无数据","data":[]}';
      }
     
    } catch (Exception $e) {
      $msg= "读取失败!: " . $e->getMessage();
      echo '{ "code": 0,"msg": "'.$msg.'","data":[]}';
  }    
}
function insertToDB_SBB($data){
  try{
    $DBDA=new DBDA();
    $pdo=$DBDA->DBLink();
    if(!empty($data) && count($data)>0){
        $res = $pdo->query('select max(id) from 设备资产表');
        $inId = $res->fetchColumn(0);
        if($inId == null){
            $inId=0;
        }
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //关闭自动提交
      //$conn-> setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
        //开始事务
      $pdo->beginTransaction();
      $sql='';
        //for循环遍历数组
        for($i = 0; $i < count($data); $i++) {
            $inId+=1;
            $arr =$data[$i];
            $syzt=0;
            if($arr[11]=="在用"){
              $syzt=1;
            }
            $BH=sprintf("%08d", $arr[0]);
            $sql="INSERT INTO 设备资产表 (id,设备编号,大类,名称,规格,单位,数量,生产厂家,购置时间,规定使用年限,备注,记录时间,使用部门,使用状态 )VALUES(
            $inId,
            '$BH',
            (SELECT 序号 FROM 设备资产大类表 WHERE 1=1 AND 大类 ='$arr[1]'),
            '$arr[2]',
            '$arr[3]',
            '$arr[4]',
            '$arr[5]',
            '$arr[6]',
            '$arr[7]',
            '$arr[8]',
            '$arr[9]',
            current_date,
            (SELECT 部门编号 FROM 部门表 WHERE 1=1 AND  部门名称='$arr[10]'),
            '$syzt');";
            $pdo->exec($sql);//预处理
        }
    
      //提交事务
      $pdo->commit(); 
      echo '{ "code": 0,"msg": "Excel导入成功!","data":[]}';
    }
  } catch(PDOException $e){
    //回滚事务
    $pdo->rollBack();
   //开启自动提交
   // $conn-> setAttribute(PDO::ATTR_AUTOCOMMIT, 1);
   //echo json_encode(array('code' => 3, 'msg' => "<br>".$e->getMessage()));
   $str= $e->getMessage();
   echo '{ "code": 1,"msg": "'.$str.'","data":[]}';
  }
  //$conn->setATTribute(PDO::ATTR_AUTOCOMMIT,1); //事务结束后，还原设置为自动提交单独语句
  $pdo=null;  //关闭连接
}
function insertToDB_SBW($data){
  try{
    $DBDA=new DBDA();
    $pdo=$DBDA->DBLink();
    if(!empty($data) && count($data)>0){
        $res = $pdo->query('select max(id) from 设备资产维修记录表');
        $inId = $res->fetchColumn(0);
        if($inId == null){
            $inId=0;
        }
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //关闭自动提交
      //$conn-> setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
        //开始事务
      $pdo->beginTransaction();
      $sql='';
        //for循环遍历数组
        for($i = 0; $i < count($data); $i++) {
            $inId+=1;
            $arr =$data[$i];
            $BH=sprintf("%08d", $arr[0]);
            $sql="INSERT INTO 设备资产维修记录表 (id,设备编号,设备名称,规格,维修单位,维修内容,维修时间,维修费用,记录时间,备注,维修人员)VALUES(
            $inId,
            '$BH',
            '$arr[1]',
            '$arr[2]',
            '$arr[3]',
            '$arr[4]',
            '$arr[5]',
            '$arr[6]',
            '$arr[7]',
            '$arr[8]',
            '$arr[9]');";
            $pdo->exec($sql);//预处理
        }
    
      //提交事务
      $pdo->commit(); 
      echo '{ "code": 0,"msg": "Excel导入成功!","data":[]}';
    }
  } catch(PDOException $e){
    //回滚事务
    $pdo->rollBack();
   //开启自动提交
   // $conn-> setAttribute(PDO::ATTR_AUTOCOMMIT, 1);
   //echo json_encode(array('code' => 3, 'msg' => "<br>".$e->getMessage()));
   $str= $e->getMessage();
   echo '{ "code": 1,"msg": "'.$str.'","data":[]}';
  }
  //$conn->setATTribute(PDO::ATTR_AUTOCOMMIT,1); //事务结束后，还原设置为自动提交单独语句
  $pdo=null;  //关闭连接
}
?>