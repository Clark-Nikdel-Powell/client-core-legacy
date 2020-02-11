<?php
/**
 * Scripts
 *
 * @package CNP
 */

namespace CNP;

/**
 * Scripts class
 */
class Scripts {

	/**
	 * Whether or not to defer script loading
	 *
	 * @var bool
	 */
	public $defer_scripts = true;

	/**
	 * Whther or not to apply the jQuery fix
	 *
	 * @var bool
	 */
	public $jquery_fix = true;

	/**
	 * Version of jQuery to enqueue
	 *
	 * @var string
	 */
	public $jquery_version = '3.4.0';

	/**
	 * Scripts constructor
	 */
	public function __construct() {

		\add_action( 'init', array( $this, 'conditional_hooks' ) );

	}

	/**
	 * Conditional WordPress actions and filters
	 */
	public function conditional_hooks() {

		if ( $this->defer_scripts && ! \is_admin() ) {
			\add_filter( 'script_loader_tag', array( $this, 'filter_defer_scripts' ), 101, 3 );
		}

		if ( $this->jquery_fix && ! \is_admin() && ! \is_customize_preview() ) {
			\add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_jquery' ), 1 );
		}

	}

	/**
	 * Add defer attribute to all script tags
	 *
	 * @param string $tag    The `<script>` tag for the enqueued script.
	 * @param string $handle The script's registered handle.
	 * @param string $src    The script's source URL.
	 */
	public function filter_defer_scripts( $tag, $handle, $src ) {

		return sprintf( '<%2$s src="%1$s" defer></%2$s>' . "\n", $src, 'script' );
	}

	/**
	 * Enqueue jquery
	 *
	 * Lighthouse audits will return a "vulnerabilities detected" issue because
	 * WordPress continues to use jQuery version 1.12.4. This class replaces
	 * this vulnerable version with a more recent, updated version of jQuery.
	 *
	 * @see https://core.trac.wordpress.org/ticket/37110
	 * @see https://snyk.io/vuln/npm:jquery?lh=1.12.4
	 */
	public function enqueue_jquery() {

		\wp_deregister_script( 'jquery' );
		\wp_deregister_script( 'jquery-core' );
		\wp_deregister_script( 'jquery-migrate' );

		$jquery_url = sprintf( 'https://code.jquery.com/jquery-%s.min.js', $this->jquery_version );

		\wp_register_script( 'jquery-core', $jquery_url, array(), $this->jquery_version, true );
		\wp_register_script( 'jquery', false, array( 'jquery-core' ), $this->jquery_version, false );

		\wp_enqueue_script( 'jquery' );

	}
}
