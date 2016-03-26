<?php
/**
 *
 *	000 Smail Grid Type
 * @since	1.0
 */
class moduleSmallGrid extends Jvfrm_Spot_Module
{
	public function __construct( $post, $param=Array() ) {
		$this->lghTitle			= 8;
		parent::__construct( $post, $param );
	}

	public function output()
	{
		ob_start();
		?>
		<div <?php $this->classes(); ?>>
			<?php $this->before(); ?>
			<div class="effect-wrap jv-thumb">
				<a href="<?php echo $this->permalink;?>">
				<?php
				$jvfrm_spot_thumbnail_size = 'jvfrm-spot-large';
				if( isset( $this->shortcode_args[ 'columns' ] ) ){
					$jvfrm_spot_shortcode_column = $this->shortcode_args[ 'columns' ];
					switch($jvfrm_spot_shortcode_column){
						case 3 : $jvfrm_spot_thumbnail_size = 'jvfrm-spot-box-v'; break;
						case 2 : $jvfrm_spot_thumbnail_size = 'jvfrm-spot-huge'; break;
						case 1 : $jvfrm_spot_thumbnail_size = 'jvfrm-spot-item-detail'; break;
					}
				}
				?>
					<?php echo $this->thumbnail( $jvfrm_spot_thumbnail_size , false, false ); ?>
				</a>
				<div class="meta-category"><?php echo $this->category(); ?></div>
			</div>
			<div class="jv-grid-meta">
				<h4 class="meta-title"><?php echo $this->title; ?></h4>
			</div>
			<?php $this->after(); ?>
		</div>
		<?php
		return ob_get_clean();
	}
}