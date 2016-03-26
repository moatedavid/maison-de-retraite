<?php if( !empty( $sectionMeta[ 'passed' ] ) ) : ?>
<h4>
	<i class="jv-helper-icon success"></i>
	<?php esc_html_e( "Menu has been setup properly.", 'javospot' ); ?>
</h4>
<?php else: ?>
<p>
	<div><?php esc_html_e( "You have not added any menus.", 'javospot' ); ?></div>
	<div><?php esc_html_e( "Please setup menus", 'javospot' ); ?> <a href="<?php echo admin_url( 'nav-menus.php' ); ?>"><?php esc_html_e( "here", 'javospot' ); ?></a>.</div>
</p>
<p><a href="<?php echo admin_url( 'nav-menus.php' ); ?>" class="button button-primary"><?php esc_html_e( "Go Menu Settings", 'javospot'); ?></a></p>
<?php endif; ?>
<hr>
<h4><?php esc_html_e( "Menu Setting Documentation", 'javospot' ); ?></h4>
<a href="<?php echo esc_url( "www.javothemes.com/spot/documentation" ); ?>" target="_blank" class="button"><?php esc_html_e( "Documentation", 'javospot'); ?></a>
<a href="<?php echo admin_url( 'nav-menus.php' ); ?>" class="button button-primary"><?php esc_html_e( "Setup Again", 'javospot'); ?></a>