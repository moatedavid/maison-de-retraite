<?php
if( $jvfrm_spot_tso->get('scroll_rb_contact_us', '') == 'use'):?>
	<a class="btn btn-primary btn-lg javo-quick-contact-us javo-dark admin-color-setting">
		<span class="fa fa-envelope-o"></span>
	</a>
	<div class="javo-quick-contact-us-content">
		<?php
		$jvfrm_spot_quick_contact_shortcode = '';

		switch( $jvfrm_spot_tso->get( 'modal_contact_type' ) ) {
			case 'contactform'	: $jvfrm_spot_quick_contact_shortcode = '[contact-form-7 id=%s title="%s"]'; break;
			case 'ninjaform'	: $jvfrm_spot_quick_contact_shortcode = '[ninja_forms id=%s title="%s"]'; break;
		}

		if(
			(int) $jvfrm_spot_tso->get( 'modal_contact_form_id' , 0 ) > 0 &&
			false !== $jvfrm_spot_tso->get( 'modal_contact_type', false)
		){
			$jvfrm_spot_contact_form_shortcode = sprintf(
				$jvfrm_spot_quick_contact_shortcode
				, $jvfrm_spot_tso->get( 'modal_contact_form_id' )
				, esc_html__( 'Javo Contact Form', 'javospot' )
			);

			echo do_shortcode( $jvfrm_spot_contact_form_shortcode );
		}else{
			?>
			<div class="alert alert-light-gray">
				<strong><?php esc_html_e('Please create a form with contact 7 or Ninja form and add.', 'javospot');?></strong>
				<p><?php esc_html_e('Theme Settings > General > Contact Form Modal Settings', 'javospot');?></p>
			</div>
			<?php
		} ?>
	</div>
<?php
endif;

if( is_user_logged_in() )
	printf('<input type="hidden" class="javo-this-logged-in" value="use">');