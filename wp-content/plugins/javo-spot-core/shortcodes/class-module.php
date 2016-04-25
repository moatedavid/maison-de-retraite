<?php
class Jvfrm_Spot_Module
{

	public $hasVideo				= false;

	protected $lghTitle				= 100;

	protected $lghContent			= 200;

	protected $shortcode_args	= Array();

	public function __construct( $post, $param=Array() )
	{
		if( !is_object( $post ) )
			return;

		$options = shortcode_atts(
			Array(
				'hide_title' => false
				, 'hide_content' => false
				, 'hide_meta' => false
				, 'hide_thumbnail' => false
				, 'hide_avatar'			=> false
				, 'thumbnail_size'		=> false
				, 'length_title'		=> false
				, 'length_content'		=> false
				, 'no_lazy'				=> false
			)
			, $param
		);

		$this->shortcode_args		= apply_filters( 'jvfrm_spot_module_shortcode_args', Array() );

		foreach( $options as $key => $value )
			$this->$key				= $value;

		if( $this->length_title )
			$this->lghTitle			= intVal( $this->length_title );

		if( $this->length_content || $this->length_content === 0 )
			$this->lghContent		= intVal( $this->length_content );

		if( $this->getArgs( 'hide_thumbnail' ) == 'hide' )
			$this->hide_thumbnail	= true;

		if( $this->getArgs( 'hide_avatar' ) )
			$this->hide_avatar		= true;

		if( $this->getArgs( 'thumbnail_size' ) )
			$this->thumbnail_size	= $this->getArgs( 'thumbnail_size' );

		$this->lghContent			= apply_filters( 'jvfrm_spot_core_module_excerpt_length', $this->lghContent );

		if( 0 === $this->lghContent )
			$this->hide_content		= true;

		$this->post					= $post;
		$this->post_id				= $this->post->ID;
		$this->authorID			= $this->post->post_author;
		$this->author				= new WP_User( $this->authorID );
		$this->date					= get_the_date( get_option( 'date_format' ), $this->post_id  );

		$this->hasVideo			= $this->check_video();

		// Post
		$this->permalink			= get_permalink( $this->post_id );
		$this->title					= $this->get_title();
		$this->title_text			= wp_trim_words( $this->post->post_title , $this->lghTitle );
		$this->excerpt				= $this->hide_content ? false :
			sprintf( "<span class='meta-excerpt'>%s</span>",
				wp_trim_words( $post->post_content, $this->lghContent, '...' )
		);

		/*$this->excerpt				= sprintf(
			apply_filters(
				'jvfrm_spot_' . get_class( $this ) . '_core_module_excerpt_after',
				$this->excerpt, $this
			)
		);*/

		// User
		$this->avatar				= $this->hide_avatar ? false : get_avatar( $this->authorID );
		$this->author_name	= $this->author->display_name;
	}

	public function getArgs( $key='', $default=false ){

		if( is_array( $this->shortcode_args ) ){
			if( array_key_exists( $key, $this->shortcode_args ))
				$default = $this->shortcode_args[ $key ];
		}

		return $default;
	}

	public function is_featured() {
		return get_post_meta( $this->post_id, '_featured_item', true ) == '1';
	}

	public function check_video() {
		$isValue	= false;
		if( !function_exists( 'lava_directory_video' ) )
			return $isValue;
		$isValue	= get_post_meta( $this->post_id, '_video_id', true );
		return !empty( $isValue );
	}

	public function get_featured_html() {
		return;
		$strFeatured		= apply_filters(
			'jvfrm_spot_module_featured_item_string',
			'<i class="fa fa-star"></i>',
			get_class( $this )
		);
		return apply_filters(
			'jvfrm_spot_module_featured_item_html',
			'<div class="label-ribbon-row">
				<div class="label-info-ribbon-row-wrapper">
					<div class="label-info-ribbon-row">
						<div class="ribbons" id="ribbon-15">
							<div class="ribbon-wrap">
								<div class="content">
									<div class="ribbon">
										<span class="ribbon-span">' . $strFeatured . '</span>
									</div>
								</div><!-- /.content -->
							</div><!-- /.ribbon-wrap -->
						</div><!-- /.ribbons -->
					</div><!-- /.label-info-ribbon -->
				</div><!-- /.ribbon-wrapper -->
			</div>',
			get_class( $this )
		);
	}

