<?
function cnp_format_event_dates($event) {

	$dates = tz_get_event_dates($event->ID);
	$now = new DateTime(current_time('mysql'), new DateTimeZone(get_option('timezone_string')));

	$today = $dates['start'] < $now && $dates['end'] > $now;
	$all_day = tz_event_is_all_day($event->ID);
	$same_day = tz_event_is_same_day($event->ID);

	$time = '';
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

	return $time;
}
