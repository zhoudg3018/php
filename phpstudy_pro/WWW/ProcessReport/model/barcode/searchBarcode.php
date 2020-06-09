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
    $pdo=$this->DBDA->DBLink();
    $query = "SELECT  * FROM  工序进度表视图 where id='$this->id' limit 1";
    $res = $pdo->prepare($query);
    $res->execute();
    $count=$res->rowCount();
    $str='';
    if($count>0){    
      $str= '{"code":0,"msg":"OK","data":[';
      $i=1;
      while($row = $res->fetch(PDO::FETCH_ASSOC)){
        $i++;
        $str=$str.json_encode($row);
        if($i<$count+1){
          $str=$str.",";
        }
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
    $pdo=$this->DBDA->DBLink();
    $query = "SELECT 工序名称 AS name,工序名称 AS value FROM  用户列表 where 工序名称 iS not NULL AND  工序名称<>''";
    $res = $pdo->prepare($query);
    $res->execute();
    $count=$res->rowCount();
    $str='';
    if($count>0){    
      $str= '{"code":0,"msg":"OK","data":[';
      $i=1;
      while($row = $res->fetch(PDO::FETCH_ASSOC)){
        $i++;
        $str=$str.json_encode($row);
        if($i<$count+1){
          $str=$str.",";
        }
      }
      $str=$str."]}";
    }else{
       $str='{"code":1,"msg":"无数据","data":[]}';
    }
    $pdo=null;
    echo $str;
  }
public function SearchTableData(){
    try{
      $pdo=$this->DBDA->DBLink();
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
            $sql=$sql." AND  工序 in ($gx)";
        }
        if( $this->sDateTime !=''){
            $sql=$sql." AND  扫描时间 >='$this->sDateTime'";
        }
        if( $this->eDateTime !=''){
            $sql=$sql." AND  扫描时间 <='$this->eDateTime'";
        }
        if( $this->detailNo !=''){
            $sql=$sql." AND  详单编号 ='$this->detailNo'";
        }
        if( $this->orderNo !=''){
            $sql=$sql." AND  订单编号 ='$this->orderNo'";
        }
        //查询总数
        $query = "SELECT id from 工序进度表视图  where 1=1 ".$sql;
        $res = $pdo->prepare($query);
        $res->execute();
        $total=$res->rowCount();
        //分页页数计算
        $perpage=ceil($total/$this->limit);
        $offset=($this->page-1)*10;

        $query = "SELECT * from 工序进度表视图  where 1=1 ".$sql."
        order by 扫描时间  desc limit {$this->limit} offset {$offset} ";
        $res = $pdo->prepare($query);
        $res->execute();
        $count=$res->rowCount();
        $str='';
        if($count>0){    
        $str= '{"code":0,"msg":"OK","perpage":'.$perpage.',"count":'.$total.',"data":[';
        $i=1;
        while($row = $res->fetch(PDO::FETCH_ASSOC)){
            $i++;
            $str=$str.json_encode($row);
            if($i<$count+1){
            $str=$str.",";
            }
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
}
$sQRCode=new sQRCode();

$sQRCode->sQRCodeFun();

?>