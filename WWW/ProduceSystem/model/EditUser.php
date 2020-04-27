<?php
 include ("pdo_config.php");
 header('Content-Type:text/html;charset=utf-8');
  $username=isset($_GET["id"])?$_GET["id"]:1; 
  $query = "select * from 用户表
  where 1=1 AND 用户名='$username' ";
  $res = $pdo->prepare($query);
  $res->execute();
  $row = $res->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title></title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <link rel="stylesheet" href="../static/layui/css/layui.css" media="all">
</head>
<body>
<!--<form method="GET" action="EditOrderUPdate.php" > -->
  <div class="layui-form" lay-filter="layui-form-useradmin" id="layuiform-useradmin" style="padding: 20px 0 0 0;">
    <div class="layui-form-item">
      <label class="layui-form-label">用户名</label>
      <div class="layui-input-inline">
        <input type="text" name="username" id="username"  readyonly="true" lay-verify="required" readonly="true" placeholder="" value="<?php  echo $row['用户名']?>" autocomplete="off" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">姓名</label>
      <div class="layui-input-inline">
        <input type="text" name="name"  id="name" lay-verify="required" placeholder="请输入" value="<?php  echo $row['姓名']?>" autocomplete="off" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">密码</label>
      <div class="layui-input-inline">
        <input type="password" name="password"  id="password" lay-verify="required" placeholder="请输入" value="<?php  echo $row['密码']?>" autocomplete="off" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">用户类别</label>
      <div class="layui-input-inline">
        <input type="text" name="usertype"  id="usertype" lay-verify="required" placeholder="请输入" value="<?php  echo $row['用户类别']?>" autocomplete="off" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
          <div class="layui-input-block">
          <button class="layui-btn  layui-btn-submit"  id="btn—edit" name="btn—edit" >确认修改</button>
          </div>
     </div>
  </div>
<!--</from>-->
  <script src="../static/layui/layui.js" ></script>  
<script>
  layui.use([ 'layer','jquery'], function(){
  var layer = layui.layer //弹层
  var $ = layui.$;
  // 修改后保存
  $( function () {
    $('#btn—edit').click( function () {
     layer.confirm( '确认修改数据吗?', function () {
        $.post( 'EditUserUPdate.php', {
          username:$("#username").val(),
          name: $("#name").val(),
          usertype: $("#usertype").val(),
          password: $("#password").val()
        }, function ( res ) {
              layer.msg( res );
        });
     });
    });
  });
});
</script>
</body>
</html>