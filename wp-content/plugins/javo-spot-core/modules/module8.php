<?php
/**
 *
 *
 * @since	1.0
 */

class module8 extends Jvfrm_Spot_Module
{
	public function __construct( $post, $param=Array() ) {
		$this->lghContent	= 30;
		parent::__construct( $post, $param );
	}

	public function output()
	{
		ob_start();
		?>
		<div <?php $this->classes( 'thumbnail' ); ?>>
			<?php $this->before(); ?>
			<div class="thumb-wrap">
				<?php echo $this->thumbnail( 'large', true ); ?>
				<div class="meta-wrap">
					<div class="meta-wrap-container">
						<div class="meta-summary meta-author-img">
							<div class="meta-status">
								<?php echo $this->avatar; ?>
							</div><!-- /.meta-rent-->
						</div><!-- /.meta-summary -->
						<div>
							<div class="meta-category no-background">
								<i class="fa fa-map-marker"></i> <span class="jv-module-location"><?php echo $this->c( 'listing_location', __( "Not Set", 'javo' ) ); ?></span> <i class="fa fa-folder-o"></i> <span class="jv-module-category"><?php echo $this->category(); ?></span>
							</div><!-- /.meta-category -->
							<h3 class="meta-title"><?php echo $this->title; ?></h3>
						</div>
					</div><!-- /.meta-wrap-container -->
				</div>
				<div class="meta-caption">
					<div class="meta-caption-container">
						<div class="text-center">
							<?php echo $this->addtionalInfo(); ?>
						</div>
						<div class="meta-excerpt text-center">
							<?php echo $this->excerpt; ?>
						</div>
						<div class="meta-actions text-center">
							<a class="btn btn-sm btn-primary jv-button-transition" href="<?php echo $this->permalink; ?>">
								<?php echo strtoupper( __( "Read More", 'javo' ) ); ?>
							</a>
						</div>
					</div><!-- /.meta-caption-container -->
				</div><!-- /.meta-caption -->
			</div><!-- /,thumb-wrap -->
			<?php $this->after(); ?>
		</div>
		<?php
		return ob_get_clean();
	}
}