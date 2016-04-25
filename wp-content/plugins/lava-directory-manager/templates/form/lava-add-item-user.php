
<div class="form-inner">
	<label><?php _e("Connexion", "Lavacode"); ?></label>
	<?php _e( "Si tu as un compte?", 'Lavacode'); ?>&nbsp;
	<a href="<?php echo $lava_loginURL; ?>"> <?php _e( "Identifiant", 'Lavacode' ); ?> </a>
</div>

<div class="form-inner">
	<label><?php _e("Email utilisateur", "Lavacode"); ?></label>
	<input name="user_email" type="email" class="form-control" value="" placeholder="<?php _e( "Adresse Email",'Lavacode' ); ?>">
</div>
