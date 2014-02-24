<script src="js/multi/jquery.facebook.multifriend.select.js" type="text/javascript"></script>
<link rel="stylesheet" href="js/multi/jquery.facebook.multifriend.select.css" type="text/css" />
<style type="text/css">
#jfmfs-container{
	background: #fff;
}

#jfmfs-friend-selector{
    height: 290px;
    overflow-y: hidden;
    width: 595px;
}

#jfmfs-friend-container{
    height: 255px;
}


.jfmfs-friend{
    width:119px;
}

.jfmfs-friends {                
    cursor:pointer;
    display:block;
    float:left;
    height:56px;
    margin:3px;
    padding:4px;
    width:120px;
    border: 1px solid #FFFFFF;
    -moz-border-radius: 5px; 
    -webkit-border-radius: 5px;
    -webkit-user-select:none;
    -moz-user-select:none;
    overflow:hidden;
}

.jfmfs-friends img {
    border: 1px solid #CCC;
    float:left;
    margin:0;
}

.jfmfs-friends.selected img {
    border: 1px solid #233E75;
}

.jfmfs-friends div {
    color:#111111;
    font-size:11px;
    overflow:hidden;
    padding:2px 0 0 6px;
}
#jfmfs-friend-selector input[type="text"]{
    margin-left:100px;
}
.selected {
    background-color: #ff7a00 !important;
    border: 1px solid #ff7a00 !important;
    
    background: #ff891e !important; /* for non-css3 browsers */

    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ea6f00', endColorstr='#ff8e28') !important; /* for IE */
    background: -webkit-gradient(linear, left top, left bottom, from(#ea6f00), to(#ff8e28)) !important; /* for webkit browsers */
    background: -moz-linear-gradient(top,  #ea6f00, #ff8e28) !important; /* for firefox 3.6+ */    
}
.selected.selected div{
color:#fff !important;
}

.sacker5{/*三壘*/
	float:left;
	width:92px;
	height:124px;
	top:278px;
	left:134px;
	position:absolute;
	cursor:pointer;
	}
.sacker6{/*二壘*/
	float:left;
	width:74px;
	height:129px;
	top: 144px;
	left: 363px;
	position:absolute;
	cursor:pointer;
	}
.sacker7{/*一壘*/
	float:left;
	width:74px;
	height:129px;
	top:257px;
	left:594px;
	position:absolute;
	cursor:pointer;
	}
.sacker2{/*左外*/
	float:left;
	width:80px;
	height: 105px;
	top: 166px;
	left:175px;
	position:absolute;
	cursor:pointer;
	}
.sacker4{/*右外*/
	float:left;
	width:74px;
	height:129px;
	top:140px;
	left:504px;
	position:absolute;
	cursor:pointer;
	}
.mascot3{/*吉洋物*/
	float:left;
	width:74px;
	height:119px;
	top:128px;
	left:666px;
	position:absolute;
	cursor:pointer;
	}
.mascot3{/*吉洋物*/
	float:left;
	width:74px;
	height:129px;
	top:125px;
	left:666px;
	position:absolute;
	cursor:pointer;
	}
.lala{/*啦啦隊*/
	float:left;
	width:151px;
	height:124px;
	top:408px;
	left:28px;
	position:absolute;
	cursor:pointer;
	}
.coach{/*安西教練*/
	float:left;
	width:75px;
	height:135px;
	top:435px;
	left:221px;
	position:absolute;
	cursor:pointer;
	}
.catch9{/*捕手*/
	float:left;
	width:100px;
	height:120px;
	top: 444px;
	left: 348px;
	position:absolute;
	cursor:pointer;
	}
.bg3>.user1{/*投手*/
	float:left;
	width:55px;
	height:55px;
	top:305px;
	left:365px;
	position:absolute;
	}
	
.fric{
	margin-left: 4px;
}

.fric img{
	width:65px;
	height:65px;
}

#fri0 img{
	margin-left: 11px !important;
	margin-top:4px;
}

#fri6{
	margin-left:43px !important;
	margin-top: 3px;
}

#fri8{
	margin-top: 5px;
}
</style>
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
	   <div ng-repeat="work in works" sn="{{ $index }}">
			<div class="{{work}}"  ng-click="select($index)"><div id="fri{{$index}}" class="fric"></div></div>
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

