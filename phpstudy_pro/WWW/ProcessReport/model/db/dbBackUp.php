<?php
   header('Content-Type:application/html; charset=utf-8');
   $DBtype = isset($_POST["DBtype"]) ? $_POST["DBtype"] : "GET_DB_FILES";
   $data = isset($_POST["data"]) ? $_POST["data"] : "";

   $DBBAK_DIR='dbBackUp';
   if($DBtype=='DB_BACKUP'){
        $dbname="demo";
        $USER="postgres";
        $password="000000";
        $url =  'DBbackUp.vbs 2>&1';
        $url_bat ='DBbackUp.bat';
        system($url.' '.$url_bat.' '.$DBBAK_DIR.' '.$dbname.' '.$USER.' '.$password,$return_var);
      if($return_var==0){
        echo '备份成功';
      }else{
        echo '备份失败';
      }
  }else if($DBtype =='GET_DB_FILES'){
    $file_path=$DBBAK_DIR;
    if (is_dir($file_path)){
      $handler = opendir($file_path);
      $strArr='';
      while( ($filename = readdir($handler)) !== false ) {
        if($filename != "." && $filename != ".."){
          if($strArr==''){
            $strArr= $strArr.'{"filename":"'.$filename.'","filepath":'.json_encode($DBBAK_DIR.'/'.$filename).'}';
           
          }else{
           $strArr= $strArr.',{"filename":"'.$filename.'","filepath":'.json_encode($DBBAK_DIR.'/'.$filename).'}';
      
          }
        }
      }
      closedir($handler);
      $str= '{ "code": 0,"msg": "OK","data":[';
      if($strArr !=''){
        $str=$str.$strArr;
      }
      $str=$str."]}";
      echo $str;
    }
  }
  else if($DBtype =='DB_DEL'){
    $filepath=dirname(__FILE__).'\\'.$data['filepath'];
    $del_result = @unlink($filepath);
       if ($del_result == true) {
          @unlink($res_filepath);
    }
    if( @unlink($filepath)==false){
      echo '删除成功';
    }else{
      echo '删除失败';
    }
  }
?>