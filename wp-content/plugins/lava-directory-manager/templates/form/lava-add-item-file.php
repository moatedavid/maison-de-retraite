<script type="text/html" id="lava-detail-image-input-template">
	<fieldset>
		{filename}
		<input type="hidden" name="lava_attach[]" value="{attachID}">
		<input type="button" name="lava_detail_file_remove" value="<?php _e( 'Supprimer', 'Lavacode' ); ?>">
	</fieldset>
</script>
<div class="form-inner">

	<label><?php _e("Image du header", "Lavacode"); ?></label>
	<input type="file" name="lava_featured_file">

</div>

<div class="form-inner">

	<label><?php _e("Gallerie images", "Lavacode"); ?></label>
	<div>
		<div id="lava-detail-image-input-wrap"></div>
		<div><input type="file" name="lava_detail_uploader"></div>
		<div id="lava-additem-upload-loading" class="hidden"></div>
	</div>

</div>