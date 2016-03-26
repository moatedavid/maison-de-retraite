<div class="jvfrm_spot_ts_tab javo-opts-group-tab hidden" tar="map">
	<!--------------------------------------------
	:: Map Common
	---------------------------------------------->
	<h2> <?php esc_html_e("Map Settings", 'javospot'); ?> </h2>
	<table class="form-table">
	<tr><th>
		<?php esc_html_e( "Map Template Page( Map )", 'javospot' );?>
		<span class="description"></span>
	</th><td>
		<h4><?php esc_html_e( "Default Map Marker", 'javospot');?>: </h4>
		<fieldset  class="inner">
			<input type="text" name="jvfrm_spot_ts[map_marker]" value="<?php echo esc_attr($jvfrm_spot_tso->get('map_marker', null));?>" tar="map_marker">
			<input type="button" class="button button-primary fileupload" value="<?php esc_html_e('Select Image', 'javospot');?>" tar="map_marker">
			<input class="fileuploadcancel button" tar="map_marker" value="<?php esc_html_e('Delete', 'javospot');?>" type="button">
			<p>
				<?php esc_html_e( "Preview", 'javospot' ); ?><br>
				<img src="<?php echo $jvfrm_spot_tso->get( 'map_marker', null );?>" tar="map_marker">
			</p>
		</fieldset>

		<h4><?php esc_html_e( "Allow to use mouse wheel on map", 'javospot');?>: </h4>
		<fieldset  class="inner">
			<select name="jvfrm_spot_ts[map_allow_mousewheel]">
				<?php
				foreach(
					Array(
						''				=> esc_html__( "Disable (default)", 'javospot' ),
						'enable'		=> esc_html__( "Enable", 'javospot' )
					) as $value => $label
				) printf( "<option value=\"{$value}\"%s>{$label}</option>", selected( $jvfrm_spot_tso->get( 'map_allow_mousewheel' ) == $value, true, false ) );
				?>
			</select>
		</fieldset>
	</td></tr><tr><th>

		<?php esc_html_e( "Map Template Page( Listing )", 'javospot' );?>
		<span class="description"></span>
		</th><td>
			<h4><?php esc_html_e("Background color", 'javospot');?>: </h4>
			<fieldset  class="inner">
				<input name="jvfrm_spot_ts[map_page_listing_part_bg]" type="text" value="<?php echo esc_attr( $jvfrm_spot_tso->get('map_page_listing_part_bg','') );?>" class="wp_color_picker" data-default-color="">
			</fieldset>
			<h4><?php esc_html_e("Background image", 'javospot');?>: </h4>
			<fieldset  class="inner">
				<input type="text" name="jvfrm_spot_ts[map_page_listing_part_bg_image]" value="<?php echo esc_attr( $jvfrm_spot_tso->get('map_page_listing_part_bg_image') );?>" tar="map_listing_bg_image">
				<input type="button" class="button button-primary fileupload" value="<?php esc_attr_e('Select Image', 'javospot');?>" tar="map_listing_bg_image">
				<input class="fileuploadcancel button" tar="map_listing_bg_image" value="<?php esc_attr_e('Delete', 'javospot');?>" type="button">
				<p>
					<?php esc_html_e("Preview",'javospot'); ?><br>
					<img src="<?php echo esc_attr( $jvfrm_spot_tso->get('map_page_listing_part_bg_image' ) );?>" tar="map_listing_bg_image" style="max-width:60%;">
				</p>
			</fieldset>
		</td></tr>
	</table>
</div>