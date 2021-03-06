<?php

/**
 * Core Plugin for Client Website Creation
 *
 * @package    ClientCore
 * @author    Clark Nidkel Powell
 * @link    http://www.clarknikdelpowell.com
 * @version    2.1
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 */
class ClientCore {

	/**
	 * Public Variables (retreived using $ClientCore->var)
	 *
	 * @var    string $timezone Time zone string retreived from the WordPress Database
	 * @var    array $settings Array of user defined settings
	 *
	 */
	public $do = null;
	public $timezone = '';
	public $settings = [
		'post_formats'       => array(),
		'custom_image_sizes' => array(),
		'options_pages'      => array(),
		'add_taxonomies'     => array(),
		'add_post_types'     => array(),
		'remove_post_types'  => array(),
		'add_css'            => array(),
		'add_js'             => array(),
	];


	/**
	 * Initial constructor. Occurs at instantiation.
	 *
	 * @param    array $settings User defined settings
	 *
	 * @since    1.0
	 */
	public function __construct( $settings ) {
		$this->settings = array_merge( $this->settings, $settings );
		$this->set_timezone();
		$this->set_image_sizes();
		$this->set_post_formats();
		$this->hook_wordpress();
		$this->options();

		if ( class_exists( 'ClientCore_Functions' ) ) {
			$this->do = new ClientCore_Functions();
		}

		return;
	}

	/**
	 * Retrieves the timezone string and sets the timezone variable.
	 * Called in __construct()
	 *
	 * @since    1.0
	 */
	public function set_timezone() {
		$this->timezone = get_option( 'timezone_string' );

		return;
	}

	/**
	 * Hooks used for wordpress (actions, filters)
	 *
	 * @since    1.0
	 */
	public function hook_wordpress() {
		add_action( 'admin_menu', [ $this, 'remove_post_types' ] );
		add_action( 'init', [ $this, 'add_taxonomies' ] );
		add_action( 'init', [ $this, 'add_post_types' ] );
		add_action( 'init', [ $this, 'add_sidebars' ] );
		add_action( 'admin_init', [ $this, 'css' ] );
		add_action( 'admin_init', [ $this, 'js' ] );
	}

	/**
	 * Registers custom image sizes using the custom_image_sizes key in $settings array.
	 *
	 * @since    1.0
	 */
	public function set_image_sizes() {

		$sizes = $this->settings['custom_image_sizes'];

		if ( is_array( $sizes ) && count( $sizes ) > 0 ) {
			foreach ( $sizes as $name => $size ) {
				if ( is_numeric( $name ) ) {
					$name = 'custom-size-' . $name;
				}
				add_image_size( $name, $size['x'], $size['y'], $size['crop'] );
			}
		}

		return true;
	}

	/**
	 * Sets the custom post formats for this site. Uses post_formats key in $settings array.
	 *
	 * @since    1.0
	 */
	public function set_post_formats() {

		$formats = $this->settings['post_formats'];

		if ( is_array( $formats ) && count( $formats ) > 0 ) {
			add_theme_support( 'post-formats', $formats );
		}

		return;
	}

	/**
	 * Creates the options page for advanced custom fields if options_page key in $settings array is true.
	 *
	 * @since    1.0
	 */
	public function options() {

		if ( empty( $this->settings['options_pages'] ) ) {
			return false;
		}

		if ( ! function_exists( 'acf_add_options_page' ) ) {
			return false;
		}

		foreach ( $this->settings['options_pages'] as $options_page ) {

			if ( false === $options_page['display'] ) {
				continue;
			}

			acf_add_options_page( [
				'page_title' => $options_page['page_title'],
				'menu_title' => $options_page['menu_title'],
				'menu_slug'  => $options_page['menu_slug'],
				'capability' => $options_page['capability'],
				'icon_url'   => $options_page['icon_url'],
				'redirect'   => $options_page['redirect'],
			] );
		}
	}

