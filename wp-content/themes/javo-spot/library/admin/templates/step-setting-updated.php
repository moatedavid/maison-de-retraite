<?php if( !empty( $sectionMeta[ 'passed' ] ) ) : ?>
<h4>
	<i class="jv-helper-icon success"></i>
	<?php esc_html_e( "Initial theme settings have been setup properly.", 'javospot' ); ?>
</h4>
<?php else: ?>
<p>
	<div><?php esc_html_e( "You have not setup any options on theme settings." ,'javospot' ); ?></div>
	<div><?php esc_html_e( "These will setup your logos, layouts, header options, colors.", 'javospot' ); ?></div>
</p>
<p><a href="<?php echo esc_url( add_query_arg( Array( 'page' => 'javo-theme-settings' ), admin_url( 'admin.php' ) ) ); ?>" class="button button-primary"><?php esc_html_e( "Go to Theme Settings Page", 'javospot'); ?></a></p>
<?php endif; ?>
<hr>
<h4><?php esc_html_e( "Theme Settings Documentation", 'javospot' ); ?></h4>
<a href="<?php echo esc_url( "www.javothemes.com/home/documentation" ); ?>" target="_blank" class="button"><?php esc_html_e( "Documentation", 'javospot'); ?></a>
<a href="<?php echo esc_url( add_query_arg( Array( 'page' => 'javo-theme-settings' ), admin_url( 'admin.php' ) ) ); ?>" class="button button-primary"><?php esc_html_e( "Setup Again", 'javospot'); ?></a>