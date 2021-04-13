(function($) {
    var $PF_builder = $("#pie-forms-builder-form");
    $("#pie-form-element_fields").sortable({
        axis: 'y',
        cursor: 'move',
        opacity: 0.65,
        scrollSensitivity: 10,
        forcePlaceholderSize: true,
        //revert: true,
        cursor: 'move',
        placeholder: "ui-state-highlight",
        connectWith: '#pie-form-element_fields',
        start: function( event, ui ) {

             ui.item.css({
                'background-color': '#f7fafc',
                'border': '1px dashed #dd0000'
            }); 
            
        },
        //containment: '.pie-forms-field-wrap',
        out: function( event ) {
           
        },
        receive: function( event, ui ) {
            
            var field = $(this).data().uiSortable.currentItem;
            fieldDrop( field );
            commonFunction();

        },
        stop: function( event, ui ) {
            ui.item.removeAttr( 'style' );
        }

      }).disableSelection();;

    $("#pie-form-draggable > li").draggable({
        delay: 200,
        cancel: false,
        scroll: false,
        revert: 'invalid',
        scrollSensitivity: 10,
        forcePlaceholderSize: true,
        
        helper: function () { 
            // var cloned = $(this).clone();
            // //cloned.attr('data-field-type', (++$no).toString());
            // return cloned;
           commonFunction();
           return $( this ).clone().insertAfter( $( this ).closest( '.pie-form-accordian-fields' ).siblings( '.pie-tab-setting' ) );
        },

        opacity: 0.75,
        connectToSortable: "#pie-form-element_fields",
        //revert: "invalid"
    }).disableSelection();
    
    // Document ready
    $( document ).ready(function (){
        // jquery-confirm defaults.
        if($('.pie-builder-main').length > 0){
            jconfirm.defaults = {
                closeIcon: true,
                backgroundDismiss: true,
                escapeKey: true,
                animationBounce: 1,
                useBootstrap: false,
                theme: 'modern',
                boxWidth: '400px',
                columnClass: 'pf-responsive-class'
            };
        }
    } );

    

    //FIELD DELETE FUNCTION
    $( 'body' ).on('click', '.delete', function () {    
        var $this = $(this);
        var field = $($this).parents('.pie-forms-field').attr("data-field-id");
       
        $.confirm({
            title: false,
            content: pf_data.i18n_delete_field_confirm,
            type: 'red',
            closeIcon: false,
            backgroundDismiss: false,
            icon: 'dashicons dashicons-warning',
            buttons: {
                confirm: {
                    text: pf_data.i18n_ok,
                    btnClass: 'btn-confirm',
                    keys: ['enter'],
                    action: function () {
                        $($this).closest('.pie-forms-field').remove();
                        $('#pie-forms-field-option-'+field).remove();
                        $('.pie-tab-setting > ul > li:first-child').trigger( 'click' );
                       
                        
                    }
                },
                cancel: {
                    text: pf_data.i18n_cancel
                }
            }
        } );

        
    });
    
    // HIDE ALL LABEL
    $PF_builder.on( 'change', '#pie-forms-panel-field-settings-hide_all_label', function( event ) {
        $('.pie-forms-field .label-title').toggleClass('hide-label');
    });
    
    // LABEL TO PLACEHOLDER
    function label_to_placeholder(){
        $('.pie-forms-field').each(function(){
            var required                            = $(this).hasClass("required");
            var field_id                            = $(this).attr("data-field-id");
            var placeholder                         = $('#pie-forms-field-option-'+field_id+'-placeholder').val();
            var placeholder_if_required             = required && placeholder !== '' ? placeholder+"*": placeholder+' ';
            var label                               = placeholder_if_required !== "" ? placeholder_if_required : " ";
            if(placeholder == '' && $("#pie-forms-panel-field-settings-label_to_placeholder").is(":checked")){
                label = required ? $(this).find('label').text() : $(this).find('label .text').text();
            }
            $(this).find('input , textarea ').attr('placeholder',label);
            if($(this).attr('data-field-type') === 'select'){
                $(this).find('select').prepend( '<option class="placeholder" selected>' + label + '</option>' );
            }
        });
    }

    $PF_builder.on( 'change', "#pie-forms-panel-field-settings-label_to_placeholder", function( event ) {    
        label_to_placeholder();
    });
    label_to_placeholder();
    
    $PF_builder.on( 'input', '.pie-form-field-wrapper-label input', function() {
        var $this   = $(this);
        var value   = '';
        var id      = $this.parent().data( 'field-id' );
        if($('#pie-forms-panel-field-settings-label_to_placeholder').is(':checked')){
            value  = $this.val();
        }
        $( '#pie-forms-field-' + id ).find( 'input , textarea ' ).attr('placeholder',value);
        $( '#pie-forms-field-' + id ).find( 'select ' ).prepend( '<option class="placeholder" selected>' + value + '</option>' );
    });
    
    //FIELD EDIT FUNCTION
    $( document ).on( 'click', '.edit', function(e) {
        $('.pie-tab-setting > ul > li:last-child').click();
        
        //Hide All Options
        $('.pie-forms-field-option').hide();
        $('.pie-forms-field').removeClass('active');
        
        var field_id = $(this).parents('.pie-forms-field').attr("data-field-id");

        $("#pie-forms-field-option-"+field_id).show();

        $("#pie-forms-field-"+field_id).addClass('active');

        commonFunction();
        
    });

    function commonFunction(){
        //Scrollbar update
        if($('.ScrollBar').length > 0){
            $('.ScrollBar').perfectScrollbar('update');
        }
        if($('.field-main-wrapper').find('.pie-forms-field').length > 0){
            $('.field-main-wrapper').addClass('has-fields');
        }else{
            $('.field-main-wrapper').removeClass('has-fields');
        }
        
    }
    commonFunction();
    //TABS SETTING CHANGER
    $('.pie-tab-setting > ul > li').on('click',function(){
        var $this_id = $(this).attr("data-id");
        $('#pie-form-accordian-fields > div').hide();
        $('#pie-form-accordian-fields').children('div#'+$this_id).show();

        $(this).siblings('li').removeClass('active');
        $(this).addClass('active');

        
        $('.pie-forms-field-option-wrapper').find('.pie-forms-field-option').hide();
        $('.pie-forms-field-option-wrapper').find('.pie-forms-field-option').eq(0).show();

        $('.field-main-wrapper').find('.pie-forms-field').removeClass('active');
        $('.field-main-wrapper').find('.pie-forms-field').eq(0).addClass('active');
        
        commonFunction();


    });
    

        $( 'body' ).on( 'click', '.pie-forms-save-button', function () {
            var $this      = $( this );
            var $form      = $( 'form#pie-forms-builder-form' );
            var structure  = getStructure();
            var form_data  = $form.serializeArray();
            
            var form_title = $( '#pf-edit-form-name' ).val().trim();

				if ( '' === form_title ) {
					$.alert({
						title: pf_data.i18n_field_title_empty,
						content: pf_data.i18n_field_title_payload,
						icon: 'dashicons dashicons-warning',
						type: 'red',
						buttons: {
							ok: {
								text: pf_data.i18n_ok,
								btnClass: 'btn-confirm',
								keys: [ 'enter' ]
							}
						}
					});
					return;
				}

            /* DB unwanted data erase start */
            var rfields_ids = [];
            $( '.pie-forms-field[data-field-id]' ).each( function() {
                rfields_ids.push( $( this ).attr( 'data-field-id' ) );
            });

            var form_data_length = form_data.length;
            while ( form_data_length-- ) {
                if ( form_data[ form_data_length ].name.startsWith( 'form_fields' ) ) {
                    var idflag = false;
                    rfields_ids.forEach( function( element ) {
                        if ( form_data[ form_data_length ].name.startsWith( 'form_fields[' + element + ']' ) ) {
                            idflag = true;
                        }
                    });
                    if ( form_data_length > -1 && idflag === false )  {
                        form_data.splice( form_data_length, 1 );
                    }
                }
            }
            /* DB fix end */

            var new_form_data = form_data.concat( structure );
            var data = {
                action: 'pie_forms_save_form',
                security: pf_data.pf_save_form,
                form_data: JSON.stringify( new_form_data )
            };

            $.ajax({
                url: pf_data.ajax_url,
                data: data,
                type: 'POST',
                beforeSend: function () {
                    $( '.pie-form-left' ).addClass('processing').append( '<i class="spinner is-active"></i>' );
                },
                success: function ( response ) {
                    $this.removeClass( 'processing' );
                    $this.find( '.loading-dot' ).remove();

                    if ( ! response.success ) {
                        $.alert({
                            title: response.data.errorTitle,
                            content: response.data.errorMessage,
                            icon: 'dashicons dashicons-warning',
                            type: 'red',
                            buttons: {
                                ok: {
                                    text: pf_data.i18n_ok,
                                    btnClass: 'btn-confirm',
                                    keys: [ 'enter' ]
                                }
                            }
                        });
                    }

                    $( '.pie-form-left' ).removeClass('processing');

                    $( '.pie-form-left' ).find('.spinner.is-active').remove();
                }
            });
        });
             
    function fieldDrop( field ) {
            var field_type = field.attr( 'data-field-type' );

            field.css({
                'left': '0',
                'width': '100%'
            }).append( '<i class="spinner is-active"></i>' );

            $.ajax({
                url: pf_data.ajax_url,
                type: 'POST',
                data: {
                    action: 'pie_forms_new_field_' + field_type,
                    field_type: field_type,
                    form_id: pf_data.form_id
                },
                beforeSend: function() {
                    $( document.body ).trigger( 'init_field_options_toggle' );
                },
                success: function( response ) {
                    var field_preview = response.data.preview,
                        field_options = response.data.options,
                        form_field_id = response.data.form_field_id,
                        field_type = response.data.field.type,
                        dragged_el_id = $( field_preview ).attr( 'id' ),
                        dragged_field_id = $( field_preview ).attr( 'data-field-id' );

                    $( '#pie-forms-field-id' ).val( form_field_id );
                    $( '.pie-forms-field-option-wrapper' ).find( '.no-fields' ).hide();
                    $( '.pie-forms-field-option-wrapper' ).append( field_options );
                    choicesInit();

                    field.after( field_preview );


                    field.remove();

                    commonFunction();

                    }
                });
            }
            

            function getStructure() {
                var wrapper   = $( '#pie-form-element_fields' );
                var structure = [];
    
                var array_index = 0;
                $.each( wrapper.find( '.pie-forms-field' ), function() {
                    var structure_object = { name: '', value: '' };
                    var field_id = $( this ).attr( 'data-field-id' );
                    structure_object.name = 'structure[list_' + array_index + ']';
                    array_index++;
                    structure_object.value = field_id;
                    structure.push( structure_object );
                });
                if ( wrapper.find( '.pie-forms-field' ).length < 1 ) {
                    structure.push({ name: 'structure[list_' + array_index + ']', value: '' });
                }
                return structure;
            }


            $( 'body' ).on( 'click', '.pie-forms-field-duplicate', function() {
                var $field = $( this ).closest( '.pie-forms-field' );

                    $.confirm({
                        title: false,
                        content: pf_data.i18n_duplicate_field_confirm,
                        type: 'orange',
                        closeIcon: false,
                        backgroundDismiss: false,
                        icon: 'dashicons dashicons-warning',
                        buttons: {
                            confirm: {
                                text: pf_data.i18n_ok,
                                btnClass: 'btn-confirm',
                                keys: ['enter'],
                                action: function () {
                                    cloneFieldAction( $field );
                                }
                            },
                            cancel: {
                                text: pf_data.i18n_cancel
                            }
                        }
                    } );
            } );

            $( document ).on( 'click', '.pie-forms-field-option-group .pie-forms-field-option-group-toggle', function(e) {
                    $('.pie-forms-field-option-group-toggle').removeClass('active');
                    $(this).addClass('active');
                    $('.pie-forms-field-option-group-inner').slideUp();
                    $(this).next('.pie-forms-field-option-group-inner').slideDown();

            });

            // Real time checkbox required.
            $PF_builder.on( 'change', '.pie-form-field-wrapper-required input', function( event ) {
                var id = $( this ).parent().data( 'field-id' );

                $( '#pie-forms-field-' + id ).toggleClass( 'required' );

                // Toggle "Required Field Message" option.
                if ( $( event.target ).is( ':checked' ) ) {
                    $( '#pie-form-field-wrapper-' + id + '-required-field-message' ).show();
                } else {
                    $( '#pie-form-field-wrapper-' + id + '-required-field-message' ).hide();
                }
            });

            // Real time checkbox hide label.
            $PF_builder.on( 'change', '.pie-form-field-wrapper-label_hide input', function() {
                var id = $(this).parent().data( 'field-id' );
                $( '#pie-forms-field-' + id ).toggleClass( 'label_hide' );
            });

            // Real-time updates for "Placeholder" field option.
            $PF_builder.on( 'input', '.pie-form-field-wrapper-placeholder input', function(e) {
                var $this    = $( this ),
                    value    = $this.val(),
                    id       = $this.parent().data( 'field-id' ),
                    $primary = $( '#pie-forms-field-' + id ).find( '.widefat:not(.secondary-input)' );

                if ( $primary.is( 'select' ) ) {
                    if ( ! value.length ) {
                        $primary.find( '.placeholder' ).remove();
                    } else {
                        if ( $primary.find( '.placeholder' ).length ) {
                            $primary.find( '.placeholder' ).text( value );
                        } else {
                            $primary.prepend( '<option class="placeholder" selected>' + value + '</option>' );
                        }
                    }
                } else {
                    $primary.attr( 'placeholder', value );
                }
            });

            // Real-time updates for "Show Label" field option.
            $PF_builder.on( 'input', '.pie-form-field-wrapper-label input', function() {
                var $this  = $(this),
                    value  = $this.val(),
                    id     = $this.parent().data( 'field-id' );
                    $label = $( '#pie-forms-field-' + id ).find( '.label-title .text' );

                if ( $label.hasClass( 'nl2br' ) ) {
                    $label.html( value.replace( /\n/g, '<br>') );
                } else {
                    $label.html( value );
                }
            });

            // Enable Limit length.
            $PF_builder.on( 'change', '.pie-form-field-wrapper-limit_enabled input', function( event ) {
                updateTextFieldsLimitControls( $( event.target ).parents( '.pie-form-field-wrapper-limit_enabled' ).data().fieldId, event.target.checked );
            } );


            //FIELD GROUP CHANGER
            $PF_builder.on( 'click', '.pie-form-accordian-fields .fields-elements > ul > li > span', function( event ) {

                $(this).parent('li').toggleClass('active');
                $(this).next('.pie-form-fields').slideToggle('medium', function() {
                        if ($(this).is(':visible'))
                            $(this).css('display','flex');
                            commonFunction();
                    });
            });


        function updateTextFieldsLimitControls( fieldId, checked ) {
            if ( ! checked ) {
                $( '#pie-form-field-wrapper-' + fieldId + '-limit_controls' ).addClass( 'pie-forms-hidden' );
            } else {
                $( '#pie-form-field-wrapper-' + fieldId + '-limit_controls' ).removeClass( 'pie-forms-hidden' );
            }
        }

        function cloneFieldAction ( field ) {
            var element_field_id = field.attr('data-field-id');
            var form_id = pf_data.form_id;
            var data = {
                action: 'pie_forms_get_next_id',
                security: pf_data.pf_get_next_id,
                form_id: form_id
            };
            $.ajax({
                url: pf_data.ajax_url,
                data: data,
                type: 'POST',
                beforeSend: function() {
                    $( document.body ).trigger( 'init_field_options_toggle' );
                },
                success: function ( response ) {
                    if ( typeof response.success === 'boolean' && response.success === true ) {
                        var field_id = response.data.field_id;
                        var field_key = response.data.field_key;
                        $('#pie-forms-field-id').val(field_id);
                        render_node(field, element_field_id, field_key);
                        field.next().removeClass('active');
                    }
                }
            });
        }

        function render_node( field, old_key, new_key ) {
            var option = $('.pie-forms-field-option-wrapper #pie-forms-field-option-' + old_key );
            var old_field_label = $('#pie-forms-field-option-' + old_key + '-label' ).val();
            var old_field_meta_key = $( '#pie-forms-field-option-' + old_key + '-meta-key' ).length ? $( '#pie-forms-field-option-' + old_key + '-meta-key' ).val() : '';
            var field_type = field.attr('data-field-type'),
            newOptionHtml = option.html(),
            new_field_label = old_field_label + ' ' + pf_data.i18n_copy,
            new_meta_key =  'html' !== field_type ? old_field_meta_key.replace( /\(|\)/g, '' ).toLowerCase().substring( 0, old_field_meta_key.lastIndexOf( '_' ) ) + '_' + Math.floor( 1000 + Math.random() * 9000 ) : '',
            newFieldCloned = field.clone();
            var regex = new RegExp(old_key, 'g');
            newOptionHtml = newOptionHtml.replace(regex, new_key);
            var newOption = $('<div class="pie-forms-field-option pie-forms-field-option-' + field_type + '" id="pie-forms-field-option-' + new_key + '" data-field-id="' + new_key + '" />');
            newOption.append(newOptionHtml);
            $.each(option.find(':input'), function () {
                var type = $(this).attr('type');
                var name = $( this ).attr( 'name' ) ? $( this ).attr( 'name' ) : '';
                var new_name = name.replace(regex, new_key);
                var value = '';
                if ( type === 'text' || type === 'hidden' ) {
                    value = $(this).val();
                    newOption.find('input[name="' + new_name + '"]').val(value);
                    newOption.find('input[value="' + old_key + '"]').val(new_key);
                } else if ( type === 'checkbox' || type === 'radio' ) {
                    if ( $(this).is(':checked') ) {
                        newOption.find('input[name="' + new_name + '"]').prop('checked', true).attr('checked', 'checked');
                    } else {
                        newOption.find('[name="' + new_name + '"]').prop('checked', false).attr('checked', false);
                    }
                } else if ( $(this).is('select') ) {
                    if ( $(this).find('option:selected').length ) {
                        var option_value = $(this).find('option:selected').val();
                        newOption.find('[name="' + new_name + '"]').find('[value="' + option_value + '"]').prop('selected', true);
                    }
                } else {
                    if ( $(this).val() !== '' ) {
                        newOption.find('[name="' + new_name + '"]').val($(this).val());
                    }
                }
            });

            $('.pie-forms-field-option-wrapper').append(newOption);
            $('#pie-forms-field-option-' + new_key + '-label').val(new_field_label);
            $('#pie-forms-field-option-' + new_key + '-meta-key').val(new_meta_key);

            // Field Clone
            newFieldCloned.attr('class', field.attr('class'));
            newFieldCloned.attr('id', 'pie-forms-field-' + new_key);
            newFieldCloned.attr('data-field-id', new_key);
            newFieldCloned.attr('data-field-type', field_type);
            newFieldCloned.find('.label-title .text').text(new_field_label);
            field.closest( '.pie-form-element' ).find( '[data-field-id="' + old_key + '"]' ).after( newFieldCloned );
            $(document).trigger('pie-form-cloned', [ new_key, field_type ] );

            // Trigger an event indicating completion of render_node action for cloning.
            $( document.body ).trigger( 'pf_render_node_complete', [ field_type, new_key, newFieldCloned, newOption ] );
        }

        function choiceUpdate( type, id ) {
            var $fieldOptions = $( '#pie-forms-field-option-' + id );
            // Radio and Checkbox use _ template.
            if ( 'radio' === type ) {
                
                var new_choice = '<li><input type="radio" disabled> {input}</li>';
                $( '#pie-forms-field-' + id + ' .primary-input li' ).not( '.placeholder' ).remove();

                }
                

            var new_choice;

            if ( 'select' === type) {
                $( '#pie-forms-field-' + id + ' .primary-input option' ).not( '.placeholder' ).remove();
                new_choice = '<option>{label}</option>';
            }

            if('radio' === type){
                $( '#pie-forms-field-' + id + ' .primary-input li' ).not( '.placeholder' ).remove();
                var new_choice = '<li><input type="radio" disabled> {input}</li>';
            }

            if('checkbox' === type){
                $( '#pie-forms-field-' + id + ' .primary-input li' ).not( '.placeholder' ).remove();
                var new_choice = '<li><input type="checkbox" disabled> {input}</li>';
            }


            $( '#pie-form-field-wrapper-' + id + '-choices .pf-choices-list li' ).each( function( index ) {
                var $this    = $( this ),
                    tag      = ('select' === type) ? '{label}' : '{input}',
                    label    = $this.find( 'input.label' ).val(),
                    selected = $this.find( 'input.default' ).is( ':checked' ),
                    choice   = $( new_choice.replace( tag, label ) );
                    //$('#pie-forms-field-'+id+'').find("li").text(label);

                $( '#pie-forms-field-' + id + ' .primary-input' ).append( choice );
                if ( true === selected ) {
                    switch ( type ) {
                        case 'select':
                            choice.prop( 'selected', true );
                            break;
                        case 'radio':
                            choice.find( 'input' ).prop( 'checked', true );
                            break;
                        case 'checkbox':
                            choice.find( 'input' ).prop( 'checked', true );
                            break;
                    }
                }
            } );
        }

        /**
         * Add new field choice.
         *
         */
        function choiceAdd( event, el ) {
            event.preventDefault();

            var $this   = $( el ),
                $parent = $this.parent(),
                checked = $parent.find( 'input.default' ).is( ':checked' ),
                fieldID = $this.closest( '.pie-form-field-wrapper-choices' ).data( 'field-id' ),
                nextID  = $parent.parent().attr( 'data-next-id' ),
                type    = $parent.parent().data( 'field-type' ),
                $choice = $parent.clone().insertAfter( $parent );

            $choice.attr( 'data-key', nextID );
            $choice.find( 'input.label' ).val( '' ).attr( 'name', 'form_fields[' + fieldID + '][choices][' + nextID + '][label]' );
            $choice.find( 'input.value' ).val( '' ).attr( 'name', 'form_fields[' + fieldID + '][choices][' + nextID + '][value]' );
            $choice.find( 'input.source' ).val( '' ).attr( 'name', 'form_fields[' + fieldID + '][choices][' + nextID + '][image]' );
            $choice.find( 'input.default').attr( 'name', 'form_fields[' + fieldID + '][choices][' + nextID + '][default]' ).prop( 'checked', false );
            $choice.find( '.attachment-thumb' ).remove();
            $choice.find( '.button-add-media' ).show();

            if ( checked === true ) {
                $parent.find( 'input.default' ).prop( 'checked', true );
            }

            nextID++;
            $parent.parent().attr( 'data-next-id', nextID );
            
            choiceUpdate( type, fieldID );
        }

        /**
         * Delete field choice.
         *
         */
        function DeleteFields( event, el ) {
            event.preventDefault();

            var $this = $( el ),
                $list = $this.parent().parent(),
                total = $list.find( 'li' ).length;

            if ( total < 2 ) {
                $.alert({
                    title: false,
                    content: pf_data.i18n_field_error_choice,
                    icon: 'dashicons dashicons-info',
                    type: 'blue',
                    buttons: {
                        ok: {
                            text: pf_data.i18n_ok,
                            btnClass: 'btn-confirm',
                            keys: [ 'enter' ]
                        }
                    }
                });
            } else {
                $this.parent().remove();
                choiceUpdate( $list.data( 'field-type' ), $list.data( 'field-id' ) );
            }
        }

        // Field choices update preview area.
        $PF_builder.on( 'change', '.pie-form-field-wrapper-choices input[type=checkbox]', function(e) {
            var list = $(this).parent().parent();
            choiceUpdate( list.data( 'field-type' ), list.data( 'field-id' ) );
        });

        // Add new field choice.
        $PF_builder.on( 'click', '.pie-form-field-wrapper-choices .add', function( event ) {
            choiceAdd( event, $(this) );
        });

        // Delete field choice.
        $PF_builder.on( 'click', '.pie-form-field-wrapper-choices .remove', function( event ) {
            DeleteFields( event, $(this) );
        });

        $PF_builder.on( 'keyup paste focusout', '.pie-form-field-wrapper-choices input.label', function(e) {
                var list = $(this).parent().parent().parent();
                choiceUpdate( list.data( 'field-type' ), list.data( 'field-id' ) );
            });



        // Field choices defaults.
        $PF_builder.on( 'click', '.pie-form-field-wrapper-choices input[type=radio]', function() {
            var $this = $(this),
                list  = $this.parent().parent();

            $this.parent().parent().find( 'input[type=radio]' ).not( this ).prop( 'checked', false );

            if ( $this.attr( 'data-checked' ) === '1' ) {
                $this.prop( 'checked', false );
                $this.attr( 'data-checked', '0' );
            }

            choiceUpdate( list.data( 'field-type' ), list.data( 'field-id' ) );
        });

/*        // Field choices defaults - (before change).
        $PF_builder.on( 'mousedown', '.pie-form-field-wrapper-choices input[type=radio]', function()  {
            var $this = $(this);

            if ( $this.is( ':checked' ) ) {
                $this.attr( 'data-checked', '1' );
            } else {
                $this.attr( 'data-checked', '0' );
            }
        });
*/
        // CHOICES SORTABLE
        function choicesInit( selector ) {
            selector = selector || '.pie-form-field-wrapper-choices ul';
            $( selector ).sortable({
                items: 'li',
                axis: 'y',
                handle: '.sort',
                scrollSensitivity: 40,
                stop: function ( event ) {
                    var field_id = $( event.target ).attr( 'data-field-id' ),
                        type     = $( '#pie-forms-field-option-' + field_id ).find( '.pie-forms-field-option-hidden-type' ).val();
                    choiceUpdate( type, field_id );
                }
            } );
        }
        choicesInit();

        // Nav Setting  Toggle
        $('.pie-builder-main .pie-right-nav li:not(.close)').click(function(){
                
                var $this_atts = $(this).attr('data-id');
                $('.pie-form-right > div').hide() 
                $('.pie-form-right #tab-'+$this_atts).show()
                //$('.pie-form-right > div').hide();

                $(this).addClass('active');
                $(this).siblings('li').removeClass('active');
                
                commonFunction();
        });

        // GENERAL / EMIAL SETTING
        $('.setting-tab .tab-accordian .pf-panel-tab').click(function(){

            $(this).next('.pf-content-section').slideToggle('slow',function(){
                $('.ScrollBar').perfectScrollbar();
            });

            if($(this).next().is(':visible')){
                $(this).toggleClass('active');
            }
        });

        // Toggle form status.
        $( document ).on( 'change', '.pie-forms-toggle-form input', function(e) {
            e.stopPropagation();
            $.post( pf_data.ajax_url, {
                action: 'pie_forms_enabled_form',
                security: pf_data.pf_enabled_form,
                form_id: $( this ).data( 'form_id' ),
                enabled: $( this ).prop( 'checked' ) ? 1 : 0
            });
        });

        //SETTING - REDIRECT AFTER FROM SUBMISSION
        $( '#pie-forms-panel-field-settings-redirect_to' ).on( 'change', function () {
            if ( this.value == 'same' ) {
                $('#pie-forms-panel-field-settings-custom_page-wrap').hide();
                $('#pie-forms-panel-field-settings-external_url-wrap').hide();
            }
            else if ( this.value == 'custom_page') {
                $('#pie-forms-panel-field-settings-custom_page-wrap').show();
                $('#pie-forms-panel-field-settings-external_url-wrap').hide();
            }
            else if ( this.value == 'external_url') {
                $('#pie-forms-panel-field-settings-custom_page-wrap').hide();
                $('#pie-forms-panel-field-settings-external_url-wrap').show();
            }
        });

        //FIELDS - ADVANCE VALIDATION DROPDOWN

        /* var mySelect = jQuery('.pie-form-field-wrapper-validation_rule').val();
        $(mySelect).parent().attr('data-field-id'); */

        $('.pie-form-field-wrapper-validation_rule').each(function(){
            var data_field_id = $(this).attr('data-field-id');
            var selected_option = $('#pie-forms-field-option-'+data_field_id+'-validation_rule option:selected').val();

            if(selected_option === "custom_regex"){
                $("#pie-form-field-wrapper-"+data_field_id+"-custom_regex").show();
            }
            if(selected_option != 'please_select' ){
                $("#pie-form-field-wrapper-"+data_field_id+"-custom_validation_message ").show();
            }
        });

        $PF_builder.on( 'change', '.pie-form-field-wrapper-validation_rule select', function() {
            var field_id = $(this).parent().attr('data-field-id');
           if( this.value == 'custom_regex' ){
               $('#pie-form-field-wrapper-'+field_id+'-custom_regex').show();
           }else{
                $('#pie-form-field-wrapper-'+field_id+'-custom_regex').hide();
           }
           if(this.value != 'please_select'){
            $('#pie-form-field-wrapper-'+field_id+'-custom_validation_message').show();
           }else{
            $('#pie-form-field-wrapper-'+field_id+'-custom_validation_message').show();
           }
        });

        //Back to Form 
        $('#go-back-form').click(function(){

            $('.pie-form-main-wrapper#settings').hide();
            $('.pie-form-main-wrapper#fields').css("display","flex");
            $('.pie-tab-setting').show();
            commonFunction();

        });
        
        //SCROLLBAR
        if($('.ScrollBar').length > 0){
            $('.ScrollBar').perfectScrollbar();
        }


        //COPY TO CLIPBOARD
        function myFunction() {
            var copyText = document.getElementById("shortcode-form");
            copyText.select();
            copyText.setSelectionRange(0, 99999)
            document.execCommand("copy");
            $('.copied-wrap').fadeIn();
            setTimeout(function(){
                $('.copied-wrap').fadeOut();
            },2000);
            //alert("Copied the text: " + copyText.value);
        }

        $('#shortcode').click(function(){
            myFunction();
        });



    // Delegates event to toggleEditTitle() on clicking.
    $( '#edit-form-name' ).on( 'click', function( e ) {
        e.stopPropagation();

        if ( '' !== $( '#pf-edit-form-name' ).val().trim() ) {
            editFormName(e);
        }
    });

    // Apply the title change to form name field.
    $( '#pf-edit-form-name' )
        .on( 'change keypress', function( e ) {
            var $this = $( this );

            e.stopPropagation();

            if ( 13 === e.which && '' !== $( this ).val().trim() ) {
                editFormName(e);
            }

            if ( '' !== $this.val().trim() ) {
                $( '#pie-forms-panel-field-settings-form_title' ).val( $this.val().trim() );
            }
        })
        .on( 'click', function( e ) {
            e.stopPropagation();
        });

    // In case the user goes out of focus from title edit state.
    $( document ).not( $( '.pf-forms-title-desc' ) ).click( function( e ) {
        var field = $( '#pf-edit-form-name' );
        e.stopPropagation();

        // Only allow flipping state if currently editing.
        if ( ! field.prop( 'disabled' ) && field.val() && '' !== field.val().trim() ) {
            editFormName(e);
        }
    });

    function editFormName( event ) {
        var $el          = $( '#edit-form-name' ),
            $input_title = $el.siblings( '#pf-edit-form-name' );

        event.preventDefault();

        // Toggle disabled property.
        $input_title.prop ( 'disabled' , function( _ , val ) {
            return ! val;
        });

        if ( ! $input_title.hasClass( 'pie-forms-name-editing' ) ) {
            $input_title.focus();
        }

        $input_title.toggleClass( 'pie-forms-name-editing' );
    }

    //One Column Two Column
    $('#pie-forms-panel-field-settings-layout_class').on('change', function(){
        var column_val = $(this).val();
        if(column_val === "two-column"){
            $('#pie-form-element_fields').addClass('two-column');
            $('#pie-form-element_fields').removeClass('one-column');
        }else{
            $('#pie-form-element_fields').removeClass('two-column');
            $('#pie-form-element_fields').addClass('one-column');

        }
    });

     // Toggle Smart Tags.
     $( document.body ).on('click', '.pf-toggle-smart-tag-display', function(e) {
        e.stopPropagation();
        $('.pf-smart-tag-lists').hide();
        $('.pf-smart-tag-lists ul').empty();
        $( this ).parent().find('.pf-smart-tag-lists').toggle('show');
        $('.pf-content-email-settings').on('click',function(){
            $('.pf-smart-tag-lists').hide();
        })
        var type = $( this ).data('type');

        var allowed_field = $ ( this ).data( 'fields' );
        
        get_all_available_field( allowed_field, type , $( this ) );
    });

    $( document.body ).on('click', '.smart-tag-field', function(e) {

        var field_id    = $( this ).data('field_id'),
            field_label = $( this ).text(),
            type        = $( this ).data('type'),
            $parent     = $ ( this ).parent().parent().parent(),
            $input      = $parent.find('input[type=text]'),
            $textarea   = $parent.find('textarea');
        if ( field_id !== 'fullname' && field_id !== 'email' && field_id !== 'subject' && field_id !== 'message' && 'other' !== type ) {
            field_label = field_label.split(/[\s-_]/);
            for(var i = 0 ; i < field_label.length ; i++){
                if ( i === 0 ) {
                    field_label[i] = field_label[i].charAt(0).toLowerCase() + field_label[i].substr(1);
                } else {
                    field_label[i] = field_label[i].charAt(0).toUpperCase() + field_label[i].substr(1);
                }
            }
            field_label = field_label.join('');
            field_id = field_label+'_'+field_id;
        } else {
            field_id = field_id;
        }
        if ( 'field' === type ) {
            $input.val( $input.val() + '{field_id="'+field_id+'"}' );
            $textarea.val($textarea.val()+'{field_id="'+field_id+'"}' );
        } else if ( 'other' === type ) {
            $input.val( $input.val() + '{'+field_id+'}' );
            $textarea.val($textarea.val() + '{'+field_id+'}' );
        }
    });

    // Enable / Disable - Toggle Form
    $( document ).on( 'change', '.pie-forms_page_pf-builder .pie-forms-toggle-form input', function(e) {
        e.stopPropagation();
        $.post( pf_data.ajax_url, {
            action: 'pie_forms_enabled_form',
            security: pf_data.pf_enabled_form,
            form_id: $( this ).data( 'form_id' ),
            enabled: $( this ).attr( 'checked' ) ? 1 : 0
        });
    });

    function get_all_available_field( allowed_field, type , el ) {
        var all_fields_without_email = [];
        var all_fields = [];
        var email_field = [];
            $('.pie-forms-field').each( function(){
            var field_type = $( this ).data('field-type');
            var field_id = $( this ).data('field-id');
                if ( allowed_field === field_type ){
                    var e_field_label = $( this ).find('.label-title span').first().text();
                    var e_field_id = field_id;
                    email_field[ e_field_id ] = e_field_label;
                } else {
                    var field_label = $( this ).find('.label-title span').first().text();
                    all_fields_without_email[ field_id ] = field_label;
                }
            all_fields[ field_id ] = $( this ).find('.label-title span').first().text();
        });
        
        if( 'other' === type || 'all' === type ){
            var other_smart_tags = pf_data.smart_tags_other;
            for( var key in other_smart_tags ) {
                $(el).parent().find('.pf-smart-tag-lists .pf-others').append('<li class = "smart-tag-field" data-type="other" data-field_id="'+key+'">'+other_smart_tags[key]+'</li>');
            }
        }

        if ( 'fields' === type || 'all' === type ) {
            if ( allowed_field === 'email' ) {
                if ( Object.keys(email_field).length < 1 ){
                    $(el).parent().find('.pf-smart-tag-lists .smart-tag-title:not(".other-tag-title")').addClass('pie-forms-hidden');
                } else {
                    $(el).parent().find('.pf-smart-tag-lists .smart-tag-title:not(".other-tag-title")').removeClass('pie-forms-hidden');
                }
                $(el).parent().find('.pf-smart-tag-lists .other-tag-title').remove();
                $(el).parent().find('.pf-smart-tag-lists .pf-others').remove();
                $(el).parent().find('.pf-smart-tag-lists').append('<div class="smart-tag-title other-tag-title">Others</div><ul class="pf-others"></ul>');
                $(el).parent().find('.pf-smart-tag-lists .pf-others').append('<li class="smart-tag-field" data-type="other" data-field_id="admin_email">Site Admin Email</li><li class="smart-tag-field" data-type="other" data-field_id="user_email">User Email</li>');

                for (var key in email_field ) {
                    $(el).parent().find('.pf-smart-tag-lists .pf-fields').append('<li class = "smart-tag-field" data-type="field" data-field_id="'+key+'">'+email_field[key]+'</li>');
                }
            } else {
                if ( Object.keys(all_fields).length < 1 ){
                    $(el).parent().find('.pf-smart-tag-lists .smart-tag-title:not(".other-tag-title")').addClass('pie-forms-hidden');
                } else {
                    $(el).parent().find('.pf-smart-tag-lists .smart-tag-title:not(".other-tag-title")').removeClass('pie-forms-hidden');
                }
                for (var meta in all_fields ) {
                    $(el).parent().find('.pf-smart-tag-lists .pf-fields').append('<li class = "smart-tag-field" data-type="field" data-field_id="'+meta+'">'+all_fields[meta]+'</li>');
                }
            }
        }
    }
    // GDPR Agreement
    $PF_builder.on( 'input', '.pie-form-field-wrapper-gdpr-checkbox input', function(e) {
        var $this    = $( this ),
            value    = $this.val(),
            id       = $this.parent().data( 'field-id' );
            $('#pie-forms-field-'+id).find('.pie-gdpr-label').text(value);
        });
})( jQuery );
