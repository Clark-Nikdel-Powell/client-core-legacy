<?php

/**
* Core Plugin for Client Website Creation
*
* @package 	ClientCore
* @author 	Clark Nidkel Powell
* @link 	http://www.clarknikdelpowell.com
* @version 	2.0
* @license 	http://opensource.org/licenses/gpl-license.php GNU Public License
*/
class ClientCore {

	/**
	* Public Variables (retreived using $ClientCore->var)
	*
	* @var 	string 	$timezone 		Time zone string retreived from the WordPress Database
	* @var 	array 	$settings 	 	Array of user defined settings
	*
	*/
	public $do 			= null;
	public $timezone 	= '';
	public $settings 	= array(
		'post_formats' 			=> array()
	,	'custom_image_sizes' 	=> array()
	,	'options_page' 			=> FALSE
	,	'add_post_types' 		=> array()
	,	'remove_post_types' 	=> array()
	,	'add_css' 				=> array()
	,	'add_js' 				=> array()
	);

	
	/**
	* Initial constructor. Occurrs at instantiation.
	*
	* @param 	array 	$settings 	User defined settings
	* @since 	1.0
	*/
	public function __construct($settings) {
		$this->settings = array_merge($this->settings,$settings);
		$this->set_timezone();
		$this->set_image_sizes();
		$this->set_post_formats();
		$this->hook_wordpress();
		$this->options();
		if ( class_exists('ClientCore_Functions') ) {
			$this->do = new ClientCore_Functions();
		}
		return;
	}

	/**
	* Retrieves the timezone string and sets the timezone variable.
	* Called in __construct()
	*
	* @since 	1.0
	*/
	public function set_timezone() {
		$this->timezone = get_option('timezone_string');
		return;
	}

	/**
	* Hooks used for wordpress (actions, filters)
	*
	* @since 	1.0
	*/
	public function hook_wordpress() {
		add_action( 'admin_menu', array( $this, 'remove_post_types' ) );
		add_action( 'init' , array( $this, 'add_post_types' ) );
		add_action( 'admin_init', array( $this, 'css' ) );
		add_action( 'admin_init', array( $this, 'js' ) );
	}

	/**
	* Registers custom image sizes using the custom_image_sizes key in $settings array.
	*
	* @since 	1.0
	*/
	public function set_image_sizes() {
		$sizes = $this->settings['custom_image_sizes'];
		if ( is_array($sizes) && count($sizes) > 0 ) {
			foreach ( $sizes as $name=>$size ) {
				if ( is_numeric($name) ) {
					$name = 'custom-size-' . $name;
				}
				add_image_size( $name, $size['x'], $size['y'], $size['crop'] );
			}
		}
		return;
	}

	/**
	* Sets the custom post formats for this site. Uses post_formats key in $settings array.
	* 
	* @since 	1.0
	*/
	public function set_post_formats() {
		$formats = $this->settings['post_formats'];
		if ( is_array($formats) && count($formats)>0 ) {
			add_theme_support( 'post-formats', $formats );
		}
		return;
	}

	/**
	* Creates the options page for advanced custom fields if options_page key in $settings array is true.
	*
	* @since 	1.0
	*/
	public function options() {
		if( function_exists('acf_add_options_page') && $this->settings['options_page'] === TRUE ) {
			acf_add_options_page(array(
				'page_title' 	=> 'CNP Settings',
				'menu_title'	=> 'CNP Settings',
				'menu_slug' 	=> 'cnp-settings',
				'capability'	=> 'activate_plugins',
				'icon_url'      => 'dashicons-wordpress',
				'redirect'		=> false
			));
		}
	}

