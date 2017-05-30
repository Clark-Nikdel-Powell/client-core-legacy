<?php
/*
	Plugin Name: Client Core
	Plugin URI: https://cnpagency.com
	Version: 2.1.0
	Description: Core functionality for a client site
	Author: Glenn Welser & Josh Nederveld
	Author URI: https://cnpagency.com/people/

	Copyright 2014+ Clark/Nikdel/Powell

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2 (or later),
	as published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


/* Global Variables */
define( 'SITE_LOCAL', ( 'localhost' === $_SERVER['SERVER_NAME']  || '127.0.0.1' === $_SERVER['SERVER_NAME'] ) );
define( 'SITE_PATH', plugin_dir_path( __FILE__ ) );
define( 'SITE_URL', SITE_LOCAL ? plugins_url() . '/Client-Core/' : plugin_dir_url( __FILE__ ) );


/* Classes Required To Function */
require_once( SITE_PATH . 'class.clientcore.php' );
require_once( SITE_PATH . 'class.clientposts.php' );

/*

Settings for Client Core:

- options_page 			= 	Whether to generate an options page for CNP settings & ACF
- post_formats 			= 	Extra post formats to add
- custom_image_sizes 	= 	Custom image sizes to create
- add_post_types 		= 	Array of post types to add (public function "register" can also be used to override default settings)
- add_taxonomies        =   Array of taxonomies to add
- remove_post_types 	= 	Array of pages to hide in the admin section
- add CSS               =   Admin-specific CSS to add
- add JS                =   Admin-specific JS to add
- p2p_connections       =   Post 2 Post connections registration - @see https://github.com/scribu/wp-posts-to-posts/wiki/p2p_register_connection_type

Feel free to remove option keys below that you don't need. They are just listed there for example.

*/

$settings = [
	'options_pages'      => [
		'cnp_settings'       => [
			'display'    => true,
			'page_title' => 'CNP Settings',
			'menu_title' => 'CNP Settings',
			'menu_slug'  => 'cnp-settings',
			'capability' => 'activate_plugins',
			'icon_url'   => 'dashicons-wordpress',
			'redirect'   => false,
		],
		'slideshow_settings' => [
			'display'    => false,
			'page_title' => 'Slideshow Settings',
			'menu_title' => 'Slideshow Settings',
			'menu_slug'  => 'cnp-slideshow-settings',
			'capability' => 'activate_plugins',
			'icon_url'   => 'dashicons-images-alt2',
			'redirect'   => false,
		],
	],
	'post_formats'       => [
		/*'video',
		'gallery',*/
	],
	'custom_image_sizes' => [
		/*'2-1_large' => [
			'x'    => 1000,
			'y'    => 500,
			'crop' => true,
		],*/
	],
	'add_post_types'     => [
		/*[
			'name'     => 'news',
			'plural'   => 'news',
			'icon'     => 'dashicons-media-document',
			'supports' => [ 'title', 'excerpt', 'editor', 'thumbnail' ],
			'labels'   => [
				'menu_name'     => 'Company News',
				'singular_name' => 'Article'
			],
			'args'     => [
				'menu_position' => 5,
				'hierarchical'  => false,
			],
		],*/
	],
	'add_taxonomies'     => [
		'media-category' => [
			'objects' => [ 'attachment' ],
			'args'    => [
				'show_admin_column'     => true,
				'hierarchical'          => true,
				'update_count_callback' => '_update_generic_term_count',
			],
			'labels'  => [
				'name'                  => 'Media Categories',
				'singular_name'         => 'Media Category',
				'menu_name'             => 'Media Categories',
				'all_items'             => 'All Media Categories',
				'edit_item'             => 'Edit Media Category',
				'view_item'             => 'View Media Category',
				'update_item'           => 'Update Media Category',
				'add_new_item'          => 'Add Media Category',
				'new_item_name'         => 'New Category Name',
				'search_items'          => 'Search Media Categories',
				'popular_items'         => 'Popular Media Categories',
				'add_or_remove_items'   => 'Add or Remove Media Category',
				'choose_from_most_used' => 'Most Used Media Categories',
				'not_found'             => 'No Media Categories Found',
			],
		],
	],
	'remove_post_types'  => [
		/*'edit.php',*/
	],
	'add_sidebars'       => [
		/*'blog' => [
			'name'         => __( 'Blog Sidebar', 'theme_text_domain' ),
			'id'           => 'blog-sidebar',
			'before_title' => '<h4 class="widget__title">',
			'after_title'  => '</h4>',
		],*/
	],
	'add_css'            => [
		/*'name' => 'filename.css',*/
	],
	'add_js'             => [
		/*'name' => 'filename.css',*/
	],
	'p2p_connections'    => [
		/*[
			'name' => '',
			'from' => '',
			'to' => '',
			'reciprocal' => TRUE,
			'sortable' => 'any',
			'title' => [ 'from' => '', 'to' => '' ]
		],*/
	],
];

/* Core Class Called (parent to all other classes) */
$client_core = new ClientCore( $settings );

$posts_settings = [
	'post_meta' => [
		/*'meta_field',
        'meta_field_2',*/
	],
];

//$client_posts = new ClientPosts( $posts_settings );
