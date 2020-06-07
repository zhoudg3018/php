<?php
    include('Conn\config.php');         // 引入配置文件
    include('Library\function.php');    // 引入函数库
    // 判断是否登录
    if(!checkLogin()){
        msg(2,' 请先登录','login.php');
    }
    // 接收数据id
    $admin_id = $_POST['admin_id'];
    $admin_user = $_POST['admin_user'];
    $admin_pass  = base64_encode( $_POST['admin_pass']);
    $admin_qpass = base64_encode( $_POST['admin_qpass']);
    /** 后台验证数据 **/
    if(empty($admin_user))
    {
        msg(2, '请输入用户姓名！');
    }
    if(empty($admin_pass))
    {
        msg(2, '请输入密码！');
    }
    if(empty($admin_qpass))
    {
        msg(2, '请输入确认密码');
    }
    if($admin_pass != $admin_qpass){

        msg(2, '密码与确认密码不一致！');
    }
    if($admin_id ==''){
        /** 判断账户密码是否正确 **/
        $query = "select * from tb_admin where admin_user = :user limit 1";
        $res = $pdo->prepare($query);
        $res->execute(array(':user'=>$admin_user));
        $admin = $res->fetch(PDO::FETCH_ASSOC);
        if(is_array($admin) && !empty($admin)){
            msg(2, '该用户已存在！');
        }
    }
    /** 新增或编辑处理 **/
    if($admin_id){
        // 编辑
        $query = "update tb_admin set admin_user=:user, admin_pass=:pass,admin_qpass=:qpass
                  where admin_id=:id";
        $res = $pdo->prepare($query);
        $res->execute(array(':user'=>$admin_user,':pass'=>$admin_pass,':qpass'=>$admin_qpass,':id'=>$admin_id));
    }else{
        // 新增
        $query = "insert into tb_admin(admin_user,admin_pass,admin_qpass)values(:user,:pass,:qpass)";
        $res = $pdo->prepare($query);
        $res->execute(array(':user'=>$admin_user,':pass'=>$admin_pass,':qpass'=>$admin_qpass));
        $admin_id = $pdo->lastInsertId();
    }
     // 新增
    if($res->rowCount()){
        echo "<script>alert('操作成功');location='add_admin_user.php?id=$admin_id&mm=$admin_pass';</script>";//弹出对话框
    }else{
        echo "<script>alert('操作失败');location='add_admin_user.php?id=$admin_id&mm=$admin_pass';</script>";//弹出对话框
    }
?>