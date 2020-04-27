<?php
    include('Conn\config.php');         // 引入配置文件
    include('Library\function.php');    // 引入函数库
    // 判断是否登录
    if(!checkLogin()){
        msg(2,' 请先登录','login.php');
    }
    $admin_user = isset($_GET['admin_user']) ? $_GET['admin_user'] : ""; // 获取客户名称
    //检查page参数
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $page = max($page, 1); //把page与1对比 取中间最大值
    $pageSize = 5; //每页显示条数
    $show = 6; //按钮数量
    $offset = ($page - 1) * $pageSize;
    /** 分页 **/
    $query_all = "select count(*) from tb_admin
                  where admin_user like '%$admin_user%' ";
    $result = $pdo->prepare($query_all);
    $result->execute();
    $total = $result->fetchColumn();
    $pages = pages($total,$page,$pageSize,$show); // 调用分页方法
    /** 筛选数据 **/
    $query = "select * from tb_admin
              where admin_user like '%$admin_user%' order by admin_id desc limit {$pageSize} offset {$offset} ";
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
        <div class="main-right  col-md-10 col-md-offset-2">
            <div class="col-md-12">
                <div class="panel panel-default ">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="input-button">
                                    <a href="add_admin_user.php">
                                        <button class="btn btn-primary add" type="button">
                                            <i class="glyphicon glyphicon-plus"></i>&nbsp;新增
                                        </button>
                                    </a>
                                </div>
                            </div>
                            <!--搜索开始-->
                            <form action="admin_user.php" method="GET">
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="admin_user" name="admin_user"
                                               value="<?php echo $admin_user ?>" placeholder="请输入账号">
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
                            echo "您查找的该用户不存在!";
                        }else{
                            ?>
                            <!--用户存在情况-->
                            <table class="table table-bordered tb-hover" style="margin-bottom: 5px; ">
                                <thead>
                                <tr>
                                    <td>用户姓名</td>
                                    <td>密码</td>
                                    <td class="text-center">操作</td>
                                </tr>
                                </thead>
                                <tbody>
                                <?php while($row = $res->fetch(PDO::FETCH_ASSOC)){ ?>
                                    <tr>
                                        <td><?php echo $row['admin_user'] ?></td>
                                        <td><?php echo $row['admin_pass'] ?></td>
                                        <td class="col-md-3">
                                            <a href="add_admin_user.php?id=<?php echo $row['admin_id']?>">
                                                <button class="btn btn-info " type="button"><i class="glyphicon glyphicon-edit"></i>&nbsp;编辑</button>
                                            </a>
                                            <a href="delete_admin_user.php?id=<?php echo $row['admin_id']?>" class="del">
                                                <button class="btn btn-danger " type="button"><i class="glyphicon glyphicon-trash"></i>&nbsp;删除</button>
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

<script>
    $(function () {
        $('.del').on('click',function () {
            var url = $(this).attr('href');
            layer.confirm('确认删除该用户信息吗?',function(){
                window.location = url; // 页面跳转到删除页面
            });
            return false;
        })
    })
</script>
<?php include('View\footer.html') ?>

