<?
add_action( 'admin_init', 'client_core_admin_stylesheet' );

function client_core_admin_stylesheet() {
	wp_enqueue_style( 'client-core-admin-styles', plugins_url('/style.css' , __FILE__) );
}
