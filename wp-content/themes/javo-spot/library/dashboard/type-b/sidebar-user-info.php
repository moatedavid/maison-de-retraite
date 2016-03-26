<?php
/**
*** User Information
***/
global
	$jvfrm_spot_tso
	, $jvfrm_spot_curUser
	, $jvfrm_spot_current_user;
?>
<div class="profile-and-image-container" style="position:relative; z-index:1;">
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab"><?php esc_html_e('Summary','javospot');?></a></li>
		<li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab"><?php esc_html_e('About','javospot'); ?></a></li>
		<li role="presentation"><a href="#sendpm" aria-controls="sendpm" role="tab" data-toggle="tab"><?php esc_html_e('Send PM','javospot'); ?></a></li>
		<li class="jv-mypage-topmenu-button"><a href="<?php echo jvfrm_spot_getCurrentUserPage( JVFRM_SPOT_ADDITEM_SLUG );?>" class="btn btn-danger pull-right admin-color-setting"><?php esc_html_e( "Submit Item", 'javospot' );?></a></li>
		<li class="jv-mypage-topmenu-button"><a href="<?php echo jvfrm_spot_getCurrentUserPage( JVFRM_SPOT_PROFILE_SLUG );?>" class="btn btn-danger pull-right admin-color-setting"><?php esc_html_e( "Edit Profile", 'javospot' );?></a></li>
		<li class="jv-mypage-topmenu-button"><a href="<?php echo esc_url( home_url( JVFRM_SPOT_DEF_LANG.JVFRM_SPOT_MEMBER_SLUG . '/' . wp_get_current_user()->user_login . '/' ) . JVFRM_SPOT_MEMBER_SLUG );?>" class="btn btn-danger pull-right admin-color-setting"><?php esc_html_e( "My Page", 'javospot' );?></a></li>
	 </ul>
	<div class="col-xs-12">
		<div class="col-md-3  col-xs-12 author-img">
			<div class="col-md-12 col-xs-12">
				<div class="img_wrap">
					<?php echo get_avatar( $jvfrm_spot_curUser->ID , 250 );?>
				</div>
			</div><!-- 12 Columns -->
		</div>
		<div class="col-sm-9 hidden-xs author-names">
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane active" id="home">
					<div class="col-sm-6 col-xs-6 my-profile-home-details">
						<ul class="list-group">
							<li class="list-group-item">
								<div class="col-md-2 col-sm-4 col-xs-4 my-home-label"><?php esc_html_e( "Name" ,'javospot' );?></div>
								<div class="col-md-10 col-sm-8 col-xs-8 my-home-content"></div>&nbsp;<?php printf('%s %s', $jvfrm_spot_curUser->first_name, $jvfrm_spot_curUser->last_name);?>
							</li> <!-- list-group-item -->

							<li class="list-group-item">
								<div class="col-md-2 col-sm-4 col-xs-4 my-home-label"><?php esc_html_e( "Email" ,'javospot' );?></div>
								<div class="col-md-10 col-sm-8 col-xs-8 my-home-content"></div>&nbsp;<?php echo esc_attr( $jvfrm_spot_curUser->user_email );?>
							</li> <!-- list-group-item -->

							<li class="list-group-item">
								<div class="col-md-2 col-sm-4 col-xs-4 my-home-label"><?php esc_html_e( "Phone" ,'javospot' );?></div>
								<div class="col-md-10 col-sm-8 col-xs-8 my-home-content"></div>&nbsp;<?php echo esc_attr( $jvfrm_spot_curUser->phone );?>
							</li> <!-- list-group-item -->

							<li class="list-group-item">
								<div class="col-md-2 col-sm-4 col-xs-4 my-home-label"><?php esc_html_e( "Mobile" ,'javospot' );?></div>
								<div class="col-md-10 col-sm-8 col-xs-8 my-home-content"></div>&nbsp;<?php echo esc_attr( $jvfrm_spot_curUser->mobile );?>
							</li> <!-- list-group-item -->

							<li class="list-group-item">
								<div class="col-md-2 col-sm-4 col-xs-4 my-home-label"><?php esc_html_e( "Fax" ,'javospot' );?></div>
								<div class="col-md-10 col-sm-8 col-xs-8 my-home-content"></div>&nbsp;<?php echo esc_attr( $jvfrm_spot_curUser->fax );?>
							</li> <!-- list-group-item -->

							<li class="list-group-item">
								<div class="col-md-2 col-sm-4 col-xs-4 my-home-label"><?php esc_html_e( "Twitter" ,'javospot' );?></div>
								<div class="col-md-10 col-sm-8 col-xs-8 my-home-content"></div>&nbsp;<?php echo esc_attr( $jvfrm_spot_curUser->twitter );?>
							</li> <!-- list-group-item -->

							<li class="list-group-item">
								<div class="col-md-2 col-sm-4 col-xs-4 my-home-label"><?php esc_html_e( "Facebook" ,'javospot' );?></div>
								<div class="col-md-10 col-sm-8 col-xs-8 my-home-content"></div>&nbsp;<?php echo esc_attr( $jvfrm_spot_curUser->facebook );?>
							</li> <!-- list-group-item -->
						</ul>
					</div> <!-- col-md-12 -->
				</div><!-- #home -->
				<div role="tabpanel" class="tab-pane" id="profile">
					<div class="row">
						<div class="col-md-12">
							<div class="tooltip-content">
								 <div class="tab-content">
									<p><?php echo $jvfrm_spot_current_user->description; ?></p>
								</div>
							</div><!-- tooltip-content -->
						</div><!-- col-md-12 -->
					</div><!-- row -->
				</div>
				<div role="tabpanel" class="tab-pane" id="sendpm">


				<?php
				$pm_form = '';
				$pm_form_id = $jvfrm_spot_tso->get('pm_contact_form_id');
				$pm_form_type = $jvfrm_spot_tso->get('pm_contact_type');
				if($pm_form_type != ''){
					if($pm_form_type == 'contactform'){ // pm type = contact form
						$pm_form = '[contact-form-7 id=%s title="%s"]';
					}else{ // pm type = ninja form
						$pm_form = '[ninja_forms id=%s title="%s"]';
					}
					echo do_shortcode(sprintf($pm_form, $pm_form_id, 'mikes form'));
				}else{
					echo 'not setup pm form';
				}
				?>







				</div>
			</div>
		</div><!-- col-md-6 hidden-xs author-names -->
		<div class="col-md-4 jv-dashboard-menu">
			<?php //get_template_part('library/dashboard/' . jvfrm_spot_dashboard()->page_style . '/sidebar', 'menu'); ?>
		</div>
	</div> <!-- col-xs-12 col-sm-12 -->

</div> <!-- container -->