<?php
include('Conn\config.php');         // 引入配置文件
include('Library\function.php');    // 引入函数库
// 判断是否登录
if(!checkLogin()){
    msg(2,' 请先登录','login.php');
}
$订单号码 = isset($_GET['id']) ? $_GET['id'] : 0;
$query = 'select * from 详单表 where 订单号码 = '.$订单号码;
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
                        <div class="row">
                         
                            <!--搜索开始-->
                            <form action="dingdan.php" method="GET">
                                <div class="col-md-3">
                                    <div class="input-group">
                                            <div style="width: 300px;">
                                                <label for="订单编号" style="width: 100px;">订单编号</label>
                                                <input type="text"  class="yhmiput" id="订单编号" name="订单编号"  value="<?php echo $订单编号 ?>" >
                                            </div>
                                            <label for="经销商编号" style="width: 100px;">经销商编号</label> <input type="text"  id="经销商编号" name="经销商编号"
                                               value="<?php echo $经销商编号 ?>" >
                                            <div>
                                            <label for="订单状态" style="width: 100px;">订单状态</label> <input type="text"  id="订单状态" name="订单状态"
                                               value="<?php echo $订单状态 ?>" >
                                            </div>  <div>
                                            <label for="标签打印状态" style="width: 100px;">标签打印状态</label> <input type="text"  id="标签打印状态" name="标签打印状态"
                                               value="<?php echo $标签打印状态 ?>" >
                                            </div>  <div>
                                            <label for="打印日期" style="width: 100px;">打印日期</label> <input type="text"  id="打印日期" name="打印日期"
                                               value="<?php echo $打印日期 ?>" >
                                            </div>
                                            <div>
                                            <label for="订单增加人" style="width: 100px;">订单增加人</label> <input type="text"  id="订单增加人" name="订单增加人"
                                               value="<?php echo $订单增加人 ?>" >
                                            </div>
                                        <span class="input-group-btn">
                                            <button class="btn btn-info"><i class="glyphicon glyphicon-search"></i></button>
                                        </span>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="panel-body">
                        <!--路线不存在情况-->
                        <?php
                        if(empty($total)){
                            echo "您查找的的数据不存在!";
                        }else{
                            ?>
                            <!--用户存在情况-->
                            <table class="table table-bordered tb-hover" style="margin-bottom: 5px; ">
                                <thead>
                                <tr>
                                    <td>订单编号</td>
                                    <td>经销商编号</td>
                                    <td>项目名称</td>
                                    <td>订单状态</td>
                                    <td>标签打印状态</td>
                                    <td>打印日期</td>
                                    <td>订单增加人</td>
                                    <td class="text-center">操作</td>
                                </tr>
                                </thead>
                                <tbody>
                                <?php while($row = $res->fetch(PDO::FETCH_ASSOC)){ ?>
                                    <tr>
                                        <td><?php echo $row['订单编号'] ?></td>
                                        <td><?php echo $row['经销商编号'] ?></td>
                                        <td><?php echo $row['项目名称'] ?></td>
                                        <td><?php echo $row['订单状态'] ?></td>
                                        <td><?php echo $row['标签打印状态'] ?></td>
                                        <td><?php echo $row['打印日期'] ?></td>
                                        <td><?php echo $row['订单增加人'] ?></td>
                                        <td class="col-md-3">
                                            <a href="xiangdan.php?id=<?php echo $row['订单编号']?>">
                                                <button class="btn btn-info " type="button"><i class="glyphicon glyphicon-edit"></i>&nbsp;进入详单页面</button>
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        <?php } ?>
                    </div>
                    <!--分页-->
                    <div class="text-right" style="padding-right: 10px">
                        <?php echo $pages?>
                    </div>
                </div>
            </div>
        </div>
        <!--右侧主区域结束-->
    </div>
    <!--主区域结束-->
</div>



<?php include('View\footer.html') ?>


