<?php
    include('Conn\config.php');         // 引入配置文件
    include('Library\function.php');    // 引入函数库

    // 判断是否登录
    if(!checkLogin()){
        msg(2,' 请先登录','login.php');
    }
    $订单编号 = isset($_GET['ddbh']) ? $_GET['ddbh'] : ""; // 获取订单编号
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
                        <div class="row">
                            <!--<div class="col-sm-4"> -->

                            <div class="input-button">
                                <form method="post" action="d_dingdan.php">   
                                    <label for="订单编号" style="width: 100px;">订单编号</label> 
                                    <input type="text"  id="订单编号" name="订单编号" value="<?php echo $订单编号?>" >
                                    
                                        <button id="add-dd" class="btn btn-primary add" type="submit">
                                            <i class="glyphicon glyphicon-plus"></i>&nbsp;新增
                                </from>
                            </div>
                        </div> 
                    </div> 
                </div>    
            </div>
        </div>
        <!--右侧主区域结束-->
    </div>
    <!--主区域结束-->
</div>

<?php include('View\footer.html') ?>

