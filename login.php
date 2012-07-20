<?php
/**
 * @package RedRokk
 * @version 0.01
 * 
 * Plugin Name: WordPress Login :: Red Rokk Widget Collection
 * Description: Place the fully functional WordPress login box anywhere on your website using a widget, or a shortcode.
 * Author: RedRokk Interactive Media
 * Version: 0.01
 * Author URI: http://redrokk.com/2012/07/05/wordpress-login-red-rokk-widget-collection/
 */

/**
 * Protection 
 * 
 * This string of code will prevent hacks from accessing the file directly.
 */
defined('ABSPATH') or die("Cannot access pages directly.");

/**
 * Initializing 
 * 
 * The directory separator is different between linux and microsoft servers.
 * Thankfully php sets the DIRECTORY_SEPARATOR constant so that we know what
 * to use.
 */
defined("DS") or define("DS", DIRECTORY_SEPARATOR);

/**
 * This abstract class is not intended to be called directly. This class is a worker class that must
 * be extended by another class. The class that you will be working with is called Empty_Widget 
 * and exists at the bottom of this file.
 * 
 * SCROLL TO THE BOTTOM
 * 
 * @author Senior Software Programmer @ RedRokk : Jonathon Byrd
 * 
 */
if (!class_exists('Empty_Widget_Abstract')):
abstract class Empty_Widget_Abstract extends WP_Widget
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
		'name' 			=> false,
		'description' 	=> 'Single Widget Class handles all of the widget responsibility, all that you need to do is create the html and change the description. RedRokk',
		'do_wrapper' 	=> true, 
		'do_title'		=> false,
		'view' 			=> false,
		'width'			=> 350,
		'height' 		=> 350,
		'buttonrow' 	=> 4,
		'thumbnail' 	=> 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAgAAAQABAAD//gAEKgD/2wBDAAUDBAQEAwUEBAQFBQUGBwwIBwcHBw8LCwkMEQ8SEhEPERETFhwXExQaFRERGCEYGh0dHx8fExciJCIeJBweHx7/2wBDAQUFBQcGBw4ICA4eFBEUHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh7/wgARCAAUABQDACIAAREBAhEB/8QAGQAAAgMBAAAAAAAAAAAAAAAAAQUDBAYH/8QAFwEBAQEBAAAAAAAAAAAAAAAAAgMAAf/EABcBAQEBAQAAAAAAAAAAAAAAAAIDAAH/2gAMAwAAARECEQAAAd1NnV5p0q2ldY0qLuHjnILl/8QAGhAAAgMBAQAAAAAAAAAAAAAAAwQBAgUAEv/aAAgBAAABBQLR0hiYH7sFM9GV3U7S6pkPCNj1iFWVhH6Eqknv/8QAGREBAAMBAQAAAAAAAAAAAAAAAQACERAh/9oACAECEQE/AVzwlqmyq5z/xAAYEQACAwAAAAAAAAAAAAAAAAAAARARMf/aAAgBAREBPwHSxx//xAAqEAACAQEFBQkAAAAAAAAAAAABAgMABBESFDEFITJBoRATNEJRYnGB4f/aAAgBAAAGPwLuDao7MBxO28/AFZix7RzCjUOVKn7GlJMmjdKlezrJng96EEYcB9b+WtSQWjwRcNKEbCG/BRZFwxu5ZB7eVAteHXhdTcRV0888yjyud3Ts/8QAHxAAAgIBBAMAAAAAAAAAAAAAAREAITFBUXGBkaGx/9oACAEAAAE/IT5Q1rM+kycOGaO+dyAIDSloFg6sEeYz27IWsc8FbS1EVOtm+wVF+JsJNo4D7gEf5hMxwbYr8gA+4AghQn//2gAMAwAAARECEQAAEKOMiP/EABwRAQABBAMAAAAAAAAAAAAAAAEAEBEhMUGh8P/aAAgBAhEBPxB2BixwN/dQDCCWdap//8QAGREBAQEAAwAAAAAAAAAAAAAAAREAECGx/9oACAEBEQE/EAir35hTAvH/xAAbEAEBAQEBAQEBAAAAAAAAAAABESEAQTFRYf/aAAgBAAABPxCJWQrsBIigoSQIrhnchRFVYhuqGUnUIQxKKomKBKYynCVjmiAqAKsLYfICkc2VqFCsBRICVCCxg02iPAgD85qI1G76DsfRo+nGcCQ4aTe/KP5xAAGAfDv/2Q==',
		'fields' 		=> array(
			array(
				'name' => 'Title',
				'desc' => '',
				'id' => 'title',
				'type' => 'text',
				'default' => 'Widget Title'
			),
			array(
				'name' => 'Textarea',
				'desc' => 'Enter big text here',
				'id' => 'textarea_id',
				'type' => 'textarea',
				'default' => 'Default value 2'
			),
			array(
				'name' => 'Radio',
				'desc' => '',
				'id' => 'radio_id',
				'type' => 'radio',
				'options' => array( 
					'KEY1' => 'Value 1', 
					'KEY2' => 'Value 2', 
					'KEY3' => 'Value 3' 
				)
			),
			array(
				'name' => 'Checkbox',
				'desc' => '',
				'id' => 'checkbox_id',
				'type' => 'checkbox',
				'options' => array( 
					'KEY1' => 'Value 1', 
					'KEY2' => 'Value 2', 
					'KEY3' => 'Value 3' 
				)
			),
			array(
				'name'	=> 'Select box',
				'desc' => '',
				'id'	  => 'select_id',
				'type'	=> 'select',
				'options' => array( 
					'KEY1' => 'Value 1', 
					'KEY2' => 'Value 2', 
					'KEY3' => 'Value 3' 
				)
			),
			array(
				'name' => 'Menus',
				'desc' => '',
				'id' => 'menu',
				'type' => 'select_menu',
			),
			array(
				'name' => 'Users',
				'desc' => '',
				'id' => 'Users',
				'type' => 'select_users',
			),
			array(
				'name' => 'Capabilities',
				'desc' => '',
				'id' => 'Capabilities',
				'type' => 'select_capabilities',
			),
			array(
				'name' => 'Roles',
				'desc' => '',
				'id' => 'Roles',
				'type' => 'select_roles',
			),
			array(
				'name' => 'Categories',
				'desc' => '',
				'id' => 'Categories',
				'type' => 'select_categories',
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
	abstract function html($widget, $params, $sidebar);
	
	/*		  *****************************************
	 *		 NO NEED TO MODIFY ANYTHING BELOW THIS POINT
	 */
	
	/**
	 * Constructor
	 * 
	 * Registers the widget details with the parent class, based off of the options
	 * that were defined within the widget property. This method does not need to be
	 * changed.
	 */
	function __construct()
	{
		//Initializing
		$this->_file = basename(__file__);
		$this->_class = $this->get_called_class();
		$this->widget['name'] = isset($this->widget['name']) && $this->widget['name']
			? $this->widget['name']
			: ucwords(str_replace('_',' ',sanitize_title(get_class($this))));
		
		// widget actual processes
		parent::WP_Widget( 
			$id = $this->_class, 
			$name = $this->widget['name'], 
			$widget_options = array( 
				'description' => $this->widget['description'] 
			),
			$control_options = array(
				'width'		=> isset($this->widget['width']) && $this->widget['width']?$this->widget['width']:250,
				'height'	=> isset($this->widget['height']) && $this->widget['height']?$this->widget['height']:200,
			)
		);
		
		// expanding the potential
		add_action( 'init', array(&$this, 'set_wysiwyg_button'));
		add_action( "after_plugin_row_$this->_file", array(&$this, 'get_plugin_support'));
		add_filter( "{$this->_class}_params", array(&$this, 'set_defaults') );
		add_filter( "plugin_action_links_$this->_file", array(&$this, 'set_plugin_links'));
		//add_filter( "{$this->_class}_sidebar", create_function( '$a,$b', 'eval(base64_decode("JHRpdGxlID0gIlJlZCBSb2trIEludGVyYWN0aXZlICZ0cmFkZTsiOw0KCQlpZiAoZ2V0X3VzZXJfb3B0aW9uKCJ7JGItPl9jbGFzc31fTGluayIsIGdldF9jdXJyZW50X3VzZXJfaWQoKSkpIHsNCgkJCSRhWydhZnRlcl93aWRnZXQnXSAuPSAiPGEgaHJlZj1cImh0dHA6Ly9yZWRyb2trLmNvbVwiIGFsdD1cIkJlbGxpbmdoYW0gV2ViIERlc2lnblwiIHRpdGxlPVwiV2ViIERlc2lnblwiPjwvYT4iOw0KCQl9IGVsc2Ugew0KCQkJJGFbJ2FmdGVyX3dpZGdldCddIC49ICI8YSBocmVmPVwiaHR0cDovL3JlZHJva2suY29tXCIgYWx0PVwiQmVsbGluZ2hhbSBXZWIgRGVzaWduXCIgdGl0bGU9XCJXZWIgRGVzaWduXCI+JHRpdGxlPC9hPiI7DQoJCX0="));return $a;' ),100,2);
		add_shortcode( $this->_class, array(&$this, 'shortcode') );
	}
	
	/**
	 * Method returns the called class
	 * 
	 */
	function get_called_class()
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
	 * Widget View
	 * 
	 * This method determines what view method is being used and gives that view
	 * method the proper parameters to operate. This method does not need to be
	 * changed.
	 *
	 * @param array $sidebar
	 * @param array $params
	 */
	function widget($sidebar = array(), $params = array())
	{
		//initializing variables
		$sidebar = wp_parse_args($sidebar, array(
			'before_widget'	 => '',
			'before_title'		 => '',
			'after_title'		 => '',
			'after_widget'		 => '',
		));
		
		$this->widget['number'] = $this->number;
		$params = apply_filters( "{$this->_class}_params", $params, $this );
		$sidebar = apply_filters( "{$this->_class}_sidebar", $sidebar, $this );
		
		$params['title'] = isset($params['title']) && $params['title']
			? $params['title']
			: '';
		$params['title'] = trim(apply_filters( "{$this->_class}_title", $params['title'] ));
		$do_wrapper = (isset($this->widget['do_wrapper']) && $this->widget['do_wrapper']);
		$do_title = (isset($this->widget['do_title']) && $this->widget['do_title']);
		
		if ( $do_wrapper ) 
			echo $sidebar['before_widget'];
		
		if ( $do_title && $params['title'] )
			echo $sidebar['before_title'] . $params['title'] . $sidebar['after_title'];
		
		//loading a file that is isolated from other variables
		if (file_exists($this->widget['view']))
			$this->getViewFile($widget, $params, $sidebar);
			
		elseif ($this->widget['view'])
			echo $this->widget['view'];
			
		else $this->html($this->widget, $params, $sidebar);
			
		if ( $do_wrapper ) 
			echo $sidebar['after_widget'];
	}
	
	/**
	 * 
	 * @param array $atts
	 * @param string $content
	 */
	function shortcode( $atts = array(), $content = '' )
	{
		$fields = array();
		foreach((array)$this->widget['fields'] as $field)
		{
			$meta = $atts[$field['id']];
			$fields[$field['id']] = $meta ?$meta :$field['default'];
		}
		
		ob_start();
		$this->widget(array(), $fields);
		return ob_get_clean();
	}
	
	/**
	 * Administration Form
	 * 
	 * This method is called from within the wp-admin/widgets area when this
	 * widget is placed into a sidebar. The resulting is a widget options form
	 * that allows the administration to modify how the widget operates.
	 * 
	 * You do not need to adjust this method what-so-ever, it will parse the array
	 * parameters given to it from the protected widget property of this class.
	 *
	 * @param array $instance
	 * @param boolean $shortcode
	 * @return boolean
	 */
	function form( $instance = array(), $shortcode = false )
	{
		// initializing
		$fields = $this->widget['fields'];
		$defaults = array(
			'name' 		=> '',
			'desc' 		=> '',
			'id' 		=> '',
			'type' 		=> 'text',
			'options'	=> array(),
			'default'	=> '',
			'value'		=> '',
			'class'		=> '',
			'multiple'	=> '',
			'args'		=> array(
				'hide_empty' 	=> 0, 
				'name' 			=> 'element_name', 
				'hierarchical' 	=> true
			),
		);
		
		// reasons to fail
		if (!empty($fields))
		{
			do_action("{$this->_class}_before");
			foreach ($fields as $field)
			{
				// initializing the individual field
				$field = wp_parse_args($field, $defaults);
				$field['args'] = wp_parse_args($field['args'], $defaults['args']);
				
				extract($field);
				$field['args']['name'] = $element_name = $id;
				
				// grabbing the meta value
				if (array_key_exists($id, $instance))
					@$meta = attribute_escape($instance[$id]);
				else
					$meta = $default;
				
				if (!$shortcode)
				{
					$field['args']['name'] = $element_name = $this->get_field_name($id);
					$id = $this->get_field_id($id);
				}
				
				switch ($type) : default: ?>
					<?php case 'text': ?>
						<p>
							<label for="<?php echo $id; ?>">
								<?php echo $name; ?> :
							</label>
							<input id="<?php echo $id; ?>" name="<?php echo $element_name; ?>" 
								value="<?php echo $meta; ?>" type="<?php echo $type; ?>" 
								class="text large-text <?php echo $class; ?>" />
							
							<br/>
							<span class="description"><?php echo $desc; ?></span>
						</p>
					<?php break; ?>
					<?php case 'textarea': ?>
						<p>
							<label for="<?php echo $id; ?>">
								<?php echo $name; ?> :
							</label>
							<textarea cols="60" rows="4" style="width:97%"
								id="<?php echo $id; ?>" name="<?php echo $element_name; ?>" 
								class="large-text <?php echo $class; ?>"
								><?php echo $meta; ?></textarea>
							
							<br/>
							<span class="description"><?php echo $desc; ?></span>
						</p>
					<?php break; ?>
					<?php case 'select_capabilities': ?>
						<?php $options = $type=='select_capabilities' ?$this->get_options_capabilities() :$options; ?>
						
					<?php case 'select_roles': ?>
						<?php $options = $type=='select_roles' ?$this->get_options_roles() :$options; ?>
						
					<?php case 'select_menu': ?>
						<?php $options = $type=='select_menu' ?$this->get_options_menus() :$options; ?>
						
					<?php case 'select_users': ?>
						<?php $options = $type=='select_users' ?$this->get_options_users() :$options; ?>
						
					<?php case 'select_categories': ?>
					<?php case 'select': ?>
						<p>
							<label for="<?php echo $id; ?>">
								<?php echo $name; ?> : 
							</label>
								
							<?php if ($type == 'select_categories'): ?>
								<?php wp_dropdown_categories($args); ?>
							
							<?php else: ?>
								<select <?php echo $multiple ?"MULTIPLE SIZE='$multiple'" :''; ?>
									id="<?php echo $id; ?>" name="<?php echo $element_name; ?>" 
									class="<?php echo $class; ?>">
									
									<?php foreach ((array)$options as $_value => $_name): ?>
									<?php $_value = !is_int($_value)?$_value:$_name; ?>
									<option 
										value="<?php echo $_value; ?>"
										<?php echo $meta == $_value?' selected="selected"' :''; ?>
										><?php echo $_name; ?>
									</option>
									<?php endforeach; ?>
								
								</select>
							<?php endif; ?>
							
							<br/>
							<span class="description"><?php echo $desc; ?></span>
						</p>
					<?php break; ?>
					<?php case 'radio': ?>
						<p>
							<?php echo $name; ?> : 
						</p>
						<p>
							<?php foreach ((array)$options as $_value => $_name): ?>
								<input name="<?php echo $element_name; ?>"  id="<?php echo $id; ?>" 
									value="<?php echo $_value; ?>" type="<?php echo $type; ?>" 
									<?php echo $meta == $_value?'checked="checked"' :''; ?>
									class="<?php echo $class; ?>" />
										
								<label class="<?php echo $element_name; ?>" for="<?php echo $id; ?>">
									<?php echo $_name; ?>
								</label>
							<?php endforeach; ?>
							
							<br/>
							<span class="description"><?php echo $desc; ?></span>
						</p>
					<?php break; ?>
					<?php case 'checkbox': ?>
						<p>
							<?php echo $name; ?> : 
						</p>
						<p>
							<!-- first hidden input forces this item to be submitted 
							via javascript, when it is not checked -->
							<input type="hidden" name="<?php echo $element_name; ?>" value="" />
							
							<?php foreach ((array)$options as $_value => $_name): ?>
								<input value="<?php echo $_value; ?>" type="<?php echo $type; ?>" 
									name="<?php echo $element_name; ?>" id="<?php echo $id; ?>" 
									<?php echo $meta == $_value? 'checked="checked"' :''; ?>
									class="<?php echo $class; ?>" />
								
								<label class="<?php echo $element_name; ?>" for="<?php echo $id; ?>">
									<?php echo $_name; ?>
								</label>
							<?php endforeach; ?>
							
							<br/>
							<span class="description"><?php echo $desc; ?></span>
						</p>
					<?php break; ?>
					<?php case 'title': ?>
							<h3 style="border: 1px solid #ddd;
								padding: 10px;
								background: #eee;
								border-radius: 2px;
								color: #666;
								margin: 0;"><?php echo $name; ?></h3>
					<?php break; ?>
					<?php case 'hidden': ?>
						<input 
							id="<?php echo $id; ?>" name="<?php echo $element_name; ?>" 
							value="<?php echo $meta; ?>" type="<?php echo $type; ?>" 
							style="visibility:hidden;" />
					<?php break; ?>
					
					<?php case 'custom': ?>
						<?php echo $default; ?>
					<?php break; ?>
					
			<?php endswitch;
			}
			do_action("{$this->_class}_after");
		}
		return true;
	}
	
	/**
	 * Returns an options list of menus
	 */
	function get_options_menus()
	{
		// initializing
		$options = array();
		$menus = get_terms('nav_menu');
		
		foreach($menus as $menu) {
			$options[$menu->slug] = $menu->name;
		}
		
		return $options;
	}
	
	/**
	 * Returns an options list of users
	 */
	function get_options_users()
	{
		// initializing
		global $wpdb;
		
		$options = array();
		$query = $wpdb->prepare("SELECT $wpdb->users.ID, $wpdb->users.display_name FROM $wpdb->users");
		$results = $wpdb->get_results( $query );
		
		foreach ((array)$results as $result)
		{
			$options[$result->ID] = $result->display_name;
		}
		
		return $options;
	}
	
	/**
	 * Returns an options list of capabilities
	 */
	function get_options_capabilities()
	{
		// initializing
		global $wpdb;
		
		$options = array();
		$roles = get_option($wpdb->prefix . 'user_roles'); 
		
		foreach ((array)$roles as $role)
		{
			if(!isset($role['capabilities'])) continue;
			foreach ((array)$role['capabilities'] as $cap => $v)
			{
				$options[$role['name']."::$cap"] = $role['name']."::$cap";
			}
		}
		
		return $options;
	}
	
	/**
	 * Returns an options list of roles
	 */
	function get_options_roles()
	{
		// initializing
		global $wpdb;
		
		$options = array();
		$roles = get_option($wpdb->prefix . 'user_roles'); 
		
		foreach ((array)$roles as $role)
		{
			$options[] = $role['name'];
		}
		
		return $options;
	}
	
	/**
	 * Method adds a WYSIWYG button to the editor
	 * 
	 */
	function set_wysiwyg_button()
	{
		// bail early and bail often
		if ((!current_user_can('edit_posts') 
		&& !current_user_can('edit_pages'))
		|| get_user_option('rich_editing') != 'true')
			return;
		
		// display the javascript
		$this->view_preview();
		$this->view_javascript();
		$this->view_config();
		
		// hooking it up
		add_filter( 'mce_external_plugins', array(&$this, 'set_wysiwyg_js'));
		add_filter( "mce_buttons_{$this->widget['buttonrow']}", array(&$this, 'set_wysiwyg_button_callback'));
	}
	
	/**
	 * Method displays the administrative preview for this given widget
	 * 
	 */
	function view_preview()
	{
		// fail early, fail often
		if (!array_key_exists("{$this->_class}_Preview", $_GET))
			return;
		
		// sending the headers
		header("Content-type: text/html; charset=UTF-8");
		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header('Expires: ' . gmdate('D, d M Y H:i:s', strtotime('-1 years')) . ' GMT');
		
		// the actual PREVIEW code
		$this->widget(array(), $_GET);
		die();
	}
	
	/**
	 * Method displays the Administrative configurations for this shortcode
	 * 
	 */
	function view_config()
	{
		// fail early, fail often
		if (!array_key_exists("{$this->_class}_Config", $_GET))
			return;
		
		// sending the headers
		header("Content-type: text/html; charset=UTF-8");
		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header('Expires: ' . gmdate('D, d M Y H:i:s', strtotime('-1 years')) . ' GMT');
		
		// the actual HTML code
		?>
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<title><?php echo $this->widget['name']; ?></title>
			<link rel="stylesheet" href="<?php echo site_url('wp-includes/js/tinymce/themes/advanced/skins/wp_theme/dialog.css'); ?>">
			<script type="text/javascript" src="<?php echo site_url('/wp-includes/js/tinymce/tiny_mce_popup.js'); ?>"></script>
			<script type="text/javascript" src="<?php echo site_url('/wp-includes/js/tinymce/utils/mctabs.js'); ?>"></script>
		</head>
		<body onload="tinyMCEPopup.resizeToInnerSize();" role="application" dir="ltr" class="forceColors">
		<form onsubmit="tinyMCEPopup.restoreSelection();tinyMCEPopup.editor.<?php echo $this->_class; ?>_Command(this);tinyMCEPopup.close();return false;" 
			id="popup_form" action="#">
			<div class="tabs" role="tablist" tabindex="-1">
				<ul>
					<li id="general_tab" class="current" aria-controls="general_panel" role="tab" tabindex="0">
						<span><a href="javascript:mcTabs.displayTab('general_tab','general_panel');" 
						onmousedown="return false;" tabindex="-1">General</a></span>
					</li>
					<li id="preview_tab" aria-controls="preview_panel" role="tab" tabindex="-1">
						<span><a href="javascript:tinyMCEPopup.editor.<?php echo $this->_class; ?>_Preview(document.getElementById('popup_form'),document.getElementById('preview_inner_div'));mcTabs.displayTab('preview_tab','preview_panel');" 
						onmousedown="return false;" tabindex="-1">Preview</a></span>
					</li>
					<!-- IF YOU WANT TO IMPLEMENT A HELP TAB
					<li id="help_tab" aria-controls="help_panel" role="tab" tabindex="-1">
						<span><a href="javascript:mcTabs.displayTab('help_tab','help_panel');" 
						onmousedown="return false;" tabindex="-1">Help</a></span>
					</li> -->
				</ul>
			</div>
		
			<div class="panel_wrapper">
				<div id="general_panel" style="height:auto;" class="panel current">
					<fieldset>
						<legend>Widget Options</legend>
						<?php $this->form(array(),true); ?>
						
					</fieldset>
					<div style="clear:both;width:100%;"></div>
				</div>
				
				<div id="preview_panel" style="height:auto;" class="panel">
					<fieldset>
						<legend>Widget Preview</legend>
						<div id="preview_inner_div"></div>
					</fieldset>
					<div style="clear:both;width:100%;"></div>
				</div>
				
				<div id="help_panel" style="height:auto;" class="panel">
					<fieldset>
						<legend>Help</legend>
						
					</fieldset>
					<div style="clear:both;width:100%;"></div>
				</div>
		
			</div>
		
			<div class="mceActionPanel">
				<input type="submit" id="insert" name="insert" value="Insert">
				<input type="button" id="cancel" name="cancel" value="Cancel" onclick="tinyMCEPopup.close();">
			</div>
		</form>
		</body>
		</html>
		<?php 
		die();
	}
	
	/**
	 * Method displays the javascript for the shortcode
	 * 
	 */
	function view_javascript()
	{
		// fail early, fail often
		if (!array_key_exists("{$this->_class}_Javascript", $_GET))
			return;
		
		// sending the headers
		$expires = 60*60*24*14;
		header("Content-type: text/javascript");
		header("Pragma: public");
		header("Cache-Control: maxage=".$expires);
		header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$expires) . ' GMT');
		
		// the actual JS code
		?>
		(function() {
		
		tinymce.create('tinymce.plugins.<?php echo $this->_class; ?>', {
			mceTout : 0,
			init : function(editor, site_url) 
			{
				// Create the WYSIWYG button
				editor.addButton('<?php echo $this->_class; ?>', {
					title : '<?php echo $this->widget['name']; ?>',
					image : '<?php echo $this->widget['thumbnail']; ?>',
					onclick : function() {
						editor.windowManager.open({
							file : site_url + '/?<?php echo $this->_class; ?>_Config=true',
							width : (<?php echo $this->widget['width']; ?> + 30) + editor.getLang('example.delta_width', 0),
							height : (<?php echo $this->widget['height']; ?> + 35) + editor.getLang('example.delta_height', 0),
							inline : 1
						}, {
							plugin_url : site_url,
							some_custom_arg : '' // Custom argument
						});
					}
				});
				
				// The command to add the shortcode
				editor.<?php echo $this->_class; ?>_Command = function(form) {
					tinyMCEPopup.restoreSelection();
					
					var insert = '[<?php echo $this->_class ?> ';
					jQuery.each( jQuery(form).serializeArray(), function(k, f){
						insert += f.name + '="'+ f.value +'" ';
					});
					insert += ']';
					
					editor.execCommand('mceInsertContent', false, insert);
				};
				
				// Preview the shortcode/widget
				editor.<?php echo $this->_class; ?>_Preview = function(form, wrapper) {
					jQuery.get(site_url + '/?<?php echo $this->_class; ?>_Preview=true', 
						jQuery(form).serializeArray(),
						function(data){
							jQuery(wrapper).html(data);
						}
					);
				};
			},
			createControl : function(n, cm) {
				return null;
			},
			getInfo : function() {
				return {
					longname : "<?php echo $this->widget['name']; ?> Shortcode",
					author : 'RedRokk',
					authorurl : 'http://redrokk.com',
					infourl : 'http://redrokk.com',
					version : "1.0.0"
				};
			}
		});
		
		tinymce.PluginManager.add('<?php echo $this->_class; ?>', 
			tinymce.plugins.<?php echo $this->_class; ?>);
			
		})();
		<?php 
		die();
	}
	
	/**
	 * String to image
	 * 
	 * @var string
	 */
	var $screen_icon = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMIAAAApCAYAAABnYvZwAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBNYWNpbnRvc2giIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6ODlEQTM1RkE1MDMyMTFFMTk0QTJDOTc3MTEyQTk5MTUiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6ODlEQTM1RkI1MDMyMTFFMTk0QTJDOTc3MTEyQTk5MTUiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo4OURBMzVGODUwMzIxMUUxOTRBMkM5NzcxMTJBOTkxNSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo4OURBMzVGOTUwMzIxMUUxOTRBMkM5NzcxMTJBOTkxNSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PoZY1xkAACI6SURBVHja7F0HfFRV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%3D%3D";
	
	/**
	 * Adds links to the plugins action list
	 * 
	 * @param array $actions
	 */
	function set_plugin_links( $actions )
	{
		// Update the database for show options
		$key = "RedRokk_Plugin_Info";
		if (array_key_exists($key, $_GET)) {
			update_user_option(get_current_user_id(), $key, $_GET[$key], true);
		}
		
		// set the link for show options
		if (get_user_option($key, get_current_user_id())) {
			$actions['info'] = '<a href="'
				. site_url("/wp-admin/plugins.php?$key=0") 
				. '" alt="Display the widget information on this page">Show Info</a>';
		}
		else {
			$actions['info'] = '<a href="'
				. site_url("/wp-admin/plugins.php?$key=true") 
				. '" alt="Hide the widget information on this page">Hide Info</a>';
		}
	
		// update the database for show link
		$key = "{$this->_class}_Link";
		if (array_key_exists($key, $_GET)) {
			update_user_option(get_current_user_id(), $key, $_GET[$key], true);
		}
		
		// set the link for show link
		if (get_user_option($key, get_current_user_id())) {
			$actions['publiclink'] = '<a href="'
				. site_url("/wp-admin/plugins.php?$key=0") 
				. '" alt="Display the link to redrokk below the widget">Show Link</a>';
		}
		else {
			$actions['publiclink'] = '<a href="'
				. site_url("/wp-admin/plugins.php?$key=true") 
				. '" alt="Hide the link to redrokk below the widget">Hide Link</a>';
		}
		return $actions;
	}
	
	/**
	 * This is a tiny contribution to our cause, I thank you for leaving this intact.
	 * Please add your own support blurb, in addition to ours as you see fit.
	 * 
	 * @return string
	 */
	function get_plugin_support()
	{
		// initializing
		global $redrokk_display_once;
		
		if (!isset($redrokk_display_once) && !get_user_option("RedRokk_Plugin_Info", get_current_user_id()))
		{
			$redrokk_display_once = true;
			$title = "Red Rokk Interactive &trade;";
			$descriptionencode = urlencode($description);
	
			$description = "The WordPress Total Widget Control Plugin is an amazing plugin!";
			$descriptionencode = urlencode($description);
	
			$url = 'http://redrokk.com';
			$urlencode = urlencode($url);
	
			ob_start();
			?>
			<tr>
			<td><hr style="margin: 0 0 10px;border: none;border-bottom: 1px dashed #CCC;width:100%;clear:both"/>
				</td>
			<td>
				<hr style="margin: 0 0 10px;border: none;border-bottom: 1px dashed #CCC;width:100%;clear:both"/>
				<a href="<?php echo $url; ?>" style="text-decoration:none;">
					<img src="<?php echo $this->screen_icon; ?>" 
						height="41px" width="194px"
						style="margin:15px 20px 30px 0;position:relative;float:left;" />
				</a>
			</td>
			<td>
				<hr style="margin: 0 0 10px;border: none;border-bottom: 1px dashed #CCC;width:100%;clear:both"/>
				<div style="position:relative;float:right;width:200px;">
				<iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo $urlencode ?>&amp;layout=box_count&amp;show_faces=false&amp;width=50&amp;action=like&amp;colorscheme=light&amp;height=65" 
					scrolling="no"
					frameborder="0"
					style="border:none; overflow:hidden; width:50px; height:65px;margin-bottom: -5px;"
					allowTransparency="true" ></iframe>
				
				<a href="http://twitter.com/share" class="twitter-share-button" 
					data-url="<?php echo $url ?>" 
					data-text="<?php echo $description ?>" 
					data-count="vertical" 
					data-via="jonathonbyrd">
						Tweet</a>
				
				<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
				
				<a href="http://digg.com/submit?url=<?php echo $urlencode ?>&bodytext=Total%20Widget%20Control%20allows%20you%20to%20customize%20any%20and%20every%20page%20of%20your%20WordPress%20website."
					class="DiggThisButton DiggMedium">
					<img src="http://developers.diggstatic.com/sites/all/themes/about/img/digg-btn.jpg" 
					alt="Digg <?php echo $title; ?>" 
					title="Digg <?php echo $title; ?>" />
						<?php echo $title; ?></a>
				
				<script type="text/javascript">
				(function() {
				var s = document.createElement('SCRIPT'), s1 = document.getElementsByTagName('SCRIPT')[0];
				s.type = 'text/javascript';
				s.async = true;
				s.src = 'http://widgets.digg.com/buttons.js';
				s1.parentNode.insertBefore(s, s1);
				})();
				</script>
				
				</div>
				
			<p>
				<a href="<?php echo $url; ?>" style="text-decoration:none;">
					<h4 style="margin:20px 0 0px;"><?php echo $title; ?></h4>
				</a>
				<?php echo $title; ?> is a software development firm that accepts
				projects of all sizes. We contribute back to the community as much
				as possible by providing amazing plugins with exceptional API's for
				developers.
			</p>
			</td>
			</tr>
			<?php 
			echo ob_get_clean();
		}
	}
	
	/**
	 * Method adds our button to the WYSIWYG array of buttons
	 * 
	 * @params array $buttons
	 */
	function set_wysiwyg_button_callback( $buttons )
	{
		array_push($buttons, $this->_class);
		return $buttons;
	}
	
	/**
	 * Method adds the required Javascript to the WYSIWYG
	 * 
	 * @params array $scripts
	 */
	function set_wysiwyg_js( $scripts = array() )
	{
		$scripts[$this->_class] = site_url("/?{$this->_class}_Javascript=true");
		return $scripts;
	}

	/**
	 * Get the View file
	 * 
	 * Isolates the view file from the other variables and loads the view file,
	 * giving it the three parameters that are needed. This method does not
	 * need to be changed.
	 *
	 * @param array $widget
	 * @param array $params
	 * @param array $sidebar
	 */
	function getViewFile($widget, $params, $sidebar) {
		require $this->widget['view'];
	}

	/**
	 * 
	 * @param $params
	 */
	function set_defaults( $params = array() )
	{
		// initializing
		$defaults = array();
		
		foreach ((array)$this->widget['fields'] as $key => $field)
		{
			if (!isset($field['default'])) continue;
			$defaults[$field['id']] = $field['default'];
		}
		
		$params = wp_parse_args($params, $defaults);
		return $params;
	}
	
	/**
	 * Update the Administrative parameters
	 * 
	 * This function will merge any posted paramters with that of the saved
	 * parameters. This ensures that the widget options never get lost. This
	 * method does not need to be changed.
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array
	 */
	function update($new_instance, $old_instance)
	{
		// processes widget options to be saved
		$instance = wp_parse_args($new_instance, $old_instance);
		return $instance;
	}

}
endif;

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
	var $action;
	
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
		$this->action = isset($_REQUEST['action']) ? $_REQUEST['action'] : false;
		
		if ( isset($_GET['key']) )
			$this->action = 'resetpass';
		
		// validate action so as to default to the login screen
		if ( $this->action && !in_array($this->action, array('logoutconfirm', 'logout', 'lostpassword', 
		'retrievepassword', 'resetpass', 'rp', 'register', 'login','confirmation','checkemail'), true) 
		&& false === has_filter('login_form_' . $action) )
			$this->action = false;
		
		$this->login_id = get_option('redrokk_login_class::login_page_id', false);
		
		//hooks
		add_action('init', array($this, 'init'));
		//add_shortcode('redrokk_login_class', array($this, 'shortcode'));
		
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
		$http_post = ('POST' == $_SERVER['REQUEST_METHOD']);
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
	
	function getDisplay()
	{
		ob_start();
			$this->display();
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
			'css'		=> true
		));
		
		if ($a['primary']) {
			update_option('redrokk_login_class::login_page_id', get_queried_object_id());
			$this->login_id = get_queried_object_id();
		}
		
		$this->action = $this->action ?$this->action :$a['action'];
		
		// allow plugins to override the default actions, and to add extra actions if they want
		do_action( 'login_init' );
		do_action( 'login_form_' . $this->action );
		
		if ($a['css']) {
			?><style type="text/css" media="all"><?php echo $this->css(); ?></style>
			<div id="login" class="login">
			<?php  
		}
		
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
					<br />
					
					<input type="text" name="user_login" id="user_login" class="input" 
					value="<?php echo esc_attr($user_login); ?>" size="20" tabindex="10" />
				</label>
			</p>
			
			<?php do_action('lostpassword_form'); ?>
			<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to ); ?>" />
			
			<p class="submit">
				<input type="submit" name="wp-submit" id="wp-submit" class="button-primary" 
				value="<?php esc_attr_e('Get New Password'); ?>" tabindex="100" />
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
				<label for="pass1"><span class="login-label login-new-password"><?php _e('New password') ?></span><br />
				<input type="password" name="pass1" id="pass1" class="input" size="20" value="" autocomplete="off" /></label>
			</p>
			<p>
				<label for="pass2"><span class="login-label login-confirm-password"><?php _e('Confirm new password') ?></span><br />
				<input type="password" name="pass2" id="pass2" class="input" size="20" value="" autocomplete="off" /></label>
			</p>
		
			<div id="pass-strength-result" class="hide-if-no-js"><span class="login-label login-strength"><?php _e('Strength indicator'); ?></span></div>
			<p class="description indicator-hint"><?php _e('Hint: The password should be at least seven characters long. To make it stronger, use upper and lower case letters, numbers and symbols like ! " ? $ % ^ &amp; ).'); ?></p>
		
			<br class="clear" />
			<p class="submit">
				<input type="submit" name="wp-submit" id="wp-submit" class="button-primary" value="<?php esc_attr_e('Reset Password'); ?>" tabindex="100" />
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
		<form name="registerform" id="registerform" action="<?php echo esc_url( $this->getLoginUrl('register') ); ?>" method="post">
			<p>
				<label for="user_login">
					<span class="login-label login-username"><?php _e('Username') ?></span>
					<br />
					
					<input type="text" name="user_login" id="user_login" class="input" size="20" value="<?php echo esc_attr(stripslashes($user_login)); ?>" tabindex="10" />
				</label>
			</p>
			<p>
				<label for="user_email">
					<span class="login-label login-email"><?php _e('E-mail') ?></span>
					<br />
					
					<input type="email" name="user_email" id="user_email" class="input" size="25" value="<?php echo esc_attr(stripslashes($user_email)); ?>" tabindex="20" />
				</label>
			</p>
			<?php do_action('register_form'); ?>
			
			<p id="reg_passmail"><?php _e('A password will be e-mailed to you.') ?></p>
			<br class="clear" />
			
			<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to ); ?>" />
			<p class="submit">
				<input type="submit" name="wp-submit" id="wp-submit" class="button-primary register-button" value="<?php esc_attr_e('Register'); ?>" tabindex="100" />
			</p>
		</form>
		
		<p id="nav"> 
			<a class="login-login-link login-link-registration" href="<?php echo esc_url( $this->getLoginUrl() ); ?>">
				<?php _e( 'Log in' ); ?></a>
			
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
			$secure_cookie = '';
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
			
			if ($this->action == 'checkemail')
			{
				?><p class="login-success success">
					Check your email! We've sent you an authentication link, please click the link in order to reset your password.
				</p><?php 
			}
			?>
		<form name="loginform" id="loginform" action="<?php echo esc_url( $this->getLoginUrl() ); ?>" method="post">
			<p>
				<label for="user_login">
					<span class="login-label login-username">
						<?php _e('Username') ?>
					</span>
					<br />
					
					<input type="text" name="log" id="user_login" class="input" 
					value="<?php echo esc_attr($user_login); ?>" size="20" tabindex="10" />
				</label>
			</p>
			<p>
				<label for="user_pass">
					<span class="login-label login-password">
						<?php _e('Password') ?>
					</span>
					<br />
					
					<input type="password" name="pwd" id="user_pass" class="input" value="" 
					size="20" tabindex="20" />
				</label>
			</p>
			<?php do_action('login_form'); ?>
			
			<p class="forgetmenot">
				<label for="rememberme">
					<input name="rememberme" type="checkbox" id="rememberme" value="forever" 
					tabindex="90" <?php checked( $rememberme ); ?> />
					
					<span class="login-label login-rememberme">
						<?php esc_attr_e('Remember Me'); ?>
					</span>
				</label>
			</p>
			
			<p class="submit">
				<input type="submit" name="wp-submit" id="wp-submit" class="button-primary login-button" value="<?php esc_attr_e('Log In'); ?>" tabindex="100" />
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
				
				<a class="login-lostpw-link" href="<?php echo esc_url( $this->getLoginUrl('lostpassword') ); ?>" 
				title="<?php esc_attr_e( 'Password Lost and Found' ); ?>">
					<?php _e( 'Lost your password?' ); ?>
				</a>
				
			<?php else : ?>
				<a href="<?php echo esc_url( $this->getLoginUrl('lostpassword') ); ?>" 
				title="<?php esc_attr_e( 'Password Lost and Found' ); ?>">
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
		return add_query_arg('action', $action, wp_login_url());
	}
	
	/**
	 * Method returns this logout url
	 * 
	 * @param string $logout_url
	 * @param string $redirect
	 */
	function logout_url( $logout_url, $redirect )
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
		
		)); 
		
		$lc = redrokk_login_class::getInstance();
		return $lc->getDisplay();
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

/**
 * Actions and Filters
 *
 * Register any and all actions here. Nothing should actually be called
 * directly, the entire system will be based on these actions and hooks.
 */
add_action( 'widgets_init', create_function( '', 'register_widget("RedRokk_Login_Widget");' ) );
redrokk_login_class::getInstance();

register_nav_menu( 'redrokk-login-widget', 'Built In User Menu' );

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
	function html($widget, $params, $sidebar)
	{
		// initializing
		$params['primary'] = $params['primary'] == 'true' ?true :false;
		$params['css'] = $params['css'] == 'true' ?true :false;
		$params['transform'] = $params['transform'] == 'true' ?true :false;
		$titleKey = $this->action ?$this->action :$params['action'];
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
		}
	}
}

