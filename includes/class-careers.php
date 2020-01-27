<?php
/**
 * Careers post type
 *
 * @package CNP
 */

namespace CNP;

/**
 * Careers Class
 */
class Careers extends Post_Type {

	/**
	 * Careers constructor
	 */
	public function __construct() {

		$this->name                = 'careers';
		$this->single              = 'Career';
		$this->plural              = 'Careers';
		$this->description         = 'Careers at Hopping Green & Sams';
		$this->hierarchical        = false;
		$this->public              = false;
		$this->show_ui             = true;
		$this->show_in_menu        = true;
		$this->menu_position       = 6;
		$this->menu_icon           = 'dashicon-clipboard';
		$this->show_in_admin_bar   = true;
		$this->show_in_nav_menus   = false;
		$this->show_in_rest        = false;
		$this->has_archive         = false;
		$this->exclude_from_search = true;
		$this->publicly_queryable  = false;
		$this->capability_type     = 'page';

		parent::__construct();

	}
}
