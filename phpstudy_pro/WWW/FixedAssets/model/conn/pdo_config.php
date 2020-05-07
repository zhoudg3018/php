<?php
header("Content-Type:text/html;charset=utf-8");
$host    = "host=localhost";
$port    = "5432";
$dbname   = "demo";
$username = "postgres";
$dbpassword="000000";
$dsn = "pgsql:$host;port=$port;dbname=$dbname";
 
try{
if(empty($pdo)){
    // create a PostgreSQL database connection
    $pdo = new PDO($dsn, $username, $dbpassword, array(
        PDO::ATTR_PERSISTENT => true
    ));

    $pdo -> exec('set names utf-8');//设置输出编码集
}
 // display a message if connected to the PostgreSQL successfully
 if($pdo){
 //echo "Connected to the <strong>$dbname</strong> database successfully!";
 }
}catch (PDOException $e){
 // report error message
 //echo $e->getMessage();
 die("error:".$e->getMessage());
}
?>