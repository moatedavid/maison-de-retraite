<?php

if( ! class_exists( 'jvfrm_spot_Directory' ) )
	die;

class jvfrm_spot_Directory_Shortcode extends jvfrm_spot_Directory
{

	private $corePrefix							= false;

	private $modules								= Array();

	public $raty_onmoduleExcerpt			= Array(
		'module1',
		'module3',
		'module4',
		'module14',
	);

	public $raty_on_moduleThumbnail	= Array(
		'module2',
		'module6',
		'module8',
		'module9',
		'module10',
		'module11',
		'module12',
		'moduleSmallGrid',
		'moduleHorizontalGrid',
		'moduleBigGrid',
	);

	public static $scdInstance				= false;

	public function __construct()
	{
		self::$scdInstance			= $this;
		$this->dir							= get_template_directory() . '/includes/';

		$this->shortcode_path	= $this->dir . 'shortcodes/';

		if( ! class_exists( self::CORE ) )
			return false;

		$this->corePrefix = Javo_Spot_Core::get_instance()->template . '_';

		// Normal Shortcode Category & Meta
		add_action( 'jvfrm_spot_modules_loaded', Array(  $this, 'all_category_additional_meta' ) );

		// Normal Shortcode Category & Meta
		add_action( 'jvfrm_spot_modules_loaded', Array(  $this, 'get_modules' ) );

		// Map Template Slug
		add_filter( 'jvfrm_spot_get_map_template_slug', Array( $this, 'get_map_template_slug' ), 10, 2 );
		add_filter( 'jvfrm_spot_shortcodes_post_type_addition', Array( $this, 'addition_posttype' ) );

		// Custom Parameter
		add_filter( $this->corePrefix .'commonParam', Array( $this, 'commonParam' ), 10, 2 );
		add_filter( $this->corePrefix .'shortcodes_atts', Array( $this, 'shortcodes_atts' ) );
		add_filter( $this->corePrefix .'shotcode_query', Array( $this, 'shotcode_query' ), 10, 2 );

		// Custom Order
		add_filter( 'jvfrm_spot_shortcodes_order_by', Array( $this, 'customOrderParam' ), 10, 2 );
		add_filter( 'jvfrm_spot_shortcode_css', Array( $this, 'custom_shortcode_css' ), 10, 2 );
		add_filter( 'jvfrm_spot_shortcode_class', Array( $this, 'custom_shortcode_class' ), 10, 3 );

		add_action( 'jvfrm_spot_parsed_shortcode', Array( $this, 'parsed_shortcode' ) );
		add_action( 'jvfrm_spot_shortcode_after', Array( $this, 'after_shortcode' ) );

		// Load module contents on map ajax template
		add_action( 'jvfrm_spot_template_all_module_loop_after', Array( $this, 'after_shortcode' ) );

		if( file_exists( $this->shortcode_path . 'shortcode-search1.php' ) )
			require_once $this->shortcode_path . 'shortcode-search1.php';

		if( file_exists( $this->shortcode_path . 'shortcode-category-slider1.php' ) )
			require_once $this->shortcode_path . 'shortcode-category-slider1.php';

		if( file_exists( $this->shortcode_path . 'shortcode-jv_featured1.php' ) )
			require_once $this->shortcode_path . 'shortcode-jv_featured1.php';

		do_action( 'jvfrm_spot_themes_custom_shortcode' );
	}

	public function get_modules( $modules ) {
		$this->modules = array_keys( $modules );
	}

	public function all_category_additional_meta( $modules ) {
		if( !empty( $modules ) ) : foreach( $modules as $module_name => $args ) {
			add_filter( "jvfrm_spot_{$module_name}_featured_tax", Array( $this, 'featured_category' ), 10, 2 );
			add_filter( "jvfrm_spot_{$module_name}_featured_no_tax", Array( $this, 'no_category_comment' ), 10, 2 );
			add_filter( "jvfrm_spot_{$module_name}_additional_meta", Array( $this, 'additional_meta' ), 10, 2 );
		} endif;
	}

	public function addition_posttype( $post_types ) {
		return wp_parse_args( Array( self::SLUG ), $post_types );
	}

