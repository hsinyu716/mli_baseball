<script>
var fuser = <?= json_encode($fuser);?>;
var posturl = '<?= site_url('main/po_wall') ?>';
var setDataurl = '<?= site_url('main/setData') ?>';
var indexurl = '<?= site_url('main/index') ?>';
var shareurl = '<?= site_url('main/share') ?>';
var count = <?=$count;?>;

$(function(){
  $.each($('.ymsg'),function(){
    if($(this).text().length>0){
      $(this).css('visibility', 'visible');
    }
  });
    //_show($('.bg6'));
  bootbox.alert('點擊大頭照，看看誰是你的最佳戰友!');
});

var fbid = 0;
function fb_login_again(){
     FB.login(function(response) {
       if (response.authResponse) {
           fbid = response.authResponse.userID;
           $('#fbid').val(fbid);
            FB.getLoginStatus(powall);
       } else {
            fb_login_again()
       }
     },{scope: 'publish_stream'});
}
</script>

<link rel="stylesheet" href="css/result.css">
<link type="text/css" rel="stylesheet" href="css/popup.css">

<div ng-app="myApp" ng-controller="resultController">

<!-- 資料填寫 -->
<div class="bg6" id="data_" style="display:none;">
 <div class="mbtn" onclick="javascript:_show($('#data_'));"></div>
 <form id="data_form">
    <input type="text" class="name_in required" name="username" id="username" alt="姓名" placeholder="姓名"/>
    <input type="text" class="tel_in required" ng-model="tel" name="tel" id="tel" alt="電話" placeholder="電話" numbers-only="numbers-only" maxlength="15"/>
    <input type="email" class="mail_in required" name="email" id="email"  alt="email" placeholder="email"/>
    <input type="checkbox" id="agree" style="display:none;"/>
 </form>
    <div class="check" ng-click="checktrigger();"></div>
    <div class="check02" onclick="javascript:_show($('.info_box'));"></div>
    <div class="mbtn1" ng-click="submit_();" style="cursor:pointer;"></div>
</div>

<!-- 角色popup -->
<div class="bg" style="display:none;width:740px;">
 <div class="mbtn" onclick="javascript:_show($('.bg'));" style="left:670px;"></div>
    <div class="pic" style="background:url({{ pop_pic }});"></div>
    <div class="pic_name">{{ pop_pic_name }}</div>
    <div class="pic_role" style="background:url({{ pop_pic_role }});"></div>
    <div class="cont_role" style="background:url({{ pop_cont_role }});"></div>    
    <div class="rbtn3" ng-click="share();"></div>    
</div>

<!-- 場地圖 -->
<div class="bg4">
   <div class="inf"></div>
   <div class="tittle"><div class="t_name"><?=$user['name']?></div></div>
   <div ng-repeat="user in fusers" sn="{{ $index }}">
    <div class="{{user.class}}" ng-click="view_($index);"><div class="head"><img src="//graph.facebook.com/{{ user.uid }}/picture?width=64&height=64" width="64" height="64" /></div><div class="ymsg">{{user.message}}</div></div>
  </div>
  <div class="user1"><div class="head"><img src="//graph.facebook.com/<?=$fbid;?>/picture?width=66&height=66"/></div></div>
  <?if($count==0):?>
    <div class="btn4" ng-click="powall();"></div>
    <div class="btn5" ng-click="index();" style="display:none;"></div>
  <?php else:?>
    <div class="btn5" ng-click="index();"></div>
  <?php endif;?>
</div>

</div>