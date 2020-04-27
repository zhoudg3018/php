<?php
    include('Conn\config.php');         // 引入配置文件
    include('Library\function.php');    // 引入函数库
    // 判断是否登录
    if(!checkLogin()){
        msg(2,' 请先登录','login.php');
    }
    $admin_id = $_GET['id']; // 获取用户id
    if(empty($admin_id)){
        msg(2,'非法操作','admin_user.php');
    }
    /** 执行删除操作 **/
    $query = 'delete from tb_admin where admin_id = :id';
    $res = $pdo->prepare($query);
    $res->bindParam(':id',$admin_id);
    $res->execute();
    if($res->rowCount()){
        msg(1,'删除成功','admin_user.php');
    }else{
        msg(2,'删除失败');
    }
?>
