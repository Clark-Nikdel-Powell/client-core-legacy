<?
if ( function_exists( 'add_image_size' ) ) {
	add_image_size( 'small', 400, 9999 ); // 400 pixels wide (and unlimited height)
	add_image_size( 'small-cropped', 400, 267, true ); // (cropped)
	add_image_size( 'medium-cropped', 600, 400, true ); // (cropped)
}