<?php
$jvfrm_spot_options = Array(
	'header_type' => apply_filters( 'jvfrm_spot_options_header_types', Array() )
	, 'header_skin' => Array(
		esc_html__("Dark", 'javospot')							=> ""
		, esc_html__("Light", 'javospot')						=> "light"
	)
	, 'able_disable' => Array(
		esc_html__("Disable", 'javospot')					=> "disabled"
		,esc_html__("Able", 'javospot')							=> "enable"

	)
	, 'header_fullwidth' => Array(
		esc_html__("Center", 'javospot')						=> "fixed"
		, esc_html__("Wide", 'javospot')						=> "full"
	)
	, 'header_relation' => Array(
		esc_html__("Transparency menu", 'javospot')	=> "absolute"
		,esc_html__("Default menu", 'javospot')				=> "relative"

	)
); ?>

<div class="jvfrm_spot_ts_tab javo-opts-group-tab hidden" tar="header">
	<h2><?php esc_html_e("Heading Settings", 'javospot'); ?> </h2>
	<table class="form-table">
	<tr><th>
		<?php esc_html_e('My Page Menu Settings','javospot'); ?>
		<span class='description'>
			<?php esc_html_e('My page menu settings', 'javospot');?>
		</span>
	</th><td>
		<h4><?php esc_html_e('Display My Page Menu in the Navigation Bar', 'javospot');?></h4>

		<fieldset class="inner">
			<label><input type="checkbox" name="jvfrm_spot_ts[nav_show_mypage]" value="use" <?php checked($jvfrm_spot_tso->get('nav_show_mypage')== "use");?>><?php esc_html_e('Enabled', 'javospot');?></label>
		</fieldset>
		<div><?php esc_html_e('Please make sure to create a permarlink.', 'javospot');?></div>
		<div><a href='<?php echo admin_url('options-permalink.php');?>'><?php esc_html_e('Please select "POST NAME" in the permarlink list', 'javospot');?></a></div>
	</td></tr><tr><th>
		<?php esc_html_e( "Default Style", 'javospot');?>
		<span class="description"></span>
	</th><td>

		<?php /**
		<h4><?php esc_html_e( "Topbar", 'javospot');?></h4>
		<fieldset class="inner">
			<?php
			foreach(
				Array(
					'enable'	=> esc_html__( "Enable", 'javospot' ),
					'disable'	=> esc_html__( "Disable", 'javospot' ),
				)
				as $strValue	=> $strLabel
			) printf( "
				<label>
					<input type=\"radio\" name=\"jvfrm_spot_ts[topbar_use]\" value=\"{$strValue}\" clss=\"inner\"%s>
						{$strLabel}
				</label>",
				checked( $strValue == $jvfrm_spot_tso->get( 'topbar_use', 'enable' ), true, false )
			); ?>
		</fieldset>
		*/ ?>

		<h4><?php esc_html_e( "General", 'javospot');?></h4><hr>
		<fieldset>
			<dl>
				<dt><?php esc_html_e( "Header Menu Type", 'javospot');?></dt>
				<dd>
					<select name="jvfrm_spot_ts[hd][header_type]">
						<?php
						foreach( $jvfrm_spot_options['header_type'] as $label => $value )
						{
							printf( "<option value='{$value}' %s>{$label}</option>", selected( $value == jvfrm_spot_tso()->header->get( 'header_type' ), true, false ) );
						} ?>
					</select>
				</dd>
			</dl>

			<dl>
				<dt><?php esc_html_e("2 Rows Type Navigation Transparency",'javospot'); ?></dt>
				<dd>
					<input type="text" name="jvfrm_spot_ts[hd][header_opacity_row_2]" value="<?php echo (float)jvfrm_spot_tso()->header->get("header_opacity_row_2", 0); ?>">
					<div class="description"><?php esc_html_e("Please enter numerical value from 0.0 to 1.0. Higer value put in, more transparent it will be. <br> Ex) 0.5=opaque", 'javospot');?></div>
				</dd>
			</dl>

			<dl>
				<dt><?php esc_html_e( "Header Menu Skin", 'javospot');?></dt>
				<dd>
					<select name="jvfrm_spot_ts[hd][header_skin]">
						<?php
						foreach( $jvfrm_spot_options['header_skin'] as $label => $value )
						{
							printf( "<option value='{$value}' %s>{$label}</option>", selected( $value == jvfrm_spot_tso()->header->get("header_skin"), true, false ) );
						} ?>
					</select>
					<div class="description"><?php esc_html_e("Depends on this option, logo changes to the color appropriate to the skin and if selected logo of skin option is not uploaded, theme's basic logo will be shown.", 'javospot');?></div>
				</dd>
			</dl>
			<dl>
				<dt><?php esc_html_e( "Initial Header Background Color", 'javospot');?></dt>
				<dd><input type="text" name="jvfrm_spot_ts[hd][header_bg]" value="<?php echo esc_attr( jvfrm_spot_tso()->header->get("header_bg", "#ffffff"));?>" class="wp_color_picker" data-default-color="#ffffff"></dd>
			</dl>
			<dl>
				<dt><?php esc_html_e( "Initial Header Transparency", 'javospot');?></dt>
				<dd>
					<input type="text" name="jvfrm_spot_ts[hd][header_opacity]" value="<?php echo (float)jvfrm_spot_tso()->header->get("header_opacity", 0); ?>">
					<div class="description"><?php esc_html_e("Please enter numerical value from 0.0 to 1.0. Higer value put in, more transparent it will be. <br> Ex) 0.5=opaque", 'javospot');?></div>
				</dd>
			</dl>


			<dl>
				<dt><?php esc_html_e( "Dropdown Menu - Background Color", 'javospot');?></dt>
				<dd><input type="text" name="jvfrm_spot_ts[hd][header_dropdown_bg]" value="<?php echo esc_attr( jvfrm_spot_tso()->header->get("header_dropdown_bg", "#262626"));?>" class="wp_color_picker" data-default-color="#262626"></dd>
			</dl>
			<dl>
			<dl>
				<dt><?php esc_html_e( "Dropdown Menu - Background Hover Color", 'javospot');?></dt>
				<dd><input type="text" name="jvfrm_spot_ts[hd][header_dropdown_hover_bg]" value="<?php echo esc_attr( jvfrm_spot_tso()->header->get("header_dropdown_hover_bg", "#333333"));?>" class="wp_color_picker" data-default-color="#333333"></dd>
			</dl>
			<dl>
			<dl>
				<dt><?php esc_html_e( "Dropdown Menu - Text Color", 'javospot');?></dt>
				<dd><input type="text" name="jvfrm_spot_ts[hd][header_dropdown_text]" value="<?php echo esc_attr( jvfrm_spot_tso()->header->get("header_dropdown_text", "#eeeeee"));?>" class="wp_color_picker" data-default-color="#eeeeee"></dd>
			</dl>
			<dl>

			<dl>
				<dt><?php esc_html_e( "Header Size", 'javospot');?></dt>
				<dd>
					<input type="text" name="jvfrm_spot_ts[hd][header_size]" value="<?php echo intVal ( jvfrm_spot_tso()->header->get( 'header_size' , 50 ) ); ?>">
					<?php esc_html_e( "Pixcel", 'javospot' ); ?>
				</dd>
			</dl>
			<dl>
				<dt><?php esc_html_e( "Navi Shadow", 'javospot');?></dt>
				<dd>
					<select name="jvfrm_spot_ts[hd][header_shadow]">
						<?php
						foreach( $jvfrm_spot_options['able_disable'] as $label => $value )
						{
							printf( "<option value='{$value}' %s>{$label}</option>", selected( $value == jvfrm_spot_tso()->header->get("header_shadow"), true, false ) );
						} ?>
					</select>
				</dd>
			</dl>
			<dl>
				<dt><?php esc_html_e( "Navi Position", 'javospot');?></dt>
				<dd>
					<select name="jvfrm_spot_ts[hd][header_fullwidth]">
						<?php
						foreach( $jvfrm_spot_options['header_fullwidth'] as $label => $value )
						{
							printf( "<option value='{$value}' %s>{$label}</option>", selected( $value == jvfrm_spot_tso()->header->get("header_fullwidth"), true, false ) );
						} ?>
					</select>
				</dd>
			</dl>
			<dl>
				<dt><?php esc_html_e( "Navi Type", 'javospot');?></dt>
				<dd>
					<select name="jvfrm_spot_ts[hd][header_relation]">
						<?php
						foreach( $jvfrm_spot_options['header_relation'] as $label => $value )
						{
							printf( "<option value='{$value}' %s>{$label}</option>", selected( $value == jvfrm_spot_tso()->header->get("header_relation"), true, false ) );
						} ?>
					</select>
					<div class="description"><?php esc_html_e("Caution: If you choose transparent menu type, page's main text contents ascends as much as menu's height to make menu transparent.", 'javospot');?></div>
				</dd>
			</dl>
		</fieldset>
		<h4><?php esc_html_e("Sticky Menu", 'javospot'); ?></h4><hr>
		<fieldset>
			<dl>
				<dt><?php esc_html_e( "Sticky Navi on / off", 'javospot');?></dt>
				<dd>
					<select name="jvfrm_spot_ts[hd][header_sticky]">
						<?php
						foreach( $jvfrm_spot_options['able_disable'] as $label => $value )
						{
							printf( "<option value='{$value}' %s>{$label}</option>", selected( $value == jvfrm_spot_tso()->header->get("header_sticky"), true, false ) );
						} ?>
					</select>
				</dd>
			</dl>
			<dl>
				<dt><?php esc_html_e( "Initial Sticky Header Background Color", 'javospot');?></dt>
				<dd>
					<input type="text" name="jvfrm_spot_ts[hd][sticky_header_bg]" value="<?php echo esc_attr( jvfrm_spot_tso()->header->get("sticky_header_bg", "#ffffff"));?>" class="wp_color_picker" data-default-color="#ffffff">
				</dd>
			</dl>
			<dl>
				<dt><?php esc_html_e( "Initial Sticky Header Transparency", 'javospot');?></dt>
				<dd>
					<input type="text" name="jvfrm_spot_ts[hd][sticky_header_opacity]" value="<?php echo esc_attr( jvfrm_spot_tso()->header->get("sticky_header_opacity", 0));?>">
					<div class="description"><?php esc_html_e("Please enter numerical value from 0.0 to 1.0. Higer value put in, more transparent it will be. <br> Ex) 0.5=opaque", 'javospot');?></div>
				</dd>
			</dl>
			<dl>
				<dt><?php esc_html_e( "Sticky Menu Skin", 'javospot');?></dt>
				<dd>
					<select name="jvfrm_spot_ts[hd][sticky_header_skin]">
						<?php
						foreach( $jvfrm_spot_options['header_skin'] as $label => $value )
						{
							printf( "<option value='{$value}' %s>{$label}</option>", selected( $value == jvfrm_spot_tso()->header->get("sticky_header_skin"), true, false ) );
						} ?>
					</select>
					<div class="description"><?php esc_html_e("Depends on this option, logo changes to the color appropriate to the skin and if selected logo of skin option is not uploaded, theme's basic logo will be shown.", 'javospot');?></div>
				</dd>
			</dl>

			<dl>
				<dt><?php esc_html_e( "Sticky Menu Shadow", 'javospot');?></dt>
				<dd>
					<select name="jvfrm_spot_ts[hd][sticky_menu_shadow]">
						<?php
						foreach( $jvfrm_spot_options['able_disable'] as $label => $value )
						{
							printf( "<option value='{$value}' %s>{$label}</option>", selected( $value == jvfrm_spot_tso()->header->get("sticky_menu_shadow"), true, false ) );
						} ?>
					</select>
				</dd>
			</dl>
		</fieldset>

		<h4><?php esc_html_e("Navi on mobile setting", 'javospot'); ?></h4><hr>
		<fieldset>
			<dl>
				<dt><?php esc_html_e( "Initial Mobile Header Background Color", 'javospot');?></dt>
				<dd>
					<input type="text" name="jvfrm_spot_ts[hd][mobile_header_bg]" value="<?php echo esc_attr( jvfrm_spot_tso()->header->get("mobile_header_bg", "#ffffff"));?>" class="wp_color_picker" data-default-color="#ffffff">
				</dd>
			</dl>
			<dl>
				<dt><?php esc_html_e( "Initial Mobile Header Transparency", 'javospot');?></dt>
				<dd>
					<input type="text" name="jvfrm_spot_ts[hd][mobile_header_opacity]" value="<?php echo esc_attr( jvfrm_spot_tso()->header->get("mobile_header_opacity", 0));?>">
					<div class="description"><?php esc_html_e("Please enter numerical value from 0.0 to 1.0. Higer value put in, more transparent it will be. <br> Ex) 0.5=opaque", 'javospot');?></div>
				</dd>
			</dl>
			<dl>
				<dt><?php esc_html_e( "Header Menu Skin", 'javospot');?></dt>
				<dd>
					<select name="jvfrm_spot_ts[hd][mobile_header_skin]">
						<?php
						foreach( $jvfrm_spot_options['header_skin'] as $label => $value )
						{
							printf( "<option value='{$value}' %s>{$label}</option>", selected( $value == jvfrm_spot_tso()->header->get("mobile_header_skin"), true, false ) );
						} ?>
					</select>
					<div class="description"><?php esc_html_e("Depends on this option, logo changes to the color appropriate to the skin and if selected logo of skin option is not uploaded, theme's basic logo will be shown.", 'javospot');?></div>
				</dd>
			</dl>

			<dl>
				<dt><?php esc_html_e( "Canvas Menu Button", 'javospot');?></dt>
				<dd>
					<label>
						<input
							type="radio"
							name="jvfrm_spot_ts[btn_header_right_menu_trigger]"
							value=""
							<?php checked($jvfrm_spot_tso->get('btn_header_right_menu_trigger') == '' );?>
						>
							<?php esc_html_e('Enable', 'javospot');?>
					</label>
					<label>
						<input
							type="radio"
							name="jvfrm_spot_ts[btn_header_right_menu_trigger]"
							value="x-hide"
							<?php checked($jvfrm_spot_tso->get('btn_header_right_menu_trigger') == 'x-hide' );?>
						>
							<?php esc_html_e('Hide', 'javospot');?>
					</label>
				</dd>
			</dl>
			<dl>
				<dt><?php esc_html_e( "Responsive Menu Button", 'javospot');?></dt>
				<dd>
					<label>
						<input
							type="radio"
							name="jvfrm_spot_ts[btn_header_top_level_trigger]"
							value=""
							<?php checked($jvfrm_spot_tso->get('btn_header_top_level_trigger') == '' );?>
						>
							<?php esc_html_e('Enable', 'javospot');?>
					</label>
					<label>
						<input
							type="radio"
							name="jvfrm_spot_ts[btn_header_top_level_trigger]"
							value="x-hide"
							<?php checked($jvfrm_spot_tso->get('btn_header_top_level_trigger') == 'x-hide' );?>
						>
							<?php esc_html_e('Hide', 'javospot');?>
					</label>
				</dd>
			</dl>
		</fieldset>

		<h4><?php esc_html_e("Responsive Menu ( with Mobile )", 'javospot'); ?></h4><hr>
		<fieldset>
			<dl>
				<dt><?php esc_html_e( "Default background", 'javospot');?></dt>
				<dd>
					<input type="text" name="jvfrm_spot_ts[hd][mobile_respon_menu_bg]" value="<?php echo esc_attr( jvfrm_spot_tso()->header->get("mobile_respon_menu_bg"));?>" class="wp_color_picker" data-default-color="#454545">
				</dd>
			</dl>
			<dl>
				<dt><?php esc_html_e( "Responsive Menu Skin", 'javospot');?></dt>
				<dd>
					<select name="jvfrm_spot_ts[hd][mobile_respon_menu_skin]">
						<?php
						foreach( $jvfrm_spot_options['header_skin'] as $label => $value )
						{
							printf( "<option value='{$value}' %s>{$label}</option>", selected( $value == jvfrm_spot_tso()->header->get("mobile_respon_menu_skin"), true, false ) );
						} ?>
					</select>
				</dd>
			</dl>

		</fieldset>

		<h4><?php esc_html_e( "Single Post Page Menu", 'javospot'); ?></h4><hr>
		<fieldset class="inner">
			<dl>
				<dt><?php esc_html_e( "Single Post Page Menu Type", 'javospot');?></dt>
				<dd>
					<select name="jvfrm_spot_ts[hd][single_post_page_header_type]">
						<?php
						foreach( $jvfrm_spot_options['header_type'] as $label => $value )
						{
							printf( "<option value='{$value}' %s>{$label}</option>", selected( $value == jvfrm_spot_tso()->header->get( 'single_post_page_header_type' ), true, false ) );
						} ?>
					</select>
				</dd>
			</dl>

			<dl>
				<dt><?php esc_html_e( "Navi Type", 'javospot');?></dt>
				<dd>
					<select name="jvfrm_spot_ts[hd][single_post_header_relation]">
						<?php
						foreach( $jvfrm_spot_options['header_relation'] as $label => $value )
						{
							printf( "<option value='{$value}' %s>{$label}</option>", selected( $value == jvfrm_spot_tso()->header->get("single_post_header_relation"), true, false ) );
						} ?>
					</select>
					<div class="description"><?php esc_html_e("Caution: If you choose transparent menu type, page's main text contents ascends as much as menu's height to make menu transparent.", 'javospot');?></div>
				</dd>
			</dl>

			<dl>
				<dt><?php esc_html_e( "Background Color", 'javospot');?></dt>
				<dd>
					<input name="jvfrm_spot_ts[hd][single_post_page_menu_bg_color]" type="text" value="<?php echo esc_attr( $jvfrm_spot_tso->get( 'single_post_page_menu_bg_color' ) );?>" class="wp_color_picker" data-default-color="#000000">
				</dd>
			</dl>

			<dl>
				<dt><?php esc_html_e( "Text Color", 'javospot');?></dt>
				<dd>
					<input name="jvfrm_spot_ts[hd][single_post_page_menu_text_color]" type="text" value="<?php echo esc_attr( $jvfrm_spot_tso->get( 'single_post_page_menu_text_color' ) );?>" class="wp_color_picker" data-default-color="#ffffff">
				</dd>
			</dl>

			<dl>
				<dt><?php esc_html_e( "Initial Header Transparency", 'javospot');?></dt>
				<dd>
					<input type="text" name="jvfrm_spot_ts[hd][single_post_header_opacity]" value="<?php echo esc_attr( jvfrm_spot_tso()->header->get("single_post_header_opacity", 0 ) );?>">
					<div class="description"><?php esc_html_e("Please enter numerical value from 0.0 to 1.0. Higer value put in, more transparent it will be. <br> Ex) 0.5=opaque", 'javospot');?></div>
				</dd>
			</dl>
		</fieldset>

		<h4><?php esc_html_e( "Single Item Page Menu", 'javospot'); ?></h4><hr>
		<fieldset class="inner">

			<dl>
				<dt><?php esc_html_e( "Navi Type", 'javospot');?></dt>
				<dd>
					<select name="jvfrm_spot_ts[hd][single_header_relation]">
						<?php
						foreach( $jvfrm_spot_options['header_relation'] as $label => $value )
						{
							printf( "<option value='{$value}' %s>{$label}</option>", selected( $value == jvfrm_spot_tso()->header->get("single_header_relation"), true, false ) );
						} ?>
					</select>
					<div class="description"><?php esc_html_e("Caution: If you choose transparent menu type, page's main text contents ascends as much as menu's height to make menu transparent.", 'javospot');?></div>
				</dd>
			</dl>

			<dl>
				<dt><?php esc_html_e( "Background Color", 'javospot');?></dt>
				<dd>
					<input name="jvfrm_spot_ts[single_page_menu_bg_color]" type="text" value="<?php echo esc_attr( $jvfrm_spot_tso->get( 'single_page_menu_bg_color' ) );?>" class="wp_color_picker" data-default-color="#000000">
				</dd>
			</dl>

			<dl>
				<dt><?php esc_html_e( "Text Color", 'javospot');?></dt>
				<dd>
					<input name="jvfrm_spot_ts[single_page_menu_text_color]" type="text" value="<?php echo esc_attr( $jvfrm_spot_tso->get( 'single_page_menu_text_color' ) );?>" class="wp_color_picker" data-default-color="#ffffff">
				</dd>
			</dl>

			<dl>
				<dt><?php esc_html_e( "Initial Header Transparency", 'javospot');?></dt>
				<dd>
					<input type="text" name="jvfrm_spot_ts[hd][single_header_opacity]" value="<?php echo esc_attr( jvfrm_spot_tso()->header->get("single_header_opacity", 0 ) );?>">
					<div class="description"><?php esc_html_e("Please enter numerical value from 0.0 to 1.0. Higer value put in, more transparent it will be. <br> Ex) 0.5=opaque", 'javospot');?></div>
				</dd>
			</dl>
		</fieldset>

		<h4><?php esc_html_e("Navi Space Setting", 'javospot'); ?></h4><hr>
		<fieldset>
			<dl>
				<dt><?php esc_html_e( "Navi Height", 'javospot');?></dt>
				<dd>
					<input type="text" name="jvfrm_spot_ts[hd][jvfrm_spot_header_height]" value="<?php echo esc_attr( jvfrm_spot_tso()->header->get("jvfrm_spot_header_height") );?>">px
				</dd>
			</dl>

			<dl>
				<dt><?php esc_html_e( "Navi Padding Left", 'javospot');?></dt>
				<dd>
					<input type="text" name="jvfrm_spot_ts[hd][jvfrm_spot_header_padding_left]" value="<?php echo esc_attr( jvfrm_spot_tso()->header->get("jvfrm_spot_header_padding_left") );?>">px
				</dd>
			</dl>

			<dl>
				<dt><?php esc_html_e( "Navi Padding Right", 'javospot');?></dt>
				<dd>
					<input type="text" name="jvfrm_spot_ts[hd][jvfrm_spot_header_padding_right]" value="<?php echo esc_attr( jvfrm_spot_tso()->header->get("jvfrm_spot_header_padding_right") );?>">px
				</dd>
			</dl>

			<dl>
				<dt><?php esc_html_e( "Navi Padding Top", 'javospot');?></dt>
				<dd>
					<input type="text" name="jvfrm_spot_ts[hd][jvfrm_spot_header_padding_top]" value="<?php echo esc_attr( jvfrm_spot_tso()->header->get("jvfrm_spot_header_padding_top") );?>">px
				</dd>
			</dl>

			<dl>
				<dt><?php esc_html_e( "Navi Padding Bottom", 'javospot');?></dt>
				<dd>
					<input type="text" name="jvfrm_spot_ts[hd][jvfrm_spot_header_padding_bottom]" value="<?php echo esc_attr( jvfrm_spot_tso()->header->get("jvfrm_spot_header_padding_bottom") );?>">px
				</dd>
			</dl>
		</fieldset>
	</td></tr>
	</table>
</div>