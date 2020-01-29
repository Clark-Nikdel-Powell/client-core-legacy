<?php
/**
 * Taxonomy
 *
 * @package CNP
 */

namespace CNP;

/**
 * Taxonomy Class
 */
abstract class Taxonomy {

	/**
	 * Array of txonomy registration arguments.
	 *
	 * @var array
	 */
	public $arguments = array();

	/**
	 * Capabilities for this taxonomy.
	 *
	 * @var object
	 */
	public $capabilities = array();

	/**
	 * A short descriptive summary of what the taxonomy is for.
	 *
	 * @var string
	 */
	public $description = '';

	/**
	 * Whether the taxonomy is hierarchical.
	 *
	 * @var bool
	 */
	public $hierarchical = false;

	/**
	 * An array of labels for this taxonomy.
	 *
	 * @var array
	 */
	public $labels = array();

	/**
	 * The callback function for the meta box display.
	 *
	 * @var bool|callable
	 */
	public $meta_box_cb = null;

	/**
	 * The callback function for sanitizing taxonomy data saved from a meta box.
	 *
	 * @var callable
	 */
	public $meta_box_sanitize_cb = null;

	/**
	 * An array of object types this taxonomy is registered for.
	 *
	 * @var array
	 */
	public $object_type = null;

	/**
	 * Plural form of the post type name. Used for labels.
	 *
	 * @var string
	 */
	public $plural;

	/**
	 * Whether a taxonomy is intended for use publicly either via the admin interface or by front-end users.
	 *
	 * @var bool
	 */
	public $public = true;

	/**
	 * Whether the taxonomy is publicly queryable.
	 *
	 * @var bool
	 */
	public $publicly_queryable = null;

	/**
	 * Query var string for this taxonomy.
	 *
	 * @var string|false
	 */
	public $query_var;

	/**
	 * The base path for this taxonomy's REST API endpoints.
	 *
	 * @var string|bool
	 */
	public $rest_base = false;

	/**
	 * The controller for this taxonomy's REST API endpoints.
	 *
	 * Custom controllers must extend WP_REST_Controller.
	 *
	 * @var string|bool
	 */
	public $rest_controller_class = false;

	/**
	 * Rewrites information for this taxonomy.
	 *
	 * @var array|false
	 */
	public $rewrite = true;

	/**
	 * Whether to display a column for the taxonomy on its post type listing screens.
	 *
	 * @var bool
	 */
	public $show_admin_column = false;

	/**
	 * Whether to show the taxonomy in the admin menu.
	 *
	 * If true, the taxonomy is shown as a submenu of the object type menu. If false, no menu is shown.
	 *
	 * @var bool
	 */
	public $show_in_menu = null;

	/**
	 * Whether the taxonomy is available for selection in navigation menus.
	 *
	 * @var bool
	 */
	public $show_in_nav_menus = null;

	/**
	 * Whether to show the taxonomy in the quick/bulk edit panel.
	 *
	 * @var bool
	 */
	public $show_in_quick_edit = null;

	/**
	 * Whether this taxonomy should appear in the REST API.
	 *
	 * Default false. If true, standard endpoints will be registered with
	 * respect to $rest_base and $rest_controller_class.
	 *
	 * @var bool
	 */
	public $show_in_rest = false;

	/**
	 * Whether to list the taxonomy in the tag cloud widget controls.
	 *
	 * @var bool
	 */
	public $show_tagcloud = null;

	/**
	 * Whether to generate and allow a UI for managing terms in this taxonomy in the admin.
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
	 * Taxonomy key.
	 *
	 * @var string
	 */
	public $taxonomy;

	/**
	 * Function that will be called when the count is updated.
	 *
	 * @var callable
	 */
	public $update_count_callback = '';

	/**
	 * Taxonomy constructor
	 */
	public function __construct() {

		\add_action( 'init', array( $this, 'register' ) );

	}

	/**
	 * Register the taxonomy
	 */
	public function register() {

		\register_taxonomy( $this->taxonomy, $this->object_type, $this->set_args() );

	}

	/**
	 * Set taxonomy registration args
	 */
	private function set_args() {

		$args = array(
			'labels'                => $this->set_labels(),
			'description'           => $this->description,
			'public'                => $this->public,
			'publicly_queryable'    => $this->publicly_queryable,
			'hierarchical'          => $this->hierarchical,
			'show_ui'               => $this->show_ui,
			'show_in_menu'          => $this->show_in_menu,
			'show_in_nav_menus'     => $this->show_in_nav_menus,
			'show_tagcloud'         => $this->show_tagcloud,
			'show_in_quick_edit'    => $this->show_in_quick_edit,
			'show_admin_column'     => $this->show_admin_column,
			'meta_box_cb'           => $this->meta_box_cb,
			'meta_box_sanitize_cb'  => $this->meta_box_sanitize_cb,
			'capabilities'          => $this->capabilities,
			'rewrite'               => $this->rewrite,
			'query_var'             => $this->query_var,
			'update_count_callback' => $this->update_count_callback,
			'show_in_rest'          => $this->show_in_rest,
			'rest_base'             => $this->rest_base,
			'rest_controller_class' => $this->rest_controller_class,
		);

		return array_merge( $args, $this->arguments );
	}

	/**
	 * Set taxonomy labels
	 */
	private function set_labels() {

		$labels = array(
			'name'                       => $this->plural,
			'singular_name'              => $this->single,
			'menu_name'                  => $this->plural,
			'all_items'                  => sprintf( 'All %s', $this->plural ),
			'parent_item'                => sprintf( 'Parent %s', $this->single ),
			'parent_item_colon'          => sprintf( 'Parent %s:', $this->single ),
			'new_item_name'              => sprintf( 'New Type', $this->single ),
			'add_new_item'               => 'Add New',
			'edit_item'                  => sprintf( 'Edit %s', $this->single ),
			'update_item'                => sprintf( 'Update %s', $this->single ),
			'view_item'                  => sprintf( 'View %s', $this->single ),
			'separate_items_with_commas' => sprintf( 'Separate %s with commas', strtolower( $this->plural ) ),
			'add_or_remove_items'        => sprintf( 'Add or remove %s', strtolower( $this->plural ) ),
			'choose_from_most_used'      => 'Choose from the most used',
			'popular_items'              => sprintf( 'Popular %s', $this->plural ),
			'search_items'               => sprintf( 'Search %s', $this->plural ),
			'not_found'                  => 'Not Found',
			'no_terms'                   => sprintf( 'No %s', strtolower( $this->plural ) ),
			'items_list'                 => sprintf( '%s list', $this->plural ),
			'items_list_navigation'      => sprintf( '%s list navigation', $this->plural ),
		);

		return array_merge( $labels, $this->labels );
	}
}
