<?php

/**
 * Post utility functions
 *
 * @package ClientPosts
 * @author Clark Nikdel Powell
 * @link http://www.clarknikdelpowell.com
 * @version  1.0
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 */
class ClientPosts {

    /**
     * Default settings
     * @var array User defined settings
     *
     * @since 1.0
     */
    public $settings = array(
        'post_meta' => array()
    );

    /**
     * Initial constructor
     * @param array $settings User defined settings
     *
     * @since 1.0
     */
    public function __construct($settings = array()) {
        $this->settings = array_merge($this->settings, $settings);
        $this->hook_wordpress();
        return;
    }

    /**
     * Hook into WordPress actions and filters
     *
     * @since 1.0
     */
    public function hook_wordpress() {
        add_filter( 'the_posts', array($this, 'posts_filter') );
    }

    /**
     * Filters the post objects
     * @param  WP_Post[] $posts Array of WP_Post objects
     * @return WP_Post[]        Array of filtered WP_Post objects
     *
     * @since  1.0
     */
    public function posts_filter($posts) {

        if ( is_admin() || empty($posts) ) {
            return $posts;
        }

        foreach ($posts as $key => $post) {
            $post = $this->attach_featured_image_meta($post);
            $post = $this->attach_post_meta($post);
        }

        if ( !class_exists('acf') ) {
            return $posts;
        }

        foreach ($posts as $key => $post) {
            $post = $this->attach_acf_meta($post);
        }

        return $posts;

    }

    /**
     * Attaches the featured image thumbnail id to the post object
     * @param  WP_Post $post
     * @return WP_Post
     *
     * @since  1.0
     */
    public function attach_featured_image_meta($post) {

        $featured_image = get_post_thumbnail_id( $post->ID );

        if ( !empty($featured_image) ) {
            $post->featured_image_id = $featured_image;
        }

        return $post;

    }

    /**
     * Attaches user-defined meta data to the post object
     * @param  WP_Post $post
     * @return WP_Post
     *
     * @since  1.0
     */
    public function attach_post_meta($post) {

        $post_meta = $this->settings['post_meta'];

        if ( !is_array($post_meta) && count($post_meta) === 0) {
            return $post;
        }

        foreach ($post_meta as $key => $name) {
            $value = get_post_meta( $post->ID, $name, true );
            if ( !empty($value) ) {
                $post->$name = $value;
            }
        }

        return $post;

    }

    /**
     * Attaches ACF meta data to the post object
     * @param  WP_Post $post
     * @return WP_Post
     *
     * @since  1.0
     */
    public function attach_acf_meta($post) {

        $fields = get_fields( $post->ID );

        if ( !$fields ) {
            return $post;
        }

        foreach ($fields as $field_name => $value) {
            $post->$field_name = $value;
        }

        return $post;

    }

}
