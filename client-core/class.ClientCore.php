<?php

/**
* Core Plugin for Client Website Creation
*
* @package 	ClientCore
* @author 	Clark Nidkel Powell
* @link 	http://www.clarknikdelpowell.com
* @version 	2.1
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
	,	'add_taxonomies' 		=> array()
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
		add_action( 'init', array( $this, 'add_taxonomies' ) );
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
	* @param 	string 		$plural 		The plural name of the post type
	* @param 	string 		$icon 			Optional class name of the dashicon to use
	* @param 	array 		$extargs 		Optional array of args to use when registering
	* @param 	array 		$extlables 		Optional array of labels to use when registering
	* @since 	1.0
	*/
	public function register_post_type( $name, $plural = '', $icon = '', $extsupports = array(), $extargs = array(), $extlabels = array() ) {
		
		if ( $icon === '' ) {
			$icon = 'dashicons-admin-post';
		}
		
		$proper_name = ucwords($name);
		$plural_proper_name = ucwords($plural);

		if ( $plural_proper_name === '' ) {
			$plural_proper_name = $proper_name;
		}

		$labels = array(
			'name' 					=> $proper_name
		,	'singular_name' 		=> $proper_name
		,	'plural_name' 			=> $plural_proper_name
		,	'add_new_item' 			=> 'Add New '.$proper_name
		,	'edit_item' 			=> 'Edit '.$proper_name
		,	'new_item' 				=> 'New '.$proper_name
		,	'view_item' 			=> 'View '.$proper_name
		,	'search_items' 			=> 'Search '.$plural_proper_name
		,	'not_found' 			=> 'No '.$plural_proper_name.' found.'
		,	'not_found_in_trash' 	=> 'No '.$plural_proper_name.' found in Trash.'
		,	'all_items' 			=> $plural_proper_name
		,	'menu_name' 			=> $plural_proper_name
		,	'name_admin_bar' 		=> $plural_proper_name
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
		,	'public' 				=> TRUE
		,	'publicly_queryable' 	=> TRUE
		,	'has_archive' 			=> TRUE
		,	'show_in_nav_menus' 	=> TRUE
		,	'menu_icon' 			=> $icon
		,	'hierarchical' 			=> TRUE
		,	'supports' 				=> $supports
		,	'menu_position' 		=> 10
		,	'show_in_menu'			 => TRUE
		);
		if ( !empty($extargs) ) {
			$args = array_merge($args,$extargs);
		}

		register_post_type($name, $args);
	}

	/**
	* Register function for wordpress taxonomies. Requires singular name and can optionally accept for, args, and labels
	*
	* @param 	string 		$name 			The name of the taxonomy (singular)
	* @param 	string 		$objects		Optional array of post_type's to use this taxonomy for
	* @param 	array 		$extargs 		Optional array of args to use when registering
	* @param 	array 		$extlables 		Optional array of labels to use when registering
	* @since 	1.0
	*/
	public function register_taxonomy( $name, $objects = array(), $extargs = array(), $extlabels = array() ) {
		$proper_name = ucwords($name);
		$labels = array(
			'name'             		 	=> $proper_name
		,	'singular_name'    		 	=> $proper_name
		,	'menu_name'        		 	=> $proper_name
		,	'all_items'        		 	=> 'All ' . $proper_name
		,	'edit_item'       		  	=> 'Edit ' . $proper_name
		,	'view_item' 				=> 'View ' . $proper_name
		,	'update_item'      		 	=> 'Update ' . $proper_name
		,	'add_new_item'     			=> 'Add New ' . $proper_name
		,	'new_item_name'     		=> 'New ' . $proper_name . ' Name'
		,	'search_items'      		=> 'Search ' . $proper_name
		,	'popular_items'     		=> 'Popular ' . $proper_name
		,	'add_or_remove_items' 		=> 'Add or Remove ' . $proper_name
		,	'choose_from_most_used' 	=> 'Most Used ' . $proper_name
		,	'not_found' => 'No ' . $proper_name . ' Found'
		);
		if ( !empty($extlabels) ) {
			$labels = array_merge($labels,$extlabels);
		}

		$args = array(
			'public' => TRUE
		,	'show_ui' => TRUE
		,	'show_in_nav_menus' => TRUE
		,	'show_tagcloud' => TRUE
		,	'meta_box_cb' => NULL
		,	'show_admin_column' => FALSE
		,	'hierarchical' => FALSE
		,	'update_count_callback' => NULL
		,	'rewrite' => TRUE
		,	'capabilities' => NULL
		,	'sort' => NULL
		,	'labels' => $labels
		);
		if ( !empty($extargs) ) {
			$args = array_merge($args,$extargs);
		}

		register_taxonomy( $name, $objects, $args );
	}

	/**
	* Action hook to loop through registering of post types. Uses add_post_types key in $settings array.
	*
	* @since 	1.0
	*/
	public function add_post_types() {
		$types = $this->settings['add_post_types'];
		$defaults = array(
			'plural' 	=> ''
		,	'icon' 		=> ''
		,	'supports' 	=> ''
		,	'args'		=> ''
		,	'labels'	=> ''
		);
		if ( is_array($types) && count($types) > 0 ) {
			foreach ( $types as $type ) {
				$type = array_merge($defaults,$type);
				if ( isset($type['name']) ) {
					$this->register_post_type($type['name'],$type['plural'],$type['icon'],$type['supports'],$type['args'],$type['labels']);
				}
			}
		}
	}

	/**
	* Action hook to loop through registering of taxonomies. Uses add_taxonomies key in $settings array.
	*
	* @since 	1.0
	*/
	public function add_taxonomies() {
		$taxonomies = $this->settings['add_taxonomies'];
		if ( is_array($taxonomies) && count($taxonomies) > 0 ) {
			foreach ( $taxonomies as $taxonomy=>$for ) {
				$this->register_taxonomy($taxonomy,$for);
			}
		}
	}


	/**
	* Action callback to loop through deregistering pages. Uses remove_post_types key in $settings array.
	*
	* @since 	1.0
	*/
	public function remove_post_types() {
		$pages = $this->settings['remove_post_types'];
		if ( is_array($pages) && count($pages) > 0 ) {
			foreach ( $pages as $page ) {
    			remove_menu_page($page);
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