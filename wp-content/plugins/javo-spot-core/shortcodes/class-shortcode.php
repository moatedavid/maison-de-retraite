<?php
if( !class_exists( 'JvfrmSpot_ShortcodeParse' ) && class_exists( 'Javo_Spot_Core' ) ) :

	class JvfrmSpot_ShortcodeParse
	{

		protected $sID;

		protected $attr = Array();

		protected $query = NULL;

		protected $fixCount = false;

		private $prefix = false;

		public $enq_prefix = '';

		public function __construct( $attr=Array() )
		{
			$this->prefix	= Javo_Spot_Core::get_instance()->template . '_';
			$this->attr		= shortcode_atts(
				apply_filters( $this->prefix . 'shortcodes_atts' ,
					Array(
						// Default Parametter
						'title'							=> '',
						'subtitle'					=> '',
						'css'							=> '',
						'author'						=> null,
						'post_type'					=> 'post',
						'count'						=> '',
						'taxonomy'					=> '',
						'term_id'						=> '',
						'columns'						=> 1,
						'paged'						=> 1,
						'pagination'					=> '',
						'thumbnail_size'				=> '',
						'order_'						=> false,
						'order_by'					=> '',
						'filter_by'					=> '',
						'filter_style'				=> 'general',
						'loading_style'				=> '',
						'custom_filter'				=> '',
						'hide_filter'					=> false,
						'primary_color'				=> '',
						'primary_font_color'			=> '',
						'primary_border_color'		=> '',
						'custom_filter_by_post'		=> '',
						'post_title_font_color'		=> '',
						'post_title_font_size'		=> false,
						'post_title_transform'		=> 'inherit',
						'post_meta_font_color'		=> '',
						'post_describe_font_color'	=> '',
						'display_category_tag'		=> '',
						'display_post_border'			=> '',
						'category_tag_color'			=> '#454545',
						'category_tag_hover_color'	=> '',
						'slide_wide'					=> 0,
						'module_contents_hide'		=> ''	,
						'module_contents_length'		=> '',
						'hide_thumbnail'				=> '',
						'hide_avatar'					=> false,
						'hover_style' => '',
						'is_dashboard' => false,
					)
				)
				, $attr
			);

			$this->attr[ 'shortcode_name' ]	= get_class( $this );
			$this->sID									= 'jvfrm_spot_scd' . md5( wp_rand( 0 , 500 ) .time() );
			if( ! empty( $this->attr ) ) foreach( $this->attr as $key => $value )
				$this->$key	 = $value;

			if( !empty( $this->subtitle ) && $this->filter_style == 'paragraph' )
				$this->title = sprintf( "<div class='sc-subtitle'>%s</div><div class='sc-title'>%s</div>", $this->subtitle, $this->title );

			add_filter( 'jvfrm_spot_core_module_excerpt_length', Array( $this, 'trim_string' ) );
			add_filter( 'jvfrm_spot_module_shortcode_args', Array( $this, 'sendArgs' ) );
			do_action( 'jvfrm_spot_parsed_shortcode', $this );
		}

		public function getID(){
			return $this->sID;
		}

		public function get_post() {

			$arrStickyPosts = get_option( 'sticky_posts' );

			$arrPostsArgs				= Array(
				'post_type'				=> 'post',
				'post_status'				=> 'publish',
				'posts_per_page'		=> 10,
				'post__not_in'			=> $arrStickyPosts,
				'paged'						=> 1,
			);

			if( $this->post_type )
				$arrPostsArgs[ 'post_type' ]			= $this->post_type;

			if( $count = intVal( $this->count ) )
				$arrPostsArgs[ 'posts_per_page' ]	= $count;

			if( $this->fixCount )
				$arrPostsArgs[ 'posts_per_page' ]	= intVal( $this->fixCount );

			if(
				!empty( $this->filter_by ) &&
				!empty( $this->custom_filter_by_post ) &&
				!empty( $this->custom_filter )
			) {
				if( (boolean) $this->custom_filter_by_post ) {
					$arrCustomFilter = $arrInclude = $arrExclude = Array();
					$arrCustomFilter = @explode( ',', $this->custom_filter );
					if( !empty( $arrCustomFilter ) ) : foreach( $arrCustomFilter as $terms  )
						if( intVal( $terms ) > 0 ) {
							$arrInclude[] = intVal( $terms );
						}elseif( intVal( $terms ) < 0 ){
							$arrExclude[] = intVal( abs( $terms ) );
						}
					endif;
				}

				$arrPostsArgs[ 'tax_query' ]		= Array();

				if( !empty( $arrInclude ) ) $arrPostsArgs[ 'tax_query' ][] = Array(
					'taxonomy'						=> $this->filter_by
					, 'field'								=> 'term_id'
					, 'terms'							=> $arrInclude
				);
				if( !empty( $arrExclude ) ) $arrPostsArgs[ 'tax_query' ][] = Array(
					'taxonomy'						=> $this->filter_by
					, 'operator'							=> 'NOT IN'
					, 'field'								=> 'term_id'
					, 'terms'							=> $arrExclude
				);
			}

			if(
				$this->taxonomy != '' &&
				intVal( $this->term_id ) > 0 ) {
				$arrPostsArgs[ 'tax_query' ]		= Array(
					Array(
						'taxonomy'						=> $this->taxonomy
						, 'field'								=> 'term_id'
						, 'terms'							=> $this->term_id
					)
				);
			}

			if( $this->order_by ) {
				$arrPostsArgs[ 'orderby' ]		= $this->order_by;
				if( $this->order_ ) {
					$arrPostsArgs[ 'order' ]		= strtoupper( $this->order_ );
				}
			}

			if( intVal( $this->paged ) > 0 )
				$arrPostsArgs[ 'paged' ]				= $this->paged;

			if( intVal( $this->author ) > 0 )
				$arrPostsArgs[ 'author' ]				= $this->author;

			$arrPostsArgs = apply_filters( $this->prefix . 'shotcode_query', $arrPostsArgs, $this );
			$this->query = new WP_Query( $arrPostsArgs );
			return $this->query->posts;
		}

		public function classes( $classes='', $exclude_object=false )
		{
			$arrClasses	= Array( 'javo-shortcode' );

			if( !empty( $classes ) )
				$arrClasses[]	= $classes;

			if( !$exclude_object )
				$arrClasses[]	= 'shortcode-' . get_class( $this );

			if(
				$this->title == '' &&
				( $this->filter_by == '' || $this->hide_filter )
			){
				$arrClasses[]	= 'header-hide';
			}elseif( $this->title == '' ){
				$arrClasses[]	= 'title-hide';
			}elseif( $this->filter_by == '' ){
				$arrClasses[]	= 'filter-hide';
			}

			if( !empty( $this->filter_style ) )
				$arrClasses[]	= 'filter-' . $this->filter_style;

			if( !empty( $this->loading_style ) )
				$arrClasses[]	= 'loader-' . $this->loading_style;

			if( !empty( $this->post_type ) )
				$arrClasses[]	= 'type-' . $this->post_type;

			if( !empty( $this->hover_style ) )
				$arrClasses[]	= 'module-hover-' . $this->hover_style;

			if( !empty( $this->slide_wide ) )
				$arrClasses[]	= 'slide-wide';

			if( !empty( $this->hide_thumbnail ) )
				$arrClasses[]	= 'thumbnail-hide';

			if( defined( 'VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG' ) && function_exists( 'vc_shortcode_custom_css_class' ) ){
				$arrClasses[] = apply_filters(
					VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG,
					vc_shortcode_custom_css_class( $this->css, ' ' ),
					get_class( $this ),
					$this->attr
				);
			}


			$strClasses	= join( ' ', (array) apply_filters( 'jvfrm_spot_shortcode_class', $arrClasses, get_class( $this ), $this ) );

			return " class=\"{$strClasses}\" ";
		}

		public function sHeader()
		{
			global $jvfrm_spot_tso;

			add_action( 'wp_footer', Array( $this, 'enqueue' ) );

			$arrCustomCSS				= Array();
			$general_prefix				= ".filter-general #{$this->sID}" . ' ';
			$linear_prefix				= ".filter-linear #{$this->sID}" . ' ';
			$paragraph_prefix			= ".filter-paragraph #{$this->sID}" . ' ';

			// Primary Color
			if( $css = ( !empty( $this->primary_color ) ? $this->primary_color : $jvfrm_spot_tso->get( 'total_button_color', '#000' ) ) ) {

				$primaryHex				= apply_filters( 'jvfrm_spot_rgb', substr( $css, 1 ) );
				$intColorR				= empty( $primaryHex[ 'r' ]  )	? 0 : $primaryHex[ 'r' ];
				$intColorG				= empty( $primaryHex[ 'g' ]  )	? 0 : $primaryHex[ 'g' ];
				$intColorB				= empty( $primaryHex[ 'b' ]  )	? 0 : $primaryHex[ 'b' ];

				/* Header */
				$arrCustomCSS[]			= "#{$this->sID} .shortcode-header{ border-color:{$css}; }";
				$arrCustomCSS[]			= "#{$this->sID} .shortcode-header .shortcode-title{ background-color:{$css}; }";

				$arrCustomCSS[]			= "#{$this->sID} div.shortcode-output .primary-bg,";
				$arrCustomCSS[]			= "#{$this->sID} div.shortcode-output .primary-bg-a > a";
				/** ----------------------------  */
				$arrCustomCSS[]			= "{ background-color:{$css}; }";

				/* Primary color font */
				$arrCustomCSS[]			= "#{$this->sID} div.shortcode-output .primary-color-font{ color:{$css} !important; }";

				/* Featured Category */
				// $arrCustomCSS[]			= "#{$this->sID} .shortcode-output .thumb-wrap .meta-status";
				/** ----------------------------  */
				// $arrCustomCSS[]			= "{ background-color:{$css} !important; }";

				/* Login Form */
				$arrCustomCSS[]			= "form#{$this->sID}_login_form > p.login-submit > input[type='submit']";
				/** ----------------------------  */
				$arrCustomCSS[]			= "{ background-color:{$css} !important; }";

				/* Rating */
				$arrCustomCSS[]			= "#{$this->sID} .shortcode-output .meta-rating-nomeric{ background-color:{$css}; }";
				$arrCustomCSS[]			= ".display-rating-garde > #{$this->sID} .shortcode-output .module.media > .media-left > a:before,";
				$arrCustomCSS[]			= ".display-rating-garde > #{$this->sID} .shortcode-output .module > .thumb-wrap:after";
				/** ----------------------------  */
				$arrCustomCSS[]			= "{ background-color:rgba( {$intColorR}, {$intColorG}, {$intColorB}, .9); }";

				/* Price */
				$arrCustomCSS[]			= "#{$this->sID} .shortcode-output .module.javo-module1 .media-body .meta-price,";
				$arrCustomCSS[]			= "#{$this->sID} .shortcode-output .meta-wrap .meta-price";
				/** ----------------------------  */
				$arrCustomCSS[]			= "{ background-color:{$css}; }";

				/* Fade */
				$arrCustomCSS[]			= "#{$this->sID} .shortcode-output .thumb-wrap:hover .javo-thb:after{ background-color:rgba( {$intColorR}, {$intColorG}, {$intColorB}, .92); }";

				/* Pagination */
				$arrCustomCSS[]			= "#{$this->sID} .shortcode-output .page-numbers.loadmore:hover,";
				$arrCustomCSS[]			= "#{$this->sID} .shortcode-output ul.pagination > li > a:hover,";
				$arrCustomCSS[]			= "#{$this->sID} .shortcode-output ul.pagination > li.active > a,";
				$arrCustomCSS[]			= "#{$this->sID} .shortcode-output ul.pagination > li.active > a:hover,";
				$arrCustomCSS[]			= "#{$this->sID} .shortcode-output ul.pagination > li.active > a:focus,";
				$arrCustomCSS[]			= "#{$this->sID} .shortcode-output ul.pagination > li.active > span,";
				$arrCustomCSS[]			= "#{$this->sID} .shortcode-output ul.pagination > li.active > span:hover,";
				$arrCustomCSS[]			= "#{$this->sID} .shortcode-output ul.pagination > li.active > a:focus";
				/** ----------------------------  */
				$arrCustomCSS[]			= "{ color:#fff; background-color:{$css} !important; border-color:{$css}!important;} ";

				switch( $this->filter_style )
				{
					// Filter General
					case 'general' :
						$arrCustomCSS[]	= $general_prefix . ".shortcode-header .shortcode-nav ul li.current{ color:{$css}; }";
						$arrCustomCSS[]	= $general_prefix . ".shortcode-header .shortcode-title{ background-color:{$css}; }";
						break;

					// Filter Linear
					case 'linear' :
						$arrCustomCSS[]	= $linear_prefix . ".shortcode-header .shortcode-title{ background-color:transparent; }";
						$arrCustomCSS[]	= $linear_prefix . ".shortcode-header .shortcode-nav ul li:hover{ border-color:{$css}; }";
						$arrCustomCSS[]	= $linear_prefix . ".shortcode-header .shortcode-nav ul li.current{ border-color:{$css}; }";
						break;

					// Filter Paragraph
					case 'paragraph' :
						$arrCustomCSS[]	= $paragraph_prefix . ".shortcode-header .shortcode-title{ background-color:transparent; }";
						$arrCustomCSS[]	= $paragraph_prefix . ".shortcode-header .shortcode-nav ul li.active{ border-color:{$css}; }";
						break;
				}
				if( get_class( $this ) == 'jvfrm_spot_vblock1' ){
					$arrCustomCSS[] = ".shortcode-jvfrm_spot_vblock1 > #{$this->sID}{ border-top-color:{$css}; }";
					$arrCustomCSS[] = ".shortcode-jvfrm_spot_vblock1 > #{$this->sID} > .shortcode-content > .shortcode-nav > .shortcode-filter > li.current,";
					$arrCustomCSS[] = ".shortcode-jvfrm_spot_vblock1 > #{$this->sID} > .shortcode-content > .shortcode-nav > .shortcode-filter > li:not(.current):hover";
					$arrCustomCSS[] = "{ background-color:{$css} !important; color:#fff !important; }";
					$arrCustomCSS[] = ".shortcode-jvfrm_spot_vblock1 > #{$this->sID} > div.shortcode-output .javo-module4 .jv-hover-back-info";
					/** ----------------------------  */
					$arrCustomCSS[] = "{background-color:{$css};}";
				}

			}

			// Primary Border Color
			if($css =  ( !empty( $this->primary_border_color ) ? $this->primary_border_color :  $jvfrm_spot_tso->get( 'total_button_border_color', false ) ) ){
				$arrCustomCSS[] = "html body #{$this->sID} .shortcode-output .jv-button-transition,";
				$arrCustomCSS[] = "html body #{$this->sID} .shortcode-output .jv-button-transition:hover,";
				$arrCustomCSS[] = "html body #{$this->sID} .shortcode-output .admin-color-setting,";
				$arrCustomCSS[] = "html body #{$this->sID} .shortcode-output .admin-color-setting:hover";
				/** ----------------------------  */
				$arrCustomCSS[] = "{ border-color:{$css} !important; }";

				/* Login Form */
				$arrCustomCSS[] = "form#{$this->sID}_login_form > p.login-submit > input[type='submit']";
				/** ----------------------------  */
				$arrCustomCSS[] = "{ border-color:{$css} !important; }";
			}

			// Primary Font Color
			if( $css = ( !empty( $this->primary_font_color ) ? $this->primary_font_color : false ) ) {
				/* Header */
				$arrCustomCSS[] = "div#{$this->sID} .shortcode-header  .shortcode-title{ color:{$css}; }";

				/* Filters */
				switch( $this->filter_style ) {
					case 'linear' :
						$arrCustomCSS[]	= $linear_prefix . ".shortcode-header .shortcode-title{ color:{$css}; }";
						break;
				}

				/* Featured */
				$arrCustomCSS[] = "div#{$this->sID} .shortcode-output .module.javo-module1 .media-body .author-name > span,";
				$arrCustomCSS[] = "div#{$this->sID} .shortcode-output .module.javo-module8 .meta-price-prefix,";
				/** ----------------------------  */
				$arrCustomCSS[] = "{ color:{$css}; }";

				/* Block 6 */
				$arrCustomCSS[] = "#{$this->sID} .shortcode-output .jv-thumb .meta-category{color:{$css};}";
			}

			// Post Title Font Color
			if( $css = ( !empty( $this->post_title_font_color ) ? $this->post_title_font_color : false ) ) {
				$arrCustomCSS[] = "div#{$this->sID} .shortcode-output  .meta-title,";
				$arrCustomCSS[] = "div#{$this->sID} .shortcode-output  .meta-title a,";
				$arrCustomCSS[] = "div#{$this->sID} .shortcode-output  .media-heading,";
				$arrCustomCSS[] = "div#{$this->sID} .shortcode-output  .media-heading a";
				/** ----------------------------  */
				$arrCustomCSS[] = "{ color:{$css} !important; }";
			}

			// Post Meta Font Color
			if( $css = ( !empty( $this->post_meta_font_color ) ? $this->post_meta_font_color : false ) ) {
				$arrCustomCSS[] = "div#{$this->sID} .shortcode-output .module-meta,";
				$arrCustomCSS[] = "div#{$this->sID} .shortcode-output .module-meta li,";
				$arrCustomCSS[] = "div#{$this->sID} .shortcode-output .module-meta li i,";
				$arrCustomCSS[] = "div#{$this->sID} .shortcode-output .module-meta li a,";
				$arrCustomCSS[] = "div#{$this->sID} .shortcode-output .module-meta a";
				/** ----------------------------  */
				$arrCustomCSS[] = "{ color:{$css} !important; }";
			}

			// Post Describe Font Color
			if( $css = ( !empty( $this->post_describe_font_color ) ? $this->post_describe_font_color : false ) ) {
				$arrCustomCSS[] = "div#{$this->sID} .shortcode-output .meta-excerpt,";
				$arrCustomCSS[] = "div#{$this->sID} .shortcode-output .meta-excerpt a";
				/** ----------------------------  */
				$arrCustomCSS[] = "{ color:{$css} !important; }";
			}

			// Category Tag Color
			if( $css = ( !empty( $this->category_tag_color ) ? $this->category_tag_color : false ) ) {
				$arrCustomCSS[] = "div#{$this->sID} .shortcode-output .meta-category:not(.no-background)";
				/** ----------------------------  */
				$arrCustomCSS[] = "{ background-color:{$css} !important; color:#fff !important; }";
			}

			// Category Tag hover Color
			if( $css = ( !empty( $this->category_tag_hover_color ) ? $this->category_tag_hover_color : false ) ) {
				$arrCustomCSS[] = "div#{$this->sID} .shortcode-output .module:hover .meta-category:not(.no-background)";
				/** ----------------------------  */
				$arrCustomCSS[] = "{ background-color:{$css} !important; color:#fff !important; }";
			}

			// Visibile / hidden Category Tag
			if( 'hide' == ( !empty( $this->display_category_tag ) ? $this->display_category_tag : false ) ) {
				$arrCustomCSS[] = "div#{$this->sID} .shortcode-output .meta-category";
				/** ----------------------------  */
				$arrCustomCSS[] = "{ display:none !important; visibility:hidden  !important; }";
			}

			// Post Title Font Size
			if( $css = ( !empty( $this->post_title_font_size ) ? $this->post_title_font_size : false ) ) {
				$arrCustomCSS[] = "div#{$this->sID} .shortcode-output h4.meta-title,";
				$arrCustomCSS[] = "div#{$this->sID} .shortcode-output h4.meta-title a";
				/** ----------------------------  */
				$arrCustomCSS[] = "{ font-size:{$css}px !important; }";
			}

			// Post Title Transform
			if( $css = ( !empty( $this->post_title_transform ) ? $this->post_title_transform : false ) ) {
				$arrCustomCSS[] = "div#{$this->sID} .shortcode-output h4.meta-title";
				/** ----------------------------  */
				$arrCustomCSS[] = "{ text-transform:{$css} !important; }";
			}

			// Display Post Border
			if( '1' == $this->display_post_border ) {
				$arrCustomCSS[] = "div#{$this->sID} .shortcode-output .module";
				/** ----------------------------  */
				$arrCustomCSS[] = "{ border-color: transparent !important; }";
			}

			$arrCustomCSS		= apply_filters( 'jvfrm_spot_shortcode_css', $arrCustomCSS, $this );
			$strCustomCSS		= join( false, $arrCustomCSS );

			printf( "\n<style type=\"text/css\">\n%s\n</style>\n<div %s>", $strCustomCSS, $this->classes() );
		}

		public function sFilter()
		{
			if( empty( $this->filter_by ) )
				return;

			if( $this->hide_filter ) {

			}else{
				if( taxonomy_exists( $this->filter_by ) ) {
					$arrCustomFilter = $arrInclude = $arrExclude = Array();
					if( (boolean) $this->custom_filter_by_post ) {
						$arrCustomFilter = @explode( ',', $this->custom_filter );
						if( !empty( $arrCustomFilter ) ) : foreach( $arrCustomFilter as $terms  )
							if( intVal( $terms ) > 0 ) {
								$arrInclude[] = intVal( $terms );
							}else{
								$arrExclude[] = intVal( $terms );
							}
						endif;
					}
					$htmlFilter				= Array();
					$objTerms				= get_terms(
						$this->filter_by
						, Array(
							'fields'				=> 'id=>name'
							, 'include'			=> $arrInclude
							, 'exclude'			=> $arrExclude
						)
					);
					$htmlFilter[]				= "<ul data-tax=\"{$this->filter_by}\" class=\"shortcode-filter\"" . ' ';
					$htmlFilter[]				= sprintf( "data-more=\"%s\" data-mobile=\"%s\">", __( "More", 'javo' ), __( "Filter", 'javo' ) );
					$htmlFilter[]				= sprintf( "<li class='current'>%s</li>", __( "All", 'javo' ) );
						if( !empty( $objTerms ) ) foreach( $objTerms as $tID => $tName )
							$htmlFilter[]		= "<li data-term=\"{$tID}\">{$tName}</li>";
					$htmlFilter[]				= "<li class> More </li>";
					$htmlFilter[]				= "</ul>";
					echo @implode( "\n", $htmlFilter );
				}
			}
		}

		public function sParams(){
			$serArgs			= json_encode( $this->attr );
			$htmlScripts		= Array();
			$htmlScripts[]	= "<script type=\"text/javascript\" id=\"js-{$this->sID}\">";
			$htmlScripts[]	= "jQuery( function($){";
			$htmlScripts[]	= sprintf( "document.ajaxurl =\"%s\";", admin_url( 'admin-ajax.php' ) );
			$htmlScripts[]	= "$.jvfrm_spot_ajaxShortcode( '{$this->sID}', {$serArgs}); });";
			$htmlScripts[]	= "</script>";
			echo implode( "\n", $htmlScripts );
		}

		public function trim_string( $intLength )
		{
			if( !empty( $this->module_contents_hide ) )
				return 0;

			if( intVal( $this->module_contents_length ) > 0 || $this->module_contents_length === '0' )
				$intLength = intVal( $this->module_contents_length );
			return $intLength;
		}

		public function sendArgs( $args ) {
			return wp_parse_args( $this->attr, $args );
		}

		public function pagination() {

			$cntMax_PageNumber	= intVal( $this->query->max_num_pages );
			$cntCurrent_Number		= max( 1, intVal( $this->paged ) ) ;
			$strMoreButton				= '';

			if( empty( $this->pagination ) )
				return;

			echo "<div class='col-md-12 jv-pagination text-center'>";
			switch( $this->pagination ) :

				case 'loadmore' :

					if( $cntCurrent_Number < $cntMax_PageNumber )
					{
						$cntCurrent_NumberUP = $cntCurrent_Number+1;
						$strMoreButton = "
							<a href=\"loadmore|{$cntCurrent_NumberUP}\" class=\"page-numbers loadmore btn jv-btn-bright outline\">
								%s
							</a>
							";
					}else{
						$strMoreButton = "";
					}
					if( !empty( $strMoreButton ) )
						printf( $strMoreButton, __( "Load More", 'javo' ) );
				break;

				case 'number' :
				default :
					$arrPagination		= paginate_links(
						Array(
							'base'			=> '|%#%'
							, 'format'		=> '%#%'
							, 'current'		=> $cntCurrent_Number
							, 'total'			=> $cntMax_PageNumber
							, 'type'			=> 'array'
							, 'prev_text'	=> '&lt;'
							, 'next_text'	=> '&gt;'
						)
					);
					echo "<ul class=\"pagination\">";
					if( is_Array( $arrPagination ) ) foreach( $arrPagination as $pLink ) {
						$strCurrent	= strpos( $pLink, 'current' ) !== false ? " class=\"active\" " : '';
						echo "<li{$strCurrent}>{$pLink}</li>";
					}
					echo "</ul>";
				// endcase;
			endswitch;
			echo "</div>";
			wp_reset_query();
		}

		public function sFooter()
		{
			// Shortcode Close
			echo "
				<div class=\"output-cover\"></div>
				<div class=\"output-loading\"></div>
			</div> <!-- /.Shortcode End {$this->sID} -->
			";
			do_action( 'jvfrm_spot_shortcode_after', $this );
		}

		public function enqueue() {
			wp_enqueue_script( $this->enq_prefix . 'jquery-flexslider-min-js' );
			wp_enqueue_script( 'flex-menu' );
			wp_enqueue_script( 'javo-ajaxShortcode' );
		}
	}

endif;