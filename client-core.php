<?php

/*
	Plugin Name: Client Core
	Plugin URI: http://clarknikdelpowell.com
	Version: 2.1.0
	Description: Core functionality for a client site
	Author: Glenn Welser, Sam Mello & Josh Nederveld
	Author URI: http://clarknikdelpowell.com/agency/people/

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
define( 'SITE_LOCAL', ( $_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1' ) );
define( 'SITE_PATH', plugin_dir_path( __FILE__ ) );
define( 'SITE_URL', SITE_LOCAL ? plugins_url() . '/Client-Core/' : plugin_dir_url( __FILE__ ) );


/* Classes Required To Function */
require_once( SITE_PATH . 'class.ClientCore.php' );
require_once( SITE_PATH . 'class.ClientPosts.php' );

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

$settings = array(
	'options_pages'      => [
		'cnp_settings'       => [
			'display'    => true,
			'page_title' => 'CNP Settings',
			'menu_title' => 'CNP Settings',
			'menu_slug'  => 'cnp-settings',
			'capability' => 'activate_plugins',
			'icon_url'   => 'dashicons-wordpress',
			'redirect'   => false
		],
		'slideshow_settings' => [
			'display'    => false,
			'page_title' => 'Slideshow Settings',
			'menu_title' => 'Slideshow Settings',
			'menu_slug'  => 'cnp-slideshow-settings',
			'capability' => 'activate_plugins',
			'icon_url'   => 'dashicons-images-alt2',
			'redirect'   => false
		]
	]
,
	'post_formats'       => array(/*
		'video'
	,	'gallery'
		*/
	)
,
	'custom_image_sizes' => array(/*
		'name' => array(
			'x' => 0
		,	'y' => 0
		,	'crop' => FALSE
		)
		*/
	)
,
	'add_post_types'     => array(/*
		array(
			'name' => 'news'				required
		,	'plural' => 'newsies'			optional
		,	'icon' => 'dashicons'			optional
		,	'supports' => array('title')	optional
		,	'labels' => array()				optional
		,	'args' => array()				optional
		)
		*/
	)
,
	'add_taxonomies'     => array(/*
		'name' => array('posts','pages')
		*/
	)
,
	'remove_post_types'  => array(/*
		'edit.php'
		*/
	)
,
	'add_css'            => array(/*
		'name' => 'filename.css'
		*/
	)
,
	'add_js'             => array(/*
		'name' => 'filename.css'
		*/
	)
,
	'p2p_connections'    => array(/*		array(
			'name' => '',
			'from' => '',
			'to' => '',
			'reciprocal' => TRUE,
			'sortable' => 'any',
			'title' => array( 'from' => '', 'to' => '' )
		)*/
	)
);

/* Core Class Called (parent to all other classes) */
$ClientCore = new ClientCore( $settings );

$posts_settings = array(
	'post_meta' => array(/*
        'meta_field',
        'meta_field_2'
         */
	)
);

$ClientPosts = new ClientPosts( $posts_settings );
