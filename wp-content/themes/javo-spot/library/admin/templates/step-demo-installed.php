<?php if( !empty( $sectionMeta[ 'passed' ] ) ) : ?>
<h4>
	<i class="jv-helper-icon success"></i>
	<?php esc_html_e( "Demo data has been setup properly.", 'javospot' ); ?>
</h4>
<?php else: ?>
<p>
<?php esc_html_e( "You have not added any data yet.", 'javospot' ) ?><br/>
<?php esc_html_e( "You can simply import a demo data if you want to make your site like one of our demo sites. after you import, you can still customize them.", 'javospot' ); ?>
<a href="<?php echo admin_url( 'admin.php?page=javo-home_import' ); ?>"><?php esc_html_e( "Visit to install demo data.", 'javospot' ); ?></a>
</p>
<p><a href="<?php echo admin_url( 'admin.php?page=javo-home_import' ); ?>" class="button button-primary"><?php esc_html_e( "Go Import Demo Page", 'javospot'); ?></a></p>
<?php endif; ?>
<hr>
<h4><?php esc_html_e( "Demo Import Documentation", 'javospot' ); ?></h4>
<a href="<?php echo esc_url( "www.javothemes.com/spot/documentation" ); ?>" target="_blank" class="button"><?php esc_html_e( "Documentation", 'javospot'); ?></a>
<a href="<?php echo admin_url( 'admin.php?page=javo-home_import' ); ?>" class="button button-primary"><?php esc_html_e( "Setup Again", 'javospot'); ?></a>