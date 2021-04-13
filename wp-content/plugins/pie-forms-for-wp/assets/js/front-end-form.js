(function($) {
    var $pie_form  = $( 'form.pie-form' )

    if ( typeof $.fn.validate === 'undefined' ) {
        return false;
    }

    // Prepend URL field contents with http:// if user input doesn't contain a schema.
    $( '.pie-field-url input[type=url]' ).change( function () {
        var url = $( this ).val();
        if ( ! url ) {
            return false;
        }
        if ( url.substr( 0, 7 ) !== 'http://' && url.substr( 0, 8 ) !== 'https://' ) {
            $( this ).val( 'http://' + url );
        }
    });

    //DATE PICKER FOR FORMS
    $('.pie-field-date').each(function(){
        $( ".jquery-ui-field" ).datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: "-90:+0",
          });
    });
    
        // Validate email addresses.

    jQuery.validator.addMethod("email", function( value, element ) {
        var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
        return this.optional( element ) || pattern.test( value );
    }, "Please enter a valid email address");


    jQuery.validator.addMethod("tel", function( value, element ) {
        var pattern = new RegExp(/^(?=.*[0-9])[- +()0-9]+$/);
        return this.optional( element ) || pattern.test( value );
    }, "Please enter a valid phone number");

   
        

   
    $pie_form.each( function() {
        var $this = $( this );
        

        // List messages to show for required fields. Use name of the field as key.
        var error_messages = {};
        var validate_rules = {};

        $( '.pie-field' ).each( function() {
            var form_id       = $( this ).closest( 'form' ).data( 'formid' );
            var field_id      = $( this ).data( 'field-id' );
            var error_message = $( this ).data( 'required-field-message' );
            var key           = 'pie_forms[form_fields][' + field_id + ']'; // Name of the input field is used as a key.
            var data_rule     = $(this).data('set_rule'); 


           
            // Required messages for checkbox
            if ( $( this ).is( '.pie-field-checkbox' ) || $( this ).is( '.pie-field-multiselect' ) ) {
                key = key + '[]';
            }  
            
        
			// Check if the error message has been already set
            if ( error_message ) {
                error_messages[ key ] = {
                    required: error_message, // Set message using 'required' key to avoid conflicts with other validations.

                };
            }

            if($(this).is('.g-recaptcha')){
                error_messages[ 'g-recaptcha-hidden' ] = {
                    required: error_message, // Set message using 'required' key to avoid conflicts with other validations.

                };
            }

            // Validate custom regex rules
            if(data_rule){
                validate_rules[ key ] = {
                    validation_rule:true,

                };
            }
            
          
        });
        
        $this.validate({
            messages: error_messages,
            rules: validate_rules,
            
            ignore: '',
            errorClass: 'pie-error',
            validClass: 'pie-valid',
            errorPlacement: function( error, element ) {
               
                if ( 'radio' === element.attr( 'type' ) || 'checkbox' === element.attr( 'type' ) ) {
                    element.closest( '.pie-field-checkbox' ).find( 'label.pie-error' ).remove();
                    element.parent().parent().parent().append( error );
                    if(element.parent().hasClass( 'pie-field-gdpr') ){
                        element.siblings('.pie-gdpr-agreement-label').after(error);
                    }
                } else if('multiselect' === element.attr( 'type' )){
                    element.next('.select2').after(error);
                } else{
                    error.insertAfter( element );
                }
            },
            highlight: function( element, errorClass, validClass ) {
                var $element  = $( element ),
                    $parent   = $element.closest( '.form-row' ),
                    inputName = $element.attr( 'name' );

                if ( $element.attr( 'type' ) === 'radio' || $element.attr( 'type' ) === 'checkbox' ) {
                    $parent.find( 'input[name=\''+inputName+'\']' ).addClass( errorClass ).removeClass( validClass );
                } else {
                    $element.addClass( errorClass ).removeClass( validClass );
                }

                $parent.removeClass( 'pie-forms-validated' ).addClass( 'pie-forms-invalid pie-got-error' );
            },
            unhighlight: function( element, errorClass, validClass ) {
                var $element  = $( element ),
                    $parent   = $element.closest( '.form-row' ),
                    inputName = $element.attr( 'name' );

                if ( $element.attr( 'type' ) === 'radio' || $element.attr( 'type' ) === 'checkbox' ) {
                    $parent.find( 'input[name=\''+inputName+'\']' ).addClass( validClass ).removeClass( errorClass );
                } else {
                    $element.addClass( validClass ).removeClass( errorClass );
                }

                $parent.removeClass( 'pie-got-error' );
            },
            submitHandler: function( form ) {
                var $form       = $( form ),
                    $submit     = $form.find( '.pie-submit' ),
                    processText = $submit.data( 'process-text' );

                // Process form.
                if ( processText ) {
                    $submit.text( processText ).prop( 'disabled', true );
                }

                if ( 1 !== $form.data( 'ajax_submission' ) ) {
                    form.submit();
                } else {
                    return;
                }
            },
            onkeyup: function( element, event ) {
                
                var excludedKeys = [ 16, 17, 18, 20, 35, 36, 37, 38, 39, 40, 45, 144, 225 ];

                if ( $( element ).hasClass( 'pie-forms-novalidate-onkeyup' ) ) {
                    return;
                }

                if ( 9 === event.which && '' === this.elementValue( element ) || -1 !== $.inArray( event.keyCode, excludedKeys ) ) {
                    return;
                } else if ( element.name in this.submitted || element.name in this.invalid ) {
                    
                    this.element( element );
                }
            },
            onfocusout: function( element ) {
                
                var validate = false;

                // Empty value error handling for elements with onkeyup validation disabled.
                if ( $( element ).hasClass( 'pie-forms-novalidate-onkeyup' ) && ! element.value ) {
                    validate = true;
                }

                if ( ! this.checkable( element ) && ( element.name in this.submitted || ! this.optional( element ) ) ) {
                    validate = true;
                }

                if ( validate ) {
                    this.element( element );
                }
                
            },
            onclick: function( element ) {
                var validate = false;

                if ( 'checkbox' === ( element || {} ).type ) {
                    $( element ).closest( '.pie-field-checkbox' ).find( 'label.pie-error' ).remove();
                    validate = true;
                } else {
                    $( element ).valid();
                }

                if ( validate ) {
                    this.element( element );
                }
            }
        });

        
    });

    
       //CUSTOM VALIDATION REGEX
       jQuery.validator.addMethod("validation_rule", function( value, element ) {   
        var thisregex = $(element).parent().attr("data-set_rule");    
     
        var pattern = new RegExp(thisregex);
        return this.optional( element ) || pattern.test( value );
        
    }, function(value, element){
        var validationmsg           = $(element).parent().attr("data-custom_validation_message");
        var custom_validation_msg   = validationmsg ? validationmsg : 'Please enter a valid value';     
        return custom_validation_msg;
    });

    //TEXTAREA WORD LIMIT
    
    $('.pie-forms-limit-words-enabled').each(function(){

        $(this).bind('copy paste cut',function(event) {
            event.preventDefault();
        });
        var wordLen = $(this).attr('data-text-limit'),
            len; // Maximum word length

            if(wordLen == 0){
                $(this).attr('disabled',' disabled' )
            }
            $(this).after( "<span class='counter' id='word-counter'></span>" );
            $(this).keydown(function(event) {	
                
            len = $(this).val().split(/[\s]+/);
            if (len != '' ) {

                $(this).siblings('#word-counter').text(len.length+' out of '+wordLen);
                if (len.length > wordLen) { 
                    $(this).siblings('#word-counter').text(' out of words');

                    if ( event.keyCode == 46 || event.keyCode == 8 ) {// Allow backspace and delete buttons
                    } else if (event.keyCode < 48 || event.keyCode > 57 ) {//all other buttons
                        event.preventDefault();
                    }
                }
            }else{
                $(this).siblings('#word-counter').text('0 out of '+wordLen);
            }
        });
           
        
    });

    //TEXTAREA CHARACTER LIMIT

    $('.pie-forms-limit-characters-enabled').each(function(){
        var characterLimit   = $(this).attr('data-text-limit');
        $(this).after( "<span class='counter' id='character-counter'></span>" );
        if(characterLimit == 0){
            $(this).attr('disabled',' disabled' )
        }
        $(this).keyup(function(event) {	
            var characterlenght  = $(this).val().length;
            $(this).siblings('#character-counter').text(characterlenght+' out of '+characterLimit);
            if (characterlenght > characterLimit) { 
                $(this).siblings('#character-counter').text(' out of words');
            } 
        })
    });


    $(document).ready(function() {
        //MULTISELECT
        if($('.pie-forms-multiselect').length > 0){
            $('.pie-forms-multiselect').select2();
        }
        //SEARCH SELECT
        if($('.pie-forms-search-select').length > 0){
            $('.pie-forms-search-select').select2();
        }
    });
    
    //CAPTCHA LOAD FOCUS FIELDS
    var captchaLoaded = false;
    $('.pf-field-wrapper input').on('focus', function() {
        
        

        if (captchaLoaded) {
            return;
        }

        var $recaptcha_type = $('.pie-form').attr("recaptcha_type");
        var $recaptcha_key  = $('.pie-form').attr("recaptcha_key");

        var head = document.getElementsByTagName('head')[0];
        var recaptchaScript = document.createElement('script');
        var _recaptchaScript = document.createElement('script');
        var $site_key = $recaptcha_key;
        recaptchaScript.type = 'text/javascript';
        _recaptchaScript.type = 'text/javascript';
        if ( 'v2' === $recaptcha_type ) {
            
            recaptchaScript.src = 'https://www.google.com/recaptcha/api.js?onload=PFRecaptchaLoad&render=explicit&ver=2.0.0';
            
            _recaptchaScript.text  = 'var PFRecaptchaLoad = function(){jQuery(".g-recaptcha").each(function(index, el){var recaptchaID = grecaptcha.render(el,{callback:function(){PFRecaptchaCallback(el);}},true);jQuery(el).attr( "data-recaptcha-id", recaptchaID);});};';
            _recaptchaScript.text += 'var PFRecaptchaCallback = function(el){jQuery(el).parent().find(".pie-recaptcha-hidden").val("1").trigger("change").valid();};';

            head.appendChild(_recaptchaScript);
            setTimeout(function() {
                head.appendChild(recaptchaScript);
            }, 1000);
            
        } else if ( 'v3' === $recaptcha_type ) {
            recaptchaScript.src    = 'https://www.google.com/recaptcha/api.js?render=' + $site_key + '&#038;ver=3.0.0';
            _recaptchaScript.text = 'grecaptcha.ready(function(){grecaptcha.execute("' + $site_key + '",{action:"pie_form"}).then(function(token){var f=document.getElementsByName("pie_forms[recaptcha]");for(var i=0;i<f.length;i++){f[i].value = token;}});});';

            head.appendChild(recaptchaScript);
            setTimeout(function() {
                head.appendChild(_recaptchaScript);
            }, 1000);
        }

       
        captchaLoaded = true;

        
        
       
    });
         
    
})( jQuery );

