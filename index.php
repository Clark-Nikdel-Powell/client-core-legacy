<?php
/**
 * Client Core Plugin.
 *
 * @link              https://cnpagency.com
 * @since             2.0.0
 * @package           CNP
 *
 * @wordpress-plugin
 * Plugin Name:       Client Core
 * Plugin URI:        https://cnpagency.com
 * Description:       Core functionality plugin.
 * Version:           2.0.0
 * Author:            CNP
 * Author URI:        https://cnpagency.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cnp-core
 * Domain Path:       /languages
 */

use CNP\Career;
use CNP\Practice_Area;
use CNP\Scripts;

define( 'CLIENT_CORE_VERSION', '2.0.0' );

/**
 * Base classes.
 *
 * Comment out those libraries that are not needed.
 */
require_once 'includes/class-post-type.php';
require_once 'includes/class-shortcode.php';
require_once 'includes/class-taxonomy.php';


// Fixes.
require_once 'includes/class-scripts.php';
$cnp_scripts = new Scripts();


// Post types.
require_once 'includes/class-career.php';
$cnp_career = new Career();

require_once 'includes/class-practice-area.php';
$cnp_practice_area = new Practice_Area();

// Taxonomies.

// Shortcodes.
