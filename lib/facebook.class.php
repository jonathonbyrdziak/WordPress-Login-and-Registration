<?php 
/**
 * @Author	Jonathon Byrd
 * @link http://www.redrokk.com
 * @Package Wordpress
 * @SubPackage Red Rokk Library
 * 
 * @version 0.1
 */
defined('ABSPATH') or die('You\'re not supposed to be here.');

/**
 * Facebook Connect
 * 
 * I am so freaking tired of all these so called facebook connect plugins that
 * don't work worth a damn.
 * 
 */
if (!class_exists('redrokk_facebook_connect')):
class redrokk_facebook_connect 
{
	
	/**
	 * The facebook application keys
	 * 
	 */
	var $_appid = null;
	var $_appsecret = null;
	
	/**
	 * The Facebook Permissions
	 * 
	 * @var csv string
	 */
	var $scope = 'email,status_update,publish_stream,user_photos,user_videos';
	
	/**
	 * The url to redirect the user to after login
	 * 
	 * @var string
	 */
	var $redirect;
	
	/**
	 * Constructor.
	 * 
	 */
	function __construct( $options )
	{
		// Initializing
		$o = get_option('sfc_options');
		$page_id = get_option("red_login_page", '0');
		$redirect = get_permalink($page_id);
		
		$options = wp_parse_args($options, array(
			'_appid'	=> @$o['appid'],
			'_appsecret'=> @$o['app_secret'],
			'redirect'	=> $redirect
		));
		
		$this->setProperties($options);
		
		if (!$this->_fb) {
			$this->_fb = new Facebook(array(
				'appId'  => $this->_appid,
				'secret' => $this->_appsecret,
			));
		}
		
		// Hooks
		add_action( 'facebook_connect', array($this, 'button'), 20, 1 );
		
		add_action( 'init' , array($this, 'force_logout') );
		add_action( 'init', array($this, 'login') );
		add_action( 'init', array($this, 'init_js') );
		add_action( 'wp_head', array($this, 'head') );
		add_action( 'wp', array($this, 'channel') );
		
		add_action( 'wp_ajax_nopriv_redrokk_facebook_connect', array($this, 'redrokk_facebook_connect') );
		add_action( 'wp_ajax_redrokk_facebook_connect', array($this, 'redrokk_facebook_connect') );
		
		add_filter( 'logout_url', array($this, 'filter_logout_url') );
		add_filter( 'user_contactmethods', array($this, 'contactmethods'), 10, 1);
	}
	
	/**
	 * 
	 * @param unknown_type $contactmethods
	 */
	function contactmethods( $contactmethods ) 
	{
	    $contactmethods['fbuid'] = 'Facebook User ID';
	    
	    return $contactmethods;
	}
	
	/**
	 * 
	 * @return boolean
	 */
	function getWallPosts( $id = null )
	{
		if ($id === null)
		{ 
			if (!$this->getUser()) return false;
			$id = $this->getUser()->id;
		}
		
		return $this->_fb->api("/$id/feed", array('access_token' => $this->getAccessToken()));
	}
	
	/**
	 * $this->_fb->getApplicationAccessToken()
	 * 
	 */
	function getAccessToken()
	{
		return $this->_fb->getAccessToken();
	}
	
	/**
	 * Method posts a message to facebook on behalf of the current user
	 * 
	 * @param unknown_type $msg
	 * @param unknown_type $link
	 * @param unknown_type $caption
	 * @return boolean
	 */
	function postMessage( $msg, $link = null, $caption = null )
	{
		if (!$this->getUser()) return false;
	
		$params = array();
		$params['message'] 	= $msg;
		
		if ($link) 		$params['link'] 	= $link;
		if ($caption) 	$params['caption'] 	= $caption;
		
		return $this->_fb->api("/me/feed", "post", $params);
	}
	
