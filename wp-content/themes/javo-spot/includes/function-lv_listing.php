<?php

function jvfrm_spot_single_navigation(){
	return apply_filters(
		'jvfrm_spot_detail_item_nav'
		, Array(
			'page-style'				=> Array(
				'label'					=> esc_html__( "Top", 'javospot' )
				, 'class'				=> 'glyphicon glyphicon-home'
				, 'type'				=> Array( get_post_type() )
			)
		)
	);
}

/**
 *
 */
function jvfrm_spot_single_map_switcher()
{
	$arrOptions					= Array(
		'option-map'				=> Array(
			'label'					=> esc_html__( "Map", 'javospot' )
			,'target'					=> '#lava-single-map-area'
			, 'comment'			=> ''
			, 'active'				=> true
		)
		, 'option-streetview'	=> Array(
			'label'					=> esc_html__( "StreetView", 'javospot' )
			,'target'					=> '#lava-single-streetview-area'
			, 'comment'			=> esc_html__( "This location is not supported by google StreetView or the location did not add.", 'javospot' )
		)
	);
	echo "<ul class=\"jv-single-lv_listing-map-switcher hidden\">";
	if( !empty( $arrOptions ) ) foreach( $arrOptions as $optionName => $optionMeta ) {
		$isActive			= isset( $optionMeta[ 'active' ] );
		$strListActive	= $isActive ? ' active' : null;
		echo "<li class=\"switch-option-item {$optionName}{$strListActive}\"" . ' ';
		echo "data-target=\"{$optionMeta['target']}\" data-comment=\"{$optionMeta['comment']}\">";
		echo "{$optionMeta['label']}</li>";
	}
	echo "</ul>";
	?>

	<script type="text/javascript">
	jQuery( function( $ ){
		"use strict";
		$( document ).on( 'lava:single-msp-setup-after',
			function( e, obj ) {
				var
					switcher_wrap = $( ".jv-single-lv_listing-map-switcher" ),
					switcher = $( ".switch-option-item", switcher_wrap ),
					btnStreetView = $( ".option-streetview", switcher_wrap );

				switcher_wrap.removeClass( 'hidden' );

				if( !obj.panoramaAllow ){
					btnStreetView.tooltip({ title:btnStreetView.data( 'comment' ) });
						return false;
				}
				switcher.on( 'click', function( e ) {
					var
						map				= obj.map.gmap3( 'get' )
						, pano			= map.getStreetView();

					if( $( this ).hasClass( 'active' ) )
						return;

					$( "#lava-single-map-area, #lava-single-streetview-area" ).addClass( 'hidden' ).hide();
					switcher.removeClass( 'active' );
					$( this ).addClass( 'active' );
					$( $( this ).data( 'target' ) ).removeClass( 'hidden' ).fadeIn();
					obj.map.gmap3({ trigger:'resize' });
					pano.setVisible( true );
				} );
			}
		);
	} );
	</script>

	<?php
}

if( !function_exists( 'jvfrm_spot_has_attach' ) ) : function jvfrm_spot_has_attach(){
	global $post;
	return !empty( $post->attach );
} endif;

if( !function_exists( 'jvfrm_spot_get_reportShortcode' ) ) : function jvfrm_spot_get_reportShortcode(){
	global $lava_report_shortcode;
	return $lava_report_shortcode;
} endif;

if( !function_exists( 'jvfrm_spot_getSearch1_Shortcode' ) ) : function jvfrm_spot_getSearch1_Shortcode(){
	global $jvfrm_spot_search1;
	return $jvfrm_spot_search1;
} endif;