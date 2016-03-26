<?php
/**
 *
 *	000 Inline Block Type
 * @since	1.0
 */
class module1 extends Jvfrm_Spot_Module
{
	public function __construct( $post, $param=Array() ) {
		$this->lghTitle			= 10;
		$this->lghContent	= 30;
		parent::__construct( $post, $param );
	}

	public function output()
	{
		ob_start();
		?>
		<div <?php $this->classes( 'media' ); ?>>
			<?php $this->before(); ?>
			<div class="media-left effect-wrap jv-thumbnail">
				<a href="<?php echo $this->permalink;?>">
					<?php echo $this->thumbnail( 'thumbnail', false, false ); ?>
					<div class="meta-category"><?php echo $this->category(); ?></div>
				</a>
			</div><!-- /.media-left -->
			<div class="media-body">
				<h4 class="media-heading meta-title"><?php echo $this->title; ?></h4>
				<?php echo $this->addtionalInfo(); ?>
				<?php if($this->excerpt != '' ){ ?>
				<div class="jv-excerpt-wrap"><?php echo $this->excerpt; ?></div>
				<?php } ?>
				<?php echo $this->moreInfo(); ?>
			</div><!-- /.media-body -->
			<?php $this->after(); ?>
		</div><!-- /.media -->
		<?php
		return ob_get_clean();
	}
}