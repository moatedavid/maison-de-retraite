<?php
$jvfrm_spot_multi_filters			= Array(
	'listing_amenities' => (Array) $post->req_listing_amenities
);
if( !empty( $jvfrm_spot_multi_filters  ) ) :  foreach( $jvfrm_spot_multi_filters as $filter => $currentvalue ) {
	?>
	<div class="row text-left javo-map-box-advance-term">
		<div class="col-md-3 jv-advanced-titles javo-map-box-title">
			<?php echo get_taxonomy( $filter )->label; ?>
		</div><!-- /.col-md-3 -->
		<div class="col-md-9 jv-advanced-fields">
			<?php
			if( $jvfrm_spot_this_term = get_terms( $filter, Array( 'hide_empty' => 0 ) ) )
				foreach( $jvfrm_spot_this_term as $term )
				{
					echo "<div class=\"col-md-4 col-sm-6 filter-terms\">";
						echo "<label>";
							echo "<input type=\"checkbox\" name=\"jvfrm_spot_map_multiple_filter\"" . ' ';
							echo "value=\"{$term->term_id}\" data-tax=\"{$filter}\" data-title=\"" . get_taxonomy( $filter )->label . "\"";
							checked( in_Array( $term->term_id, $currentvalue ) );
							echo ">";
							echo esc_html( $term->name );
						echo "</label>";

					echo "</div>";
				}
			else
				printf(
					"<div class=\"col-md-12\"><div class=\"alert alert-danger\">%s</div></div>"
					, sprintf( esc_html__( "Not Found %s", 'javospot' ) , $filter )
				);
			?>
		</div><!-- /.col-md-9 -->
	</div>
	<?php
} endif;