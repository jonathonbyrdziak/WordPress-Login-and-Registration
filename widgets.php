<?php 

/**
 * Actions and Filters
 *
 * Register any and all actions here. Nothing should actually be called
 * directly, the entire system will be based on these actions and hooks.
 */
add_action( 'widgets_init', create_function( '', 'register_widget("RedRokk_Login_Widget");' ) );

/**
 * This is the class that you'll be working with. Duplicate this class as many times as you want. Make sure
 * to include an add_action call to each class, like the line above.
 *
 * @author byrd
 *
 */
class RedRokk_Login_Widget extends Empty_Widget_Abstract
{
	/**
	 * Widget settings
	 *
	 * Simply use the following field examples to create the WordPress Widget options that
	 * will display to administrators. These options can then be found in the $params
	 * variable within the widget method.
	 *
	 *
	 */
	protected $widget = array(
		// you can give it a name here, otherwise it will default
		// to the classes name. BTW, you should change the class
		// name each time you create a new widget. Just use find
		// and replace!
		'name' => 'Login and Registration Form',

		// this description will display within the administrative widgets area
		// when a user is deciding which widget to use.
		'description' => 'Place the fully functional WordPress login box anywhere on your website using a widget, or a shortcode.',

		// determines whether or not to use the sidebar _before and _after html
		'do_wrapper' => true,

		// determines whether or not to display the widgets title on the frontend
		'do_title'	=> false,

		// string : if you set a filename here, it will be loaded as the view
		// when using a file the following array will be given to the file :
		// array('widget'=>array(),'params'=>array(),'sidebar'=>array(),
		// alternatively, you can return an html string here that will be used
		'view' => false,
	
		// If you desire to change the size of the widget administrative options
		// area
		'width'	=> 350,
		'height' => 350,
	
		// Shortcode button row
		'buttonrow' => 4,
	
		// The image to use as a representation of your widget.
		// Whatever you place here will be used as the img src
		// so we have opted to use a basencoded image.
		'thumbnail' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAABGdBTUEAALGPC/xhBQAAAAZiS0dEAP8A/wD/oL2nkwAAAAlwSFlzAAALEwAACxMBAJqcGAAAAAd0SU1FB9wHBRM2Gd/BfkoAAAMhSURBVDjLrZRLb1tVFIW/fX2u48Rx8zSiOFESgWiU0ioDqsgSD5UiUYWH6Kg/g0ErMUBC9IcwADFggMQUJowYJA2iaVQ1DJLYSZoHzcNNnPi+fM5mcG0rrcoksCd3cHS+vdbe61z4n0t++/Mv/0J37m2T8boBPS+naV1wHIR/mChKPs50d/0s6s5PAzIoUZTcMknTzuzsPUMVbZ2dp1QEaYrMGAWJmsp/gIGkdz0jYrJ+hulLo3RlfVT/xU9bhlUQRRVU0y8KipIklkcbuxhByPfkKOR7Xtq8fhISBHEKdOnWUmCriSrGZOjO+YgIBoFGI+bB0g77ByeIpJKcU6bfKvFk5xA/o2SzpnPWUYzirOW0YZkYfwVEMCgYk2FsZIChwXzHoaoyNJinurnHWOkC3bksnuc9D1TFWkv1yRHWOgQwAL7vMTY6+FLLly+VqG4eYm1EOwpn7aoqw4MF+go59G9NgWcDqKoda3v7+zzd3aI4kGd8bBwRQRG0RVSXLsap0mjN2by4yTasmSSsrazQPzBAEITM37/PxVcv0j9QpGnTpKgqTWvpypqOJgPQbDq2do7SbUpqKY5jTgNHdf0BpdIIxWKR9Y11VtY28Uwvx8d1fN9ndHQUZ6FYLLSAAkliWasesF877Yh1Dq5OvUlsDYuL80xNTdHX18/y8mMWFx8ShhHWNhkeHubq9HsMF2dasVHI5XzK18ZxVjtzBOjqMqxVn1IYusKPP/1KXz7h8LCGqhLHMWEYUqlUmJubo7I2y1T5empZnZLx5Mwo0/kkTcuN9yeBSW7fKnPv3jfEcYyqUqvV6O0tMDv7KQsL83z/3bdcXlrCAzxPQETU84R2dkUEEQ/nFOcczjpGSiNkfZ8oihAR7t79koODPSqVVa3X6+xub3tGVTd2ayc8a0SimiZI9EyS2hEBrr3zAdt7h9SXH+Gc486dLzSOYzltBHLjo0949+ZnG8Y5/WH74Ohx6xXoC2/rucpme3h98kqyMPf7zSgMv3LOycnJyXEQRl9/OPv5w9cm3lg81y+rXC57YRhOBEFwPUmSX1ZXV7fa7f8BAqmdfON9nh8AAAAASUVORK5CYII=',
		
		/* The field options that you have available to you. Please
		 * contribute additional field options if you create any.
		 *
		 */
		'fields' => array(
			// You should always offer a widget title
			array(
				'name' 		=> 'Title',
				'desc' 		=> '',
				'id' 		=> 'title',
				'type' 		=> 'text',
				'default' 	=> 'Login'
			),
			array(
				'name' => 'Transform the Title',
				'desc' => '',
				'id' => 'transform',
				'type' => 'checkbox',
				'options' => array( 
					'true' => 'Yes, change the url to display the current action.' 
				),
				'default'	=> 'true'
			),
			array(
				'name'		=> 'Default Form',
				'desc' 		=> '',
				'id'	  	=> 'action',
				'type'		=> 'select',
				'options' 	=> array( 
					'login' => 'Login', 
					'lostpassword' => 'Lost Password',
					'register' => 'Registration'
				)
			),
			array(
				'name'	=> 'When Logged In, Show',
				'desc' => '',
				'id'	  => 'display',
				'type'	=> 'select',
				'options' => array( 
					'menu' => 'Nav Menu', 
					'logout' => 'Logout Button', 
					'action' => 'Default Action',
					'form' => 'Continue Showing the Form' ,
					'nothing' => 'Nothing' 
				),
				'desc'		=> 'What should display to logged in users?'
			),
			array(
				'name' => 'Default CSS',
				'desc' => '',
				'id' => 'css',
				'type' => 'checkbox',
				'options' => array( 
					'true' => 'Yes, include the default css styles.' 
				),
				'default'	=> 'true'
			),
			array(
				'name' => 'Rewrite Urls ( WARNING!! )',
				'desc' => '',
				'id' => 'primary',
				'type' => 'checkbox',
				'options' => array( 
					'true' => 'Yes, point all urls to this instance.' 
				),
				'desc'	=> 'If you choose to rewrite the urls, then all links to your login and logout pages will be redirected to the page that this plugin displays on.<br/><br/>This url rewrite method should not be used when displaying a widget, unless you have only a single instance of this widget.'
			),
		)
	);

