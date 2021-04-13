<?php if ( ! defined( 'ABSPATH' ) ) exit;

final class PIE_Admin_Menus_Main extends PIE_Abstracts_Menu
{
    public $page_title = 'Pie Forms';

    public $menu_slug = 'pie-forms';

    public $icon_url = 'dashicons-text-page';

    public $position = '38';

    public $ver = Pie_Forms::VERSION;
    public $field_type_settings = array();

    public function __construct()
    {
        parent::__construct();

            

    }


    public function get_page_title()
    {
        return esc_html__( 'Pie Forms', 'pie-forms' );
    }

  
    public function display()
    {
        
        if( !isset($_GET[ 'form_id' ]) && $_GET['page'] == $this->menu_slug) {
        
        ?>

            <div class="wrap">
                <h1 class="wp-heading-inline"><?php esc_html_e( 'All Forms', 'pie-forms' ); ?></h1>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=pie-addnew' ) ); ?>" class="page-title-action"><?php esc_html_e( 'New Form', 'pie-forms' ); ?></a>
                <?php
                    $table_data      = new PIE_Admin_AllFormTable();
                ?>
            </div>            

            <?php
            
            $arguments = array(
                'label'     =>  __( 'Users Per Page', 'pie-forms' ),
                'default'   =>  5,
                'option'    =>  'pie_form_per_page'
            );
            add_screen_option( 'per_page', $arguments );

            wp_enqueue_style('TableGrid', Pie_Forms::$url . 'assets/css/admin-table.css', array(), Pie_Forms::VERSION );
        }

    }

  
    public function get_capability()
    {
        return apply_filters( 'pie_forms_admin_parent_menu_capabilities', $this->capability );

    }

   /*  public function screen_option(){
      return true;
    }
 */
}