	/**
	 * Register function for wordpress taxonomies. Requires singular name and can optionally accept for, args, and labels
	 *
	 * @param    string $name The name of the taxonomy (singular)
	 * @param    array $objects Optional array of post_types to use this taxonomy for
	 * @param    array $extargs Optional array of args to use when registering
	 * @param    array $extlabels Optional array of labels to use when registering
	 *
	 * @since    1.0
	 */
	public function register_taxonomy( $name, $objects = array(), $extargs = array(), $extlabels = array() ) {

		$proper_name = ucwords( $name );

		$labels = [
			'name'                  => $proper_name,
			'singular_name'         => $proper_name,
			'menu_name'             => $proper_name,
			'all_items'             => 'All ' . $proper_name,
			'edit_item'             => 'Edit ' . $proper_name,
			'view_item'             => 'View ' . $proper_name,
			'update_item'           => 'Update ' . $proper_name,
			'add_new_item'          => 'Add New ' . $proper_name,
			'new_item_name'         => 'New ' . $proper_name . ' Name',
			'search_items'          => 'Search ' . $proper_name,
			'popular_items'         => 'Popular ' . $proper_name,
			'add_or_remove_items'   => 'Add or Remove ' . $proper_name,
			'choose_from_most_used' => 'Most Used ' . $proper_name,
			'not_found'             => 'No ' . $proper_name . ' Found',
		];

		if ( ! empty( $extlabels ) ) {
			$labels = array_merge( $labels, $extlabels );
		}

		$args = [
			'public'                => true,
			'show_ui'               => true,
			'show_in_nav_menus'     => true,
			'show_tagcloud'         => true,
			'meta_box_cb'           => null,
			'show_admin_column'     => false,
			'hierarchical'          => false,
			'update_count_callback' => null,
			'rewrite'               => true,
			'sort'                  => null,
			'labels'                => $labels,
		];

		if ( ! empty( $extargs ) ) {
			$args = array_merge( $args, $extargs );
		}

		register_taxonomy( $name, $objects, $args );
	}

	/**
	 * Register function for wordpress post types. Requires singular name, but will accept icon, labels, and args arrays for customization.
	 *
	 * @param    string $name The name of the post type (singular)
	 * @param    string $plural The plural name of the post type
	 * @param    string $icon Optional class name of the dashicon to use
	 * @param    array $extargs Optional array of args to use when registering
	 * @param    array $extlabels Optional array of labels to use when registering
	 *
	 * @since    1.0
	 */
	public function register_post_type( $name, $plural = '', $icon = '', $extsupports = array(), $extargs = array(), $extlabels = array() ) {

		if ( '' === $icon ) {
			$icon = 'dashicons-admin-post';
		}

		$proper_name        = ucwords( $name );
		$plural_proper_name = ucwords( $plural );

		if ( '' === $plural_proper_name ) {
			$plural_proper_name = $proper_name;
		}

		$labels = [
			'name'               => $proper_name,
			'singular_name'      => $proper_name,
			'plural_name'        => $plural_proper_name,
			'add_new_item'       => 'Add New ' . $proper_name,
			'edit_item'          => 'Edit ' . $proper_name,
			'new_item'           => 'New ' . $proper_name,
			'view_item'          => 'View ' . $proper_name,
			'search_items'       => 'Search ' . $plural_proper_name,
			'not_found'          => 'No ' . $plural_proper_name . ' found.',
			'not_found_in_trash' => 'No ' . $plural_proper_name . ' found in Trash.',
			'all_items'          => $plural_proper_name,
			'menu_name'          => $plural_proper_name,
			'name_admin_bar'     => $plural_proper_name,
		];
		if ( ! empty( $extlabels ) ) {
			$labels = array_merge( $labels, $extlabels );
		}

		$supports = [
			'title',
			'editor',
			'revisions',
			'thumbnail',
		];

		if ( ! empty( $extsupports ) ) {

			if ( isset( $extsupports['none'] ) && false === $extsupports['none'] ) {
				$supports = array();
			} else {
				$supports = $extsupports;
			}
		}

		$args = [
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'has_archive'        => true,
			'show_in_nav_menus'  => true,
			'menu_icon'          => $icon,
			'hierarchical'       => true,
			'supports'           => $supports,
			'menu_position'      => 10,
			'show_in_menu'       => true,
		];

		if ( ! empty( $extargs ) ) {
			$args = array_merge( $args, $extargs );
		}

		register_post_type( $name, $args );
	}

