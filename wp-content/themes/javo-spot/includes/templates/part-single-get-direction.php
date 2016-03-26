<div class="col-md-12 col-xs-12 lava-get-direction" id="javo-item-wc-get-direction-section" data-jv-detail-na1v>
	<h3 class="page-header"><?php esc_html_e( "Get Direction", 'javospot' ); ?></h3>
	<div class="panel panel-default">
		<div class="panel-body">
			<?php
			$objGetDirection->add_class( 'button', 'admin-color-setting btn btn-primary' );
			$objGetDirection->load_template(
				'single-get-direction.php',
				$objGetDirection->getParams()
			); ?>
		</div><!--/.panel-body-->
	</div><!--/.panel-->
</div><!-- /#javo-item-describe-section -->