	public function featured_category( $taxonomy, $post_id ) {

		if( get_post_type( $post_id ) == self::SLUG )
			$taxonomy = self::NAME . '_category';
		return $taxonomy;
	}

	public function no_category_comment( $comment, $post_id ) {
		if( get_post_type( $post_id ) == self::SLUG )
			$comment = esc_html__( "Not Set", 'javospot' );
		return $comment;
	}

	public function additional_meta( $args, $obj=false ) {

		if( get_post_type( $obj->post ) != self::SLUG || empty( $obj ) )
			return $args;

		$arrOutput				= Array(
			'meta-location'		=> Array(
				'icon'			=> 'glyphicon glyphicon-map-marker',
				'value'			=> sprintf( "<span>%s</span>", esc_html( $obj->c( self::NAME . '_location', esc_html__( "Not Set", 'javospot' ) ) ) )
			),
			'meta-author'		=> Array(
				'icon'			=> 'glyphicon glyphicon-user',
				'value'			=> sprintf( "<span>%s</span>", esc_html( $obj->author_name ) )
			),
			'module-meta-category' => Array(
				'icon'			=> 'fa fa-folder-o',
				'value'			=> sprintf( "<span>%s</span>", esc_html( $obj->category() ) )
			),
		);

		return $arrOutput;
	}

	public function more_meta( $obj=Array() )
	{
		if( get_post_type( $obj->post ) != self::SLUG )
			return;

		switch( get_class( $obj ) )
		{
			case 'module1' :
				printf( "<div class=\"meta-price\">%s %s</div>", esc_html( $obj->m( '_price_prefix' ) ), esc_html( $obj->m( '_price' ) ) );
				printf( "<div class=\"author-name\">%s <span>%s</span></div>", _x( 'By', 'More meta author', 'javospot' ), esc_html( $obj->author_name ) );
			break;

			default:
				printf( "<div class=\"meta-price\">%s %s</div>", esc_html( $obj->m( '_price_prefix' ) ), esc_html( $obj->m( '_price' ) ) );
		}

	}

	private function get_rating_template( $obj=null, $type=false )
	{
		$strTemplate			= '';
		$ratingScore			= floatVal( get_post_meta( $obj->post_id, 'rating_average', true ) );
		$ratingPercentage	= floatVal( ( $ratingScore / 5 ) * 100 ) . '%%';
		$rating2x					= intVal( $ratingScore ) * 2;
		switch( $type ) {
			case 'number' :
				$strTemplate	= sprintf( "<div class='meta-rating-nomeric score-lv-%s'>%.1f</div>", esc_attr( $rating2x ),  esc_attr( $ratingScore ) );
			break;
			case 'star' :
				$strTemplate	= "<div class='meta-rating-wrap'><div class='meta-rating' style=\"width:" . esc_html( $ratingPercentage ) .";\"></div></div>";
			break;
		}
		return $strTemplate;
	}

	public function contents_with_raty_number( $html, $obj=null )
	{
		if( is_null( $obj ) )
			return $html;
		return $this->get_rating_template( $obj, 'number' ) . $html;
	}

	public function contents_with_raty_star( $html, $obj=null )
	{
		if( is_null( $obj ) )
			return $html;

		return $this->get_rating_template( $obj, 'star' ) . $html;
	}

	public function get_map_template_slug( $template_slug, $post_type='post'  ) {
		$template_slug		= 'lava_' . self::SLUG . '_map';
		return $template_slug;
	}

