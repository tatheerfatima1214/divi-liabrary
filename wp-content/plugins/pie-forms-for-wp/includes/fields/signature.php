<?php if ( ! defined( 'ABSPATH' ) ) exit;

class PIE_Fields_Signature extends PIE_Abstracts_Fields
{   

    public function __construct()
    {
        $this->name     = esc_html__( 'Signature', 'pie-forms' );
		$this->type     = 'signature';
		$this->icon     = 'signature';
		$this->order    = 170;
        $this->group    = 'advanced';
        $this->is_pro   = true;

        parent::__construct();
    }
}