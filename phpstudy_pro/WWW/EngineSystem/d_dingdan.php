<?php
include('Conn\config.php');         // 引入配置文件
include('Library\function.php');    // 引入函数库
// 判断是否登录
if(!checkLogin()){
    msg(2,' 请先登录','login.php');
}

$订单编号  = $_POST['订单编号'];
$Bool=false;
if(empty($订单编号)){
    echo "<script>alert('订单编号不能为空');location='dingdan_add.php';</script>";//弹出对话框
    $Bool=true;
}else{
    $query = "select * from 订单表 WHERE 订单编号='$订单编号'";
    $res1 = $pdo->prepare($query);
    $res1->execute();
    if($res1->rowCount()>0){
        echo "<script>alert('订单编号已存在，请重新输入！');location='dingdan_add.php?ddbh=$订单编号';</script>";//弹出对话框
        $Bool=true;
    }
}
if($Bool==false){
    $DD_date = date("Ymd");
    $query = "select MAX(订单流水号) from 订单表 WHERE 订单流水号 like '$DD_date%'";
    $res = $pdo->prepare($query);
    $res->execute();
    $strArr = $res->fetch(PDO::FETCH_ASSOC);
    if(is_array($strArr ) && !empty($strArr['max'])){
    $订单流水号=(int)$strArr['max']+1;
    }else{
    $订单流水号=$DD_date.'001';
    }
    $query = "insert into 订单表 (订单流水号,订单编号) values('$订单流水号','$订单编号')";
    $res = $pdo->prepare($query);
    $res->execute();
    If($res->rowCount()>0){
    echo "<script>alert('操作成功');location='add_dingdan.php?id=$订单流水号&ddbh=$订单编号';</script>";//弹出对话框
    // msg(2,' 操作成功',"add_dingdan.php?id=$订单流水号");
    
    }else{
    
        echo "<script>alert('操作失败');location='dingdan_add.php?&ddbh=$订单编号';</script>";//弹出对话框
    }
}
?>



