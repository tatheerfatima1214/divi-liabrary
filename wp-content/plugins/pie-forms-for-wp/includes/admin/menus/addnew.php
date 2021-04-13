<?php if ( ! defined( 'ABSPATH' ) ) exit;

final class PIE_Admin_Menus_AddNew extends PIE_Abstracts_Submenu
{
    public $parent_slug = 'pie-forms';

    //public $page_title = '';

    public $menu_slug = 'pie-addnew';

	public $priority = 11;

    protected $_prefix = 'pie_forms';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_page_title()
    {
        return esc_html__( 'New Form', 'pie-forms' );
    }

    public function get_capability()
    {
        return $this->capability;
    }

    public function display()
    {
        Pie_Forms::template( 'add-new.php' );

		

    }

} // End Class PIE_Admin_Addnew
