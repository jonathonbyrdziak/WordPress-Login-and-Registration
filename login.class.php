<?php 
/**
 * @Author	Anonymous
 * @link http://www.redrokk.com
 * @Package Wordpress
 * @SubPackage RedRokk Library
 * @copyright  Copyright (C) 2011+ Redrokk Interactive Media
 * 
 * @version 0.2
 */
defined('ABSPATH') or die('You\'re not supposed to be here.');

/**
 * Actions and Filters
 *
 * Register any and all actions here. Nothing should actually be called
 * directly, the entire system will be based on these actions and hooks.
 */
add_action('plugins_loaded', 'redrokk_login_class::getInstance', 1);

/**
 * Login Class
 * 
 * Too many times have I built one of these that required dependencies.
 * This time, this class is based on shortcode hooks alone and does not rely on
 * any other custom programmed file.
 * 
 * @author Jonathon Byrd
 */
if (!class_exists('redrokk_login_class')) :
class redrokk_login_class 
{
	/**
	 * Redirects the user to a specific url on login
	 * 
	 * @var string
	 */
	var $redirect;
	
	/**
	 * Whether or not to display the standard css
	 * 
	 * @var boolean
	 */
	var $css = true;
	
	/**
	 * variable contains the action type
	 * 
	 * @var string
	 */
	var $action = 'login';
	
	/**
	 * Variable contains any errors that may occur during the login process
	 * 
	 * @var WP_Error
	 */
	var $errors;
	
	/**
	 * Variable exists in order to pass messages from the initialization to the display
	 * 
	 * @var array
	 */
	var $notices = array();
	
	/**
	 * Variable contains the id of the frontend login page
	 * 
	 * @var integer
	 */
	var $login_id = false;
	
	/**
	 * Constructor.
	 *
	 */
	function __construct( $options = array() )
	{
		// initializing
		$this->setProperties($options);
		
		//initializing
		$this->action = isset($_REQUEST['action']) ? $_REQUEST['action'] : $this->action;
		
		if ( isset($_GET['key']) )
			$this->action = 'resetpass';
		
		// validate action so as to default to the login screen
		if ( !in_array($this->action, array('logoutconfirm', 'logout', 'lostpassword', 
		'retrievepassword', 'resetpass', 'rp', 'register', 'login','confirmation','checkemail'), true) 
		&& false === has_filter('login_form_' . $action) )
			$this->action = 'login';
		
		$this->login_id = get_option('redrokk_login_class::login_page_id', false);
		
		//hooks
		add_action('init', array($this, 'init'));
		add_filter('logout_url', array($this, 'logout_url'), 20, 2);
		add_filter('login_url', array($this, 'login_url'), 20, 2);
	}
	
