<?php
$edit_id						= isset( $edit ) ? $edit->ID : 0;
$lava_post_type			= constant( 'Lava_Directory_Manager_Func::SLUG' );
$addition_terms			= apply_filters( "lava_add_{$lava_post_type}_terms", Array() );

$limit_terms				= Array(
	'listing_category' => lava_directory()->admin->get_settings( 'limit_category', 0 ),
	'listing_location'		=> 1
);

if( !empty( $addition_terms ) ) : foreach( $addition_terms as $taxonomy ) {
	$this_value			= wp_get_object_terms( $edit_id, $taxonomy, Array( 'fields' => 'ids' ) );
	if( is_wp_error( $this_value ) )
		continue;
        
        $label = get_taxonomy( $taxonomy )->label;
        if($taxonomy  == 'listing_amenities'){
            $label = 'Liste des équipements';
        }
        if($taxonomy  == 'listing_keyword'){
            $label = 'Liste des mots clés';
        }
        if($taxonomy  == 'listing_category'){
            $label = 'Liste des catégories';
        }
        if($taxonomy  == 'listing_location'){
            $label = 'Liste des villes';
        }       
	printf( "
		<div class=\"form-inner\">
			<label>%s</label>
			<select name=\"lava_additem_terms[{$taxonomy}][]\" multiple=\"multiple\" class=\"lava-add-item-selectize\" data-limit=\"%s\" data-create=\"%s\">
				<option value=\"\">%s</option>%s
			</select>
		</div>"
		, $label
		, ( Array_Key_Exists( $taxonomy, $limit_terms ) ? $limit_terms[ $taxonomy ] : 0 )
		, ( $taxonomy === 'listing_keyword' )
		, $label
		, apply_filters('lava_get_selbox_child_term_lists', $taxonomy, null, 'select', $this_value, 0, 0, "-")
	);
} endif;

?>

<script type="text/javascript">
jQuery( function( $ ){

	var lava_Ai_update_extend = function()
	{
		if( ! window.__LAVA_AI__EXTEND__ )
			this._init();
	}

	lava_Ai_update_extend.prototype = {

		constrcutor : lava_Ai_update_extend

		, _init : function()
		{
			var obj						= this;
			window.__LAVA_AI__EXTEND__	= 1;
			obj.setCategory();
		}

		, setCategory : function()
		{
			var obj						= this;

			$( '.lava-add-item-selectize' ).each( function() {

				var
					limit		= parseInt( $( this ).data( 'limit' ) || 0 )
					, isCreate	= parseInt( $( this ).data( 'create' ) || 0 )
					options		= { plugins : [ 'remove_button' ], create : isCreate };

				if( limit > 0 )
					options.maxItems	= limit;

				$( this ).selectize( options );

			} );
		}
	};
	new lava_Ai_update_extend;
} );
</script>