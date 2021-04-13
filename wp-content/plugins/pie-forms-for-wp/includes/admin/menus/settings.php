<?php if ( ! defined( 'ABSPATH' ) ) exit;

final class PIE_Admin_Menus_Settings extends PIE_Abstracts_Submenu
{
    public $parent_slug = 'pie-forms';

    public $menu_slug = 'pf-settings';

    public $priority = 11;

    protected $_prefix = 'pie_forms';

    public function __construct()
    {
        parent::__construct();


    }

    public function get_page_title()
    {
        return esc_html__( 'Global Settings', 'pie-forms' );
    }

    public function get_capability()
    {
        return $this->capability;
    }

    public function display()
    {
        wp_register_script( 'SettingJS', Pie_Forms::$url . 'assets/js/setting.js', array(), Pie_Forms::VERSION );
        wp_enqueue_script( 'SettingJS');

        wp_enqueue_style('SettingPage', Pie_Forms::$url . 'assets/css/setting.css', array(), Pie_Forms::VERSION );
        $this->settings_page_init();

        self::output();

    }

    public static function output() {
        // global $current_section, $current_tab;

        // // Get tabs for the settings page.
        // $tabs = apply_filters( 'pie_forms_settings_tabs_array', array() );

        Pie_Forms::template( 'settings.php' );

        

        ///var_dump($tabs);die;
    }
    	/**
	 * Loads settings page.
	 */
	public function settings_page_init() {
		global $current_tab, $current_section;
		// Get current tab/section.
		$current_tab     = empty( $_GET['tab'] ) ? 'general' : sanitize_title( wp_unslash( $_GET['tab'] ) ); 

        $current_section = empty( $_REQUEST['section'] ) ? '' : sanitize_title( wp_unslash( $_REQUEST['section'] ) ); 

		// Save settings if data has been posted.
		if ( apply_filters( '' !== $current_section ? "pie_forms_save_settings_{$current_tab}_{$current_section}" : "pie_forms_save_settings_{$current_tab}", ! empty( $_POST ) ) ) { 
            PIE_Admin_Settings::save();
            
		}

		// Add any posted messages.
		if ( ! empty( $_GET['pf_error'] ) ) { 
			PIE_Admin_Settings::add_error( wp_kses_post( wp_unslash( $_GET['pf_error'] ) ) ); 
		}

		if ( ! empty( $_GET['pf_message'] ) ) { 
			PIE_Admin_Settings::add_message( wp_kses_post( wp_unslash( $_GET['pf_message'] ) ) ); 
        }
        


		do_action( 'pie_forms_settings_page_init' );
	}

} // End Class PIE_Admin_Settings
