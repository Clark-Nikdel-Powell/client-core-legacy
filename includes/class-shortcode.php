<?php
/**
 * Shortcode
 *
 * @package CNP
 */

namespace CNP;

/**
 * Shortcode Class
 */
abstract class Shortcode {

	/**
	 * Shortcode tag
	 *
	 * @var string
	 */
	public $tag;

	/**
	 * Shortcode constructor
	 *
	 * @param string $tag The shortcode tag.
	 */
	public function __construct( $tag ) {

		$this->tag = $tag;

		\add_action( 'init', array( $this, 'register_shortcode' ) );

	}

	/**
	 * Registers a new shortcode.
	 *
	 * Care should be taken through prefixing or other means to ensure that the
	 * shortcode tag being added is unique and will not conflict with other,
	 * already-added shortcode tags. In the event of a duplicated tag, the tag
	 * loaded last will take precedence.
	 */
	private function register_shortcode() {

		\add_shortcode( $this->tag, $this->callback );

	}

	/**
	 * Callback function run when the shortcode is found.
	 *
	 * @param array  $atts Array of shortcode attributes.
	 * @param string $content Shortcode content. Defaults to null.
	 * @param string $shortcode_tag The shortcode tag. Defaults to empty string.
	 */
	abstract public function callback( $atts, $content = null, $shortcode_tag = '' );

}
