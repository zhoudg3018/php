<?php
include ('../../PHPExcel/PHPExcel.php'); // 引入配置文件
header("Content-Type:text/html;charset=utf-8");
header("Access-Control-Allow-Origin: *"); //解决跨域
header('Access-Control-Allow-Methods:POST');// 响应类型  
header('Access-Control-Allow-Headers:*'); // 响应头设置 
$path='';


$field=isset($_POST["field"])?$_POST["field"]:''; 
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
 
      $arr = explode(",", $field);
      die( _readExcel($tmp_file,$arr));
   }
   die($str); 
 }
//创建一个读取excel数据，可用于入库  
function _readExcel($filename,$arr)  
{      
    //引用PHPexcel 类  
    include '../../PHPExcel/PHPExcel/IOFactory.php'; 
   
    try{
      //加载excel文件  
      $objPHPExcelReader = PHPExcel_IOFactory::load($filename);    
      $sheet = $objPHPExcelReader->getSheet(0);        // 读取第一个工作表(编号从 0 开始)  
      $highestRow = $sheet->getHighestRow();           // 取得总行数  
      //$highestColumn = $sheet->getHighestColumn();     // 取得总列数  
        // 一次读取一列  
        $res_arr ='';  
        for ($row = 2; $row <= $highestRow; $row++) {  
         
            $str='{';
            for ($column = 0; $column< count($arr); $column++) {  
                $val = $sheet->getCellByColumnAndRow($column, 1)->getValue();  
               if($arr[$column]==$val){
           
                $val = $sheet->getCellByColumnAndRow($column, $row)->getValue();  
        
                if($str=='{'){
                  $str=$str.'"'.$arr[$column].'":"'.$val.'"'; 
                }else{

                  $str=$str.',"'.$arr[$column].'":"'.$val.'"'; 
                }
               
               } 
            }  
            $str=  $str.'}';
            if($row ==2){
              $res_arr= $str;
            }else{
              $res_arr= $res_arr.','.$str;
            }
        } 
      return '{ "code": 0,"msg": "上传成功","data":['. $res_arr.']}';
    } catch (Exception $e) {
     
      $msg= "上传失败: " . $e->getMessage();
      return '{ "code": 0,"msg": "'.$msg.'","data":[]}';
  }    
}

?>