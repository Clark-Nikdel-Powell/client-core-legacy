<?php
/**
 * Post Type Class
 *
 * Base class for registering WordPress post types.
 *
 * @package CNP
 */

namespace CNP;

/**
 * Post_Type Class
 */
class Post_Type {

	/**
	 * Whether to allow this post type to be exported.
	 *
	 * Default true.
	 *
	 * @var bool
	 */
	public $can_export = true;

	/**
	 * Post type capabilities.
	 *
	 * @var array
	 */
	public $capabilities = array();

	/**
	 * The string to use to build the read, edit, and delete capabilities.
	 *
	 * May be passed as an array to allow for alternative plurals when using this argument as a base to construct the capabilities, e.g. array('story', 'stories'). Default 'post'.
	 *
	 * @var string
	 */
	public $capability_type = 'post';

	/**
	 * Whether to delete posts of this type when deleting a user.
	 *
	 * If true, posts of this type belonging to the user will be moved to trash when then user is deleted.
	 * If false, posts of this type belonging to the user will *not* be trashed or deleted.
	 * If not set (the default), posts are trashed if post_type_supports( 'author' ).
	 * Otherwise posts are not trashed or deleted. Default null.
	 *
	 * @var bool
	 */
	public $delete_with_user = null;

	/**
	 * A short descriptive summary of what the post type is.
	 *
	 * @var string
	 */
	public $description = '';

	/**
	 * Whether to exclude posts with this post type from front end search results.
	 *
	 * Default is the opposite value of $public.
	 *
	 * @var bool
	 */
	public $exclude_from_search = null;

	/**
	 * Whether there should be post type archives, or if a string, the archive slug to use.
	 *
	 * Will generate the proper rewrite rules if $rewrite is enabled. Default false.
	 *
	 * @var bool|string
	 */
	public $has_archive = false;

	/**
	 * Whether the post type is hierarchical (e.g. page).
	 *
	 * Default false.
	 *
	 * @var bool
	 */
	public $hierarchical = false;

	/**
	 * An array of labels for this post type.
	 *
	 * @var array
	 */
	public $labels;

	/**
	 * Whether to use the internal default meta capability handling.
	 *
	 * Default false.
	 *
	 * @var bool
	 */
	public $map_meta_cap = false;

	/**
	 * The url to the icon to be used for this menu.
	 *
	 * Pass a base64-encoded SVG using a data URI, which will be colored to match the color scheme -- this should begin with 'data:image/svg+xml;base64,'. Pass the name of a Dashicons helper class to use a font icon, e.g. 'dashicons-chart-pie'. Pass 'none' to leave div.wp-menu-image empty so an icon can be added via CSS.
	 *
	 * Defaults to use the posts icon.
	 *
	 * @var string
	 */
	public $menu_icon = null;

	/**
	 * The position in the menu order the post type should appear.
	 *
	 * To work, $show_in_menu must be true. Default null.
	 *
	 * @var int
	 */
	public $menu_position = null;

	/**
	 * Post type key.
	 *
	 * @var string
	 */
	public $name;

	/**
	 * Plural form of the post type name. Used for labels.
	 *
	 * @var string
	 */
	public $plural;

	/**
	 * Whether a post type is intended for use publicly either via the admin interface or by front-end users.
	 *
	 * Default false.
	 *
	 * @var bool
	 */
	public $public = false;

	/**
	 * Whether queries can be performed on the front end for the post type as part of `parse_request()`.
	 *
	 * Endpoints would include:
	 * - `?post_type={post_type_key}`
	 * - `?{post_type_key}={single_post_slug}`
	 * - `?{post_type_query_var}={single_post_slug}`
	 *
	 * Default is the value of $public.
	 *
	 * @var bool
	 */
	public $publicly_queryable = null;

	/**
	 * Sets the query_var key for this post type.
	 *
	 * Defaults to $post_type key. If false, a post type cannot be loaded at `?{query_var}={post_slug}`.
	 * If specified as a string, the query `?{query_var_string}={post_slug}` will be valid.
	 *
	 * @var string|bool
	 */
	public $query_var;

	/**
	 * Provide a callback function that sets up the meta boxes for the edit form.
	 *
	 * Do `remove_meta_box()` and `add_meta_box()` calls in the callback. Default null.
	 *
	 * @var string
	 */
	public $register_meta_box_cb = null;

	/**
	 * The base path for this post type's REST API endpoints.
	 *
	 * @var string|bool
	 */
	public $rest_base;

	/**
	 * The controller for this post type's REST API endpoints.
	 *
	 * Custom controllers must extend WP_REST_Controller.
	 *
	 * @var string|bool
	 */
	public $rest_controller_class;

	/**
	 * Triggers the handling of rewrites for this post type.
	 *
	 * Defaults to true, using $post_type as slug.
	 *
	 * @var array|false $rewrite
	 */
	public $rewrite;

	/**
	 * Makes this post type available via the admin bar.
	 *
	 * Default is the value of $show_in_menu.
	 *
	 * @var bool
	 */
	public $show_in_admin_bar = null;

	/**
	 * Where to show the post type in the admin menu.
	 *
	 * To work, $show_ui must be true. If true, the post type is shown in its own top level menu. If false, no menu is shown. If a string of an existing top level menu (eg. 'tools.php' or 'edit.php?post_type=page'), the post type will be placed as a sub-menu of that.
	 *
	 * Default is the value of $show_ui.
	 *
	 * @var bool|string
	 */
	public $show_in_menu = null;

