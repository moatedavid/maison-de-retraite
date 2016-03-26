<?php if( !empty( $sectionMeta[ 'passed' ] ) ) : ?>
<h4>
	<i class="jv-helper-icon success"></i>
	<?php esc_html_e( "Permalink has been setup properly.", 'javospot' ); ?>
</h4>
<?php else: ?>
<p>
	<div><?php esc_html_e( "Our theme is compatible with permalink structure. it's also good for SEO.", 'javospot' ); ?></div>
	<div><?php esc_html_e( "Please setup permalink to 'Post name'.", 'javospot' ); ?></div>
</p>
<p>
	<a href="<?php echo admin_url( 'options-permalink.php' ); ?>" class="button button-primary">
		<?php esc_html_e( "Go Permalink Settings", 'javospot'); ?>
	</a>
</p>
<?php endif; ?>
<hr>
<h4><?php esc_html_e( "Permalink Documentation", 'javospot' ); ?></h4>
<a href="<?php echo esc_url( "www.javothemes.com/spot/documentation" ); ?>" target="_blank" class="button"><?php esc_html_e( "Documentation", 'javospot'); ?></a>
<a href="<?php echo admin_url( 'options-permalink.php' ); ?>" class="button button-primary"><?php esc_html_e( "Setup Again", 'javospot'); ?></a>