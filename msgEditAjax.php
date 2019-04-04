<?php
//メッセージを更新
//タグを更新
require('function.php');

if(!empty($_POST)){
    $msg_id = (!empty($_POST['editMsgId'])) ? $_POST['editMsgId'] : '';
    $p = 1;
    $search = '';
    $tag = 0;
    $msg = $_POST['editMsg'];
    $postTag = $_POST['tag'];
    $dbMsgData = getMsgOne($msg_id);
    $dbTag = $dbMsgData['tag_id'];
    $dbMsg = $dbMsgData['msg'];
    msgEdit($msg, $dbMsg, 'msg', $msg_id, $postTag, $dbTag, $p, $search, $tag);
}
?>





