<div class="row text-left javo-map-box-contract-type">
	<div class="col-md-3 javo-map-box-title javo-map-box-title">
		<?php esc_html_e( "Location", 'javospot' ); ?>
	</div><!-- /.col-md-3 -->
	<div class="col-md-9 javo-map-box-field">
		<select name="map_filter[listing_location]" class="form-control javo-selectize-option" data-tax="listing_location">
			<option value=""><?php esc_html_e( "Any Location", 'javospot' ); ?></option>
			<?php echo apply_filters('jvfrm_spot_get_selbox_child_term_lists', 'listing_location', null, 'select', $post->req_listing_location, 0, 0, "-");?>
		</select>
	</div><!-- /.col-md-9 -->
</div><!-- /.row -->