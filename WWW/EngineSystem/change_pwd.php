<?php
include('Conn\config.php');         // 引入配置文件
include('Library\function.php');    // 引入函数库
// 判断是否登录
if(!checkLogin()){
    msg(2,' 请先登录','login.php');
}
?>

<?php include('View/header.html') ?>
<div class="container-fluid">
    <!--顶部导航-->
    <?php include('View/nav.html') ?>
    <!--主区域开始-->
    <div class="row" style="margin-top:70px">
        <!--左侧菜单-->
        <?php include('View/menu.html') ?>
        <!--右侧主区域开始-->
        <div class="main-right  col-md-10 col-md-offset-2">
            <div class="col-md-12">
                <div class="panel panel-default ">
                    <div class="panel-heading">
                        修改密码
                    </div>
                    <div class="panel-body">
                        <form id="password-form" action="store_pwd.php" method="post" class="col-md-6" style="padding-left: 10px">
                            <div class="form-group">
                                <label for="admin_user">管理员</label>
                                <input type="text" class="form-control" id="admin_user" name="admin_user">
                            </div>
                            <div class="form-group">
                                <label for="admin_pass">原始密码</label>
                                <input type="password" class="form-control" id="admin_pass" name="admin_pass" >
                            </div>
                            <div class="form-group">
                                <label for="admin_new_pass">新密码</label>
                                <input type="password" class="form-control" id="admin_new_pass" name="admin_new_pass" >
                            </div>
                            <div class="form-group">
                                <label for="admin_new_pass2">确认密码</label>
                                <input type="password" class="form-control" id="admin_new_pass2" name="admin_new_pass2" >
                            </div>
                            <button type="submit" class="btn btn-primary">提交</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--右侧主区域结束-->
    </div>
    <!--主区域结束-->
</div>

<script>
    $(function () {
        $('#password-form').submit(function () {
            if(!checkField('#admin_user','请输入管理员名!')){
                return false;
            }
            if(!checkField('#admin_pass','请输入原始密码')){
                return false;
            }
            if(!checkField('#admin_new_pass','请输入原始密码')){
                return false;
            }
            //判断新密码与原始密码是否相同
            if($('#admin_pass').val() == $('#admin_new_pass').val()) {
                layer.tips('新密码与原始密码不应相同', '#admin_new_pass', {time: 2000, tips: 2});
                $("#admin_new_pass").focus();
                return false;
            }
            // 判断两次输入新密码是否一致
            if($('#admin_new_pass').val() != $('#admin_new_pass2').val()) {
                layer.tips('两次输入密码不一致', '#admin_new_pass2', {time: 2000, tips: 2});
                $("#admin_new_pass2").focus();
                return false;
            }
            return true;
        })
    });

    function checkField(id,message){
        if($(id).val() == ''){
            layer.tips(message, id, {time: 2000, tips: 2});
            $(id).focus();
            return false;
        }
        return true;
    }

    function checkPhone(id,message){
        if(!checkField(id,message)){
            return false;
        }
        var tel = $(id).val();
        if(!(/^(\d{3}-)(\d{8})$|^(\d{4}-)(\d{7})$|^(\d{4}-)(\d{8})$|^(\d{11})$/.test(tel))){
            layer.tips('电话格式错误!', id, {time: 2000, tips: 2});
            $(id).focus();
            return false;
        }
        return true;
    }
</script>

<?php include('View\footer.html') ?>


