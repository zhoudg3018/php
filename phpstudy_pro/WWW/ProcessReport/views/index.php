﻿
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title>开洋木业生产系统</title>
  <link rel="stylesheet" href="../static/layui/css/layui.css" >
  <script src="../static/layui/layui.all.js"></script>
</head>
<body class="layui-layout-body">
<div class="layui-layout layui-layout-admin">
  <div class="layui-header">
    <div class="layui-logo">开洋木业</div>
     <ul id="right_meun" class="layui-nav layui-layout-right">
      <li class="layui-nav-item">
        <a href="javascript:;" id="admin_user">       
         <img src="http://t.cn/RCzsdCq" id class="layui-nav-img">
          
        </a>
        <dl class="layui-nav-child">
           <!-- <dd><a href="./view/user/info.html" target="iframeMain" >基本资料</a></dd> -->
            <dd><a href="./user/password.html" target="iframeMain" >修改密码</a></dd>
        </dl>
      </li>
      <li layadmin-event="logout"  class="layui-nav-item"><a id="Esc" >退出</a></li>
            <li class="layui-nav-item layadmin-flexible" lay-unselect>
            <a href="javascript:;" layadmin-event="flexible" title="侧边伸缩">
                <i class="layui-icon layui-icon-shrink-right" id="LAY_app_flexible"></i>
            </a>
        </li>
    </ul>
  </div>
  <div id="hxNavbar"></div>
  <div class="layui-body" >
  <iframe id="iframeMain" name="iframeMain" src="./home.html" style="border-width: 0px;vertical-align: top; width: 100%; height:100%;">
  </iframe>
  </div>
  
  <div class="layui-footer">
    <!-- 底部固定区域 -->
    <!--© layui.com - 底部固定区域-->
  </div>
</div>
<script src="../static/layui/layui.js"></script>
<script src="../static/modules/hxNav.js"></script> 
<script src="../static/modules/jquery.js"></script>
<!--<script src="../static/modules/check_token.js"></script>-->
<script>
//JavaScript代码区域
layui.extend({
            hxNav: '../static/modules/hxNav'  // 根据你自己的目录设置
        }).use(['element','hxNav','jquery','layer'],function(){
          var element = layui.element;
          var layer = layui.layer;
          var $ = layui.$;
          
         $(function () {
          if(window.sessionStorage.getItem("admin")===null){
                window.location.href="./user/login.html";
              }
              var username= window.sessionStorage.getItem('admin');
                $("#admin_user").html(username);
                $("#Esc").on("click",function(){
                  window.sessionStorage.clear();
                  window.location.href="./user/login.html";
                });
              
          });

          $(document).ready(function(){
              $("dd>a").click(function (e) {
                    e.preventDefault();
                    $("#iframeMain").attr("src",$(this).attr("href"));
                });
            });
            var loading=layer.load(1);
            
            layui.hxNav({
                element: '#hxNavbar',        // 必须，指定ID
                url: '../model/menu/getMenuNavbar.php',  // 请求后台数据的接口
                type: 'post',
                shrink: false,
                onSelect: function(v) {
                  console.log(v);
                  //  layui.hxNav('select', id );
                }
            });
            layer.close(loading);
               //左侧导航栏收缩功能动画效果
               var i=0;
               $('#LAY_app_flexible').click(function(){
                //这里定义一个全局变量来方便判断动画收缩的效果,也就是放在最外面
                if(i==0){
                  $(".layui-side").animate({width:'toggle'});
                  $(".layui-body").animate({left:'0px'});			
                  $(".layui-footer").animate({left:'0px'});
                  //$("#right_meun").hide();  
                  i++;
                }else{
                  $(".layui-side").animate({width:'toggle'});
                  $(".layui-body").animate({left:'200px'});
                  $(".layui-footer").animate({left:'200px'});
                  //$("#right_meun").show();  
                  i--;
                }		
              });
              //左侧导航栏收缩提示
              $('#LAY_app_flexible').hover(function(){
                layer.tips('收缩左侧导航栏', '#LAY_app_flexible', {tips:[4,'#FF8000'],time:0});
              },function(){
                layer.closeAll('tips'); 
              });
            
        });
</script>
</body>
</html>