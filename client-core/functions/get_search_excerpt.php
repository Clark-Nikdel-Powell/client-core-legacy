<?php
function cnp_get_search_excerpt() {

$ancestor = highest_ancestor();
$s        = get_query_var('s');
$key      = esc_html($s, 1);

// shorten content around the query
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

// highlight the query in the shortened content without losing capitalization
preg_match_all("/$key+/i", $shortened, $matches);
if (is_array($matches[0]) && count($matches[0]) >= 1) {
	foreach ($matches[0] as $match) {
		$shortened = str_replace($match, '<b class="red">'.$match.'</b>', $shortened);
	}
}
$excerpt = $before.$shortened.$after;

return $excerpt;
}
