<?php

defined( 'ABSPATH' ) || exit;

/**
 * PIE_Tools_PluginsThemes.
 */
class PIE_Tools_PluginsThemes extends PIE_Abstracts_Tools {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'plugins_and_themes';
		$this->label = esc_html__( 'Plugins And Themes', 'pie-forms' );
		parent::__construct();
		
	}

}