<?php
/**
 * Shortcodes
 */

defined( 'ABSPATH' ) || exit;

class PIE_Shortcodes_Display {

	/**
	 * Init shortcodes.
	 */
	public static function init() {
		self::init_shortcode_hooks();

		$shortcodes = array(
			'pie_form' => __CLASS__ . '::form',
		);

		foreach ( $shortcodes as $shortcode => $function ) {
			add_shortcode( apply_filters( "{$shortcode}_shortcode_tag", $shortcode ), $function );
		}
	}

	/**
	 * Shortcode Wrapper.
	 */
	public static function shortcode_wrapper(
		$function,
		$atts = array(),
		$wrapper = array(
			'class'  => 'pie-forms',
			'before' => null,
			'after'  => null,
		)
	) {
		ob_start();

		echo empty( $wrapper['before'] ) ? '<div class="' . esc_attr( $wrapper['class'] ) . '">' : $wrapper['before'];
		call_user_func( $function, $atts );
		echo empty( $wrapper['after'] ) ? '</div>' : $wrapper['after'];


		return ob_get_clean();
	}

	/**
	 * Form shortcode.
	 */
	public static function form( $atts ) {
		return self::shortcode_wrapper( array( 'PIE_Shortcodes_Form', 'output' ), $atts );
	}

	/**
	 * Initialize shortcode.
	 */
	public static function init_shortcode_hooks() {
		self::shortcode_wrapper( array( 'PIE_Shortcodes_Form', 'hooks' ) );
	}
}
