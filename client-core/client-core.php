<?
/*
    Plugin Name: Client Core
    Plugin URI: http://clarknikdelpowell.com
    Version: 0.1.0
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

////////////////////////////////////////////////////////////////////////////////
//  CONSTANTS  ////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////

// FIRST: do a find and replace for SITE with the site abbreviation.
// ALSO:  replace "Client" with client name.

define('SITE_LOCAL', ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1'));
define('SITE_PATH', plugin_dir_path(__FILE__));
define('SITE_URL', FDOC_LOCAL ? plugins_url().'/Client-Core/' : plugin_dir_url(__FILE__));
define('SITE_PRE', 'site_');

////////////////////////////////////////////////////////////////////////////////
// CSS: Admin Styles  /////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////

// Enqueue Admin Styles
//require_once(SITE_PATH.'css/enqueue_styles.php');


////////////////////////////////////////////////////////////////////////////////
// FUNCTIONS: content blocks, helper functions  ///////////////////////////////
//////////////////////////////////////////////////////////////////////////////

// Format Event Dates
//require_once(SITE_PATH.'functions/format_event_dates.php');

// Content Blocks
//require_once(SITE_PATH.'functions/tweet_handler.php');
//require_once(SITE_PATH.'functions/postdata_header.php');
//require_once(SITE_PATH.'functions/get_search_excerpt.php');


////////////////////////////////////////////////////////////////////////////////
// REGISTER: post types, meta boxes, sidebars  ////////////////////////////////
//////////////////////////////////////////////////////////////////////////////

// Post Types
//require_once(SITE_PATH.'register/news_post_type.php');

////////////////////////////////////////////////////////////////////////////////
// SETTINGS  //////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////

// Timezone
$tz_string = get_option('timezone_string');
if (!empty($tz_string)) {
	date_default_timezone_set($tz_string);
}

// Image Sizes
//require_once(SITE_PATH.'settings/add_image_sizes.php');

// Post Formats
//require_once(SITE_PATH.'settings/add_post_formats.php');