	/**
	 * Method is designed to log the user in
	 * 
	 */
	function login()
	{
		$fbuser = $this->getUser();
		
		if ($fbuser && isset($fbuser->email) && isset($fbuser->id) && $fbuser->id)
		{
			//logged into fb and website
			//then link accounts
			if (is_user_logged_in())
			{
				update_user_meta(get_current_user_id(), 'fbuid', $fbuser->id);
			}
		
			//logged into fb and not website
			//if account does not exist create it and log them in
			elseif ( is_email($fbuser->email) && !$this->getUserByFacebookID($fbuser->id) )
			{
				$user_pass = wp_generate_password( 12, false);
					
				$user_id = wp_insert_user(array(
					'user_login' 	=> isset($fbuser->username)?$fbuser->username:$fbuser->first_name.' '.$fbuser->last_name,
					'user_email' 	=> $fbuser->email,
					'first_name' 	=> $fbuser->first_name,
					'last_name' 	=> $fbuser->last_name,
					'user_pass' 	=> $user_pass,
				));
					
				if (!is_wp_error($user_id))
				{
					update_user_option( $user_id, 'default_password_nag', true, true );
					wp_new_user_notification( $user_id, $user_pass );
		
					update_usermeta($user_id, 'fbuid', $fbuser->id);
		
					wp_signon(array(
						'user_login' => isset($fbuser->username)?$fbuser->username:$fbuser->email,
						'user_password' => $user_pass
					));
				}
					
				$redirect = isset($_REQUEST['redirect']) && $_REQUEST['redirect']
					? $_REQUEST['redirect'] 
					: false;
				
				if ($redirect) {
					wp_redirect( $redirect );
					exit;
				}
			}
		
			//logged into fb and not website
			//website account already exists
			else
			{
				//what do we do when this person already has a wordpress account
				//the are not logged in with wordpress
				//but they are logged in with facebook
					
				//I think that the cookie is already set..
				//ask them to login regularily
			}
		}
		
		if (!is_user_logged_in() && $fbuser && isset($fbuser->email) && is_email($fbuser->email))
		{
			//$user_info = get_user_by_email($fbuser->email);
			$user_info = $this->getUserByFacebookID($fbuser->id);
			if (!$user_info)
			{
				
			}
		
			$this->forceLogin( $user_info->ID );
		
			update_usermeta($user_info->ID, 'fbuid', $fbuser->id);
			
			if (is_user_logged_in()) {
				$redirect = isset($_REQUEST['redirect']) && $_REQUEST['redirect']
					? $_REQUEST['redirect'] 
					: false;
				
				if ($redirect) {
					wp_redirect( $redirect );
					exit;
				}
			}
		}
	}
	
	function forceLogin( $ID )
	{
		$user_info = get_userdata( $ID );
	
		$credentials = array();
		$credentials['user_login'] = $user_info->user_login;
	
		$secure_cookie = apply_filters('secure_signon_cookie', is_ssl(), $credentials);
		wp_set_auth_cookie($user_info->ID,  true, $secure_cookie);
	}
	
	function getUser()
	{
		if (!$this->fbuser)
		{
			try {
				//	$fbuid = get_usermeta($this->id, 'fbuid', false);
				//	$this->fbuser = (object)$this->_fb->api("/$fbuid");
				$this->fbuser = (object)$this->_fb->api("/me");
			}
			catch (FacebookApiException $e) {
				error_log($e);
				$this->fbuser = null;
			}
		}
		
		return $this->fbuser;
	}
	
