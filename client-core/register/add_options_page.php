<?php
if( function_exists('acf_add_options_page') ) {

	acf_add_options_page(array(
		'page_title' 	=> 'CNP Settings',
		'menu_title'	=> 'CNP Settings',
		'menu_slug' 	=> 'cnp-settings',
		'capability'	=> 'activate_plugins',
		'icon_url'      => 'dashicons-wordpress',
		'redirect'		=> false
	));

}