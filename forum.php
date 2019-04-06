<?php
require('function.php');

delog('#######');
delog('掲示板ページ');
delogStart();

require('auth.php');
require('head.php');
require('header.php');



  if(!empty($_POST) && empty($_POST['userId'])){
    delog('post:ok');
    delog(print_r($_POST,true));

    $msg = $_POST['msg'];

    validEmpty($msg,'msg');
    validMaxLen($msg,'msg');

    if(empty($err_msg)){
      delog('メッセージバリデーションおｋです');

      try{
        $dbh = dbConnect();
        $sql = 'INSERT INTO message (msg,user_id,create_date,pic1) VALUES (:msg,:userid,:create_date,:pic1)';
        $data = array(':msg'=>$msg,':userid'=>$_SESSION['user_id'],':create_date'=>date('Y-m-d H:i:s'),':pic1'=>'');
        $stmt = queryPost($dbh,$sql,$data);


      }catch(Exception $e){
        error_log('エラー発生：'.$e->getMessage());
      }

    }
  }else{
    delog('post:none');
    delog(print_r($_POST,true));
  }

?>

<!-- ここから 修正-->
<?php
  $currentPageNum = (!empty($_GET['p'])) ? $_GET['p'] : 1;
  if(!preg_match('/^[0-9]+$/',$currentPageNum)){
    delog('GETパラメータに不正な値が入りました 掲示板トップに戻ります');
    header("Location:forum.php");
  }
  //表示件数
  $listSpan = 10;
  //現在の表示レコード先頭を算出（OFFSETの値）
  $currentMinNum = ($currentPageNum-1) * $listSpan;
  $tag = (isset($_GET['tag'])) ? $_GET['tag'] : '';
  $dbTagData = getTag();

  if(!empty($_GET['search'])){
    $search = $_GET['search'];delog('asss!');
    $dbMessageData = searchMessage($currentMinNum,$search,$listSpan);
  }else{
    $search = '';delog('as!');
    $dbMessageData = getMessageList($currentMinNum,$tag,$listSpan);
    $dbUserData = getUserDataNeo($_SESSION['user_id']);
  }
  //$currentPageNum 現在のページ数
  //$listSpan = 10; 表示件数
  $minPage = 0;//ページング表示項目のうち、最小のページ番号
  $maxPage = 0;//ページング表示項目のうち、最大でのページ番号
  $pageColNum = 5;//ページング表示項目数　最大で５こ
  $totalPageNum = $dbMessageData['total_page'];//総ページ数
  $page = paging($currentPageNum,$totalPageNum,$pageColNum); 
?>

