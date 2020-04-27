<?php

//url type参数处理 1:操作成功 2:操作失败
$type = isset($_GET['type']) && in_array(intval($_GET['type']), array(1, 2)) ? intval($_GET['type']) : 1;

$title = $type == 1 ? '操作成功' : '操作失败';

$msg = isset($_GET['msg']) ? trim($_GET['msg']) : '操作成功';

$url = isset($_GET['url']) ? trim($_GET['url']) : '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title ?></title>
    <link rel="stylesheet" type="text/css" href="Public/css/done.css"/>
</head>
<body>

<div class="content">
    <div class="center">
        <div class="image_center">
            <?php if($type == 1): ?>
                <span class="smile_face">:)</span>
            <?php else: ?>
                <span class="smile_face">:(</span>
            <?php endif; ?>

        </div>
        <div class="code">
            <?php echo $msg ?>
        </div>
        <div class="jump">
            页面在 <strong id="time" style="color: #009f95">3</strong> 秒 后跳转
        </div>
    </div>
</div>
</body>
<script src="Public/js/jquery.min.js"></script>
<script>
    $(function () {
        var time = 3;
        var url = "<?php echo $url?>" || null;//js读取php变量
        var interval = setInterval(function () {
                            if (time > 1) {
                                time--;
                                console.log(time);
                                $('#time').html(time);
                            }
                            else {
                                $('#time').html(0);
                                if (url) {
                                    location.href = url;
                                    clearInterval(interval); // 清除定时
                                } else {
                                    history.go(-1);
                                }
                            }
                        }, 1000);
    })
</script>
</html>
