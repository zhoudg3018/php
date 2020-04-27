<?php
    include('Conn\config.php');         // 引入配置文件
    include('Library\function.php');    // 引入函数库
    // 判断是否登录
    if(!checkLogin()){
        msg(2,' 请先登录','login.php');
    }
    /** 接收数据 **/
    $admin_user = $_POST['admin_user'];
    $admin_pass = md5($_POST['admin_pass']);            // 使用md5加密
    $admin_new_pass  = md5($_POST['admin_new_pass']);   // 使用md5加密
    $admin_new_pass2 = md5($_POST['admin_new_pass2']);  // 使用md5加密

    /** 后台验证数据 **/
    if(empty($admin_user))
    {
        msg(2, '请输入管理员名！');
    }
    if(empty($admin_pass))
    {
        msg(2, '请输入原始密码!');
    }
    if(empty($admin_new_pass))
    {
        msg(2, '请输入新密码!');
    }
    if($admin_new_pass == $admin_pass){
        msg(2,'新密码与原始密码不应相同');
    }
    if($admin_new_pass !== $admin_new_pass2 ){
        msg(2, '两次输入密码不一致!');
    }
    /** 判断原始密码是否正确 **/
    $query = "select * from tb_admin where admin_user = '$admin_user' and admin_pass = '$admin_pass'";
    $res = $pdo->prepare($query);
    $res->execute();
    $count = $res->fetchColumn();
    if(empty($count)){
        msg(2, '原始密码错误!','change_pwd.php');
    }
    // 更改tb_admin表
    $query = "update tb_admin set admin_pass = '$admin_new_pass' where admin_user ='$admin_user' ";
    $update = $pdo->prepare($query);
    $update->execute();
    if($update->rowCount()){
        msg(1,'操作成功','change_pwd.php');
    }else{
        msg(2,'操作失败','change_pwd.php');
    }

?>