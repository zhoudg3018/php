<?php
 header('Content-Type:application/json; charset=utf-8');
 include ("../conn/pdo_config.php");
//获取变量
$type=isset($_POST["type"])?$_POST["type"]:''; 
$id=isset($_POST["id"])?$_POST["id"]:''; 
$data=isset($_POST["data"])?$_POST["data"]:''; 
$name=isset($_POST["name"])?$_POST["name"]:''; 
$sbbh=isset($_POST["sbbh"])?$_POST["sbbh"]:''; 
$bigType=isset($_POST["bigType"])?$_POST["bigType"]:''; 
$qstr='';
if($name!=''){
    $qstr=$qstr." AND  名称  like '$name%'";
}
if( $sbbh!=''){
    $qstr=$qstr." AND  设备编号 = '$sbbh'";
  }
//大类
if( $bigType!=''){
    $bigs='';
    for($i=0;$i <count($bigType); $i++){
        $bigs=$bigs."'".$bigType[$i]."',";
        if($i==(count($bigType)-1)){
            $bigs=substr($bigs,0,strlen($bigs)-1);
         break;
        }
    }
    $qstr=$qstr." AND  大类 in ($bigs)";
}
//业务逻辑
if($type=='SELECT_TY'){
    $query = "SELECT *  FROM 设备资产大类表 ORDER BY 序号";
    echo GetData($pdo,$query);
}
else if($type=='SELECT_TB')
{
    $query = "SELECT *  FROM 设备资产表 
    WHERE 1=1 ".$qstr." ORDER BY id;";
    echo GetData($pdo,$query);
}
else if($type=='DELETE'){
    $query = "DELETE FROM 设备资产表 WHERE id='$id'";
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
        $res = $pdo->query('select max(id) from 设备资产表');
        $inId = $res->fetchColumn(0);
        if($inId == null){
            $inId=0;
        }
        try {  
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();
            for($i=0;$i <count($data); $i++){
                $row = $data[$i];
                if(!empty($row) && $row['flg']=='insert'){
                    $inId+=1;
                    $inSql="INSERT INTO 设备资产表 (id,flg,设备编号,大类,名称,规格,单位,数量,金额,出厂时间,生产厂家,购置时间,规定使用年限,备注,记录人,"
                        ."记录时间,购买价格,设备负责人,机器原值,机器净值,使用部门,品牌,生产能力,使用日期,固定资产转移单,附件,使用状态)VALUES ($inId,'',"
                    ."'".$row['设备编号']."','".$row['大类']."','".$row['名称']."','".$row['规格']."','".$row['单位']."',"
                    ."'".$row['数量']."','".$row['金额']."','".$row['出厂时间']."','".$row['生产厂家']."','".$row['购置时间']."',"
                    ."'".$row['规定使用年限']."','".$row['备注']."','".$row['记录人']."','".$row['记录时间']."','".$row['购买价格']."',"
                    ."'".$row['设备负责人']."','".$row['机器原值']."','".$row['机器净值']."','".$row['使用部门']."','".$row['品牌']."',"
                    ."'".$row['生产能力']."','".$row['使用日期']."','".$row['固定资产转移单']."','".$row['附件']."','".$row['使用状态']."');";
              
                    $res = $pdo->prepare($inSql);
                    $res->execute();
                }
                else if(!empty($row) && $row['flg']=='edit'){
                    $sql= "UPDATE 设备资产表 SET 设备编号='".$row['设备编号']."', 大类='".$row['大类']."',名称='".$row['名称']."',规格='".$row['规格']."',单位='".$row['单位']."',"
                    ."数量='".$row['数量']."',金额='".$row['金额']."',出厂时间='".$row['出厂时间']."',生产厂家='".$row['生产厂家']."',购置时间='".$row['购置时间']."',"
                    ." 规定使用年限='".$row['规定使用年限']."',备注='".$row['备注']."',记录人='".$row['记录人']."',记录时间='".$row['记录时间']."',购买价格='".$row['购买价格']."',"
                    ."设备负责人='".$row['设备负责人']."',机器原值='".$row['机器原值']."', 机器净值='".$row['机器净值']."',使用部门='".$row['使用部门']."',品牌='".$row['品牌']."',"
                    ."生产能力='".$row['生产能力']."',使用日期='".$row['使用日期']."',固定资产转移单='".$row['固定资产转移单']."',附件='".$row['附件']."',使用状态='".$row['使用状态']."'"
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