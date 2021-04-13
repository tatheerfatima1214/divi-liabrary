
<?php
defined( 'ABSPATH' ) || exit;

global $current_section, $current_tab;

$tabs = apply_filters( 'pie_forms_marketing_tabs_array', array() );


$tab_exists        = isset( $tabs[ $current_tab ] ) || has_action( 'pie_forms_sections_' . $current_tab ) || has_action( 'pie_forms_settings_' . $current_tab );
$current_tab_label = isset( $tabs[ $current_tab ] ) ? $tabs[ $current_tab ] : '';


?>
<?php if(is_plugin_active('pie-forms-for-wp-mailchimp/pie-forms-for-wp-mailchimp.php') ){?>
<div class="wrap pie-forms">
	<form method="<?php echo esc_attr( apply_filters( 'pie_forms_settings_form_method_tab_' . $current_tab, 'post' ) ); ?>" id="mainform" action="" enctype="multipart/form-data">
		<nav class="nav-tab-wrapper pf-nav-tab-wrapper">
			<?php
			foreach ( $tabs as $slug => $label ) {
				echo '<a href="' . esc_html( admin_url( 'admin.php?page=pf-marketing&tab=' . esc_attr( $slug ) ) ) . '" class="nav-tab ' . ( $current_tab === $slug ? 'nav-tab-active' : '' ) .' tab_'.$slug.'"><span class="pf-nav-icon ' . esc_attr( $slug ) . '"></span>' . esc_html( $label ) . '</a>';
			}
			do_action( 'pie_forms_marketing_tabs' );
			?>
		</nav>
		<?php 
			?>
			<div class="pie-forms-marketing" id='<?php echo $current_tab; ?>'>
			<h1 class="marketing-page-heading"><?php echo esc_html( $current_tab_label ); ?></h1>
				<?php
					do_action( 'pie_forms_sections_' . $current_tab );

					PIE_Admin_Settings::show_messages();
					
					do_action( 'pie_forms_settings_' . $current_tab );
				?>
				<p class="submit">
						<button name="save" class="pie-forms-btn pie-forms-btn-primary pie-forms-save-button" type="submit" value="<?php esc_attr_e( 'Save Settings', 'pie-forms' ); ?>"><?php _e( 'Save Settings', 'pie-forms' ); ?></button>
					<?php  ?>
					<?php wp_nonce_field( 'pie-forms-settings' ); ?>
				</p>	
			</div>
		<?php 
			?>	
		<?php  ?>
		</form>
	</div>
	<?php }else{
		echo "<img src='".Pie_forms::$url."assets/images/mailchimp-addon.png' alt='mailchimp-addon' > ";
		$plugins = 'https://pieforms.com/plan-and-pricing/?utm_source=admindashboard&utm_medium=Mailchimpsection&utm_campaign=planpricing';
		echo sprintf(__('<div class="pf-activate-plugin-notice">Mailchimp Addon is part of Premium Plan. <a href="%s" target="_blank">Click here</a> to view pricing.</div>'), $plugins);
	} ?>
