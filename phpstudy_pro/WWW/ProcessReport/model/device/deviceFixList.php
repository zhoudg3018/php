<?php
 header('Content-Type:application/json; charset=utf-8');
 include ("../conn/pdo_config.php");
 $DBDA=new DBDA();
$pdo=$DBDA->DBLink();
//获取变量
$type=isset($_POST["type"])?$_POST["type"]:''; 
$id=isset($_POST["id"])?$_POST["id"]:''; 
$data=isset($_POST["data"])?$_POST["data"]:''; 
$name=isset($_POST["name"])?$_POST["name"]:''; 
$sbbh=isset($_POST["sbbh"])?$_POST["sbbh"]:''; 
$qstr='';
if($name!=''){
    $qstr=$qstr." AND  设备名称  like '$name%'";
}
if( $sbbh!=''){
    $qstr=$qstr." AND  设备编号 = '$sbbh'";
  }

if($type=='SELECT_TB')
{
    $query = "SELECT *  FROM 设备资产维修记录表 
    WHERE 1=1 ".$qstr." ORDER BY id DESC;";
    echo GetData($pdo,$query);
}
else if($type=='DELETE'){
    $query = "DELETE FROM 设备资产维修记录表 WHERE id='$id'";
    echo DeleteData($pdo,$query);
    
}else if($type=='UPIN'){
    echo UpAndInsert($pdo,$data);
}
//获取初始值
function GetData($pdo,$query){
    $res = $pdo->prepare($query);
    $res->execute();
    $count=$res->rowCount();
    $i=1;
    $str= '{ "code": 0,"msg": "OK","data":[';
    if($count>0){ 
        while($row = $res->fetch(PDO::FETCH_ASSOC)){
            $i++;
            $str=$str.json_encode($row);
            if($i<$count+1){
                $str=$str. ",";
            }
        }   
    }
    $str=$str."]}";
    return $str;
}
//删除
function DeleteData($pdo,$query){
    $res = $pdo->prepare($query);
    $res->execute();
    $count=$res->rowCount();
    $str='';
    if($count>0){ 
        $str='{ "code": 0,"msg": "删除成功","data":[]}';
    }
    else{
        $str='{ "code": 0,"msg": "删除失败","data":[]}';
    }
    return $str;
}

function UpAndInsert($pdo,$data){
    $str= '{ "code": 0,"msg": "无数据更新","data":[]}';
    if(!empty($data) && count($data)>0){
        $res = $pdo->query('select max(id) from 设备资产维修记录表');
        $inId = $res->fetchColumn(0);
        if($inId == null){
            $inId=0;
        }
        try {  
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();
            for($i=0;$i <count($data); $i++){
                $row = $data[$i];
                $BH=sprintf("%04d", $row['设备编号']);
                if(!empty($row) && $row['flg']=='insert'){
                    $inId+=1;
                    /*$inSql="INSERT INTO 设备资产维修记录表 (id,flg,设备编号,维修单位,维修内容,维修时间,维修费用,维修单位负责人,联系电话,记录人,记录时间,备注,购买部件名称,
                            购买部件价格,报修人员, 维修人员,设备保养方法,保养日期,保养人员,接待人) VALUES ($inId,'','"
                            .$BH."','".$row['维修单位']."','".$row['维修内容']."','".$row['维修时间']."','".$row['维修费用']."',"."'".$row['维修单位负责人']."','"
                            .$row['联系电话']."','".$row['记录人']."','".$row['记录时间']."','".$row['备注']."',"."'".$row['购买部件名称']."','".$row['购买部件价格']."','"
                            .$row['报修人员']."','".$row['维修人员']."','".$row['设备保养方法']."','".$row['保养日期']."','".$row['保养人员']."','".$row['接待人']."');";
                    */
                    $inSql="INSERT INTO 设备资产维修记录表 (id,flg,设备编号,设备名称,规格,维修单位,维修内容,维修时间,维修费用,记录时间,备注,维修人员) VALUES ($inId,'','"
                            .$BH."','".$row['设备名称']."','".$row['规格']."','".$row['维修单位']."','".$row['维修内容']."','".$row['维修时间']."','".$row['维修费用']."','"
                            .$row['记录时间']."','".$row['备注']."','".$row['维修人员']."');";
                    


                    $res = $pdo->prepare($inSql);
                    $res->execute();
                }
                else if(!empty($row) && $row['flg']=='edit'){
                   /* $sql= "UPDATE 设备资产维修记录表 SET 设备编号='".$BH."', 维修单位='".$row['维修单位']."',维修内容='".$row['维修内容']."',维修时间='".$row['维修时间']."',"
                    ."维修单位负责人='".$row['维修单位负责人']."',联系电话='".$row['联系电话']."',记录人='".$row['记录人']."',记录时间='".$row['记录时间']."',备注='".$row['备注']."',"
                    ."购买部件名称='".$row['购买部件名称']."', 购买部件价格='".$row['购买部件价格']."',报修人员='".$row['报修人员']."',维修人员='".$row['维修人员']."',设备保养方法='"
                    .$row['设备保养方法']."',保养日期='".$row['保养日期']."',保养人员='".$row['保养人员']."',接待人='".$row['接待人']."'  WHERE 1=1 AND id='".$row['id']."';";
                    */
                    $sql= "UPDATE 设备资产维修记录表 SET 设备编号='".$BH."',设备名称='".$row['设备名称']."',规格='".$row['规格']."', 维修单位='".$row['维修单位']."',".
                    "维修内容='".$row['维修内容']."',维修时间='".$row['维修时间']."',记录时间='".$row['记录时间']."',备注='".$row['备注']."',维修人员='".$row['维修人员']."'"
                    ." WHERE 1=1 AND id='".$row['id']."';";
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
    return $str;
}
?>