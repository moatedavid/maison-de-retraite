<?php if( !empty( $sectionMeta[ 'passed' ] ) ) : ?>
<h4>
	<i class="jv-helper-icon success"></i>
	<?php esc_html_e( "Widgets have been setup properly.", 'javospot' ); ?>
</h4>
<?php else: ?>
<p>
	<?php esc_html_e( "We recommend you to add some widgets for proper functionality", 'javospot' ); ?>
</p>
<p><a href="<?php echo admin_url( 'widgets.php' ); ?>" class="button button-primary"><?php esc_html_e( "Go to Widgets Settings", 'javospot'); ?></a></p>
<?php endif; ?>
<hr>
<h4><?php esc_html_e( "Widget Setting Documentation", 'javospot' ); ?></h4>
<a href="<?php echo esc_url( "www.javothemes.com/spot/documentation" ); ?>" target="_blank" class="button"><?php esc_html_e( "Documentation", 'javospot'); ?></a>
<a href="<?php echo admin_url( 'widgets.php' ); ?>" class="button button-primary"><?php esc_html_e( "Setup Again", 'javospot'); ?></a>