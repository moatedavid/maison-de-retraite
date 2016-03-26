;( function( $ ){

	// Json Generator
	var lava_directory_admin_settings = function () {

		this.init();
	}

	lava_directory_admin_settings.prototype = {

		constructor : lava_directory_admin_settings

		, init : function()
		{
			$( document )
				.on( 'click', '.lava-data-refresh-trigger'		, this.onGenerator() )
				.on( 'click', '.fileupload'					, this.image_upload() )
				.on( 'click', '.fileuploadcancel'			, this.image_remove() )

		}

		, onGenerator : function ()
		{
			var obj		= this;
			return function ( e )
			{
				var
					$this			= $( this )
					, frm			= $( document ).find( "form#lava_common_item_refresh" )
					, loading		= '<span class="spinner" style="display: block; float:left;"></span>'
					, strLoading	= $( this ).data( 'loading' ) || "Processing"
					, parent		= $( this ).parent();

				parent.html( loading + ' ' + strLoading );
				frm.find( "[name='lang']" ).val( $( this ).data( 'lang' ) );
				frm.submit();
			}
		}

		, image_upload : function ()
		{
			var file_frame;

			return function ( e, undef )
			{
				e.preventDefault();

				var
					attahment
					, output_image
					, t				= $( this ).attr( 'tar' )
					, bxTitle		= $( this ).data( 'uploader_title' ) || "Upload"
					, bxOK			= $( this ).data( 'uploader_button_text' ) || "Apply"
					, bxMultiple	= false;

				if( file_frame ){
					file_frame.open();
					return;
				}

				file_frame = wp.media.frames.file_frame = wp.media({
					title				: bxTitle
					, button			: { text : bxOK }
					, multiple			: bxMultiple
				});

				file_frame.on( 'select', function(){
					attachment			= file_frame.state().get('selection').first().toJSON();
					output_image		= attachment.url;

					if( attachment.sizes.thumbnail !== undef )
						output_image	= attachment.sizes.thumbnail.url;

					$("input[type='text'][tar='" + t + "']").val(attachment.url);
					$("img[tar='" + t + "']").prop("src", output_image );
				});

				file_frame.open();
			}
		}

		,image_remove : function ()
		{
			return function ( e )
			{
				var t = $(this).attr("tar");
				$("input[type='text'][tar='" + t + "']").val("");
				$("img[tar='" + t + "']").prop("src", "");
			}
		}
	}

	new lava_directory_admin_settings;

})( jQuery );