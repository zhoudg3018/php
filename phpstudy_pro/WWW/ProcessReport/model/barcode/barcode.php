<?php
include ("../conn/pdo_config.php");
Class QRCode{
  public $code; 
  public $gx_name; 
  public $DBDA;

   public function CodeFun(){
    $this->code=isset($_POST["code"])?$_POST["code"]:''; 
    $this->gx_name=isset($_POST["gx_name"])?$_POST["gx_name"]:''; 
    $this->DBDA=new DBDA();
    $this->insertCode();
   }
   public function insertCode(){
    try{
      $pdo=$this->DBDA->DBLink();
      $date=date("Y-m-d h:i:s");
      $sql="SELECT * FROM 工序进度表 WHERE 1=1 工序='$this->gx_name' AND 详单条码='$this->code' LIMIT 1;";
      $result = $pdo->prepare($sql);
      $result->execute();
      $query='';
      if($result->rowCount()>0){
        $query="UPDATE 工序进度表 SET 扫描时间 =to_char(now(), 'YYYY-MM-DD HH24:MI:SS'::text) WHERE 工序='$this->gx_name' AND 详单条码='$this->code';";
      }else{
        $dateM = date("Ym",strtotime($date));
        $firstday = date("Y-m-01",strtotime($date));
        $lastday = date("Y-m-01",strtotime("$firstday +1 month"));
        $sql_check1="create table IF NOT EXISTS 工序进度表_$this->gx_name partition of 工序进度表 for values in ('$this->gx_name') partition by range(扫描时间);";
        $sql_check2="create table IF NOT EXISTS 工序进度表_$this->gx_name"."_$dateM partition of 工序进度表_$this->gx_name for values from ('$firstday') to ('$lastday');";
        $sql = "INSERT INTO 工序进度表 (ID,工序,详单条码,扫描时间) VALUES(
           (SELECT CASE WHEN MAX(id) is null THEN 1 ELSE MAX(id)+1 END FROM 工序进度表),'$this->gx_name','$this->code','$date');";
        $query=$sql_check1.$sql_check2.$sql;
      }
      if($query !=''){
        $res = $pdo->exec($query);
        if($res > 0){
            //获取MMH数据
            $m_Data=$this->getDataFromMMh();
            if(!empty($m_Data)){
              $query = "UPDATE 工序进度表 
              SET 扫描时间 = to_char(now(), 'YYYY-MM-DD HH24:MI:SS'::text)
              ,订单编号='$m_Data[1]'
              ,详单编号='$m_Data[2]'
              ,类别='$m_Data[3]'
              ,详单ID='$m_Data[5]'
              ,详单组='$m_Data[7]'
              ,详单组序号='$m_Data[8]'
                WHERE  详单条码='$this->code' AND 工序='$this->gx_name' ";
                $res = $pdo->prepare($query);
                $res->execute();
                if( $res->rowCount() <=0){
                  echo "更新失败";//弹出对话框
                }else{
                  echo " 更新成功";
                }
              }else{
                echo " 扫描成功";
              }
          }
      }else{
        echo " 扫描失败";
      } 
    } catch(PDOException $e){
      echo 'PG'.$e->getMessage();
    }
  }
 public function getDataFromMMh(){
   $conn=$this->DBDA->DBSQLLink();
   $query = "SELECT * FROM 详单条码表 WHERE 详单条码 ='$this->code'";
   $res = $conn->query($query);
   $row_arr=array();
   while ($row = $res->fetch(PDO::FETCH_NUM)){
      $row_arr=$row;
   }
   $conn=null;
   return $row_arr;
 }
}
$barcode=new QRCode();
$barcode->CodeFun();

?>