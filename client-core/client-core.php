<?php

/*
	Plugin Name: Client Core
	Plugin URI: http://clarknikdelpowell.com
	Version: 2.0.0
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
define( 'SITE_LOCAL', ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1') );
define( 'SITE_PATH', plugin_dir_path(__FILE__) );
define( 'SITE_URL', SITE_LOCAL ? plugins_url().'/Client-Core/' : plugin_dir_url(__FILE__) );


/* Classes Required To Function */
require_once( SITE_PATH . 'class.ClientCore.php' );
require_once( SITE_PATH . 'class.Functions.php' );


/* 

Settings for Client Core: 

- options_page 			= 	Whether to generate an options page for CNP settings & ACF
- post_formats 			= 	Extra post formats to add
- custom_image_sizes 	= 	Custom image sizes to create 
- add_post_types 		= 	Array of post types to add (public function "register" can also be used to override default settings)
- remove_post_types 	= 	Array of pages to hide in the admin section

Feel free to remove option keys below that you don't need. They are just listed there for example.

*/

$settings = array(
	'options_page' => FALSE
,	'post_formats' => array(
		/*
		'video'
	,	'gallery'
		*/
	)
,	'custom_image_sizes' => array(
		/*
		'name' => array(
			'x' => 0
		,	'y' => 0
		,	'crop' => FALSE
		)
		*/
	)
,	'add_post_types' => array(
		/*
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
,	'add_taxonomies' => array(
		/*
		'name' => array('posts','pages')
		*/
	)
,	'remove_post_types' => array(
		/*
		'edit.php'
		*/
	)
,	'add_css' => array(
		/*
		'name' => 'filename.css'
		*/
	)
,	'add_js' => array(
		/*
		'name' => 'filename.css'
		*/
	)
);

/* Core Class Called (parent to all other classes) */
$ClientCore = new ClientCore($settings);

/*

Function Usage:

- "$ClientCore->do" 				is the parent object for all function calls.
- "$ClientCore->do->tweets" 		uses Lepidoptera to load the tweets markup.
- "$ClientCore->do->event_dates" 	uses Tzolkin to format event dates for display.
- "$ClientCore->do->search_excerpt" Loads the markup for searches in WordPress.
- "$ClientCore->do->post_header" 	Standardizes and unifies the markup for the postdata header.

*/