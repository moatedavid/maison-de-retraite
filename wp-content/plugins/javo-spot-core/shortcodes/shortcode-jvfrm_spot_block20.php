<?php

add_action( 'jvfrm_spot_modules_loaded', 'jvfrm_spot_block20_get_modules' );

function jvfrm_spot_block20_get_modules( $modules ) {
	$GLOBALS[ 'jvfrm_spot_block20_all_modules' ] = $modules;
}


class jvfrm_spot_block20 extends JvfrmSpot_ShortcodeParse
{
	private $modules		= Array();

	public function output( $attr, $content='' )
	{
		global $jvfrm_spot_block20_all_modules;
		$this->modules	= Array_keys( $jvfrm_spot_block20_all_modules );

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
		if( !empty( $this->modules ) ) : foreach( $this->modules as $module_name ) {
			if( class_exists( $module_name ) && !empty( $queried_posts[0] ) ) {
				$module = new $module_name( $queried_posts[0] );
				echo "<div>";
					echo "<h3 class=\"page-header\">{$module_name}</h3>";
					echo $module->output();
				echo "</div>";
			}
		} endif;

		$this->sParams();
		$this->pagination();
	}
}