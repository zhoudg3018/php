<?php
    //开启session
    session_start();
    //用户未登录
    if(!isset($_SESSION['username']) || empty($_SESSION['username'])||!isset($_SESSION['password'])||!isset($_SESSION['password']))
    {
    Header("Location:./view/login.html"); 
    }
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title>开洋木业生产系统</title>
  <link rel="stylesheet" href="./static/layui/css/layui.css" >
  <script src="./static/layui/jquery.js"></script>  
</head>
<body class="layui-layout-body">
<div class="layui-layout layui-layout-admin">
  <div class="layui-header">
    <div class="layui-logo">开洋木业</div>
    <!-- 头部区域（可配合layui已有的水平导航） -->
     <!-- 
    <ul class="layui-nav layui-layout-left">
  
        <li class="layui-nav-item"><a href="">控制台</a></li>
        <li class="layui-nav-item"><a href="">商品管理</a></li>
        <li class="layui-nav-item"><a href="">用户</a></li>
      
      <li class="layui-nav-item">
        <a href="javascript:;">其它系统</a>
        <dl class="layui-nav-child">
          <dd><a href="">邮件管理</a></dd>
          <dd><a href="">消息管理</a></dd>
          <dd><a href="">授权管理</a></dd>
        </dl>--
      </li>
    </ul>
      -->
    <ul class="layui-nav layui-layout-right">
      <li class="layui-nav-item">
        <a href="javascript:;">       
          <img src="http://t.cn/RCzsdCq" class="layui-nav-img">
          <?php
            session_start();
            echo $_SESSION['username'];
          ?>
        </a>
        <dl class="layui-nav-child">
           <!-- <dd><a href="./view/user/info.html" target="iframeMain" >基本资料</a></dd> -->
            <dd><a href="./view/user/password.html" target="iframeMain" >修改密码</a></dd>
        </dl>
      </li>
      <li layadmin-event="logout"  class="layui-nav-item"><a href="./view/login.html">退出</a></li>
    </ul>
  </div>
  
  <div class="layui-side layui-bg-black">
    <div class="layui-side-scroll">
      <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
      <ul class="layui-nav layui-nav-tree"  lay-filter="test">
        <li class="layui-nav-item layui-nav-itemed">
          <a class="" href="javascript:;">订单管理</a>
          <dl class="layui-nav-child">
            <dd><a href="./view/order.html" target="iframeMain" >订单查询</a></dd>
          </dl>
        </li>
        <li class="layui-nav-item">
          <a href="javascript:;">用户管理</a>
          <dl class="layui-nav-child">
            <dd><a href="./view/user/userlist.html" target="iframeMain">用户列表</a></dd>
            <!--<dd><a href="javascript:power;">权限设置</a></dd> -->
          </dl>
        </li>
      </ul>
    </div>
  </div>
  
  <div class="layui-body">
  <iframe id="iframeMain" name="iframeMain" src="./view/order.html"  style="width: 100%"; height="100%";>
   </iframe>
   
    <script>
    $(document).ready(function(){
       $("dd>a").click(function (e) {
            e.preventDefault();
            $("#iframeMain").attr("src",$(this).attr("href"));
        });
    });
    </script>
    
  </div>
  
  <div class="layui-footer">
    <!-- 底部固定区域 -->
    <!--© layui.com - 底部固定区域-->
  </div>
</div>
<script src="./static/layui/layui.js"></script>
<script>
//JavaScript代码区域
layui.use('element', function(){
  var element = layui.element;
  
});
</script>
</body>
</html>