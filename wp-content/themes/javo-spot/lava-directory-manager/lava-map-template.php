<?php
global
	$jvfrm_spot_tso
	, $jvfrm_spot_get_query
	, $jvfrm_spot_post_query
	, $jvfrm_spot_map_box_type;
get_header();
if( ! $jvfrm_spot_this_map_opt = get_post_meta( $post->ID, 'jvfrm_spot_map_page_opt', true) )
	$jvfrm_spot_this_map_opt = Array();
$jvfrm_spot_mopt = new jvfrm_spot_array( $jvfrm_spot_this_map_opt );
do_action( "lava_{$post->lava_type}_map_container_before", $post );
?>
<div id="javo-maps-listings-wrap" <?php post_class(); ?>>
	<?php do_action( "jvfrm_spot_{$post->lava_type}_map_body" ); ?>
</div>
<fieldset>
	<input type="hidden" name="get_pos_trigger" value="<?php echo (boolean) esc_attr( $post->req_is_geolocation ); ?>">
	<input type="hidden" name="set_radius_value" value="<?php echo esc_attr( $post->lava_current_dis ); ?>">
</fieldset>
<script type="text/html" id="javo-map-not-found-data">
	<div class="jvfrm_spot_map_not_found" data-dismiss>
		<?php esc_html_e( "Aucune résidence trouvé", 'javospot' ); ?>
	</div>
</script>
<?php
do_action( "lava_{$post->lava_type}_map_container_after", $post );
?>
</div><!-- /#page-style ( in header.php ) -->
<?php
wp_footer(); ?>
</body>
</html>