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
  <div id="hxNavbar"></div>
      <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
       <!-- <ul class="layui-nav layui-nav-tree"  lay-filter="test">
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
            <dd><a href="javascript:power;">权限设置</a></dd> 
          </dl>
        </li>
      </ul>
      -->

  <div class="layui-body">
   
  </div>
  
  <div class="layui-footer">
    <!-- 底部固定区域 -->
    <!--© layui.com - 底部固定区域-->
  </div>
</div>
<script src="./static/layui/layui.js"></script>
<script src="./static/layui/hxNav.js"></script>  
<script>
//JavaScript代码区域
layui.extend({
            hxNav: './static/layui/hxNav'  // 根据你自己的目录设置
        }).use(['element','hxNav'],function(){
          var element = layui.element;
          
            layui.hxNav({
                element: '#hxNavbar',        // 必须，指定ID
                url: './getMenuNavbar.php',  // 请求后台数据的接口
                type: 'post',
                shrink: false,
                onSelect: function(v) {
                    console.log(v);
                  //  layui.hxNav('select', id );
                }
            });
        });
</script>
</body>
</html>