	/**
	 * Method outputs the connect link for facebook
	 *
	 * @param array $object
	 */
	function button( $object = array() )
	{
		if (!is_user_logged_in()): ?>
		<div id="fb-root"></div>
		<div id="facebooklogin">
			<a href="#" id="facebooklink">
				<img id="facebookimage" src="data:image/png;base64,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" />
			</a>
			
			<img id="facebookloader" style="margin:8px;display:none;" src='data:image/gif;base64,R0lGODlhEAAQAPQAAP///z1ipvn6+2eEuaGz00FlqFl5s9/l8LzJ4E1vrZaqzoqgyeru9LC/2tPb6nOOvn6WwwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAAFUCAgjmRpnqUwFGwhKoRgqq2YFMaRGjWA8AbZiIBbjQQ8AmmFUJEQhQGJhaKOrCksgEla+KIkYvC6SJKQOISoNSYdeIk1ayA8ExTyeR3F749CACH5BAkKAAAALAAAAAAQABAAAAVoICCKR9KMaCoaxeCoqEAkRX3AwMHWxQIIjJSAZWgUEgzBwCBAEQpMwIDwY1FHgwJCtOW2UDWYIDyqNVVkUbYr6CK+o2eUMKgWrqKhj0FrEM8jQQALPFA3MAc8CQSAMA5ZBjgqDQmHIyEAIfkECQoAAAAsAAAAABAAEAAABWAgII4j85Ao2hRIKgrEUBQJLaSHMe8zgQo6Q8sxS7RIhILhBkgumCTZsXkACBC+0cwF2GoLLoFXREDcDlkAojBICRaFLDCOQtQKjmsQSubtDFU/NXcDBHwkaw1cKQ8MiyEAIfkECQoAAAAsAAAAABAAEAAABVIgII5kaZ6AIJQCMRTFQKiDQx4GrBfGa4uCnAEhQuRgPwCBtwK+kCNFgjh6QlFYgGO7baJ2CxIioSDpwqNggWCGDVVGphly3BkOpXDrKfNm/4AhACH5BAkKAAAALAAAAAAQABAAAAVgICCOZGmeqEAMRTEQwskYbV0Yx7kYSIzQhtgoBxCKBDQCIOcoLBimRiFhSABYU5gIgW01pLUBYkRItAYAqrlhYiwKjiWAcDMWY8QjsCf4DewiBzQ2N1AmKlgvgCiMjSQhACH5BAkKAAAALAAAAAAQABAAAAVfICCOZGmeqEgUxUAIpkA0AMKyxkEiSZEIsJqhYAg+boUFSTAkiBiNHks3sg1ILAfBiS10gyqCg0UaFBCkwy3RYKiIYMAC+RAxiQgYsJdAjw5DN2gILzEEZgVcKYuMJiEAOwAAAAAAAAAAAA=='/>
		</div>
		
		<?php else: ?>
		<div id="fb-root"></div>
		<div class="facebooklogin">
			<a href="<?php $this->logout_url() ?>">
				<img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFkAAAAXCAIAAADvO3TkAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAABWtJREFUeNrkWH1MW1UUp6UP2vLRFloKbRl1pXyU0QGLIOwPJwyYykSmom4s2T/6zzaXGLO4+EGURWeyhDCXmWyJyZKRmOCMExNlRIKyaRj7YMCAAoUWCi30A+gnH33vedZXXhto4dFNInpyaQ/n/c559/7uuffcWxqO4+eu/P7bn2rX4nLYJkUu5Q9rTGHbXFiRSEmR7MN3nqedrPvJMu/KUYii2RGbCnHtxoOaytyw7S9251J3/1Qch8XoHtS/Vp5tMFlHJ8whBOpR6bc7F5wY5rPK5OstvQwMw8PptDnrQmiBcHzb5wWMXZzAAR4Y8A+K4aCFFihkx3+VoJ5RMDzjgRGFygX+X+ACCPBygaLY/z0v0BUu3ChOEBNQ2EykskSRpxDxeVHj+rnW2yO37mtWMbq+SMW8N8qzu/p07XdGt2Bg8DpBXFRXr466CzAAn3QyL4K1t17eXbZXDkQAckcS1+Fawvz2l3UcyQYFXJGa4JybME6pqeCfpEEPa4+XcNh0y8w4dS9fXqDYemuEz2UTSsddjWnWMaab9QdTWSPEnrLksi8vLQK+Yl/GLrkQOO3s0d3pmSAw+crk4oKdYGzrHH1GzAP7jMXhH2StV5ZcSCJJfV/+TngkEzGLn0u7O+SkvHdi3rxwoxhspMEaOdZvvutsaumbNtsJO7kDb9gIvuADglW/mP3mS8q+gRH7vPFkTaFCygJApiwB9EgkTKUagPMfABiYxeW0kxECeqWIeCSS1FOSYuFdErEwjhNh1KupdA+aG/XjIpgcKs0SeFYHCOjQ4rks4hG5X2wouJdPPFHAPfhC5s+tXXVf1tc1XJ8ymI9W5s8axwuUEnh84oPPL1xugqegWwxjCy4b4c7nsQN6EWEJJKl/29QBStOPrfWXGhdddoyauH1rxO2b51XyevmuVXpbp9ofjFJZIx4M9FYo4IIyNKTiCqR8SZbJuqxMS9RrewXxR8E+oTOk5h6YX0A8YM8y9jgSk7HWC8dLSSTBxeOvlXdxACxKR6mVOWCArCNYsHLwaHhaKuFFsSIIHT6nTba1lZnaGQS32lzwFRsTxYhggyOEtcJlwGW3OxbBHhMdxUBYbJaHizCczD6bYyGgl3f8HqRPxzHveRjDqfRtpY74aioWjL+PGm6ePVUGmxahBzuxUcyLAfXksGamuqpUa3DK0zNlyXENl7+nhSNwJyrKTXn/eM2YiVFaJCfAKO7NiyGtOaAXQXF5cZFqcvHV/VmEF9EdRYZswoSOGfFNc7F+HVlZ7YFLBvU6An8wV7UX245VyOpOH4HZbvzhj0tXGgWSrF87hrgs9HBVmc3uVKmn9mRL8cdT7AsezEsh5VRXlU0azO1/9VeU5IDXvV7t/T7tgZIiSLFzl9vYsQLqdWQT586QuXjQP1V8+Lxu6DZforDaF+uvdZ/54qrNMhnOQHiJcq5QrkxPtDjopW/Xaod7Tp86BlzMW2HbiyaDB/QC4ydft+lHv3JajUgk+7PzCfNGjSRt76cX2+FdYIyJEzOj+U/z3EkW1YAYimuSGR2fmvcKgafRGULpHmhkBMiXE0cKD+3PmDY7cxXiwRFdb//Ijkyxf/C1XoRRlFpIYgTJSuIRaaTYPeLcSSUvnnSNbCj3Hk2+e+aqPAmGhjT/snyjuSWSxUGYnC277PjyYv17Kr7ulfRp3VM1M26VZs5lM4KOcGQ8XtJW3oC991Q6nbbkRiMQxkKQ3ztv3hp+OKj/R/OCEEgEaFt/A2ZGIsAA8MDIyxL1jxjjuWxODCsgtHvQQChCfqy/XTs5u9a4PX+8wE0WJ/BAg5L53tlmqPAhzEPB7uTOhxPbnQvIiJyMpAsfH/xbgAEAg35MtfF9OMAAAAAASUVORK5CYII='/>
			</a>
		</div>
		<?php endif; ?>
		
		<script type="application/javascript">
		jQuery(function(){
			jQuery('#facebooklink').FacebookConnect(<?php echo json_encode($object) ?>);
		});
		</script>
		<?php 
	}
		