	public function commonParam( $args, $strGroup=Array() )
	{
		$arrShortcodeTax = Array();

		if( function_exists( 'jvfrm_spot_shortcode_taxonomies' ) )
			$arrShortcodeTax = jvfrm_spot_shortcode_taxonomies( self::SLUG, Array( esc_html__( "None", 'javospot' )	=> '' ) );

		$arrAppend = Array(

			/** Filter Taxonomy */
			Array(
				'type'						=> 'dropdown'
				, 'group'					=> $strGroup[ 'filter' ]
				, 'heading'				=> esc_html__( "Category Filter", 'javospot')
				, 'holder'				=> 'div'
				, 'dependency'		=> Array(
					'element'			=> 'post_type'
					, 'value'				=> self::SLUG
				)
				, 'param_name'		=> self::SLUG . '_filter_by'
				, 'value'					=> $arrShortcodeTax
			)

			/** Custom Post Taxonomies */
			, Array(
				'type'						=> 'checkbox'
				, 'group'					=> $strGroup[ 'filter' ]
				, 'heading'				=> esc_html__( "Use Custom Terms", 'javospot')
				, 'holder'				=> 'div'
				, 'dependency'		=> Array(
					'element'			=> 'post_type'
					, 'value'				=> self::SLUG
				)
				,  'description'		=> esc_html__( "To display specific terms only, please enable. if you use custom terms..", 'javospot' )
				, 'param_name'		=> 'custom_filter_by_' . self::SLUG
				, 'value'					=> Array( esc_html__( "Enable", 'javospot' ) => '1' )
			)

			/** Header / Filter Custom Terms */
			, Array(
				'type'						=> 'textfield'
				, 'group'					=> $strGroup[ 'filter' ]
				, 'heading'				=> esc_html__( "Custom Terms IDs", 'javospot')
				, 'holder'				=> 'div'
				, 'dependency'		=> Array(
					'element'			=> 'custom_filter_by_' . self::SLUG
					, 'value'				=> '1'
				)
				, 'param_name'		=> 'custom_filter_' . self::SLUG
				, 'description'			=> esc_html__( "Enter category IDs separated by commas (ex: 13,23,18). To exclude categories please add '-' (ex: -9, -10)", 'javospot' )
				, 'value'					=> ''
			)

			/** Featured Item */
			, Array(
				'type'						=> 'checkbox'
				, 'group'					=> $strGroup[ 'filter' ]
				, 'heading'				=> esc_html__( "Display only featured items", 'javospot')
				, 'holder'				=> 'div'
				, 'dependency'		=> Array(
					'element'			=> 'post_type'
					, 'value'				=> self::SLUG
				)
				, 'description'			=> esc_html__( "To display featured items only, please enable", 'javospot' )
				, 'param_name'		=> 'featured_' . self::SLUG
				, 'value'					=> Array( esc_html__( "Enable", 'javospot' ) => '1' )
			)
		);

		if( class_exists( 'Lava_Directory_Review' ) )
		{
			$arrAppend[]			= Array(
				'type'					=> 'dropdown'
				, 'group'				=> $strGroup[ 'advanced' ]
				, 'heading'				=> esc_html__( "Rating display style", 'javospot'	)
				, 'holder'				=> 'div'
				, 'param_name'		=> 'rating_type'
				, 'value'					=> Array(
					esc_html__( "Stars", 'javospot' )					=> 'star' ,
					esc_html__( "Numeric", 'javospot' )			=> 'number' ,
					esc_html__( "Disable ( hide )", 'javospot' )	=> 'disabled' ,
				)
			);

			$arrAppend[]			= Array(
				'type'					=> 'checkbox'
				, 'group'				=> $strGroup[ 'filter' ]
				, 'heading'				=> esc_html__( "Review ranking display option ( Only for order by Rating)", 'javospot')
				, 'holder'				=> 'div'
				, 'dependency'		=> Array(
					'element'			=> 'order_by'
					, 'value'				=> 'rating'
				)
				, 'param_name'		=> 'display_rating_grade'
				, 'value'					=> Array( esc_html__( "Enable", 'javospot' ) => '1' )
			);

			$arrAppend[]			= Array(
				'type'					=> 'colorpicker'
				, 'group'				=> $strGroup[ 'style' ]
				, 'heading'				=> esc_html__( "Rating Rank Numeric Background Color", 'javospot')
				, 'holder'				=> 'div'
				, 'dependency'		=> Array(
					'element'			=> 'order_by'
					, 'value'				=> 'rating'
				)
				, 'param_name'		=> 'rating_grade_color'
				, 'value'					=> ''
			);

		}

		return wp_parse_args( $arrAppend, $args );
	}

	public function shortcodes_atts( $attr )
	{
		return wp_parse_args(
			Array(
				self::SLUG . '_filter_by'			=> ''
				, 'custom_filter_by_' . self::SLUG	=> ''
				, 'custom_filter_' . self::SLUG		=> ''
				, 'featured_' . self::SLUG			=> ''
				, 'rating_type'						=> 'star'
				, 'display_rating_grade'			=> ''
				, 'rating_grade_color'				=> ''
			)
			, $attr
		);
	}

