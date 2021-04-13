<?php

defined( 'ABSPATH' ) || exit;

class PIE_Fields_Country extends PIE_Abstracts_Fields {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->name     = esc_html__( 'Country', 'pie-forms' );
		$this->type     = 'country';
		$this->icon     = 'country';
		$this->order    = 190;
		$this->group    = 'advanced';
        $this->is_pro   = true;
        
        parent::__construct();
    }
}