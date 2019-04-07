<body class="l-body-wrapper">
  <header　class="l-header">
    <div class="l-top-container">
      <div class="l-top-container__left">
          <div class="p-top">
            <a href="index.php" class="p-top__logo">Forum</a>
            <div style="
              display: flex;
              font-size: 0.55em;
              align-items: center;
              "><div>テスト用アカウントで<a href="login.php" style="
              text-decoration-color: #fff;
              color: #fff;
              ">ログイン</a>できます</div>
            </div>
            <!-- <form class="p-top__form" method="get">
              <input type="text" name="search" class="p-top__search" placeholder="キーワードを入力">
            </form> -->
          </div>
      </div>

      <div class="l-top-container__right">
        <div class="p-top">
          <form action="signup.php">
            <input type="submit" class="c-bt p-top__bt" value="ユーザ登録">
          </form>
          <?php if( isLogin() ):?>
            <form action="logout.php">
              <input type="submit" class="c-bt p-top__bt" value="ログアウト">
            </form>
            <?php else:?>
            <form action="login.php">
              <input type="submit" class="c-bt p-top__bt" value="ログイン">
            </form>
          <?php endif;?>
          <div class="p-top__menu js-click-menu">
            <span class="p-top__menu__line"></span>
            <span class="p-top__menu__line"></span>
            <span class="p-top__menu__line"></span>
            <!-- メニュー出す -->
            <div class="p-top__menu__slide u-d-none js-click-menu-show">
              <div class="p-top__menu__slide__item"><a href="index.php">投稿TOP</a></div>
              <div class="p-top__menu__slide__item"><a href="passEdit.php">パスワード変更</a></div>
              <div class="p-top__menu__slide__item"><a href="profEdit.php">プロフィール編集</a></div>
              <div class="p-top__menu__slide__item"><a href="signup.php">ユーザ登録</a></div>
              <div class="p-top__menu__slide__item"><a href="login.php">ログイン</a></div>
              <div class="p-top__menu__slide__item"><a href="logout.php">ログアウト</a></div>
            </div>
          </div>    
        </div>
      </div>
    </div>

    
  </header>
