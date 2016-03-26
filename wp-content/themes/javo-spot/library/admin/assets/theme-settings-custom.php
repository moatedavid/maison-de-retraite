<div class="jvfrm_spot_ts_tab javo-opts-group-tab hidden" tar="custom">
	<h2> <?php esc_html_e("Javo Customization Settings", 'javospot'); ?> </h2>
	<table class="form-table">
	<tr><th>
		<?php esc_html_e( "CSS Stylesheet", 'javospot');?>
		<span class="description"><?php esc_html_e('Please Add Your Custom CSS Code Here.', 'javospot');?></span>
	</th><td>
		<h4><?php esc_html_e('Code:', 'javospot');?></h4>
		<?php esc_html_e( '<style type="text/css">', 'javospot' );?>
		<fieldset>
			<textarea name="jvfrm_spot_ts[custom_css]" class='large-text code' rows='15'><?php echo stripslashes( $jvfrm_spot_tso->get( 'custom_css', '' ) );?></textarea>
		</fieldset>
		<?php esc_html_e( '</style>', 'javospot' );?>
	</td></tr><tr><th>
		<?php esc_html_e('Custom Script', 'javospot');?>
		<span class="description">
			<?php esc_html_e(' If you have additional script, please add here.', 'javospot');?>
		</span>
	</th><td>
		<h4><?php esc_html_e('Code:', 'javospot');?></h4>
		<?php esc_html_e( '<script type="text/javascript">', 'javospot' );?>
		<fieldset>
			<textarea name="jvfrm_spot_ts[custom_js]" class="large-text code" rows="15"><?php echo stripslashes( $jvfrm_spot_tso->get('custom_js', ''));?></textarea>
		</fieldset>
		<?php esc_html_e( '</script>', 'javospot' );?>
		<div><?php esc_html_e('(Note : Please make sure that your scripts are NOT conflict with our own script or ajax core)', 'javospot');?></div>
	</td></tr>
	</table>
</div>