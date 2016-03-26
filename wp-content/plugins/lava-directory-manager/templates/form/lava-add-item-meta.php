<?php

$lava_item_fields	= apply_filters( "lava_{$lava_post_type}_more_meta", Array() );

if( !empty( $lava_item_fields ) && is_Array( $lava_item_fields ) ) : foreach( $lava_item_fields as $fID => $meta ) {

	$post_id			= intVal( get_query_var( 'edit' ) );
	$this_value			= get_post_meta( $post_id, $fID, true );

	echo "<div class=\"form-inner\">";
		echo "<label>{$meta['label']}</label>";
	switch( $meta[ 'element'] ) {
		case 'select' :
			echo "<select name=\"lava_additem_meta[{$fID}]\" class=\"form-control\">";
			if( !empty( $meta[ 'values' ] ) ) foreach( $meta[ 'values' ] as $value => $label )
				printf( "<option value=\"{$value}\"%s>{$label}</option>", selected( $this_value == $value, true, false ) );
			echo "</select>";
		break;
		case 'textarea' :
			echo join( ' ',
				Array(
					'<textarea',
					"name=\"lava_additem_meta[{$fID}]\"",
					"class=\"form-control\"",
					'style="resize:none; width:100%; height:150px;"',
					'>',
					esc_html( $this_value ),
					'</textarea>'
				)
			);
		break;
		case 'input' :
			echo "<input type=\"{$meta['type']}\" name=\"lava_additem_meta[{$fID}]\" value=\"{$this_value}\" class=\"form-control\">";
		break;
	}
	echo "</div>";

} endif;