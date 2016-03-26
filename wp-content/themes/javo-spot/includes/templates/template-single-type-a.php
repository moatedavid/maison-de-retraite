<div class="container">
	<div class="row">
		<div id="javo-single-content" class="col-md-9 col-xs-12 item-single">
			<div class="row" id="javo-detail-item-content">
				<div id="javo-item-photos-section">
                                    <?php if( jvfrm_spot_has_attach() ) : ?>
                                            <div class="col-md-12 col-xs-12 item-summary">
                                                    <?php get_template_part( 'includes/templates/html', 'single-grid-images' ); ?>
                                            </div><!-- /.col-md-12.item-summary -->
                                    <?php endif; ?>
				</div>


				<?php do_action( 'jvfrm_spot_' . get_post_type() . '_single_description_before' ); ?>

				<div class="col-md-12 col-xs-12 item-description" id="javo-item-describe-section" data-jv-detail-nav>

					<h3 class="page-header"><?php esc_html_e( "Description", 'javospot' ); ?></h3>
					<div class="panel panel-default">
						<div class="panel-body">

							<!-- Post Content Container -->
							<div class="jv-custom-post-content">
								<div class="jv-custom-post-content-inner">
									<?php the_content(); ?>
								</div><!-- /.jv-custom-post-content-inner -->
								<div class="jv-custom-post-content-trigger">
									<i class="fa fa-plus"></i>
									<?php esc_html_e( "Read More", 'javospot' ); ?>

								</div><!-- /.jv-custom-post-content-trigger -->
							</div><!-- /.jv-custom-post-content -->

						</div><!--/.panel-body-->
					</div><!--/.panel-->
				</div><!-- /#javo-item-describe-section -->

				<?php do_action( 'jvfrm_spot_' . get_post_type() . '_single_description_after' ); ?>				

				<?php lava_directory_amenities(
					get_the_ID(),
					Array(
						'container_before' => sprintf( '
						<div class="col-md-12 col-xs-12 item-amenities" id="javo-item-amenities-section" data-jv-detail-nav>
							<h3 class="page-header">%1$s</h3>
							<div class="panel panel-default">
								<div class="panel-body">
									<div class="expandable-content" >',
									esc_html__( "Amenities", 'javospot' )
						),
						'container_after'  => '
									</div>
								</div><!-- panel-body -->
							</div>
						</div><!-- /#javo-item-amenities-section -->'
					)
				); ?>

				<?php if( function_exists( 'get_lava_directory_review' ) ): ?>
					<div class="col-md-12 col-xs-12 item-description" id="javo-item-review-section" data-jv-detail-nav>

						<h3 class="page-header"><?php esc_html_e( "Review", 'javospot' ); ?></h3>
						<div class="panel panel-default">
							<div class="panel-body">
								<?php get_lava_directory_review(); ?>
							</div><!--/.panel-body-->
						</div><!--/.panel-->
					</div><!-- /#javo-item-describe-section -->
				<?php endif; ?>
                                <?php get_template_part( 'includes/templates/html', 'single-detail-options' ); ?>
				<?php do_action( 'jvfrm_spot_' . get_post_type() . '_single_map_before' ); ?>

				<div class="col-md-12 col-xs-12" id="javo-item-location-section" data-jv-detail-nav>
					<h3 class="page-header"><?php esc_html_e( "Location", 'javospot' ); ?></h3>
					<div class="jv-single-map-wapper">
						<div><?php jvfrm_spot_single_map_switcher(); ?></div>
						<div id="lava-single-map-area" class=""></div>
						<div id="lava-single-streetview-area" class="hidden"></div>
					</div><!-- /.jv-single-map-wapper -->
				</div><!-- /#javo-item-location-section -->

				<?php do_action( 'jvfrm_spot_' . get_post_type() . '_single_map_after' ); ?>

			</div><!-- /#javo-detail-item-content -->

		</div> <!-- /#javo-single-content -->
		<div id="javo-single-sidebar" class="col-md-3 sidebar-right">
			<?php lava_directory_get_widget(); ?>
		</div><!-- /.col-md-3 -->
	</div><!--/.row-->
</div><!-- /.container -->