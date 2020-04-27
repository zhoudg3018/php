<?php
 include ("pdo_config.php");
 header('Content-Type:text/html;charset=utf-8');
  $branchNo=isset($_GET["id"])?$_GET["id"]:1; 
  $query = "select * from 批次号表
  where 1=1 AND 批次号='$branchNo' ";
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
      <label class="layui-form-label">批次号</label>
      <div class="layui-input-inline">
        <input type="text" name="branchNo" id="branchNo" lay-verify="required" readonly="true" placeholder="" value="<?php  echo $row['批次号']?>" autocomplete="off" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">生产单号</label>
      <div class="layui-input-inline">
        <input type="text" name="produceNo"  id="produceNo" lay-verify="required" placeholder="请输入生产单号" value="<?php  echo $row['生产单号']?>" autocomplete="off" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">订单编号</label>
      <div class="layui-input-inline">
        <input type="text" name="detailsNo"  id="detailsNo" lay-verify="required" placeholder="请输入订单编号" value="<?php  echo $row['订单编号']?>" autocomplete="off" class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
          <div class="layui-input-block">
          <button class="layui-btn  layui-btn-submit"  id="btn1" name="btn1" >确认修改</button>
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
    $('#btn1').click( function () {
     layer.confirm( '确认修改数据吗?', function () {
        $.post( 'EditOrderUPdate.php', {
          branchNo:$("#branchNo").val(),
          produceNo: $("#produceNo").val(),
          detailsNo: $("#detailsNo").val()
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