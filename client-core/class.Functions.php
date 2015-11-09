<?php

/**
* Functions class for ClientCore
*
* @package 	ClientCore
* @author 	Clark Nidkel Powell
* @link 	http://www.clarknikdelpowell.com
* @version 	2.1
* @license 	http://opensource.org/licenses/gpl-license.php GNU Public License
*/
class ClientCore_Functions {

	/**
	* Takes event dates from Tzolkin and formats them.
	*
	* @param 	int 	$event 		The event ID to look up.
	* @since 	2.0
	*/
	public function event_dates( $event ) {
		$time = FALSE;
		if ( function_exists('tz_get_event_dates') ) {
			$dates = tz_get_event_dates($event->ID);
			$now = new DateTime(current_time('mysql'), new DateTimeZone($this->timezone));

			$today = $dates['start'] < $now && $dates['end'] > $now;
			$all_day = tz_event_is_all_day($event->ID);
			$same_day = tz_event_is_same_day($event->ID);

			if ($all_day && $same_day) { //All Day
				$time = $dates['start']->format('M j, Y').' - All Day';
			} elseif ($today && $same_day) { // Now - 1:45 PM
				$time = sprintf(
					 'Now - %s'
					,$dates['end']->format('g:i A')
				);
			} elseif ($all_day) { // Mon, Jan 13 - Fri, Jan 18
				$time = sprintf(
					 '%s - %s'
					,$dates['start']->format('D, M j, Y')
					,$dates['end']->format('D, M j, Y')
				);
			} elseif ($same_day) { // 11:05 AM - 1:45 PM
				$time = sprintf(
					 '%s - %s'
					,$dates['start']->format('g:i A')
					,$dates['end']->format('g:i A')
				);
			} else {
				$time = sprintf( // Mon, Jan 13 @ 11:05 AM - Fri, Jan 18 @ 1:45 PM
					 '%s - %s'
					,$dates['start']->format('D, M j, Y @ g:i A')
					,$dates['end']->format('D, M j, Y @ g:i A')
				);
			}
		}
		return $time;
	}

	/**
	* Uses Lepidoptera to retrevie tweets and format them into markup.
	*
	* @param 	int 	$maxtweets 		The maximum # of tweets to show.
	* @since 	2.0
	*/
	public function tweets( $maxtweets = 10 ) {
		$output = FALSE;
		if ( function_exists('LEPI_get_tweets') ) {
			$handle = get_option('twitter_handle');
			$tweets = LEPI_get_tweets($maxtweets, $handle);

			if ( is_array($tweets) && count($tweets) > 0 ) {
				
				$tweets_list = '';
				foreach ( $tweets as $key => $tweet ) {
					$current_time = current_time('timestamp');
					$timestamp    = $tweet['timestamp'];
					$time         = $current_time - $timestamp;

					// If the time from is greater than one day, display the date.
					if ( ($time/3600) > 24 ) {
						$format = date('M j', $timestamp);
					} else {
						$format = cnp_human_timing($timestamp) .' ago';
					}

					$icon    = '<i class="fa fa-twitter"></i>';
					$img     = '<div class="image"><img src="'. str_replace("_normal", "", $tweet['profile_img']) .'" /></div>';
					$time    = '<time class="time" datetime="'. date('Y-m-d', $timestamp) .'">'. $format .'</time>';
					$footer  = '<h4 class="handle"><a href="http://www.twitter.com/'. $handle .'">@'. $handle .'</a> '. $time .'</h4>';
					$text    = '<div class="text"><p class="tweet-text">'. $tweet['text'] .'</p>'. $footer .'</div>';
					$tweets_list .= '<div class="tweet">'. $icon . $img . $text .'</div>';
				}
				$output = '<div class="sidebar-block twitter">'. $tweets_list .'</div>';
			}
		}
		return $output;
	}

