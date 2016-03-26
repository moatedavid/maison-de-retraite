<?php
/**
***	My Page Change Password
***/

$jvfrm_spot_change_message = jvfrm_spot_dashboard_msg();

require_once JVFRM_SPOT_DSB_DIR . '/mypage-common-header.php';
get_header(); ?>
<div class="jv-my-page jv-my-page-change-password">
	<div class="row top-row">
		<div class="col-md-12">
			<?php get_template_part('library/dashboard/' . jvfrm_spot_dashboard()->page_style . '/sidebar', 'user-info');?>
		</div> <!-- col-12 -->
	</div> <!-- top-row -->

	<div class="container secont-container-content">
		<div class="row row-offcanvas row-offcanvas-left">
			<?php get_template_part('library/dashboard/' . jvfrm_spot_dashboard()->page_style . '/sidebar', 'menu'); ?>
			<div class="col-xs-12 col-sm-10 main-content-right" id="main-content">
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default panel-wrap lava-change-password">
							<div class="panel-heading">

								<div class="row">
									<div class="col-md-12 my-page-title">
										<?php esc_html_e('Change Password', 'javospot');?>
									</div> <!-- my-page-title -->


								</div> <!-- row -->
							</div> <!-- panel-heading -->

							<div class="panel-body">
							<!-- Starting Content -->

								<?php
								if( !empty( $jvfrm_spot_change_message ) ) :
									$change_status	= $jvfrm_spot_change_message[0] === 'e' ? 'alert-warning' : 'alert-success';
								?>
								<div class="alert <?php echo sanitize_html_class( $change_status ); ?>">
									<?php echo $jvfrm_spot_change_message[1]; ?>
								</div>
								<?php endif; ?>

								<div class="row">
									<div class="col-md-12">
										<form method="post" name="javo-dashboard-change-pw">
											<div class="row">
												<div class="col-md-8 col-md-offset-2">
													<div class="row">
														<div class="col-md-4 ">
															<h3 class='tab-inner-titles'>
																<?php esc_html_e( "Current Password", 'javospot');?>&nbsp;
															</h3>

														</div>
														<div class="col-md-8">
															<div class="form-group right-inner-addon">
																<input type="password" name="current_pass" id="user_login" class="input form-control" value="" size="20" placeholder="<?php esc_attr_e('Please enter your current password', 'javospot');?>">
															</div> <!-- form-group -->
														</div>
														<div class="col-md-12">
															<small><?php esc_html_e("* To reset your password, provide your current password.", 'javospot' );?></small>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-8 col-md-offset-2">
													<div class="row">
														<div class="col-md-4">
															<h3 class='tab-inner-titles'>
																<?php esc_html_e( "Change Password", 'javospot');?>&nbsp;
															</h3>
														</div>
														<div class="col-md-8">
															<div class="form-group right-inner-addon">
																<input type="password" name="new_pass" id="user_login" class="input form-control" value="" size="20" placeholder="<?php esc_attr_e('Please enter new password', 'javospot');?>">
															</div> <!-- form-group -->
														</div>
													</div><!--/.row-->
													<div class="row">
														<div class="col-md-4">
															<h3 class='tab-inner-titles'>
																<?php esc_html_e( "confirm Password", 'javospot');?>&nbsp;
															</h3>
														</div>
														<div class="col-md-8">
															<div class="form-group right-inner-addon">
																<input type="password" name="new_pass_confirm" id="user_login" class="input form-control" value="" size="20" placeholder="<?php esc_attr_e('Please enter new password again', 'javospot');?>">
															</div> <!-- form-group -->
														</div>
															<div class="col-md-12">
															<small><?php esc_html_e('* Please enter your new password twice.', 'javospot'); ?></small>
														</div>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="col-md-8 col-md-offset-2">
													<button type="submit">
														<?php esc_html_e( "Change password", 'javospot' );?>
													</button>
												</div>
											</div>
											<?php wp_nonce_field( 'security', 'jvfrm_spot_dashboard_changepw_nonce' ); ?>
										</form>
									</div><!-- col-md-offset-1 -->
								</div><!-- Row -->


							<!-- End Content -->
							</div> <!-- panel-body -->
						</div> <!-- panel -->
					</div> <!-- col-md-12 -->
				</div><!--/row-->
			</div><!-- wrap-right -->
		</div><!--/row-->
	</div><!--/.container-->
</div><!--jv-my-page-->
<?php get_footer();