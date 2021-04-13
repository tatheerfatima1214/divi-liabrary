<?php

defined( 'ABSPATH' ) || exit;


class PIE_Core_Ajax {


	public static function init() {
		self::add_ajax_events();
	}

	/**
	 * Hook in methods - uses WordPress ajax handlers (admin-ajax).
	 */
	public static function add_ajax_events() {
		$ajax_events = array(
			'save_form'               => false,
			'create_form'             => false,
			'get_next_id'             => false,
			'enabled_form'            => false,
			'ajax_form_submission'    => true,
		);
		foreach ( $ajax_events as $ajax_event => $nopriv ) {
			add_action( 'wp_ajax_pie_forms_' . $ajax_event, array( __CLASS__, $ajax_event ) );

			if ( $nopriv ) {

				add_action( 'wp_ajax_nopriv_pie_forms_' . $ajax_event, array( __CLASS__, $ajax_event ) );

				add_action( 'pf_ajax_' . $ajax_event, array( __CLASS__, $ajax_event ) );
			}
		}
	}

	/**
	 * Ajax handler to get next form ID.
	 */
	public static function get_next_id() {

		$form_id = isset( $_POST['form_id'] ) ? absint( $_POST['form_id'] ) : 0;
		if ( $form_id < 1 ) {
			wp_send_json_error(
				array(
					'error' => esc_html__( 'Invalid form', 'pie-forms' ),
				)
			);
		}
		if ( ! current_user_can( apply_filters( 'pie_forms_manage_cap', 'manage_options' ) ) ) {
			wp_send_json_error();
		}
		$field_key      = PIE_Forms()->core()->field_unique_key( $form_id );
		$field_id_array = explode( '-', $field_key );
		$new_field_id   = ( $field_id_array[ count( $field_id_array ) - 1 ] + 1 );
		wp_send_json_success(
			array(
				'field_id'  => $new_field_id,
				'field_key' => $field_key,
			)
		);
	}

	/**
	 * Triggered when clicking the form toggle.
	 */
	public static function enabled_form() {
		// Run a security check.
		check_ajax_referer( 'pie_forms_enabled_form', 'security' );

		$form_id = isset( $_POST['form_id'] ) ? absint( $_POST['form_id'] ) : 0;
		$enabled = isset( $_POST['enabled'] ) ? absint( $_POST['enabled'] ) : 0;
		
		
		$form_fields =  Pie_Forms()->core()->get_form_fields($form_id);
	
		$form_fields['form_enabled'] = $enabled;
		
		Pie_Forms()->form()->update( $form_id, $form_fields );
	}

