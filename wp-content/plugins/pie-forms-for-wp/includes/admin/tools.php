<?php
defined( 'ABSPATH' ) || exit;

	class PIE_Admin_Tools {

		/**
		 * Display Environment details.
		 */
		public static function environment() {
			?>
			<?php
			/**
			 * Pie forms Version
			 */
			
			$pie_form_ver = get_plugins();
			$pie_form_ver = $pie_form_ver['pie-forms-for-wp/pie-forms-for-wp.php'];

			if(!empty($pie_form_ver)):
				?>
				<div class="environment-info">
					<label class="info-label"><?php echo esc_html("Pie Forms Version",'pie-forms') ?></label>
					<span class="info-status"><?php echo esc_html($pie_form_ver['Name'].' '. $pie_form_ver['Version'])?></span>
				</div>
				<?php
			endif;

			/**
			 * PhP Version
			 */
			
			?>
			<div class="environment-info">
			<label class="info-label"><?php echo esc_html("PHP Version",'pie-forms') ?></label>
				<span class="info-status"><?php echo  esc_html(phpversion()) ?></span>
			</div>
			
			<?php
			/**
			 *  MySQL Version
			 */
			?>

			<div class="environment-info">
			<label class="info-label"><?php echo esc_html("MySQL Version",'pie-forms') ?></label>
			<?php
				global $wpdb;
				$mysqli_version = $wpdb->db_version();
				?>
					<span class="info-status"><?php echo esc_html($mysqli_version) ?></span>
			</div>

			<?php
			/**
			 * Wordpress Version 
			 */
			?>
			
			<div class="environment-info">
            <label class="info-label"><?php echo esc_html("Wordpress Version",'pie-forms') ?></label>
				<span class="info-status"><?php echo esc_html( get_bloginfo('version')) ?></span>
				
			</div>
			
			<?php
			/**
			 * Curl
			 */
			?>

 			<div class="environment-info">
				<label class="info-label"><?php echo esc_html("Curl",'pie-forms') ?></label>
				<?php
				if(function_exists('curl_version')):
				?>
					<span class="info-status"><?php echo esc_html("Enable","pie-forms")?></span>
				<?php 
				else:
				?>
					<span class="info-status"><?php echo esc_html("Disable","pie-forms")?></span>
				<?php endif; ?>
			</div>
			
			<?php 
			/**
			 * File Get Contents
			 */
			?>

			 <div class="environment-info">
				<label class="info-label"><?php echo esc_html("File Get Contents",'pie-forms') ?></label>
				<?php 
				if(function_exists('file_get_contents')):
				?>
					<span class="info-status"><?php echo esc_html("Enable","pie-forms")?></span>
				<?php 
				else:
				?>
					<span class="info-status"><?php echo esc_html("Disable","pie-forms")?></span>
				<?php
				endif;
				?>
			</div>

			<?php 
			/**
			 * MB String
			 */
			?>

			<div class="environment-info">
				<label class="info-label"><?php _e("MB String",'pie-forms') ?></label>
				<?php if (extension_loaded('mbstring')):
				?>
					<span class="info-status"><?php echo esc_html("Enable","pie-forms") ?></span>
				<?php				
				else:
				?>
					<span class="info-status">'<?php echo esc_html("Disable","pie-forms") ?></span>
				<?php
				endif;
				?>
			</div>

			<?php 
			/**
			 * PHP Post Max Size
			 * PHP Time Limit
			 */

			?>
			<div class="environment-info">
				<label class="info-label"><?php echo esc_html("PHP Post Max Size",'pie-forms') ?></label>
				<span class="info-status"><?php echo esc_html(ini_get('post_max_size')) ?></span>
			</div>
			<div class="environment-info">
				<label class="info-label"><?php echo esc_html("PHP Time Limit",'pie-forms') ?></label>
				<span class="info-status"><?php echo esc_html(ini_get('max_execution_time')) ?></span>
			</div>				

			<?php 
			 
			/**
			 * WP Memory Limit
			 */
			
			?>
			<div class="environment-info">
                <label class="info-label"><?php echo esc_html("WP Memory Limit",'pie-forms') ?></label>
				<span class="info-status"><?php echo esc_html(WP_MEMORY_LIMIT) ?></span>
            </div>
					
			<?php
			/**
			 * WP Debug Mode
			 */
			?>

			<div class="environment-info">
				<label class="info-label"><?php echo esc_html("WP Debug Mode",'pie-forms') ?></label>
				<?php
				if ( defined('WP_DEBUG') && WP_DEBUG ):
				?>
					<span class="info-status"><?php echo esc_html( 'Yes', 'pie-forms' ) ?></span> 
				<?php
					else:
				?>
				<span class="info-status">'<?php echo esc_html( 'No', 'pie-forms' )?></span>
				<?php 
				endif;
				?>
			</div>
			
			<?php
			/**
			 * WP Language
			 */
			?>

			<div class="environment-info">
				<label class="info-label"><?php echo esc_html("WP Language",'pie-forms') ?></label>
				<span class="info-status"><?php echo esc_html(get_locale())?></span>
			</div>

			
			<?php
			/**
			 * WP Language
			 */
			?>

			<div class="environment-info">
				<label class="info-label"><?php echo esc_html('WP Max Upload Size','pie-forms') ?></label>
				<span class="info-status"><?php echo esc_html(size_format( wp_max_upload_size() ))?></span>
			</div>
		<?php	
		}

		/**
		 * Display Plugins and Themes details.
		 */
		public static function plugins_and_themes() {
		?>
			<textarea id="pie-forms-plugins-and-themes" name="pie-forms-plugins-and-themes" readonly="readonly">
			<?php

				/**
				 * For themes 
				 */
				$themes 		= wp_get_themes();
				
				#$current_theme = get_current_theme(); get_current_theme() is deprecated since version 3.4!
				$current_theme 	= wp_get_theme();

				echo "\r\n=== Themes ===\r\n\r\n";
				foreach($themes as $theme){
					if( $current_theme == $theme['Name'] )
						echo $theme['Name']." [ACTIVATED]\r\n";
					else
						echo $theme['Name']." [DEACTIVATED]\r\n";
				}
				
				$activate_plugins 	= get_option('active_plugins');
				$all_plugins 		= get_plugins();
				
				echo "\r\n\r\n=== Plugins (".count($activate_plugins)."/".count($all_plugins).") ===\r\n\r\n";
				foreach($all_plugins as $key=>$plugin){
					if( in_array($key,$activate_plugins) )
						echo $plugin['Name']." [ACTIVATED]\r\n";
					else
						echo $plugin['Name']." [DEACTIVATED]\r\n";
				}
			?>
			</textarea>	
		<?php
		}
	}