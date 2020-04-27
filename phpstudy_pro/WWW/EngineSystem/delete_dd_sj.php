<?php
    include('Conn\config.php');         // 引入配置文件
    include('Library\function.php');    // 引入函数库
    // 判断是否登录
    if(!checkLogin()){
        msg(2,' 请先登录','login.php');
    }
    $订单流水号 = isset($_GET['id']) ? $_GET['id'] : 0;
    $订单编号 = isset($_GET['ddbh']) ? $_GET['ddbh'] :'';
    if(empty($订单流水号)){
        msg(2,'非法操作',"add_dingdan.php?id=$订单流水号");
    }

    /** 执行删除操作 **/
    $query = 'delete from 详单表 where 订单流水号 = :id';
    $res = $pdo->prepare($query);
    $res->bindParam(':id',$订单流水号);
    $res->execute();
    if($res->rowCount()){
        msg(1,'删除成功',"add_dingdan.php?id=$订单流水号&ddbh=$订单编号");
    }else{
        msg(1,'删除失败或者无数据可删！',"add_dingdan.php?id=$订单流水号&ddbh=$订单编号");
    }
?>
