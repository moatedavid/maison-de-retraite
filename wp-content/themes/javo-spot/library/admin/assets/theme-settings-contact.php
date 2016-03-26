<div class="jvfrm_spot_ts_tab javo-opts-group-tab hidden" tar="contact">
	<h2> <?php esc_html_e('Contact Information Settings', 'javospot'); ?> </h2>
	<table class="form-table">
	<tr><th>
		<?php esc_html_e('Contact Information', 'javospot');?>
		<span class="description">
			<?php esc_html_e('Add Your Contact Information', 'javospot');?>
		</span>
	</th><td>
		<h4><?php esc_html_e('Address', 'javospot');?></h4>
		<fieldset class="inner">
			<input name="jvfrm_spot_ts[address]" type="text" value="<?php echo sanitize_text_field( $jvfrm_spot_tso->get("address") );?>" class="large-text">
		</fieldset>

		<h4><?php esc_html_e('Phone', 'javospot');?></h4>
		<fieldset class="inner">
			<input name="jvfrm_spot_ts[phone]" type="text" value="<?php echo sanitize_text_field( $jvfrm_spot_tso->get("phone") );?>" class="large-text">
		</fieldset>

		<h4><?php esc_html_e('Mobile', 'javospot');?></h4>
		<fieldset class="inner">
			<input name="jvfrm_spot_ts[mobile]" type="text" value="<?php echo sanitize_text_field( $jvfrm_spot_tso->get("mobile") );?>" class="large-text">
		</fieldset>

		<h4><?php esc_html_e('Fax', 'javospot');?></h4>
		<fieldset class="inner">
			<input name="jvfrm_spot_ts[fax]" type="text" value="<?php echo sanitize_text_field( $jvfrm_spot_tso->get("fax") );?>" class="large-text">
		</fieldset>

		<h4><?php esc_html_e('Email', 'javospot');?></h4>
		<fieldset class="inner">
			<input name="jvfrm_spot_ts[email]" type="text" value="<?php echo sanitize_text_field( $jvfrm_spot_tso->get("email") );?>" class="large-text">
		</fieldset>

		<h4><?php esc_html_e('Working Hours', 'javospot');?></h4>
		<fieldset class="inner">
			<input name="jvfrm_spot_ts[working_hours]" type="text" value="<?php echo sanitize_text_field( $jvfrm_spot_tso->get("working_hours") );?>" class="large-text">
		</fieldset>

		<h4><?php esc_html_e('Additional Information', 'javospot');?></h4>
		<fieldset class="inner">
			<input name="jvfrm_spot_ts[additional_info]" type="text" value="<?php echo sanitize_text_field( $jvfrm_spot_tso->get("additional_info") );?>" class="large-text">
		</fieldset>
	</td></tr><tr><th>
		<?php esc_html_e('Social Network Service IDs', 'javospot');?>
		<span class="description">
			<?php esc_html_e('Add your SSN information.', 'javospot');?>
		</span>
	</th><td>
		<h4><?php esc_html_e("Facebook  ex) https://facebook.com/your_name",'javospot'); ?></h4>
		<fieldset class="inner">
			<input name="jvfrm_spot_ts[facebook]" type="text" value="<?php echo sanitize_text_field( $jvfrm_spot_tso->get("facebook") );?>" class="large-text">
		</fieldset>

		<h4><?php esc_html_e("Twitter",'javospot'); ?></h4>
		<fieldset class="inner">
			<input name="jvfrm_spot_ts[twitter]" type="text" value="<?php echo sanitize_text_field( $jvfrm_spot_tso->get("twitter") );?>" class="large-text">
		</fieldset>

		<h4><?php esc_html_e("Google+",'javospot'); ?></h4>
		<fieldset class="inner">
			<input name="jvfrm_spot_ts[google]" type="text" value="<?php echo sanitize_text_field( $jvfrm_spot_tso->get("google" ) );?>" class="large-text">
		</fieldset>

		<h4><?php esc_html_e("Dribbble",'javospot'); ?></h4>
		<fieldset class="inner">
			<input name="jvfrm_spot_ts[dribbble]" type="text" value="<?php echo sanitize_text_field( $jvfrm_spot_tso->get("dribbble") );?>" class="large-text">
		</fieldset>

		<h4><?php esc_html_e("Forrst",'javospot'); ?></h4>
		<fieldset class="inner">
			<input name="jvfrm_spot_ts[forrst]" type="text" value="<?php echo sanitize_text_field( $jvfrm_spot_tso->get("forrst") );?>" class="large-text">
		</fieldset>

		<h4><?php esc_html_e("Pinterest",'javospot'); ?></h4>
		<fieldset class="inner">
			<input name="jvfrm_spot_ts[pinterest]" type="text" value="<?php echo sanitize_text_field( $jvfrm_spot_tso->get("pinterest") );?>" class="large-text">
		</fieldset>

		<h4><?php esc_html_e("Instagram",'javospot'); ?></h4>
		<fieldset class="inner">
			<input name="jvfrm_spot_ts[instagram]" type="text" value="<?php echo sanitize_text_field( $jvfrm_spot_tso->get("instagram") );?>" class="large-text">
		</fieldset>

		<h4><?php esc_html_e("Website",'javospot'); ?></h4>
		<fieldset class="inner">
			<input name="jvfrm_spot_ts[website]" type="text" value="<?php echo sanitize_text_field( $jvfrm_spot_tso->get("website") );?>" class="large-text">
		</fieldset>

	</td></tr>
	</table>
</div>