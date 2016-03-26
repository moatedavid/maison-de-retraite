<?php require_once "fonts.php";?>
<div class="jvfrm_spot_ts_tab javo-opts-group-tab hidden" tar="font">
	<!-- Themes setting > Font -->
	<h2><?php esc_html_e("Fonts Settings", 'javospot');?></h2>
	<table class="form-table">
	<tr><th>
		<?php esc_html_e('Basic Font', 'javospot');?>
		<span class="description">
			<?php esc_html_e('Setup font size, tags', 'javospot');?>
		</span>
	</th><td>
		<h4><?php esc_html_e("Choose basic font-family",'javospot'); ?></h4>
		<fieldset>
			<select name="jvfrm_spot_ts[basic_font]">
				<?php
				ob_start();
				foreach($jvfrm_spot_font_names As $value=> $font){
					printf("<option value='%s'%s>%s</option>"
						, $value, ((  $jvfrm_spot_tso->get( 'basic_font' ) == $value)? " selected":""), $font
					);
				};
				ob_end_flush();?>
			</select>
		</fieldset>

		<h4><?php esc_html_e("Normal Font size",'javospot'); ?></h4>
		<fieldset>
			<div style="width:400px; display:inline-block; margin:0 15px 0 0;">
				<div class="jvfrm_spot_setting_slider" data-tar="#jvfrm_spot_ts_f_normal" data-val="<?php echo esc_attr( $jvfrm_spot_tso->get('basic_normal_size', 13) );?>"></div>
			</div>
			<input name="jvfrm_spot_ts[basic_normal_size]" id="jvfrm_spot_ts_f_normal" value="<?php echo esc_attr( $jvfrm_spot_tso->get('basic_normal_size', 13) );?>" type="text" size="2" readonly>
		</fieldset>

		<h4><?php esc_html_e("Line height",'javospot'); ?></h4>
		<fieldset>
			<div style="width:400px; display:inline-block; margin:0 15px 0 0;">
				<div class="jvfrm_spot_setting_slider" data-tar="#jvfrm_spot_ts_line_height" data-val="<?php echo esc_attr( $jvfrm_spot_tso->get('basic_line_height', 20) );?>"></div>
			</div>
			<input name="jvfrm_spot_ts[basic_line_height]" id="jvfrm_spot_ts_line_height" value="<?php echo esc_attr( $jvfrm_spot_tso->get('basic_line_height', 20) );?>" type="text" size="2" readonly>
		</fieldset>
	</td></tr><tr><th>
		<?php esc_html_e('H1', 'javospot');?>
		<span class="description">
			<?php esc_html_e('Setup font size, tags', 'javospot');?>
		</span>
	</th><td>
		<h4><?php esc_html_e("Choose H1 font-family",'javospot'); ?></h4>
		<fieldset>
			<select name="jvfrm_spot_ts[h1_font]">
				<?php
				ob_start();
				foreach($jvfrm_spot_font_names As $value=> $font){
					printf("<option value='%s'%s>%s</option>"
						, $value, (( $jvfrm_spot_tso->get( 'h1_font' ) == $value)? " selected":""), $font
					);
				};
				ob_end_flush();?>
			</select>
		</fieldset>

		<h4><?php esc_html_e("H1 Font size",'javospot'); ?></h4>
		<fieldset>
			<div style="width:400px; display:inline-block; margin:0 15px 0 0;">
				<div class="jvfrm_spot_setting_slider" data-tar="#jvfrm_spot_ts_f_h1" data-val="<?php echo esc_attr( $jvfrm_spot_tso->get('h1_normal_size', 18) );?>"></div>
			</div>
			<input name="jvfrm_spot_ts[h1_normal_size]" id="jvfrm_spot_ts_f_h1" value="<?php echo esc_attr( $jvfrm_spot_tso->get('h1_normal_size', 18) );?>" type="text" size="2" readonly>
		</fieldset>

		<h4><?php esc_html_e("Line height",'javospot'); ?></h4>
		<fieldset>
			<div style="width:400px; display:inline-block; margin:0 15px 0 0;">
				<div class="jvfrm_spot_setting_slider" data-tar="#jvfrm_spot_ts_line_height_h1" data-val="<?php echo esc_attr( $jvfrm_spot_tso->get('h1_line_height', 20) );?>"></div>
			</div>
			<input name="jvfrm_spot_ts[h1_line_height]" id="jvfrm_spot_ts_line_height_h1" value="<?php echo esc_attr( $jvfrm_spot_tso->get('h1_line_height', 20) );?>" type="text" size="2" readonly>
		</fieldset>
	</td></tr><tr><th>
	<?php esc_html_e('H2', 'javospot');?>
		<span class="description">
			<?php esc_html_e('Setup font size, tags', 'javospot');?>
		</span>
	</th><td>
		<h4><?php esc_html_e("Choose H2 font-family",'javospot'); ?></h4>
		<fieldset>
			<select name="jvfrm_spot_ts[h2_font]">
				<?php
				ob_start();
				foreach($jvfrm_spot_font_names As $value=> $font){
					printf("<option value='%s'%s>%s</option>"
						, $value, ((  $jvfrm_spot_tso->get( 'h2_font' ) == $value)? " selected":""), $font
					);
				};
				ob_end_flush();?>
			</select>
		</fieldset>

		<h4><?php esc_html_e("H2 Font size",'javospot'); ?></h4>
		<fieldset>
			<div style="width:400px; display:inline-block; margin:0 15px 0 0;">
				<div class="jvfrm_spot_setting_slider" data-tar="#jvfrm_spot_ts_f_h2" data-val="<?php echo esc_attr( $jvfrm_spot_tso->get('h2_normal_size', 16) );?>"></div>
			</div>
			<input name="jvfrm_spot_ts[h2_normal_size]" id="jvfrm_spot_ts_f_h2" value="<?php echo esc_attr( $jvfrm_spot_tso->get('h2_normal_size', 16) );?>" type="text" size="2" readonly>
		</fieldset>

		<h4><?php esc_html_e("Line height",'javospot'); ?></h4>
		<fieldset>
			<div style="width:400px; display:inline-block; margin:0 15px 0 0;">
				<div class="jvfrm_spot_setting_slider" data-tar="#jvfrm_spot_ts_line_height_h2" data-val="<?php echo esc_attr( $jvfrm_spot_tso->get('h2_line_height', 20) );?>"></div>
			</div>
			<input name="jvfrm_spot_ts[h2_line_height]" id="jvfrm_spot_ts_line_height_h2" value="<?php echo esc_attr( $jvfrm_spot_tso->get('h2_line_height', 20) );?>" type="text" size="2" readonly>
		</fieldset>
	</td></tr><tr><th>
	<?php esc_html_e('H3', 'javospot');?>
		<span class="description">
			<?php esc_html_e('Setup font size, tags', 'javospot');?>
		</span>
	</th><td>
		<h4><?php esc_html_e("Choose H3 font-family",'javospot'); ?></h4>
		<fieldset>
			<select name="jvfrm_spot_ts[h3_font]">
				<?php
				ob_start();
				foreach($jvfrm_spot_font_names As $value=> $font){
					printf("<option value='%s'%s>%s</option>"
						, $value, ((  $jvfrm_spot_tso->get( 'h3_font' )  == $value)? " selected":""), $font
					);
				};
				ob_end_flush();?>
			</select>
		</fieldset>

		<h4><?php esc_html_e("H3 Font size",'javospot'); ?></h4>
		<fieldset>
			<div style="width:400px; display:inline-block; margin:0 15px 0 0;">
				<div class="jvfrm_spot_setting_slider" data-tar="#jvfrm_spot_ts_f_h3" data-val="<?php echo esc_attr( $jvfrm_spot_tso->get('h3_normal_size', 14) );?>"></div>
			</div>
			<input name="jvfrm_spot_ts[h3_normal_size]" id="jvfrm_spot_ts_f_h3" value="<?php echo esc_attr( $jvfrm_spot_tso->get('h3_normal_size', 14) );?>" type="text" size="2" readonly>

		</fieldset>

		<h4><?php esc_html_e("Line height",'javospot'); ?></h4>
		<fieldset>
			<div style="width:400px; display:inline-block; margin:0 15px 0 0;">
				<div class="jvfrm_spot_setting_slider" data-tar="#jvfrm_spot_ts_line_height_h3" data-val="<?php echo esc_attr( $jvfrm_spot_tso->get('h3_line_height', 20) );?>"></div>
			</div>
			<input name="jvfrm_spot_ts[h3_line_height]" id="jvfrm_spot_ts_line_height_h3" value="<?php echo esc_attr( $jvfrm_spot_tso->get('h3_line_height', 20) );?>" type="text" size="2" readonly>
		</fieldset>
	</td></tr><tr><th>
		<?php esc_html_e('H4', 'javospot');?>
		<span class="description">
			<?php esc_html_e('Setup font size, tags', 'javospot');?>
		</span>
	</th><td>
		<h4><?php esc_html_e("Choose H4 font-family",'javospot'); ?></h4>
		<fieldset>
			<select name="jvfrm_spot_ts[h4_font]">
				<?php
				ob_start();
				foreach($jvfrm_spot_font_names As $value=> $font){
					printf("<option value='%s'%s>%s</option>"
						, $value, ( ( $jvfrm_spot_tso->get( 'h4_font' )  == $value)? " selected":""), $font
					);
				};
				ob_end_flush();?>
			</select>
		</fieldset>

		<h4><?php esc_html_e("H4 Font size",'javospot'); ?></h4>
		<fieldset>
			<div style="width:400px; display:inline-block; margin:0 15px 0 0;">
				<div class="jvfrm_spot_setting_slider" data-tar="#jvfrm_spot_ts_f_h4" data-val="<?php echo esc_attr( $jvfrm_spot_tso->get('h4_normal_size', 13) );?>"></div>
			</div>
			<input name="jvfrm_spot_ts[h4_normal_size]" id="jvfrm_spot_ts_f_h4" value="<?php echo esc_attr( $jvfrm_spot_tso->get('h4_normal_size', 13) );?>" type="text" size="2" readonly>

		</fieldset>

		<h4><?php esc_html_e("Line height",'javospot'); ?></h4>
		<fieldset>
			<div style="width:400px; display:inline-block; margin:0 15px 0 0;">
				<div class="jvfrm_spot_setting_slider" data-tar="#jvfrm_spot_ts_line_height_h4" data-val="<?php echo esc_attr( $jvfrm_spot_tso->get('h4_line_height', 20) );?>"></div>
			</div>
			<input name="jvfrm_spot_ts[h4_line_height]" id="jvfrm_spot_ts_line_height_h4" value="<?php echo esc_attr( $jvfrm_spot_tso->get('h4_line_height', 20) );?>" type="text" size="2" readonly>
		</fieldset>
	</td></tr><tr><th>
		<?php esc_html_e('H5', 'javospot');?>
		<span class="description">
			<?php esc_html_e('Setup font size, tags', 'javospot');?>
		</span>
	</th><td>
		<h4><?php esc_html_e("Choose H5 font-family",'javospot'); ?></h4>
		<fieldset>
			<select name="jvfrm_spot_ts[h5_font]">
				<?php
				ob_start();
				foreach($jvfrm_spot_font_names As $value=> $font){
					printf("<option value='%s'%s>%s</option>"
						, $value, ((  $jvfrm_spot_tso->get( 'h5_font' ) == $value)? " selected":""), $font
					);
				};
				ob_end_flush();?>
			</select>
		</fieldset>

		<h4><?php esc_html_e("H5 Font size",'javospot'); ?></h4>
		<fieldset>
			<div style="width:400px; display:inline-block; margin:0 15px 0 0;">
				<div class="jvfrm_spot_setting_slider" data-tar="#jvfrm_spot_ts_f_h5" data-val="<?php echo esc_attr( $jvfrm_spot_tso->get('h5_normal_size', 13) );?>"></div>
			</div>
			<input name="jvfrm_spot_ts[h5_normal_size]" id="jvfrm_spot_ts_f_h5" value="<?php echo esc_attr( $jvfrm_spot_tso->get('h5_normal_size', 13) );?>" type="text" size="2" readonly>

		</fieldset>

		<h4><?php esc_html_e("Line height",'javospot'); ?></h4>
		<fieldset>
			<div style="width:400px; display:inline-block; margin:0 15px 0 0;">
				<div class="jvfrm_spot_setting_slider" data-tar="#jvfrm_spot_ts_line_height_h5" data-val="<?php echo esc_attr( $jvfrm_spot_tso->get('h5_line_height', 20) );?>"></div>
			</div>
			<input name="jvfrm_spot_ts[h5_line_height]" id="jvfrm_spot_ts_line_height_h5" value="<?php echo esc_attr( $jvfrm_spot_tso->get('h5_line_height', 20) );?>" type="text" size="2" readonly>
		</fieldset>
	</td></tr><tr><th>
		<?php esc_html_e('H6', 'javospot');?>
		<span class="description">
			<?php esc_html_e('Setup font size, tags', 'javospot');?>
		</span>
	</th><td>
		<h4><?php esc_html_e("Choose H6 font-family",'javospot'); ?></h4>
		<fieldset>
			<select name="jvfrm_spot_ts[h6_font]">
				<?php
				ob_start();
				foreach($jvfrm_spot_font_names As $value=> $font){
					printf("<option value='%s'%s>%s</option>"
						, $value, ((  $jvfrm_spot_tso->get( 'h6_font' )  == $value)? " selected":""), $font
					);
				};
				ob_end_flush();?>
			</select>
		</fieldset>

		<h4><?php esc_html_e("H6 Font size",'javospot'); ?></h4>
		<fieldset>
			<div style="width:400px; display:inline-block; margin:0 15px 0 0;">
				<div class="jvfrm_spot_setting_slider" data-tar="#jvfrm_spot_ts_f_h6" data-val="<?php echo esc_attr( $jvfrm_spot_tso->get('h6_normal_size', 13) );?>"></div>
			</div>
			<input name="jvfrm_spot_ts[h6_normal_size]" id="jvfrm_spot_ts_f_h6" value="<?php echo esc_attr( $jvfrm_spot_tso->get('h6_normal_size', 13) );?>" type="text" size="2" readonly>

		</fieldset>

		<h4><?php esc_html_e("Line height",'javospot'); ?></h4>
		<fieldset>
			<div style="width:400px; display:inline-block; margin:0 15px 0 0;">
				<div class="jvfrm_spot_setting_slider" data-tar="#jvfrm_spot_ts_line_height_h6" data-val="<?php echo esc_attr( $jvfrm_spot_tso->get('h6_line_height', 20) );?>"></div>
			</div>
			<input name="jvfrm_spot_ts[h6_line_height]" id="jvfrm_spot_ts_line_height_h6" value="<?php echo esc_attr( $jvfrm_spot_tso->get('h6_line_height', 20) );?>" type="text" size="2" readonly>
		</fieldset>
	</td></tr>
	</table>
</div>