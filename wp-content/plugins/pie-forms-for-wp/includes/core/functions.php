<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class PIE_Core_Functions
{   

    public function __construct()
    {

	}

	/**
	 * Get all PieForms screen ids.
	 *
	 * @return array
	 */
	function pf_get_screen_ids() {
		$pf_screen_id = sanitize_title( esc_html__( 'Pie Forms', 'pie-forms' ) );
		$screen_ids    = array(
			'dashboard_page_pf-welcome',
			'toplevel_page_' . $pf_screen_id,
			$pf_screen_id . '_page_pf-builder',
			$pf_screen_id . '_page_pf-entries',
			$pf_screen_id . '_page_pf-settings',
			$pf_screen_id . '_page_pf-tools',
			$pf_screen_id . '_page_pf-addons',
			$pf_screen_id . '_page_pf-email-templates',
		);

		return apply_filters( 'pie_forms_screen_ids', $screen_ids );
	}


	/**
	 * Get a builder fields type's name.
	 */
	public function get_fields_group( $type = '' ) {
		$types = $this->get_fields_groups();
		return isset( $types[ $type ] ) ? $types[ $type ] : '';
	}

	/**
	 * Get builder fields groups.
	 *
	 * @return array
	 */
	public function get_fields_groups() {
		return (array) apply_filters(
			'pie_forms_builder_fields_groups',
			array(
				'basic'  => __( 'Basic Fields', 'pie-forms' ),
				'advanced' => __( 'Advanced Fields', 'pie-forms' ),
				'payment'  => __( 'Payment Fields', 'pie-forms' ),
				'survey'   => __( 'Survey Fields', 'pie-forms' ),
			)
		);
	}


	public function field_unique_key( $form_id ) {
		
		if ( empty( $form_id ) ) {
			return false;
		}
		

		$form_id++;

		$field_id = $this->pf_get_random_string() . '-' . $form_id;

		return $field_id;
	}

	/**
		* Generate random string.
	*/
	function pf_get_random_string( $length = 10 ) {
		$string         = '';
		$code_alphabet  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$code_alphabet .= 'abcdefghijklmnopqrstuvwxyz';
		$code_alphabet .= '0123456789';
		$max            = strlen( $code_alphabet );
		for ( $i = 0; $i < $length; $i ++ ) {
			$string .= $code_alphabet[ $this->pf_crypto_rand_secure( 0, $max - 1 ) ];
		}

		return $string;
	}

	/**
	 * Crypto rand secure.
	 */
	function pf_crypto_rand_secure( $min, $max ) {
		$range = $max - $min;
		if ( $range < 1 ) {
			return $min;
		} // not so random...
		$log    = ceil( log( $range, 2 ) );
		$bytes  = (int) ( $log / 8 ) + 1; // Length in bytes.
		$bits   = (int) $log + 1; // Length in bits.
		$filter = (int) ( 1 << $bits ) - 1; // Set all lower bits to 1.
		do {
			$rnd = hexdec( bin2hex( openssl_random_pseudo_bytes( $bytes ) ) );
			$rnd = $rnd & $filter; // Discard irrelevant bits.
		} while ( $rnd > $range );

		return $min + $rnd;
	}

	/**
	 * Performs json_decode and unslash.

	 */
	public function pf_decode( $data ) {
		if ( ! $data || empty( $data ) ) {
			return false;
		}

		return json_decode( $data, true );
	}

	/**
	 * Performs json_encode and wp_slash.
	 */
	function pf_encode( $data = false ) {
		if ( empty( $data ) ) {
			return false;
		}

		return wp_slash( wp_json_encode( $data ) );
	}

	//GET FORM FIELD DATA ONLY
    public function get_form_fields($form_id){
		
		$form_data = Pie_Forms()->form()->get( absint( $form_id ));
        $data = array_shift($form_data);
        $data_unslesh = $this->pf_decode( wp_unslash($data->form_data) );
        
        return $data_unslesh;
    }

	function pf_flatten_array( $value = array() ) {
		$return = array();
		array_walk_recursive( $value, function( $a ) use ( &$return ) { $return[] = $a; } ); // @codingStandardsIgnoreLine.
		return $return;
	}

	function pie_forms_panel_field( $option, $panel, $field, $form_data, $label, $args = array(), $echo = true ) {
		// Required params.
		if ( empty( $option ) || empty( $panel ) || empty( $field ) ) {
			return '';
		}

		// Setup basic vars.
		$panel       = esc_attr( $panel );
		$field       = esc_attr( $field );
		$panel_id    = $this->sanitize_html_class( $panel );
		$label       = ! empty( $label ) ? $label : '';
		$class       = ! empty( $args['class'] ) ? esc_attr( $args['class'] ) : '';
		$input_class = ! empty( $args['input_class'] ) ? esc_attr( $args['input_class'] ) : '';
		$default     = isset( $args['default'] ) ? $args['default'] : '';
		$tinymce     = isset( $args['tinymce'] ) ? $args['tinymce'] : '';
		$placeholder = ! empty( $args['placeholder'] ) ? esc_attr( $args['placeholder'] ) : '';
		$data_attr   = '';
		$output      = '';

		$field_name = sprintf( '%s[%s]', $panel, $field );
		$value      = ( isset( $form_data[ $panel ][ $field ] ) && !empty( $form_data[ $panel ][ $field ] ) ) ? 
		$form_data[ $panel ][ $field ] : $default;
		// Check for data attributes.
		if ( ! empty( $args['data'] ) ) {
			foreach ( $args['data'] as $key => $val ) {
				if ( is_array( $val ) ) {
					$val = wp_json_encode( $val );
				}
				$data_attr .= ' data-' . $key . '=\'' . $val . '\'';
			}
		}

		// Determine what field type to output.
		switch ( $option ) {

			// Text input.
			case 'text':
				$type   = ! empty( $args['type'] ) ? esc_attr( $args['type'] ) : 'text';
				$output = sprintf(
					'<input type="%s" id="pie-forms-panel-field-%s-%s" name="%s" value="%s" placeholder="%s" class="widefat %s" %s>',
					$type,
					$this->sanitize_html_class( $panel_id ),
					$this->sanitize_html_class( $field ),
					$field_name,
					esc_attr( $value ),
					$placeholder,
					$input_class,
					$data_attr
				);
				break;

			// Textarea.
			case 'textarea':
				$rows   = ! empty( $args['rows'] ) ? (int) $args['rows'] : '3';
				$output = sprintf(
					'<textarea id="pie-forms-panel-field-%s-%s" name="%s" rows="%d" placeholder="%s" class="widefat %s" %s>%s</textarea>',
					$this->sanitize_html_class( $panel_id ),
					$this->sanitize_html_class( $field ),
					$field_name,
					$rows,
					$placeholder,
					$input_class,
					$data_attr,
					esc_textarea( $value )
				);
				break;

			// TinyMCE.
			case 'tinymce':
				$arguments                  = wp_parse_args(
					$tinymce,
					array(
						'media_buttons' => false,
						'tinymce'       => false,
					)
				);
				$arguments['textarea_name'] = $field_name;
				$arguments['teeny']         = true;
				$id                         = 'pie-forms-panel-field-' . $this->sanitize_html_class( $panel_id ) . '-' . $this->sanitize_html_class( $field );
				$id                         = str_replace( '-', '_', $id );
				ob_start();
				wp_editor( $value, $id, $arguments );
				$output = ob_get_clean();
				break;

			// Checkbox.
			case 'checkbox':
				$checked   = checked( '1', $value, false );
				$checkbox  = sprintf(
					'<input type="hidden" name="%s" value="0" class="widefat %s" %s %s>',
					$field_name,
					$input_class,
					$checked,
					$data_attr
				);
				$checkbox .= sprintf(
					'<input type="checkbox" id="pie-forms-panel-field-%s-%s" name="%s" value="1" class="%s" %s %s>',
					$this->sanitize_html_class( $panel_id ),
					$this->sanitize_html_class( $field ),
					$field_name,
					$input_class,
					$checked,
					$data_attr
				);
				$output    = sprintf(
					'<label for="pie-forms-panel-field-%s-%s" class="inline">%s',
					$this->sanitize_html_class( $panel_id ),
					$this->sanitize_html_class( $field ),
					$checkbox . $label
				);
				if ( ! empty( $args['tooltip'] ) ) {
					$output .= sprintf( ' <i class="dashicons dashicons-editor-help pie-forms-help-tooltip"><span class="tooltip-hover">%s</span></i>', esc_attr( $args['tooltip'] ) );
				}
				$output .= '</label>';
				break;

			// Radio.
			case 'radio':
				$options = $args['options'];
				$x       = 1;
				$output  = '';
				foreach ( $options as $key => $item ) {
					if ( empty( $item['label'] ) ) {
						continue;
					}
					$checked = checked( $key, $value, false );
					$output .= sprintf(
						'<span class="row"><input type="radio" id="pie-forms-panel-field-%s-%s-%d" name="%s" value="%s" class="widefat %s" %s %s>',
						$this->sanitize_html_class( $panel_id ),
						$this->sanitize_html_class( $field ),
						$x,
						$field_name,
						$key,
						$input_class,
						$checked,
						$data_attr
					);
					$output .= sprintf(
						'<label for="pie-forms-panel-field-%s-%s-%d" class="inline">%s',
						$this->sanitize_html_class( $panel_id ),
						$this->sanitize_html_class( $field ),
						$x,
						$item['label']
					);
					if ( ! empty( $item['tooltip'] ) ) {
						$output .= sprintf( ' <i class="dashicons dashicons-editor-help pie-forms-help-tooltip"><span class="tooltip-hover">%s</span></i>', esc_attr( $item['tooltip'] ) );
					}
					$output .= '</label></span>';
					$x ++;
				}
				break;

			// Select.
			case 'select':
				if ( empty( $args['options'] ) && empty( $args['field_map'] ) ) {
					return '';
				}

				if ( ! empty( $args['field_map'] ) ) {
					$options          = array();
					$available_fields = pf_get_form_fields( $form_data, $args['field_map'] );
					if ( ! empty( $available_fields ) ) {
						foreach ( $available_fields as $id => $available_field ) {
							$lbl            = ! empty( $available_field['label'] ) ? esc_attr( $available_field['label'] ) : esc_html__( 'Field #', 'pie-forms' ) . $id;
							$options[ $id ] = $lbl;
						}
					}
					$input_class .= ' pie-forms-field-map-select';
					$data_attr   .= ' data-field-map-allowed="' . implode( ' ', $args['field_map'] ) . '"';
					if ( ! empty( $placeholder ) ) {
						$data_attr .= ' data-field-map-placeholder="' . esc_attr( $placeholder ) . '"';
					}
				} else {
					$options = $args['options'];
				}

				$output = sprintf(
					'<select id="pie-forms-panel-field-%s-%s" name="%s" class="widefat %s" %s>',
					$this->sanitize_html_class( $panel_id ),
					$this->sanitize_html_class( $field ),
					$field_name,
					$input_class,
					$data_attr
				);

				if ( ! empty( $placeholder ) ) {
					$output .= '<option value="">' . $placeholder . '</option>';
				}

				foreach ( $options as $key => $item ) {
					$output .= sprintf( '<option value="%s" %s>%s</option>', esc_attr( $key ), selected( $key, $value, false ), $item );
				}

				$output .= '</select>';
				break;
		}

		$smarttags_class = ! empty( $args['smarttags'] ) ? 'pf_smart_tag' : '';

		// Put the pieces together....
		$field_open  = sprintf(
			'<div id="pie-forms-panel-field-%s-%s-wrap" class="pie-forms-panel-field %s %s %s">',
			$this->sanitize_html_class( $panel_id ),
			$this->sanitize_html_class( $field ),
			$class,
			$smarttags_class,
			'pie-forms-panel-field-' . $this->sanitize_html_class( $option )
		);
		$field_open .= ! empty( $args['before'] ) ? $args['before'] : '';
		if ( ! in_array( $option, array( 'checkbox' ), true ) && ! empty( $label ) ) {
			$field_label = sprintf(
				'<label for="pie-forms-panel-field-%s-%s">%s',
				$this->sanitize_html_class( $panel_id ),
				$this->sanitize_html_class( $field ),
				$label
			);
			if ( ! empty( $args['tooltip'] ) ) {
				$field_label .= sprintf( ' <i class="dashicons dashicons-editor-help pie-forms-help-tooltip"><span class="tooltip-hover">%s</span></i>', esc_attr( $args['tooltip'] ) );
			}
			if ( ! empty( $args['after_tooltip'] ) ) {
				$field_label .= $args['after_tooltip'];
			}
			if ( ! empty( $args['smarttags'] ) ) {
				$smart_tag = '';

				$type        = ! empty( $args['smarttags']['type'] ) ? esc_attr( $args['smarttags']['type'] ) : 'form_fields';
				$form_fields = ! empty( $args['smarttags']['form_fields'] ) ? esc_attr( $args['smarttags']['form_fields'] ) : '';
				$smart_tag .= '<a href="#" class="pf-toggle-smart-tag-display" data-type="' . $type . '" data-fields="' . $form_fields . '"><span class="dashicons dashicons-shortcode"></span></a>';

				$smart_tag .= '<div class="pf-smart-tag-lists ScrollBar " style="display: none">';
				$smart_tag .= '<div class="smart-tag-title">';
				$smart_tag .= esc_html__( 'Available Fields', 'pie-forms' );
				$smart_tag .= '</div><ul class="pf-fields"></ul>';
				if ( 'all' === $type || 'other' === $type ) {
					$smart_tag .= '<div class="smart-tag-title other-tag-title">';
					$smart_tag .= esc_html__( 'Others', 'pie-forms' );
					$smart_tag .= '</div><ul class="pf-others"></ul>';
				}
				$smart_tag .= '</div>';
			} else {
				$smart_tag = '';
			}

			$field_label .= '</label>';
		} else {
			$field_label = '';
			$smart_tag   = '';
		}
		$field_close  		 = ! empty( $args['after'] ) ? $args['after'] : '';
		$field_close 		.= '</div>';
		$smart_tag_output    = '<div class="pie-form-smart-tag-parent">'.$output . $smart_tag.'</div>';
		$output 			 = $field_open . $field_label . $smart_tag_output  . $field_close;

		if ( $echo ) {
			echo $output; 
		} else {
			return $output;
		}
	}

	function sanitize_html_class( $class, $fallback = '' ) {
		// Strip out any %-encoded octets.
		$sanitized = preg_replace( '|%[a-fA-F0-9][a-fA-F0-9]|', '', $class );

		// Limit to A-Z, a-z, 0-9, '_', '-'.
		$sanitized = preg_replace( '/[^A-Za-z0-9_-]/', '', $sanitized );

		if ( '' == $sanitized && $fallback ) {
			return sanitize_html_class( $fallback );
		}
		/**
		 * Filters a sanitized HTML class string.
		 */
		return apply_filters( 'sanitize_html_class', $sanitized, $class, $fallback );
	}

	function pie_html_attributes( $id = '', $class = array(), $datas = array(), $atts = array(), $echo = false ) {
		$id    = trim( $id );
		$parts = array();

		if ( ! empty( $id ) ) {
			$id = sanitize_html_class( $id );
			if ( ! empty( $id ) ) {
				$parts[] = 'id="' . $id . '"';
			}
		}

		if ( ! empty( $class ) ) {
			$class = $this->pf_sanitize_classes( $class, true );
			if ( ! empty( $class ) ) {
				$parts[] = 'class="' . $class . '"';
			}
		}

		if ( ! empty( $datas ) ) {
			foreach ( $datas as $data => $val ) {
				$parts[] = 'data-' . sanitize_html_class( $data ) . '="' . esc_attr( $val ) . '"';
			}
		}

		if ( ! empty( $atts ) ) {
			foreach ( $atts as $att => $val ) {
				if ( '0' === $val || ! empty( $val ) ) {
					$parts[] = sanitize_html_class( $att ) . '="' . esc_attr( $val ) . '"';
				}
			}
		}

		$output = implode( ' ', $parts );

		if ( $echo ) {
			echo trim( $output ); 
		} else {
			return trim( $output );
		}
	}

	
/**
 * Array combine.
 */
function pf_sanitize_array_combine( $array ) {
	if ( empty( $array ) || ! is_array( $array ) ) {
		return $array;
	}

	return array_map( 'sanitize_text_field', $array );
}

	function pf_sanitize_classes( $classes, $convert = false ) {
		$css   = array();
		$array = is_array( $classes );

		if ( ! empty( $classes ) ) {
			if ( ! $array ) {
				$classes = explode( ' ', trim( $classes ) );
			}
			foreach ( $classes as $class ) {
				$css[] = sanitize_html_class( $class );
			}
		}

		if ( $array ) {
			return $convert ? implode( ' ', $css ) : $css;
		} else {
			return $convert ? $css : implode( ' ', $css );
		}
	}

	function pie_string_translation( $form_id, $field_id, $value, $suffix = '' ) {
		$context = isset( $form_id ) ? 'pie_forms_' . absint( $form_id ) : 0;
		$name    = isset( $field_id ) ? $field_id : '';

		if ( function_exists( 'icl_register_string' ) ) {
			icl_register_string( $context, $name, $value );
		}

		if ( function_exists( 'icl_t' ) ) {
			$value = icl_t( $context, $name, $value );
		}

		return $value;
	}

	/**
	 * Get random meta-key for field option.
	 */
	function pf_get_meta_key_field_option( $field ) {
		$random_number = rand( pow( 10, 3 ), pow( 10, 4 ) - 1 ); // phpcs:ignore WordPress.WP.AlternativeFunctions.rand_rand
		return strtolower( str_replace( array( ' ', '/_' ), array( '_', '' ), $field['label'] ) ) . '_' . $random_number;
	}

	/**
	 * Checks if field exists within the form.
	 */
	function pie_is_field_exists( $form_id, $field ) {
				
		$form_obj  		= Pie_Forms()->form()->get( $form_id );

		$form_shift     = array_shift($form_obj);
		$form_data = ! empty( $form_shift->form_data ) ? $this->pf_decode( wp_unslash($form_shift->form_data) ) : '';
		
		if ( ! empty( $form_data['form_fields'] ) ) {
			foreach ( $form_data['form_fields'] as $form_field ) {
				if ( $field === $form_field['type'] ) {
					return true;
				}
			}
		}

		return false;
	}

	function pf_decode_string( $string ) {
		if ( ! is_string( $string ) ) {
			return $string;
		}
	
		return wp_kses_decode_entities( html_entity_decode( $string, ENT_QUOTES ) );
	}

	function pf_sanitize_textarea_field( $string ) {
		if ( empty( $string ) || ! is_string( $string ) ) {
			return $string;
		}
	
		if ( function_exists( 'sanitize_textarea_field' ) ) {
			$string = sanitize_textarea_field( $string );
		} else {
			$string = implode( "\n", array_map( 'sanitize_text_field', explode( "\n", $string ) ) );
		}
	
		return $string;
	}

	//EMAIL TEMPLATE INCLUDE
	function pf_get_template( $template_name) {
		
		$template = $this->pf_locate_template($template_name);
	
		$action_args = array(
			'template_name' => $template_name,
			'located'       => $template,
			
		);
	
		include $action_args['located'];
			
	}

	function pf_locate_template( $template_name ) {
		$template_path = Pie_Forms::$dir.'includes/templates/'.$template_name;
		return  $template_path;
	}

	//TEMPLATE JSON
	function templateJson(){
		//$templatejson = "https://raw.githubusercontent.com/haidersayani/Pie-Form/master/Template-All/Form.json";
		$templatejson = Pie_Forms::$dir.'includes/templates/forms.json';
		return $templatejson;
	}

	/**
	 * Get the required label text, with a filter.
	 */
	function pf_get_required_label() {
		return apply_filters( 'pie_forms_required_label', esc_html__( 'This field is required.', 'pie-forms' ) );
	}

	/**
	 * Define Regex.
	 */	
	function get_define_regex($value,$key){
		$regex = '';
		switch ( $key ) {
			case 'alpha_only':
				$regex = "^[a-zA-Z ]*$";
				break;
			case 'phone':
				$regex = "^(?=.*[0-9])[- +()0-9]+$";
				break;
		}

		if ( !preg_match('/'.$regex.'/', $value) )
			{
				return true;
				
			}
	}

	/**
	 * Get all forms.
	 */
	function pf_get_all_forms( $skip_disabled_entries = false ) {
		$forms    = array();
		
		$form_ids = Pie_Forms()->form()->get_result();
		
		if(!empty($form_ids)){

			foreach ($form_ids as $key => $value) {
				$form_id 		= $value->id;	
				$form_data 		= $this->get_form_fields($form_id);
				
				if ( ( $skip_disabled_entries ) && ( isset( $form_data['form_enabled'] ) && '1' === $form_data['form_enabled'] ) ) {
					continue;
				}
				
				$forms[ $form_id ] = $form_data['settings']['form_title'];
				
			}
		}
		
		return $forms;
	}

	public function get_pro_form_field_types() {
		$_available_fields = array();
		$this_field = pie_forms()->form_fields();
		if ( count( $this_field ) > 0 ) {
			foreach ( array_values( $this_field ) as $form_field ) {
				foreach ( $form_field as $field ) {
					if ( $field->is_pro ) {
						$_available_fields[] = $field->type;
					}
				}
			}
		}

		return $_available_fields;
	}

}