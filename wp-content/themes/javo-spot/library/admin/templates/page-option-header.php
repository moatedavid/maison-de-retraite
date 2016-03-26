<?php
$jvfrm_spot_fancy_dft_opt						= Array(
	'repeat'							=> Array(
		esc_html__("no-repeat", 'javospot')		=> 'no-repeat'
		, esc_html__("repeat-x", 'javospot')		=> 'repeat-x'
		, esc_html__("repeat-y", 'javospot')		=> 'repeat-y'
	)
	, 'background-position-x'			=> Array(
		esc_html__("Left", 'javospot')			=> 'left'
		, esc_html__("Center", 'javospot')		=> 'center'
		, esc_html__("Right", 'javospot')		=> 'right'
	)
	, 'background-position-y'			=> Array(
		esc_html__("top", 'javospot')			=> 'top'
		, esc_html__("Center", 'javospot')		=> 'center'
		, esc_html__("Bottom", 'javospot')		=> 'bottom'
	)
);

// Fancy Option
$get_jvfrm_spot_opt_fancy			= get_post_meta( $post->ID, 'jvfrm_spot_header_fancy_type', true );
$jvfrm_spot_fancy_opts				= get_post_meta( $post->ID, 'jvfrm_spot_fancy_options', true );
$jvfrm_spot_fancy						= new jvfrm_spot_array( $jvfrm_spot_fancy_opts );

// Slide Option
$jvfrm_spot_slider						= maybe_unserialize( get_post_meta( $post->ID, 'jvfrm_spot_slider_options', true ) );
$get_jvfrm_spot_opt_slider			= maybe_unserialize( get_post_meta( $post->ID, 'jvfrm_spot_slider_type', true ) );

$strOutputSliderLists			= Array();

if( class_exists( 'RevSlider' ) ) :
	$objSlideRevolution		= new RevSlider;
	$valCurrentSlider			= !empty( $jvfrm_spot_slider[ 'rev_slider' ] ) ? $jvfrm_spot_slider[ 'rev_slider' ] : null;
	$arrSliderItems				= ( Array ) $objSlideRevolution->getArrSliders();
	$strOutputSliderLists[]	='<select name="jvfrm_spot_slide[rev_slider]">';
	$strOutputSliderLists[]	= sprintf( "<option value=''>%s</option>", esc_html__( "Select Slider", 'javospot' ) );
	if( !empty( $arrSliderItems ) ) : foreach( $arrSliderItems as $slider ) {
		$strOutputSliderLists[] = sprintf(
			'<option value="%s"%s>%s</option>',
			$slider->getAlias(),
			selected( $slider->getAlias() == $valCurrentSlider, true, false ),
			$slider->getTitle()
		);
	} else:
		$strOutputSliderLists[]	= sprintf( "<optgroup label=\"%s\"></optgroup>", esc_html__( "Empty Slider", 'javospot' ) );
	endif;
	$strOutputSliderLists[]	='</select>';
else:
	$strOutputSliderLists[]	= sprintf(
		'<label>%s</label>',
		esc_html__( "Please install revolition slider plugin or create slide item." , 'javospot' )
	);
endif;


$jvfrm_spot_pageHeaderOptions	= Array(
	'op_h_title_show'				=> Array(
		'value'							=> 'default',
		'label'							=> esc_html__( "Show page title", 'javospot' ),
	),
	'op_h_title_hide'					=> Array(
		'value'							=> 'notitle',
		'label'							=> esc_html__( "Hide page title", 'javospot' ),
	),
	'op_h_title_fancy'				=> Array(
		'value'							=> 'fancy',
		'label'							=> esc_html__( "Fancy Header", 'javospot' ),
	),
	'op_h_title_slide'				=> Array(
		'value'							=> 'slider',
		'label'							=> esc_html__( "Slide Show", 'javospot' ),
	),
);


if( !$get_jvfrm_spot_opt_header = get_post_meta( $post->ID, 'jvfrm_spot_header_type', true ) )
	$get_jvfrm_spot_opt_header = 'notitle';

if( !empty( $jvfrm_spot_pageHeaderOptions )) : foreach( $jvfrm_spot_pageHeaderOptions as $key => $meta ) {
	$strIsTrue		= $get_jvfrm_spot_opt_header == $meta[ 'value' ];
	$strIsActive		= $strIsTrue ? ' active' : false;

	echo join( "\n",
		Array(
			"<label class=\"jvfrm_spot_pmb_option header {$key}{$strIsActive}\">",
				'<span class="ico_img"></span>',
				sprintf( "<p><input type=\"radio\" name=\"jvfrm_spot_opt_header\" value=\"%s\" %s>%s</p>",
					$meta[ 'value' ],
					checked( $strIsTrue, true, false ),
					$meta[ 'label' ]
				),
			'</label>',
		)
	);

} endif; ?>