	/**
	* Register function for wordpress post types. Requires singular name, but will accept icon, labels, and args arrays for customization.
	*
	* @param 	string 		$name 			The name of the post type (singular)
	* @param 	string 		$icon 			Optional class name of the dashicon to use
	* @param 	array 		$extargs 		Optional array of args to use when registering post type
	* @param 	array 		$extlables 		Optional array of labels to use when registering post type
	* @since 	1.0
	*/
	public function register( $name, $icon = '', $extargs = array(), $extlabels = array(), $extsupports = array() ) {
		if ( $icon === '' ) {
			$icon = 'dashicons-admin-post';
		}
		$proper_name = ucwords($name);
		$labels = array(
			'name' 					=> $proper_name
		,	'singular_name' 		=> $proper_name
		,	'plural_name' 			=> $proper_name.'s'
		,	'add_new_item' 			=> 'Add New '.$proper_name
		,	'edit_item' 			=> 'Edit '.$proper_name
		,	'new_item' 				=> 'New '.$proper_name
		,	'view_item' 			=> 'View '.$proper_name
		,	'search_items' 			=> 'Search '.$proper_name.'s'
		,	'not_found' 			=> 'No '.$proper_name.'s found.'
		,	'not_found_in_trash' 	=> 'No '.$proper_name.'s found in Trash.'
		,	'all_items' 			=> $proper_name.'s'
		,	'menu_name' 			=> $proper_name.'s'
		,	'name_admin_bar' 		=> $proper_name.'s'
		);
		if ( !empty($extlabels) ) {
			$labels = array_merge($labels,$extlabels);
		}

		$supports = array(
			'title'
		,	'editor'
		,	'revisions'
		,	'thumbnail'
		);
		if ( !empty($extsupports) ) {
			if ( isset($extsupports['none']) && $extsupports['none'] === FALSE ) {
				$supports = array();
			}
			else {
				$supports = $extsupports;
			}
		}

		$args = array(
			 'labels' 				=> $labels
			,'public' 				=> true
			,'publicly_queryable' 	=> true
			,'has_archive' 			=> false
			,'show_in_nav_menus' 	=> true
			,'menu_icon' 			=> $icon
			,'hierarchical' 		=> false
			,'supports' 			=> $supports
			,'menu_position' 		=> 10
			,'show_in_menu'			 => true
		);
		if ( !empty($extargs) ) {
			$args = array_merge($args,$extargs);
		}

		register_post_type($name, $args);
	}

	/**
	* Action hook to loop through registering of post types. Uses add_post_types key in $settings array.
	*
	* @since 	1.0
	*/
	public function add_post_types() {
		$types = $this->settings['add_post_types'];
		if ( is_array($types) && count($types) > 0 ) {
			foreach ( $types as $name=>$dashicon ) {
				$this->register($name,$dashicon);
			}
		}
	}

	/**
	* Action callback to loop through deregistering pages. Uses remove_post_types key in $settings array.
	*
	* @since 	1.0
	*/
	public function remove_post_types() {
		$types = $this->settings['remove_post_types'];
		if ( is_array($types) && count($types) > 0 ) {
			foreach ( $types as $type ) {
    			remove_menu_page($type);
    		}
    	}
	}

	/**
	* Action callback to loop through enqueuing style sheets in the admin area. Uses add_css key in $settings array.
	*
	* @since 	1.0
	*/
	public function css() {
		$stylesheets = $this->settings['add_css'];
		if ( is_array($stylesheets) && count($stylesheets) > 0 ) {
			foreach ( $stylesheets as $name=>$stylesheet ) {
				wp_enqueue_style( $name, SITE_URL . '/css/' . $stylesheet );
			}
		}
	}

	/**
	* Action callback to loop through enqueuing javacript in the admin area. Uses add_js key in $settings array.
	*
	* @since 	1.0
	*/
	public function js() {
		$scripts = $this->settings['add_js'];
		if ( is_array($scripts) && count($scripts) > 0 ) {
			foreach ( $scripts as $name=>$script ) {
				wp_enqueue_script( $name, SITE_URL . '/js/' . $script, array('jquery'), null, TRUE );
			}
		}
	}
}