<?php if (is_user_logged_in()) return false; ?>
<div id="red_login_popup" style="display:none;">
	<!-- SIGN IN DEFAULT -->
	<div id="prompt_sign_in_main"  class="loginpages">
		<div class="prompt_interior">
			<div class="prompt_sign_in_header">Log in to Opt in!</div>
			<div
				style="background: #f3f3f3; padding: 15px; text-align: center; -moz-border-radius: 5px; -webkit-border-radius: 5px">
				<?php do_action('facebook_connect'); ?>
			</div>

			<div
				style="margin-top: 15px; border-bottom: 2px solid #ededed; padding: 0 0 20px 30px;">
				<div style="float: left; width: 25px; padding: 3px 7px 0 7px">
					<img src="<?php bloginfo('template_url') ?>/images/shield.png">
				</div>
				<div
					style="float: left; width: 350px; line-height: 150%; color: #535353">
					We won't message your friends or send anything back to these sites
					on your behalf without your explicit permission.</div>
				<div style="clear: both"></div>
			</div>

			<div
				style="font-weight: bold; font-size: 1.2em; margin: 25px 0 15px 0; color: #2d2d2d">Want
				to sign in with a password?</div>
			<?php 
			echo redrokk_login_class::shortcode(array(
				'css' => false
			));
			?>
			<ul class="prompt_sign_in_options">
				<li><a href="#" class="registrationlink">Sign
						Up</a></li>
				<li>&#46;</li>
				<li><a href="#" class="forgotpwlink">Forgot Password</a></li>
				<div style="clear: both"></div>
			</ul>
			
			<div class="clear clearfix clearFix"></div>
		</div>
	</div>
	
	
	<!-- REGISTRATION -->
	<div id="registrationlink" class="loginpages" style="display: none">
		<div class="prompt_interior">
			<div class="prompt_sign_in_header">Sign Up With Email Address</div>

			<div
				style="background: #f3f3f3; padding: 25px 15px 25px 15px; text-align: center; -moz-border-radius: 5px; 
				-webkit-border-radius: 5px;">

				<?php 
				echo redrokk_login_class::shortcode(array(
					'css' => false,
					'action'=>'register'
				));
				?>

			</div>

			<div style="font-size: .95em; margin-top: 25px">
				<a href="#" class="backtosignin">
					Back to sign in</a>
			</div>
			<div class="clear clearfix clearFix"></div>
		</div>
	</div>
	
	
	<!-- FORGOT PASSWORD -->
	<div id="prompt_sign_in_reset" class="loginpages" style="display: none">
		<div class="prompt_interior">
			<h2 class="prompt_sign_in_header">Can't remember your password?</h2>

			<p style="text-align: left; margin-bottom: 25px">Enter your email
				address below and we'll send you instructions on how to reset your
				password.</p>

			<div
				style="background: #f3f3f3; padding: 25px 15px 25px 15px; text-align: center; -moz-border-radius: 5px; 
				-webkit-border-radius: 5px;">

				<?php 
				echo redrokk_login_class::shortcode(array(
					'css' => false,
					'action'=>'lostpassword'
				));
				?>

			</div>

			<div style="font-size: .95em; margin-top: 25px">
				<a href="#" class="backtosignin">
					Back to sign in</a>
			</div>
			<div class="clear clearfix clearFix"></div>
		</div>
	</div>
	
	
	<!-- CONFIRM EMAIL HAS BEEN SENT -->
	<div id="prompt_registration_confirm" class="loginpages" style="display: none">
		<div class="prompt_interior">

			<h2 class="prompt_sign_in_header">Please check your email</h2>
			<p>We've sent you a password and instructions on how to login. It may
				take a minute or two for the instructions to arrive so please be
				patient!</p>
			<div class="clear clearfix clearFix"></div>
		</div>
	</div>
	<div class="clear clearfix clearFix"></div>
	
</div>

<script type="text/javascript">
jQuery(function(){
	/* 
	For some reason the buttons on this popup can get picky
	jQuery('#wp-submit').click(function(){jQuery('#loginform').submit();});
	*/
	jQuery('.register-button').click(function(){jQuery('#registerform').submit();});

	/* dialog screen changes */
	jQuery('.forgotpwlink').click(function(){show_area('#prompt_sign_in_reset');});
	jQuery('.registrationlink').click(function(){show_area('#registrationlink');});
	jQuery('.backtosignin').click(function(){show_area('#prompt_sign_in_main');});
	
	/* initialize the login popup */
	jQuery('#red_login_popup').RedRokkLogin({
		redirect_to	: '<?php echo site_url() ?>',
		ajaxurl		: '<?php echo admin_url('admin-ajax.php') ?>',
		dialog 		: {
			dialogClass : 'red_white_popup'
		}
	});
});
</script>