	public function get_title()
	{
		if( $this->hide_title )
			return false;

		$strOutput	= join(
			false, Array(
				apply_filters( 'jvfrm_spot_module_title_before', '', get_class( $this ), $this ),
				$this->title_before(),
				wp_trim_words( get_the_title( $this->post_id ), $this->lghTitle ),
				apply_filters( 'jvfrm_spot_module_title_after', '', get_class( $this ), $this ),
			)
		);
		return sprintf( "<a href=\"%s\">%s</a>", $this->permalink, $strOutput );
	}

	public function title_before() {
		$strBeforeTitle			= Array();
		if( $this->hasVideo ) {
			$strBeforeTitle[]	= '<i class="fa fa-video-camera"></i>&nbsp;';
		}
		return join( false, $strBeforeTitle );
	}

	public function thumbnail( $sizeName='thumbnail', $div_holder=false, $responseive=true, $classes='' )
	{
		$strOutput				= $strAttribute = $strImgTagAppend = '';
		$arrClasses				= Array();

		if( $this->hide_thumbnail )
			return $strOutput;

		if( $this->thumbnail_size )
			$sizeName			= $this->thumbnail_size;

		if( jvfrm_spot_tso()->get( 'lazyload' ) != 'disable' && !$this->no_lazy )
			$arrClasses[]		= 'jv-lazyload';


		$arrThumbnailID		= get_post_thumbnail_id( $this->post_id );
		$arrThumbnailMeta	= wp_get_attachment_image_src( $arrThumbnailID, $sizeName );
		$strNoImage			= apply_filters( 'jvfrm_spot_no_image', jvfrm_spot_tso()->get('no_image')!='' ?  jvfrm_spot_tso()->get('no_image') : JVFRM_SPOT_IMG_DIR.'/blank-image.png' );
		$is_not_found_image	= empty( $arrThumbnailMeta[0] );
		$returnImage		= ! $is_not_found_image ? $arrThumbnailMeta[0] : $strNoImage;
		$thumbnailAlt		= $this->title_text;

		if( is_array( $sizeName ) && !empty( $sizeName[0] ) && is_numeric( $sizeName[0] ) )
			$strImgTagAppend		= " width=\"{$sizeName[0]}\" ";

		/*
		if( !empty( $arrThumbnailMeta[1] ) )
			$strImgTagAppend	.= " width=\"{$arrThumbnailMeta[1]}\" ";

		if( !empty( $arrThumbnailMeta[2] ) )
			$strImgTagAppend	.= " height=\"{$arrThumbnailMeta[2]}\" ";
		*/

		if( $this->no_lazy )
			$strAttribute			= ' data-no-lazy="true"';

		if( $is_not_found_image )
			$arrClasses[]			= 'no-image';

		if( $div_holder ) {
			$arrClasses[]			= 'javo-thb';
			$strOutput				= "<div class=\"%s\" style=\"background-image:url(%s);\"{$strAttribute}></div>";
		}else{
			if( $responseive )
				$arrClasses[]		= 'img-responsive';
				$strOutput			= "<img class=\"%s\" src=\"%s\"  {$strImgTagAppend} alt=\"{$thumbnailAlt}\"{$strAttribute}>";
		}
		$arrClasses[]				= trim( $classes );

		return sprintf(
			apply_filters( 'jvfrm_spot_' . get_class( $this ) . '_core_module_thumbnail_after', $strOutput, $this ),
			implode( ' ', $arrClasses ),
			$returnImage
		);
	}

	public function classes( $classes='' )
	{
		$arrClasses			= Array( 'module' );
		$arrClasses[]		= $classes;
		$arrClasses[]		= 'javo-' . get_class( $this  );
		$arrClasses[]		= 'post-' . $this->post_id;
		$arrClasses[]		= 'status-' . $this->post->post_status;
		$arrClasses[]		= 'type-' . $this->post->post_type;

		if( $this->hide_avatar )
			$arrClasses[]	= 'hide-avatar';

		if( $this->is_featured() )
			$arrClasses[]	= 'featured';

		$strClasses	= @implode( ' ', (array) apply_filters( 'jvfrm_spot_module_css', $arrClasses, get_class( $this ) ) );

		echo " class=\"{$strClasses}\" data-post-id=\"{$this->post_id}\" ";
	}

