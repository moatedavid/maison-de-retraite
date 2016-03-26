<?php
/**
 *
 *
 * @since	1.0
 */
class module14 extends Jvfrm_Spot_Module
{
	public function __construct( $post, $param=Array() ) {
		$this->lghTitle			= 10;
		$this->lghContent	= 30;
		parent::__construct( $post, $param );
	}

	public function output()
	{
		ob_start();
		$jvfrm_spot_item_category = $this->category();
		$jvfrm_spot_item_location = $this -> get_term('item_location');
		?>
		<div <?php $this->classes( 'media' ); ?>>
			<?php $this->before(); ?>
			<div class="media-left">
				<a href="<?php echo $this->permalink;?>">
					<?php echo $this->thumbnail( 'thumbnail' , false, false ); ?>
				</a>
			</div><!-- /.media-left -->
			<div class="media-body">
				<h4 class="media-heading"><?php echo $this->title; ?></h4>
				<?php echo $this->excerpt; ?>
				<ul class="module-meta list-inline">
					<li class="jv-meta-category">
						<i class="fa fa-user"></i>
						<?php echo $jvfrm_spot_item_category!='' ? $jvfrm_spot_item_category : __('No Category','javo'); ?>
					</li><!-- jv-meta-category -->
					<li class="jv-meta-location">
						<i class="fa fa-map-marker"></i>
						<?php echo $jvfrm_spot_item_location!='' ? $jvfrm_spot_item_location : __('No Location','javo'); ?>
					</li><!-- jv-meta-location -->
				</ul><!-- module-meta list-inline -->
				<?php echo $this->moreInfo(); ?>
			</div><!-- /.media-body -->
			<?php $this->after(); ?>
		</div><!-- /.media -->
		<?php
		return ob_get_clean();
	}
}