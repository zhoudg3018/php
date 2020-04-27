<?php
include ('../PHPExcel/PHPExcel.php'); // 引入配置文件

header("Content-Type:text/html;charset=utf-8");
$branchNo= $_POST['branchNo'];

$path='';
if (! empty ( $_FILES ['file'] ['name'] ))
 {
   $max_size="2000000"; //最大文件限制（单位：byte）
   $tmp_file = $_FILES ['file'] ['tmp_name'];
   $file_types = explode ( ".", $_FILES ['file'] ['name'] );
   $file_type = $file_types [count ( $file_types ) - 1];
    
   if (strtolower($file_type) != "xls" && strtolower($file_type) != "xlsx")      
   {
     $res['code'] = '0';
		 $res['err']  = '请选择*.xlsx 或者 *.xls文件，重新上传！';
		 die(json_encode($res));
    }
  
    $savePath = dirname(__FILE__). '\upfile\Excel';

    $file_name = time(). "." . $file_type;
    $path=$savePath."\\".$file_name;
    if (! move_uploaded_file($tmp_file,$path)) 
     {
     // echo json_encode(array('code' => 2, 'msg' => $file_name.'上传失败！'));
      $res['code'] = '0';
      $res['err']  = $file_name.'上传失败！';
      die(json_encode($res));
       // die;
     }
    $exfn=_readExcel($path);
    delFile($path);
    insertToPG($exfn,$branchNo);
 }else{
  $res['code'] = '0';
  $res['err']  = '请选择*.xlsx 或者 *.xls文件，重新上传！';
  die(json_encode($res));
       
 }
//创建一个读取excel数据，可用于入库  
function _readExcel($path)  
{      
    //引用PHPexcel 类  
    require '../PHPExcel/PHPExcel/IOFactory.php'; 
      //加载excel文件  
      $filename = $path;  
      $objPHPExcelReader = PHPExcel_IOFactory::load($filename);    
  
      $sheet = $objPHPExcelReader->getSheet(0);        // 读取第一个工作表(编号从 0 开始)  
      $highestRow = $sheet->getHighestRow();           // 取得总行数  
    //  $highestColumn = $sheet->getHighestColumn();     // 取得总列数  
    
      $arr = array('客户姓名','详单编号','生产单号','组号','大类','型号','产品尺寸','数量','门做法',
        '树种','油漆类型','油漆颜色','门玻璃','洞口类型','门长','门宽','门厚','封边类型','门锁孔','合页槽');  

            // 一次读取一列  
            $res_arr = array();  
            for ($row = 2; $row <= $highestRow; $row++) {  
                $row_arr = array();  
                for ($column = 0; $arr[$column] != ''; $column++) {  
                    $val = $sheet->getCellByColumnAndRow($column, $row)->getValue();  
                    $row_arr[] = $val;  
                }  
                $res_arr[] = $row_arr;  
            } 
         return $res_arr;
  }
function insertToPG($exfn,$branchNo){
  try{
    $host    = "host=localhost";
    $port    = "5432";
    $dbname   = "Test";
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
   
    //for循环遍历数组
    for($i = 0; $i < count($exfn); $i++) {
        $arr =$exfn[$i];
        $sql="INSERT INTO 详单表 (批次号,客户姓名,详单编号,生产单号,组号,大类,型号,产品尺寸,数量,门做法,树种,油漆类型,油漆颜色,
        门玻璃,洞口类型,门长,门宽,门厚,封边类型,门锁孔,合页槽)VALUES(
        '$branchNo','$arr[0]','$arr[1]',
        '$arr[2]','$arr[3]','$arr[4]',
        '$arr[5]','$arr[6]','$arr[7]',
        '$arr[8]','$arr[9]','$arr[10]',
        '$arr[11]','$arr[12]','$arr[13]',
        '$arr[14]','$arr[15]','$arr[16]',
        '$arr[17]','$arr[18]','$arr[19]');";
         $conn->exec($sql);//预处理
    }
   
    //提交事务
    $conn->commit(); 
    $res['code'] = '1';
    $res['err']  = 'Excel导入成功！';
    die(json_encode($res));
  } catch(PDOException $e){
    //回滚事务
    $conn->rollBack();
   //开启自动提交
   // $conn-> setAttribute(PDO::ATTR_AUTOCOMMIT, 1);
   //echo json_encode(array('code' => 3, 'msg' => "<br>".$e->getMessage()));
   $res['code'] = '0';
   $res['err']  = $e->getMessage();
   die(json_encode($res));
  }
  //$conn->setATTribute(PDO::ATTR_AUTOCOMMIT,1); //事务结束后，还原设置为自动提交单独语句
  $conn=null;  //关闭连接
  
}
  

//删除文件 $path为绝对路径
 function delFile($path){
    $url=iconv('utf-8','gbk',$path);
    if(PATH_SEPARATOR == ':'){ //linux
    unlink($path);
    }else{//Windows
    unlink($url);
    }
}
?>