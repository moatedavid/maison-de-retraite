<?php
if( ! $get_jvfrm_spot_opt_sidebar = get_post_meta( $post->ID, 'jvfrm_spot_sidebar_type', true ) )
	$get_jvfrm_spot_opt_sidebar		= 'full';
?>

<label class="jvfrm_spot_pmb_option sidebar op_s_left <?php echo sanitize_html_class( $get_jvfrm_spot_opt_sidebar == 'left'? ' active':'' );?>">
	<span class="ico_img"></span>
	<p><input name="jvfrm_spot_opt_sidebar" value="left" type="radio" <?php checked($get_jvfrm_spot_opt_sidebar == 'left');?>> <?php esc_html_e("Left",'javospot'); ?></p>
</label>
<label class="jvfrm_spot_pmb_option sidebar op_s_right <?php echo sanitize_html_class( $get_jvfrm_spot_opt_sidebar == 'right'? ' active':'' );?>">
	<span class="ico_img"></span>
	<p><input name="jvfrm_spot_opt_sidebar" value="right" type="radio" <?php checked($get_jvfrm_spot_opt_sidebar == 'right');?>> <?php esc_html_e("Right",'javospot'); ?></p>
</label>
<label class="jvfrm_spot_pmb_option sidebar op_s_full <?php echo sanitize_html_class( $get_jvfrm_spot_opt_sidebar == 'full' || $get_jvfrm_spot_opt_sidebar == ''? ' active':'' );?>">
	<span class="ico_img"></span>
	<p><input name="jvfrm_spot_opt_sidebar" value="full" type="radio" <?php checked( $get_jvfrm_spot_opt_sidebar == 'full' || $get_jvfrm_spot_opt_sidebar == '');?>> <?php esc_html_e("Fullwidth",'javospot'); ?></p>
</label>