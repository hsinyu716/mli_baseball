
<script>
$(function(){
	_show($('#loading'));
	func = 'message';
	check_FB();
});

var o_fbid = '<?= $fbid;?>';
var msgurl = "<?= site_url('main/setMsg') ?>";
var indexurl = "<?=site_url('main/index');?>";
var resulturl = "<?=site_url('main/result2');?>/"+o_fbid;

function check_msg(){
	$.ajax({
        url: "<?= site_url('main/check_msg') ?>",
        cache: false,
        type: 'post',
        data:{
				'fbid':o_fbid,
				'tofbid':fbid
            },
        dataType:'json',
        beforeSend: function(html){
        },
        error: function(e){
            //alert("error:"+e.responseText);
        },
        success: function(res){
        	if(res.cnt>0 && res.data.message==null){
        		_show($('#loading'));
        		_show('.bg5');
        	}else if(res.cnt==0){
        		_show($('#loading'));
        	}else if(res.cnt>0 && res.data.message!=null){
        		location.href=resulturl;
        	}
        },
        complete:function(){
			func = 'select';
        }
     });
}
</script>

<style>
#message{
border:0;
background:none;
}
</style>

<div class="bg1">
   <div class="inf"></div>
   <a href="javascript:check_FB();"><div class="go"></div></a>
</div>

<div class="bg5" ng-app="myApp" ng-controller="msgController" style="display:none;">
    <div class="t_name"><?=$user['name'];?></div>
    <input type="text" value="" class="input" id="message" placeholder="字數在15字以內" maxlength="15"/>
    <div class="mbtn1" ng-click="submit_();"></div>
</div>