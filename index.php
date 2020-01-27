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

use CNP\Careers;

define( 'CLIENT_CORE_VERSION', '2.0.0' );

require_once 'includes/class-post-type.php';
require_once 'includes/class-careers.php';
$careers = new Careers();
