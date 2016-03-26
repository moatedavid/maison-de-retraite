<?php
class module4 extends Jvfrm_Spot_Module
{
	public function __construct( $post, $param=Array() ) {
		$this->lghTitle		= 10;
		$this->lghContent	= 0;
		parent::__construct( $post, $param );
	}

	public function output()
	{
		ob_start();
		?>
		<div <?php $this->classes( 'jv-effect-zoom-in' ); ?>>
			<?php $this->before(); ?>
				<a href="<?php echo $this->permalink;?>">
					<div class="jv-thumb">
						<?php echo $this->thumbnail( 'large' ); ?>
						<div class="meta-category"><?php echo $this->category(); ?></div>
					</div>
				</a>
				<h4 class="meta-title"><?php echo $this->title; ?></h4>
				<p><?php echo $this->excerpt; ?></p>
				<?php echo $this->addtionalInfo(); ?>


				<?php /*
				<div class="jv-hover-back-info">
					<div class="col-md-12 jv-hover-back-title"><?php echo $this->title; ?></div>
					<div class="col-sm-8 jv-hover-back-meta"><?php echo $this->addtionalInfo(); ?></div>
					<div class="col-sm-4 jv-hover-back-thumbnail">
						<a href="<?php echo $this->permalink;?>">
							<?php echo $this->thumbnail(); ?>
						</a>
					</div>

					<div class="jv-hover-back-button-group">
						<div class="btn-group" role="group" aria-label="...">
							<button type="button" class="btn btn-default" href="<?php echo $this->permalink;?>" onclick="location.href=this.href;">
								<i class="fa fa-search"></i>
							</button>
							<button type="button" class="btn btn-default"><i class="fa fa-heart-o"></i></button>
							<button type="button" class="btn btn-default"><i class="fa fa-share-alt"></i></button>
						</div>
					</div>
				</div>*/ ?>

			<?php $this->after(); ?>
		</div><!-- /.row -->
		<?php
		return ob_get_clean();
	}
}