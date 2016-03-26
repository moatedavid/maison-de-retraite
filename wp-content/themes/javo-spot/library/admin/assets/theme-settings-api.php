<div class="jvfrm_spot_ts_tab javo-opts-group-tab hidden" tar="api">
	<h2> <?php esc_html_e("APIs Settings", 'javospot'); ?> </h2>
	<table class="form-table">
	<tr><th>
		<?php esc_html_e('Google API', 'javospot');?>
		<span class="description">
			<?php esc_html_e('Paste your Google Analytic tracking codes here.', 'javospot');?>
		</span>
	</th><td>
		<h4><?php esc_html_e('Google Analystics Code', 'javospot');?></h4>
		<fieldset>
			<textarea name="jvfrm_spot_ts[analytics]" class="large-text code" rows="15"><?php echo stripslashes($jvfrm_spot_tso->get('analytics', ''));?></textarea>
		</fieldset>
	</td></tr><tr><th>
		<?php esc_html_e( 'MailChimp API', 'javospot');?>
	</th><td>
		<h4><?php esc_html_e('Mail-Chimp API Key', 'javospot');?></h4>
		<fieldset class="inner">
			<label>
				<?php esc_html_e('API KEY', 'javospot');?><br>
				<input type="text" name="jvfrm_spot_ts[mailchimp_api]" value="<?php echo esc_attr( $jvfrm_spot_tso->get( 'mailchimp_api' ) );?>">
			</label>
		</fieldset>
	</td></tr>
	</table>
</div>