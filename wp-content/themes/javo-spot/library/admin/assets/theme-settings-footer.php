<div class="jvfrm_spot_ts_tab javo-opts-group-tab hidden" tar="footer">
	<h2> <?php esc_html_e("Footer Settings", 'javospot'); ?> </h2>
	<table class="form-table">
	<tr><th>
		<?php esc_html_e('Color Settings', 'javospot');?>
		<span class="description">
			<?php esc_html_e('You can change colors on footer area.', 'javospot');?>
		</span>
	</th><td>

		<h4><?php esc_html_e('Footer Background Color','javospot');?></h4>

		<!-- <fieldset class='inner' id="jv-theme-settings-footer-top-bg-color">
			<span><?php esc_html_e( "Top", 'javospot' ); ?></span>
			<input name="jvfrm_spot_ts[footer_top_background_color]" type="text" value="<?php echo esc_attr( $jvfrm_spot_tso->get('footer_top_background_color','#333333'));?>" class="wp_color_picker" data-default-color="#323131">
		</fieldset> -->

		<fieldset class="inner" id="jv-theme-settings-footer-middle-bg-color">
			<!-- <span><?php esc_html_e( "Middle", 'javospot' ); ?></span> -->
			<input name="jvfrm_spot_ts[footer_middle_background_color]" type="text" value="<?php echo esc_attr( $jvfrm_spot_tso->get('footer_middle_background_color','#323131'));?>" class="wp_color_picker" data-default-color="#333333">
		</fieldset>

		<!-- <fieldset class="inner" id="jv-theme-settings-footer-bottom-bg-color">
			<span><?php esc_html_e( "Bottom", 'javospot' ); ?></span>
			<input name="jvfrm_spot_ts[footer_bottom_background_color]" type="text" value="<?php echo esc_attr( $jvfrm_spot_tso->get('footer_bottom_background_color','#333333'));?>" class="wp_color_picker" data-default-color="#323131">
		</fieldset> -->

		<h4><?php esc_html_e('Footer Title Color','javospot');?></h4>
		<fieldset class="inner">
			<input name="jvfrm_spot_ts[footer_title_color]" type="text" value="<?php echo esc_attr( $jvfrm_spot_tso->get('footer_title_color','#ffffff'));?>" class="wp_color_picker" data-default-color="#ffffff">
		</fieldset>

		<h4><?php esc_html_e('Footer Title Underline Color','javospot');?></h4>
		<fieldset class="inner">
			<input name="jvfrm_spot_ts[footer_title_underline_color]" type="text" value="<?php echo esc_attr( $jvfrm_spot_tso->get('footer_title_underline_color','#ffffff'));?>" class="wp_color_picker" data-default-color="footer_title_underline_color">
		</fieldset>

		<h4><?php esc_html_e('Footer Content Color','javospot');?></h4>
		<fieldset class="inner">
			<input name="jvfrm_spot_ts[footer_content_color]" type="text" value="<?php echo esc_attr( $jvfrm_spot_tso->get('footer_content_color','#999999'));?>" class="wp_color_picker" data-default-color="#999999">
		</fieldset>

	<h4><?php esc_html_e('Footer Content Link Hover Color','javospot');?></h4>
		<fieldset class="inner">
			<input name="jvfrm_spot_ts[footer_content_link_hover_color]" type="text" value="<?php echo esc_attr( $jvfrm_spot_tso->get('footer_content_link_hover_color','#ffffff'));?>" class="wp_color_picker" data-default-color="ffffff">
	</fieldset>

	</td></tr>

	<tr><th>
		<?php esc_html_e( 'Footer Layout Option', 'javospot' );?>
		<span class="description"></span>
	</th><td>

		<h4><?php esc_html_e( 'Footer Type','javospot' );?></h4>
		<fieldset class="inner">
			<label>
				<input type="radio" name="jvfrm_spot_ts[footer_container_type]" value="" <?php checked($jvfrm_spot_tso->get('footer_container_type') == '' );?>>
				<?php esc_html_e( "Wide", 'javospot');?>
			</label>
			<label>
				<input type="radio" name="jvfrm_spot_ts[footer_container_type]" value="active" <?php checked($jvfrm_spot_tso->get('footer_container_type')== "active");?>>
				<?php esc_html_e( "Boxed", 'javospot');?>
			</label>
		</fieldset>

	</td></tr>

	<tr><th>
		<?php esc_html_e( 'Footer Infomation', 'javospot' );?>
	</th><td>

		<h4><?php esc_html_e( 'Show Footer Information','javospot' );?></h4>
		<fieldset class="inner">
			<label>
				<input type="radio" name="jvfrm_spot_ts[footer_info_use]" value="active" <?php checked($jvfrm_spot_tso->get('footer_info_use') == 'active' );?>>
				<?php esc_html_e( "Enabled", 'javospot');?>
			</label>
			<label>
				<input type="radio" name="jvfrm_spot_ts[footer_info_use]" value="" <?php checked($jvfrm_spot_tso->get('footer_info_use')== "");?>>
				<?php esc_html_e( "Disabled", 'javospot');?>
			</label>
		</fieldset>

		<h4><?php esc_html_e( 'Social Icons','javospot' );?></h4>
		<fieldset class="inner">
			<label>
				<input type="radio" name="jvfrm_spot_ts[footer_social_use]" value="active" <?php checked($jvfrm_spot_tso->get('footer_social_use') == 'active' );?>>
				<?php esc_html_e( "Enabled", 'javospot');?>
			</label>
			<label>
				<input type="radio" name="jvfrm_spot_ts[footer_social_use]" value="" <?php checked($jvfrm_spot_tso->get('footer_social_use')== "");?>>
				<?php esc_html_e( "Disabled", 'javospot');?>
			</label>
		</fieldset>

		<h4><?php esc_html_e( 'Footer bottom - Middle Area Title','javospot' );?></h4>
		<fieldset class="inner">
			<input name="jvfrm_spot_ts[footer_info_text_title]" value="<?php echo esc_attr( $jvfrm_spot_tso->get('footer_info_text_title'));?>">
		</fieldset>

		<h4><?php esc_html_e( 'Footer bottom - Middle Text','javospot' );?></h4>
		<fieldset class="inner">
			<textarea name="jvfrm_spot_ts[footer_text]" class="large-text code" rows="10"><?php echo stripslashes($jvfrm_spot_tso->get('footer_text', ''));?></textarea>
		</fieldset>



		<h4><?php esc_html_e( 'Footer Bottom - Right Title','javospot' );?></h4>
		<fieldset class="inner">
			<input name="jvfrm_spot_ts[footer_info_image_title]" value="<?php echo esc_attr( $jvfrm_spot_tso->get('footer_info_image_title'));?>">
		</fieldset>

		<h4><?php esc_html_e( 'Footer Bottom - Right','javospot' );?></h4>
		<fieldset class="inner">
			<input type="text" name="jvfrm_spot_ts[footer_info_image_url]" value="<?php echo esc_attr( $jvfrm_spot_tso->get('footer_info_image_url'));?>" tar="footer_info_image">
			<input type="button" class="button button-primary fileupload" value="<?php esc_attr_e('Select Image', 'javospot');?>" tar="footer_info_image">
			<input class="fileuploadcancel button" tar="footer_image" value="<?php esc_attr_e('Delete', 'javospot');?>" type="button">
			<p>
				<?php esc_html_e("Preview",'javospot'); ?><br>
				<img src="<?php echo esc_attr( $jvfrm_spot_tso->get('footer_info_image_url'));?>" tar="footer_info_image" style="max-width:60%;">
			</p>
		</fieldset>

	</td></tr>

	<tr><th>
		<?php esc_html_e('Footer Background Option', 'javospot');?>
		<span class="description">
			<?php esc_html_e('You can add a background image on footer area.', 'javospot');?>
		</span>
	</th><td>
		<h4><?php esc_html_e('Background status','javospot');?></h4>
		<fieldset class="inner">
			<label>
				<input type="radio" name="jvfrm_spot_ts[footer_background_image_use]" value="use" <?php checked($jvfrm_spot_tso->get('footer_background_image_use') == "use");?>><?php esc_html_e('Enable', 'javospot');?>
			</label>
			<label>
				<input type="radio" name="jvfrm_spot_ts[footer_background_image_use]" value="" <?php checked($jvfrm_spot_tso->get('footer_background_image_use')== "");?>><?php esc_html_e('Disable', 'javospot');?>
			</label>
		</fieldset>
		<h4><?php esc_html_e('Image Upload','javospot');?></h4>
		<fieldset class="inner">
			<input type="text" name="jvfrm_spot_ts[footer_background_image_url]" value="<?php echo esc_attr( $jvfrm_spot_tso->get('footer_background_image_url'));?>" tar="footer_image">
			<input type="button" class="button button-primary fileupload" value="<?php esc_attr_e('Select Image', 'javospot');?>" tar="footer_image">
			<input class="fileuploadcancel button" tar="footer_image" value="<?php esc_attr_e('Delete', 'javospot');?>" type="button">
			<p>
				<?php esc_html_e("Preview",'javospot'); ?><br>
				<img src="<?php echo esc_attr( $jvfrm_spot_tso->get('footer_background_image_url'));?>" tar="footer_image" style="max-width:60%;">
			</p>
		</fieldset>
		<h4><?php esc_html_e('Background Size','javospot'); ?></h4>
		<fieldset class="inner">
			<?php
			$footer_background_size = Array(
				'contain'			=> esc_html__('Contain', 'javospot')
				, 'cover'		=> esc_html__('Cover', 'javospot')
			);
			?>
			<select name="jvfrm_spot_ts[footer_background_size]">
				<option value=""><?php esc_html_e('Select', 'javospot');?></option>
				<?php
				foreach($footer_background_size as $size=> $text){
					printf('<option value="%s" %s>%s</option>', $size
						,( $jvfrm_spot_tso->get('footer_background_size')!='' && $jvfrm_spot_tso->get('footer_background_size') == $size? " selected": "")
						, $text);
				} ?>
			</select>
		</fieldset>
		<h4><?php esc_html_e('Background Repeat','javospot'); ?></h4>
		<fieldset class="inner">
			<?php
			$footer_background_repeat = Array(
				'repeat'			=> esc_html__('Repeat X, Y', 'javospot')
				, 'repeat-x'		=> esc_html__('Repeat-X', 'javospot')
				, 'repeat-y'		=> esc_html__('Repeat-Y', 'javospot')
				, 'no-repeat'		=> esc_html__('No-Repeat', 'javospot')
			);
			?>
			<select name="jvfrm_spot_ts[footer_background_repeat]">
				<option value=""><?php esc_html_e('Select', 'javospot');?></option>
				<?php
				foreach($footer_background_repeat as $repeat=> $text){
					printf('<option value="%s" %s>%s</option>', $repeat
						,( $jvfrm_spot_tso->get('footer_background_repeat')!='' && $jvfrm_spot_tso->get('footer_background_repeat') == $repeat? " selected": "")
						, $text);
				} ?>
			</select>
		</fieldset>
		<h4><?php esc_html_e('Opacity (0.1 ~ 1)','javospot'); ?></h4>
		<fieldset class="inner">
			<input name="jvfrm_spot_ts[footer_background_opacity]" value="<?php echo esc_attr( $jvfrm_spot_tso->get('footer_background_opacity'));?>">
		</fieldset>
	</td></tr><tr><th>
		<?php esc_html_e('Copyright Information', 'javospot');?>
		<span class="description">
			<?php esc_html_e('Type your copyright information. It will be displayed on footer.', 'javospot');?>
		</span>
	</th><td>
		<h4><?php esc_html_e('Copyright Color','javospot');?></h4>
		<fieldset class="inner">
			<input name="jvfrm_spot_ts[copyright_color]" type="text" value="<?php echo esc_attr( $jvfrm_spot_tso->get('copyright_color','#ffffff'));?>" class="wp_color_picker" data-default-color="ffffff">
		</fieldset>

		<h4><?php esc_html_e('Display Text or HTML', 'javospot');?></h4>
		<fieldset>
			<textarea name="jvfrm_spot_ts[copyright]" class="large-text code" rows="15"><?php echo stripslashes($jvfrm_spot_tso->get('copyright', ''));?></textarea>
		</fieldset>
	</td></tr>
	</table>
</div>