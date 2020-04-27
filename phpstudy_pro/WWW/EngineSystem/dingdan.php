<?php
    include('Conn\config.php');         // 引入配置文件
    include('Library\function.php');    // 引入函数库

    // 判断是否登录
    if(!checkLogin()){
        msg(2,' 请先登录','login.php');
    }
 
    $订单编号 = isset($_GET['订单编号']) ? $_GET['订单编号'] : ""; // 获取订单编号
    $经销商编号 = isset($_GET['经销商编号']) ? $_GET['经销商编号'] : ""; // 获取经销商编号
    $订单状态 = isset($_GET['订单状态']) ? $_GET['订单状态'] : ""; // 获取订单状态
    $标签打印状态 = isset($_GET['标签打印状态']) ? $_GET['标签打印状态'] : ""; // 获取标签打印状态
    $打印日期 = isset($_GET['打印日期']) ? $_GET['打印日期'] : ""; // 获取打印日期
    $订单增加人 = isset($_GET['订单增加人']) ? $_GET['订单增加人'] : ""; // 获取订单增加人
    $项目名称 = isset($_GET['项目名称']) ? $_GET['项目名称'] : ""; // 获取项目名称
    //检查page参数
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $page = max($page, 1); //把page与1对比 取中间最大值
    $pageSize = 10; //每页显示条数
    $show = 6; //按钮数量
    $offset = ($page - 1) * $pageSize;
    /** 分页 **/
 
    $query_str='';
    if( $订单编号!=''){
        $query_str=$query_str."AND 订单编号 like '$订单编号'";

    }
    if( $经销商编号!=''){
        $query_str=$query_str."AND  经销商编号 like '$经销商编号'";

    }
    if( $标签打印状态!=''){
        $query_str=$query_str."AND 标签打印状态 like '$标签打印状态'";

    }
    if( $打印日期!=''){
        $query_str=$query_str."AND 打印日期 like '$打印日期'";
    }
    if( $订单增加人!=''){
        $query_str=$query_str."AND 订单增加人 like '$订单增加人'";
    }
    if( $项目名称!=''){
        $query_str=$query_str."AND 项目名称 like '$项目名称'";
    }
    if( $订单状态!=''){
        $query_str=$query_str."AND 订单状态 like '$订单状态'";
    }
        
    $query_all = "select count(*) from 订单表
    where 1=1".$query_str;     
                 
    $result = $pdo->prepare($query_all);
    $result->execute();
    $total = $result->fetchColumn();
    $pages = pages($total,$page,$pageSize,$show); // 调用分页方法
    /** 筛选数据 **/
    $query = "select * from 订单表
              where 1=1".$query_str."
              order by 订单编号 desc limit {$pageSize} offset {$offset} ";
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
                            <!--搜索开始-->
                      
                                <!--<div class="col-md-3"> -->
                                    <!--<div class="input-group">-->
                               
                                <form method="GET" action="dingdan.php" > 
                                    <table>
                                        
                                        <td>
                                            
                                                <label for="订单编号" style="width: 100px;">订单编号</label>
                                                <input type="text"  class="yhmiput" id="订单编号" name="订单编号"  value="<?php echo $订单编号 ?>" >
                                            
                                        </td>
                                        <td>
                                            <div>
                                                <label for="经销商编号" style="width: 100px;">经销商编号</label>
                                                    <input type="text"  id="经销商编号" name="经销商编号" value="<?php echo $经销商编号 ?>" >
                                            </div> 
                                        </td>
                                        <td>
                                            <div>
                                                <label for="项目名称" style="width: 100px;">项目名称</label>
                                                    <input type="text"  id="项目名称" name="项目名称" value="<?php echo $项目名称 ?>" >
                                            </div> 
                                        </td>
                                        <td> 
                                            <div>
                                            <label for="标签打印状态" style="width: 100px;">标签打印状态</label> 
                                            <input type="text"  id="标签打印状态" name="标签打印状态" value="<?php echo $标签打印状态 ?>" >
                                            </div> 
                                        <td>
                                            <div>
                                                <label for="打印日期" style="width: 100px;">打印日期</label>
                                                <input type="text"  id="打印日期" name="打印日期" value="<?php echo $打印日期 ?>" >
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                            <label for="订单增加人" style="width: 100px;">订单增加人</label> <input type="text"  id="订单增加人" name="订单增加人"
                                                value="<?php echo $订单增加人 ?>" >
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                            <label for="订单状态" style="width: 100px;">订单状态</label>
                                                <select id="订单状态"  name="订单状态" class="yhmiput" style=" width:127px;height:28px">
                                                <option value ="录入">录入</option>
                                                <option value ="打印">打印</option>
                                                <option value="作废">作废</option>
                                                </select>
                                            </div> 
                                        </td>
                                        <td>
                                            <div>
                                            <span class="input-group-btn">
                                            <button class="btn btn-info" ><i class="glyphicon glyphicon-search"></i></button>
                                            </span>
                                            </div> 
                                        </td>
                                    </table>
                                </form>
                             </div>
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
                                 <td >订单流水号</td>
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
                                        <td ><?php echo $row['订单流水号'] ?></td>
                                        <td><?php echo $row['订单编号'] ?></td>
                                        <td><?php echo $row['经销商编号'] ?></td>
                                        <td><?php echo $row['项目名称'] ?></td>
                                        <td><?php echo $row['订单状态'] ?></td>
                                        <td><?php echo $row['标签打印状态'] ?></td>
                                        <td><?php echo $row['打印日期'] ?></td>
                                        <td><?php echo $row['订单增加人'] ?></td>
                                        <td class="col-md-3">
                                            <a href="add_dingdan.php?id=<?php echo $row['订单流水号']?>&ddbh=<?php echo $row['订单编号']?>" target="_blank" >
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

