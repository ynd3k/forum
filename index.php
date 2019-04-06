<?php

require('function.php');
delog('##################');
delog('トップページ!');
delogStart();

require('head.php');
require('header.php');

if(!empty($_POST)){
  //delog('post中身:'.print_r($_POST));

  $email = $_POST['email'];
  $pass = $_POST['pass'];
  $repass = $_POST['repass'];

  validEmpty($email,'email');
  validMaxLen($email,'email');
  validEmail($email,'email');
  validEmailDup($email,'email');

  validEmpty($pass,'pass');
  validMinLen($pass,'pass');
  validMaxLen($pass,'pass');
  validHalf($pass,'pass');

  validEmpty($repass,'repass');
  validMatch($pass,$repass,'repass');

  if(empty($err_msg)){
    delog('バリデーションおｋです');
    try{
      $dbh = dbConnect();
      $sql = 'INSERT INTO users (name,email,pass,create_date,zip,pic1) VALUES (:name,:email,:pass,:create_date,:zip,:pic1)';
      $data = array(':name'=>'',':email'=>$email,':pass'=>password_hash($pass,PASSWORD_DEFAULT),':create_date'=>date('Y-m-d H:i:s'),':zip'=>0,':pic1'=>'');
      $stmt = queryPost($dbh,$sql,$data);

      //=====================
      //セッションにログイン時間とタイムリミットとユーザIDを保存
      //=====================
      $sessionLimit = 60 * 60;
      $_SESSION['login_date'] = time();
      $_SESSION['login_limit'] = $sessionLimit;
      $_SESSION['user_id'] = $dbh->lastInsertId();
      $_SESSION['msg-success'] = 'ユーザー登録が完了しました';
      delog('新規登録したセッション変数の中身:'.print_r($_SESSION,true));

      header("Location:forum.php");
      exit;
    }catch(Exception $e){
      error_log('エラー発生：'.$e->getMessage());
    }
  }
}
?>

<?php require('noticeMsg.php');?>

<div class="l-signup-container">
  <form class="p-signup" method="post">
      
    <label>メールアドレス
      <span class="u-err-msg <?php if(!empty($err_msg['email'])) echo 'c-err-msg';?>"><?php if(!empty($err_msg['email'])) echo $err_msg['email'];?></span>
      <input type="text" name="email" class="c-input p-signup__form <?php if(!empty($err_msg['email'])) echo 'u-err-border';?>" value="<?php echo inputHold('email');?>">
    </label>
    
    <label>パスワード
      <span class="u-err-msg <?php if(!empty($err_msg['pass'])) echo 'c-err-msg';?>"><?php if(!empty($err_msg['pass'])) echo $err_msg['pass'];?></span>
      <input type="password" name="pass" class="c-input p-signup__form <?php if(!empty($err_msg['pass'])) echo 'u-err-border';?>" value="<?php echo inputHold('pass');?>">
    </label>
    <label>パスワード再入力
      <span class="u-err-msg <?php if(!empty($err_msg['repass'])) echo 'c-err-msg';?>"><?php if(!empty($err_msg['repass'])) echo $err_msg['repass'];?></span>
      <input type="password" name="repass" class="c-input p-signup__form <?php if(!empty($err_msg['repass'])) echo 'u-err-border';?>" value="<?php echo inputHold('repass');?>">
    </label>
    <input type="submit" class="c-bt p-signup__bt" value="登録する">
  </form>
</div>

    
<?php require('footer.php'); ?>
