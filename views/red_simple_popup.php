<?php if (is_user_logged_in()) return false; ?>
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
<script type="application/javascript">
jQuery(function(){
	jQuery('#red_login_popup').RedRokkLogin({
		dialog : {
			dialogClass : 'red_simple_popup'
		}
	});
});
	
</script>