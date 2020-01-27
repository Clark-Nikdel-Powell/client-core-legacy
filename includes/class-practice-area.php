<?php
/**
 * Careers post type
 *
 * @package CNP
 */

namespace CNP;

/**
 * Careers Class
 */
class Practice_Area extends Post_Type {

	/**
	 * Careers constructor
	 */
	public function __construct() {

		$this->name                = 'practice-area';
		$this->single              = _x( 'Practice Area', 'Post Type Singular Name', 'cnp-core' );
		$this->plural              = _x( 'Practice Areas', 'Post Type General Name', 'cnp-core' );
		$this->description         = 'Practice Areas at Hopping Green & Sams';
		$this->hierarchical        = true;
		$this->public              = true;
		$this->show_ui             = true;
		$this->show_in_menu        = true;
		$this->menu_position       = 6;
		$this->menu_icon           = 'dashicons-book';
		$this->show_in_admin_bar   = true;
		$this->show_in_nav_menus   = true;
		$this->show_in_rest        = true;
		$this->has_archive         = false;
		$this->exclude_from_search = false;
		$this->publicly_queryable  = true;
		$this->capability_type     = 'page';

		$this->arguments = array(
			'has_post_type_page'            => true,
			'post_type_page_disable_editor' => false,
		);

		$this->labels = array(
			'name'                  => _x( 'Practice Areas', 'Post Type General Name', 'hgs-core' ),
			'singular_name'         => _x( 'Practice Area', 'Post Type Singular Name', 'hgs-core' ),
			'menu_name'             => __( 'Practice Areas', 'hgs-core' ),
			'name_admin_bar'        => __( 'Practice Area', 'hgs-core' ),
			'archives'              => __( 'Practice Area Archives', 'hgs-core' ),
			'attributes'            => __( 'Practice Area Attributes', 'hgs-core' ),
			'parent_item_colon'     => __( 'Parent Practice Area:', 'hgs-core' ),
			'all_items'             => __( 'All Practice Areas', 'hgs-core' ),
			'add_new_item'          => __( 'Add New Practice Area', 'hgs-core' ),
			'add_new'               => __( 'Add New', 'hgs-core' ),
			'new_item'              => __( 'New Practice Area', 'hgs-core' ),
			'edit_item'             => __( 'Edit Practice Area', 'hgs-core' ),
			'update_item'           => __( 'Update Practice Area', 'hgs-core' ),
			'view_item'             => __( 'View Practice Area', 'hgs-core' ),
			'view_items'            => __( 'View Practice Areas', 'hgs-core' ),
			'search_items'          => __( 'Search Practice Areas', 'hgs-core' ),
			'not_found'             => __( 'Not found', 'hgs-core' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'hgs-core' ),
			'featured_image'        => __( 'Featured Image', 'hgs-core' ),
			'set_featured_image'    => __( 'Set featured image', 'hgs-core' ),
			'remove_featured_image' => __( 'Remove featured image', 'hgs-core' ),
			'use_featured_image'    => __( 'Use as featured image', 'hgs-core' ),
			'insert_into_item'      => __( 'Insert into Practice Area', 'hgs-core' ),
			'uploaded_to_this_item' => __( 'Uploaded to Practice Area', 'hgs-core' ),
			'items_list'            => __( 'Practice Area list', 'hgs-core' ),
			'items_list_navigation' => __( 'Practice Area list navigation', 'hgs-core' ),
			'filter_items_list'     => __( 'Filter Practice Area list', 'hgs-core' ),
		);

		parent::__construct();

	}
}
