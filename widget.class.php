<?php
/**
 * @package WordPress
 * @subpackage Empty Widget Class
 * @version 0.5
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
			    'name'    => 'Select box',
				'desc' => '',
			    'id'      => 'select_id',
			    'type'    => 'select',
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
	abstract function html($widget = array(), $params = array(), $sidebar = array());
	
	/*          *****************************************
	 *         NO NEED TO MODIFY ANYTHING BELOW THIS POINT
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
            'before_widget'     => '',
            'before_title'         => '',
            'after_title'         => '',
            'after_widget'         => '',
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
					@$meta = esc_attr($instance[$id]);
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
								><?php echo trim($meta); ?></textarea>
							
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
