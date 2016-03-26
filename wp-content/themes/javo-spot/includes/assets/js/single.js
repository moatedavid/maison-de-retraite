;( function( $ ) {
	"use strict";

	var jvfrm_spot_single_template_script			= function( el ) {
		this.el = el;
		this.param	= jvfrm_spot_custom_post_param;
		this.init();
	}

	jvfrm_spot_single_template_script.prototype	= {
		constractor : jvfrm_spot_single_template_script
		, init : function()
		{
			var
				obj			= this
				, offy		= $( "#wpadminbar" ).outerHeight() || 0;
			$( document )
				.on( 'click', $( '.lava-Di-share-trigger', this.el ).selector, obj.showShare() )
				.on( 'click', $( '.lava-wg-single-report-trigger', this.el ).selector, obj.showReport() )
				.on( 'click', $( '#lava-wg-url-link-copy', this.el ).selector, obj.copyLink() )
				.on( 'click', '.expandable-content-overlay', obj.readMore( obj ) )
				.on( 'click', '.jv-custom-post-content-trigger', obj.readMoreType2( obj ) )
				.on( 'click', '#javo-item-detail-image-section a.link-display', obj.imageMore() );

			if( this.param.map_type != 'boxed' ){
				$( document )
					.on( 'lava:single-msp-setup-after', function(){
						$( window )
							.on( 'resize', obj.bindResize())
							.trigger( 'resize' );
					} );
			}

			if( this.param.widget_sticky != 'disable' )
				this.el.find( '.panel' ).sticky({ topSpacing : parseInt( offy ) }).css( 'zIndex', 1 );

			$( ".sidebar-inner" ).css( 'background', '' );
			$( ".lava-spyscroll" ).css({ padding:0, 'zIndex':2 }).sticky({ topSpacing : parseInt( offy ) });
		}
		, showShare : function()
		{
			var obj = this;
			return function( e )
			{
				e.preventDefault();
				jQuery.lava_msg({
					content			: $("#lava-Di-share").html()
					, classes		: 'lava-Di-share-dialog'
					, close			: 0
					, close_trigger	: '.lava-Di-share-dialog .close'
					, blur_close	: true
				});
				if( typeof ZeroClipboard == 'function' ) {
					var objZC = new ZeroClipboard( $( '#lava-wg-url-link-copy', document ).get(0) );
					objZC.on( 'aftercopy', function( event ) {
						alert( "Copy : " + event.data[ 'text/plain' ] );
					} );
				}

				$( document ).trigger( 'jvfrm_spot_sns:init' );
			}
		}
		, showReport : function()
		{
			var obj		= this;
			return function( e )
			{
				e.preventDefault();
				jQuery.lava_msg({
					content			: $( '#lava-wg-single-report-template' ).html()
					, classes		: 'lava-wg-single-report-dialog'
					, close			: 0
					, close_trigger	: '.lava-wg-single-report-dialog .close'
					, blur_close	: true
				});
			}
		}
		, copyLink : function()
		{
			return function( e )
			{
				e.preentDefault();
				// Todo : Code here.
			}
		}
		, bindResize : function()
		{
			var
				obj					= this
				, container				= $( ".jv-single-map-wapper" )
				, parent			= container.parent()
				, posLeft			= 0;
			return function()
			{
				var
					is_boxed		= $( 'body' ).hasClass( 'boxed' )
					, offset			= parent.offset().left
					, dcWidth		= $( window ).width()
					, pdParent	= parseInt( parent.css( 'padding-left' ) )
				if( is_boxed )
					return;
				if( offset > 0 ) {
					posLeft		= -( offset );
				} else {
					posLeft		= 0;
				}
				container.css({
					marginLeft	: posLeft - pdParent
					, width		: dcWidth
				});
			}
		},

		readMore : function( obj ) {
			return function( e )
			{
				e.preventDefault();
				$( this ).closest( '.expandable-content-wrap' ).addClass( 'loaded' );
			}
		},

		readMoreType2 : function( obj ) {
			return function( e )
			{
				e.preventDefault();
				$( this ).closest( '.jv-custom-post-content' ).addClass( 'loaded' );
			}
		},

		imageMore : function(){
			return function(e){
				e.preventDefault();
				var
					container	= $( '#javo-item-detail-image-section' ),
					parseImage	= container.data( 'images' );
				if( typeof $.fn.lightGallery == 'undefined' )
					return;
				container.lightGallery({
					dynamic		: true,
					dynamicEl	: parseImage
				});
			}
		}
	}
	new jvfrm_spot_single_template_script( $( "form.lava-wg-author-contact-form" ) );
})(jQuery );