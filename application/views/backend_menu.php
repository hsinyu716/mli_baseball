<!-- menu -->
<div id="nav" class="gmleft_tabs" align="left">
	<ul class="gmtoggle_tabs">
		<li <?php if($this->router->method=='user'){ echo 'class="selected"'; } ?>><a href="<?=site_url('backend/user');?>">參加者資料</a></li>				
		<li <?php if($this->router->method=='admin'){ echo 'class="selected"'; } ?>><a href="<?=site_url('backend/admin');?>">app設定</a></li>
		<li <?php if($this->router->method=='logout'){ echo 'class="selected"'; } ?>><a href="javascript:logout();void(0);">登出</a></li>				
	</ul>
</div>				
<div style='height:10px;'></div>
