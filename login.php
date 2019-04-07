<?php
  require('function.php');
  delog('#######');
  delog('ログイン画面★');
  delogStart();

  require('auth.php');

  require('head.php');
  require('header.php');




    if(!empty($_POST)){
      //delog('post中身:'.print_r($_POST,true));

      $email = $_POST['email'];
      $pass = $_POST['pass'];
      $login_save_flg = (!empty($_POST['login_save'])) ? true : false;

      //テスト用垢だけ
      if($email === 't' && $pass === 't'){

        try {
          $dbh = dbConnect();
          $sql = 'SELECT pass,id FROM users WHERE email=:email AND delete_flg=0';
          $data = array(':email'=>$email);
          $stmt = queryPost($dbh,$sql,$data);
          $result = $stmt->fetch(PDO::FETCH_ASSOC);

          delog('クエリ結果の中身:'.print_r($result,true));

          if(!empty($result) && password_verify($pass,array_shift($result))){
            delog('パスワードがマッチしました');

            $sessionLimit = 60*60;
            $_SESSION['login_date'] = time();
            $_SESSION['login_limit'] = ($login_save_flg) ? $sessionLimit*24*30 : $sessionLimit;
            $_SESSION['user_id'] = array_shift($result);
            $_SESSION['msg-success'] = 'ログインしました';

            delog('新規ログイン時のセッション変数：'.print_r($_SESSION,true));
            header("Location:index.php");
            exit;
          }else{
            delog('パスが不一致..');
            $err_msg['common'] = MSG12;
          }
        }catch(Exception $e){
          error_log('エラー発生：'.$e->getMessage());
        }
      }
      //テスト用垢だけend

      validEmpty($email,'email');
      validMaxLen($email,'email');
      validEmail($email,'email');

      validEmpty($pass,'pass');
      validMinLen($pass,'pass');
      validMaxLen($pass,'pass');
      validHalf($pass,'pass');

      if(empty($err_msg)){
        delog('バリデーションおｋです');

        try {
          $dbh = dbConnect();
          $sql = 'SELECT pass,id FROM users WHERE email=:email AND delete_flg=0';
          $data = array(':email'=>$email);
          $stmt = queryPost($dbh,$sql,$data);
          $result = $stmt->fetch(PDO::FETCH_ASSOC);

          delog('クエリ結果の中身:'.print_r($result,true));

          if(!empty($result) && password_verify($pass,array_shift($result))){
            delog('パスワードがマッチしました');

            $sessionLimit = 60*60;
            $_SESSION['login_date'] = time();
            $_SESSION['login_limit'] = ($login_save_flg) ? $sessionLimit*24*30 : $sessionLimit;
            $_SESSION['user_id'] = array_shift($result);
            $_SESSION['msg-success'] = 'ログインしました';

            delog('新規ログイン時のセッション変数：'.print_r($_SESSION,true));
            header("Location:index.php");
            exit;
          }else{
            delog('パスが不一致..');
            $err_msg['common'] = MSG12;
          }
        }catch(Exception $e){
          error_log('エラー発生：'.$e->getMessage());
        }
      }

    }else{
      delog('post無し'.print_r($_POST,true));
    }
?>
<?php require('noticeMsg.php');?>

<div class="l-signup-container">
  <form class="p-signup" method="post">
    <p style="line-height: 1.5;color: #fff">テスト用アカウント<br>
    メールアドレス：<br>
    <span style="color:#111;">t</span><br>
    パスワード:<br>
    <span style="color: #111;">t</span></p>
    <span class="u-err-msg <?php if(!empty($err_msg['common'])) echo 'c-err-msg-common';?>"><?php if(!empty($err_msg['common'])) echo $err_msg['common'];?></span>

    <label>メールアドレス
        <span class="u-err-msg <?php if(!empty($err_msg['email'])) echo 'c-err-msg';?>"><?php if(!empty($err_msg['email'])) echo $err_msg['email'];?></span>
        <input type="text" class="c-input p-signup__form <?php if(!empty($err_msg['email'])) echo 'u-err-border';?>" name="email" value="<?php echo inputHold('email');?>">
    </label>

    <label>パスワード
        <span class="u-err-msg <?php if(!empty($err_msg['pass'])) echo 'c-err-msg';?>"><?php if(!empty($err_msg['pass'])) echo $err_msg['pass'];?></span>
        <input type="password" class="c-input p-signup__form <?php if(!empty($err_msg['pass'])) echo 'u-err-border';?>" name="pass" value="<?php echo inputHold('pass');?>" >
    </label>

    <label>
        <div class="p-signup__save">
            <span class="p-signup__save__check">ログインを維持する</span>
            <input type="checkbox" name="login_save" class="p-signup__save__checkbox">
            <i class="far fa-square p-signup__save__icon js-click-check-save"></i>
            <i class="far fa-check-square p-signup__save__icon p-signup__save__icon--hide js-click-check-save"></i>
        </div>
    </label>
    <input type="submit" class="c-bt p-signup__bt" value="ログイン">

  </form>
</div>






  <?php require('footer.php'); ?>
