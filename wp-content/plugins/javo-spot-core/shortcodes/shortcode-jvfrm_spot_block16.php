<?php
class jvfrm_spot_block16 extends JvfrmSpot_ShortcodeParse
{
	public function output( $attr, $content='' )
	{
		parent::__construct( $attr ); ob_start();
		$this->sHeader();
		?>
		<div id="<?php echo $this->sID; ?>" class="shortcode-container fadein">
			<div class="shortcode-header">
				<div class="shortcode-title">
					<?php echo $this->title; ?>
				</div>
				<div class="shortcode-nav">
					<?php $this->sFilter(); ?>
				</div>
			</div>
			<div class="row shortcode-output" ><?php $this->loop( $this->get_post() ); ?> </div>
		</div>
		<?php
		$this->sFooter(); return ob_get_clean();
	}

	public function loop( $queried_posts )
	{
		$columns		= intVal( $this->columns );

		// Query Start
		if( ! empty( $queried_posts ) ) : foreach( $queried_posts as $index => $post ) {
			$arrModuleParam		= Array(
				'length_content'	=> 50,
				'thumbnail_size'	=> 'jvfrm-spot-box-v'
			);
			switch( $columns ) :
				case 2:
					echo "<div class='col-md-6 col-sm-6'>";
					$arrModuleParam[ '' ] = 'jvfrm-spot-box';
				break;
				case 3:
					echo "<div class='col-md-4 col-sm-6'>";
					$arrModuleParam[ 'thumbnail_size' ] = 'jvfrm-spot-map-thumbnail';
				break;
				case 1:
				default:
					echo "<div class='col-md-12'>";
			endswitch;
				$objModule1	= new module1( $post, $arrModuleParam );
				echo $objModule1->output();
			echo "</div>";
		} endif;
		$this->sParams();
		$this->pagination();
	}
}