	/**
	 * Method refreshes the users cookie so that we can log them in on reload
	 * 
	 */
	function redrokk_facebook_connect()
	{
		?>
		<script type="text/javascript">
		FB.getLoginStatus(function(response) {
		  if (response.status === 'connected') {
		    // the user is logged in and has authenticated your
		    // app, and response.authResponse supplies
		    // the user's ID, a valid access token, a signed
		    // request, and the time the access token 
		    // and signed request each expire
		    var uid = response.authResponse.userID;
		    var accessToken = response.authResponse.accessToken;

			<?php $redirect_to = apply_filters('onJavascriptLogin', $this->redirect); ?>
	 		<?php if ($redirect_to): ?>
		    window.location.replace("<?php echo $redirect_to; ?>");
		    <?php endif; ?>
		    
		  } else if (response.status === 'not_authorized') {
		    // the user is logged in to Facebook, 
		    // but has not authenticated your app
		  } else {
		    // the user isn't logged in to Facebook.
		  }
		});
		</script>
		<?php
		die();
	}
	
	/**
	 * Method returns the user by the given facebook uid
	 * 
	 * @param unknown_type $Id
	 * @return boolean
	 */
	function getUserByFacebookID( $Id )
	{
		global $wpdb;
	
		$sql = "SELECT *, users.ID"
		. " FROM $wpdb->users users, $wpdb->usermeta usermeta"
		. " WHERE users.ID = usermeta.user_id"
		. " AND usermeta.meta_key = 'fbuid'"
		. " AND usermeta.meta_value = '$Id'";
	
		$users = $wpdb->get_results($sql);
		if (count($users) >= 1) {
			foreach ($users as $user) {
				return get_userdata( $user->ID );
			}
		}
		return false;
	}
	
