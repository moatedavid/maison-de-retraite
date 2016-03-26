<?php
/**
***	My Review Lists
***/

global
	$jvfrm_spot_curUser
	, $manage_mypage;

$jvfrm_spot_dashboard_tabs	= apply_filters( 'jvfrm_spot_dashboard_' . jvfrm_spot_dashboard()->page_style . '_nav',
	Array(
		'my-items'		=> Array(
			'label'		=> esc_html__( "My List", 'javospot' ),
			'active'	=> true,
		),
	)
);


require_once JVFRM_SPOT_DSB_DIR . '/mypage-common-header.php';
get_header(); ?>

<div class="jv-my-page">
	<div class="row top-row container">
		<h2 class="jv-my-page-user-name"><?php printf('%s', $jvfrm_spot_curUser->display_name);?></h2>
		<div class="col-md-12">
			<?php get_template_part('library/dashboard/' . jvfrm_spot_dashboard()->page_style . '/sidebar', 'user-info');?>
		</div> <!-- col-12 -->
	</div> <!-- top-row -->

	<div class="container second-container-content">
		<div class="row row-offcanvas row-offcanvas-left jv-mypage-home">
			<div class="col-xs-12 col-sm-12 main-content-right" id="main-content">
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default panel-wrap">

							<div class="panel-heading">
							</div> <!-- panel-heading -->

							<div class="panel-body">
								<ul class="nav nav-tabs" role="tablist">
									<?php
									if( !empty( $jvfrm_spot_dashboard_tabs ) ) : foreach( $jvfrm_spot_dashboard_tabs as $tabID => $arrMeta ){
										$is_active	= isset( $arrMeta['active'] ) ? " class=\"active\" " : false;
										echo "<li role=\"presentation\"{$is_active}>
												<a href=\"#{$tabID}\" aria-controls=\"{$tabID}\" role=\"tab\" data-toggle=\"tab\">{$arrMeta['label']}</a>
											</li>";
									} endif; ?>
								 </ul>
								<?php
								$strPaymentShortcode	= 'lava_' . jvfrm_spot_core()->slug . '_orders';
								$strFavoriteShortcode		= 'lava_' . jvfrm_spot_core()->slug . '_favorites'; ?>
								<div class="tab-content">
									<div role="tabpanel" class="tab-pane active" id="my-items">
									<!-- Starting Content -->
										<?php do_action( 'jvfrm_spot_dashboard_mylists', $jvfrm_spot_curUser ); ?>
									<!-- End Content -->
									</div> <!-- #my-items -->
									<div role="tabpanel" class="tab-pane" id="jv-favorite">
										<?php
										if( shortcode_exists( $strFavoriteShortcode ) ) {
											echo do_shortcode( '[' . $strFavoriteShortcode . ']' );
										}else{
											printf( "<div class='jv-mypage-not-found-dat'>%s</div>", esc_html__( "Not found any data", 'javospot' ) );
										} ?>
									</div><!-- #jv-favorite -->
									<div role="tabpanel" class="tab-pane" id="jv-payment">
										<?php
										if( shortcode_exists( $strPaymentShortcode ) ) {
											echo do_shortcode( '[' . $strPaymentShortcode . ']' );
										}else{
											printf( "<div class='jv-mypage-not-found-dat'>%s</div>", esc_html__( "Not found any data", 'javospot' ) );
										} ?>
									</div><!-- #jv-payment -->
								</div><!-- tab-content -->
							</div><!-- panel-body -->


						</div> <!-- panel -->
					</div> <!-- col-md-12 -->
				</div><!--/row-->
			</div><!-- wrap-right -->
		</div><!--/row-->
	</div><!--/.container-->
</div><!--jv-my-page-->
<?php get_footer();