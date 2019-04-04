<?php
require('function.php');

delog('#######');
delog('ダイレクトメッセージページ');
delogStart();

require('auth.php');
require('head.php');
require('header.php');

$msg = (!empty($_POST['msg'])) ? $_POST['msg'] : '';
$myId = $_SESSION['user_id'];
$otherId = $_GET['u_id'];

try{
  $dbh = dbConnect();
  $sql = 'SELECT * FROM dm_board WHERE my_id=:my_id AND other_id=:other_id OR my_id=:other_id AND other_id=:my_id';
  $data = array(':my_id'=>$myId,':other_id'=>$otherId);
  $stmt = queryPost($dbh,$sql,$data);
  $db_dm_boardData = $stmt->fetch(PDO::FETCH_ASSOC);
  $board_id = $db_dm_boardData['id'];

  if($stmt->rowCount()){
     delog('すでにdm_boardテーブルに登録されてます');
  }else{
    delog('dm_boardテーブルに登録されていません');
    $sql = 'INSERT into dm_board (my_id,other_id,create_date) VALUES (:my_id,:other_id,:create_date)';
    $data = array(':my_id'=>$myId,':other_id'=>$otherId,':create_date'=>date('Y-m-d H:i:s'));
    $stmt = queryPost($dbh,$sql,$data);
  }
}catch(Exception $e){
  error_log('エラー発生：'.$e->getMessage());
}

if(!empty($_POST)){
  delog('postされてるDMページ');
  delog($_POST);
  //$msg = $_POST['msg'];
  //$myId = $_SESSION['user_id'];
  //$otherId = $_GET['u_id'];
  $dbOtherData = getUserDataNeo($otherId);

  validMaxLen($msg,'msg');
  validEmpty($msg,'msg');

  if(empty($err_msg)){
    try{
      $dbh = dbConnect();
      $sql = 'INSERT into dm_msg (to_user,from_user,msg,create_date,board_id) VALUES (:to_user,:from_user,:msg,:create_date,:board_id)';
      $data = array(':to_user'=>$otherId,':from_user'=>$myId,':msg'=>$msg,':create_date'=>date('Y-m-d H:i:s'),':board_id'=>$db_dm_boardData['id']);
      $stmt = queryPost($dbh,$sql,$data);

      $db_dm_msgData = getDM($board_id);

    }catch(Exception $e){
      error_log('エラー発生：'.$e->getMessage());
    }
  }
}else{
  delog('postされてないDMページ');
  $dbOtherData = getUserDataNeo($otherId);
  $db_dm_msgData = getDM($board_id);
}

?>
<?php require('noticeMsg.php');?>


<!-- ここから修正 -->
<div class="l-container-wrapper">

  <?php require('sideMenu.php');?>

  <div class="l-main-container u-bg-beige">
    <form method="post" class="l-dm-form">
      <input class="c-input l-dm-form__input" name="msg" value="" placeholder="<?php echo $dbOtherData['name'];?>さんへメッセージを送る">
      <input type="submit" name="submit" value="送信" class="c-bt l-dm-form__btn">
    </form>
    <?php foreach ($db_dm_msgData as $key => $val): ?>
      <!-- メッセージ一覧表示 -->
      <div class="l-card-container u-flex-row-reverse">
        <div class="c-card <?php if($val['from_user'] == $myId) echo 'u-flex-grow-none'?>">
          <img class="c-card__img" src="<?php echo get($val['from_user'])['pic1'];?>">
          <div class="l-card-wrapper">
            <p class="c-card__msg">
              <span class="js-show-edit"><?php echo sanitize($val['msg']);?></span>
            </p>
            <div class="l-card-info-wrapper">
              <p class="c-card__info">
                <?php echo get($val['from_user'])['name'];?>  
                <?php echo sanitize($val['create_date']);?>
              </p>
            </div>
          </div>
        </div>
      </div>    
    <?php endforeach;?>
  </div>

</div>
<!-- ここまで修正 -->

 <?php require('footer.php'); ?>