	/**
	* Loads the markup for searches in WordPress
	*
	* @since 	2.0
	*/
	public function search_excerpt() {
		$ancestor 	= highest_ancestor();
		$s 			= get_query_var('s');
		$key		= esc_html($s, 1);

		$charsBefore = 100;
		$charsTotal = 250;
		$content = strip_shortcodes(strip_tags(get_the_content()));
		$content = preg_replace('/\b(https?):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', '', $content);
		$content = preg_replace('|www\.[a-z\.0-9]+|i', '', $content);
		$position = stripos($content, $key);
		if ($position < $charsBefore) {
			$position = 0;
			$before = "";
		} else {
			$position = $position-$charsBefore;
			$before = "&hellip; ";
		}
		if (($position+$charsTotal) > strlen($content)) {
			$length = strlen($content) - $position;
			$after = "";
		} else {
			$length = $charsTotal;
			$after = " &hellip;";
		}
		$shortened = substr($content, $position, $length);
		preg_match_all("/$key+/i", $shortened, $matches);
		if (is_array($matches[0]) && count($matches[0]) >= 1) {
			foreach ($matches[0] as $match) {
				$shortened = str_replace($match, '<strong class="highlighted">'.$match.'</strong>', $shortened);
			}
		}
		$excerpt = $before.$shortened.$after;
		return $excerpt;
	}

	/**
	* The point of this function is to standardize and unify the markup for the postdata header, whether you're on a singular post, an archive page, a search page, a taxonomy archive, and so on. There are several blocks built out currently. You may not need all of them, so modify it as necessary.
	*
	* @since 	2.0
	*/
	public function post_header( $post, $options=array() ) {
		// Variables Setup
		$defaults = array(
			'cat_bar' => false
		,	'meta_bar' => true
		,	'post_thumbnail' => true
		,	'like_follow' => false
		);

		$vars = wp_parse_args( $options, $defaults );
		$id = $post->ID;


		/*
		CATEGORY BAR 

		Description: displays adjacent posts in the same category.
		Requires: "news-category" taxonomy
		Displays on: News Singular
		*/
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


		/*
		Title
		*/
		if ( !is_singular() ) {
			$title = '<h3 class="title"><a href="'. get_the_permalink() .'">'. get_the_title() .'</a></h3>';
		}
		else {
			$title = '<h1 class="title">'. get_the_title() .'</h1>';
		}


		/*
		Meta Bar

		Description: Displays author avatar & name and post date
		Requires: nothing
		Optional: WP User Avatar
		Displays on: News Singular
		*/
		$meta_bar = '';
		$date = '';
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


		/*
		Post Excerpt

		Requires: cnp_get_search_excerpt()
		Displays on: Everywhere
		*/
		$excerpt = '';
		(is_search() ? $excerpt = '<p class="summary">'. cnp_get_search_excerpt() .'</p>' : $excerpt = '');
		if ( has_excerpt() ) {
			$excerpt = '<p class="summary">'. get_the_excerpt() .'</p>';
		}


		/*
		Post Thumbnail

		Requires: nothing
		Displays on: Singular
		*/
		$thumbnail = '';
		if ( has_post_thumbnail() && is_singular() && $vars['post_thumbnail'] === true ) {

			$post_thumbnail = get_post(get_post_thumbnail_id($id));
			$img = get_the_post_thumbnail( $id, 'large' );
			$caption = '';
			if (!empty($post_thumbnail->post_excerpt) && is_singular()) {$caption = '<p class="wp-caption-text">'. $post_thumbnail->post_excerpt .'</p>';}
			$thumbnail = '<div class="post-thumbnail"><div class="inside">'. $img . $caption .'</div></div>';
		}


		/*
		Like/Follow

		Description: displays like/follow buttons.
		Requires: Lepidoptera
		Displays on: Singular
		*/
		$actions_nav = '';
		if ( function_exists('LEPI_get_tw_button') && $vars['like_follow'] === true ) {
			if ( is_singular() && $post->post_type == 'news' ) {
				$actions_nav = '<nav class="actions"><h5 class="nav-title">Follow Florida Citrus!</h5> <div class="follow">'. LEPI_get_tw_button('type=follow') . LEPI_get_fb_button() .'</div></nav>';
			}
		}


		/*
		Output
		*/
		$header = '<div class="postdata">'. $cat_bar .'<div class="inside">'. $date . $title . $meta_bar . $excerpt .'</div>'. $thumbnail . $actions_nav .'</div>';

		echo $header;
	}
}