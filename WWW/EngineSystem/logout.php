<?php
include('Library\function.php');
session_start();
//释放user
unset($_SESSION['user']);
msg(1, '退出登录成功!', 'login.php');
?>