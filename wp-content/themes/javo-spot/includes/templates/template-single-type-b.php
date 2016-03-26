<div class="container wrapper">
	<div class="row">
		<div id="javo-single-content" class="col-md-9 col-xs-12 item-single">
			<div class="row" id="javo-detail-item-content">
				<div class="col-md-12 col-xs-12 item-summary">
					<div class="row">
						<div class="col-md-12">
							<h3 class="page-header admin-color-setting margin-top-zero"><?php esc_html_e( "Detail Photos", 'javospot' ); ?></h3>
							<?php
							lava_directory_attach(
								Array(
									'type'					=> 'ul'
									, 'title'					=> ''
									, 'size'					=> 'jvfrm-spot-item-detail'
									, 'container_class'	=> 'lava-detail-images flexslider hidden'
									, 'wrap_class'		=> 'slides'
									, 'featured_image'	=> true
								)
							); ?>
						</div><!-- /.col-md-12 -->
					</div><!-- /.row -->
				</div><!-- /.col-md-12.item-summary -->
				<?php get_template_part( 'includes/templates/html', 'single-detail-options' ); ?>
				<div class="col-md-12 col-xs-12 item-amenities" id="javo-item-amenities-section" data-jv-detail-nav>
					<h3 class="page-header admin-color-setting"><?php esc_html_e( "Amenities", 'javospot' ); ?></h3>
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="expandable-content" ><?php lava_directory_amenities(); ?></div>
						</div><!-- panel-body -->
					</div>
				</div><!-- /#javo-item-amenities-section -->
				<?php do_action( 'jvfrm_spot_' . get_post_type() . '_single_description_before' ); ?>
				<div class="col-md-12 col-xs-12 item-description" id="javo-item-describe-section" data-jv-detail-nav>
					<h3 class="page-header admin-color-setting"><?php esc_html_e( "Detail", 'javospot' ); ?></h3>
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="expandable-content-wrap" tabindex="0">
								<?php the_content(); ?>
								<div class="expandable-content-overlay">
									<div id="javo-item-description-read-more">
										<i class="fa fa-plus"></i>
										<?php esc_html_e( "Read More", 'javospot' ); ?>
									</div>
								</div><!-- /.expandable-content-overlay -->
							</div><!-- /.expandable-content-wrap -->
						</div><!--/.panel-body-->
					</div><!--/.panel-->
				</div><!-- /#javo-item-describe-section -->
				<?php if( function_exists( 'get_lava_directory_review' ) ): ?>
					<div class="col-md-12 col-xs-12 item-description" id="javo-item-review-section" data-jv-detail-nav>
						<h3 class="page-header admin-color-setting"><?php esc_html_e( "Review", 'javospot' ); ?></h3>
						<div class="panel panel-default">
							<div class="panel-body">
								<?php get_lava_directory_review(); ?>
							</div><!--/.panel-body-->
						</div><!--/.panel-->
					</div><!-- /#javo-item-describe-section -->
				<?php endif; ?>
				<div class="col-md-12 col-xs-12" id="javo-item-location-section" data-jv-detail-nav>
					<h3 class="page-header admin-color-setting"><?php esc_html_e( "Location", 'javospot' ); ?></h3>
					<div class="jv-single-map-wapper">
						<div><?php jvfrm_spot_single_map_switcher(); ?></div>
						<div id="lava-single-map-area" class=""></div>
						<div id="lava-single-streetview-area" class="hidden"></div>
					</div><!-- /.jv-single-map-wapper -->
				</div><!-- /#javo-item-location-section -->
				<div class="col-md-12 col-xs-12" id="javo-item-author-section">
					<div class="row">
						<div class="col-md-12 col-xs-12">
							<h3 class="page-header admin-color-setting"><?php esc_html_e( "Author", 'javospot' ); ?></h3> <span class="author_allpost_link"><a href="<?php echo jvfrm_spot_getUserPage( get_the_author_meta( 'ID' ) ); ?>" class="admin-color-setting"><?php esc_html_e( "ALL POSTS", 'javospot' ); ?></a></span>
							<div class="item-summary-inner ">
								<div class="row">
									<div class="item-summary-author col-md-2 col-xs-2">
										<a href="<?php echo jvfrm_spot_getUserPage( get_the_author_meta( 'ID' ) ); ?>">
											<?php echo get_avatar( get_the_author_meta( 'ID' ) ); ?>
										</a>
									</div>
									<div class="item-author-description col-md-10 col-xs-10">
										<div class="row">
											<div class="col-md-8 col-xs-6">
												<div class="javo-summary-author-name"><?php the_author_meta( "display_name" ); ?></div>
											</div> <!--/ .col-md-8 col-xs-6 -->
											<div class="col-md-4 col-xs-6">
											</div> <!--/ .col-md-4 col-xs-6 -->
											<div class="col-md-12 col-xs-12">
												<?php the_author_meta( "description" ); ?>
											</div> <!--/ .col-md-12 col-xs-12 -->
										</div> <!--/ .row -->
									</div>
								</div><!--/.row-->
							</div><!--/.item-summary-inner-->
						</div><!-- /.col-md-12.col-xs-12 -->
					</div><!-- /.row -->
				</div><!-- /#javo-item-author-section -->
			</div><!-- /#javo-detail-item-content -->
		</div> <!-- /#javo-single-content -->
		<div id="javo-single-sidebar" class="col-md-3 sidebar-right">
			<?php lava_directory_get_widget(); ?>
		</div><!-- /.col-md-3 -->
	</div><!--/.row-->