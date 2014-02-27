<script src="js/multi/jquery.facebook.multifriend.select.js" type="text/javascript"></script>
<link rel="stylesheet" href="js/multi/jquery.facebook.multifriend.select.css" type="text/css" />
<link rel="stylesheet" href="js/multi/jquery.facebook.multifriend.select.custom.css" type="text/css" />

<link rel="stylesheet" href="css/select.css" type="text/css" />
<script>
var posi = <?= $position;?>;
var posi_title = <?= $posi_title;?>;
var posi_class = <?= $posi_class;?>;

var everAppended = false;
var pit = 0;
</script>

<form id="fri_form" action="<?=site_url('main/result');?>" method="POST">
<input type="hidden" name="frie[]" value="0"/>
<input type="hidden" name="frie[]" value="0"/>
<input type="hidden" name="frie[]" value="0"/>
<input type="hidden" name="frie[]" value="0"/>
<input type="hidden" name="frie[]" value="0"/>
<input type="hidden" name="frie[]" value="0"/>
<input type="hidden" name="frie[]" value="0"/>
<input type="hidden" name="frie[]" value="0"/>
<input type="hidden" name="frie[]" value="0"/>
</form>

<div id="top" ng-app="myApp" ng-controller="friendController">
	<div class="bg3">
	   <div class="inf"></div>
	   <div ng-repeat="p in posi" sn="{{ $index }}">
			<div class="{{p}}"  ng-click="select($index)"><div id="fri{{$index}}" class="fric"></div></div>
		</div>
		<div class="user1"><img src="//graph.facebook.com/<?=$fbid;?>/picture?width=55&height=55"/></div>
		<div class="btn3" ng-click="confirm(0);"></div>
	</div>
	
	<div id="friend_div2" class="radius" style="width:735px;height:500px;text-align:center;">
		<div id="jfmfs-container" style="height:450px;overflow:auto;">
		<?php foreach($friends as $fk=>$fv):?>
			<div class='jfmfs-friend' id='<?=$fv['uid'];?>' ng-click="selectf(<?=$fv['uid'];?>);"><img src="//graph.facebook.com/<?=$fv['uid'];?>/picture"/><div class='friend-name'><?=$fv['name'];?></div></div>
		<?php endforeach;?>
		</div>
		<button  style="width:100px;height:45px;" ng-click="conf_sel();">確定</button>
	</div>
</div>

