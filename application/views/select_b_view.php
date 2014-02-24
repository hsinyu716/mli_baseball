<script>
var self_ = "<?=site_url('main/select');?>";
var rand_ = "<?=site_url('main/result');?>";
</script>
<form id="fri_form" action="<?=site_url('main/result');?>" method="POST">
<input type="hidden" name="frie[]" value="0"/>
</form>

<div class="bg2" id="top" ng-app="myApp" ng-controller="selectController">
   <div class="inf"></div>
   	<div class="rbtn">
   		<div class="btn1"  ng-click="confirm(1);"></div>
  		 <div class="btn2" ng-click="confirm(0);"></div>
    </div>
</div>
