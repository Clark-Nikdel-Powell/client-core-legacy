<?php

/*
The point of this function is to standardize and unify the markup for the postdata header, whether
you're on a singular post, an archive page, a search page, a taxonomy archive, and so on.

There are several blocks built out currently. You may not need all of them, so modify it as necessary.
*/

function cnp_postdata_header($post, $options=array()) {

	// Variables Setup
	$defaults = array(
		'cat_bar' => false
	,	'meta_bar' => true
	,	'post_thumbnail' => true
	,	'like_follow' => false
	);

	$vars = wp_parse_args( $options, $defaults );
	$id = $post->ID;


	/*//////////////////////////////////////////////////////////////////////////////
	// CONTENT HEADER  ////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////*/


	/*//////////////////////////////////////////////////////
	//  CATEGORY BAR  /////////////////////////////////////
	////////////////////////////////////////////////////*/

	// Description: displays adjacent posts in the same category.
	// Requires: "news-category" taxonomy
	// Displays on: News Singular
	$cat_bar = '';
	if ( is_singular('news') && $vars['cat_bar'] == true ) {

	$category = get_the_terms( $post, 'news-category' );
	if ( !empty($category) ) {

		$category = array_shift($category);
		$cat_name = $category->name;

		// Set up the previous/next post links
		$prev_cat_article = get_adjacent_post( true, '', true, 'news-category' );
		$next_cat_article = get_adjacent_post( true, '', false, 'news-category' );
		$cat_nav = '';
		if (!empty($prev_cat_article) || !empty($next_cat_article)) {
			$cat_nav .= '<div class="more"><label class="hide-on-tbsm">More '. $cat_name . (strrchr($cat_name,' ') != " News" ? ' News' : '' ) .':</label> <nav>';
			if (!empty($prev_cat_article)) {$cat_nav .= '<a class="prev" href="'. get_permalink($prev_cat_article->ID) .'" title="'. $prev_cat_article->post_title .'"><i class="fa fa-chevron-left"></i></a>';}
			if (!empty($next_cat_article)) {$cat_nav .= '<a class="next" href="'. get_permalink($next_cat_article->ID) .'" title="'. $next_cat_article->post_title .'"><i class="fa fa-chevron-right"></i></a>';}
			$cat_nav .= '</nav>';
		}

		$cat_bar = '<header class="category"><a class="block-title" href="'. get_term_link($category) .'">'. $cat_name .'</a> '. $cat_nav .'</header>';
	}

	}


	/*//////////////////////////////////////////////////////
	// Title  /////////////////////////////////////////////
	////////////////////////////////////////////////////*/

	if ( !is_singular() ) {
		$title = '<h3 class="title"><a href="'. get_the_permalink() .'">'. get_the_title() .'</a></h3>';
	}
	else {
		$title = '<h1 class="title">'. get_the_title() .'</h1>';
	}


	/*//////////////////////////////////////////////////////
	//  Meta Bar  /////////////////////////////////////////
	////////////////////////////////////////////////////*/

	// Description: Displays author avatar & name and post date
	// Requires: nothing
	// Optional: WP User Avatar
	// Displays on: News Singular
	$meta_bar = '';
	if ( is_singular('news') && $vars['meta_bar'] === true ) {

	if ( function_exists('get_wp_user_avatar') ) {
		$avatar = '<span class="image">'. get_wp_user_avatar() .'</span>';
	} else {
		$avatar = '';
	}

	$author = '<div class="author">'. $avatar .'<a href="'. get_author_posts_url($post->post_author) .'" class="name">'. get_the_author() .'</a></div>';
	$date = '<div class="date"><i class="fa fa-clock-o"></i><span class="time">'. get_the_date('g:i a T') .'&nbsp;&nbsp;</span><span class="calendar-date">'. get_the_date('F j, Y') .'</span></div>';

	$meta_bar = '<footer class="meta">'. $author . $date .'</footer>';

	}
	$date = '';
	if ( !is_singular() ) { $date = fdoc_date('n/j', $id); }


	/*//////////////////////////////////////////////////////
	// Post Excerpt  //////////////////////////////////////
	////////////////////////////////////////////////////*/

	// Requires: cnp_get_search_excerpt()
	// Displays on: Everywhere
	$excerpt = '';
	(is_search() ? $excerpt = '<p class="summary">'. cnp_get_search_excerpt() .'</p>' : $excerpt = '');
	if ( has_excerpt() ) {
		$excerpt = '<p class="summary">'. get_the_excerpt() .'</p>';
	}


	/*//////////////////////////////////////////////////////
	// Post Thumbnail  ////////////////////////////////////
	////////////////////////////////////////////////////*/

	// Requires: nothing
	// Displays on: Singular
	$thumbnail = '';
	if ( has_post_thumbnail() && is_singular() && $vars['post_thumbnail'] === true ) {

		$post_thumbnail = get_post(get_post_thumbnail_id($id));
		$img = get_the_post_thumbnail( $id, 'large' );
		$caption = '';
		if (!empty($post_thumbnail->post_excerpt) && is_singular()) {$caption = '<p class="wp-caption-text">'. $post_thumbnail->post_excerpt .'</p>';}
		$thumbnail = '<div class="post-thumbnail"><div class="inside">'. $img . $caption .'</div></div>';
	}


	/*//////////////////////////////////////////////////////
	//  Like/Follow  //////////////////////////////////////
	////////////////////////////////////////////////////*/

	// Description: displays like/follow buttons.
	// Requires: Lepidoptera
	// Displays on: Singular
	if ( function_exists('LEPI_get_tw_button') && $vars['like_follow'] === true ) {
		if ( is_singular() && $post->post_type == 'news' ) {
			$actions_nav = '<nav class="actions"><h5 class="nav-title">Follow Florida Citrus!</h5> <div class="follow">'. LEPI_get_tw_button('type=follow') . LEPI_get_fb_button() .'</div></nav>';
		}
	}


	/*//////////////////////////////////////////////////////
	// Output  ////////////////////////////////////////////
	////////////////////////////////////////////////////*/

	$header = '<div class="postdata">'. $cat_bar .'<div class="inside">'. $date . $title . $meta_bar . $excerpt .'</div>'. $thumbnail . $actions_nav .'</div>';

	echo $header;
}