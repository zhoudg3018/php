<?php
include ("../conn/pdo_config.php");
header("Content-Type:text/html;charset=utf-8");
Class sQRCode{
  public $type;
  public $DBDA;
  public $checkData;
  public $data;
  public $sfield;
   public function sQRCodeFun(){
    $this->type=isset($_POST["type"])?$_POST["type"]:''; 
    $this->DBDA=new DBDA();
    $this->checkData=isset($_POST["checkData"])?$_POST["checkData"]:''; 
    $this->data=isset($_POST["data"])?$_POST["data"]:''; 
    $this->sfield=isset($_POST["sfield"])?$_POST["sfield"]:''; 
    
   
    if($this->type=='SELECT_TB'){
        $this->SearchTableData();
    }
    else if($this->type=='SELECT_C'){
        $this->getContentData();
    }
     else if($this->type=='DELETE_ONE'){
        $this->OneDelete();
    }else if($this->type=='SAVE'){
       $this->saveAndEdit();
      }
      else if($this->type=='DELETE_T'){
        $this->delCheckData();
    }
   }
 public function saveAndEdit(){
    if($this->data !=''){
      $data=$this->data;
      $str= '{ "code": 0,"msg": "无数据更新","data":[]}';
      if(!empty($data) && count($data)>0){
        $pdo=$this->DBDA->DBLink();
          try {  
              $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              $pdo->beginTransaction();
              for($i=0;$i <count($data); $i++){ 
                  $row = $data[$i];
                  if(!empty($row) && $row['flg']=='add'){
                    $inSql = "INSERT INTO  品质检验记录表(产品名称,单位,检验数量,不良数量,不良内容描述,数量,日期,检验员,单号,责任工序,备注,序号) values("
                    ."'".$row['产品名称']."'"
                    .",'".$row['单位']."'"
                    .",'".$row['检验数量']."'"
                    .",'".$row['不良数量']."'"
                    .",'".$row['不良内容描述']."'"
                    .",'".$row['数量']."'"
                    .",'".$row['日期']."'"
                    .",'".$row['检验员']."'"
                    .",'".$row['单号']."'"
                    .",'".$row['责任工序']."'"
                    .",'".$row['备注']."'"
                    .",(SELECT CASE WHEN MAX(序号) IS NULL THEN 1 ELSE MAX(序号)+1 END FROM 品质检验记录表));";
                    $res = $pdo->prepare($inSql);
                    $res->execute();
                  }
                  else if(!empty($row) && $row['flg']=='edit'){
                    $sql = "UPDATE 品质检验记录表  SET 产品名称='".$row['产品名称']."'"
                    .",单位='".$row['单位']."'"
                    .",检验数量='".$row['检验数量']."'"
                    .",不良数量='".$row['不良数量']."'"
                    .",不良内容描述='".$row['不良内容描述']."'"
                    .",数量='".$row['数量']."'"
                    .",日期='".$row['日期']."'"
                    .",检验员='".$row['检验员']."'"
                    .",单号='".$row['单号']."'"
                    .",责任工序='".$row['责任工序']."'"
                    .",备注='".$row['备注']."'"
                    ." where 1=1 AND 序号='".$row['序号']."'";
                      $res = $pdo->prepare($sql);
                      $res->execute();
                  }
              }
              $pdo->commit();
              $str ='{ "code": 0,"msg": "保存成功","data":[]}';
          } catch (Exception $e) {
              $pdo->rollBack();
              $msg= "保存失败: " . $e->getMessage();
              $str ='{ "code": 0,"msg": "'.$msg.'","data":[]}';
          }    
      }
      echo $str;
    }
  }
   function delCheckData(){
    $pdo=$this->DBDA->DBLink();
    $query='';
    for($i = 0; $i < count($this->checkData); $i++) {
     if($i==0){
       $query="'".$this->checkData[$i]['序号']."'";
     }else{
       $query=$query.",'".$this->checkData[$i]['序号']."'";
     }
    }
    if(!empty($query)){
       //更新
       $query = "DELETE FROM 品质检验记录表  where 序号 IN ($query) ";
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
      $ID= $this->data['序号'];
    if( $ID !=''){
      $query = "DELETE FROM 品质检验记录表  where 序号 = '$ID'";
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
 public function getContentData(){
    $pdo=$this->DBDA->DBLink();
    $query = "SELECT  内容 AS name,键 AS value FROM  不良内容表 where 内容 iS not NULL AND  内容<>''";
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
        if($this->sfield !=''){
          $d_no=$this->sfield['d_no'];
          $startTime=$this->sfield['startTime'];
          $endTime=$this->sfield['endTime'];
          $t_content=$this->sfield['t_content'];
          if(  $d_no!=''){
          $sql=$sql." AND  单号 like '$d_no%'";
          }
          if( $startTime !=''){
              $sql=$sql." AND  日期 >='$startTime'";
          }
          if( $endTime !=''){
            $sql=$sql." AND  日期 <='$endTime'";
          }
          //工序
          $gx_arr =explode(",", $t_content);
          if($this->t_content!=''&&!empty($gx_arr)){
              $gx='';
              for($i=0;$i <count($gx_arr); $i++){
                  $gx=$gx."'".$gx_arr[$i]."',";
                  if($i==(count($gx_arr)-1)){
                      $gx=substr($gx,0,strlen($gx)-1);
                  break;
                  }
              }
              $sql=$sql." AND  不良内容描述 in ($gx)";
          }
        }
        $query = "SELECT * from 品质检验记录表  where 1=1 ".$sql."
        order by 序号  desc;";

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