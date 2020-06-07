<?php
header("Content-Type:text/html;charset=utf-8");
class DBDA
{
    public function DBLink(){
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
            }
        }catch (PDOException $e){
            die("error:".$e->getMessage());
        }
        return $pdo;
    }

    
    public function DBSQLLink(){

        $host    = "47.96.226.38";
        $port    = "1433";
        $dbname   = "Kaiyang";
        $username = "testky";
        $dbpassword="ky@12345";
        $dsn = "sqlsrv:Server=$host,$port;Database=$dbname";
        try{
            if(empty($conn)){
                $conn =  new PDO($dsn, $username, $dbpassword);
                $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            }
        }catch(Exception $e)
             { 
               // $msg='sql'.$e->getMessage();
              
                 die( print_r( $e->getMessage() ) ); 
             }
             return $conn;
       }
}
?>