<ul class="jv-default-setting-status-plugins">
	<?php
	if( !empty( $sectionMeta[ 'plugins' ] ) ) : foreach( $sectionMeta[ 'plugins' ] as $className => $pluginsName ) {
		$isActived	 = class_exists( $className ) ? ' class="active"' : null;
		echo "<li{$isActived}>{$pluginsName}</li>";
	} endif; ?>
</ul>

<?php if( !empty( $sectionMeta[ 'passed' ] ) ) : ?>
<h4>
	<i class="jv-helper-icon success"></i>
	<?php esc_html_e( "Plugins have been setup properly.", 'javospot' ); ?>
</h4>
<?php else: ?>
<p>
	<a href="<?php echo esc_url( add_query_arg( Array( 'page' => sanitize_title( $this->name ) . '_plugins' ), admin_url( 'admin.php' ) ) ); ?>" class="button button-primary">
		<?php esc_html_e( "Go to Plugin Active Page", 'javospot'); ?>
	</a>
</p>
<?php endif; ?>
<hr>
<h4><?php esc_html_e( "Instal Plugin Documentation", 'javospot' ); ?></h4>
<a href="<?php echo esc_url( "www.javothemes.com/spot/documentation" ); ?>" target="_blank" class="button"><?php esc_html_e( "Documentation", 'javospot'); ?></a>
<a href="<?php echo esc_url( add_query_arg( Array( 'page' => sanitize_title( $this->name ) . '_plugins' ), admin_url( 'admin.php' ) ) ); ?>" class="button button-primary"><?php esc_html_e( "Setup Again", 'javospot'); ?></a>