	/**
	 * Widget HTML
	 *
	 * If you want to have an all inclusive single widget file, you can do so by
	 * dumping your css styles with base_encoded images along with all of your
	 * html string, right into this method.
	 *
	 * @param array $widget
	 * @param array $params
	 * @param array $sidebar
	 */
	function html($widget = array(), $params = array(), $sidebar = array())
	{
		// initializing
		$params['primary'] = $params['primary'] == 'true' ?true :false;
		$params['css'] = $params['css'] == 'true' ?true :false;
		$params['transform'] = $params['transform'] == 'true' ?true :false;
		$titleKey = isset($this->action) ?$this->action :$params['action'];
		$actions = array(
			'lostpassword' => 'Lost Password', 
			'retrievepassword' => 'Retrieve my Password', 
			'resetpass' => 'Reset my Password', 
			'register' => 'Registration', 
			'login' => $params['title']
		);
		
		// Instance Title
		if (is_user_logged_in()) {
			switch ($params['display'])
			{
				default:
				case 'menu':
					$actions['login'] = 'User Menu';
					break;
				case 'logout':
				case 'nothing':
					$actions['login'] = '';
					break;
			}
		}
		if ($params['transform'] && array_key_exists($titleKey, $actions))
			$params['title'] = $actions[$titleKey];
		
		if ($params['title'])
			echo $sidebar['before_title'] . $params['title'] . $sidebar['after_title'];
		
		
		// if user is not logged in, show the form
		if (!is_user_logged_in()) {
			$l = redrokk_login_class::getInstance();
			$l->display( $params );
			return;
		}
		
		
		// if the user is logged in
		switch ($params['display'])
		{
			default:
			case 'menu':
			$args = array(
				'container_class' => 'user-menu', 
				'theme_location' => 'redrokk-login-widget' 
			);
			wp_nav_menu( $args );
			break;
			
			case 'logout':
				echo '<a href="'.wp_logout_url()
					.'" class="logoutbutton button" title="Logout">Logout</a>';
				break;
			
			case 'nothing':
				break;
			case 'form':
				$l = redrokk_login_class::getInstance();
				$l->display( $params );
				break;
		}
	}
}

