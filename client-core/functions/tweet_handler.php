<?php
/**
 * Name: cnp_tweet_handler()
 *
 * Description: returned formatted Twitter feed.
 *
 * @access public
 * @param  int    $number  	The block to return.
 *
 **/

function  cnp_tweet_handler($maxtweets) {

	$handle = get_option('twitter_handle');
	$tweets = LEPI_get_tweets($maxtweets, $handle);

	if ( !isset($tweets) )
		return;

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
	return $output;
}
