<?php
include ('PHPExcel/PHPExcel.php'); // 引入配置文件
include ('Library\function.php');    // 引入函数库 
header("Content-Type:text/html;charset=utf-8");
$订单流水号= $_POST['订单流水号'];
$订单编号= $_POST['订单编号'];
$项目名称= $_POST['项目名称'];
$标签颜色= $_POST['标签颜色'];
$path='';
if (! empty ( $_FILES ['file_stu'] ['name'] ))
 {
   $tmp_file = $_FILES ['file_stu'] ['tmp_name'];
   $file_types = explode ( ".", $_FILES ['file_stu'] ['name'] );
   $file_type = $file_types [count ( $file_types ) - 1];
    
    if (strtolower ( $file_type ) != "xlsx")            
   {
     //  $this->error ( '不是Excel文件，重新上传' );
    // msg(2,' 请选择Excel.xlsx文件，重新上传',"add_dingdan.php?id=$订单流水号");
     echo "<script>alert('请选择Excel.xlsx文件，重新上传！');location='add_dingdan.php?id=$订单流水号';</script>";//弹出对话框
    }
  
    $savePath = dirname(__FILE__). '\public\upfile\Excel';

    $file_name = time(). "." . $file_type;
    $path=$savePath."\\".$file_name;
    if (! move_uploaded_file($tmp_file,$path)) 
     {
       // msg(2,' 上传失败！','add_dingdan.php');
        echo "<script>alert('上传失败！');location='add_dingdan.php?id=$订单流水号';</script>";//弹出对话框

     }
     $exfn = _readExcel($path,$订单流水号,$订单编号,$项目名称,$标签颜色);
    // $toUrl = "Location:add_dingdan.php?id=".$订单流水号;
   //  header($toUrl); 
 }else{
 // msg(2,' 请选择Excel.xlsx文件上传',"add_dingdan.php?id=$订单流水号");
  echo "<script>alert('请选择Excel.xlsx文件上传');location='add_dingdan.php?id=$订单流水号';</script>";//弹出对话框

 }



//创建一个读取excel数据，可用于入库  
 function _readExcel($path,$订单流水号,$订单编号,$项目名称,$标签颜色)  
{      
    //引用PHPexcel 类  
     include ('PHPExcel/PHPExcel/IOFactory.php'); 
      //加载excel文件  
      $filename = $path;  
      $objPHPExcelReader = PHPExcel_IOFactory::load($filename);    
      $res_arr = array();  
      $reader = $objPHPExcelReader->getWorksheetIterator();  
      //循环读取sheet  
      foreach($reader as $sheet) {  
          //读取表内容  
          $content = $sheet->getRowIterator();  
          //逐行处理  
          //$res_arr = array(); 
          // 新增
          include ('Conn\config.php'); 
          $num=0;
          $query = "select MAX(cast(详单编号 AS INTEGER))  from 详单表 WHERE 订单流水号 ='$订单流水号'";
          $res = $pdo->prepare($query);
          $res->execute();
          $strArr = $res->fetch(PDO::FETCH_ASSOC);
          if(is_array($strArr ) && !empty($strArr['max'])){
            $num=(int)$strArr['max'];
          }
          
          foreach($content as $key => $items) {  
                
               $rows = $items->getRowIndex();              //行  
               $columns = $items->getCellIterator();       //列  
               $row_arr = array();  
               //确定从哪一行开始读取  
               if($rows < 2){  
                   continue;  
               }  
               //逐列读取  
               foreach($columns as $head => $cell) {  
                   //获取cell中数据  
                   $data = $cell->getValue();  
                   $row_arr[] = $data;  
               }  
               $res_arr[] = $row_arr;  
               $num=$num+1;
               $wz='';
             
               for($i=2;$i<7;$i++){
                if(!empty($row_arr[$i])){
                  if(!empty($wz)){
                    if($i!=4){
                      $wz = $wz.'-'.$row_arr[$i];
                    }
                    else{
                      $wz=$wz.'F-'.$row_arr[$i];
                    }
                  }else{
                    $wz=$row_arr[$i];
                  }
                }
              }


             $wz=str_replace("'","''",$wz);
             $wz=str_replace("‘","''",$wz);
             $wz=str_replace("’","''",$wz);
              $cmmz=$row_arr[8];
              if(!empty($cmmz)&!empty($row_arr[7])){
                $cmmz=$cmmz.'/'.$row_arr[7];
              }
              if(empty($cmmz)){
                $cmmz=$row_arr[7];
              }
               $query = "insert into 详单表(订单流水号,订单编号,详单编号,项目名称,位置,产品名称,颜色,开向,尺寸规格,类型,标签颜色)
                values('$订单流水号',
                '$订单编号',
                $num,
                '$项目名称',
                '$wz',
                '$cmmz',
                '$row_arr[9]',
                '$row_arr[10]',
                '$row_arr[11]',
                '$row_arr[12]',
                '$标签颜色'
                )";
                $res = $pdo->prepare($query);
                $res->execute();
          }  
      } 
      if($res->rowCount()){
        echo "<script>alert('操作成功');location='add_dingdan.php?id=$订单流水号&ddbh=$订单编号';</script>";//弹出对话框
        delFile($path);
      }else{

        echo "<script>alert('操作失败');location='add_dingdan.php?id=$订单流水号&ddbh=$订单编号';</script>";//弹出对话框
      }
    /// return $res_arr; 
}  


//删除文件  $path为绝对路径
 function delFile($path){
    $url=iconv('utf-8','gbk',$path);
    if(PATH_SEPARATOR == ':'){ //linux
    unlink($path);
    }else{//Windows
    unlink($url);
    }
    }


?>