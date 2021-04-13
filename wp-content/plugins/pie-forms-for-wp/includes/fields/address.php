<?php
/**
 * Address field
 *
 * @package PieForms\Fields     
 */

defined( 'ABSPATH' ) || exit;

/**
 * PIE_Fields_Address Class.
 */
class PIE_Fields_Address extends PIE_Abstracts_Fields {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->name   = esc_html__( 'Address', 'pie-forms' );
		$this->type   = 'address';
		$this->icon   = 'address';
		$this->order  = 130;
		$this->group  = 'advanced';
		$this->is_pro = true;

		parent::__construct();
	}
}