<div id="jvfrm_spot_post_header_fancy">
	<div class="">
		<label class="jvfrm_spot_pmb_option fancy op_f_left active">
			<span class="ico_img"></span>
			<p><input name="jvfrm_spot_opt_fancy" type="radio" value="left" checked="checked"> <?php esc_html_e("Title left",'javospot'); ?></p>
		</label>
		<label class="jvfrm_spot_pmb_option fancy op_f_center">
			<span class="ico_img"></span>
			<p><input name="jvfrm_spot_opt_fancy" type="radio" value="center"> <?php esc_html_e("Title center",'javospot'); ?></p>
		</label>
		<label class="jvfrm_spot_pmb_option fancy op_f_right">
			<span class="ico_img"></span>
			<p><input name="jvfrm_spot_opt_fancy" type="radio" value="right"> <?php esc_html_e("Title right",'javospot'); ?></p>
		</label>
	</div>
	<hr>
	<div class="jvfrm_spot_pmb_field">
		<dl>
			<dt><label for="jvfrm_spot_fancy_field_title"><?php esc_html_e("Title",'javospot'); ?></label></dt>
			<dd><input name="jvfrm_spot_fancy[title]" id="jvfrm_spot_fancy_field_title" type="text" value="<?php echo esc_attr($jvfrm_spot_fancy->get('title') );?>"></dd>
		</dl>
		<dl>
			<dt><label for="jvfrm_spot_fancy_field_title_size"><?php esc_html_e("Title Size",'javospot'); ?></label></dt>
			<dd><input name="jvfrm_spot_fancy[title_size]" id="jvfrm_spot_fancy_field_title_size" type="text" value="<?php echo esc_attr( $jvfrm_spot_fancy->get('title_size', 17) );?>"></dd>
		</dl>
		<dl>
			<dt><label for="jvfrm_spot_fancy_field_title_color"><?php esc_html_e("Title Color",'javospot'); ?></label></dt>
			<dd>
				<input name="jvfrm_spot_fancy[title_color]" type="text" value="<?php echo esc_attr( $jvfrm_spot_fancy->get('title_color', '#000000') );?>" id="jvfrm_spot_fancy_field_title_color" class="wp_color_picker" data-default-color="#000000">
			</dd>
		</dl>
		<dl>
			<dt><label for="jvfrm_spot_fancy_field_subtitle"><?php esc_html_e("Subtitle",'javospot'); ?></label></dt>
			<dd><input name="jvfrm_spot_fancy[subtitle]" id="jvfrm_spot_fancy_field_subtitle" type="text" value="<?php echo esc_attr( $jvfrm_spot_fancy->get('subtitle') );?>"></dd>
		</dl>
		<dl>
			<dt><label for="jvfrm_spot_fancy_field_subtitle_size"><?php esc_html_e("Subtitle Size",'javospot'); ?></label></dt>
			<dd><input name="jvfrm_spot_fancy[subtitle_size]" id="jvfrm_spot_fancy_field_subtitle_size" type="text" value="<?php echo esc_attr( $jvfrm_spot_fancy->get('subtitle_size', 12) );?>"></dd>
		</dl>
		<dl>
			<dt><label for="jvfrm_spot_fancy_field_subtitle_color"><?php esc_html_e("Subtitle color",'javospot'); ?></label></dt>
			<dd><input name="jvfrm_spot_fancy[subtitle_color]" value="<?php echo esc_attr( $jvfrm_spot_fancy->get('subtitle_color', '#000000') );?>" id="jvfrm_spot_fancy_field_subtitle_color" type="text" class="wp_color_picker" data-default-color="#000000"></dd>
		</dl>
		<hr>
		<dl>
			<dt><label for="jvfrm_spot_fancy_field_bg_color"><?php esc_html_e("Background color",'javospot'); ?></label></dt>
			<dd><input name="jvfrm_spot_fancy[bg_color]" value="<?php echo esc_attr( $jvfrm_spot_fancy->get('bg_color', '#FFFFFF') );?>" id="jvfrm_spot_fancy_field_bg_color" type="text" class="wp_color_picker" data-default-color="#ffffff"></dd>
		</dl>
		<dl>
			<dt><label for="jvfrm_spot_fancy_field_bg_image"><?php esc_html_e("Background Image",'javospot'); ?></label></dt>
			<dd>
				<div class="jv-uploader-wrap">
					<input type="text" name="jvfrm_spot_fancy[bg_image]" value="<?php echo esc_attr( $jvfrm_spot_fancy->get('bg_image'));?>" >
					<button type="button" class="button button-primary upload" data-title="<?php esc_attr_e( "Select Background Image", 'javospot' ); ?>" data-btn="<?php esc_attr_e( "Select", 'javospot' ); ?>">
						<span class="dashicons dashicons-admin-appearance"></span>
						<?php esc_html_e( "Select Background Image", 'javospot' ); ?>
					</button>
					<button type="button" class="button remove">
						<?php esc_html_e( "Delete", 'javospot' );?>
					</button>
					<h4><?php esc_html_e("Preview",'javospot'); ?></h4>
					<img src="<?php echo esc_attr( $jvfrm_spot_fancy->get( 'bg_image' ) );?>" style="max-width:500px;">
				</div>
			</dd>
		</dl>
		<dl>
			<dt><label for="jvfrm_spot_fancy_field_position_x"><?php esc_html_e("Position X",'javospot'); ?></label></dt>
			<dd>
				<select name="jvfrm_spot_fancy[bg_position_x]" id="jvfrm_spot_fancy_field_position_x">
					<?php
					foreach( $jvfrm_spot_fancy_dft_opt['background-position-x'] as $label => $value ) {
						echo "<option value=\"{$value}\"".selected( $value == $jvfrm_spot_fancy->get('bg_position_x') ).">{$label}</option>";
					} ?>
				</select>
			</dd>
		</dl>
		<dl>
			<dt><label for="jvfrm_spot_fancy_field_position_y"><?php esc_html_e("Position Y",'javospot'); ?></label></dt>
			<dd>
				<select name="jvfrm_spot_fancy[bg_position_y]" id="jvfrm_spot_fancy_field_position_y">
				<?php
					foreach( $jvfrm_spot_fancy_dft_opt['background-position-y'] as $label => $value ) {
						echo "<option value=\"{$value}\"".selected( $value == $jvfrm_spot_fancy->get('bg_position_y') ).">{$label}</option>";
					} ?>
				</select>
			</dd>
		</dl>
		<hr>
		<dl>
			<dt><label for="jvfrm_spot_fancy_field_fullscreen"><?php esc_html_e("Height(pixel)",'javospot'); ?> </label></dt
			>
			<dd><input name="jvfrm_spot_fancy[height]" id="jvfrm_spot_fancy_field_fullscreen" value="<?php echo (int)$jvfrm_spot_fancy->get('height', 150);?>" type="text"></dd>
		</dl>

	</div>
