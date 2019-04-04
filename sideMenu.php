<div class="l-side-container">
    <div class="p-sidebar p-sidebar--active">
      <i class="fas fa-desktop p-sidebar__icon"></i>
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