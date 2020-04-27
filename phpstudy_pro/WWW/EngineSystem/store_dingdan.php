<?php
    include('Conn\config.php');         // 引入配置文件
    include('Library\function.php');    // 引入函数库
    // 判断是否登录
    if(!checkLogin()){
        msg(2,' 请先登录','login.php');
    }
    // 接收数据
    $订单流水号  = $_POST['订单流水号'];
  //  $订单流水号 = isset($_GET['id']) ? $_GET['id'] : 0;
    $订单编号   = $_POST['订单编号'];
    $经销商编号 = $_POST['经销商编号'];
    $订单状态  = $_POST['订单状态'];
    $标签打印状态 = $_POST['标签打印状态'];
    $标签颜色   = $_POST['标签颜色'];
    $打印日期   = $_POST['打印日期'];
    $订单增加人 = $_POST['订单增加人'];
    $项目名称  = $_POST['项目名称'];
    $Bool=false;
    if(empty($项目名称)){
        $Bool=true;
 
        echo "<script>alert('请输入项目名称！');location='add_dingdan.php?id=$订单流水号&ddbh=$订单编号&bqys=$标签颜色&xmmc=$项目名称';</script>";//弹出对话框
    }
 
    if(empty($标签颜色)){
        $Bool=true;
        echo "<script>alert('请输入标签颜色！');location='add_dingdan.php?id=$订单流水号&ddbh=$订单编号&bqys=$标签颜色&xmmc=$项目名称';</script>";//弹出对话框
    }
    if(  $Bool==false){
        // 编辑
        $query = "update 订单表 set 订单编号='$订单编号',
                                    经销商编号='$经销商编号',
                                    订单状态='$订单状态',
                                    标签打印状态='$标签打印状态',
                                    打印日期='$打印日期',
                                    订单增加人='$订单增加人',
                                    项目名称='$项目名称',
                                    标签颜色='$标签颜色'
                    where 订单流水号='$订单流水号'";
        $res = $pdo->prepare($query);
        $res->execute();
        if($res->rowCount()){
            echo "<script>alert('订单操作成功');location='add_dingdan.php?id=$订单流水号&ddbh=$订单编号&bqys=$标签颜色&xmmc=$项目名称';</script>";//弹出对话框
        }else{
            echo "<script>alert('订单操作失败');location='add_dingdan.php?id=$订单流水号&ddbh=$订单编号&bqys=$标签颜色&xmmc=$项目名称';</script>";//弹出对话框
        }
    }
    
?>