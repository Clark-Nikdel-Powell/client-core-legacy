<?php
/**
 * Career post type
 *
 * @package CNP
 */

namespace CNP;

/**
 * Career Class
 */
class Career extends Post_Type {

	/**
	 * Career constructor
	 */
	public function __construct() {

		$this->post_type           = 'career';
		$this->single              = 'Career';
		$this->plural              = 'Careers';
		$this->description         = 'Careers at Hopping Green & Sams';
		$this->hierarchical        = false;
		$this->public              = false;
		$this->show_ui             = true;
		$this->show_in_menu        = true;
		$this->menu_position       = 6;
		$this->menu_icon           = 'dashicons-clipboard';
		$this->show_in_admin_bar   = true;
		$this->show_in_nav_menus   = false;
		$this->show_in_rest        = false;
		$this->has_archive         = false;
		$this->exclude_from_search = true;
		$this->publicly_queryable  = false;
		$this->capability_type     = 'page';

		$this->labels = array(
			'name'                  => _x( 'Careers', 'Post Type General Name', 'cnp-core' ),
			'singular_name'         => _x( 'Career', 'Post Type Singular Name', 'cnp-core' ),
			'menu_name'             => __( 'Careers', 'cnp-core' ),
			'name_admin_bar'        => __( 'Career', 'cnp-core' ),
			'archives'              => __( 'Career Archives', 'cnp-core' ),
			'attributes'            => __( 'Career Attributes', 'cnp-core' ),
			'parent_item_colon'     => __( 'Parent Career:', 'cnp-core' ),
			'all_items'             => __( 'All Careers', 'cnp-core' ),
			'add_new_item'          => __( 'Add New Career', 'cnp-core' ),
			'add_new'               => __( 'Add New', 'cnp-core' ),
			'new_item'              => __( 'New Career', 'cnp-core' ),
			'edit_item'             => __( 'Edit Career', 'cnp-core' ),
			'update_item'           => __( 'Update Career', 'cnp-core' ),
			'view_item'             => __( 'View Career', 'cnp-core' ),
			'view_items'            => __( 'View Careers', 'cnp-core' ),
			'search_items'          => __( 'Search Careers', 'cnp-core' ),
			'not_found'             => __( 'Not found', 'cnp-core' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'cnp-core' ),
			'featured_image'        => __( 'Cover Image', 'cnp-core' ),
			'set_featured_image'    => __( 'Set cover image', 'cnp-core' ),
			'remove_featured_image' => __( 'Remove cover image', 'cnp-core' ),
			'use_featured_image'    => __( 'Use as cover image', 'cnp-core' ),
			'insert_into_item'      => __( 'Insert into career', 'cnp-core' ),
			'uploaded_to_this_item' => __( 'Uploaded to this career', 'cnp-core' ),
			'items_list'            => __( 'Career list', 'cnp-core' ),
			'items_list_navigation' => __( 'Career list navigation', 'cnp-core' ),
			'filter_items_list'     => __( 'Filter career list', 'cnp-core' ),
		);

		parent::__construct();

	}
}
