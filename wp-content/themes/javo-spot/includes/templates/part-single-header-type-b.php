<?php
$arrAllowPostTypes		= apply_filters( 'jvfrm_spot_single_post_types_array', Array( 'lv_listing' ) );

// Right Side Navigation
$jvfrm_spot_rs_navigation = jvfrm_spot_single_navigation();
function jvfrm_spot_custom_single_style()
{
	if ( false === (boolean)( $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' ) ) )
		$large_image_url	= '';
	else
		$large_image_url	=  $large_image_url[0];

	$output_style	= Array();
	$output_style[]	= sprintf( "%s:%s;", 'background-image'			, "url({$large_image_url})" );
	$output_style[]	= sprintf( "%s:%s;", 'background-attachment'	, 'fixed' );
	$output_style[]	= sprintf( "%s:%s;", 'background-repeat'		, 'no-repeat' );
	$output_style[]	= sprintf( "%s:%s;", 'background-position'		, 'center center' );
	$output_style[]	= sprintf( "%s:%s;", 'background-size'			, 'cover' );
	$output_style[]	= sprintf( "%s:%s;", '-webkit-background-size'	, 'cover' );
	$output_style[]	= sprintf( "%s:%s;", '-moz-background-size'		, 'cover' );
	$output_style[]	= sprintf( "%s:%s;", '-ms-background-size'		, 'cover' );
	$output_style[]	= sprintf( "%s:%s;", '-o-background-size'		, 'cover' );

	if( jvfrm_spot_tso()->get( 'topmap_height' ) )
		$output_style[]	= sprintf( "%s:%s;", 'height'				, jvfrm_spot_tso()->get('topmap_height') . 'px' );

	$output_style	= apply_filters( 'jvfrm_spot_featured_detail_header'	, $output_style, $large_image_url );
	$output_style	= esc_attr( join( ' ', $output_style ) );

	echo "style=\"{$output_style}\"";
} ?>
<div class="single-item-tab-feature-bg <?php echo sanitize_html_class( jvfrm_spot_tso()->get( 'lv_listing_single_header_cover', null ) ); ?>" <?php //jvfrm_spot_custom_single_style(); ?>>
	<div class="single-item-tab-bg">
		<div class="single-featured-img-wrap">
			<div class="single-featured-img">
			   <?php the_post_thumbnail( 'full' ); ?>
			</div>
			<div class="single-img-gradient"></div>
		</div>

		<div class="container captions">
			<div class="header-inner">
				<div class="item-bg-left pull-left text-left">
					<div class="taxonomy-wrap">
						<span class="item_cate admin-color-setting"><?php lava_directory_featured_terms( 'listing_category' ); ?></span>
						<span class="listing_location"><?php lava_directory_featured_terms( 'listing_location' ); ?></span>
					</div> <!-- /.taxonomy-wrap -->
					<h1 class="uppercase">
						<?php echo apply_filters( 'jvfrm_spot_' . get_post_type() . '_single_title', get_the_title() );?>
					</h1>
				</div>
				<div class="clearfix"></div>
			</div> <!-- header-inner -->
		</div> <!-- container -->
	</div> <!-- single-item-tab-bg -->
	<div class="single-item-tab-bg-overlay"></div>
	<div class="bg-dot-black"></div> <!-- bg-dot-black -->
</div> <!-- single-item-tab-feature-bg -->
<div id="javo-detail-item-header-wrap" class="container-fluid javo-spyscroll lava-spyscroll">
	<div class="container">
		<div data-spy="scroll" data-target=".navbar">
			<div id="javo-detail-item-header" class="navbar">
				<?php
				if( !empty( $jvfrm_spot_rs_navigation ) )	{
					echo "<ul class=\"nav-tabs\">\n";
					foreach( $jvfrm_spot_rs_navigation as $id => $attr )
					{
						if( ! in_Array( get_post_type(), $attr[ 'type' ] ) )
							continue;
						echo "\t\t\t\t\t\t <li class=\"javo-single-nav\" title=\"{$attr['label']}\">\n";
							echo "\t\t\t\t\t\t\t <a href=\"#{$id}\"><i class=\"{$attr['class']}\"></i> {$attr['label']}</a>\n";
						echo "\t\t\t\t\t\t </li>\n";
					}
					echo "\t\t\t\t\t</ul>\n";
				} ?>
			</div>
		</div>
	</div><!--/.nav-collapse -->
</div>