/* global pf_data */
jQuery( function( $ ) {
	'use strict';

		var form = $( 'form[data-ajax_submission="1"]' );

		form.each( function( i, v ) {
			$( document ).ready( function() {
				var formTuple = $( v ),
					btn = formTuple.find( '.pie-submit' );

				
				btn.on( 'click', function( e ) {
					
					var data = formTuple.serializeArray();
					e.preventDefault();

					// We let the bubbling events in form play itself out.
					formTuple.trigger( 'focusout' ).trigger( 'change' ).trigger( 'submit' );

					var errors = formTuple.find( '.pie-error:visible' );

					if ( errors.length > 0 ) {
						$( [document.documentElement, document.body] ).animate({
							scrollTop: errors.last().offset().top - 70
						}, 800 );
						return;
					}

					// Change the text to user defined property.
					$( this ).html( formTuple.data( 'process-text' ) );

					// Add action intend for ajax_form_submission endpoint.
					data.push({
						name: 'action',
						value: 'pie_forms_ajax_form_submission'
					});
					data.push({
						name: 'security',
						value: pf_data.pf_ajax_submission
					});
					
					// Fire the ajax request.
					$.ajax({
						url: pf_data.ajax_url,
						type: 'POST',
						data: data
					})
					.done( function ( xhr, textStatus, errorThrown ) {
						
						var redirect_url = ( xhr.data && xhr.data.redirect_url ) ? xhr.data.redirect_url : '';
						if ( redirect_url ) {
							formTuple.trigger( 'reset' );
							window.location = redirect_url;
							return;
						}
						if ( 'success' === xhr.data.response || true === xhr.success ) {
							formTuple.trigger( 'reset' );
							formTuple.closest( '.pie-forms' ).html( xhr.data.message ).focus();
						} else {
							console.log(xhr.data);
							var	form_id = formTuple.data( 'formid' ),
								error   =  pf_data.error,
								err     =  JSON.parse( errorThrown.responseText );

								if ( 'string' === typeof err.data.message ) {
									error =  err.data.message;
								}

								formTuple.closest( '.pie-forms' ).find( '.pie-forms-notice' ).remove();
								formTuple.closest( '.pie-forms' ).prepend( '<div class="pie-forms-notice pie-forms-notice--error" role="alert">'+ error  +'</div>' ).focus();


							btn.attr( 'disabled', false ).html( pf_data.submit );
						}
					})
					.fail( function () {
						btn.attr( 'disabled', false ).html( pf_data.submit );
						formTuple.trigger( 'focusout' ).trigger( 'change' );
						formTuple.closest( '.pie-forms' ).find( '.pie-forms-notice' ).remove();
						formTuple.closest( '.pie-forms' ).prepend( '<div class="pie-forms-notice pie-forms-notice--error" role="alert">'+ pf_data.error  +'</div>' ).focus();
					})
					.always( function( xhr ) {
						var redirect_url = ( xhr.data && xhr.data.redirect_url ) ? xhr.data.redirect_url : '';
						if ( ! redirect_url && $( '.pie-forms-notice' ).length ) {
							$( [document.documentElement, document.body] ).animate({
								scrollTop: $( '.pie-forms-notice' ).offset().top
							}, 800 );
						}
					});
				});
			});
		});

});
