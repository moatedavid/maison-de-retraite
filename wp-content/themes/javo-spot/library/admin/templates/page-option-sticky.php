<table class="widefat">
	<tr>
		<td colspan="2">
			<h2><?php esc_html_e("Sticky Options", 'javospot');?></h2>
		</td>
	</tr>
	<tr>
		<td valign="middle"><p><?php esc_html_e("Sticky Navi on / off", 'javospot');?></p></td>
		<td valign="middle">
			<table class="javo-post-header-meta">
				<tr>
					<td width="50%" valign="middle">
						<select name="jvfrm_spot_hd[header_sticky]">
							<?php
							foreach( $jvfrm_spot_options['able_disable'] as $label => $value )
							{
								printf( "<option value='{$value}' %s>{$label}</option>", selected( $value == $jvfrm_spot_query->get("header_sticky"), true, false ) );
							} ?>
						</select>
					</td>
					<td width="50%" valign="middle">
						<small class="description"></small>
					</td>
				</tr>
			</table>

		</td>
	</tr>
	<tr>
		<td valign="middle"><p><?php esc_html_e("Sticky Header Menu Skin", 'javospot');?></p></td>
		<td valign="middle">
			<table class="javo-post-header-meta">
				<tr>
					<td width="50%" valign="middle">
						<select name="jvfrm_spot_hd[sticky_header_skin]">
							<?php
							foreach( $jvfrm_spot_options['header_skin'] as $label => $value )
							{
								printf( "<option value='{$value}' %s>{$label}</option>", selected( $value == $jvfrm_spot_query->get("sticky_header_skin"), true, false ) );
							} ?>
						</select>
					</td>
					<td width="50%" valign="middle">
						<small class="description">
							<?php esc_html_e("Depends on this option, logo changes to the color appropriate to the skin and if selected logo of skin option is not uploaded, theme's basic logo will be shown. ", 'javospot');?>
						</small>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr class="alternate">
		<td valign="middle"><p><?php esc_html_e("Initial Sticky Header Background Color", 'javospot');?></p></td>
		<td valign="middle">
			<table class="javo-post-header-meta">
				<tr>
					<td width="50%" valign="middle">
						<input type="text" name="jvfrm_spot_hd[sticky_header_bg]" value="<?php echo esc_attr( $jvfrm_spot_query->get("sticky_header_bg", null ));?>" class="wp_color_picker">
					</td>
					<td width="50%" valign="middle">
						<small class="description">
							<?php esc_html_e("If color value is not inserted, it will be replaced to color set from theme settings", 'javospot');?>
						</small>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td valign="middle"><p><?php esc_html_e("Initial Sticky Header Transparency", 'javospot');?></p></td>
		<td valign="middle">
			<table class="javo-post-header-meta">
				<tr>
					<td width="25%">
						<select name="jvfrm_spot_hd[sticky_header_opacity_as]" data-docking>
							<?php
							foreach( $jvfrm_spot_options['default_able'] as $label => $value )
							{
								printf(
									"<option value='{$value}' %s>{$label}</option>"
									, selected( $value == $jvfrm_spot_query->get("sticky_header_opacity_as"), true, false)
								);
							} ?>
						</select>
					</td>
					<td width="25%">
						<?php
						if( false === ( $jvfrm_spot_options['sticky_opacity'] = $jvfrm_spot_query->get("sticky_header_opacity") ) )
						{
							$jvfrm_spot_options['sticky_opacity'] = 1;
						} ?>
						<input type="text" name="jvfrm_spot_hd[sticky_header_opacity]" value="<?php echo (float)$jvfrm_spot_options['sticky_opacity'];?>">
					</td>
					<td width="50%" valign="middle">
						<small class="description">
							<?php esc_html_e("Please enter numerical value from 0.0 to 1.0. Higer value put in, more transparent it will be. <br> Ex) 0.5=opaque", 'javospot');?>
						</small>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>