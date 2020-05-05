<?php
header("Content-Type:text/html;charset=utf-8");
try{
if(empty($pdosql)){
    $pdosql =  new PDO("sqlsrv:Server=47.96.226.38,1433;Database=Kaiyang", "testky", "ky@12345");
    $pdosql->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
}
}
 catch(Exception $e)
 { 
   // $msg='sql'.$e->getMessage();
  
     die( print_r( $e->getMessage() ) ); 
 }
?>