	/**
	 * AJAX create new form.
	 */
	public static function create_form() {
		
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) )
        die ( 'Nonce Error!');
        
		//$pie_form_name   = $_POST['form_name'] ;
		//$pie_form_template = $_POST['form_template'];
		
		$pie_form_name   = isset( $_POST['form_name'] ) ? sanitize_text_field( $_POST['form_name'] ) : esc_html__( __('Blank Form', 'pie-forms') );
		
		$pie_form_template = isset( $_POST['form_template'] ) ? sanitize_text_field( wp_unslash( $_POST['form_template'] ) ) : 'blank';
		

        global $wpdb;
        $table = $wpdb->prefix.'pf_forms';
        $data = array('form_title' => $pie_form_name, 'post_status'=>'published');
        $format = array('%s');
        $wpdb->insert($table,$data,$format);
        $form_id = $wpdb->insert_id;
        
        
        // $table_fields = $wpdb->prefix.'pf_fields';
        // $data_fields = array('form_id'=>$form_id,'form_title' => $pie_form_name);
        // $format_fields = array('%d','%s');
        // $wpdb->insert($table_fields,$data_fields,$format_fields);
		
		
		// TEMPLATES START
		$raw_templates = file_get_contents(Pie_Forms()->core()->templateJson());
		$templates     = json_decode(  $raw_templates );

		if ( ! empty( $templates ) ) {
			
			foreach ( $templates->templates as $template_data ) {
				if ( $pie_form_template == $template_data->slug ) {
					$decoded = base64_decode( $template_data->settings );
					
					$unslesh = wp_unslash($decoded);
					
					$base64_decode = json_decode($unslesh);
				

					$base64_decode->id = $form_id;
					$base64_decode->settings->form_title = 	$pie_form_name;
				
						
					$data    = array(
						'form_id' 		=> $form_id,
						'form_title' 	=>  $pie_form_name,
						'form_data' 	=>  PIE_Forms()->core()->pf_encode($base64_decode),
					);
					$format = array('%d','%s','%s');
					$wpdb->insert( $wpdb->prefix . 'pf_fields', $data, $format );
				
					}

				
			}
		}

        echo json_encode($form_id);
        exit();

		wp_send_json_error(
			array(
				'error' => esc_html__( 'Something went wrong, please try again later', 'pie-forms' ),
			)
		);
	}
	/**
	 * AJAX Form save.
	 */
	public static function save_form() {

		// Check for form data.
		if ( empty( $_POST['form_data'] ) ) {
			die( esc_html__( 'No data provided', 'pie-forms' ) );
		}
		
		$form_post = json_decode( stripslashes( $_POST['form_data'] ) );
		
		$data = array();
		

		if ( ! is_null( $form_post ) && $form_post ) {
			foreach ( $form_post as $post_input_data ) {
				preg_match( '#([^\[]*)(\[(.+)\])?#', $post_input_data->name, $matches );

				$array_bits = array( $matches[1] );

				if ( isset( $matches[3] ) ) {
					$array_bits = array_merge( $array_bits, explode( '][', $matches[3] ) );
				}

				$new_post_data = array();

				for ( $i = count( $array_bits ) - 1; $i >= 0; $i -- ) {
					if ( count( $array_bits ) - 1 === $i ) {
						$new_post_data[ $array_bits[ $i ] ] = wp_slash( $post_input_data->value );
					} else {
						$new_post_data = array(
							$array_bits[ $i ] => $new_post_data,
						);
					}
				}

				$data = array_replace_recursive( $data, $new_post_data );
			}
		}

		// Check for empty meta key.
		$empty_meta_data = array();
		if ( ! empty( $data['form_fields'] ) ) {
			foreach ( $data['form_fields'] as $field_key => $field ) {
				if ( ! empty( $field['label'] ) ) {
					// Only allow specific html in label.
					$data['form_fields'][ $field_key ]['label'] = wp_kses(
						$field['label'],
						array(
							'a'      => array(
								'href'  => array(),
								'class' => array(),
							),
							'span'   => array(
								'class' => array(),
							),
							'em'     => array(),
							'small'  => array(),
							'strong' => array(),
						)
					);

					// Register string for translation.
					Pie_Forms()->core()->pie_string_translation( $data['id'], $field['id'], $field['label'] );
				}

				if ( empty( $field['meta-key'] ) && ! in_array( $field['type'], array( 'html', 'title', 'captcha' ), true ) ) {
					$empty_meta_data[] = $field['label'];
				}
			}
			
			if ( ! empty( $empty_meta_data ) ) {
				wp_send_json_error(
					array(
						'errorTitle'   => esc_html__( 'Meta Key missing', 'pie-forms' ),
						/* translators: %s: empty meta data */
						'errorMessage' => sprintf( esc_html__( 'Please add Meta key for fields: %s', 'pie-forms' ), '<strong>' . implode( ', ', $empty_meta_data ) . '</strong>' ),
					)
				);
			}
		}


		// Fix for sorting field ordering.
		if ( isset( $data['structure'], $data['form_fields'] ) ) {
			$structure           = Pie_Forms()->core()->pf_flatten_array( $data['structure'] );
			$data['form_fields'] = array_merge( array_intersect_key( array_flip( $structure ), $data['form_fields'] ), $data['form_fields'] );
		}
		$form_id = Pie_Forms()->form()->update( $data['id'], $data );
		do_action( 'pie_forms_save_form', $form_id, $data );

		if ( ! $form_id ) {
			wp_send_json_error(
				array(
					'errorTitle'   => esc_html__( 'Form not found', 'pie-forms' ),
					'errorMessage' => esc_html__( 'An error occurred while saving the form.', 'pie-forms' ),
				)
			);
		} else {
			wp_send_json_success(
				array(
					'form_name'    => esc_html( $data['settings']['form_title'] ),
				)
			);
		}
	}


		/**
	 * Ajax handler for form submission.
	 */
	public static function ajax_form_submission() {
		//die("asdf");
		check_ajax_referer( 'pie_forms_ajax_form_submission', 'security' );
		if ( ! empty( $_POST['pie_forms']['id'] ) ) {
			$process = Pie_Forms()->task->ajax_form_submission( stripslashes_deep( $_POST['pie_forms'] ) ); 
			if ( 'success' === $process['response'] ) {
				wp_send_json_success( $process );
			}

			wp_send_json_error( $process );
		}
	}
}

PIE_Core_Ajax::init();
