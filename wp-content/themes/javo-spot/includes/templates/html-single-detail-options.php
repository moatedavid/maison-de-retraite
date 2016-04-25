<div class="col-md-12 col-xs-12 item-condition" id="javo-item-condition-section" data-jv-detail-nav>
	<h3 class="page-header"><?php esc_html_e( "Item detail", 'javospot' ); ?></h3>
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="row summary_items">
				<div class="col-md-6 col-xs-6 summary-detail-left">
					<div class="row">
						<div class="col-md-4 col-xs-12">
							<span><?php esc_html_e( "Website", 'javospot' );?></span>
						</div>
						<div class="col-md-8 col-xs-12">
							<span><a href="<?php echo esc_attr(get_post_meta( get_the_ID(), '_website', true ));?>" target="_blank"><?php echo esc_html(get_post_meta( get_the_ID(), '_website', true ));?></a></span>
						</div>
					</div><!-- /.row *website -->
					<div class="row">
						<div class="col-md-4 col-xs-12">
							<span><?php esc_html_e( "Email", 'javospot' );?></span>
						</div>
						<div class="col-md-8 col-xs-12">
							<span><?php echo get_the_ID() . esc_html(get_post_meta( get_the_ID(), '_email', true ));?></span>
						</div>
					</div><!-- /.row *email -->
					<div class="row">
						<div class="col-md-4 col-xs-12">
							<span><?php esc_html_e( "Address", 'javospot' );?></span>
						</div>
						<div class="col-md-8 col-xs-12">
							<span><?php echo esc_html(get_post_meta( get_the_ID(), '_address', true ));?></span>
						</div>
					</div><!-- /.row *address -->
				</div><!--/.col-md-6.summary-detail-left -->
				<div class="col-md-6 col-xs-6 summary-detail-right">
					<div class="row">
						<div class="col-md-4 col-xs-12">
							<span><?php esc_html_e( "Phone 1", 'javospot' );?></span>
						</div>
						<div class="col-md-8 col-xs-12">
							<span><?php echo esc_html(get_post_meta( get_the_ID(), '_phone1', true ));?></span>
						</div>
					</div><!-- /.row *phone1-->
					<div class="row">
						<div class="col-md-4 col-xs-12">
							<span><?php esc_html_e( "Phone 2", 'javospot' );?></span>
						</div>
						<div class="col-md-8 col-xs-12">
							<span><?php echo esc_html(get_post_meta( get_the_ID(), '_phone2', true ));?></span>
						</div>
					</div><!-- /.row *phone2-->
					<div class="row">
						<div class="col-md-4 col-xs-12">
							<span><?php esc_html_e( "Keyword", 'javospot' );?></span>
						</div>
						<div class="col-md-8 col-xs-12">
							<span><i><?php echo esc_html(lava_directory_terms( get_the_ID(), 'listing_keyword' )); ?></i></span>
						</div>
					</div><!-- /.row *phone2-->
				</div><!--/.col-md-6.summary-detail-right -->
			</div><!--/.summary_items -->
		</div><!--/.panel-body -->
	</div><!--/.panel panel-default -->
</div><!--/.col-md-12 -->