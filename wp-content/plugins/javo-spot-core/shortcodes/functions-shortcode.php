<?php

if( ! function_exists( 'jvfrm_spot_ajaxShortcode_loader' ) ) :
	add_action( 'wp_ajax_jvfrm_spot_ajaxShortcode_loader', 'jvfrm_spot_ajaxShortcode_loader' );
	add_action( 'wp_ajax_nopriv_jvfrm_spot_ajaxShortcode_loader', 'jvfrm_spot_ajaxShortcode_loader' );
	function jvfrm_spot_ajaxShortcode_loader() {
		$jvfrm_spot_query		= new jvfrm_spot_Array( $_POST );
		$arrArgs			= $jvfrm_spot_query->get( 'attr', Array() );
		$strShortcode	= $arrArgs[ 'shortcode_name' ];

		if( ! class_exists( $strShortcode ) )
			die;

		$objShortcode	= new $strShortcode( $arrArgs, null);
		die( $objShortcode->loop( $objShortcode->get_post() ) );
	}
endif;

if( ! function_exists( 'jvfrm_spot_shortcode_post_types' ) ) :
	// Shocodes get post_type parameters
	add_filter( 'jvfrm_spot_shortcodes_post_types' , 'jvfrm_spot_shortcode_post_types' );
	function jvfrm_spot_shortcode_post_types ( $returnPostType = Array() )
	{
		$arrOutput		= Array();
		$arrPostTypes = apply_filters( 'jvfrm_spot_shortcodes_post_type_addition', $returnPostType );
		foreach( $arrPostTypes as $post_type ) {
			if( $objPost = get_post_type_object( $post_type ) )
				$arrOutput[ $objPost->labels->name ] = $post_type;
		}
		return $arrOutput;
	}
endif;

if( ! function_exists( 'jvfrm_spot_shortcode_taxonomies' ) ) :
	// Shocodes get taxonomies parameters
	function jvfrm_spot_shortcode_taxonomies( $object_type, $returnTaxonomy = Array() )
	{
		$arrAllTaxonomies		= get_object_taxonomies( $object_type, 'objects');

		if( in_array( 'post_tag', Array_Keys( $arrAllTaxonomies ) ) )
			unset( $arrAllTaxonomies[ 'post_tag'] );

		if( !empty( $arrAllTaxonomies ) ) foreach( $arrAllTaxonomies as $taxonomy )
			$returnTaxonomy[ join( ', ', $taxonomy->object_type ) . ' ('. $taxonomy->labels->name . ')' ] = $taxonomy->name;

		return $returnTaxonomy;
	}
endif;

if( ! function_exists( 'jvfrm_spot_shortcode_mailchimp_lists' ) ) :
	// Shocodes get mailchimp parameters
	add_filter( 'jvfrm_spot_mail_chimp_get_lists', 'jvfrm_spot_shortcode_mailchimp_lists' );
	function jvfrm_spot_shortcode_mailchimp_lists( $jvfrm_spot_result=Array() )
	{
		global $jvfrm_spot_tso;

		if( !$jvfrm_spot_tso instanceof jvfrm_spot_get_theme_settings )
			return $jvfrm_spot_result;

		if( '' !== ( $jvfrm_spot_api_key = $jvfrm_spot_tso->get( 'mailchimp_api', '' ) ) )
		{
			$jvfrm_spot_result	= Array();
			$mc_instance	= new MCAPI( $jvfrm_spot_api_key );
			$mc_lists_map	= $mc_instance->lists();

			if( !empty( $mc_lists_map['data'] ) )
			{
				foreach( $mc_lists_map['data'] as $list )
				{
					$jvfrm_spot_result[ $list[ 'name'] ] = $list[ 'id' ];
				}
			}else{
				$jvfrm_spot_result	= Array(
					__( "Wrong API key.", 'javo') => ''
				);
			}
		}

		return $jvfrm_spot_result;
	}
endif;

if( ! function_exists( 'jvfrm_spot_shortcode_map_template' ) ) :
	// Shocodes get map template parameters
	add_filter( 'jvfrm_spot_get_map_templates', 'jvfrm_spot_shortcode_map_template', 10, 2 );
	function jvfrm_spot_shortcode_map_template( $append = Array(), $slug='post' )
	{
		global $wpdb;

		$posts			= $wpdb->get_results(
			$wpdb->prepare( "
				select post.ID as ID, post.post_title as title from $wpdb->posts as post
				left join $wpdb->postmeta as meta on post.ID = meta.post_id
				where post.post_status='publish' and post.post_type='page' and
				meta.meta_key=%s and meta.meta_value=%s"
				, '_wp_page_template'
				, 'lava_lv_listing_map'
			)
			, OBJECT
		);
		if( !empty( $posts )  ) : foreach( $posts as $post ) {
			$append[ $post->title ] = $post->ID;
		} endif;
		return $append;
	}
endif;

if( !function_exists( 'jvfrm_spot_shortcode_featured_item' ) ) :
	// Shocodes get featured items parameters
	add_filter( 'jvfrm_spot_get_fetured_item', 'jvfrm_spot_shortcode_featured_item' );
	function jvfrm_spot_shortcode_featured_item( $append = Array() )
	{
		global $wpdb;
		$posts			= $wpdb->get_results(
			$wpdb->prepare( "
				select post.ID as ID, post.post_title as title from $wpdb->posts as post
				left join $wpdb->postmeta as meta on post.ID = meta.post_id
				where post.post_status='publish' and meta.meta_key=%s and meta.meta_value=%s"
				, '_featured_item'
				, 1
			)
			, OBJECT
		);
		if( !empty( $posts )  ) : foreach( $posts as $post ) {
			$append[ $post->title ] = $post->ID;
		} endif;
		return $append;
	}
endif;

add_action( 'after_setup_theme', 'jvfrm_spot_core_favorite' );
function jvfrm_spot_core_favorite(){
	if(
		function_exists( 'jvfrm_spot_core' ) &&
		class_exists( 'lvDirectoryFavorite_button' )
	) :
		$post_type = jvfrm_spot_core()->slug;
		add_action( 'lava_' . $post_type . '_map_container_after', 'jvfrm_spot_core_loadFavorite_script' );
	endif;
}
if( ! function_exists( 'jvfrm_spot_core_loadFavorite_script' ) ) :
	function jvfrm_spot_core_loadFavorite_script(){
		$objFavorite = new lvDirectoryFavorite_button;
		lvDirectoryFavorite_button::scripts();
	}
endif;