	/**
	 * Method handles any redirects that may need to happen prior to showing the
	 * template
	 * 
	 */
	function init()
	{
		// initializing
		$http_post = (!empty($_REQUEST) 
				&& (isset($_REQUEST['user_login']) 
				|| isset($_REQUEST['pass1'])
				|| isset($_REQUEST['log'])
				|| isset($_REQUEST['user_email'])
			));
		$this->errors = new WP_Error();
		
		if (!$this->action) return;
		
		switch ($this->action) 
		{
			default: //needs to remain blank because it runs on every page
			break;
			case 'logoutconfirm':
				$this->notices[] = "You've been logged out.";
				break;
				
			case 'logout' :
				require_once ABSPATH.WPINC.'/pluggable.php';
				wp_logout();
				wp_redirect( $this->getLoginUrl('logoutconfirm') );
				exit();
			break;
			
			case 'lostpassword' :
			case 'retrievepassword' :
				if ( $http_post ) {
					$this->errors = $this->retrieve_password();
					if ( !is_wp_error($this->errors) ) {
						$redirect_to = !empty( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : $this->getLoginUrl('checkemail');
						wp_safe_redirect( $redirect_to );
						exit();
					}
				}
			break;
			
			case 'resetpass' :
			case 'rp' :
				$user = $this->check_password_reset_key($_GET['key'], $_GET['login']);
			
				if ( is_wp_error($user) ) {
					wp_redirect( $this->getLoginUrl('lostpassword&error=invalidkey') );
					exit;
				}
				$this->errors = '';
			
				if ( isset($_POST['pass1']) && $_POST['pass1'] != $_POST['pass2'] ) {
				} elseif ( isset($_POST['pass1']) && !empty($_POST['pass1']) ) {
					$this->reset_password($user, $_POST['pass1']);
					exit;
				}
				
				if ( isset($_POST['pass1']) && $_POST['pass1'] != $_POST['pass2'] ) {
					$this->errors = new WP_Error('password_reset_mismatch', __('The passwords do not match.'));
				}
				
				wp_enqueue_script('utils');
				wp_enqueue_script('user-profile');
			break;
			
			case 'register' :
				if ( is_multisite() ) {
					// Multisite uses wp-signup.php
					wp_redirect( apply_filters( 'wp_signup_location', site_url('wp-signup.php') ) );
					exit;
				}
				
				if ( !get_option('users_can_register') ) {
					return;
				}
			
				$user_login = '';
				$user_email = '';
				if ( $http_post ) {
					$user_login = $_POST['user_login'];
					$user_email = $_POST['user_email'];
					$this->errors = $this->register_new_user($user_login, $user_email);
					if ( !is_wp_error($this->errors) ) {
						$redirect_to = !empty( $_POST['redirect_to'] ) ? $_POST['redirect_to'] : add_query_arg('checkemail', 'registered', $this->getLoginUrl('lostpassword&error=invalidkey'));
						wp_safe_redirect( $redirect_to );
						exit();
					}
					elseif ($this->errors->get_error_messages()) { 
						$this->errors = $this->errors->get_error_messages();
					}
				}
			
			break;
			
			case 'login' :
				if (!$http_post) break;
				$secure_cookie = '';
				$interim_login = isset($_REQUEST['interim-login']);
			
				// If the user wants ssl but the session is not ssl, force a secure cookie.
				if ( !empty($_POST['log']) && !force_ssl_admin() ) {
					$user_name = sanitize_user($_POST['log']);
					if ( $user = get_user_by('login', $user_name) ) {
						if ( get_user_option('use_ssl', $user->ID) ) {
							$secure_cookie = true;
							force_ssl_admin(true);
						}
					}
				}
				
				if ( isset( $_REQUEST['redirect_to'] ) ) {
					$redirect_to = $_REQUEST['redirect_to'];
					// Redirect to https if user wants ssl
					if ( $secure_cookie && false !== strpos($redirect_to, 'wp-admin') )
						$redirect_to = preg_replace('|^http://|', 'https://', $redirect_to);
				} else {
					$this->redirect = apply_filters('redrokk_login_class::redirect', $this->redirect);
					$redirect_to = $this->redirect ?$this->redirect :site_url('/');
				}
				
				$reauth = empty($_REQUEST['reauth']) ? false : true;
				
				// If the user was redirected to a secure login form from a non-secure admin page, and secure login is required but secure admin is not, then don't use a secure
				// cookie and redirect back to the referring non-secure admin page.  This allows logins to always be POSTed over SSL while allowing the user to choose visiting
				// the admin via http or https.
				if ( !$secure_cookie && is_ssl() && force_ssl_login() && !force_ssl_admin() && ( 0 !== strpos($redirect_to, 'https') ) && ( 0 === strpos($redirect_to, 'http') ) )
					$secure_cookie = false;
				
				$user = wp_signon('', $secure_cookie);
				
				$redirect_to = apply_filters('login_redirect', $redirect_to, isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '', $user);
				
				if ( !is_wp_error($user) && !$reauth ) {
					if ( $interim_login ) {
						$message = '<p class="message">' . __('You have logged in successfully.') . '</p>';
						 ?>
						<script type="text/javascript">setTimeout( function(){window.close()}, 8000);</script>
						<p class="alignright">
						<input type="button" class="button-primary" value="<?php esc_attr_e('Close'); ?>" onclick="window.close()" /></p>
						</div></body></html>
						<?php return;
					}
					
					if ( ( empty( $redirect_to ) || $redirect_to == 'wp-admin/' || $redirect_to == admin_url() ) ) {
						
						// If the user doesn't belong to a blog, send them to user admin. If the user can't edit posts, send them to their profile.
						if ( is_multisite() && !get_active_blog_for_user($user->ID) && !is_super_admin( $user->ID ) )
							$redirect_to = user_admin_url();
						elseif ( is_multisite() && !$user->has_cap('read') )
							$redirect_to = get_dashboard_url( $user->ID );
						elseif ( !$user->has_cap('edit_posts') )
							$redirect_to = admin_url('profile.php');
					}
					wp_safe_redirect($redirect_to);
					exit();
				}
				
				$this->errors = $user;
				// Clear errors if loggedout is set.
				if ( !empty($_GET['loggedout']) || $reauth )
					$this->errors = new WP_Error();
				
				// If cookies are disabled we can't log in even with a valid user+pass
				if ( isset($_POST['testcookie']) && empty($_COOKIE[TEST_COOKIE]) )
					$this->errors->add('test_cookie', __("<strong>ERROR</strong>: Cookies are blocked or not supported by your browser. You must <a href='http://www.google.com/cookies.html'>enable cookies</a> to use WordPress."));
			
				// Some parts of this script use the main login form to display a message
				if		( isset($_GET['loggedout']) && TRUE == $_GET['loggedout'] )
					$this->errors->add('loggedout', __('You are now logged out.'), 'message');
				elseif	( isset($_GET['registration']) && 'disabled' == $_GET['registration'] )
					$this->errors->add('registerdisabled', __('User registration is currently not allowed.'));
				elseif	( isset($_GET['checkemail']) && 'confirm' == $_GET['checkemail'] )
					$this->errors->add('confirm', __('Check your e-mail for the confirmation link.'), 'message');
				elseif	( isset($_GET['checkemail']) && 'newpass' == $_GET['checkemail'] )
					$this->errors->add('newpass', __('Check your e-mail for your new password.'), 'message');
				elseif	( isset($_GET['checkemail']) && 'registered' == $_GET['checkemail'] )
					$this->errors->add('registered', __('Registration complete. Please check your e-mail.'), 'message');
				elseif	( $interim_login )
					$this->errors->add('expired', __('Your session has expired. Please log-in again.'), 'message');
			
				// Clear any stale cookies.
				if ( $reauth )
					wp_clear_auth_cookie();
			
			break;
		}
	}
	
	/**
	 * 
	 * @param unknown_type $args
	 * @return string
	 */
	function getDisplay( $args = array() )
	{
		ob_start();
			$this->display($args);
		return ob_get_clean();
	}
	
	/**
	 * Method handles the actual display of the login system
	 * 
	 */
	function display( $args = array() )
	{
		// intiailizing
		$a = wp_parse_args($args, array(
			'primary'	=> false,
			'action'	=> 'login',
			'css'		=> $this->css
		));
		$this->css = $a['css'];
		
		if ($a['primary']) {
			update_option('redrokk_login_class::login_page_id', get_queried_object_id());
			$this->login_id = get_queried_object_id();
		}
		
		$this->action = isset($args['action']) 
			? $args['action'] 
			: ($this->action
				? $this->action
				: $a['action']);
		
		// allow plugins to override the default actions, and to add extra actions if they want
		do_action( 'login_init' );
		do_action( 'login_form_' . $this->action );
		
		?><style type="text/css" media="all"><?php echo $this->css(); ?></style>
		<div id="login" class="login red_login">
		<?php  
		
		$http_post = ('POST' == $_SERVER['REQUEST_METHOD']);
		switch ($this->action) {
		case 'lostpassword' :
		case 'retrievepassword' :
		
			if ( isset($_GET['error']) && 'invalidkey' == $_GET['error'] ) $this->errors->add('invalidkey', __('Sorry, that key does not appear to be valid.'));
			$redirect_to = apply_filters( 'lostpassword_redirect', !empty( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '' );
		
			do_action('lost_password');
		
			$user_login = isset($_POST['user_login']) ? stripslashes($_POST['user_login']) : '';
		
			if (!empty($this->notices)) 
			{
				?><p class="login-notice notice">
					<?php echo implode('</p><p class="login-notice notice">', $this->notices); ?>
				</p><?php 
			}
			
			if (is_wp_error($this->errors) && !empty($this->errors->errors)){
				$this->errors = $this->errors->get_error_messages();
			}
			if (!is_wp_error($this->errors) && $this->errors) 
			{
				?><p class="login-error error">
					<?php echo implode('</p><p class="login-error error">', $this->errors); ?>
				</p><?php 
			} 
			
		?>
		<form name="lostpasswordform" id="lostpasswordform" method="post"
	 	action="<?php echo esc_url( $this->getLoginUrl('lostpassword') ); ?>">
			<p>
				<label for="user_login">
					<span class="login-label login-username-email">
						<?php _e('Username or E-mail:') ?>
					</span>
				</label>
				
				<input type="text" name="user_login" id="user_login" class="input red_login_input" value="<?php echo esc_attr($user_login); ?>" size="20" tabindex="10" />
			</p>
			
			<?php do_action('lostpassword_form'); ?>
			<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to ); ?>" class="red_login_hidden"/>
			
			<p class="submit">
				<input type="submit" name="wp-submit" id="wp-submit" class="button-primary red_login_button" value="<?php esc_attr_e('Get New Password'); ?>" tabindex="100" />
			</p>
		</form>
		
		<p id="nav">
		<a href="<?php echo esc_url( $this->getLoginUrl() ); ?>"><?php _e('Log in') ?></a>
		<?php if ( get_option( 'users_can_register' ) ) : ?>
		 | <a href="<?php echo esc_url( $this->getLoginUrl('register') ); ?>"><?php _e( 'Register' ); ?></a>
		<?php endif; ?>
		</p>
		
		<?php
		break;
		
		case 'resetpass' :
		case 'rp' :
			
			if (!empty($this->notices)) 
			{
				?><p class="login-notice notice">
					<?php echo implode('</p><p class="login-notice notice">', $this->notices); ?>
				</p><?php 
			}
			
			if (is_wp_error($this->errors) && !empty($this->errors->errors)){
				$this->errors = $this->errors->get_error_messages();
			}
			if (!is_wp_error($this->errors) && $this->errors) 
			{
			?><p class="login-error error">
					<?php echo implode('</p><p class="login-error error">', $this->errors); ?>
				</p><?php 
			} 
			
		?>
		<form name="resetpassform" id="resetpassform" action="<?php echo esc_url( $this->getLoginUrl('resetpass&key=' . urlencode( $_GET['key']) . '&login=' . urlencode( $_GET['login'] ), 'login_post' ) ); ?>" method="post">
			<input type="hidden" id="user_login" value="<?php echo esc_attr( $_GET['login'] ); ?>" autocomplete="off" />
		
			<p>
				<label for="pass1"><span class="login-label login-new-password"><?php _e('New password') ?></span></label>
				<input type="password" name="pass1" id="pass1" class="input red_login_input" size="20" value="" autocomplete="off" />
			</p>
			<p>
				<label for="pass2"><span class="login-label login-confirm-password"><?php _e('Confirm new password') ?></span></label>
				<input type="password" name="pass2" id="pass2" class="input red_login_input" size="20" value="" autocomplete="off" />
			</p>
		
			<div id="pass-strength-result" class="hide-if-no-js"><span class="login-label login-strength"><?php _e('Strength indicator'); ?></span></div>
			<p class="description indicator-hint"><?php _e('Hint: The password should be at least seven characters long. To make it stronger, use upper and lower case letters, numbers and symbols like ! " ? $ % ^ &amp; ).'); ?></p>
		
			<br class="clear" />
			<p class="submit">
				<input type="submit" name="wp-submit" id="wp-submit" class="button-primary red_login_button" value="<?php esc_attr_e('Reset Password'); ?>" tabindex="100" />
			</p>
		</form>
		
		<p id="nav">
		<a href="<?php echo esc_url( $this->getLoginUrl() ); ?>"><?php _e( 'Log in' ); ?></a>
		<?php if ( get_option( 'users_can_register' ) ) : ?>
		 | <a href="<?php echo esc_url( $this->getLoginUrl('register') ); ?>"><?php _e( 'Register' ); ?></a>
		<?php endif; ?>
		</p>
		
		<?php
		break;
		
		case 'register' :
			$redirect_to = apply_filters( 'registration_redirect', !empty( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : $this->getLoginUrl('confirmation') );
		
			if ( get_option('users_can_register') ) :
			
			if (!empty($this->notices)) 
			{
				?><p class="login-notice notice">
					<?php echo implode('</p><p class="login-notice notice">', $this->notices); ?>
				</p><?php 
			}
			
			if (is_wp_error($this->errors) && !empty($this->errors->errors)){
				$this->errors = $this->errors->get_error_messages();
			}
			if (!is_wp_error($this->errors) && $this->errors) 
			{
				?><p class="login-error error">
					<?php echo implode('</p><p class="login-error error">', $this->errors); ?>
				</p><?php 
			} 
			
			?>
		<form name="registerform" autocomplete="off" id="registerform" action="<?php echo esc_url( $this->getLoginUrl('register') ); ?>" method="post">
			<p>
				<label for="user_login">
					<span class="login-label login-username"><?php _e('Username') ?></span>
				</label>
				
				<input type="text" name="user_login" id="user_login" class="input red_login_input" size="20"value="<?php echo esc_attr(stripslashes($user_login)); ?>" tabindex="10" />
			</p>
			<p>
				<label for="user_email">
					<span class="login-label login-email"><?php _e('E-mail') ?></span>
				</label>
				
				<input type="email" name="user_email" id="user_email" class="input red_login_input" size="25" value="<?php echo esc_attr(stripslashes($user_email)); ?>" tabindex="20" />
			</p>
			<?php do_action('register_form'); ?>
			
			<p id="reg_passmail"><?php _e('A password will be e-mailed to you.') ?></p>
			<br class="clear" />
			
			<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to ); ?>" />
			<p class="submit">
				<input type="submit" name="wp-submit" id="wp-submit" class="button-primary register-button red_login_button" 
				value="<?php esc_attr_e('Register'); ?>" tabindex="100" />
			</p>
		</form>
		
		<p id="nav"> 
			<a class="login-login-link login-link-registration" href="<?php echo esc_url( $this->getLoginUrl() ); ?>"><?php _e( 'Log in' ); ?></a>
			
			<span class="login-spacer"> | </span>
			
			<a href="<?php echo esc_url( $this->getLoginUrl('lostpassword') ); ?>" class="login-lostpw-link" title="<?php esc_attr_e( 'Password Lost and Found' ) ?>">
				<?php _e( 'Lost your password?' ); ?></a>
		</p>
		
		<?php
			else:
		?>
			<p>Registrations are currently locked.</p>
		<?php
			endif;
		break;
		case 'confirmation':
			?>
				<p>Congratulations! Your registration has been completed. We've sent you a confirmation
				email which contains your password. Please locate the email to continue.</p>
			<?php
			break;
		
		case 'login' :
		case 'checkemail':
		default:
			$secure_cookie = $user_login = '';
			$interim_login = isset($_REQUEST['interim-login']);
			$reauth = empty($_REQUEST['reauth']) ? false : true;
			
			if ( isset( $_REQUEST['redirect_to'] ) ) {
				$redirect_to = $_REQUEST['redirect_to'];
				// Redirect to https if user wants ssl
				if ( $secure_cookie && false !== strpos($redirect_to, 'wp-admin') )
					$redirect_to = preg_replace('|^http://|', 'https://', $redirect_to);
			} else {
				$this->redirect = apply_filters('redrokk_login_class::redirect', $this->redirect);
				$redirect_to = $this->redirect ?$this->redirect :site_url('/');
			}
			$redirect_to = apply_filters('login_redirect', $redirect_to, isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '');
		
			if ( isset($_POST['log']) )
				$user_login = ( 'incorrect_password' == $this->errors->get_error_code() || 'empty_password' == $this->errors->get_error_code() ) ? esc_attr(stripslashes($_POST['log'])) : '';
			$rememberme = ! empty( $_POST['rememberme'] );
			
			if (!empty($this->notices)) {
				?><p class="login-notice notice">
					<?php echo implode('</p><p class="login-notice notice">', $this->notices); ?>
				</p><?php 
			}
			
			if (is_wp_error($this->errors) && !empty($this->errors->errors)){
				$this->errors = $this->errors->get_error_messages();
			}
			
			if (!is_wp_error($this->errors) && $this->errors) {
				?><p class="login-error error">
					<?php echo implode('</p><p class="login-error error">', $this->errors); ?>
				</p><?php 
			} 
			
			if ($this->action == 'checkemail') {
				?><p class="login-success success">
					Check your email! We've sent you an authentication link, please click the link in order to reset your password.
				</p><?php 
			}
			
			?>
		<form name="loginform" autocomplete="off" id="loginform" action="<?php echo esc_url( $this->getLoginUrl() ); ?>" method="post">
			<p>
				<label for="user_login">
					<span class="login-label login-username">
						<?php _e('Username') ?>
					</span>
				</label>
				
				<input type="text" name="log" id="user_login" class="input red_login_input" value="<?php echo esc_attr($user_login); ?>" size="20" tabindex="10" />
			</p>
			<p>
				<label for="user_pass">
					<span class="login-label login-password">
						<?php _e('Password') ?>
					</span>
				</label>
				
				<input type="password" name="pwd" id="user_pass" class="input red_login_input" value="" size="20" tabindex="20" />
			</p>
			<?php do_action('login_form'); ?>
			
			<p class="forgetmenot">
				<label for="rememberme">
					<input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="90" <?php checked( $rememberme ); ?> class="red_login_checkbox" />
					
					<span class="login-label login-rememberme">
						<?php esc_attr_e('Remember Me'); ?>
					</span>
				</label>
			</p>
			
			<p class="submit">
				<input type="submit" name="wp-submit" id="wp-submit"  tabindex="100" 
				class="button-primary red_login_button login-button" value="<?php esc_attr_e('Log In'); ?>"/>
		<?php	if ( $interim_login ) { ?>
				<input type="hidden" name="interim-login" value="1" />
		<?php	} else { ?>
				<input type="hidden" name="redirect_to" value="<?php echo esc_attr($redirect_to); ?>" />
		<?php 	} ?>
				<input type="hidden" name="testcookie" value="1" />
			</p>
		</form>
		
		<?php if ( !$interim_login ) { ?>
		<p id="nav">
			<?php if ( isset($_GET['checkemail']) 
				&& in_array( $_GET['checkemail'], array('confirm', 'newpass') ) ) : ?>
				
			<?php elseif ( get_option('users_can_register') ) : ?>
				<a class="login-registration-link" href="<?php echo esc_url( $this->getLoginUrl('register') ); ?>">
					<?php _e( 'Register' ); ?>
				</a>
				
				<span class="login-spacer"> | </span>
				
				<a class="login-lostpw-link" href="<?php echo esc_url( $this->getLoginUrl('lostpassword') ); ?>" title="<?php esc_attr_e( 'Password Lost and Found' ); ?>">
					<?php _e( 'Lost your password?' ); ?>
				</a>
				
			<?php else : ?>
				<a href="<?php echo esc_url( $this->getLoginUrl('lostpassword') ); ?>" title="<?php esc_attr_e( 'Password Lost and Found' ); ?>">
					<?php _e( 'Lost your password?' ); ?>
				</a>
			<?php endif; ?>
		</p>
		<?php } ?>
		
		<script type="text/javascript">
		function wp_attempt_focus(){
			setTimeout( function(){ try{
			<?php if ( $user_login || $interim_login ) { ?>
			d = document.getElementById('user_pass');
			d.value = '';
			<?php } else { ?>
			d = document.getElementById('user_login');
			<?php if ( 'invalid_username' == $this->errors->get_error_code() ) { ?>
			if( d.value != '' )
			d.value = '';
			<?php
			}
			}?>
			d.focus();
			d.select();
			} catch(e){}
			}, 200);
		}
		
		<?php if ( !$error ) { ?>
		wp_attempt_focus();
		<?php } ?>
		if(typeof wpOnload=='function')wpOnload();
		</script>
		
		<?php
		break;
		} // end action switch
		
		?></div><?php 
	}
	
	/**
	 * Function returns the permalink for the requested action
	 * 
	 * @param $action
	 */
	function getLoginUrl( $action = 'login' )
	{
		$login_url = site_url('wp-login.php', 'login');
		return apply_filters($action, add_query_arg('action', $action, $login_url));
	}
	
	/**
	 * Method returns this logout url
	 * 
	 * @param string $logout_url
	 * @param string $redirect
	 */
	function logout_url( $logout_url, $redirect = false )
	{
		if (!$this->login_id) return $logout_url;
		
		$args = array( 'action' => 'logout' );
		if ( !empty($redirect) ) {
			$args['redirect_to'] = urlencode( $redirect );
		}
		
		$logout_url = add_query_arg($args, $this->getLoginUrl('logout'));
		$logout_url = wp_nonce_url( $logout_url, 'log-out' );
		
		return apply_filters('redrokk_login_class::logout_url', $logout_url, $redirect);
	}
	
	/**
	 * Method returns this login url
	 * 
	 * @param string $login_url
	 * @param boolean $redirect
	 */
	function login_url( $login_url, $redirect )
	{
		if (!$this->login_id) return $login_url;
		
		$login_url = $this->getLoginUrl();
		
		if ( !empty($redirect) )
			$login_url = add_query_arg('redirect_to', urlencode($redirect), $login_url);
		
		return apply_filters('redrokk_login_class::login_url', $login_url, $redirect);
	}
	
	/**
	 * Shortcode
	 *  
	 * @param array $atts
	 * @param string $content
	 */
	function shortcode( $atts = array(), $content = '' )
	{
		$atts = wp_parse_args($atts, array(
			'css'	=> true,
		)); 
		
		$lc = redrokk_login_class::getInstance();
		$display = $lc->getDisplay($atts);
		return str_replace(array("\r","\n"), '', $display);
	}
	
	/**
	 * Method only contains the css
	 */
	function css()
	{
		if (!apply_filters('redrokk_login_class::css', $this->css)) return;
		
		?>
		.login form {
			margin-left: 8px;
			padding: 26px 24px 46px;
			font-weight: normal;
			background: white;
			border: 1px solid #E5E5E5;
			-moz-box-shadow: rgba(200,200,200,0.7) 0 4px 10px -1px;
			-webkit-box-shadow: rgba(200, 200, 200, 0.7) 0 4px 10px -1px;
			box-shadow: rgba(200, 200, 200, 0.7) 0 4px 10px -1px;
		}
		#login form p {
			margin-bottom: 0;
		}
		.login * {
			margin: 0;
			padding: 0;
		}
		.login label {
			color: #777;
			font-size: 14px;
		}
		.login form .input {
			font-weight: 200;
			font-size: 24px;
			line-height: 1;
			width: 100%;
			padding: 3px;
			margin-top: 2px;
			margin-right: 6px;
			margin-bottom: 16px;
			border: 1px solid #E5E5E5;
			background: #FBFBFB;
			outline: none;
			-moz-box-shadow: inset 1px 1px 2px rgba(200,200,200,0.2);
			-webkit-box-shadow: inset 1px 1px 2px 
			rgba(200, 200, 200, 0.2);
			box-shadow: inset 1px 1px 2px 
			rgba(200, 200, 200, 0.2);
		}
		.login form .forgetmenot {
			font-weight: normal;
			float: left;
			margin-bottom: 0;
		}
		.login form .forgetmenot label {
			font-size: 12px;
			line-height: 19px;
		}
		.login input {
			color: #555;
		}
		.login input.button-primary:hover, .login button.button-primary:hover, .login a.button-primary:hover, .login a.button-primary:focus, .login a.button-primary:active {
			border-color: #13455B;
			color: #EAF2FA;
		}
		.login .button-primary {
			font-size: 13px!important;
			line-height: 16px;
			padding: 3px 10px;
			float: right;
		}
		.login input.button-primary, .login button.button-primary, .login a.button-primary {
			border-color: #298CBA;
			font-weight: bold;
			color: white;
			background: 	#21759B url(../images/button-grad.png) repeat-x scroll left top;
			text-shadow: rgba(0, 0, 0, 0.3) 0 -1px 0;
		}
		.login .submit input, .login .button, .login input.button, .login .button-primary, .login input.button-primary, 
		.login .button-secondary, .login input.button-secondary, .login .button-highlighted, .login input.button-highlighted,
		.login #postcustomstuff .submit input {
			text-decoration: none;
			font-size: 12px!important;
			line-height: 13px;
			padding: 3px 8px;
			cursor: pointer;
			border-width: 1px;
			border-style: solid;
			-webkit-border-radius: 11px;
			border-radius: 11px;
			-moz-box-sizing: content-box;
			-webkit-box-sizing: content-box;
			box-sizing: content-box;
		}
		.login #nav, .login #backtoblog {
			text-shadow: 
			white 0 1px 0;
			margin: 0 0 0 16px;
			padding: 16px 16px 0;
		}
		.login #nav a:hover, .login #backtoblog a:hover {
			color: #D54E21!important;
		}
		.login #nav a, .login #backtoblog a {
			color: #21759B!important;
		}
		.nocomments {display:none;}
		<?php 
	}
	
	/**
	 * Handles sending password retrieval email to user.
	 *
	 * @uses $wpdb WordPress Database object
	 *
	 * @return bool|WP_Error True: when finish. WP_Error on error
	 */
	function retrieve_password()
	{
		global $wpdb, $current_site;
	
		$this->errors = new WP_Error();
	
		if ( empty( $_POST['user_login'] ) ) {
			$this->errors->add('empty_username', __('<strong>ERROR</strong>: Enter a username or e-mail address.'));
		} else if ( strpos( $_POST['user_login'], '@' ) ) {
			$user_data = get_user_by( 'email', trim( $_POST['user_login'] ) );
			if ( empty( $user_data ) )
				$this->errors->add('invalid_email', __('<strong>ERROR</strong>: There is no user registered with that email address.'));
		} else {
			$login = trim($_POST['user_login']);
			$user_data = get_user_by('login', $login);
		}
	
		do_action('lostpassword_post');
	
		if ( $this->errors->get_error_code() )
			return $this->errors;
	
		if ( !$user_data ) {
			$this->errors->add('invalidcombo', __('<strong>ERROR</strong>: Invalid username or e-mail.'));
			return $this->errors;
		}
	
		// redefining user_login ensures we return the right case in the email
		$user_login = $user_data->user_login;
		$user_email = $user_data->user_email;
	
		do_action('retreive_password', $user_login);  // Misspelled and deprecated
		do_action('retrieve_password', $user_login);
	
		$allow = apply_filters('allow_password_reset', true, $user_data->ID);
	
		if ( ! $allow )
			return new WP_Error('no_password_reset', __('Password reset is not allowed for this user'));
		else if ( is_wp_error($allow) )
			return $allow;
	
		$key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));
		if ( empty($key) ) {
			// Generate something random for a key...
			$key = wp_generate_password(20, false);
			do_action('retrieve_password_key', $user_login, $key);
			// Now insert the new md5 key into the db
			$wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login));
		}
		$message = __('Someone requested that the password be reset for the following account:') . "\r\n\r\n";
		$message .= network_site_url() . "\r\n\r\n";
		$message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
		$message .= __('If this was a mistake, just ignore this email and nothing will happen.') . "\r\n\r\n";
		$message .= __('To reset your password, visit the following address:') . "\r\n\r\n";
		$message .= '<' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . ">\r\n";
	
		if ( is_multisite() )
			$blogname = $GLOBALS['current_site']->site_name;
		else
			// The blogname option is escaped with esc_html on the way into the database in sanitize_option
			// we want to reverse this for the plain text arena of emails.
			$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
	
		$title = sprintf( __('[%s] Password Reset'), $blogname );
	
		$title = apply_filters('retrieve_password_title', $title);
		$message = apply_filters('retrieve_password_message', $message, $key);
	
		if ( $message && !wp_mail($user_email, $title, $message) )
			wp_die( __('The e-mail could not be sent.') . "<br />\n" . __('Possible reason: your host may have disabled the mail() function...') );
	
		return true;
	}

	/**
	 * Retrieves a user row based on password reset key and login
	 *
	 * @uses $wpdb WordPress Database object
	 *
	 * @param string $key Hash to validate sending user's password
	 * @param string $login The user login
	 *
	 * @return object|WP_Error
	 */
	function check_password_reset_key($key, $login) 
	{
		global $wpdb;
	
		$key = preg_replace('/[^a-z0-9]/i', '', $key);
	
		if ( empty( $key ) || !is_string( $key ) )
			return new WP_Error('invalid_key', __('Invalid key'));
	
		if ( empty($login) || !is_string($login) )
			return new WP_Error('invalid_key', __('Invalid key'));
	
		$user = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->users WHERE user_activation_key = %s AND user_login = %s", $key, $login));
	
		if ( empty( $user ) )
			return new WP_Error('invalid_key', __('Invalid key'));
	
		return $user;
	}
	
	/**
	 * Handles resetting the user's password.
	 *
	 * @uses $wpdb WordPress Database object
	 *
	 * @param string $key Hash to validate sending user's password
	 */
	function reset_password($user, $new_pass) 
	{
		do_action('password_reset', $user, $new_pass);
	
		wp_set_password($new_pass, $user->ID);
	
		wp_password_change_notification($user);
	}
	
	/**
	 * Handles registering a new user.
	 *
	 * @param string $user_login User's username for logging in
	 * @param string $user_email User's email address to send password and add
	 * @return int|WP_Error Either user's ID or error on failure.
	 */
	function register_new_user( $user_login, $user_email ) 
	{
		$this->errors = new WP_Error();
	
		$sanitized_user_login = sanitize_user( $user_login );
		$user_email = apply_filters( 'user_registration_email', $user_email );
	
		// Check the username
		if ( $sanitized_user_login == '' ) {
			$this->errors->add( 'empty_username', __( '<strong>ERROR</strong>: Please enter a username.' ) );
		} elseif ( ! validate_username( $user_login ) ) {
			$this->errors->add( 'invalid_username', __( '<strong>ERROR</strong>: This username is invalid because it uses illegal characters. Please enter a valid username.' ) );
			$sanitized_user_login = '';
		} elseif ( username_exists( $sanitized_user_login ) ) {
			$this->errors->add( 'username_exists', __( '<strong>ERROR</strong>: This username is already registered, please choose another one.' ) );
		}
		
		// Check the e-mail address
		if ( $user_email == '' ) {
			$this->errors->add( 'empty_email', __( '<strong>ERROR</strong>: Please type your e-mail address.' ) );
		} elseif ( ! is_email( $user_email ) ) {
			$this->errors->add( 'invalid_email', __( '<strong>ERROR</strong>: The email address isn&#8217;t correct.' ) );
			$user_email = '';
		} elseif ( email_exists( $user_email ) ) {
			$this->errors->add( 'email_exists', __( '<strong>ERROR</strong>: This email is already registered, please choose another one.' ) );
		}
	
		do_action( 'register_post', $sanitized_user_login, $user_email, $this->errors );
	
		$this->errors = apply_filters( 'registration_errors', $this->errors, $sanitized_user_login, $user_email );
	
		if ( $this->errors->get_error_code() )
			return $this->errors;
	
		$user_pass = wp_generate_password( 12, false);
		$user_id = wp_create_user( $sanitized_user_login, $user_pass, $user_email );
		if ( ! $user_id ) {
			$this->errors->add( 'registerfail', sprintf( __( '<strong>ERROR</strong>: Couldn&#8217;t register you... please contact the <a href="mailto:%s">webmaster</a> !' ), get_option( 'admin_email' ) ) );
			return $this->errors;
		}
	
		update_user_option( $user_id, 'default_password_nag', true, true ); //Set up the Password change nag.
	
		wp_new_user_notification( $user_id, $user_pass );
	
		return $user_id;
	}

	/**
	 * Get the current page url
	 */
	function getCurrentPage()
	{
		$pageURL = 'http';
		if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}

	/**
	 * Method to bind an associative array or object to the JTable instance.This
	 * method only binds properties that are publicly accessible and optionally
	 * takes an array of properties to ignore when binding.
	 *
	 * @param   mixed  $src	 An associative array or object to bind to the JTable instance.
	 * @param   mixed  $ignore  An optional array or space separated list of properties to ignore while binding.
	 *
	 * @return  boolean  True on success.
	 *
	 * @link	http://docs.joomla.org/JTable/bind
	 * @since   11.1
	 */
	public function bind($src, $ignore = array())
	{
		// If the source value is not an array or object return false.
		if (!is_object($src) && !is_array($src))
		{
			trigger_error('Bind failed as the provided source is not an array.');
			return $this;
		}

		// If the source value is an object, get its accessible properties.
		if (is_object($src))
		{
			$src = get_object_vars($src);
		}

		// If the ignore value is a string, explode it over spaces.
		if (!is_array($ignore))
		{
			$ignore = explode(' ', $ignore);
		}

		// Bind the source value, excluding the ignored fields.
		foreach ($this->getProperties() as $k => $v)
		{
			// Only process fields not in the ignore array.
			if (!in_array($k, $ignore))
			{
				if (isset($src[$k]))
				{
					$this->$k = $src[$k];
				}
			}
		}

		return $this;
	}

	/**
	 * Set the object properties based on a named array/hash.
	 *
	 * @param   mixed  $properties  Either an associative array or another object.
	 *
	 * @return  boolean
	 *
	 * @since   11.1
	 *
	 * @see	 set() 
	 */
	public function setProperties($properties)
	{
		if (is_array($properties) || is_object($properties))
		{
			foreach ((array) $properties as $k => $v)
			{
				// Use the set function which might be overridden.
				$this->set($k, $v);
			}
		}

		return $this;
	}

	/**
	 * Modifies a property of the object, creating it if it does not already exist.
	 *
	 * @param   string  $property  The name of the property.
	 * @param   mixed   $value	 The value of the property to set.
	 *
	 * @return  mixed  Previous value of the property.
	 *
	 * @since   11.1
	 */
	public function set($property, $value = null)
	{
		$_property = 'set'.str_replace(' ', '', ucwords(str_replace('_', ' ', $property)));
		if (method_exists($this, $_property)) {
			return $this->$_property($value);
		}

		$previous = isset($this->$property) ? $this->$property : null;
		$this->$property = $value;
		return $this;
	}
	
	/**
	 * Returns an associative array of object properties.
	 *
	 * @param   boolean  $public  If true, returns only the public properties.
	 *
	 * @return  array 
	 *
	 * @see	 get()
	 */
	public function getProperties($public = true)
	{
		$vars = get_object_vars($this);
		if ($public)
		{
			foreach ($vars as $key => $value)
			{
				if ('_' == substr($key, 0, 1))
				{
					unset($vars[$key]);
				}
			}
		}

		return $vars;
	}

	/**
	 * 
	 * contains the current instance of this class
	 * @var object
	 */
	static $_instance = null;
	
	/**
	 * Method is called when we need to instantiate this class
	 * 
	 * @param array $options
	 */
	public static function getInstance( $options = array() )
	{
		if (!isset(self::$_instance))
		{
			$class = get_class();
			self::$_instance =& new $class($options);
		}
		else
		{
			self::$_instance->setProperties($options);
		}
		return self::$_instance;
	}
}
endif;
