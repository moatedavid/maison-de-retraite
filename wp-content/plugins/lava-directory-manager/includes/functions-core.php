<?php

if( ! defined( 'ABSPATH' ) )
	die();




/**
 * Get assets url in plugin
 *
 * @return	String
 */
function lava_get_directory_manager_assets_url() {
	global $lava_directory_manager;

	if( ! is_object( $lava_directory_manager ) )
		return false;

	return $lava_directory_manager->assets_url;
}




/**
 * Get file load for template in plugin
 *
 * @param	String filename
 * @return	void
 */
function lava_directory_template( $filename )
{
	global $lava_directory_manager;

	$load_temlate_file		= "{$lava_directory_manager->template_path}/$filename";

	if( file_exists( $load_temlate_file ) )
		require_once $load_temlate_file;
}




/**
 *
 *
 * @param	Integer	Target ID of post
 * @param	String	Taxonomy ID
 * @param	String	Separator word
 * @return	String
 */
function lava_directory_terms( $post_id, $taxonomy='', $sep=', ' ) {
	global $lava_directory_manager_func;
	return $lava_directory_manager_func->getTermsNameInItems( $post_id, $taxonomy, $sep );
}




/**
 *
 *
 * @param	String	Taxonomy ID
 * @param	Integer	Target ID of post
 * @param	Boolean	if echo true print result
 * @return	String
 */
function lava_directory_featured_terms( $taxonomy='', $post_id=0, $echo=true ) {
	global $lava_directory_manager_func;

	$post			= get_post( $post_id );
	$sep_string		= '|';
	$tmp_string		= $lava_directory_manager_func->getTermsNameInItems( $post->ID, $taxonomy, $sep_string );
	$output_string	= @explode( $sep_string, $tmp_string );
	$output_result	= isset( $output_string[0] ) ? $output_string[0] : false;

	if( $echo )
		echo $output_result;
	return $output_result;
}




/**
 *
 *
 * @param	Integer	Target ID of post
 * @param	String	Taxonomy ID
 * @param	String	Separator word
 * @return	String
 */
function lava_directory_get_edit_page( $post_id=false ) {
	global $lava_directory_manager_func;
	return $lava_directory_manager_func->get_edit_link( $post_id );
}




/**
 *
 *
 * @param	Integer	Target ID of post
 * @param	String	Taxonomy ID
 * @param	String	Separator word
 * @return	String
 */
function lava_directory_edit_page() {
	$post	= get_post();
	echo !empty( $post ) ? lava_directory_get_edit_page( $post->ID ) : false;
}




/**
 *
 *
 * @param	Integer	Target ID of post
 * @param	String	Taxonomy ID
 * @param	String	Separator word
 * @return	String
 */
function lava_directory_setupdata( &$post=false ) {

	if( ! is_object( $post ) )
		$post = get_post();

	if( !class_exists( 'Lava_Directory_Manager_Func' ) )
		return;

	Lava_Directory_Manager_Func::setupdata( $post );
}




/**
 *
 *
 * @param	Integer	Target ID of post
 * @param	String	Taxonomy ID
 * @param	String	Separator word
 * @return	String
 */
function lava_directory_mapdata( &$post=false ) {

	if( ! is_object( $post ) )
		$post = get_post();

	if( !class_exists( 'Lava_Directory_Manager_Func' ) )
		return;

	Lava_Directory_Manager_Func::setup_mapdata( $post );
}




/**
 *
 * @return	String
 */
function lava_directory_get_widget() {
	$post	= get_post();
	echo "<ul class=\"lava-single-sidebar\">";
	if( is_active_sidebar( 'lava-' . get_post_type() . '-single-sidebar' ) )
		dynamic_sidebar( 'lava-' . get_post_type() . '-single-sidebar' );
	echo "</ul>";
}




/**
 *
 *
 * @param	Integer	Target ID of post
 * @param	String	Taxonomy ID
 * @param	String	Separator word
 * @return	String
 */
function lava_add_item_submit_button ( $new='', $change='' ) {

	global $edit;
	$post_type		= constant( 'Lava_Directory_Manager_Func::SLUG' );
	$strAddNew		= !empty( $new )	? $new			: __( "Submit", 'Lavacode' );
	$strModify		= !empty( $change )	? $change		: __( "Save", 'Lavacode' );
	$strButtonLable	= $edit->ID > 0		? $strModify	: $strAddNew;

	echo "<fieldset>";
		echo "<button type=\"submit\">{$strButtonLable}</button>";
		echo "<input type=\"hidden\" name=\"post_id\" value=\"{$edit->ID}\">";
		wp_nonce_field( 'lava_directory_manager_submit_' . $post_type, 'security' );
	echo "</fieldset>";
}




/**
 *
 *
 * @param	String	Image output size
 * @return	String
 */
