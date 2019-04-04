<footer id="footer" class="site-width">
</footer>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script>
  $(function(){
    var $ftr = $('#footer');
    if( window.innerHeight > $ftr.offset().top + $ftr.outerHeight() ){
      $ftr.attr({'style':'position:fixed; top:' + (window.innerHeight - $ftr.outerHeight()) +'px;'});

    }


    var $favo = $('.js-click-favo') || null;

    //favoMsgId = $favo.data('messageid') || null;

    //if(favoMsgId !== undefined && favoMsgId !== null){
      $favo.on('click',function(){
        var $this = $(this);
        $.ajax({
          type: "POST",
          url: "ajaxFavo.php",
          data: {messageId : $this.data('messageid')}
        }).done(function(data){
          //console.log('ajax success');
          //console.log($this.data('messageid'));
          $this.toggleClass('c-card__like--active');
        }).fail(function(msg){
          //console.log('ajax error');
        });
      });
  //}

    var $side_favo = $('.js-click-side-favo');
    $side_favo.on('click',function(){
      var $this = $(this);
      $.ajax({
        url: 'showAjaxFavo.php',
        type: 'POST',
        data: {
          listSpan: $this.data('listspan'),
          currentMinNum: $this.data('cmn')
        },
      })
      .done(function(data){
        //for(var i in data){
          // console.log(data);
        //}
        $('.js-show-favo').html(data);
      })
      .fail(function(msg){
        console.log('failed..');
      });
    });

    $('.js-click-edit').on('click', function(e){
      e.preventDefault();
      var $this = $(this);
      var tag_val = $this.siblings('.js-get-edit-tag').val();
      var msg_val = $this.siblings('.js-get-msg-edit').val();
      $.ajax({
        url: 'msgEditAjax.php',
        type: 'POST',
        data: {
          editMsgId: $this.data('messageid'),
          editMsg: msg_val,
          tag: tag_val
        }
      })
      .done(function(data){
        $this.closest('form').siblings('.c-card').find('.js-show-edit').text(msg_val);
        $this.closest('.js-toggle-edit').slideToggle();
      })
      .fail(function(a,b,c){
        console.log('failed');
        console.log(a);
        console.log(b);
        console.log(c);

      });
    });



    var $jsShowMsg = $('.p-notification');
    var msg = $jsShowMsg.text();
    if(msg.replace(/^[\s　]+[\s　]+$/g,"").length){
      // $jsShowMsg.slideToggle('slow');
      // setTimeout(function(){ 
        $jsShowMsg.animate({left: '20px'},500); 
        setTimeout(function() {
          $jsShowMsg.animate({left: '-300px'},500); 
        }, 4000);
      // }, 3500);
    }


var $dropArea = $('.area-drop');
var $fileInput = $('.input-file');
$dropArea.on('dragover',function(e){
  e.stopPropagation();
  e.preventDefault();
  $(this).css('border','3px #ccc dashed');
});

$dropArea.on('dragleave',function(e){
  e.stopPropagation();
  e.preventDefault();
  $(this).css('border','none');
});


$fileInput.on('change', function(e){
      $dropArea.css('border', 'none');
      console.log(this.files[0]);
      var file = this.files[0],
          $img = $(this).siblings('.prev-img'),
          fileReader = new FileReader();


      fileReader.onload = function(event) {

        $img.attr('src', event.target.result).show();
      };

      fileReader.readAsDataURL(file);

    });



    var $height_wrapper = $('#forum-wrapper');
    var $height_sidebar = $('#forum-sidebar');

    $height_sidebar.height($height_wrapper.height());

    var $check_save = $('.js-click-check-save');;
    $check_save.on('click', function(){
      $(this).toggleClass('p-signup__save__icon--hide');
      $(this).siblings('i').toggleClass('p-signup__save__icon--hide');
    });

    $('.js-up-prof').on('change', function(e){
      var file = e.target.files[0];
      var blobUrl = window.URL.createObjectURL(file);
      console.log(blobUrl);
      $('.js-show-prof-img').attr('src', blobUrl);
    });

    var $sidebar = $('.p-sidebar');
    $sidebar.on('click',function(){
      $sidebar.each(function(){
        if($(this).hasClass('p-sidebar--active')){
          $(this).removeClass('p-sidebar--active');
          $(this).children('.p-sidebar__text').removeClass('p-sidebar__text--active');
        }
      });
      $this = $(this);
      $this.addClass('p-sidebar--active');
      $this.children('.p-sidebar__text').addClass('p-sidebar__text--active');
    });

    $('.js-click-search').on('click', function(){
      $(this).siblings('.p-search-wrapper').slideToggle(300);
    });
    $('.js-click-search-tag').on('click', function(){
      $(this).siblings('.p-search-tag-wrapper').slideToggle(300);
    });

    $('.js-click-toggle-edit, .js-show-edit').on('click',function(){
      // console.log('aa');
      $this = $(this);
      $this.closest('.c-card').siblings('.js-toggle-edit').slideToggle();
    });

    $('.js-click-menu').on('click',function(){
      $(this).find('.js-click-menu-show').toggleClass('u-d-none');
    });













  });
</script>
</body>
</html>
