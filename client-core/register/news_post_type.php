<?
add_action( 'init', 'create_news_taxonomy' );

function create_news_taxonomy() {

	register_taxonomy(
		'news-category'
		,array('news')
		,array(
			'label' => __( 'Categories' )
			,'labels' => array(
				'singular_name' => "Category"
			)
			,'rewrite' => array( 'slug' => 'news/category' )
			,'hierarchical' => true
			,'show_admin_column' => true
		)
	); // register_taxonomy

	register_taxonomy(
		'news-tag'
		,array('news')
		,array(
			'label' => __( 'Tags' )
			,'labels' => array(
				'singular_name' => "Tag"
			)
			,'rewrite' => array( 'slug' => 'news/tagged-as' )
			,'hierarchical' => false
			,'show_admin_column' => true
		)
	); // register_taxonomy

} // create_news_taxonomy

// Add News Post_Type
class CNP_News_Post_Type extends CNP_Post_Type {

    protected static $name = 'news';

    protected static $args = array(
         'label'         => 'News'
        ,'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'author', 'post-formats')
        ,'has_archive'   => true
        ,'menu_position' => 11
        ,'menu_icon'     => 'dashicons-welcome-widgets-menus'
        ,'taxonomies'    => array('news-category', 'news-tag')
    );

    protected static $labels = array(
         'name'               => 'News'
        ,'singular_name'      => 'Article'
        ,'search_items'       => 'Search Articles'
        ,'view_item'          => 'View Article'
        ,'edit_item'          => 'Edit Article'
    );

}

CNP_News_Post_Type::initialize();

// Add News Meta Box
class CNP_News_Meta_Box extends CNP_Meta_Box {

	protected static $id        = 'source_url';
	protected static $title     = 'Source URL';
	protected static $context   = 'default';
	protected static $post_type = 'news';
	protected static $fields    = array();

    public static function set_fields() {
        $type = CNP_News_Meta_Box::$post_type.'_';
        CNP_News_Meta_Box::$fields = array(
    		array(
    			'type'  => 'text',
    			'id'    => SITE_PRE.$type.'source_url',
    			'label' => 'Source:'
    		)
    	);
    }
}

CNP_News_Meta_Box::set_fields();
CNP_News_Meta_Box::initialize();