	public function register_sidebar( $args ) {

		$defaults = [
			'name'          => '',
			'id'            => '',
			'description'   => '',
			'class'         => '',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widgettitle">',
			'after_title'   => '</h3>',
		];

		$vars = wp_parse_args( $args, $defaults );

		register_sidebar( $vars );

	}

	/**
	 * Action hook to loop through registering of taxonomies. Uses add_taxonomies key in $settings array.
	 *
	 * @since    1.0
	 */
	public function add_taxonomies() {

		$taxonomies = $this->settings['add_taxonomies'];

		if ( is_array( $taxonomies ) && count( $taxonomies ) > 0 ) {

			foreach ( $taxonomies as $taxonomy_slug => $taxonomy_args ) {

				$custom_args = '';
				if ( isset( $taxonomy_args['args'] ) && ! empty( $taxonomy_args['args'] ) ) {
					$custom_args = $taxonomy_args['args'];
				}

				$custom_labels = '';
				if ( isset( $taxonomy_args['labels'] ) && ! empty( $taxonomy_args['labels'] ) ) {
					$custom_labels = $taxonomy_args['labels'];
				}

				$this->register_taxonomy( $taxonomy_slug, $taxonomy_args['objects'], $custom_args, $custom_labels );
			}
		}
	}

	/**
	 * Action hook to loop through registering of post types. Uses add_post_types key in $settings array.
	 *
	 * @since    1.0
	 */
	public function add_post_types() {

		$types = $this->settings['add_post_types'];

		$defaults = [
			'plural'   => '',
			'icon'     => '',
			'supports' => '',
			'args'     => '',
			'labels'   => '',
		];

		if ( is_array( $types ) && count( $types ) > 0 ) {
			foreach ( $types as $type ) {
				$type = array_merge( $defaults, $type );
				if ( isset( $type['name'] ) ) {
					$this->register_post_type( $type['name'], $type['plural'], $type['icon'], $type['supports'], $type['args'], $type['labels'] );
				}
			}
		}
	}


	public function add_sidebars() {

		$sidebars = $this->settings['add_sidebars'];

		if ( is_array( $sidebars ) && count( $sidebars ) > 0 ) {

			foreach ( $sidebars as $sidebar_slug => $sidebar_args ) {
				$this->register_sidebar( $sidebar_args );
			}
		}
	}


	/**
	 * Action callback to loop through de-registering pages. Uses remove_post_types key in $settings array.
	 *
	 * @since    1.0
	 */
	public function remove_post_types() {

		$pages = $this->settings['remove_post_types'];

		if ( is_array( $pages ) && count( $pages ) > 0 ) {
			foreach ( $pages as $page ) {
				remove_menu_page( $page );
			}
		}
	}

	/**
	 * Action callback to loop through enqueuing style sheets in the admin area. Uses add_css key in $settings array.
	 *
	 * @since    1.0
	 */
	public function css() {

		$stylesheets = $this->settings['add_css'];

		if ( is_array( $stylesheets ) && count( $stylesheets ) > 0 ) {
			foreach ( $stylesheets as $name => $stylesheet ) {
				wp_enqueue_style( $name, SITE_URL . '/css/' . $stylesheet );
			}
		}
	}

	/**
	 * Action callback to loop through enqueuing javacript in the admin area. Uses add_js key in $settings array.
	 *
	 * @since    1.0
	 */
	public function js() {

		$scripts = $this->settings['add_js'];

		if ( is_array( $scripts ) && count( $scripts ) > 0 ) {
			foreach ( $scripts as $name => $script ) {
				wp_enqueue_script( $name, SITE_URL . '/js/' . $script, [ 'jquery' ], null, true );
			}
		}
	}
}