	/**
	 * Makes this post type available for selection in navigation menus.
	 *
	 * Default is the value $public.
	 *
	 * @var bool
	 */
	public $show_in_nav_menus = null;

	/**
	 * Whether to include the post type in the REST API. Set this to true for the post type to be available in the block editor. Default false.
	 *
	 * @var bool
	 */
	public $show_in_rest;

	/**
	 * Whether to generate and allow a UI for managing this post type in the admin.
	 *
	 * Default is the value of $public.
	 *
	 * @var bool
	 */
	public $show_ui = null;

	/**
	 * Singular form of the post type name. Used for labels.
	 *
	 * @var string
	 */
	public $single;

	/**
	 * Core feature(s) the post type supports. Serves as an alias for calling add_post_type_support() directly. Core features include 'title', 'editor', 'comments', 'revisions', 'trackbacks', 'author', 'excerpt', 'page-attributes', 'thumbnail', 'custom-fields', and 'post-formats'. Additionally, the 'revisions' feature dictates whether the post type will store revisions, and the 'comments' feature dictates whether the comments count will show on the edit screen. A feature can also be specified as an array of arguments to provide additional information about supporting that feature.
	 *
	 * Default array('title', 'editor').
	 *
	 * @var array
	 */
	public $supports;

	/**
	 * An array of taxonomy identifiers that will be registered for the post type.
	 *
	 * Taxonomies can be registered later with `register_taxonomy()` or `register_taxonomy_for_object_type()`.
	 *
	 * Default empty array.
	 *
	 * @var array
	 */
	public $taxonomies = array();

	/**
	 * Post_Type constructor
	 *
	 * @param string $post_type Post type key.
	 * @param array  $labels Array of post type labels.
	 */
	public function __construct( $post_type, $labels = array() ) {

		$this->post_type = $post_type;
		$this->labels    = $this->set_labels( $labels );

		\add_action( 'init', array( $this, 'register' ) );

	}

	/**
	 * Register the post type
	 */
	public function register() {

		\register_post_type( $this->post_type, $this->set_args() );

	}

	/**
	 * Set post type labels
	 *
	 * @param array $labels Post type label settings.
	 */
	private function set_labels( $labels ) {

		$default_labels = array(
			'name'                  => $this->plural,
			'singular_name'         => $this->single,
			'menu_name'             => $this->plural,
			'name_admin_bar'        => $this->single,
			'archives'              => sprintf( '%s Archives', $this->single ),
			'attributes'            => sprintf( '%s Attributes', $this->single ),
			'parent_item_colon'     => sprintf( 'Parent %s:', $this->single ),
			'all_items'             => sprintf( 'All %s', $this->plural ),
			'add_new_item'          => sprintf( 'Add New %s', $this->single ),
			'add_new'               => 'Add New',
			'new_item'              => sprintf( 'New %s', $this->single ),
			'edit_item'             => sprintf( 'Edit %s', $this->single ),
			'update_item'           => sprintf( 'Update %s', $this->single ),
			'view_item'             => sprintf( 'View %s', $this->single ),
			'view_items'            => sprintf( 'View %s', $this->plural ),
			'search_items'          => sprintf( 'Search %s', $this->plural ),
			'not_found'             => 'Not found',
			'not_found_in_trash'    => 'Not found in Trash',
			'featured_image'        => 'Featured Image',
			'set_featured_image'    => 'Set featured image',
			'remove_featured_image' => 'Remove featured image',
			'use_featured_image'    => 'Use as featured image',
			'insert_into_item'      => sprintf( 'Insert into %s', strtolower( $this->single ) ),
			'uploaded_to_this_item' => sprintf( 'Uploaded to this %s', strtolower( $this->single ) ),
			'items_list'            => sprintf( '%s list', $this->single ),
			'items_list_navigation' => sprintf( '%s list navigation', $this->single ),
			'filter_items_list'     => sprintf( 'Filter %s list', strtolower( $this->single ) ),
		);

		return array_merge( $default_labels, $labels );
	}

	/**
	 * Set post type registration args
	 */
	private function set_args() {

		return array(
			'labels'                => $this->labels,
			'description'           => $this->description,
			'public'                => $this->public,
			'hierarchical'          => $this->hierarchical,
			'exclude_from_search'   => $this->exclude_from_search,
			'publicly_queryable'    => $this->publicly_queryable,
			'show_ui'               => $this->show_ui,
			'show_in_menu'          => $this->show_in_menu,
			'show_in_nav_menus'     => $this->show_in_nav_menus,
			'show_in_admin_bar'     => $this->show_in_admin_bar,
			'menu_position'         => $this->menu_position,
			'menu_icon'             => $this->menu_icon,
			'capability_type'       => $this->capability_type,
			'capabilities'          => $this->capabilities,
			'map_meta_cap'          => $this->map_meta_cap,
			'supports'              => $this->supports,
			'register_meta_box_cb'  => $this->register_meta_box_cb,
			'taxonomies'            => $this->taxonomies,
			'has_archive'           => $this->has_archive,
			'rewrite'               => $this->rewrite,
			'query_var'             => $this->query_var,
			'can_export'            => $this->can_export,
			'delete_with_user'      => $this->delete_with_user,
			'show_in_rest'          => $this->show_in_rest,
			'rest_base'             => $this->rest_base,
			'rest_controller_class' => $this->rest_controller_class,
		);
	}
}
