<div class="row text-left javo-map-box-category">

	<div class="col-md-3 javo-map-box-title">
		<?php esc_html_e( "Category", 'javospot' ); ?>
	</div><!-- /.col-md-3 -->
	<div class="col-md-9 javo-map-box-field">
		<select name="map_filter[listing_category]" class="form-control javo-selectize-option" data-tax="listing_category">
			<option value=""><?php esc_html_e( "Any Categories", 'javospot' ); ?></option>
			<?php echo apply_filters('jvfrm_spot_get_selbox_child_term_lists', 'listing_category', null, 'select', $post->req_listing_category, 0, 0, "-");?>
		</select>
	</div><!-- /.col-md-9 -->

</div><!-- /.row -->