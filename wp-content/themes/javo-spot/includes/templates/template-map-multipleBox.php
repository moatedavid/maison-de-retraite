<?php
/* Map Switcher */{
	$jvfrm_spot_listing_switcher =
		Array(
			'maps'	=>
				Array(
					'label'		=> esc_html__( "Map", 'javospot' )
					, 'icon'	=> 'fa fa-globe'
				)
			, 'listings'	=>
				Array(
					'label'		=> esc_html__( "List", 'javospot' )
					, 'icon'	=> 'fa fa-bars'
				)
		);
} ?>
<div id="javo-maps-listings-switcher" <?php jvfrm_spot_map_class( 'text-right'); ?>>
	<div class="col-sm-8 switcher-left">
		<?php do_action( 'jvfrm_spot_'. jvfrm_spot_core()->slug . '_map_switcher_before' ); ?>
	</div><!-- /.col-xs-8 -->
	<div class="col-sm-4 switcher-right">
		<div class="btn-group" data-toggle="buttons">
			<?php
			foreach( $jvfrm_spot_listing_switcher as $type => $attr )
			{
				$this_listing_type	= apply_filters(
					'jvfrm_spot_' . jvfrm_spot_core()->slug . '_map_switcher_value',
					get_post_meta(get_the_ID(), '_page_listing', true )
				);
				$jvfrm_spot_listing_type		= $this_listing_type != '1' ? 'maps' : 'listings';
				$is_active				= $this_listing_type == '1';
				$is_active				= $jvfrm_spot_listing_type === $type ? ' active' : $is_active;
				echo "<label class=\"btn btn-default {$is_active}\">";
					echo "<input type=\"radio\" name=\"m\" value=\"{$type}\"" . checked( (boolean)$is_active, true, false ) . ">";
					echo "<i class=\"{$attr['icon']}\"></i>" . ' ';
					echo "<span>".esc_html( $attr['label'] )."</span>";
				echo "</label>";
			} ?>
		</div><!--/.btn-group-->
	</div>
</div>
<div id="javo-maps-wrap" class="hidden">
	<div class="row">
		<?php get_template_part( 'templates/parts/part-multiple-listing-type', 'map' ); ?>
	</div><!-- /.row -->
</div>
<div id="javo-listings-wrap" class="hidden">
	<div class="row">
		<?php get_template_part( 'templates/parts/part-multiple-listing-type', 'list' ); ?>
	</div><!-- /.row -->
</div>