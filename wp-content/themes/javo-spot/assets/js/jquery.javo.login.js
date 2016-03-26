( function( $ ){
	"use strict";
	var jvfrm_spot_ajax_login = function ( el )
	{
		this.form		= el;
		this.param		= el.find( "fieldset" );
		this.ajaxurl	= this.param.find( "[name='ajaxurl']" ).val();
		if( el.length )
			this.init();
	}
	jvfrm_spot_ajax_login.prototype = {
		constructor : jvfrm_spot_ajax_login
		, init : function ()
		{
			$( document )
				.on( 'submit', this.form.selector, this.submit() )
				.on( 'javo:loginProcessing', this.processing() );
		}
		, submit : function ()
		{
			var obj				= this;
			return function( e )
			{
				e.preventDefault();
				$.ajaxSetup({
					beforeSend	: function () {
						$( document ).trigger( 'javo:loginProcessing' );
					}
					, complete	: function () {
						$( document ).trigger( 'javo:loginProcessing', new Array( true ) );
					}
				});
				$.post(
					obj.ajaxurl
					, obj.form.serialize()
					, function ( xhr ){
						if( xhr && xhr.error ) {
							$.jvfrm_spot_msg({ content: xhr.error || "Failed" });
						}else{
							if( xhr && xhr.state == 'OK' )
								location.reload();;
						}
					}
					, 'json'
				)
				.fail( function(xhr) {
					console.log( xhr.responseText );
				} );
			}
		}
		, processing : function ()
		{
			var obj		= this;
			return function ( event, able )
			{
				var
					selector		= obj.form.selector
					, elements		= $( selector + ' *' )
					, submit		= obj.form.find( "[type='submit']" );
				if( able ) {
					elements.prop( 'disabled', false )	.removeClass( 'disabled' );
					submit.button( 'reset' );
				}else{
					elements.prop( 'disabled', true )	.addClass( 'disabled' );
					submit.button( 'loading' );
				}
			}
		}
	}
	$.jvfrm_spot_login = function( el ){
		new jvfrm_spot_ajax_login( el );
	}
} )( jQuery, window );