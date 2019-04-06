
<?php
  require('function.php');
  delog('#######');
  delog('パスワード変更');
  delogStart();

  require('auth.php');

  require('head.php');
  require('header.php');


  if(!empty($_POST)){
    delog('パス変更のPosT中身');
    delog($_POST);
    $pass_old = (!empty($_POST['pass-old'])) ? $_POST['pass-old'] : '';
    $pass_new = (!empty($_POST['pass-new'])) ? $_POST['pass-new'] : '';
    $pass_new_re = (!empty($_POST['pass-new-re'])) ? $_POST['pass-new-re'] : '';
    $dbUserData = getUserData($_SESSION['user_id']);

    validOldPassMatch($pass_old,'pass-old');
    validPassOldAndNew($pass_old,$pass_new,'pass-new');

    validPass($pass_new,'pass-new');
    validMatch($pass_new,$pass_new_re,'pass-new-re');


    if(empty($err_msg)){
      delog('ok');
      try{
        $dbh = dbConnect();
        $sql = 'UPDATE users SET pass=:pass WHERE id=:id';
        $data = array(':pass'=>password_hash($pass_new,PASSWORD_DEFAULT),':id'=>$_SESSION['user_id']);
        $stmt = queryPost($dbh,$sql,$data);

        if($stmt){
          $_SESSION['msg-success'] = 'パスワードの変更が完了しました';

//           $username = ($dbUserData[0]['name']) ? $dbUserData[0]['name'] : '名無し';
//           $from = '@.com';
//           $to = 'a';
//           $subject = 'パスワード変更通知';
//           $comment = <<<EOT
// {$username}　さん
// パスワードが変更されました。身に覚えがない場合は下記までお問い合わせください。

// //////////////////////
// URL http://testtest.c
// E-mail info@aaaa.com
// //////////////////////
// EOT;

//           sendMail($from,$to,$subject,$comment);

          header("Location:index.php");
          exit();
        }
      }catch(Exception $e){
        error_log('エラー発生：'.$e->getMessage());
      }
    }else{
      delog('no');
    }


  }

?>
<?php require('noticeMsg.php');?>

<div class="l-signup-container">

<form class="p-signup" method="post">
  <span class="u-err-msg <?php if(!empty($err_msg['common'])) echo 'c-err-msg-common';?>"><?php if(!empty($err_msg['common'])) echo $err_msg['common'];?></span>

  <label>現在のパスワード
      <span class="u-err-msg <?php if(!empty($err_msg['pass-old'])) echo 'c-err-msg';?>"><?php if(!empty($err_msg['pass-old'])) echo $err_msg['pass-old'];?></span>
      <input type="password" class="c-input p-signup__form <?php if(!empty($err_msg['pass-old'])) echo 'u-err-border';?>" name="pass-old" value="<?php echo inputHold('pass-old');?>" >
  </label>

  <label>新しいパスワード
      <span class="u-err-msg <?php if(!empty($err_msg['pass-new'])) echo 'c-err-msg';?>"><?php if(!empty($err_msg['pass-new'])) echo $err_msg['pass-new'];?></span>
      <input type="password" class="c-input p-signup__form <?php if(!empty($err_msg['pass-new'])) echo 'u-err-border';?>" name="pass-new" value="<?php echo inputHold('pass-new');?>" >
  </label>

  <label>新しいパスワード(確認)
      <span class="u-err-msg <?php if(!empty($err_msg['pass-new-re'])) echo 'c-err-msg';?>"><?php if(!empty($err_msg['pass-new-re'])) echo $err_msg['pass-new-re'];?></span>
      <input type="password" class="c-input p-signup__form <?php if(!empty($err_msg['pass-new-re'])) echo 'u-err-border';?>" name="pass-new-re" value="<?php echo inputHold('pass-new-re');?>" >
  </label>

  <input type="submit" class="c-bt p-signup__bt" value="変更">

</form>
</div>



<?php require('footer.php'); ?>
