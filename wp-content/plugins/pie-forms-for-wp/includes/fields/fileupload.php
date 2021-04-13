<?php if ( ! defined( 'ABSPATH' ) ) exit;

class PIE_Fields_Fileupload extends PIE_Abstracts_Fields
{   

    public function __construct()
    {
        $this->name     = esc_html__( 'File Upload', 'pie-forms' );
		$this->type     = 'fileupload';
		$this->icon     = 'fileupload';
		$this->order    = 210;
        $this->group    = 'advanced';
        $this->is_pro   = true;

        parent::__construct();
    }
}