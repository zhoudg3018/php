<?php
include('Conn\config.php');         // 引入配置文件
include('Library\function.php');    // 引入函数库
// 判断是否登录
if(!checkLogin()){
    msg(2,' 请先登录','login.php');
}
$订单流水号 = isset($_GET['id']) ? $_GET['id'] : 0;
$订单编号 = isset($_GET['ddbh']) ? $_GET['ddbh'] : 0;
$项目名称 = isset($_GET['xmmc']) ? $_GET['xmmc'] : '';

$query = "select * from 订单表 where 订单流水号 = '$订单流水号'";
$res = $pdo->prepare($query);
$res->execute();
$row1 = $res->fetch(PDO::FETCH_ASSOC);

$query = "select * from 详单表 where 订单流水号= '$订单流水号' ORDER BY cast(详单编号 AS INTEGER) ASC";
$res2 = $pdo->prepare($query);
$res2->execute();
    //$row2 = $res2->fetch(PDO::FETCH_ASSOC);
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
                        <SPAN>增加订单信息</SPAN>
                        <!--<input type="button" name="Submit" style="margin-right: 20px;float:right" onclick="javascript:history.back(-1);" value="返回">-->
                    </div>
                    <div class="panel-body">

                        <form id="add-dingdan"  method="post" class="col-md-11" style="padding-left: 10px" action="store_dingdan.php">
                    
                            <table>
                            
                               <tr>
                               <td><div class="form-group">
                                    <label for="订单流水号">订单流水号</label>
                                   <input type="text"  readonly="readonly" class="form-control" name="订单流水号" id="订单流水号" value="<?php echo $row1['订单流水号']?>">
                                </div>
                                </td>    
                               <td>
                               <div class="form-group">
                                    <label for="订单编号">订单编号</label>
                                    <?php  if(!empty($订单编号)){
                                    ?>
                                        <input type="text" readonly="readonly" class="form-control" id="订单编号"
                                        name="订单编号" value="<?php  
                                        if(empty($row1['订单编号'])) {
                                            echo $订单编号;}
                                        else {
                                            echo $row1['订单编号']; 
                                        }
                                     ?>">
                                     <?php 
                                    }else{
                                         ?>
                                         <input type="text" readonly="readonly"  class="form-control" id="订单编号"
                                        name="订单编号" value="<?php  
                                        if(empty($row1['订单编号'])) {
                                            echo $订单编号;}
                                        else {
                                            echo $row1['订单编号']; 
                                        } ?>">
                                        <?php
                                        }
                                         ?>
                                    </div>
                                </td>
                                <td>
                                <div class="form-group">
                                    <label for="经销商编号">经销商编号</label>
                                    <input type="text" class="form-control" id="经销商编号"
                                        name="经销商编号" value="<?php echo $row1['经销商编号'] ?>">
                                </div>
                                </td>
                                <td>
                                <div class="form-group">
                                    <label for="项目名称">项目名称</label>
                                    <input type="text" class="form-control" id="项目名称"
                                        name="项目名称" value="<?php 
                                         if(empty($row1['项目名称'])) {
                                            echo $项目名称;}
                                        else {
                                            echo $row1['项目名称']; 
                                        }
                                        ?>">
                                </div>
                                </td>
                                <td>
                                <div class="form-group">
                                    <label for="标签颜色">标签颜色选择</label>
                                    <input type="text" class="form-control" id="标签颜色"
                                        name="标签颜色" value="<?php  
                                        if(empty($row1['标签颜色'])) {
                                            echo $标签颜色;}
                                        else {
                                            echo $row1['标签颜色'];
                                            } ?>">
                                </div>
                                </td>
                                
                                <td>
                                <div class="form-group">
                                    <label for="标签打印状态">标签打印状态</label>
                                    <input type="text" class="form-control" id="标签打印状态"
                                        name="标签打印状态" value="<?php echo $row1['标签打印状态'] ?>">
                                </div>
                                </td>
                                <td>
                                <div class="form-group">
                                    <label for="打印日期">打印日期</label>
                                    <input type="text" class="form-control" id="打印日期"
                                        name="打印日期" value="<?php echo $row1['打印日期'] ?>">
                                </div>
                                </td>
                                <td>
                                <div class="form-group">
                                    <label for="订单增加人">订单增加人</label>
                                    <input type="text" class="form-control" id="订单增加人"
                                        name="订单增加人" value="<?php echo $row1['订单增加人'] ?>">
                                </div>
                                </td>
                                <td>
                                <div class="form-group">
                                        <label for="订单状态">订单状态</label>
                                        <select id="订单状态"  name="订单状态" class="form-control" >
                                        <option value ="录入">录入</option>
                                        <option value ="打印">打印</option>
                                        <option value="作废">作废</option>
                                        </select>
                                </div>
                                </td>
                                <?php
                                   if($_SESSION['user']['admin_user'] !='打印员'){
                                ?>
                                <td>
                                <div class="form-group">
                                    </br>
                                        <button type="submit" class="btn btn-primary">   保存</button>
                                    </div>
                                </td>
                                <?php
                                    }
                                 ?>
                                </tr>
                                </table>
                        </form>
                        </br>
                        </br>
                        </br>
                        <div>
                        <div align="center">
                          <?php
                          
                            if(!empty($row1['订单编号'] )){
                                if($_SESSION['user']['admin_user'] !='打印员'){
                          ?>
                           <form method="post" action="EXImport.php" enctype="multipart/form-data">
                                </br>
                                </br>
                                 <div >
                                 <input type="hidden" name="订单流水号" id="订单流水号" value="<?php echo $row1['订单流水号']?>">
                                 <input type="hidden" name="订单编号" id="订单编号" value="<?php echo $row1['订单编号']?>">
                                 <input type="hidden" name="项目名称" id="项目名称" value="<?php echo $row1['项目名称']?>">
                                 <input type="hidden" name="标签颜色" id="标签颜色" value="<?php echo $row1['标签颜色']?>">
                                 <input type="file" style="margin-left: 20px;float:left" name="file_stu" />
                                 <input type="submit" class="btn btn-primary" style="margin-left: 20px;float:left" value="导入" />
                                </div>
                           </form>
                             
                               <a href="delete_dd_sj.php?id=<?php echo $row1['订单流水号'] ?>&ddbh=<?php echo $row1['订单编号'] ?>" class="del">
                                <button class="btn btn-danger"  style="margin-right: 20px;float:right" type="button"><i class="glyphicon glyphicon-trash"></i>&nbsp;删除全部导入数据</button>
                               </a>
                            <?php
                                } 
                            ?>
                                <div class="form-group">
                                    <label style="margin-left: 20px;float:left" for="标签颜色">标签颜色</label>
                                    <input type="text" style="margin-left: 20px;float:left" id="bqys" name="bqys" value="">
                                    <a href="dyym.php?id=<?php echo $row1['订单流水号'] ?>" id ="dy" target="_blank" >
                                        <button type="submit"  id="dy_dd" class="btn btn-primary" style="margin-left: 20px;float:left" >打印</button>
                                    </a>
                                </div>
                            </div>
                           <div class="panel-body">
                                <!--用户存在情况-->
                             <table class="table table-bordered tb-hover" style="margin-bottom: 5px; ">
                                <thead>
                                <tr>
                                    <td>订单流水号</td>
                                    <td>订单编号 </td>
                                    <td>详单编号 </td>
                                    <td>项目名称 </td>
                                    <td>产品名称 </td>
                                    <td>尺寸规格 </td>
                                    <td>  位置  </td>
                                    <td>  开向  </td>
                                    <td>  颜色  </td>
                                    <td>  类型  </td>
                                    <td>  标签颜色 </td>
                                </tr>
                                </thead>
                                <tbody>
                                <?php 
                                     if($res2->rowCount()>0){
                                        while($row2 = $res2->fetch(PDO::FETCH_ASSOC)){
                                 ?>
                                    <tr>
                                        <td><?php echo $row2['订单流水号'] ?></td>
                                        <td><?php echo $row2['订单编号'] ?></td>
                                        <td><?php echo $row2['详单编号'] ?></td>
                                        <td><?php echo $row2['项目名称'] ?></td>
                                        <td><?php echo $row2['产品名称'] ?></td>
                                        <td><?php echo $row2['尺寸规格'] ?></td>
                                        <td><?php echo $row2['位置'] ?></td>
                                        <td><?php echo $row2['开向'] ?></td>
                                        <td><?php echo $row2['颜色'] ?></td>
                                        <td><?php echo $row2['类型'] ?></td>
                                        <td><?php echo $row2['标签颜色'] ?></td>
                                    </tr>
                                <?php 
                                        } 
                                    }
                                ?>
                                </tbody>
                                </table>
                            
                          </div>
                          </Div>
                          <?php 
                            }
                       
                             ?>
                    </div>
                </div>
            </div>
        </div>
        <!--右侧主区域结束-->
    </div>
    <!--主区域结束-->
</div>

<script>

  document.getElementById("dy_dd").onclick = function () {
    //根据id获取超链接,设置href属性
    var aObj = document.getElementById("dy");
    var bqys=document.getElementById("bqys").value;
    var ddlsh=document.getElementById("订单流水号").value;
    aObj.href = "dyym.php?id="+ddlsh+"&bqys="+bqys;
    //根据id获取超链接,设置文字内容
  };
 

    function checkField(id,message){
        if($(id).val() == ''){
            layer.tips(message, id, {time: 2000, tips: 2});
            $(id).focus();
            return false;
        }
        return true;
    }


</script>


<?php include('View\footer.html') ?>


