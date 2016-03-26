<?php
class jvfrm_spot_category_box{

	public static $shortcode_loaded = false;

	public function __construct(){
		add_shortcode( 'jvfrm_spot_category_box'	, Array( $this, 'parse_shortcode' ) );
		add_action( 'admin_footer'				, Array( $this, 'jvfrm_spot_backend_scripts_func' ) );
	}

	public function parse_shortcode( $atts, $content='' ) {
		return $this->rander(
			shortcode_atts(
				Array(
					'column' => '1-3',
					'jvfrm_spot_featured_block_title' => '',
					'jvfrm_spot_featured_block_description' => '',
					'text_color' => '#fff',
					'text_sub_color' => '#fff',
					'overlay_color' => '#34495e',
					'jvfrm_spot_featured_block_id' =>'',
					'jvfrm_spot_featured_block_param'	=> '',
					'attachment_other_image' => '',
					'map_template'			=> '',
					'feataured_label' => ''
				), $atts
			)
		);
	}

	public static function jvfrm_spot_backend_scripts_func()
	{
		if( ! self::$shortcode_loaded )
			return;

		ob_start(); ?>
		<script type="text/javascript">jQuery(function(e){e(document).on("change","select[name='jvfrm_spot_featured_block_id']",function(){var t=e(this).closest(".wpb-edit-form").find('input[name="jvfrm_spot_featured_block_title"]');t.val(e(this).find(":selected").text())})});</script>
		<?php ob_end_flush();
	}

	public static function rander( $params )
	{
		global $jvfrm_spot_tso;
		extract( $params );

		$output_link			= esc_url(
			apply_filters( 'jvfrm_spot_wpml_link', $map_template ) . $jvfrm_spot_featured_block_param
		);

		$strImageSize		= 'full';
		$strClassName		= 'javo-image-full-size';
		$is_post				= '' == $attachment_other_image;

		if( $column == '1-3' ) {
			$strImageSize	= 'jvfrm-spot-large';
			$strClassName	= 'javo-image-min-size';
		}elseif( $column == '2-3' ) {
			$strImageSize	= 'jvfrm-spot-item-detail';
			$strClassName	= 'javo-image-middle-size';
		}

		if( $is_post ) {
			$jvfrm_spot_this_attachment_meta = get_the_post_thumbnail( $jvfrm_spot_featured_block_id, $strImageSize );
		}else{
			$jvfrm_spot_this_attachment_meta = wp_get_attachment_image( $attachment_other_image, $strImageSize );
		}

		self::$shortcode_loaded = true;

		ob_start();
		?>
		<div class="javo-featured-block <?php echo $strClassName; ?>">
			<a href="<?php echo $output_link; ?>">
				<?php echo $jvfrm_spot_this_attachment_meta; ?>
				<div class="javo-image-overlay" style="background:<?php echo $overlay_color; ?>"></div>
				<div class="javo-text-wrap">
					<h4 style="color:<?php echo $text_color; ?>"><?php echo $jvfrm_spot_featured_block_title; ?></h4>
					<div class="jvfrm_spot_text_description-wrap">
						<span class="jvfrm_spot_text_description" style="color:<?php echo $text_sub_color; ?>"><?php echo $jvfrm_spot_featured_block_description; ?></span>
					</div>
				</div> <!--javo-text-wrap -->
			</a>
		</div>

		<?php
		wp_reset_query();
		$content = ob_get_clean();
		return $content;
	}
}
new jvfrm_spot_category_box;