	public function shotcode_query( $query, $obj=null )
	{
		$prefixFeatured		= 'featured_' . self::SLUG;
		if( !is_null( $obj ) ) {
			if( isset( $obj->$prefixFeatured	 ) ) {
				if( '1' == $obj->$prefixFeatured	 )
					$query[ 'meta_query' ][] = Array(
						'key'		=> '_featured_item',
						'value'	=> '1'
					);
			}
			if( $obj->order_by == 'rating' && class_exists( 'Lava_Directory_Review' ) ) {
				$query[ 'meta_key' ]		= 'rating_average';
				$query[ 'orderby' ]		= 'meta_value_num';
			}
		}
		return $query;
	}

	public function parsed_shortcode( $obj )
	{
		$arrObject = get_object_vars( $obj );

		if( $obj->post_type == self::SLUG ) {

			if( !empty( $arrObject[ self::SLUG . '_filter_by' ] ) )
				$obj->filter_by					= $arrObject[ self::SLUG . '_filter_by' ];

			if( isset( $arrObject[ 'custom_filter_by_' . self::SLUG ] ) && $arrObject[ 'custom_filter_by_' . self::SLUG ]  == '1' )
			{
				$obj->custom_filter_by_post	= $arrObject[ 'custom_filter_by_' . self::SLUG ];
				if( !empty( $arrObject[ 'custom_filter_' . self::SLUG ] ) )
					$obj->custom_filter			= $arrObject[ 'custom_filter_' . self::SLUG ];
			}

			$obj->enq_prefix	= str_replace( '_', '-', sanitize_title( self::MAINPLUG ) ) . '-';

			if( class_exists( 'Lava_Directory_Review' ) && $obj->rating_type != 'disabled' )
			{
				if( !empty( $this->modules ) ) : foreach( $this->modules as $module_name ) {
					if( in_Array( $module_name, $this->raty_onmoduleExcerpt ) && !empty( $obj->rating_type )  ) {
						add_filter( "jvfrm_spot_{$module_name}_core_module_excerpt_after", Array( $this, 'contents_with_raty_' . $obj->rating_type  ), 10, 2 );
					}else if( in_Array( $module_name, $this->raty_on_moduleThumbnail ) && !empty( $obj->rating_type ) ) {
						add_filter( "jvfrm_spot_{$module_name}_core_module_thumbnail_after", Array( $this, 'contents_with_raty_' . $obj->rating_type ), 10, 2 );
					}
				} endif;
			}
		}
	}

	public function after_shortcode( $obj=null ) {
		if( !empty( $this->modules ) ) : foreach( $this->modules as $module_name ) {
			remove_all_filters( "jvfrm_spot_{$module_name}_core_module_excerpt_after" );
			remove_all_filters( "jvfrm_spot_{$module_name}_core_module_thumbnail_after" );
		} endif;
	}

	public function customOrderParam( $args ) {
		if( class_exists( 'Lava_Directory_Review' ) ) {
			$args = wp_parse_args(
				Array( esc_html__( "Rating", 'javospot' ) => 'rating' ), $args
			);
		}
		return $args;
	}

	public function custom_shortcode_css( $style_rows=Array(), $obj=null )
	{
		if( is_null( $obj ) )
			return $style_rows;

		$strShortcodeID		= $obj->getID();

		if( class_exists( 'Lava_Directory_Review' ) && !empty( $obj->display_rating_grade ) ) {
			if( $css = ( !empty( $obj->rating_grade_color ) ? $obj->rating_grade_color : false ) ) {
				$style_rows[]	= "
				.display-rating-garde > #{$strShortcodeID} .shortcode-output .module.media > .media-left > a:before,
				.display-rating-garde > #{$strShortcodeID} .shortcode-output .module > .thumb-wrap:after
				{ background-color:{$css}; }";
			}
		}
		return $style_rows;
	}

	public function custom_shortcode_class( $classes=Array(), $module_name=false, $obj=null )
	{
		if( is_null( $obj ) )
			return $classes;

		if( class_exists( 'Lava_Directory_Review' ) && !empty( $obj->display_rating_grade ) ) {
			$classes[]		= 'display-rating-garde';
		}
		return $classes;
	}
}