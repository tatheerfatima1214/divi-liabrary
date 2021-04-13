<?php $templates = Pie_Forms()->templateView()->page_output(); 
?>
<div class="dashboard-wrapper">
  <ul class="button-group filters-button-group">
    <li> <button class="button is-checked" data-filter="*"><?php _e( 'All', 'pie-forms' ); ?></button> </li>
    <li> <button class="button" data-filter=".free"><?php _e( 'Free', 'pie-forms' ); ?></button> </li>
    <li> <button class="button" data-filter=".premium"><?php _e( 'Premium', 'pie-forms' ); ?></button> </li>
  </ul>
  <div class="pf-content-area">
      <div class="pie-forms-logo">
        <img src="<?php  echo Pie_Forms::$url . 'assets/images/pie-forms.png'; ?>" alt="pie-forms">
      </div>
      <div class="element-grid">
      <?php
          if ( empty( $templates ) ) {
            echo '<div id="message" class="error"><p>' . esc_html__( 'Something went wrong. Please refresh your templates.', 'pie-forms' ) . '</p></div>';
          } else {
            foreach ( $templates as $template ) :
                $plan = $template->plan; 
                $upgrade_class = $plan === 'premium' ? 'upgrade-modal' : '';
            ?>
              <div class="element-item <?php echo $plan;?>" data-template="<?php echo $template->slug; ?>">
                <div class="element-image">
                  <img src="<?php echo Pie_Forms::$url . 'assets/images/templates/'.$template->image; ?>" alt="blank">
                  <div class="getting-start">
                    <a class = "<?php echo $upgrade_class ?>" href="javascript:;">Get Started</a>
                </div>
                </div>
                <div class="name">
                  <h3><?php echo $template->title; ?></h3>
                </div>
                <!-- <div class="description">
                  <p><?php echo $template->description; ?></p>
                </div> -->
              </div>
            <?php endforeach;
            
            }?>
        </div>
  </div>   
</div>