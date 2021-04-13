<?php

defined( 'ABSPATH' ) || exit;

class PIE_Settings_Email extends PIE_Abstracts_Settings {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'email';
		$this->label = esc_html__( 'Email', 'pie-forms' );

		parent::__construct();
	}

	/**
	 * Get settings array.
	 */
	public function get_settings() {
		$settings = apply_filters(
			'pie_forms_email_settings',
			array(
				array(
					'title' => esc_html__( 'Email Settings', 'pie-forms' ),
					'type'  => 'title',
					'id'    => 'email_settings',
				),
				array(
					'title'    => esc_html__( 'Enable copies', 'pie-forms' ),
					'desc'     => esc_html__( 'Enable the use of Cc and Bcc email addresses.', 'pie-forms' ),
					'id'       => 'pf_enable_email_copies',
					'type'     => 'checkbox',
					'default'  => 'no',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'email_settings',
				),
			)
		);

		return apply_filters( 'pie_forms_get_settings_' . $this->id, $settings );
	}


	/**
	 * Save settings.
	 */
	public function save() {
		$settings = $this->get_settings();

		PIE_Admin_Settings::save_fields( $settings );
	}
}