	public function get_meta( $key, $default_value=false ) {
		$strOutput	= get_post_meta( $this->post_id, $key, true );
		return empty( $strOutput ) ? $default_value : $strOutput;
	}

	public function m( $key, $value=false ){
		return $this->get_meta( $key, $value );
	}

	public function get_term( $taxonomy=false, $sep=', ' )
	{
		$output_terms = Array();
		if( $terms = wp_get_object_terms( $this->post_id, $taxonomy, Array( 'fields' => 'names' ) ) )
		{
			$output_terms = is_array( $terms ) ? join( $sep, $terms ) : null;
			$output_terms = trim( $output_terms );
			// $output_terms = substr( $output_terms, 0, -1 );
		}else{
			$output_terms = '';
		}
		return $output_terms;
	}

	public function c( $taxonomy=false, $default='',  $single=true, $sep=', ' )
	{
		$strTerms	= $this->get_term( $taxonomy, $sep );

		if( $single && !empty( $strTerms  ) ) {
			$strTerms	= @explode( $sep, $strTerms );
			$strTerms	= isset( $strTerms[0] ) ? $strTerms[0] : '';
		}
		return empty( $strTerms ) ? $default : $strTerms;
	}

	public function category()
	{
		return $this->c(
			apply_filters( 'jvfrm_spot_' . get_class( $this ) . '_featured_tax', 'category', $this->post_id )
			, apply_filters( 'jvfrm_spot_' . get_class( $this ) . '_featured_no_tax', __( "No Category", 'javo' ), $this->post_id )
		);
	}

	public function addtionalInfo( $classes=null )
	{
		if( $this->hide_meta )
			return;

		$arrAdditional_args	= Array(
			'meta-date'			=> Array(
				'icon'					=> ''
				, 'value'				=> $this->date
			)
			, 'meta-author'		=> Array(
				'icon'					=> 'glyphicon glyphicon-user'
				, 'value'				=> $this->author_name
			)
		);
		$arrAdditional			= apply_filters( 'jvfrm_spot_' . get_class( $this ) . '_additional_meta', $arrAdditional_args, $this );

		// Output
		$arrOutput			=$arrClass = Array();
		$arrClass[]			= 'module-meta';
		$arrClass[]			= 'list-inline';

		if( !empty( $classes ) )
			$arrClass[]		= $classes;

		$arrOutput[]		= sprintf( "<ul class=\"%s\">", implode( ' ', $arrClass ) );
		if( !empty( $arrAdditional ) ) foreach( $arrAdditional as $key => $meta )
			$arrOutput[]	= "<li class=\"{$key}\"><i class=\"{$meta['icon']}\"></i> {$meta['value']}</li>";
		$arrOutput[]		= "</ul>";

		return implode( "\n", $arrOutput );
	}

	public function moreInfo(){
		do_action( 'jvfrm_spot_' . get_class( $this ) . '_shortcode_more_meta', $this );
	}

	public function hover_layer(){
		?>
		<div class="three-inner-detail">
			<a href="<?php echo $this->permalink; ?>">
				<i class="fa fa-search"></i>
			</a>
		</div>

		<?php
		if( function_exists( 'lava_favorite_button' ) ) :

			echo '<div class="three-inner-detail">';
			lava_favorite_button(
				Array(
					'post_id' => $this->post_id,
					'save' => "<i class='fa fa-heart-o'></i>",
					'unsave' => "<i class='fa fa-heart'></i>"
				)
			);
			echo '</div>';
		endif;
		do_action( 'jvfrm_spot_module_hover_content', get_class( $this ), $this );
	}

	public function before(){
		if( $this->is_featured() )
			echo $this->get_featured_html();
		do_action( 'jvfrm_spot_module_html_before', get_class( $this ), $this );
	}

	public function after() {
		do_action( 'jvfrm_spot_module_html_after', get_class( $this ), $this );
	}
}