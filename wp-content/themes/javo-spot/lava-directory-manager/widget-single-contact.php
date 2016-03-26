<?php
$arrContactInfos	= Array(
	Array(
		'label'		=> esc_html__( "Author", 'javospot' ),
		'value'		=> esc_attr( $post->display_name ),
		'link'		=> 'enable'
	),
	Array(
		'label'		=> esc_html__( "Phone", 'javospot' ),
		'value'		=> esc_attr( $post->_phone1 ),
	),
);

$author_url = jvfrm_spot_getUserPage( get_the_author_meta( 'ID' ) );

add_filter( 'wp_footer', 'jvfrm_spot_single_contact_modal_html' );
function jvfrm_spot_single_contact_modal_html(){
	global $lava_contact_shortcode;
	?>
	<div class="modal fade lava_contact_modal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<button type="button" class="close" data-dismiss="modal" aria-label="<?php esc_attr_e( "Close", 'javospot' ); ?>"><span aria-hidden="true">&times;</span></button>
					<div class="contact-form-widget-wrap">
						<?php echo apply_filters( 'the_content', $lava_contact_shortcode ); ?>
					</div> <!-- contact-form-widget-wrap -->
				</div>
			</div>
		</div>
	</div>
	<?php
} ?>

<form class="cart lava-wg-author-contact-form" method="post" enctype='multipart/form-data'>

	<div class="panel panel-default">
		<div class="panel-heading admin-color-setting">
			<div class="row">
				<div class="col-md-12">
					<h3><?php esc_html_e( " Contact", 'javospot' ) ?></h3>
				</div><!-- /.col-md-12 -->
			</div><!-- /.row -->
		</div><!-- /.panel-heading -->

		<div class="panel-body">
			<div class="row">
				<div class="col-md-12">
					<ul class="list-group">
						<li class="list-group-item">
							<?php
							/* Portion informations */
							if( ! empty( $arrContactInfos ) )
							{
								echo "<ul style=\"position: relative;\">\n";
								foreach( $arrContactInfos as $args )
								{
									echo "\t<li class=\"row\">\n";
										echo "\t\t<div class=\"hidden-xs hidden-sm col-lg-4\">\n";
											echo "\t\t\t{$args['label']}\n";
										echo "\t\t</div>\n";

										if(isset($args['link'])){
											echo "\t\t<div class=\"hidden-xs hidden-sm col-xs-11 col-lg-8\"><a href=\"{$author_url}\">\n";
												echo "\t\t\t{$args['value']}\n";
											echo "\t\t</a></div>\n";
										}else{
											echo "\t\t<div class=\"hidden-xs hidden-sm col-xs-11 col-lg-8\">\n";
												echo "\t\t\t{$args['value']}\n";
											echo "\t\t</div>\n";
										}

									echo "\t</li>\n";
								}
								echo "</ul>\n";
							} ?>
						</li><!--/.list-group-item-->
					</ul>
				</div><!--/.col-md-12-->
			</div><!--/.row-->

		</div><!-- /.panel-body -->

		<div class="panel-body author-contact-button-wrap">

			<!-- Large modal -->
			<button type="button" class="btn btn-primary admin-color-setting lava_contact_modal_button" data-toggle="modal" data-target=".lava_contact_modal"><?php esc_html_e("contact", 'javospot') ?></button>
			<!-- Add Wish List -->
			<div class="row share">
				<div class="col-md-6 text-center">

					<button type="button" class="btn admin-color-setting-hover lava-Di-share-trigger">
						<?php esc_html_e( "Share", 'javospot' ); ?>
					</button>

				</div><!-- /.col-md-6 -->
				<div class="col-md-6 text-center">

					<button type="button" class="btn admin-color-setting-hover lava-wg-single-report-trigger">
						<?php esc_html_e( "Report", 'javospot' ); ?>
					</button>
				</div><!-- /.col-md-12 -->
			</div><!-- /.row -->
			<div class="row">
				<div class="col-md-12">
					<?php
					if( function_exists( 'lava_directory_claim_button' ) )
						lava_directory_claim_button(
							Array(
								'class'	=> 'btn btn-block admin-color-setting-hover',
								'label'		=> esc_html__( "Claim", 'javospot' ),
								'icon'		=> false
							)
						);
					?>
					<?php
					if( function_exists( 'lava_directory_booking_button' ) )
						lava_directory_booking_button(
							Array(
								'class'	=> 'btn btn-block admin-color-setting-hover',
								'label'		=> esc_html__( "Booking", 'javospot' ),
								'icon'		=> false
							)
						);
					?>
				</div><!-- /.col-md-12 -->
			</div><!-- /.row -->
		</div><!-- /.panel-body -->
	</div><!-- /.panel -->
	<fieldset>
		<input type="hidden" name="ajaxurl" value="<?php echo esc_url( admin_url( 'admin-ajax.php' ) );?>">
	</fieldset>
</form>

<!-- Modal -->
<script type="text/html" id="lava-wg-single-report-template">
	<div class="row lava_report_wrap">
		<button type="button" class="close">
			<span aria-hidden="true">&times;</span>
		</button>
		<div class="col-md-12"><?php echo apply_filters( 'the_content', jvfrm_spot_get_reportShortcode() );  ?></div>
	</div>
</script>
<script type="text/html" id="lava-Di-share">

	<div class="row">

		<div class="col-md-12">

			<header class="modal-header">
				<?php esc_html_e( "Share", 'javospot' ); ?>
				<button type="button" class="close">
					<span aria-hidden="true">&times;</span>
				</button>
			</header>
			<div class="row">
				<div class="col-md-9">

					<div class="input-group">
						<span class="input-group-addon">
							<i class="fa fa-link"></i>
						</span><!-- /.input-group-addon -->
						<input type="text" value="<?php the_permalink(); ?>" class="form-control" readonly>

					</div>
				</div><!-- /.col-md-9 -->
				<div class="col-md-3">
					<button class="btn btn-primary btn-block" id="lava-wg-url-link-copy" data-clipboard-text="<?php the_permalink(); ?>">
						<i class="fa fa-copy"></i>
						<?php esc_html_e( "Copy URL", 'javospot' );?>
					</button>
				</div><!-- /.col-md-3 -->
			</div><!-- /,row -->

			<p>

				<div class="row">

					<div class="col-md-4">
						<button class="btn btn-info btn-block javo-share sns-facebook" data-title="<?php the_title(); ?>" data-url="<?php the_permalink(); ?>">
							<?php esc_html_e( "Facebook", 'javospot' );?>
						</button>
					</div><!-- /.col-md-4 -->

					<div class="col-md-4">
						<button class="btn btn-info btn-block javo-share sns-twitter" data-title="<?php the_title(); ?>" data-url="<?php the_permalink(); ?>">
							<?php esc_html_e( "Twitter", 'javospot' );?>
						</button>
					</div><!-- /.col-md-4 -->

					<div class="col-md-4">
						<button class="btn btn-info btn-block javo-share sns-google" data-title="<?php the_title(); ?>" data-url="<?php the_permalink(); ?>">
							<?php esc_html_e( "Google +", 'javospot' );?>
						</button>
					</div><!-- /.col-md-4 -->

				</div><!-- /,row -->
			</p>
		</div>
	</div>
</script>