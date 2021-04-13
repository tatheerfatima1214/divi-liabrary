<?php

defined( 'ABSPATH' ) || exit;

/**
 * PIE_Tools_Environment.
 */
class PIE_Tools_Environment extends PIE_Abstracts_Tools {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'environment';
		$this->label = esc_html__( 'Environment', 'pie-forms' );
		parent::__construct();
		
	}

}