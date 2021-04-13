
<?php
	defined( 'ABSPATH' ) || exit;

	$tabs = apply_filters( 'pie_forms_tools_tabs_array', array() );

?>
	<div class="wrap pie-forms">
		<nav class="nav-tab-wrapper pf-nav-tab-wrapper">
			<?php
			foreach ( $tabs as $current_tab => $label ) {
				echo '<a href="' . esc_html( admin_url( 'admin.php?page=pf-tools&tab=' . esc_attr( $current_tab ) ) ) . '" class="nav-tab ' . ( ( isset($_REQUEST['tab']) && $_REQUEST['tab'] === $current_tab || 
				( $current_tab === 'environment' && !isset($_REQUEST['tab']) ))  ? 'nav-tab-active' : '' ) .' tab_'.$current_tab.'"><span class="pf-nav-icon ' . esc_attr( $current_tab ) . '"></span>' . esc_html( $label ) . '</a>';
			}

			?>
		</nav>
		<?php 
		foreach ($tabs as $current_tab => $value): 
			if( ( isset($_REQUEST['tab']) && $_REQUEST['tab'] === $current_tab ) || 
			( $current_tab === 'environment' && !isset($_REQUEST['tab']) ) ):
			?>
			<div class="tools-wrapper">
			<?php
				switch ( $current_tab ):
					case 'plugins_and_themes':
						PIE_Admin_Tools::plugins_and_themes();
					break;
					case 'environment':
						PIE_Admin_Tools::environment();
					break;
					default:
						PIE_Admin_Tools::environment();
					break;
				endswitch; 
			?>
			</div>
			<?php
			endif;
		endforeach; ?>	
</div>