function lava_directory_attach( $args=Array() ) {

	global $post;

	$option						= wp_parse_args(
		$args
		, Array(
			'size'				=> 'thumbnail'
			, 'type'			=> 'normal'
			, 'title'			=> false
			, 'wrap_class'		=> ''
			, 'container_class'	=> 'lava-attach'
			, 'featured_image'	=> false
		)
	);

	$arrSlideItems				= Array();

	if( $option[ 'featured_image' ] )
		$arrSlideItems[]		= get_post_thumbnail_id();

	$arrSlideItems				= Array_Merge( $arrSlideItems, (Array) $post->attach );

	$arrOutputHTML				= Array(
		'container_before'		=> "<div class=\"{$option[ 'container_class' ]}\">"
		, 'container_after'		=> '</div>'
		, 'wrap_before'			=> ''
		, 'wrap_after'			=> ''
		, 'item_before'			=> ''
		, 'item_after'			=> ''
	);

	switch( $option[ 'type' ] ) :
		case 'ul' :
		case 'slide' :
		case 'slider' :
			$classes		= @explode( ' ', trim( $option[ 'wrap_class' ] ) );
			$classes		= @implode( ' ', wp_parse_args( $classes, Array( 'lava-attach-item' ) ) );

			$arrOutputHTML[ 'wrap_before' ]		= sprintf( '<ul class="%s">', $classes );
			$arrOutputHTML[ 'item_before' ]		= "<li>";
			$arrOutputHTML[ 'item_after' ]		= "</li>";
			$arrOutputHTML[ 'wrap_after' ]		= "</ul>";
		break;
	endswitch;

	echo $arrOutputHTML[ 'container_before' ] . "\n";

		if( !empty( $option[ 'title' ] ) ) : echo $option[ 'title' ] . "\n"; endif;

		echo "\t" . $arrOutputHTML[ 'wrap_before' ] . "\n";

			if( !empty( $arrSlideItems ) ) : foreach( $arrSlideItems as $attachID ) {
				if( false !== (boolean)( $htmlAttachIMG = wp_get_attachment_image( $attachID, $option['size'] ) ) ) :
					echo "\t\t{$arrOutputHTML['item_before']}$htmlAttachIMG{$arrOutputHTML['item_after']}\n";
				endif;
			} endif;

		echo "\t" . $arrOutputHTML[ 'wrap_after' ] ."\n";

	echo $arrOutputHTML[ 'container_after' ] ."\n";
}




/**
 *
 *
 * @return	String
 */
if( !function_exists( 'lava_get_author_avatar' ) ) : function lava_get_author_avatar() {
	global $post;
	$strAvatarImage	= !empty( $post->avatar ) ? $post->avatar : null;
	echo "<img src=\"{$strAvatarImage}\">";
} endif;





/**
 *
 *
 * @param	Integer	Target ID of post
 * @return	Void
 */
function lava_directory_amenities( $post_id=0, $args=Array() ) {

	$post				= get_post();

	if( $post_id )
		$post			= get_post( $post_id );

	$args = shortcode_atts(
		Array(
			'container_before' => '',
			'container_after' => '',
		),
		$args
	);

	$strSlug = lava_directory()->core->getSlug();

	$is_display = apply_filters( 'lava_' . $strSlug . '_amenties_display', true, $post_id ) ;

	$strAmenity_ID		= 'listing_amenities';
	$is_showall			= lava_directory_manager_get_option( 'display_amenities' ) == 'showall';

	$arrAmenities		= get_terms( $strAmenity_ID, Array( 'hide_empty' => 0, 'fields' => 'id=>name' ) );
	$arrHasAmenities	= wp_get_object_terms( $post->ID, $strAmenity_ID, Array( 'fields' => 'ids' ) );

	if( is_wp_error( $arrAmenities ) ) {
		printf( "<div align=\"center\">%s</div>", $arrAmenities->get_error_message() );
		return;
	}

	if( !$is_display )
		return false;

	echo $args[ 'container_before' ];

		echo "<div id=\"lava-directory-amenities\">";

			if( $is_showall ) :
				if( !empty( $arrAmenities ) ) : foreach( $arrAmenities as $term_id => $name  ) {
					$hasTerms	= in_Array( $term_id, $arrHasAmenities );
					$strClass	= $hasTerms ? ' active' : '';
					echo "
						<div class=\"lava-amenity{$strClass}\">
							{$name}
						</div>
					";
				} else:
					printf( "<div align=\"center\">%s</div>", __( "Not found amenities.", 'Lavacode' ) );
				endif;

			else:

				if( !empty( $arrHasAmenities ) ) : foreach( $arrHasAmenities as $term_id ) {
					$strlabel	= get_term_by( 'id', $term_id, $strAmenity_ID )->name;
					echo "
						<div class=\"lava-amenity showall\">
							{$strlabel}
						</div>
					";
				} else:
					printf( "<div align=\"center\">%s</div>", __( "Not found amenities.", 'Lavacode' ) );
				endif;

			endif;
		echo "</div>";

	echo $args[ 'container_after' ];

}