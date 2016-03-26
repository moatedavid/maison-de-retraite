<?php
/**
* Custom codes for Visual Composet
* 1. Grid Builder
*/


/** [ 1. Grid Builder ] **/
/** vc grid builder - listing category **/
add_filter( 'vc_grid_item_shortcodes', 'jvfrm_spot_listing_category_add_grid_shortcode' );
function jvfrm_spot_listing_category_add_grid_shortcode( $shortcodes ) {
   $shortcodes['jvfrm_spot_list_category'] = array(
     'name' => __( 'List category', 'javospot' ),
     'base' => 'jvfrm_spot_list_category',
     'category' => __( 'Content', 'javospot' ),
     'description' => __( 'Show List Category. Only for Javo Listings (lv_listing).', 'javospot' ),
     'post_type' => Vc_Grid_Item_Editor::postType(),
  );

   return $shortcodes;
}

add_filter( 'vc_gitem_template_attribute_listing_category', 'jvfrm_spot_gitem_attr_listing_category', 10, 2 );
function jvfrm_spot_gitem_attr_listing_category( $value, $data ){
	global $post;
	$strOutput = false;
	if( get_post_type( $post->ID ) == jvfrm_spot_core()->slug ) {
		$arrTerms = wp_get_object_terms( $post->ID, 'listing_category', array( 'fields' => 'names' ));
		$strOutput = join( ', ', $arrTerms );
	}
	return $strOutput;
}

// output function
add_shortcode( 'jvfrm_spot_list_category', 'jvfrm_spot_list_category_render' );
function jvfrm_spot_list_category_render() {
	return '<div class="jv_vc_gi_listing_location">{{ listing_category }}</div>';
}



/** vc grid builder - listing location **/
add_filter( 'vc_grid_item_shortcodes', 'jvfrm_spot_listing_location_add_grid_shortcode' );
function jvfrm_spot_listing_location_add_grid_shortcode( $shortcodes ) {
   $shortcodes['jvfrm_spot_list_location'] = array(
     'name' => __( 'List location', 'javospot' ),
     'base' => 'jvfrm_spot_list_location',
     'location' => __( 'Content', 'javospot' ),
     'description' => __( 'Show List location. Only for Javo Listings (lv_listing).', 'javospot' ),
     'post_type' => Vc_Grid_Item_Editor::postType(),
  );

   return $shortcodes;
}

add_filter( 'vc_gitem_template_attribute_listing_location', 'jvfrm_spot_gitem_attr_listing_location', 10, 2 );
function jvfrm_spot_gitem_attr_listing_location( $value, $data ){
	global $post;
	$strOutput = false;
	if( get_post_type( $post->ID ) == jvfrm_spot_core()->slug ) {
		$arrTerms = wp_get_object_terms( $post->ID, 'listing_location', array( 'fields' => 'names' ));
		$strOutput = join( ', ', $arrTerms );
	}
	return $strOutput;
}

// output function
add_shortcode( 'jvfrm_spot_list_location', 'jvfrm_spot_list_location_render' );
function jvfrm_spot_list_location_render() {
	return '<div class="jv_vc_gi_listing_location">{{ listing_location }}</div>';
}