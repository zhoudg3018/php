<?php
    include('Conn\config.php');         // 引入配置文件
    include('Library\function.php');    // 引入函数库
    // 判断是否登录
    if(!checkLogin()){
        msg(2,' 请先登录','login.php');
    }
    // 获取查询条件
    $start = isset($_GET['start']) ? $_GET['start'] : "";
    $end   = isset($_GET['end']) ? $_GET['end'] : "";
    /** 分页设置 **/
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1; //检查page参数
    $pageSize = 5; //每页显示条数
    $show = 6; //按钮显示数量
    $offset = ($page - 1) * $pageSize; // 设置起点
    // 获取分页数据
    $query_all = "select count(*) from tb_car where car_road like '%$start%' and car_road like '%$end%' ";
    $result = $pdo->prepare($query_all);
    $result->execute();
    $total = $result->fetchColumn();
    $pages = pages($total,$page,$pageSize,$show); // 调用分页方法
    /** 筛选数据 **/
    $query = "select tb_car.*,tb_car_log.car_log from tb_car left join tb_car_log on tb_car_log.car_number = tb_car.car_number ";
    $query .= "where car_road like '%$start%' and car_road like '%$end%' order by id desc limit {$pageSize} offset {$offset} ";
    $res = $pdo->prepare($query);
    $res->execute();
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
        <?php header("Location:dingdan.php") ?>
        <!--右侧主区域结束-->
    </div>
    <!--主区域结束-->
</div>
<?php include('View\footer.html') ?>

