<?php

require('function.php') ;
delog('#####');
delog('ログアウト');
delogStart();

session_unset();
$_SESSION['msg-success'] = 'ログアウトしました';
header("Location:login.php");
 ?>
