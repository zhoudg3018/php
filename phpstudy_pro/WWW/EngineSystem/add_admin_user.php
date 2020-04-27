<?php
include('Conn\config.php');         // 引入配置文件
include('Library\function.php');    // 引入函数库
// 判断是否登录
if(!checkLogin()){
    msg(2,' 请先登录','login.php');
}
$admin_id = isset($_GET['id']) ? $_GET['id'] : 0;
$admin_pass = isset($_GET['mm']) ? $_GET['mm'] : 0;
$admin_qpass=$admin_pass;
$query = 'select * from tb_admin where admin_id = '.$admin_id;
$res = $pdo->prepare($query);
$res->execute();
$row = $res->fetch(PDO::FETCH_ASSOC);
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
                        填写用户信息
                    </div>
                    <div class="panel-body">
                        <form id="add-admin—user" action="store_admin_user.php" method="post" class="col-md-6" style="padding-left: 10px">
                            <input type="hidden" name="admin_id" value="<?php echo $row['admin_id'] ?>">
                            <div class="form-group">
                                <label for="admin_user">账号</label>
                                <input type="text" class="form-control" id="admin_user"
                                       name="admin_user" value="<?php echo $row['admin_user'] ?>">
                            </div>
                            <div class="form-group">
                                <label for="admin_pass">密码</label>
                                <input type="password" class="form-control"  id="admin_pass" name="admin_pass" value="<?php echo $row['admin_pass'] ?>">
                            </div>
                            <div class="form-group">
                                <label for="admin_qpass">确认密码</label>
                                <input type="password"  class="form-control" id="admin_qpass"
                                       name="admin_qpass" value="<?php echo $row['admin_qpass'] ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">提交</button>
                            <!--<input type="button" class="btn btn-primary" name="Submit" style="margin-right: 20px;float:right" onclick="javascript:history.back(-1);" value="返回">
                            -->
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
        $('#add-admin—user').submit(function () {
            if(!checkField('#admin_user','请输入用户姓名!')){
                return false;
            }
            if(!checkField('#admin_pass','请输入密码')){
                return false;
            }
            if(!checkField('#admin_qpass','请输入确认密码')){
                return false;
            }

            var admin_pass = $('#admin_pass').val();
            var admin_qpass = $('#admin_qpass').val();
            if(admin_pass != admin_qpass){
                layer.tips('密码和确认密码不一致!', id, {time: 2000, tips: 2});
                $('#admin_qpass').focus();
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
   /*
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
    */
</script>

<?php include('View\footer.html') ?>


