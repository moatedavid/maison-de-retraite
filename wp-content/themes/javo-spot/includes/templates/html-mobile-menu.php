<!--moblie menu-->
<div class="navbar-mobile-wrap">
	<?php if( is_singular( self::SLUG ) ) : ?>
		<div class="row visible-xs">
			<div class="col-md-12">
				<button type="button" class="btn btn-pirmary btn-block" data-toggle="modal" data-target=".lava_contact_modal">
					<i class="fa fa-user"></i>
					<?php esc_html_e( "Contact", 'javospot' ); ?>
				</button>
			</div>
		</div>
	<?php endif; ?>
	<!-- Brand and toggle get grouped for better mobile display -->
	<div class="row navbar-moblie visible-xs">
		<!-- Button trigger modal -->
		<div class="col-xs-4 col-xs-offset-4 text-center">
			<button type="button" class="btn btn-primary btn-lg javo-mobile-modal-button" data-toggle="modal" data-target="#jv-mobile-search-form">
			  <i class="fa fa-plus"></i>
			</button>
		</div>
	</div><!--/.navbar-header-->
</div><!--/.row-->
<!--/.moblie menu-->