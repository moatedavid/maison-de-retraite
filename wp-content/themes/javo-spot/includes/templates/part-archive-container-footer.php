<div class="jv-archive-bottom-section">
	<?php
	if( class_exists( 'jvfrm_spot_category_box2' ) ) :
		$objCategoryBox2	= new jvfrm_spot_category_box2();
		echo join( false,
			Array(
				sprintf(
					"<h3 class=\"jv-archive-bottom-header\">%s</h3>",
					esc_html__( "Other categories", 'javospot' )
				),
				$objCategoryBox2->output(
					Array( 'post_title_transform' => 'uppercase' ), false
				)
			)
		);
	endif; ?>
</div>