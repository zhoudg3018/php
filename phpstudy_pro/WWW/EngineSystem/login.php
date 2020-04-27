<?php
include('Library\function.php');
// 判断是否登录
if(checkLogin())
{
    msg(1,'您已登录','index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>开洋木业工程销售订单OMS</title>
    <link href="Public/css/login.css" rel="stylesheet" />
    <script src="Public/js/jquery.min.js"></script>
    <script src="Public/js/bootstrap.min.js"></script>
    <script src="Public/js/layer/layer.js"></script>
</head>
<body>
<div class="header">
    <!--
    <div class="logo f1">
        <img height="40px" src="Public/images/logo.jpg">
    </div>
    -->
</div>
<div class="content">
    <div class="center">
        <div class="center-login">
            <div class="login-banner" >
                <a href="http://47.96.226.38/#/login"><img style="width: 536px; height:540px" src="Public/images/login_banner.png" alt=""></a>
            </div>
            <div class="user-login">
                <div class="user-box">
                    <div class="user-title">
                        <p style="text-align: center">用户登录</p>
                    </div>
                    <form class="login-table" name="login" id="login-form"  method="post">
                        <div class="login-left form">
                            <label class="username">用户名</label>
                            <input type="text" class="yhmiput" name="admin_user" placeholder="Username" id="username">
                        </div>
                        <div class="login-right">
                            <label class="passwd">密码</label>
                            <input type="password" class="yhmiput" name="admin_pass" placeholder="Password" id="password">
                        </div>
                        <div class="login-btn">
                            <button id="login">登录</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="footer">
    <p>Copyright@2020开洋木业有限公司</p>
</div>

</body>

<script>
    $('#login').click(function(){
        // 验证数据是否为空
        var username = $('#username').val();
        var password = $('#password').val();
        if (username == '' || username.length <= 0) {
            layer.tips('用户名不能为空', '#username', {time: 2000, tips: 2});
            $('#username').focus();
            return false;
        }
        if (password == '' || password.length <= 0) {
            layer.tips('密码不能为空', '#password', {time: 2000, tips: 2});
            $('#password').focus();
            return false;
        }
        // ajax提交
        $.post('check_login.php', {admin_user:username,admin_pass:password}, function(res) {
            if(res){
                console.log(res);
                layer.msg('登录成功');
                window.location.href = "dingdan.php";
            }else{
                layer.msg('用户名和密码不匹配');
            }
        });
        return false;
    });
</script>
</html>