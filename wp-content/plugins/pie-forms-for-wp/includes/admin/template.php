<?php

defined( 'ABSPATH' ) || exit;


class PIE_Admin_Template {


	public function __construct() {

	}


	public static function page_output() {
        
		 
		$templates       = array();
		$refresh_url     = add_query_arg(
			array(
				'page'               => 'pf-builder&create-form=1',
				'action'             => 'pf-template-refresh',
				'pf-template-nonce' => wp_create_nonce( 'refresh' ),
			),
			admin_url( 'admin.php' )
		);
		$category  = 'free'; 
		$templates = self::get_template_data( $category );
			return $templates;

}

/**
 * Get section content for the template screen.
 */
public static function get_template_data() {
	
		$raw_templates = file_get_contents(Pie_Forms()->core()->templateJson());
				
		if ( ! is_wp_error( $raw_templates ) ) {
			$template_data = json_decode(  $raw_templates );
			foreach ( $template_data->templates as $template_tuple ) {
				// We retrieve the image, then use them instead of the remote server.
				$image = wp_remote_get( $template_tuple->image );
				$type  = wp_remote_retrieve_header( $image, 'content-type' );

				// Remote file check failed, we'll fallback to remote image.
				if ( ! $type ) {
					continue;
				}

				
			}

			if ( ! empty( $template_data->templates ) ) {
				set_transient( 'pf_template_section', $template_data, WEEK_IN_SECONDS );
			}
		}

		if ( ! empty( $template_data->templates ) ) {
			return apply_filters( 'pie_forms_template_section_data', $template_data->templates );
		}
	}

}