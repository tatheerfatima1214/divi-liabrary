<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class PIE_Database_Models_Form
 */
final class PIE_Database_Models_Forms
{
   

    public function __construct( )
    {
        add_action( 'admin_init', array( $this, 'actions' ) );
    }

	//GET PF_FORMS RESULT
    public function get_result(){
        global $wpdb;
        $table_name = $wpdb->prefix . 'pf_forms';
        $results = $wpdb->get_results( "SELECT * FROM $table_name");

        return $results;
	}
	
	//GET PUBLISHED POST
	public function get_status_except_trash(){
        global $wpdb;
		$table_name = $wpdb->prefix . 'pf_forms';
        $results = $wpdb->get_results( "SELECT * FROM $table_name WHERE post_status = 'published' ");

        return $results;
    }

	//GET FORM ID FROM PF_FIELDS 
    public function get($form_id){
    	global $wpdb;
		$form_data = $wpdb->get_results( 
			$wpdb->prepare("SELECT * FROM {$wpdb->prefix}pf_fields WHERE form_id = %d", $form_id), OBJECT );
		return $form_data;
		
	}

	//Get All Forms PF_FIELDS
	public function get_multiple(){
    	global $wpdb;
		$table_name = $wpdb->prefix . 'pf_fields';
        $form_data = $wpdb->get_results( "SELECT * FROM $table_name");
		return $form_data;
		
	}
	
	//GET POST STATUS
	public function get_status($status){
		
		global $wpdb;
		$table_name = $wpdb->prefix . 'pf_forms';
		
        $results = $wpdb->get_results( 
			$wpdb->prepare("SELECT * FROM $table_name WHERE post_status = %s",$status)
		);
		
        return count($results);
	}

	//GET DATA FROM STATUS
	public function get_data_by_status($status){
		
		global $wpdb;
		$table_name = $wpdb->prefix . 'pf_forms';
		
        $results = $wpdb->get_results( 
			$wpdb->prepare("SELECT * FROM $table_name WHERE post_status = %s",$status)
		);
		
        return $results;
	}
	
	
	//RESTORE FORM
	public function restore($form_id){
		global $wpdb;
		
		$table_name = $wpdb->prefix . 'pf_forms';
		$data = array( 'post_status' => 'published');
		$where = ['id' => $form_id];
		$results = $wpdb->update( $table_name, $data, $where );
			
        return $results;
	}


   //UPDATE FORM
	public function update( $form_id = '', $data = array(), $args = array() ) {
		global $wpdb;

		if ( empty( $data ) ) {
			return false;
		}

		if ( empty( $form_id ) ) {
			$form_id = $data['form_id'];
		}
		$data = wp_unslash( $data );

		if ( ! empty( $data['settings']['form_title'] ) ) {
			$title = $data['settings']['form_title'];
		} else {
			$title = get_the_title( $form_id );
		}
		

		$data['form_field_id'] = ! empty( $data['form_field_id'] ) ? absint( $data['form_field_id'] ) : '0';
		$data    = array(
			'form_title' => $title,
			'form_data' => PIE_Forms()->core()->pf_encode( $data ),
			'form_type'	=> "",
		);

		
		
		//for creating template added var_dump 
		$where = [ 'form_id' => $form_id ];
		$wpdb->update( $wpdb->prefix . 'pf_fields', $data, $where ); 
		$wpdb->update( $wpdb->prefix . 'pf_forms', array( "form_title" => $title), array("id"=>$form_id) ); 
		

		do_action( 'pie_forms_save_form', $form_id, $data );
		
		return $form_id;
	}

	//GET DUPLICATE FORM
	public static function duplicate_form(){
		global $wpdb;

		$id = $_REQUEST['id'];

		check_admin_referer( 'pie-forms-duplicate-form_' . $id );

		//DUPLICATE PF_FORMS
		$wpdb->query( 	
			$wpdb->prepare( 
				"INSERT INTO {$wpdb->prefix}pf_forms (form_title,post_status) SELECT form_title, post_status FROM {$wpdb->prefix}pf_forms WHERE id = %d",$id 
			)
		);
		$duplicate_id = $wpdb->insert_id;
		
		//DUPLICATE PF_FIELDS
		$wpdb->query(
			$wpdb->prepare( 
				"INSERT INTO {$wpdb->prefix}pf_fields (form_id,form_title,form_data,form_type ) SELECT {$duplicate_id},form_title,form_data,form_type FROM {$wpdb->prefix}pf_fields WHERE form_id = %d",$id
				)
			);
	
		
		$redirect_url =  get_admin_url( null, 'admin.php?page=pie-forms&id=' . $duplicate_id );
		wp_safe_redirect( $redirect_url );
		exit;
	  }
	
