<?php
/*
Plugin Name: Pie Forms
Description: The simple and flexible WordPress form plugin. Drag & Drop form builder to help create simple to complex forms for any purpose with just a few clicks.
Version: 1.4.6
Author: Genetech Solutions
Author URI: https://www.genetechsolutions.com
Text Domain: pie-forms
Domain Path: /languages/
*/

// Define PF_PLUGIN_FILE.
if ( ! defined( 'PF_PLUGIN_FILE' ) ) {
	define( 'PF_PLUGIN_FILE', __FILE__ );
}
register_activation_hook(__FILE__, 'deactivate_premium');

/**
  * Activation
 */
function deactivate_premium() {
    if(is_plugin_active('pie-forms-for-wp-premium/pie-forms-for-wp-premium.php') )
	{  
		deactivate_plugins('pie-forms-for-wp-premium/pie-forms-for-wp-premium.php');				
    }
}

if( !class_exists('Pie_Forms')){
final class Pie_Forms
    {

        const VERSION = '1.4.6';
        
        const DB_VERSION = '1.1';

        private static $instance;

        /**
         * Plugin Directory
         *
         * @var string $dir
         */
        public static $dir = '';
        
        /**
         * Plugin URL
         *
         * @var string $url
         */
        public static $url = '';

        /**
         * Admin Menus
         *
         * @var array
         */
        public $menus = array();
        
        public $builder = array();
        
        
        public $task;
        
        public $db = array();

        /**
         * AJAX Controllers
         *
         * @var array
         */
        public $ajax = array();

        /**
         * Form Fields
         *
         * @var array
         */
        public $fields = array();
        public $core = array();
        public $form_fields = array();

        /**
         * Form Actions
         *
         * @var array
         */
        public $actions = array();

        /**
         * Merge Tags
         *
         * @var array
         */
        public $merge_tags = array();

        /**
         * Metaboxes
         *
         * @var array
         */
        public $metaboxes = array();

        /**
         * Model Factory
         *
         * @var object
         */
        public $factory = '';

        /**
         * Logger
         *
         * @var string
         */
        protected $_logger = '';

        /**
         * Dispatcher
         *
         * @var string
         */
        protected $_dispatcher = '';

        /**
         * @var PIE_Session
         */
        public $session = null;

        /**
         * @var PIE_Tracking
         */
        public $tracking;

        /**
         * Plugin Settings
         */
        protected $settings = array();

        protected $requests = array();

        protected $processes = array();
       /**
         * PieForms Constructor.
        */
        public function __construct() {
           
            
        }


        public static function instance()
        {
            if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Pie_Forms ) ) {
                self::$instance = new Pie_Forms;
                self::$dir = plugin_dir_path( __FILE__ );
                self::$url = plugin_dir_url( __FILE__ );

               
                /*
                * Register our autoloader
                */
                spl_autoload_register( array( self::$instance, 'autoloader' ) );
                /*
                * Admin Menus
                */
                self::$instance->menus[ 'main' ]                = new PIE_Admin_Menus_Main();
                self::$instance->menus[ 'form_id' ]             = new PIE_Admin_Menus_Builder();
                self::$instance->menus[ 'all_forms' ]           = new PIE_Admin_Menus_AllForms();
                self::$instance->menus[ 'add_new' ]             = new PIE_Admin_Menus_AddNew();
                self::$instance->menus[ 'settings' ]            = new PIE_Admin_Menus_Settings();
                self::$instance->menus[ 'marketing' ]           = new PIE_Admin_Menus_Marketing();
                self::$instance->menus[ 'entries' ]             = new PIE_Admin_Menus_Entries();
                self::$instance->menus[ 'tools' ]               = new PIE_Admin_Menus_Tools();
                self::$instance->menus[ 'blockuser' ]           = new PIE_Admin_Menus_Blockuser();
                self::$instance->ajax                           = new PIE_Core_Ajax();
                self::$instance->task                           = new PIE_Form_Task();
                
                
                register_activation_hook( __FILE__, array( self::$instance, 'activation' ) );
                register_deactivation_hook( __FILE__, array( self::$instance, 'deactivate' ) );
                
                
                add_action( 'plugins_loaded', array( self::$instance, 'plugins_loaded' ) );
                
                
                $shortcode                      = new PIE_Shortcodes_Form();
                $display                        = new PIE_Shortcodes_Display();                    
                $block                          = new PIE_Shortcodes_Block();
                $PIE_Settings_reCAPTCHA         = new PIE_Settings_reCAPTCHA();
                $PIE_Settings_Validation        = new PIE_Settings_Validation();
                $PIE_Tools_Environment          = new PIE_Tools_Environment();
                $PIE_Settings_General           = new PIE_Settings_General();
                $PIE_Tools_PluginsThemes        = new PIE_Tools_PluginsThemes();
                $PIE_Blockuser_Username         = new PIE_Blockuser_Username();
                $PIE_Blockuser_Emailaddress     = new PIE_Blockuser_Emailaddress();
                $PIE_Blockuser_IPaddress        = new PIE_Blockuser_IPaddress();
                $PIE_Settings_Email             = new PIE_Settings_Email();
                $tag                            = new PIE_Email_Tags();
                $PIE_Admin_Editor               = new PIE_Admin_Editor();
                $PIE_db_Models_FormsEntries     = new PIE_Database_Models_FormsEntries();
                $PIE_Core_Install               = new PIE_Core_Install();
                $PIE_Admin_Notices              = new PIE_Admin_Notices();
                
                //ENQUEUE
                $PIE_Enqueue_Assets             = new PIE_Enqueue_Assets();
                
                add_action( 'init', array( 'PIE_Shortcodes_Display', 'init' ), 0 );
                add_action( 'init', array( 'PIE_Form_Preview', 'init' ) );
                // add_action( 'init', array( 'PIE_Core_Install', 'init' ) );
                
            }
            
            return self::$instance;
        }
        

        /**
         * Activation
         */
        public function activation() {

            //create PIEFORMS tables 
            $database = new PIE_Database_DbTables();
            $database->run();
        }
        
        /**
         * Dectivation
         */
        
        public function deactivate() {
            
        }

        /**
         * Get the plugin url.
         */
        public function plugin_url() {
            return untrailingslashit( plugins_url( '/', PF_PLUGIN_FILE ) );
        }

        public function form(  )
        {
            $forms = new PIE_Database_Models_Forms;
            return $forms;

        }

        public function templateView(){
            $PIE_Admin_Template = new PIE_Admin_Template;
            return $PIE_Admin_Template;
        }

        public function core(  ){
            $core = new PIE_Core_Functions();
            
            return $core;
        }


        /**
         * Load Classes from Directory
         *
         * @param string $prefix
         * @return array
         */
        private static function load_classes( $prefix = '' )
        {
            $return = array();
            
            $subdirectory = str_replace( '_', DIRECTORY_SEPARATOR, str_replace( 'PIE_', '', $prefix ) );
            $lower_case_subdir = strtolower($subdirectory);
            $directory = 'includes/' . $lower_case_subdir;
            foreach (scandir( self::$dir . $directory ) as $path) {
                
                $path = explode( DIRECTORY_SEPARATOR, str_replace( self::$dir, '', $path ) );
                $filename = str_replace( '.php', '', end( $path ) );
                $class_name = 'PIE_' . $prefix . '_' . $filename;
                
                if( ! class_exists( $class_name ) ) continue;
                
                $return[] = new $class_name;
            }

            return $return;
        }
       
        /**
         * Autoloader
         *
         * Autoload Pie Forms classes
         *
         * @param $class_name
         */
        public function autoloader( $class_name )
        {
            if( class_exists( $class_name ) ) return;

            /* Pie Forms Prefix */
            if (false !== strpos($class_name, 'PIE_')) {
                $class_name = str_replace('PIE_', '', $class_name);
                $classes_dir = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
                $class_file = str_replace('_', DIRECTORY_SEPARATOR, $class_name) . '.php';
                $class_lower = strtolower($class_file);
                if (file_exists($classes_dir . $class_lower)) {
                    require_once $classes_dir . $class_lower;
                }

            }

        }


        public function plugins_loaded()
        {
            self::$instance->builder['field']                        = new PIE_Admin_Builder_Field();
            self::$instance->builder['setting']                      = new PIE_Admin_Builder_Settings();
                
           
            /*
             * Field Class Registration
             */
            self::$instance->fields = apply_filters( 'PIE_forms_register_fields', self::load_classes( 'Fields' ) );
            
            // Get sort order.
            $order_end = 999;
            foreach ( self::$instance->fields as $field ) {
               
                if ( isset( $field->order ) && is_numeric( $field->order ) ) {
                    // Add in position.
                    self::$instance->form_fields[ $field->group ][ $field->order ] = $field;
                } else {
                    // Add to end of the array.
                    self::$instance->form_fields[ $field->group ][ $order_end ] = $field;
                    $order_end++;
                }
               
                ksort( self::$instance->form_fields[ $field->group ] );
                
            }

          
            load_plugin_textdomain( 'pie-forms', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );

         

        }

      
        
        public function form_fields() {
            $_available_fields = array();
            if ( count( self::$instance->form_fields ) > 0 ) {
                foreach ( self::$instance->form_fields as $group => $field ) {
                    $_available_fields[ $group ] = $field;
                }
            }


            return $_available_fields;
        }

        /*
         * STATIC METHODS
         */

        /**
         * Template
         *
         * @param string $file_name
         * @param array $data
         */
        public static function template( $file_name = '', array $data = array(), $return = FALSE )
        {
            if( ! $file_name ) return FALSE;

            extract( $data );

            $path = self::$dir . 'includes/templates/' . $file_name;
            if( ! file_exists( $path ) ) return FALSE;

            if( $return ) return file_get_contents( $path );
            include $path;
        }


    } // End Class Pie_Forms



    /**
     * The main function responsible for returning The Highlander Pie_Forms
     * Instance to functions everywhere.
     *
     * Use this function like you would a global variable, except without needing
     * to declare the global.
     *
     * Example: <?php $Pie = Pie_Forms(); ?>
     *
     * @return Pie_Forms Highlander Instance
     */
} 

if( !function_exists( 'Pie_Forms' ) ) {
    function Pie_Forms()
    {
        return Pie_Forms::instance();
    }
} 
Pie_Forms();