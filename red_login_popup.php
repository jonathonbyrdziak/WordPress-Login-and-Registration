<div id="red_login_popup" style="display:none;">
	<div class="redleft">
		<?php 
		echo redrokk_login_class::shortcode(array( 
			'css' => false 
		));
		?>
	</div>
	<div class="redright">
		
	</div>
</div>

<style type="text/css">
.red_login_popup {
	z-index:10000!important;
	background: white;
	border: 1px solid #E5E5E5;
	-moz-box-shadow: rgba(200,200,200,0.7) 0 4px 10px -1px;
	-webkit-box-shadow: rgba(200, 200, 200, 0.7) 0 4px 10px -1px;
	box-shadow: rgba(200, 200, 200, 0.7) 0 4px 10px -1px;
	padding: 20px;
}
.red_login_popup .ui-dialog-titlebar {
	position: absolute;
	top: 0;
	right: 5px;
	font-size: 10px;
}
</style>