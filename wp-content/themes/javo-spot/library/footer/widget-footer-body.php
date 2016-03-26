<div class="footer-background-wrap">
	<footer class="footer-wrap">
		<div class="container footer-sidebar-wrap">
			<div class="row">
				<div class="col-md-4 jv-footer-column"><?php if( is_active_sidebar('sidebar-foot1') ) : ?><?php dynamic_sidebar("sidebar-foot1");?><?php endif; ?></div> <!-- col-md-3 -->
				<div class="col-md-4 jv-footer-column"><?php if( is_active_sidebar('sidebar-foot2') ) : ?><?php dynamic_sidebar("sidebar-foot2");?><?php endif; ?></div> <!-- col-md-3 -->
				<div class="col-md-4 jv-footer-column"><?php if( is_active_sidebar('sidebar-foot3') ) : ?><?php dynamic_sidebar("sidebar-foot3");?><?php endif; ?></div> <!-- col-md-3 -->
			</div> <!-- row -->
			<?php if($jvfrm_spot_tso->get('footer_info_use') == 'active'){ ?>

			<div class="jv-footer-separator"></div>

			<div class="row jv-footer-info">
				<div class="col-sm-3 jv-footer-info-logo-wrap">
					<div class="jv-footer-logo-text-title">
						<?php esc_html_e( "Contact", 'javospot' ); ?>
					</div>
					<div class="jv-footer-info-logo">
					<?php
					printf('<p class="contact_us_detail"><a href="%s"><img src="%s" data-at2x="%s" alt="javo-footer-info-logo"></a></p>'
						, get_site_url()
						, ($jvfrm_spot_tso->get( 'bottom_logo_url' ) != "" ?  $jvfrm_spot_tso->get('bottom_logo_url') : JVFRM_SPOT_IMG_DIR."/jv-logo3.png")
						, ($jvfrm_spot_tso->get( 'bottom_retina_logo_url' ) != "" ?  $jvfrm_spot_tso->get('bottom_retina_logo_url') : "")
					);
					?>
					</div>

					<?php if($jvfrm_spot_tso->get( 'email' )!=='') ?><div class="jv-footer-info-email"><i class="fa fa-envelope"></i> <a href="mailto:<?php echo  esc_attr($jvfrm_spot_tso->get( 'email' )); ?>"><?php echo esc_html($jvfrm_spot_tso->get( 'email' )); ?></a></div>
					<?php if($jvfrm_spot_tso->get("working_hours")!=='') ?><div class="jv-footer-info-working-hour"><i class="fa fa-clock-o"></i> <?php echo esc_html($jvfrm_spot_tso->get("working_hours")); ?></div>

					<?php if($jvfrm_spot_tso->get('footer_social_use') == 'active'){ ?>
					<div class="jv-footer-info-social-icon-wrap">
						<?php
							if($jvfrm_spot_tso->get( 'facebook' )!=='') printf("<div class='jv-footer-info-social'><a href='%s' target='_blank'><i class='fa fa-facebook'></i></a></div>", esc_html($jvfrm_spot_tso->get("facebook")) );
							if($jvfrm_spot_tso->get( 'twitter' )!=='') printf("<div class='jv-footer-info-social'><a href='%s' target='_blank'><i class='fa fa-twitter'></i></a></div>", esc_html($jvfrm_spot_tso->get("twitter")) );
							if($jvfrm_spot_tso->get( 'google' )!=='') printf("<div class='jv-footer-info-social'><a href='%s' target='_blank'><i class='fa fa-google-plus'></i></a></div>", esc_html($jvfrm_spot_tso->get("google")) );
							if($jvfrm_spot_tso->get( 'dribbble' )!=='') printf("<div class='jv-footer-info-social'><a href='%s' target='_blank'><i class='fa fa-dribbble'></i></a></div>", esc_html($jvfrm_spot_tso->get("dribbble")) );
							if($jvfrm_spot_tso->get( 'pinterest' )!=='') printf("<div class='jv-footer-info-social'><a href='%s' target='_blank'><i class='fa fa-pinterest'></i></a></div>", esc_html($jvfrm_spot_tso->get("pinterest")) );
							if($jvfrm_spot_tso->get( 'instagram' )!=='') printf("<div class='jv-footer-info-social'><a href='%s' target='_blank'><i class='fa fa-instagram'></i></a></div>", esc_html($jvfrm_spot_tso->get("instagram")) );
						} ?>
					</div>
				</div>

				<div class="col-sm-6 jv-footer-info-text-wrap">
					<div class="jv-footer-info-text-title">
						<?php echo $jvfrm_spot_tso->get('footer_info_text_title') !== '' ? esc_html($jvfrm_spot_tso->get('footer_info_text_title')) : ''; ?>
					</div>

					<div class="jv-footer-info-text">
						<?php echo $jvfrm_spot_tso->get('footer_text') !== '' ? esc_html($jvfrm_spot_tso->get('footer_text', '')) : ''; ?>
					</div>
				</div>

				<div class="col-sm-3 jv-footer-info-image-wrap">
					<div class="jv-footer-info-image-title">
						<?php echo $jvfrm_spot_tso->get('footer_info_image_title') !== '' ? esc_html($jvfrm_spot_tso->get('footer_info_image_title')) : ''; ?>
					</div>
					<div class="jv-footer-info-image">
						<img src="<?php echo esc_html($jvfrm_spot_tso->get('footer_info_image_url')) ; ?>" alt="javo-footer-info-image">
					</div>
				</div>

			</div><!-- row jv-footer-info -->
			<?php } ?>
			<div class="row footer-copyright">
				<div class="text-center">
					<?php echo stripslashes(esc_html($jvfrm_spot_tso->get('copyright', null)));?>
				</div>
			</div> <!-- footer-copyright -->
		</div> <!-- container-->
	</footer>
</div>