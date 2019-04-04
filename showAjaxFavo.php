<?php
require('function.php');
delog('#### showAjaxFavo  ####');
// echo $_POST['name']. $_POST['age']. '！！！';

$span = $_POST['listSpan'];
$currentMinNum = $_POST['currentMinNum'];

try{
    $dbh = dbConnect();
    $sql = 'SELECT * FROM message INNER JOIN favorite ON 
    favorite.msg_id=message.msg_id AND favorite.favo_user_id=:u_id ORDER BY message.create_date DESC';
    $data = array(':u_id'=>$_SESSION['user_id']);
    $stmt = queryPost($dbh,$sql,$data);
    $result['total_recode'] = $stmt->rowCount();
    $result['total_page'] = ceil($result['total_recode'] / $span);

    $sql .= ' LIMIT '.$span.' OFFSET '. $currentMinNum;
    $stmt = queryPost($dbh,$sql,$data);

    if($stmt){
        $result['data'] = $stmt->fetchAll();
        delog($result['data']);
        foreach($result['data'] as $key => $val):?>
            <div class="l-card-container">
                <div class="c-card">
                    <img class="c-card__img" src="<?php echo getNameAndPic1($val['user_id'])['pic1'];?>">
                    <div class="l-card-wrapper">
                        <p class="c-card__msg">
                            <i class="far fa-heart c-card__like js-click-favo <?php if(isFavo($_SESSION['user_id'],$val['msg_id'])) echo 'c-card__like--active';?>" data-messageid="<?php echo sanitize($val['msg_id']);?>"></i>
                            <span class="js-show-edit"><?php echo sanitize($val['msg']);?></span>
                        </p>
                        <p class="c-card__info">
                            <a href="directMsg.php?u_id=<?php echo $val['user_id'];?>" style="text-decoration-color:#5ac608;color:#47a500;">
                            <span ><?php echo getNameAndPic1($val['user_id'])['name'];?></span>
                            </a> <?php echo sanitize($val['create_date']);?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endforeach;
    }else{
        delog('しっぱい・・');
    }

}catch(Exception $e){
    error_log('エラー発生：'. $e->getMessage());
}
delog('## showAjaxFavo　終了 ##');