	// COMMON ACTIONS
	public function actions() {
		if(!isset($_REQUEST['page']) || $_REQUEST['page'] != 'pie-forms'){
			return false;
		}

			// Duplicate form.
			if ( isset( $_REQUEST['action'] ) && 'duplicate_form' === $_REQUEST['action'] ) {
				$this->duplicate_form();
			}

			if ( isset( $_REQUEST['action'] ) && 'delete' === $_REQUEST['action'] ) {
				$nonce = esc_attr( $_REQUEST['_wpnonce'] );
		  
			  
			  if ( ! wp_verify_nonce( $nonce, 'pf_delete_form' ) ) {
				die( 'No naughty business' );
			  }
			  else {
				  self::delete_form( absint( $_GET['form'] ) );
				  $redirect_url =  get_admin_url( null, 'admin.php?page=pie-forms' );
	
				  wp_safe_redirect( $redirect_url );
				exit;
			  }
		  
			}

			//Trash

			
			if ( ( isset( $_GET['action'] ) && 'trash' === $_GET['action'] )  ) {

				$nonce = esc_attr( $_REQUEST['_wpnonce'] );
				if ( ! wp_verify_nonce( $nonce, 'pf_trash_form' ) ) {
					die( 'No naughty business' );
				  }else{
					  $this->trash_form(absint( $_GET['id']));
				  }
			}

			//UnTrash

			if ( ( isset( $_REQUEST['action'] ) && 'restore' === $_REQUEST['action'] ) && 
				 ( isset( $_REQUEST['page'] ) && 'pie-forms' === $_REQUEST['page'] ) ) {
				$this->restore(absint( $_GET['id']));
			}

			// If the delete bulk action is triggered
			if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
			|| ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
			) {
				
			
				$delete_ids = esc_sql( $_POST['form_id'] );
				// loop over the array of record IDs and delete them
				foreach ( $delete_ids as $id ) {
					self::delete_form( $id );
			
				}
			
				$redirect_url =  get_admin_url( null, 'admin.php?page=pie-forms' );

				wp_safe_redirect( $redirect_url );
				exit;
			}
			
			// If the trash bulk action is triggered
			if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'trash' )
			|| ( isset( $_POST['action2'] ) && $_POST['action2'] == 'trash' )
			) {
			
				$trash_ids = esc_sql( $_POST['form_id'] );
				// loop over the array of record IDs and delete them
				foreach ( $trash_ids as $id ) {
					
					self::trash_form( $id );
			
				}
			
				$redirect_url =  get_admin_url( null, 'admin.php?page=pie-forms' );

				wp_safe_redirect( $redirect_url );
				exit;
			}


			// If the restore bulk action is triggered
			if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-restore' )
			|| ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-restore' )
			) {
			
				$restore_ids = esc_sql( $_POST['form_id'] );
				// loop over the array of record IDs and delete them
				foreach ( $restore_ids as $id ) {
					
					self::restore( $id );
			
				}
			
				$redirect_url =  get_admin_url( null, 'admin.php?page=pie-forms' );

				wp_safe_redirect( $redirect_url );
				exit;
			}
	}
	
	//DELETE A FORM RECORD
    public static function delete_form( $id ) {
        global $wpdb;
        
        //DELETE PF FORMS
        $wpdb->delete(
        "{$wpdb->prefix}pf_forms",
        [ 'id' => $id ],
        [ '%d' ]
        );

        //DELETE PF FIELDS
        $wpdb->delete(
            "{$wpdb->prefix}pf_fields",
            [ 'form_id' => $id ],
            [ '%d' ]
        );

       
	}
	
	//TRASH A FORM RECORD
    public static function trash_form( $form_id ) {

		global $wpdb;

		$data = [ 'post_status' => "trash" ]; 
		$where = [ 'id' => $form_id ]; 
		$wpdb->update( $wpdb->prefix . 'pf_forms', $data, $where );

	}

} // End PIE_Database_Models_Form
