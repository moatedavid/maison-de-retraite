<div class="jvfrm_spot_ts_tab javo-opts-group-tab hidden" tar="singlepage">
	<h2><?php esc_html_e("Single Page", 'javospot'); ?></h2>
	<table class="form-table">
		<tr><th>
			<?php esc_html_e("Single Item Color Settings", 'javospot');?>
			<span class="description">
				<?php esc_html_e("The color set up here will have the first priority to be applied.", 'javospot');?>
			</span>
		</th><td>
			<h4><?php esc_html_e("Backgound Color",'javospot'); ?></h4>
			<fieldset class="inner">
				<input name="jvfrm_spot_ts[single_page_background_color]" type="text" value="<?php echo esc_attr( $jvfrm_spot_tso->get('single_page_background_color','#ffffff') );?>" class="wp_color_picker" data-default-color="#ffffff">
			</fieldset>

			<h4><?php esc_html_e("Background Color (Box layout)",'javospot'); ?></h4>
			<fieldset class="inner">
				<input name="jvfrm_spot_ts[single_page_box_background_color]" type="text" value="<?php echo esc_attr( $jvfrm_spot_tso->get('single_page_box_background_color','#ffffff') );?>" class="wp_color_picker" data-default-color="#ffffff">
			</fieldset>

			<h4><?php esc_html_e("Title Color",'javospot'); ?></h4>
			<fieldset class="inner">
				<input name="jvfrm_spot_ts[single_page_title_color]" type="text" value="<?php echo esc_attr( $jvfrm_spot_tso->get('single_page_title_color','#ffffff') );?>" class="wp_color_picker" data-default-color="">
			</fieldset>

			<h4><?php esc_html_e("Location Title Color",'javospot'); ?></h4>
			<fieldset class="inner">
				<input name="jvfrm_spot_ts[single_page_location_title_color]" type="text" value="<?php echo esc_attr( $jvfrm_spot_tso->get('single_page_location_title_color','#ffffff') );?>" class="wp_color_picker" data-default-color="">
			</fieldset>

			<h4><?php esc_html_e("Description Text Color",'javospot'); ?></h4>
			<fieldset class="inner">
				<input name="jvfrm_spot_ts[single_page_description_color]" type="text" value="<?php echo esc_attr( $jvfrm_spot_tso->get('single_page_description_color','#888888') );?>" class="wp_color_picker" data-default-color="#888888">
			</fieldset>
		</td></tr>
	</table><!-- form-table -->
</div><!-- javo-opts-group-tab -->