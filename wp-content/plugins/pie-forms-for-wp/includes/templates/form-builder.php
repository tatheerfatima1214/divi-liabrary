<?php
  $form_id          = esc_attr($_GET[ 'form_id' ]);
  // Get tabs for the builder panel.
  $tabs = apply_filters( 'pie_forms_builder_tabs_array', array() );
  //extract($tabs);

  
  
  // Get preview link.
  $preview_link = add_query_arg(
    array(
      'form_id'     => absint( $form_id ),
      'pf_preview' => 'true',
    ),
    home_url()
  );

?>

<div id="pie-builder-main" class="pie-builder-main" data-form_id="<?php echo $form_id; ?>">
  <form id="pie-forms-builder-form" name="pie-forms-builder" method="post" data-id="<?php echo absint( $form_id ); ?>">
      <input type="hidden" name="id" value="<?php echo absint( $form_id ); ?>">
      <input type="hidden" name="form_enabled" value="1">
      <input type="hidden" value="" name="form_field_id" id="pie-forms-field-id">
      <header class='pie-header'>
        <div class="header-left">
          <div class='logo'><img src="<?php  echo Pie_Forms::$url . 'assets/images/logo-white.png'; ?>" alt="pie-forms"></div>

            <ul class="pie-center-nav">
              <li class="shortcode"><input  type="text" id="shortcode-form" name="shortcode" value="[pie_form id='<?php echo $form_id ?>']" readonly="readonly">
              <span id="shortcode" class="copy-icon"></span>
              <div class="copied-wrap"><?php _e('Copied!', 'pie-forms')?></div>
            </li>
              <li class="preview"><a href="<?php echo esc_url( $preview_link ); ?>" target="_blank"><?php esc_html_e( __('Preview', 'pie-forms') ); ?></a></li>
              <li class="save"><button name="save_form" class="pie-forms-btn pie-forms-save-button" type="button" value="<?php esc_attr_e( __('Save', 'pie-forms') ); ?>"><?php _e('Save', 'pie-forms')?></button></li>
            </ul>
        </div>
        <div class="right">
          <ul class="pie-right-nav">
            <li class="form-fields active" data-id="fields"><a href="javascript:;">Form Fields</a></li>
            <li class="settings" data-id="settings"><a href="javascript:;">Form Settings</a></li>
            <li class="close"><a href="<?php echo admin_url('admin.php?page=pie-forms')?>"></a></li>
          </ul>
        </div>
      </header>
      <main>
        <section class="pie-section-main">
              <?php $fields     = 'fields';
                    $settings = 'settings' //foreach ( $tabs as $fields => $tab ) : ?>

                  <!--FORM FIELDS ACCORDIAN START -->   
                <div class="pie-form-main-wrapper" id="<?php echo $fields;?>">
                    <div class="pie-form-left" >
                          <div class="pie-form-element" id="pie-<?php echo $fields; ?>">
                                <?php do_action( 'pie_forms_builder_content_' . $fields ); ?>
                          </div>
                          <div class="pie-preview-save">
                            <div class="preview-button">
                            <a href="<?php echo esc_url( $preview_link ); ?>" target="_blank"></a>  
                            </div>
                            <div class="save-button-pie">
                              <button name="save_form" class="save-button pie-forms-btn pie-forms-save-button icon-save-button" type="button" value="<?php esc_attr_e( __('Save', 'pie-forms') ); ?>"></button>
                            </div>
                          </div>
                    </div>
                    <div class="pie-form-right">

                      <div class="tab-<?php echo $fields; ?>" id="tab-<?php echo $fields; ?>">
                              <div class="pie-tab-setting">
                                  <ul>
                                    <li data-id="tab-add-fields" class="active">Add Fields</li>    
                                    <li data-id="tab-field-options">Field Options</li>    
                                  </ul>
                              </div>
                              <div class="pie-form-accordian-<?php echo $fields;?> ScrollBar" id="pie-form-accordian-<?php echo $fields;?>">
                                    <?php do_action( 'pie_forms_builder_sidebar_' . $fields ); ?>
                              </div>
                      </div>
                       <!-- FIELDS ACCORDIAN START -->


                      <!-- SETTING ACCORDIAN START -->
                      <div class="accordian-<?php echo $settings; ?>" id="tab-<?php echo $settings; ?>">
                            <div class="pie-form-accordian-<?php echo $settings;?>" id="pie-form-accordian-<?php echo $settings;?>">
                                    <?php do_action( 'pie_forms_builder_sidebar_' . $settings ); ?>
                              </div>

                              <div class="pie-form-element ScrollBar" id="pie-<?php echo $settings; ?>">
                                <?php do_action( 'pie_forms_builder_content_' . $settings ); ?>
                          </div>
                      </div>
                      <!-- SETTING ACCORDIAN END -->


                  </div>
                </div>  
                
            <?php //endforeach; ?>
        </section>
      </main>
    </form>  
</div>