	/**
	 * Method determines if the logged in user is a facebook user or not
	 * 
	 * @return boolean
	 */
	function is_facebook_user()
	{
		//get wp user
		$user = get_userdata(get_current_user_ID());
	
		if (!is_user_logged_in()) return false;
		if (!$this->getUser()) return false;
	
		$fbuid = get_user_meta(get_current_user_id(), 'fbuid', true);
	
		//get fb user
		if (isset($this->getUser()->username)
				&& sanitize_title($this->getUser()->username) == $user->user_nicename
		){
			return true;
		}
	
		// does fbuid match?
		elseif (isset($this->getUser()->id)
				&& $this->getUser()->id == $fbuid
		){
			return true;
		}
	
		return false;
	}
	
	/**
	 *
	 * @param string $url
	 */
	function filter_logout_url( $url )
	{
		if ($this->is_facebook_user()) {
			return $this->get_logout_url();
		}
	
		return $url;
	}
	
	/**
	 *
	 */
	function force_logout()
	{
		if (!array_key_exists('facebook_logout', $_GET)) return false;
	
		wp_logout();
	
		$redirect_to = !empty( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : $_SERVER["HTTP_REFERER"];
		if ($redirect_to) {
			wp_safe_redirect( $redirect_to );
			exit();
		}
	}
	
	/**
	 *
	 */
	function init_js()
	{
		wp_enqueue_script('jquery');
	}
	
	/**
	 * Method outputs the js needed to operate the connect
	 * 
	 */
	function head()
	{
		require_once plugin_dir_path( dirname(__file__) ).'js/facebook.js.php';
	}
	
	/**
	 *
	 */
	function channel()
	{
		if (!array_key_exists('facebook_channel', $_GET)) 
			return false;
		
		echo '<script src="//connect.facebook.net/en_US/all.js"></script>';
		die();
	}
	
	/**
	 * 
	 * @param string $next
	 */
	function logout_url( $redirect = null )
	{
		echo $this->get_logout_url( $redirect );
	}
	
	/**
	 * 
	 * @param string $redirect
	 */
	function get_logout_url( $redirect = null )
	{
		if ($redirect === null) {
			$redirect = $this->redirect;
		}
		
		$logout = add_query_arg(array('redirect_to' => $redirect, 'facebook_logout' =>''), site_url());
		return $this->_fb->getLogoutUrl( array('next' => $logout) );
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
	 * Method returns the called class
	 *
	 */
	public static function get_called_class()
	{
		if (function_exists('get_called_class')) {
			return get_called_class();
		}
	
		$called_class = false;
		$objects = array();
		$traces = debug_backtrace();
		foreach ($traces as $trace)
		{
			if (isset($trace['object'])) {
				if (is_object($trace['object'])) {
					$objects[] = $trace['object'];
				}
			}
		}
	
		if (count($objects)) {
			$called_class = get_class($objects[0]);
		}
	
		return $called_class;
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
	 * @param array $id
	 * @param array $options
	 */
	public static function getInstance( $options = array() )
	{
		if (!isset(self::$_instance))
		{
			$class = self::get_called_class();
			self::$_instance =& new $class($options);
		}
		return self::$_instance;
	}
}
endif;

