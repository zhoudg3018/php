<?php
include ("../conn/pdo_config.php");
Class sQRCode{
  public $type;
  public $DBDA;
  public $code; 
  public $c_name; 
  public $sDateTime;
  public $eDateTime;
  public $detailNo;
  public $orderNo;
  public $limit;
  public $page;
  public $checkData;
  public $sm_time;
  public $i_value;
   public function sQRCodeFun(){
    $this->type=isset($_POST["type"])?$_POST["type"]:''; 
    $this->code=isset($_POST["code"])?$_POST["code"]:''; 
    $this->id=isset($_POST["id"])?$_POST["id"]:''; 
    $this->gx_name=isset($_POST["gx_name"])?$_POST["gx_name"]:''; 
    $this->sDateTime=isset($_POST["sDateTime"])?$_POST["sDateTime"]:''; 
    $this->eDateTime=isset($_POST["eDateTime"])?$_POST["eDateTime"]:''; 
    $this->detailNo=isset($_POST["detailNo"])?$_POST["detailNo"]:''; 
    $this->orderNo=isset($_POST["orderNo"])?$_POST["orderNo"]:''; 
    $this->limit=isset($_POST["limit"])?$_POST["limit"]:10;
    $this->page=isset($_POST["page"])?$_POST["page"]:1;
    $this->checkData=isset($_POST["checkData"])?$_POST["checkData"]:''; 
    $this->sm_time=isset($_POST["sm_time"])?$_POST["sm_time"]:''; 
    $this->DBDA=new DBDA();
   
    if($this->type=='SELECT_GX'){
        $this->getGXData();
    }else if($this->type=='SELECT_TB'){
        $this->SearchTableData();
   }else if($this->type=='DELETE_T'){
        $this->OneDelete();
    }
      else if($this->type=='DELETE_C'){
        $this->delCheckData();
    }else if($this->type=='SELECT_ONE'){
      $this->OneSearch();
    }else if($this->type=='EDIT'){
    $this->OneEdit();
    }
    else if($this->type=='SELECT_E'){
      $this->GetExcelData();
      }
   }
   public function OneEdit(){
    if($this->sm_time !='' &&$this->sm_time !=''){
        $pdo=$this->DBDA->DBLink();
        $query = "UPDATE 工序进度表  SET  扫描时间 ='$this->sm_time' where id='$this->id'";
        $res = $pdo->prepare($query);
        $res->execute();
        $count=$res->rowCount();
        $str='';
        if($count>0){    
          $str= '{"code":0,"msg":"更新成功","data":[]}';
        }else{
          $str= '{"code":0,"msg":"更新失败","data":[]}';
        }
        $pdo=null;
    }
    else{
       $str='{"code":0,"msg":"请输入扫描时间","data":[]}';
    }
    echo $str;
  }
   public function OneSearch(){
    $pdo=$this->DBDA->DBSQLLink();
    $query = "SELECT TOP 1  * FROM  [dengguoDB].[dbo].[免漆线二码数据表视图] where 行号='$this->id';";
    $res = $pdo->query($query);
    $rows = $res->fetchAll(); 
    $count = count($rows);
    $str='';
    if($count>0){    
      $str= '{"code":0,"msg":"OK","data":[';
      $i=0;
      while($i<$count){
        $str=$str.json_encode($rows[$i]);
        if($i<$count-1){
          $str=$str.",";
        }
        $i++;
      }
      $str=$str."]}";
    }else{
       $str='{"code":1,"msg":"无数据","data":[]}';
    }
    $pdo=null;
    echo $str;
  }
   function delCheckData(){
    $pdo=$this->DBDA->DBLink();
    $query='';
    for($i = 0; $i < count($this->checkData); $i++) {
     if($i==0){
       $query="'".$this->checkData[$i]['id']."'";
     }else{
       $query=$query.",'".$this->checkData[$i]['id']."'";
     }
    }
    if(!empty($query)){
       //更新
       $query = "DELETE FROM 工序进度表  where id IN ($query) ";
       $res = $pdo->prepare($query);
       $res->execute();
       if( $res->rowCount()>0){
        $str= '{"code":0,"msg":"删除成功","data":[]}';
       }else{
        $str='{"code":0,"msg":"删除失败","data":[]}';
       }
     }
     $pdo=null;
     echo $str;
  }
   public function OneDelete(){
    $pdo=$this->DBDA->DBLink();
      //更新
    if($this->id !=''){
      $query = "DELETE FROM 工序进度表  where id = '$this->id'";
      $res = $pdo->prepare($query);
      $res->execute();
      if( $res->rowCount()>0){
       $str= '{"code":0,"msg":"删除成功","data":[]}';
      }else{
       $str='{"code":0,"msg":"删除失败","data":[]}';
      }
    }
    $pdo=null;
    echo $str;
  }
 public function getGXData(){
    $pdo=$this->DBDA->DBSQLLink();
    $query = "SELECT distinct 工序类型 AS name,工序类型 AS value FROM [dengguoDB].[dbo].[免漆线二码数据表视图];";
    $res = $pdo->query($query);
    $rows = $res->fetchAll(); 
    $count = count($rows);
    $str='';
    if($count>0){    
      $str= '{"code":0,"msg":"OK","data":[';
      $i=0;
      while($i<$count){
          $str=$str.json_encode($rows[$i]);
          if($i<$count-1){
          $str=$str.",";
          }
          $i++;
      }
      $str=$str."]}";
    }else{
       $str='{"code":1,"msg":"无数据","data":[]}';
    }
   
    echo $str;
  }
public function SearchTableData(){
    try{
      $pdo=$this->DBDA->DBSQLLink();
        //查询条件
        $sql='';
        if( $this->code !=''){
        $sql=$sql." AND  详单条码 like '$this->code%'";
        }
        //工序
        $gx_arr =explode(",", $this->gx_name);
        if($this->gx_name!=''&&!empty($gx_arr)){
            $gx='';
            for($i=0;$i <count($gx_arr); $i++){
                $gx=$gx."'".$gx_arr[$i]."',";
                if($i==(count($gx_arr)-1)){
                    $gx=substr($gx,0,strlen($gx)-1);
                break;
                }
            }
            $sql=$sql." AND  工序类型 in ($gx)";
        }
        if( $this->sDateTime !=''){
            $sql=$sql." AND  插入时间 >='$this->sDateTime'";
        }
        if( $this->eDateTime !=''){
            $sql=$sql." AND  插入时间 <='$this->eDateTime'";
        }
        if( $this->detailNo !=''){
            $sql=$sql." AND  详单编号 ='$this->detailNo'";
        }
        if( $this->orderNo !=''){
            $sql=$sql." AND  订单编号 ='$this->orderNo'";
        }
        //查询总数
        $query="SELECT count(*) from  [dengguoDB].[dbo].[免漆线二码数据表视图]  where 1=1".$sql;
        $rs = $pdo->query($query);
        $total =$rs->fetchColumn();
        //分页页数计算
        $perpage=ceil($total/$this->limit);
        $offset=($this->page-1)*10;

        $query = "select top {$this->limit} * 
                from (select row_number() 
                over(order by 插入时间 DESC) as rownumber,* 
                from [dengguoDB].[dbo].[免漆线二码数据表视图]  where 1=1 ".$sql.") A
                where rownumber>{$offset};";
        $res = $pdo->query($query);
        $rows = $res->fetchAll(); 
        $count = count($rows);
        $str='';
        if($count>0){    
        $str= '{"code":0,"msg":"OK","perpage":'.$perpage.',"count":'.$total.',"data":[';
        $i=0;
        while($i<$count){
            $str=$str.json_encode($rows[$i]);
            if($i<$count-1){
            $str=$str.",";
            }
            $i++;
        }
        $str=$str."]}";
       }else{
        $str='{"code":1,"msg":"无数据","perpage":'.$perpage.',"count":'.$total.',"data":[]}';
        }
        echo $str;
    } catch(PDOException $e){
        $msg='PG'.$e->getMessage();
        echo '{"code":1,"msg":'.$msg.',"perpage":'.$perpage.',"count":'.$total.',"data":[]}';
    }    
  }
  public function GetExcelData(){
    try{
      $pdo=$this->DBDA->DBSQLLink();
      //查询条件
      $sql='';
      if( $this->code !=''){
      $sql=$sql." AND  详单条码 like '$this->code%'";
      }
      //工序
      $gx_arr =explode(",", $this->gx_name);
      if($this->gx_name!=''&&!empty($gx_arr)){
          $gx='';
          for($i=0;$i <count($gx_arr); $i++){
              $gx=$gx."'".$gx_arr[$i]."',";
              if($i==(count($gx_arr)-1)){
                  $gx=substr($gx,0,strlen($gx)-1);
              break;
              }
          }
          $sql=$sql." AND  工序类型 in ($gx)";
      }
      if( $this->sDateTime !=''){
          $sql=$sql." AND  插入时间 >='$this->sDateTime'";
      }
      if( $this->eDateTime !=''){
          $sql=$sql." AND  插入时间 <='$this->eDateTime'";
      }
      if( $this->detailNo !=''){
          $sql=$sql." AND  详单编号 ='$this->detailNo'";
      }
      if( $this->orderNo !=''){
          $sql=$sql." AND  订单编号 ='$this->orderNo'";
      }


      $query = "select * from [dengguoDB].[dbo].[免漆线二码数据表视图]  where 1=1 ".$sql;
      $res = $pdo->query($query);
      $rows = $res->fetchAll(); 
      $count = count($rows);
      $str='';
      if($count>0){    
      $str= '{"code":0,"msg":"OK","data":[';
      $i=0;
      while($i<$count){
          $str=$str.json_encode($rows[$i]);
          if($i<$count-1){
          $str=$str.",";
          }
          $i++;
      }
      $str=$str."]}";
     }else{
      $str='{"code":1,"msg":"无数据","data":[]}';
      }
      echo $str;
    } catch(PDOException $e){
        $msg='PG'.$e->getMessage();
        echo '{"code":1,"msg":'.$msg.',"data":[]}';
    }    
  }
}
$sQRCode=new sQRCode();

$sQRCode->sQRCodeFun();

?>