( function( $ ){

	// Edit Taxonomy Uploader
	if( typeof lv_edit_featured_taxonomy_variables != 'undefined' ) {
		var
			uploader		=  false
			, params		= lv_edit_featured_taxonomy_variables || {};

		; $( document )
			.on("click", ".fileupload", function( e ){
				e.preventDefault();

				var
					target			= $( this ).data( 'target' )
					, input			= $( this ).data( 'featured-field' )
					, parent		= $( this).closest( '.form-field' )
					, srcOutput	= $( this ).data( 'result-src' );

				srcOutput		= typeof srcOutput != 'undefined';

				if( ! uploader ){
					uploader = wp.media.frames.file_frame = wp.media({
						title			: params.mediaBox_title
						, multiple	: false
						, button	: {
							text		: params.mediaBox_select
						}
					});
				}
				uploader.off( 'select' ).on( 'select', function(){
					var response	= uploader.state().get( 'selection' ).first().toJSON();

					$( target ).val( response.url );
					$( input ).val( response.id );

					if( srcOutput )
						$( input ).val( response.url );

					$( 'img', parent ).prop( 'src', response.url );

				}).open();
				return;
			})
			.on("click", ".fileupload-remove", function(){
				var
					container		= $( this ).closest( '.form-field' ),
					sender			= $( "input[type='hidden']", container ),
					previewer		= $( "img", container );

				sender.val( null );
				previewer.prop( 'src', '' );
			});
	}


} )( jQuery );