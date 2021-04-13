(function($) {

    // Show/hide based on reCAPTCHA type.
    $( 'select#pf_recaptcha_type' ).change( function() {
        var recaptcha_v2_site_key             = $( '#pf_recaptcha_v2_site_key' ).parents( 'tr' ).eq( 0 ),
            recaptcha_v2_secret_key           = $( '#pf_recaptcha_v2_secret_key' ).parents( 'tr' ).eq( 0 ),
            recaptcha_v2_invisible_site_key   = $( '#pf_recaptcha_v2_invisible_site_key' ).parents( 'tr' ).eq( 0 ),
            recaptcha_v2_invisible_secret_key = $( '#pf_recaptcha_v2_invisible_secret_key' ).parents( 'tr' ).eq( 0 ),
            recaptcha_v2_invisible            = $( '#pf_recaptcha_v2_invisible' ).parents( 'tr' ).eq( 0 ),
            recaptcha_v3_site_key             = $( '#pf_recaptcha_v3_site_key' ).parents( 'tr' ).eq( 0 ),
            recaptcha_v3_secret_key           = $( '#pf_recaptcha_v3_secret_key' ).parents( 'tr' ).eq( 0 );
        
        //if ( $( this ).is( ':checked' ) ) {
            if ( 'v2' === $( this ).val() ) {
                if( $( '#pf_recaptcha_v2_invisible' ).is(':checked') ) {
                    recaptcha_v2_site_key.hide();
                    recaptcha_v2_secret_key.hide();
                    recaptcha_v2_invisible_site_key.show();
                    recaptcha_v2_invisible_secret_key.show();
                } else {
                    recaptcha_v2_invisible_site_key.hide();
                    recaptcha_v2_invisible_secret_key.hide();
                    recaptcha_v2_site_key.show();
                    recaptcha_v2_secret_key.show();
                }
                recaptcha_v2_invisible.show();
                recaptcha_v3_site_key.hide();
                recaptcha_v3_secret_key.hide();
            } else {
                recaptcha_v2_site_key.hide();
                recaptcha_v2_secret_key.hide();
                recaptcha_v2_invisible.hide();
                recaptcha_v2_invisible_site_key.hide();
                recaptcha_v2_invisible_secret_key.hide();
                recaptcha_v3_site_key.show();
                recaptcha_v3_secret_key.show();
            }
        //}
    }).change();

    $( 'select#pf_recaptcha_v2_invisible' ).change( function() {
        if ( $( this ).is( ':checked' ) ) {
            $( '#pf_recaptcha_v2_site_key' ).parents( 'tr' ).eq( 0 ).hide();
            $( '#pf_recaptcha_v2_secret_key' ).parents( 'tr' ).eq( 0 ).hide();
            $( '#pf_recaptcha_v2_invisible_site_key' ).parents( 'tr' ).eq( 0 ).show();
            $( '#pf_recaptcha_v2_invisible_secret_key' ).parents( 'tr' ).eq( 0 ).show();
        } else {
            $( '#pf_recaptcha_v2_site_key' ).parents( 'tr' ).eq( 0 ).show();
            $( '#pf_recaptcha_v2_secret_key' ).parents( 'tr' ).eq( 0 ).show();
            $( '#pf_recaptcha_v2_invisible_site_key' ).parents( 'tr' ).eq( 0 ).hide();
            $( '#pf_recaptcha_v2_invisible_secret_key' ).parents( 'tr' ).eq( 0 ).hide();
        }
    });

})( jQuery );

