<?php
defined( 'ABSPATH' ) || exit;

class PIE_Fields_DateTime extends PIE_Abstracts_Fields {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->name     = esc_html__( 'Date / Time', 'pie-forms' );
		$this->type     = 'date-time';
		$this->icon     = 'date-time';
		$this->order    = 200;
        $this->group    = 'advanced';
        $this->is_pro   = true;

        parent::__construct();
    }
}