<div class="l-container-wrapper">
  <!-- サイドメニューよみこみ -->
  <?php require('sideMenu.php');?>

  <!-- スマホ用サイドバーここに表示 -->
  <div class="l-side-container-sp">
    <div class="p-sidebar p-sidebar--active">
      <a href="forum.php"><i class="fas fa-desktop p-sidebar__icon"></i></a>
      <a class="p-sidebar__text p-sidebar__text--active" href="forum.php">トップ</a>
    </div>
    <div class="p-sidebar js-click-side-favo" data-cmn="<?php echo $currentMinNum;?>" data-listspan="<?php echo $listSpan;?>">
      <i class="far fa-heart p-sidebar__icon"></i>
      <a class="p-sidebar__text">お気に入り</a>
    </div>
    <div class="p-sidebar js-click-search">
      <i class="fas fa-search p-sidebar__icon"></i>
      <a class="p-sidebar__text">ワード検索</a>
    </div>
    <div class="u-d-none p-search-wrapper">
      <form method="get" class="p-search">
        <input type="text" class="c-input" name="search">
        <input type="submit" value="検索" class="c-bt">
      </form>
    </div>
    <div class="p-sidebar js-click-search-tag">
      <i class="fas fa-search p-sidebar__icon"></i>
      <a class="p-sidebar__text">タグ検索</a>
    </div>
    <div class="u-d-none p-search-tag-wrapper"><!-- -->
      <form method="get" class="u-d-flex">
        <select name="tag">
          <option value="0">タグを選ぶ</option>

          <?php foreach($dbTagData as $key => $val):?>
          <option value="<?php echo $val['id'];?>"><?php echo sanitize($val['name']);?></option>
          <?php endforeach;?>
        </select>
        <input type="submit" value="検索" class="c-bt">
      </form>
    </div>
  </div>
  <!-- スマホ用サイドバーここに表示end -->

  <div class="l-main-container">
    <div class="u-d-flex u-flex-between">
      <!-- ページネーション -->
      <div class="p-paging">
        <?php for($i=$page['min']; $i<=$page['max']; $i++):?>
          <div class="p-paging__item <?php if($currentPageNum == $i) echo 'p-paging__item--active';?>">
            <a  class="p-paging__item__num <?php if($currentPageNum == $i) echo 'p-paging__item__num--active';?>" 
                href="?p=<?php echo $i;
                              echo pagingLink('search');
                              echo pagingLink('tag');?>">
              <?php echo $i;?>
            </a>
          </div>
        <?php endfor;?>
      </div>
      <form method="post" class="p-msg-post">
        <input type="text" class="c-input p-msg-post__input" name="msg" placeholder="メッセージを入力してください。">
        <input type="submit" class="c-bt" value="送信">
      </form>
    </div>

    <div class="js-show-favo" style="margin-top:10px;">
      <?php foreach($dbMessageData['data'] as $key => $val):?>
      <!-- メッセージ一覧表示 -->
      <div class="l-card-container">
        <div class="c-card">
          <img class="c-card__img" src="<?php echo sanitize( getNameAndPic1($val['user_id'])['pic1'] );?>">
          <div class="l-card-wrapper">
            <p class="c-card__msg">
              <i class="far fa-heart c-card__like js-click-favo <?php if(isFavo($_SESSION['user_id'],$val['msg_id'])) echo 'c-card__like--active';?>" data-messageid="<?php echo sanitize($val['msg_id']);?>"></i>
              <span class="js-show-edit"><?php echo sanitize($val['msg']);?></span>
            </p>
            <div class="l-card-info-wrapper">
              <p class="c-card__info">
                <a href="directMsg.php?u_id=<?php echo sanitize($val['user_id']);?>" style="text-decoration-color:#5ac608;color:#47a500;">
                  <span><?php echo sanitize( getNameAndPic1($val['user_id'])['name'] );?></span>
                </a> 
                <span style="margin-left:10px;"><?php echo sanitize($val['create_date']);?></span>
              </p>
              <button class="c-bt c-card__edit c-card__edit--sp js-click-toggle-edit" data-messageid="<?php echo sanitize($val['msg_id']);?>">編集</button>
            </div>
          </div>
        </div>
        <!-- 編集画面 -->
        <form method="post" class="u-d-none js-toggle-edit p-form-edit">
          <input  name="msg_edit" class="c-input p-form-edit__input js-get-msg-edit" value="<?php echo sanitize($val['msg']);?>">
          <select class="js-get-edit-tag c-select-box" name="tag">
            <option value="0">タグを選択してください</option>
            <?php foreach ($dbTagData as $key => $value):?>
            <option value="<?php echo $value['id'];?>"
              <?php 
                if(!empty($_POST)){
                  // if($value['id'] == $postTag) echo 'selected';
                }else{
                  if($value['id'] == $val['tag_id']) echo 'selected';
                }
              ?>><?php echo $value['name'];?>
            </option>
            <?php endforeach;?>
          </select>
          <input type="submit" class="c-bt p-form-edit__btn js-click-edit" value="編集する"
                data-messageid="<?php echo sanitize($val['msg_id']);?>"
                data-msg="<?php echo sanitize($val['msg']);?>" >
        </form>
      </div>
      
      <?php endforeach;?>
    </div>
    <form method="post" class="p-msg-post-md">
        <input type="text" class="c-input p-msg-post-md__input" name="msg" placeholder="メッセージを入力してください。">
        <input type="submit" class="c-bt p-msg-post-md__btn" value="送信">
      </form>
  </div><!-- l-main-container終わり -->

</div>

<!-- ここまで 修正-->



<!-- <p id="js-show-msg" class="msg-slide"></p> -->
<?php require('noticeMsg.php');?>


<?php require('footer.php'); ?>
