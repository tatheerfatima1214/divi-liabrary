<?php

defined( 'ABSPATH' ) || exit;

class PIE_Admin_Builder_Settings extends PIE_Admin_Builder_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id      = 'settings';
		$this->label   = esc_html__( __('Settings', 'pie-forms' ));
		$this->sidebar = true;

		//add_action( 'pie_forms_settings_connections_email', array( $this, 'output_connections_list' ) );

		parent::__construct();
	}

	/**
	 * Outputs the builder sidebar.
	 */
	public function output_sidebar() {
		/* $sections = apply_filters(
			'pie_forms_builder_settings_section',
			array(
				'general' => esc_html__( __('General Settings', 'pie-forms' )),
				'email'   => esc_html__( __('Email Settings', 'pie-forms' )),
			),
			$this->form_data
		);

		if ( ! empty( $sections ) ) {
			echo '<div class="setting-tab">';
				foreach ( $sections as $slug => $section ) {
					$this->add_sidebar_tab( $section, $slug );
					do_action( 'pie_forms_settings_connections_' . $slug, $section );
				}
			echo '</div>';
		} */
	}

	
	/**
	 * Outputs the builder content.
	 */
	public function output_content() {
		$settings     = isset( $this->form_data['settings'] ) ? $this->form_data['settings'] : array();
		$email_status = isset( $this->form_data['settings']['enable_email_notification'] ) ? $this->form_data['settings']['enable_email_notification'] : 0;
		$core 		  = Pie_Forms()->core();


		$sections = apply_filters(
			'pie_forms_builder_settings_section',
			array(
				'general' => esc_html__( __('General Settings', 'pie-forms' )),
				'email'   => esc_html__( __('Email Settings', 'pie-forms' )),
				'limit'   => esc_html__( __('Limit and Schedule', 'pie-forms' )),
			),
			$this->form_data
		);

		if ( ! empty( $sections ) ) {
			
			echo '<div class="pie-form-accordian-settings">';
				echo '<div class="setting-tab">';
					foreach ( $sections as $slug => $section ) {
						$class  = '';
						$class .= 'default' === $slug ? ' default' : '';
						//$class .= ! empty( $icon ) ? ' icon' : '';
					//	$active = ($slug == 'general') ? 'active' : '';
						//$this->add_sidebar_tab( $section, $slug );
				
						echo '<div class="pf-list-'.esc_attr($slug).' tab-accordian">';
							echo '<a href="#" class="pf-panel-tab pf-setting-panel pie-forms-panel-sidebar-section pie-forms-panel-sidebar-section-' . esc_attr( $slug ) . esc_attr( $class ) .'" data-section="' . esc_attr( $slug ) . '">';
					
							echo esc_html( $section );
							echo '</a>';

							// --------------------------------------------------------------------//
										// General Starts
							// --------------------------------------------------------------------//

							if($slug == "general"){
								
										/* echo '<span class="go-back" id="go-back-form">Go back to forms</span>'; */
										echo '<div class="pf-content-section ScrollBar pf-content-general-settings">';
										/* echo '<div class="pf-content-section-title">';
										esc_html_e( 'General', 'pie-forms' );
										echo '</div>'; */
										$core->pie_forms_panel_field(
											'text',
											'settings',
											'form_title',
											$this->form_data,
											esc_html__( __('Form Name', 'pie-forms' )),
											array(
												'default' => isset( $this->form->form_title ) ? $this->form->form_title : '',
												'tooltip' => esc_html__( __('Give a name to this form', 'pie-forms' )),
											)
										);

										$core->pie_forms_panel_field(
											'checkbox',
											'settings',
											'enable_title',
											$this->form_data,
											esc_html__( __('Enable Form Title / Name on Front End', 'pie-forms' )),
											array(
												'default' => isset( $this->form->enable_title ) ? $this->form->enable_title : '',
												'tooltip' => esc_html__( __('Enable title on front end', 'pie-forms' )),
											)
										);

										$core->pie_forms_panel_field(
											'textarea',
											'settings',
											'form_description',
											$this->form_data,
											esc_html__( __('Form description', 'pie-forms' )),
											array(
												'input_class' => 'short',
												'default'     => isset( $this->form->form_description ) ? $this->form->form_description : '',
												'tooltip'     => sprintf( esc_html__( __('Give the description to this form', 'pie-forms' ) )),
											)
										);
										$core->pie_forms_panel_field(
											'checkbox',
											'settings',
											'enable_description',
											$this->form_data,
											esc_html__( __('Enable Form Description Front End', 'pie-forms' )),
											array(
												'default' => isset( $this->form->enable_description ) ? $this->form->enable_description : '',
												'tooltip' => esc_html__( __('Enable description on front end', 'pie-forms' )),
											)
										);
										// $core->pie_forms_panel_field(
										// 	'textarea',
										// 	'settings',
										// 	'form_disable_message',
										// 	$this->form_data,
										// 	esc_html__( __('Form disabled message', 'pie-forms' )),
										// 	array(
										// 		'input_class' => 'short',
										// 		'default'     => isset( $this->form->form_disable_message ) ? $this->form->form_disable_message : __( 'This form is disabled.', 'pie-forms' ),
										// 		'tooltip'     => sprintf( esc_html__( __('Message that shows up if the form is disabled.', 'pie-forms' ) )),
										// 	)
										// );
										$core->pie_forms_panel_field(
											'textarea',
											'settings',
											'successful_form_submission_message',
											$this->form_data,
											esc_html__( __('Successful form submission message', 'pie-forms' )),
											array(
												'input_class' => 'short',
												'default'     => isset( $this->form->successful_form_submission_message ) ? $this->form->successful_form_submission_message : "",
												'tooltip'     => sprintf( esc_html__( __('Success message that shows up after submitting form.', 'pie-forms' ) )),
											)
										);
										// $core->pie_forms_panel_field(
										// 	'checkbox',
										// 	'settings',
										// 	'submission_message_scroll',
										// 	$this->form_data,
										// 	__( 'Automatically scroll to the submission message', 'pie-forms' ),
										// 	array(
										// 		'default' => '1',
										// 	)
										// );
										$core->pie_forms_panel_field(
											'select',
											'settings',
											'redirect_to',
											$this->form_data,
											esc_html__( __('Redirect To', 'pie-forms' )),
											array(
												'default' => 'same',
												'tooltip' => sprintf( esc_html__( __('Choose where to redirect after form submission.', 'pie-forms' ) )),
												'options' => array(
													'same'         => esc_html__( __('Same Page', 'pie-forms' )),
													'custom_page'  => esc_html__( __('Pages', 'pie-forms' )),
													'external_url' => esc_html__( __('External URL', 'pie-forms' )),
												),
											)
										);
										$core->pie_forms_panel_field(
											'select',
											'settings',
											'custom_page',
											$this->form_data,
											esc_html__( __('Pages', 'pie-forms' )),
											array(
												'default' => '0',
												'options' => $this->pf_get_all_pages(),
											)
										);
										$core->pie_forms_panel_field(
											'text',
											'settings',
											'external_url',
											$this->form_data,
											esc_html__( __('External URL', 'pie-forms' )),
											array(
												'default' => isset( $this->form->external_url ) ? $this->form->external_url : '',
											)
										);
										$core->pie_forms_panel_field(
											'select',
											'settings',
											'layout_class',
											$this->form_data,
											esc_html__( __('Layout Design', 'pie-forms' )),
											array(
												'default' => '0',
												'tooltip' => esc_html__( __('Choose design layout', 'pie-forms' )),
												'options' => array(
													'one-column'    => esc_html__( __('One Column', 'pie-forms' )),
													'two-column' => esc_html__( __('Two Column', 'pie-forms' )),
												),
											)
										);
										$core->pie_forms_panel_field(
											'text',
											'settings',
											'form_class',
											$this->form_data,
											esc_html__( __('Form Class', 'pie-forms' )),
											array(
												'default' => isset( $this->form->form_class ) ? $this->form->form_class : '',
												/* translators: %1$s - general settings docs url */
												'tooltip' => sprintf( esc_html__( __('Enter CSS class names for the form wrapper. Multiple class names should be separated with spaces.', 'pie-forms' ) )),
											)
										);
										echo '<div class="pie-forms-border-container">';
										$core->pie_forms_panel_field(
											'text',
											'settings',
											'submit_button_text',
											$this->form_data,
											esc_html__( __('Submit button text', 'pie-forms' )),
											array(
												'default' => isset( $settings['submit_button_text'] ) ? $settings['submit_button_text'] : __( 'Submit', 'pie-forms' ),
												'tooltip' => esc_html__( __('Enter desired text for submit button.', 'pie-forms' )),
											)
										);
										$core->pie_forms_panel_field(
											'text',
											'settings',
											'submit_button_processing_text',
											$this->form_data,
											__( 'Submit button processing text', 'pie-forms' ),
											array(
												'default' => isset( $settings['submit_button_processing_text'] ) ? $settings['submit_button_processing_text'] : __( 'Processing', 'pie-forms' ),
												'tooltip' => esc_html__( __('Enter the submit button text that you would like the button to display while the form submission is processing.', 'pie-forms' )),
											)
										);
										$core->pie_forms_panel_field(
											'text',
											'settings',
											'submit_button_class',
											$this->form_data,
											esc_html__( __('Submit button class', 'pie-forms' )),
											array(
												'default' => isset( $settings['submit_button_class'] ) ? $settings['submit_button_class'] : '',
												'tooltip' => esc_html__( __('Enter CSS class names for submit button. Multiple class names should be separated with spaces.', 'pie-forms' )),
											)
										);
										do_action( 'pie_forms_inline_submit_settings', $this, 'submit', 'connection_1' );
										echo '</div>';

										$core->pie_forms_panel_field(
											'checkbox',
											'settings',
											'honeypot',
											$this->form_data,
											esc_html__( 'Enable anti-spam honeypot', 'pie-forms' ),
											array(
												'default' => '1',
											)
										);

										
										$core->pie_forms_panel_field(
											'checkbox',
											'settings',
											'hide_all_label',
											$this->form_data,
											__( 'Hide All Labels', 'pie-forms' ),
											array(
												'default' => 0,
												'tooltip' => esc_html__( __('Hide all forms labels.', 'pie-forms' )),
											)
										);
										$core->pie_forms_panel_field(
											'checkbox',
											'settings',
											'label_to_placeholder',
											$this->form_data,
											__( 'Display All Labels as Placeholder', 'pie-forms' ),
											array(
												'default' => 0,
												'tooltip' => esc_html__( __('Displays the label text as placeholder.', 'pie-forms' )),
											)
										);
										
										$recaptcha_type   = get_option( 'pf_recaptcha_type', 'v2' );
										$recaptcha_key    = get_option( 'pf_recaptcha_' . $recaptcha_type . '_site_key' );
										$recaptcha_secret = get_option( 'pf_recaptcha_' . $recaptcha_type . '_secret_key' );
										if ( ! empty( $recaptcha_key ) && ! empty( $recaptcha_secret ) ) {
											$core->pie_forms_panel_field(
												'checkbox',
												'settings',
												'recaptcha_support',
												$this->form_data,
												'v3' === $recaptcha_type ? esc_html__( __('Enable Google reCAPTCHA v3', 'pie-forms' )) : ( 'yes' === get_option( 'pf_recaptcha_v2_invisible' ) ? esc_html__( __('Enable Google Invisible reCAPTCHA v2', 'pie-forms' )) : esc_html__( __('Enable Google Checkbox reCAPTCHA v2', 'pie-forms' ) )),
												array(
													'default' => '0',
													'tooltip' => sprintf( esc_html__( __('Enable Google reCaptcha. Make sure the site key and secret key is set in Global settings.', 'pie-forms' ) )),
												)
											);
										}

										$core->pie_forms_panel_field(
											'checkbox',
											'settings',
											'ajax_form_submission',
											$this->form_data,
											esc_html__( 'Enable Ajax Form Submission', 'pie-forms' ),
											array(
												'default' => isset( $settings['ajax_form_submission'] ) ? $settings['ajax_form_submission'] : 0,
												'tooltip' => esc_html__( 'Enables form submission without reloading the page.', 'pie-forms' ),
											)
										);
							
										do_action( 'pie_forms_general_settings', $this );

										echo '</div>';
							}

							// --------------------------------------------------------------------//
										// General Ends
							// --------------------------------------------------------------------//

							// --------------------------------------------------------------------//
									// Email Start
							// --------------------------------------------------------------------//

							if($slug == "email"){
									
									$form_name = isset( $settings['form_title'] ) ? ' ' . $settings['form_title'] : '';
									if ( ! isset( $settings['email']['connection_1'] ) ) {
										$settings['email']['connection_1']                   = array( 'connection_name' => __( 'Admin Notification', 'pie-forms' ) );
										
										$settings['email']['connection_1']['pf_to_email']   = isset( $settings['email']['pf_to_email'] ) ? sanitize_email($settings['email']['pf_to_email']) : '{admin_email}';
										
										$settings['email']['connection_1']['pf_from_name']  = isset( $settings['email']['pf_from_name'] ) ? $settings['email']['pf_from_name'] : get_bloginfo( 'name', 'display' );
										
										$settings['email']['connection_1']['pf_from_email'] = isset( $settings['email']['pf_from_email'] ) ? sanitize_email($settings['email']['pf_from_email']) : '{admin_email}';
										
										$settings['email']['connection_1']['pf_reply_to']   = isset( $settings['email']['pf_reply_to'] ) ? sanitize_email($settings['email']['pf_reply_to']) : '';
										
										/* translators: %s: Form Name */
										$settings['email']['connection_1']['pf_email_subject'] = isset( $settings['email']['pf_email_subject'] ) ? $settings['email']['pf_email_subject'] : sprintf( esc_html__( __('New Form Entry %s', 'pie-forms' )), $form_name);
										$settings['email']['connection_1']['pf_email_message'] = isset( $settings['email']['pf_email_message'] ) ? $settings['email']['pf_email_message'] : '{all_fields}';

										$email_settings = array( 'attach_pdf_to_admin_email', 'show_header_in_attachment_pdf_file', 'conditional_logic_status', 'conditional_option', 'conditionals' );
										foreach ( $email_settings as $email_setting ) {
											$settings['email']['connection_1'][ $email_setting ] = isset( $settings['email'][ $email_setting ] ) ? $settings['email'][ $email_setting ] : '';
										}

										// Backward compatibility.
										$unique_connection_id = sprintf( 'connection_%s', uniqid() );
										if ( isset( $settings['email']['pf_send_confirmation_email'] ) && '1' === $settings['email']['pf_send_confirmation_email'] ) {
											$settings['email'][ $unique_connection_id ] = array( 'connection_name' => esc_html__( __('User Notification', 'pie-forms' ) ));

											foreach ( $email_settings as $email_setting ) {
												$settings['email'][ $unique_connection_id ][ $email_setting ] = isset( $settings['email'][ $email_setting ] ) ? $settings['email'][ $email_setting ] : '';
											}
										}
									}

									$email_status = isset( $settings['email']['enable_email_notification'] ) ? $settings['email']['enable_email_notification'] : '1';
									$hidden_class = '1' !== $email_status ? 'pie-forms-hidden' : '';

									echo '<div class="pf-content-section ScrollBar pf-content-email-settings">';
									
									foreach ( $settings['email'] as $connection_id => $connection ) :
										if ( preg_match( '/connection_/', $connection_id ) ) {
											echo '<div class="pf-content-email-settings-inner ' . esc_attr( $hidden_class ) . '" data-connection_id=' . esc_attr( $connection_id ) . '>';
											$email_copy = get_option('pf_enable_email_copies');
											
											
											$core->pie_forms_panel_field(
												'text',
												'email',
												'pf_to_email',
												$this->form_data,
												esc_html__( __('To Address', 'pie-forms' )),
												array(
													'default'    => isset( $settings['email'][ $connection_id ]['pf_to_email'] ) ? $settings['email'][ $connection_id ]['pf_to_email'] : '{admin_email}',
													/* translators: %1$s - general settings docs url */
													'tooltip'    => sprintf( esc_html__( __('Enter the recipient\'s email address (comma separated) to receive form entry notifications', 'pie-forms' ) )),
													'smarttags'  => array(
														'type'        => 'fields',
														'form_fields' => 'email',
													),
													'parent'     => 'settings',
													'subsection' => $connection_id,
												)
											);
											if($email_copy === 'yes'){
												$core->pie_forms_panel_field(
													'text',
													'email',
													'pf_carboncopy',
													$this->form_data,
													esc_html__( 'Cc Address', 'pie-forms' ),
													array(
														'default'    => isset( $settings['email'][ $connection_id ]['pf_carboncopy'] ) ? $settings['email'][ $connection_id ]['pf_carboncopy'] : '',
														'tooltip'    => esc_html__( 'Enter Cc recipient\'s email address (comma separated) to receive form entry notifications.', 'pie-forms' ),
														'smarttags'  => array(
															'type'        => 'fields',
															'form_fields' => 'email',
														),
														'parent'     => 'settings',
														'subsection' => $connection_id,
													)
												);
												$core->pie_forms_panel_field(
													'text',
													'email',
													'pf_blindcarboncopy',
													$this->form_data,
													esc_html__( 'Bcc Address', 'pie-forms' ),
													array(
														'default'    => isset( $settings['email'][ $connection_id ]['pf_blindcarboncopy'] ) ? $settings['email'][ $connection_id ]['pf_blindcarboncopy'] : '',
														'tooltip'    => esc_html__( 'Enter Bcc recipient\'s email address (comma separated) to receive form entry notifications.', 'pie-forms' ),
														'smarttags'  => array(
															'type'        => 'fields',
															'form_fields' => 'email',
														),
														'parent'     => 'settings',
														'subsection' => $connection_id,
													)
												);
											}
											$core->pie_forms_panel_field(
												'text',
												'email',
												'pf_from_name',
												$this->form_data,
												esc_html__( __('From Name', 'pie-forms' )),
												array(
													'default'    => isset( $settings['email'][ $connection_id ]['pf_from_name'] ) ? $settings['email'][ $connection_id ]['pf_from_name'] : get_bloginfo( 'name', 'display' ),
													'tooltip'    => sprintf( esc_html__( __('Enter the From Name to be displayed in Email.', 'pie-forms' ) )),
													'smarttags'  => array(
														'type'        => 'all',
														'form_fields' => 'all',
													),
													'parent'     => 'settings',
													'subsection' => $connection_id,
												)
											);
											$core->pie_forms_panel_field(
												'text',
												'email',
												'pf_from_email',
												$this->form_data,
												esc_html__( __('From Address', 'pie-forms' )),
												array(
													'default'    => isset( $settings['email'][ $connection_id ]['pf_from_email'] ) ? $settings['email'][ $connection_id ]['pf_from_email'] : '{admin_email}',
													'tooltip'    => sprintf( esc_html__( __('Enter the Email address from which you want to send Email.', 'pie-forms' ) )),
													'smarttags'  => array(
														'type'        => 'fields',
														'form_fields' => 'email',
													),
													'parent'     => 'settings',
													'subsection' => $connection_id,
												)
											);
											$core->pie_forms_panel_field(
												'text',
												'email',
												'pf_reply_to',
												$this->form_data,
												esc_html__( __('Reply To', 'pie-forms' )),
												array(
													'default'    => isset( $settings['email'][ $connection_id ]['pf_reply_to'] ) ? $settings['email'][ $connection_id ]['pf_reply_to'] : '',
													'tooltip'    => sprintf( esc_html__( __('Enter the reply to email address where you want the email to be received when this email is replied.', 'pie-forms' ))),
													'smarttags'  => array(
														'type'        => 'fields',
														'form_fields' => 'email',
													),
													'parent'     => 'settings',
													'subsection' => $connection_id,
												)
											);
											$core->pie_forms_panel_field(
												'text',
												'email',
												'pf_email_subject',
												$this->form_data,
												esc_html__( __('Email Subject', 'pie-forms' )),
												array(
													/* translators: %s: Form Name */
													'default'    => isset( $settings['email'][ $connection_id ]['pf_email_subject'] ) ? $settings['email'][ $connection_id ]['pf_email_subject'] : sprintf( esc_html__( __('New Form Entry %s', 'pie-forms' ), $form_name )),
													/* translators: %1$s - General Settings docs url */
													'tooltip'    => sprintf( esc_html__( __('Enter the subject of the email.', 'pie-forms' ))),
													'smarttags'  => array(
														'type'        => 'all',
														'form_fields' => 'all',
													),
													'parent'     => 'settings',
													'subsection' => $connection_id,
												)
											);
											$core->pie_forms_panel_field(
												'tinymce',
												'email',
												'pf_email_message',
												$this->form_data,
												esc_html__( __('Admin Email Message', 'pie-forms' )),
												array(
													'default'    => isset( $settings['email'][ $connection_id ]['pf_email_message'] ) ? $settings['email'][ $connection_id ]['pf_email_message'] : __( '{all_fields}', 'pie-forms' ),
													/* translators: %1$s - general settings docs url */
													'tooltip'    => sprintf( esc_html__( __('Enter the message of the email.', 'pie-forms' ) )),
													'smarttags'  => array(
														'type'        => 'all',
														'form_fields' => 'all',
													),
													'parent'     => 'settings',
													'subsection' => $connection_id,
													/* translators: %s - all fields smart tag. */
													'after'      => '<p class="desc">' . sprintf( esc_html__( __('To display all form fields, use the %s Smart Tag.', 'pie-forms' ) ). '</p>', '<code>{all_fields}</code>' ),
												)
											);

											do_action( 'pie_forms_inline_email_settings', $this, $connection_id );

											echo '</div>';
										}

									endforeach;

									echo '</div>';
							}

							// --------------------------------------------------------------------//
									// Email Ends
							// --------------------------------------------------------------------//
							
							// --------------------------------------------------------------------//
									// limit Start
							// --------------------------------------------------------------------//
							
							if($slug == "limit"){
								echo '<div class="pf-content-section ScrollBar pf-content-limit-settings">';
									echo sprintf( __( '<div  class="upgrade-to-pro"><p>This feature is only available in our Premium Plan. <a href=
									"https://pieforms.com/plan-and-pricing/?utm_source=admindashboard&utm_medium=adminfeatures&utm_campaign=formbuilder" target="_blank"> Upgrade to Premium </a>and enjoy all the amazing features. </p></div>', 'pie-forms' ));
								echo '</div>';
							}

							//--------------------------------------------------------------------//
									// limit Start
							// --------------------------------------------------------------------//
							
							do_action( 'pie_forms_settings_connections_' . $slug, $section, $this->form_data );
						echo '</div>';

						
					}
				echo '</div>';
			echo '</div>';
		}

		

		
		
		do_action( 'pie_forms_settings_panel_content', $this );
	}

	/**
	 * Get all pages.
	 */
	public function pf_get_all_pages() {
		$pages = array();
		foreach ( get_pages() as $page ) {
			$pages[ $page->ID ] = $page->post_title;
		}

		return $pages;
	}
}