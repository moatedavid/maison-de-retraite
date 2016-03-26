<div class="jvfrm_spot_ts_tab javo-opts-group-tab hidden" tar="general">
<!-- Themes setting > General -->
	<h2><?php esc_html_e("General", 'javospot');?></h2>
	<table class="form-table">
	<tr><th>
		<?php esc_html_e("Page Layout Setting",'javospot'); ?>
	</th><td>
		<h4><?php esc_html_e('Page Layout', 'javospot');?></h4>
		<fieldset class="inner">

			<label style="padding: 0 15px 0;">
				<input type="radio" name="jvfrm_spot_ts[layout_style_boxed]" value='' <?php checked( '' == jvfrm_spot_tso()->get( 'layout_style_boxed', '' ) );?>>
				<?php esc_html_e( "Wide (Width : 1170px)", 'javospot');?>
			</label>

			<label style="padding: 0 15px 0;">
				<input type="radio" name="jvfrm_spot_ts[layout_style_boxed]" value='wide-1400' <?php checked( 'wide-1400' == jvfrm_spot_tso()->get( 'layout_style_boxed', '' ) );?>>
				<?php esc_html_e( "Wide (Width : 1400px)", 'javospot');?>
			</label>

			<label style="padding: 0 15px 0;">
				<input type="radio" name="jvfrm_spot_ts[layout_style_boxed]" value='active' <?php checked( 'active' == jvfrm_spot_tso()->get( 'layout_style_boxed', '' ) );?>>
				<?php esc_html_e( "Boxed (Width : 1170px)", 'javospot');?>
			</label>

			<label style="padding: 0 15px 0;">
				<input type="radio" name="jvfrm_spot_ts[layout_style_boxed]" value='active-1400' <?php checked( 'active-1400' == jvfrm_spot_tso()->get( 'layout_style_boxed', '' ) );?>>
				<?php esc_html_e( "Boxed (Width : 1400px)", 'javospot');?>
			</label>

		</fieldset>

		<h4><?php esc_html_e('Boxed layout shadow', 'javospot');?></h4>
		<fieldset class="inner">
			<label style="padding: 0 15px 0;">
				<input type="radio" name="jvfrm_spot_ts[layout_boxed_shadow]" value='' <?php checked( '' == $jvfrm_spot_tso->get('layout_boxed_shadow') );?>>
				<?php esc_html_e( "Enable", 'javospot');?>
			</label>
			<label style="padding: 0 15px 0;">
				<input type="radio" name="jvfrm_spot_ts[layout_boxed_shadow]" value='disable' <?php checked( 'disable' == $jvfrm_spot_tso->get('layout_boxed_shadow') );?>>
				<?php esc_html_e( "Disable", 'javospot');?>
			</label>
		</fieldset>

		<?php
		/** // Later use
		<h4><?php esc_html_e( "My Page Style", 'javospot' ); ?></h4>
		<fieldset class="inner">
			<select name="jvfrm_spot_ts[mypage_style]">
				<?php

				foreach(
					Array(
						''				=> esc_html__( "Type A (default)", 'javospot' ),
						'type-b'		=> esc_html__( "Type B", 'javospot' ),
					) as $type => $label ) {
					printf(
						"<option value=\"{$type}\" %s>{$label}</option>",
						selected( $jvfrm_spot_tso->get( 'mypage_style', '' ) == $type, true, false )
					);
				}
				?>
			</select>
		</fieldset>
		*/ ?>
		<h4><?php esc_html_e("Background Image",'javospot'); ?></h4>
		<fieldset class="inner">
			<p>
				<input type="text" name="jvfrm_spot_ts[page_background_image]" value="<?php echo esc_attr( $jvfrm_spot_tso->get('page_background_image'));?>" tar="g405">
				<input type="button" class="button button-primary fileupload" value="<?php esc_attr_e('Select Image', 'javospot');?>" tar="g405">
				<input class="fileuploadcancel button" tar="g405" value="<?php esc_attr_e('Delete', 'javospot');?>" type="button">
			</p>
			<p>
				<?php esc_html_e("Preview",'javospot'); ?><br>
				<img src="<?php echo esc_attr( $jvfrm_spot_tso->get('page_background_image'));?>" tar="g405">
			</p>
		</fieldset>
	</td></tr><tr><th>
		<?php esc_html_e("Blank Image Settings",'javospot'); ?>
		<span class='description'>
			<?php esc_html_e("Blank (or white) images are shown when no images are available. The preferred dimensions are 300x300.", 'javospot');?>
		</span>
	</th><td>
		<h4><?php esc_html_e("Blank Image",'javospot'); ?></h4>
		<fieldset class="inner">
			<p>
				<input type="text" name="jvfrm_spot_ts[no_image]" value="<?php echo esc_attr( $jvfrm_spot_tso->get('no_image', JVFRM_SPOT_IMG_DIR.'/no-image.png'));?>" tar="g404">
				<input type="button" class="button button-primary fileupload" value="<?php esc_attr_e('Select Image', 'javospot');?>" tar="g404">
				<input class="fileuploadcancel button" tar="g404" value="<?php esc_attr_e('Delete', 'javospot');?>" type="button">
			</p>
			<p>
				<?php esc_html_e("Preview",'javospot'); ?><br>
				<img src="<?php echo esc_attr( $jvfrm_spot_tso->get('no_image', JVFRM_SPOT_IMG_DIR.'/no-image.png'));?>" tar="g404">
			</p>
		</fieldset>
	</td></tr><tr><th>
		<?php esc_html_e("Login Settings",'javospot'); ?>
		<span class='description'>
			<?php esc_html_e("The page to redirect users to after a successful login.", 'javospot');?>
		</span>
	</th><td>
		<h4><?php esc_html_e("Login Modal Style",'javospot'); ?> :</h4>
		<fieldset class="inner">
			<?php
			$jvfrm_spot_login_modal_types = Array(
				1		=> esc_html__('Classic Style', 'javospot')
				, 2		=> esc_html__('Simple Style (Default)', 'javospot')
			);?>

			<select name="jvfrm_spot_ts[login_modal_type]">
				<?php
				foreach( $jvfrm_spot_login_modal_types as $key => $label )
				{
					printf('<option value="%s"%s>%s</option>', $key, selected( $key == $jvfrm_spot_tso->get('login_modal_type', 2), true, false), $label );
				} ?>
			</select>

		</fieldset>

		<h4><?php esc_html_e("Redirect to",'javospot'); ?> :</h4>
		<fieldset class="inner">
		<?php
		$jvfrm_spot_login_redirect_options = Array(
			'home'			=> esc_html__('Main Page', 'javospot')
			, 'current'		=> esc_html__('Current Page', 'javospot')
			, 'admin'		=> esc_html__('WordPress Profile Page', 'javospot')
		);

		?>
			<select name="jvfrm_spot_ts[login_redirect]">
				<option value=""><?php esc_html_e('Profile Page (Default)', 'javospot');?></option>
				<?php
				foreach($jvfrm_spot_login_redirect_options as $key=> $text){
					printf('<option value="%s" %s>%s</option>', $key
						,( $jvfrm_spot_tso->get( 'login_redirect' ) == $key ? " selected": "")
						, $text);
				} ?>
			</select>
		</fieldset>


		<h4><?php esc_html_e( "User Agreement",'javospot'); ?> :</h4>
		<fieldset class="inner">
			<select name="jvfrm_spot_ts[agree_register]">
				<option value=""><?php esc_html_e( "Disable", 'javospot' );?></option>
				<?php
				if( $pages = get_posts( "post_type=page&post_status=publish&posts_per_page=-1&suppress_filters=0" ) )
				{
					printf( "<optgroup label=\"%s\">", esc_html__( "Select a page for user agreement", 'javospot' ) );
					foreach( $pages as $post )
						printf(
							"<option value=\"{$post->ID}\" %s>{$post->post_title}</option>"
							, selected( $post->ID == $jvfrm_spot_tso->get( 'agree_register', '' ), true, false )
						);
					echo "</optgroup>";
				} ?>
			</select>
		</fieldset>

	</td></tr>
	<tr><th>
		<?php esc_html_e("Color Settings",'javospot'); ?>
		<span class="description">
			<?php esc_html_e("Choose colors to match your theme.", 'javospot');?>
		</span>
	</th><td>

		<h4><?php esc_html_e("Primary Color Selection", 'javospot'); ?></h4>
		<fieldset class="inner">
			<input name="jvfrm_spot_ts[total_button_color]" type="text" value="<?php echo esc_attr( $jvfrm_spot_tso->get( 'total_button_color' )  );?>" class="wp_color_picker" data-default-color="#0FAF97">
		</fieldset>

		<h4><?php esc_html_e( "Primary Font Color Selection", 'javospot' ); ?></h4>
		<fieldset class="inner">
			<input name="jvfrm_spot_ts[primary_font_color]" type="text" value="<?php echo esc_attr( $jvfrm_spot_tso->get( 'primary_font_color' ) );?>" class="wp_color_picker" data-default-color="#fff">
		</fieldset>

		<h4><?php esc_html_e("Border Color Setup", 'javospot'); ?></h4>
		<fieldset class="inner">
			<label><input type="radio" name="jvfrm_spot_ts[total_button_border_use]" value="use" <?php checked($jvfrm_spot_tso->get('total_button_border_use') == "use");?>><?php esc_html_e('Use', 'javospot');?></label>
			<label><input type="radio" name="jvfrm_spot_ts[total_button_border_use]" value="" <?php checked($jvfrm_spot_tso->get('total_button_border_use')== "");?>><?php esc_html_e('Not Use', 'javospot');?></label>
		</fieldset>

		<h4><?php esc_html_e("Border Color Selection", 'javospot'); ?></h4>
		<fieldset class="inner">
			<input name="jvfrm_spot_ts[total_button_border_color]" type="text" value="<?php echo esc_attr( $jvfrm_spot_tso->get( 'total_button_border_color' ) );?>" class="wp_color_picker" data-default-color="#0FAF97">
		</fieldset>

	</td></tr><tr><th>

		<?php esc_html_e('Miscellaneous Settings','javospot'); ?>
		<span class='description'>
			<?php esc_html_e('Other settings', 'javospot');?>
		</span>
	</th><td>
		<h4><?php esc_html_e('Preloader', 'javospot');?></h4>
		<fieldset class="inner">

			<label>
				<input type="radio" name="jvfrm_spot_ts[preloader_hide]" value="use" <?php checked( 'use' == $jvfrm_spot_tso->get('preloader_hide') );?>>
				<?php esc_html_e( "Enable", 'javospot');?>
			</label>
			<label>
				<input type="radio" name="jvfrm_spot_ts[preloader_hide]" value="" <?php checked( '' == $jvfrm_spot_tso->get('preloader_hide') );?>>
				<?php esc_html_e( "Disable", 'javospot');?>
			</label>

		</fieldset>

		<h4><?php esc_html_e('Fixed Contact-Us Button (on Right-Buttom)', 'javospot');?></h4>
		<fieldset class="inner">

			<label>
				<input type="radio" name="jvfrm_spot_ts[scroll_rb_contact_us]" value="use" <?php checked( 'use' == $jvfrm_spot_tso->get('scroll_rb_contact_us') );?>>
				<?php esc_html_e( "Enable", 'javospot');?>
			</label>
			<label>
				<input type="radio" name="jvfrm_spot_ts[scroll_rb_contact_us]" value="" <?php checked( '' == $jvfrm_spot_tso->get('scroll_rb_contact_us') );?>>
				<?php esc_html_e( "Disable", 'javospot');?>
			</label>

		</fieldset>

		<h4><?php esc_html_e('WordPress Admin Top Bar (Except for the Admin)', 'javospot');?></h4>
		<fieldset class="inner">

			<label>
				<input type="radio" name="jvfrm_spot_ts[adminbar_hidden]" value="" <?php checked( '' == $jvfrm_spot_tso->get('adminbar_hidden') );?>>
				<?php esc_html_e( "Enable", 'javospot');?>
			</label>
			<label>
				<input type="radio" name="jvfrm_spot_ts[adminbar_hidden]" value="use" <?php checked( 'use' == $jvfrm_spot_tso->get('adminbar_hidden') );?>>
				<?php esc_html_e( "Disable", 'javospot');?>
			</label>

		</fieldset>

		<h4><?php esc_html_e( "Use Lazy Loading Images", 'javospot');?></h4>
		<fieldset class="inner">
			<label>
				<input type="radio" name="jvfrm_spot_ts[lazyload]" value="" <?php checked( '' == $jvfrm_spot_tso->get('lazyload') );?>>
				<?php esc_html_e( "Enable", 'javospot');?>
			</label>
			<label>
				<input type="radio" name="jvfrm_spot_ts[lazyload]" value="disable" <?php checked( 'disable' == $jvfrm_spot_tso->get('lazyload') );?>>
				<?php esc_html_e( "Disable", 'javospot');?>
			</label>
		</fieldset>

	</td></tr><tr><th>
		<?php esc_html_e("Contact Form Modal Settings",'javospot'); ?>
	</th><td>
		<h4><?php esc_html_e('This form is for Contact Modal', 'javospot');?></h4>
		<fieldset class="inner">

			<label style="padding: 0 15px 0;">
				<input type="radio" name="jvfrm_spot_ts[modal_contact_type]" value='' <?php checked( '' == $jvfrm_spot_tso->get('modal_contact_type') );?>>
				<?php esc_html_e( "None", 'javospot');?>
			</label>
			<label style="padding: 0 15px 0;">
				<input type="radio" name="jvfrm_spot_ts[modal_contact_type]" value='contactform' <?php checked( 'contactform' == $jvfrm_spot_tso->get('modal_contact_type') );?>>
				<?php esc_html_e( "Contact Form", 'javospot');?>
			</label>
			<label style="padding: 0 15px 0;">
				<input type="radio" name="jvfrm_spot_ts[modal_contact_type]" value='ninjaform' <?php checked( 'ninjaform' == $jvfrm_spot_tso->get('modal_contact_type') );?>>
				<?php esc_html_e( "Ninja Form", 'javospot');?>
			</label>

		</fieldset>
		<fieldset class="inner">
			<label>
				<?php esc_html_e('Contact Form ID', 'javospot');?><br>
				<input type="text" name="jvfrm_spot_ts[modal_contact_form_id]" value="<?php echo esc_attr( $jvfrm_spot_tso->get('modal_contact_form_id' ) );?>">
			</label>
			<p><?php esc_html_e('To create a Contact Form ID, please go to the Contact Form Menu.', 'javospot');?></p>
		</fieldset>
	</td></tr><tr><th>
		<?php esc_html_e("My Page PM Form Settings",'javospot'); ?>
	</th><td>
		<h4><?php esc_html_e('This form is for PM', 'javospot');?></h4>
		<fieldset class="inner">

			<label style="padding: 0 15px 0;">
				<input type="radio" name="jvfrm_spot_ts[pm_contact_type]" value='' <?php checked( '' == $jvfrm_spot_tso->get('pm_contact_type') );?>>
				<?php esc_html_e( "None", 'javospot');?>
			</label>
			<label style="padding: 0 15px 0;">
				<input type="radio" name="jvfrm_spot_ts[pm_contact_type]" value='contactform' <?php checked( 'contactform' == $jvfrm_spot_tso->get('pm_contact_type') );?>>
				<?php esc_html_e( "Contact Form", 'javospot');?>
			</label>
			<label style="padding: 0 15px 0;">
				<input type="radio" name="jvfrm_spot_ts[pm_contact_type]" value='ninjaform' <?php checked( 'ninjaform' == $jvfrm_spot_tso->get('pm_contact_type') );?>>
				<?php esc_html_e( "Ninja Form", 'javospot');?>
			</label>

		</fieldset>
		<fieldset class="inner">
			<label>
				<?php esc_html_e('PM Form ID', 'javospot');?><br>
				<input type="text" name="jvfrm_spot_ts[pm_contact_form_id]" value="<?php echo esc_attr( $jvfrm_spot_tso->get('pm_contact_form_id' ) );?>">
			</label>
			<p><?php esc_html_e('To create a Contact Form ID, please go to the Contact Form Menu.', 'javospot');?></p>
		</fieldset>
	</td></tr>
	</table>
</div>