</div><!-- /#jvfrm_spot_post_header_fancy -->

<div id="jvfrm_spot_post_header_slide">
	<div class="">
		<label class="jvfrm_spot_pmb_option slider op_d_rev active">
			<span class="ico_img"></span>
			<p><input name="jvfrm_spot_opt_slider" type="radio" value="rev" checked="checked"> <?php esc_html_e("Revolution",'javospot'); ?></p>
		</label>
	</div>

	<!-- section  -->
	<div class="jvfrm_spot_pmb_tabs slider jvfrm_spot_pmb_field">
		<div class="jvfrm_spot_pmb_tab active" tab="rev">
			<dl>
				<dt><label><?php esc_html_e("Choose slider",'javospot'); ?></label></dt>
				<dd><?php echo join( "\n", $strOutputSliderLists ); ?></dd>
			</dl>
		</div>
	</div>
</div><!-- /#jvfrm_spot_post_header_slide -->

<table class="widefat">
	<tr>
		<td valign="middle"><?php esc_html_e( "Topbar", 'javospot');?></td>
		<td valign="middle">
			<table class="javo-post-header-meta">
				<tr>
					<td width="5%" valign="middle">
						<select name="jvfrm_spot_hd[topbar]">
							<?php
							foreach( $jvfrm_spot_options[ 'able_disable' ] as $label => $value )
								printf(
									"<option value='{$value}' %s>{$label}</option>"
									, selected( $value == $jvfrm_spot_query->get( 'topbar' ), true, false )
								);
							?>
						</select>
					</td>
					<td width="5%" valign="middle">&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
</table>