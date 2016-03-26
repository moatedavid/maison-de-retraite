<?php
/**
 *	Javo Shortcodes
 *
 *	@Since	1.0.0
 */


/** Core Classes */
require_once 'functions-shortcode.php';
require_once 'class-shortcode.php';
require_once 'class-module.php';

/** Shortcodes */
require_once 'shortcode-jvfrm_spot_category_box.php';
require_once 'shortcode-jvfrm_spot_category_box2.php';
require_once 'shortcode-jvfrm_spot_mailchimp.php';
// require_once 'shortcode-jvfrm_spot_slider_authors.php';

if( !function_exists( 'jvfrm_spot_register_shortcodes' ) ) :

	function jvfrm_spot_register_shortcodes( $prefix )
	{

		if( !class_exists( $prefix . 'core' ) )
			return false;

	/**
	 *	Variables
	 */
		$dirShortcode		= trailingslashit( dirname( __FILE__ ) );
		$dirModule			= Javo_Spot_Core::$instance->module_path . '/';

		$arrGroupStrings	= Array(
			'header'		=> __( "Header", 'javo' ),
			'content'		=> __( "Content", 'javo' ),
			'advanced'		=> __( "Advanced", 'javo' ),
			'effect'		=> __( "Effect", 'javo' ),
			'style'			=> __( "Style", 'javo' ),
			'filter'		=> __( "Filter", 'javo' )
		);

		list(
			$groupHeader,
			$groupContent,
			$groupAdvanced,
			$groupEffect,
			$groupStyle,
			$groupFilter
		) = Array_values( $arrGroupStrings );

	/**
	 *	Shortcodes Part
	 */
		$arrShortcodeDEFAULTS		= Array(

			/* Shortcode 1 */
			'jvfrm_spot_block1'				=> Array(
				'name'				=> __( "Block 1", 'javo'),
				'icon'				=> 'jv-vc-shortcode-icon shortcode-block-1',
				'column'			=> 3,
				'hide_avatar'		=> true,
				'params'			=> Array(
					Array(
						'type'		=> 'dropdown',
						'group'		=> $groupStyle,
						'heading'		=> __( "Display Thumbnail", 'javo'),
						'holder'		=> 'div',
						'param_name'	=> '_display_thumbnail',
						'value'		=> Array(
							__( "Enable", 'javo' ) => '',
							__( "Disable", 'javo' ) => '1'
						)
					),
					Array(
						'type'					=> 'dropdown'
						, 'group'				=> $groupStyle
						, 'heading'				=> __( "Display Post Border", 'javo')
						, 'holder'				=> 'div'
						, 'param_name'			=> 'display_post_border'
						, 'value'				=> Array(
							__( "Enable", 'javo' ) => '',
							__( "Disable", 'javo' ) => '1'
						)
					)
				),
				'hover_style'	=> true,
			),

			/* Shortcode 2 */
			/*'jvfrm_spot_block2'			=> Array(
				'name'			=> __( "Block 2", 'javo'),
				'icon'			=> 'jv-vc-shortcode-icon shortcode-block-2',
				'column'		=> 3,
				'more'			=> true,
			),
*/

			/* Shortcode 3 */
			'jvfrm_spot_block3'			=> Array(
				'name'			=> __( "Block 3", 'javo'),
				'filter'		=> true,
				'column'		=> 3,
				'icon'			=> 'jv-vc-shortcode-icon shortcode-block-3',
				'hide_avatar'	=> true,
			),

			/* Shortcode 4 */
			/*'jvfrm_spot_block4'			=> Array(
				'name'			=> __( "Block 4", 'javo'),
				'icon'			=> 'jv-vc-shortcode-icon shortcode-block-4',
				'column'		=> 4,
				'hover_style'	=> true,
			),
*/

			/* Shortcode 5 */
			/*'jvfrm_spot_block5'			=> Array(
				'name'			=> __( "Block 5", 'javo'),
				'more'			=> true,
				'column'		=> 3,
				'icon'			=> 'jv-vc-shortcode-icon shortcode-block-5',
			),
*/

			/* Shortcode 6 */
			'jvfrm_spot_block6'			=> Array(
				'name'			=> __( "Block 6", 'javo'),
				'icon'			=> 'jv-vc-shortcode-icon shortcode-block-6',
				'column'		=> 3,
				'more'			=> true,
				'hover_style'	=> true,
			),

			/* Shortcode 7 */
			'jvfrm_spot_block7'			=> Array(
				'name'			=> __( "Block 7", 'javo'),
				'more'			=> true,
				'column'		=> 2,
				'icon'			=> 'jv-vc-shortcode-icon shortcode-block-7',
				'hover_style'	=> true,
			),

			/* Shortcode 8 */
			'jvfrm_spot_block8'			=> Array(
				'name'				=> __( "Block 8", 'javo')
				, 'more'				=> true
				, 'icon'				=> 'jv-vc-shortcode-icon shortcode-block-8'
				, 'column'			=> 2
			),

			/* Shortcode 9 */
			/*'jvfrm_spot_block9'			=> Array(
				'name'				=> __( "Block 9", 'javo')
				, 'icon'				=> 'jv-vc-shortcode-icon shortcode-block-9'
				, 'more'				=> true
			),
*/

			/* Shortcode 10 */
			'jvfrm_spot_block10'		=> Array(
				'name'			=> __( "Block 10", 'javo'),
				'more'			=> true,
				'column'		=> 3,
				'icon'			=> 'jv-vc-shortcode-icon shortcode-block-10',
				'hover_style'	=> true,
			),

			/* Shortcode 11 */
			'jvfrm_spot_block11'		=> Array(
				'name'			=> __( "Block 11", 'javo'),
				'more'			=> true,
				'icon'			=> 'jv-vc-shortcode-icon shortcode-block-11',
				'column'		=> 3,
				'hover_style'	=> true,
			),

			/* Shortcode 12 */
			'jvfrm_spot_block12'			=> Array(
				'name'				=> __( "Block 12", 'javo')
				, 'icon'				=> 'jv-vc-shortcode-icon shortcode-block-12'
				, 'column'			=> 3
				, 'more'				=> true
			),

			/* Shortcode 13 */
			/*'jvfrm_spot_block13'			=> Array(
				'name'				=> __( "Block 13", 'javo')
				, 'filter'				=> true
				, 'column'			=> 3
				, 'icon'				=> 'jv-vc-shortcode-icon shortcode-block-13'
			),
*/

			/* Shortcode 14  */
			/*'jvfrm_spot_block14'			=> Array(
				'name'				=> __( "Block 14", 'javo')
				, 'icon'				=> 'jv-vc-shortcode-icon shortcode-block-14'
				, 'more'				=> true
			),
*/

			/* Shortcode 15  */
			/*'jvfrm_spot_block15'		=> Array(
				'name'			=> __( "Block 15", 'javo')
				, 'icon'		=> 'jv-vc-shortcode-icon shortcode-block-15'
				, 'column'		=> 3
				, 'more'		=> true
				, 'hide_thumb'	=> true
			),
*/

			/* Shortcode 16  */
			'jvfrm_spot_block16'		=> Array(
				'name'			=> __( "Block 16", 'javo'),
				'icon'			=> 'jv-vc-shortcode-icon shortcode-block-15',
				'column'		=> 3,
				'more'			=> true,
				'hover_style'	=> true,
			),

			/* Shortcode Grid 1  */
			'jvfrm_spot_big_grid1'		=> Array(
				'name'			=> __( "Grid 1", 'javo'),
				'more'			=> true,
				'fixed_count'	=> true,
				'icon'			=> 'jv-vc-shortcode-icon shortcode-grid1',
				'hover_style'	=> true,
			),

			/* Shortcode Grid 2  */
			/*'jvfrm_spot_big_grid2'		=> Array(
				'name'				=> __( "Grid 2", 'javo')
				, 'icon'				=> 'jv-vc-shortcode-icon shortcode-grid2'
				, 'fixed_count'	=> true
				, 'more'				=> true
			),
*/

			/* Shortcode Grid 3  */
			'jvfrm_spot_big_grid3'			=> Array(
				'name'				=> __( "Grid 3", 'javo'),
				'icon'				=> 'jv-vc-shortcode-icon shortcode-grid3',
				'more'				=> true,
				'hover_style'		=> true,
			),

			/* Shortcode Slide1 */
			/*'jvfrm_spot_slider1'			=> Array(
				'name'				=> __( "Slider 1", 'javo')
				, 'icon'				=> 'jv-vc-shortcode-icon shortcode-slider1'
				, 'fixed_count'	=> true
				, 'hide_avatar'	=> true
			),
*/

			/* Shortcode Slide1 */
			'jvfrm_spot_slider2'			=> Array(
				'name'				=> __( "Slider 2", 'javo')
				, 'icon'				=> 'jv-vc-shortcode-icon shortcode-slider2'
				, 'fixed_count'	=> true
				, 'hide_avatar'	=> true
			),

			/* Shortcode Slide3 */
			'jvfrm_spot_slider3'		=> Array(
				'name'			=> __( "Slider 3", 'javo'),
				'icon'			=> 'jv-vc-shortcode-icon shortcode-slider3',
				'fixed_count'	=> true,
				'column'		=> 3,
				'params'		=> Array(
					Array(
						'type'			=> 'checkbox',
						'group'			=> $groupStyle,
						'heading'		=> __( "Wide Slider?", 'javo'),
						'holder'		=> 'div',
						'param_name'	=> 'slide_wide',
						'value'			=> Array( __( "Enable", 'javo' ) => '1' )
					)
				),
				'hover_style'	=> true,
			),

			/* Shortcode Slide4 */
			/*'jvfrm_spot_slider4'			=> Array(
				'name'				=> __( "Slider 4", 'javo')
				, 'icon'				=> 'jv-vc-shortcode-icon shortcode-slider4'
				, 'fixed_count'	=> true
			),
*/

			/* Shortcode Slide5 */
			/*'jvfrm_spot_slider5'			=> Array(
				'name'				=> __( "Slider 5", 'javo')
				, 'icon'				=> 'jv-vc-shortcode-icon shortcode-slider4'
				, 'fixed_count'	=> true
			),
*/

			/* Shortcode Timeline1 */
			/*'jvfrm_spot_timeline1'		=> Array(
				'name'				=> __( "Timeline 1", 'javo')
				, 'icon'				=> 'jv-vc-shortcode-icon shortcode-timeline1'
			),
*/

			/* Shortcode Login1 */
			'jvfrm_spot_login1'				=> Array(
				'name'				=> __( "Login 1", 'javo')
				, 'icon'				=> 'jv-vc-shortcode-icon shortcode-login1'
				, 'fixed_count'	=> true
			),

			/* Vertical Shortcode 1 */
			/*'jvfrm_spot_vblock1'			=> Array(
				'name'				=> __( "Vertical Block 1", 'javo')
				, 'icon'			=> 'jv-vc-shortcode-icon shortcode-block-1'
				, 'column'			=> 3
			),
*/

			/* Vertical Slider Shortcode 1 */
			/*'jvfrm_spot_vslider1'			=> Array(
				'name'				=> __( "Vertical Slider 1", 'javo')
				, 'icon'			=> 'jv-vc-shortcode-icon shortcode-block-1'
			),
*/

		);

	if( defined( 'Javo_Spot_Core::DEBUG' ) && Javo_Spot_Core::DEBUG ) {
		/* Shortcode 20  */
		$arrShortcodeDEFAULTS[ 'jvfrm_spot_block20' ]	=
			Array(
				'name'				=> __( "Block 20 ( ALL MODULE )", 'javo')
				, 'icon'				=> 'jv-vc-shortcode-icon shortcode-block-20'
				, 'fixed_count'	=> true
			);
	}


	/**
	 *	Modules Part
	 */
		$arrModuleDEFAULTS				= Array(
			'module1'					=> Array( 'file' => $dirModule . 'module1.php' )
			, 'module2'					=> Array( 'file' => $dirModule . 'module2.php' )
			, 'module3'					=> Array( 'file' => $dirModule . 'module3.php' )
			, 'module4'					=> Array( 'file' => $dirModule . 'module4.php' )
			, 'module5'					=> Array( 'file' => $dirModule . 'module5.php' )
			, 'module6'					=> Array( 'file' => $dirModule . 'module6.php' )
			, 'module8'					=> Array( 'file' => $dirModule . 'module8.php' )
			, 'module9'					=> Array( 'file' => $dirModule . 'module9.php' )
			, 'module12'				=> Array( 'file' => $dirModule . 'module12.php' )
			, 'module13'				=> Array( 'file' => $dirModule . 'module13.php' )
			, 'module14'				=> Array( 'file' => $dirModule . 'module14.php' )
			, 'moduleSmallGrid'			=> Array( 'file' => $dirModule . 'module-SmallGrid.php' )
			, 'moduleBigGrid'			=> Array( 'file' => $dirModule . 'module-BigGrid.php' )
			, 'moduleHorizontalGrid'	=> Array( 'file' => $dirModule . 'module-HorizontalGrid.php' )
			, 'moduleWC1'				=> Array( 'file' => $dirModule . 'module-wc1.php' )
		);


		$arrShortcodes	= apply_filters( 'jvfrm_spot_shortcodes_args', $arrShortcodeDEFAULTS );
		$arrModules		= apply_filters( 'jvfrm_spot_modules_args', $arrModuleDEFAULTS );
		$arrCommonParam	= Array(

			/**
			 *	@group : header
			 */
			 Array(
				'type'						=> 'dropdown'
				, 'group'					=> $groupHeader
				, 'heading'				=> __( "Header Type", 'javo')
				, 'holder'				=> 'div'
				, 'class'					=> ''
				, 'param_name'		=> 'filter_style'
				, 'value'					=> Array(
					__( "None", 'javo' )					=> ''
					, __( "Style1", 'javo' )				=> 'general'
					, __( "Style2", 'javo' )				=> 'linear'
					, __( "Style3", 'javo' )				=> 'paragraph'
					, __( "Style4", 'javo' )				=> 'boxline'
				)
			)

			/** Shortcode Title */
			, Array(
				'type'						=> 'textfield'
				, 'group'					=> $groupHeader
				, 'heading'				=> __( "Title", 'javo' )
				, 'holder'				=> 'div'
				, 'dependency'		=> Array(
					'element'			=> 'filter_style'
					, 'not_empty'		=> true
				)
				, 'param_name'		=> 'title'
				, 'value'					=> ''
			)

			/** Shortcode Subtitle */
			, Array(
				'type'						=> 'textfield'
				, 'group'					=> $groupHeader
				, 'heading'				=> __( "Sub Title (Only Header Type Style3)", 'javo' )
				, 'holder'				=> 'div'
				, 'dependency'		=> Array(
					'element'			=> 'filter_style'
					, 'value'				=> 'paragraph'
				)
				, 'param_name'		=> 'subtitle'
				, 'value'					=> ''
			)


			/**
			 *	@group : Style
			 */

			 /** Custom Post Taxonomies */
			, Array(
				'type'						=> 'dropdown'
				, 'group'					=> $groupFilter
				, 'heading'					=> __( "Show / Hide Filter", 'javo')
				, 'holder'					=> 'div'
				, 'param_name'			=> 'hide_filter'
				, 'value'						=> Array(
					__( "Show", 'javo' ) => '',
					__( "Hide", 'javo' ) => '1'
				)
			),

			Array(
				'type'			=> 'css_editor',
				'group'			=> $groupStyle,
				'heading'		=> __( 'Css', 'my-text-domain' ),
				'param_name'	=> 'css',
			),

			/** Primary Color */
			Array(
				'type'						=> 'colorpicker'
				, 'group'					=> $groupStyle
				, 'heading'				=> __( "Primary Color", 'javo' )
				, 'holder'				=> 'div'
				, 'param_name'		=> 'primary_color'
				, 'value'					=> ''
			)

			/** Primary Font Color */
			, Array(
				'type'						=> 'colorpicker'
				, 'group'					=> $groupStyle
				, 'heading'				=> __( "Primary Font Color", 'javo' )
				, 'holder'				=> 'div'
				, 'param_name'		=> 'primary_font_color'
				, 'value'					=> ''
			)

			/** Primary Border Color */
			, Array(
				'type'						=> 'colorpicker'
				, 'group'					=> $groupStyle
				, 'heading'				=> __( "Primary Border Color", 'javo' )
				, 'holder'				=> 'div'
				, 'param_name'		=> 'primary_border_color'
				, 'value'					=> ''
			)

			/** Post TItle Font Color */
			, Array(
				'type'						=> 'colorpicker'
				, 'group'					=> $groupStyle
				, 'heading'				=> __( "Post Title Color", 'javo' )
				, 'holder'				=> 'div'
				, 'param_name'		=> 'post_title_font_color'
				, 'value'					=> ''
			)

			/** Post Title Size  */
			, Array(
				'type'					=> 'textfield'
				, 'group'				=> $groupStyle
				, 'heading'				=> __( "Post Title Font Size", 'javo' )
				, 'holder'				=> 'div'
				, 'description'		=> __( "Pixcel", 'javo' )
				, 'param_name'		=> 'post_title_font_size'
				, 'value'					=> ''
			)

			/** Post TItle Capitalize */
			, Array(
				'type'					=> 'dropdown'
				, 'group'				=> $groupStyle
				, 'heading'				=> __( "Post Title Transform", 'javo' )
				, 'holder'				=> 'div'
				, 'param_name'		=> 'post_title_transform'
				, 'value'					=> Array(
					__( "Inheritance", 'javo' )	=> 'inherit',
					__( "Uppercase", 'javo' )	=> 'uppercase',
					__( "Lowercase", 'javo' )	=> 'lowercase',
				)
			)

			/** Post Meta Font Color */
			, Array(
				'type'						=> 'colorpicker'
				, 'group'					=> $groupStyle
				, 'heading'				=> __( "Post Meta Font Color", 'javo' )
				, 'holder'				=> 'div'
				, 'param_name'		=> 'post_meta_font_color'
				, 'value'					=> ''
			)

			/** Post Describe Font Color */
			, Array(
				'type'						=> 'colorpicker'
				, 'group'					=> $groupStyle
				, 'heading'				=> __( "Post Description Font Color", 'javo' )
				, 'holder'				=> 'div'
				, 'param_name'		=> 'post_describe_font_color'
				, 'value'					=> ''
			)

			/** Category Tag Visibility */
			, Array(
				'type'			=> 'dropdown',
				'group'			=> $groupStyle,
				'heading'		=> __( "Display Category Tags", 'javo' ),
				'holder'		=> 'div',
				'param_name'	=> 'display_category_tag',
				'value'			=> Array(
					__( "Visible", 'javo' )	=> '',
					__( "HIdden", 'javo' )	=> 'hide'
				)
			)

			/** Category Tag Color */
			, Array(
				'type'			=> 'colorpicker',
				'group'			=> $groupStyle,
				'heading'		=> __( "Category Tags Background Color", 'javo' ),
				'holder'		=> 'div',
				'dependency'	=> Array(
					'element'	=> 'display_category_tag',
					'value'		=> Array( '' )
				),
				'param_name'	=> 'category_tag_color',
				'value'			=> '#666'
			)

			/** Category Tag Hover Color */
			, Array(
				'type'			=> 'colorpicker',
				'group'			=> $groupStyle,
				'heading'		=> __( "Category Tags Background Hover Color", 'javo' ),
				'holder'		=> 'div',
				'dependency'	=> Array(
					'element'	=> 'display_category_tag',
					'value'		=> Array( '' )
				),
				'param_name'	=> 'category_tag_hover_color',
				'value'			=> ''
			)

			/** Pre-loader Style */
			, Array(
				'type'			=> 'dropdown',
				'group'			=> $groupStyle,
				'heading'		=> __( "Pre-loader Style Type", 'javo'),
				'holder'		=> 'div',
				'class'			=> '',
				'param_name'	=> 'loading_style',
				'value'			=>
					Array(
						__( "None", 'javo' )		=> '',
						__( "Rectangle", 'javo' )	=> 'rect',
						__( "circle", 'javo' )		=> 'circle'
					)
			)

			/**
			 *	@group : Filter
			 */

			/** Post Type */
			, Array(
				'type'					=> 'dropdown'
				, 'group'				=> $groupFilter
				, 'heading'				=> __( "Post Type", 'javo' )
				, 'holder'				=> 'div'
				, 'param_name'		=> 'post_type'
				, 'value'					=> apply_filters( 'jvfrm_spot_shortcodes_post_types', Array( 'post' ) )
			)

			/** Order Type */
			, Array(
				'type'					=> 'dropdown'
				, 'group'				=> $groupFilter
				, 'heading'				=> __( "Order By", 'javo' )
				, 'holder'				=> 'div'
				, 'param_name'		=> 'order_by'
				, 'value'					=> apply_filters( 'jvfrm_spot_shortcodes_order_by',
					Array(
						__( "None", 'javo' )			=> '',
						__( "Post Title", 'javo' )	=> 'title',
						__( "Date", 'javo' )			=> 'date',
						__( "Random", 'javo' )	=> 'rand',
					)
				)
			)

			/** Order Type */
			, Array(
				'type'					=> 'dropdown'
				, 'group'				=> $groupFilter
				, 'heading'				=> __( "Order Type", 'javo' )
				, 'holder'				=> 'div'
				, 'dependency'		=> Array(
					'element'			=> 'order_by'
					, 'not_empty'		=> true
				)
				, 'param_name'		=> 'order_'
				, 'value'					=> Array(
					__( "Descending ( default )", 'javo' )	=> '',
					__( "Ascending", 'javo' )		=> 'ASC'
				)
			)

			/** Filter Taxonomy */
			, Array(
				'type'					=> 'dropdown'
				, 'group'				=> $groupFilter
				, 'heading'				=> __( "Category Filter", 'javo')
				, 'holder'				=> 'div'
				, 'dependency'		=> Array(
					'element'			=> 'post_type'
					, 'value'				=> 'post'
				)
				, 'param_name'		=> 'filter_by'
				, 'value'					=> jvfrm_spot_shortcode_taxonomies( 'post', Array( __( "None", 'javo' )	=> '' ) )
			)

			/** Custom Post Taxonomies */
			, Array(
				'type'					=> 'checkbox'
				, 'group'				=> $groupFilter
				, 'heading'				=> __( "Use Custom Terms", 'javo')
				, 'holder'				=> 'div'
				, 'dependency'		=> Array(
					'element'			=> 'filter_by'
					, 'not_empty'		=> true
				)
				,  'description'		=> __( "To display specific terms only, please enable. if you use custom terms.", 'javo' )
				, 'param_name'		=> 'custom_filter_by_post'
				, 'value'					=> Array( __( "Enable", 'javo' ) => '1' )
			)

			/** Header / Filter Custom Terms */
			, Array(
				'type'						=> 'textfield'
				, 'group'					=> $groupFilter
				, 'heading'				=> __( "Custom Terms IDs", 'javo')
				, 'holder'				=> 'div'
				, 'dependency'		=> Array(
					'element'			=> 'custom_filter_by_post'
					, 'value'				=> '1'
				)
				, 'param_name'		=> 'custom_filter'
				, 'description'			=> __( "Enter category IDs separated by commas (ex: 13,23,18). To exclude categories please add '-' (ex: -9, -10)", 'javo' )
				, 'value'					=> ''
			)

			/**
			 *	@group : Advanced
			 */
			 , Array(
				'type'						=> 'dropdown'
				, 'group'					=> $groupAdvanced
				, 'heading'				=> __( "Display excerpt", 'javo' )
				, 'holder'				=> 'div'
				, 'param_name'		=> 'module_contents_hide'
				, 'value'					=> Array(
					__( "Enable", 'javo' )		=> '',
					__( "Disable", 'javo' )		=> 'hide'
				)
			)
			, Array(
				'type'						=> 'textfield'
				, 'group'					=> $groupAdvanced
				, 'heading'				=> __( "Limit length of excerpt", 'javo' )
				, 'holder'				=> 'div'
				, 'description'			=> __( "( 0 = Unlimited )", 'javo' )
				, 'param_name'		=> 'module_contents_length'
				, 'value'					=> '0'
			)
		);

		$arrCommonParam		= apply_filters( $prefix . 'commonParam', $arrCommonParam, $arrGroupStrings );
		$arrPaginationParam		= Array(

			/**
			 *	@group : Style
			 */

			 /** Pagination Style */
			Array(
				'type'						=> 'dropdown'
				, 'group'					=> $groupStyle
				, 'heading'				=> __( "Load More Type", 'javo')
				, 'holder'				=> 'div'
				, 'class'					=> ''
				, 'param_name'		=> 'pagination'
				, 'value'					=> Array(
					__( "None", 'javo' )				=> ''
					, __( "Pagination", 'javo' )	=> 'number'
					, __( "Load More", 'javo' )	=> 'loadmore'
				)
			)
		);

		$arrAmountParam							=  Array(

			/**
			 *	@group : Advanced
			 */

			/** Posts Per Page */
			Array(
				'type'										=> 'textfield'
				, 'group'									=> $groupAdvanced
				, 'heading'									=> __( "Number of posts to load", 'javo')
				, 'holder'									=> 'div'
				, 'class'										=> ''
				, 'param_name'							=> 'count'
				, 'value'										=> 5
			)
		);

		/** module hover style **/
		$arrHoverStyleParam = Array(
			/**
			 *	@group : Effect
			 */
			Array(
				'type'			=> 'dropdown'
				, 'group'		=> $groupEffect
				, 'heading'		=> __( "Module Hover Effect", 'javo')
				, 'holder'		=> 'div'
				, 'class'		=> ''
				, 'param_name'	=> 'hover_style'
				, 'value'		=> Array(
					__( "None", 'javo' )				=> ''
					, __( "Effect 1 (Zoom In)", 'javo' )	=> 'zoom-in'
					, __( "Effect 2 (Dark Fade In)", 'javo' )	=> 'dark-fade-in'
				)
			)
		);

		// Parse Shortcodes
		if( ! empty( $arrShortcodes ) ) : foreach( $arrShortcodes as $scdName => $scdAttr ) :

			// File Exists
			if( isset( $scdAttr[ 'file' ] ) )
				$fnShortcode		= $scdAttr[ 'file' ];
			else
				$fnShortcode		= $dirShortcode . 'shortcode-' .$scdName . '.php';

			// Other Shortcode
			if( ! file_exists( $fnShortcode ) )
				continue;
			else
				require_once $fnShortcode;

			// Shortcode icon
			$scdAttr[ 'icon' ]	= isset( $scdAttr[ 'icon' ] ) ? $scdAttr[ 'icon' ] : 'javo-vc-icon';

			// Default Setting
			$scdAttr				= wp_parse_args(
				Array(
					'base'			=> $scdName
					, 'category'	=> __( "Javo", 'javo' )
				)
				, $scdAttr
			);

			// Merge Parametters
			if( empty( $scdAttr[ 'params' ] ) ){
				$scdAttr[ 'params']	= $arrCommonParam;
			}else{
				$scdAttr[ 'params']	= wp_parse_args( $scdAttr[ 'params'], $arrCommonParam );
			}

			if( isset( $scdAttr[ 'more' ] ) )
				$scdAttr[ 'params']	= wp_parse_args( $arrPaginationParam, $scdAttr[ 'params'] );

			if( isset( $scdAttr[ 'column' ] ) && 0 < intVal( $scdAttr[ 'column' ] ) )
			{
				$arrColumn					= Array();
				for( $intCount = 1; $intCount <= $scdAttr[ 'column' ]; $intCount ++ )
					$arrColumn[ $intCount . ' ' . _n( "Column", "Columns", $intCount, 'javo' ) ] = $intCount;

				$scdAttr[ 'params']		= wp_parse_args(
					Array(
						Array(
							'type'				=> 'dropdown'
							, 'group'			=> $groupContent
							, 'heading'			=> __( "Columns", 'javo')
							, 'holder'			=> 'div'
							, 'class'				=> ''
							, 'param_name'	=> 'columns'
							, 'value'				=> $arrColumn
						)
					)
					, $scdAttr[ 'params']
				);
			}

			if( isset( $scdAttr[ 'hide_thumb' ] ) )
			{
				$scdAttr[ 'params']		= wp_parse_args(
					Array(
						Array(
							'type'				=> 'dropdown'
							, 'group'			=> $groupContent
							, 'heading'			=> __( "Display Posts Thumbnails", 'javo')
							, 'holder'			=> 'div'
							, 'class'				=> ''
							, 'param_name'	=> 'hide_thumbnail'
							, 'value'				=> Array(
								__( "Visible", 'javo' )	=> '',
								__( "Hidden", 'javo' )	=> 'hide'
							)
						)
					)
					, $scdAttr[ 'params']
				);
			}

			if( isset( $scdAttr[ 'hide_avatar' ] ) )
			{
				$scdAttr[ 'params']		= wp_parse_args(
					Array(
						Array(
							'type'				=> 'dropdown'
							, 'group'			=> $groupContent
							, 'heading'			=> __( "Display User Avatar", 'javo')
							, 'holder'			=> 'div'
							, 'class'				=> ''
							, 'param_name'	=> 'hide_avatar'
							, 'value'				=> Array(
								__( "Visible", 'javo' )	=> false,
								__( "Hidden", 'javo' )	=> true
							)
						)
					)
					, $scdAttr[ 'params']
				);
			}

			if( !isset( $scdAttr[ 'fixed_count' ] ) )
				$scdAttr[ 'params']	= wp_parse_args( $arrAmountParam, $scdAttr[ 'params'] );

			/* module hover style */
			if(isset($scdAttr['hover_style']))
				$scdAttr['params'] = wp_parse_args($arrHoverStyleParam, $scdAttr['params']);

			add_shortcode( $scdName, 'jvfrm_spot_parse_shortcode' );

			// Register shortcode in visual composer
			if( function_exists( 'vc_map' ) )
				vc_map( $scdAttr );

		endforeach; endif;

		$arrOtherShortcode = apply_filters( 'jvfrm_spot_other_shortcode_array', Array() );
		if( !empty( $arrOtherShortcode ) ) foreach( $arrOtherShortcode as $shortcode_name => $shortcode_callback )
			add_shortcode( $shortcode_name, $shortcode_callback );


		if( function_exists( 'vc_map' ) ) :

			// Category Block
			vc_map(array(
				'base'				=> 'jvfrm_spot_category_box'
				, 'name'				=> __( "Category Block", 'javo' )
				, 'icon'				=> 'jv-vc-shortcode-icon shortcode-featured'
				, 'category'		=> __( "Javo", 'javo' )
				, 'params'			=> Array(
				Array(
					'type'				=>'dropdown'
					, 'heading'			=> __('Row Column', 'javo')
					, 'holder'			=> 'div'
					, 'class'				=> ''
					, 'param_name'	=> 'column'
					, 'value'				=> Array(
						'1/3'				=> '1-3'
						, '2/3'			=> '2-3'
						, 'full'				=> 'full'
					)
				)
				, Array(
					'type'				=>'textfield'
					, 'heading'			=> __('Item Name', 'javo')
					, 'holder'			=> 'div'
					, 'class'				=> ''
					, 'param_name'	=> 'jvfrm_spot_featured_block_title'
					, 'value'				=> ''
				)
				, Array(
					'type'				=>'textfield'
					, 'heading'			=> __('Item Description', 'javo')
					, 'holder'			=> 'div'
					, 'class'				=> ''
					, 'param_name'	=> 'jvfrm_spot_featured_block_description'
					, 'value'				=> ''
				)
				, Array(
					'type'				=>'colorpicker'
					, 'heading'			=> __('Name Color', 'javo')
					, 'holder'			=> 'div'
					, 'group'			=> $groupStyle
					, 'param_name'	=> 'text_color'
					, 'value'				=> '#fff'
				)
				, Array(
					'type'				=>'colorpicker'
					, 'heading'			=> __('Name Sub Color', 'javo')
					, 'holder'			=> 'div'
					, 'group'			=> $groupStyle
					, 'param_name'	=> 'text_sub_color'
					, 'value'				=> '#fff'
				)
				, Array(
					'type'				=>'colorpicker'
					, 'heading'			=> __('Overlay Color', 'javo')
					, 'holder'			=> 'div'
					, 'group'			=> $groupStyle
					, 'param_name'	=> 'overlay_color'
					, 'value'				=> '#34495e'
				)
				/*, Array(
					'type'				=>'dropdown'
					, 'heading'			=> __('Original Featured Item', 'javo')
					, 'holder'			=> 'div'
					, 'class'				=> ''
					, 'param_name'=> 'jvfrm_spot_featured_block_id'
					, 'value'				=> apply_filters( 'jvfrm_spot_get_fetured_item', Array( __( "Select One", 'javo' ) => '' ) )
				)*/
				, Array(
					'type'				=>'textfield'
					, 'heading'			=> __( 'Link Parameter', 'javo')
					, 'holder'			=> 'div'
					, 'class'				=> ''
					, 'group'			=> $groupAdvanced
					, 'param_name'	=> 'jvfrm_spot_featured_block_param'
					, 'value'				=> ''
				)
				, Array(
					'type'				=>'attach_image'
					, 'heading'			=> __('Image (If you want another image)', 'javo')
					, 'holder'			=> 'div'
					, 'class'				=> ''
					, 'group'			=> $groupAdvanced
					, 'param_name'	=> 'attachment_other_image'
					, 'value'				=> ''
				)

				/*, Array(
					'type'				=>'dropdown'
					, 'heading'			=> __( 'Link Type', 'javo')
					, 'holder'			=> 'div'
					, 'class'				=> ''
					, 'group'			=> $groupAdvanced
					, 'param_name'	=> 'jvfrm_spot_block_link_type'
					, 'value'				=> array(
						__("Map","javo") => "map"
						, __("Social","javo") => "social"
					)
				)*/
				, Array(
					'type'				=>'dropdown'
					, 'heading'			=> __( "Map Template Page", 'javo')
					, 'holder'			=> 'div'
					, 'class'				=> ''
					, 'param_name'	=> 'map_template'
					, 'value'				=> apply_filters(
						'jvfrm_spot_get_map_templates'
						, Array( __( "No set", 'javo') => '' )
						)
					)
				)
			));
			// Category Block 2
			/*vc_map(
				Array(
					'base'				=> 'jvfrm_spot_category_box2'
					, 'name'				=> __( "Category Block 2", 'javo' )
					, 'icon'				=> 'jv-vc-shortcode-icon shortcode-featured'
					, 'category'		=> __( "Javo", 'javo' )
					, 'params'			=> Array()
				)
			);
*/

			// Author's Slider
			/*vc_map(array(
				'base'						=> 'jvfrm_spot_slider_authors'
				, 'name'						=> __( "Authors Slider", 'javo' )
				, 'category'				=> __( "Javo", 'javo')
				, 'icon'						=> 'jv-vc-shortcode-icon shortcode-authors'
				, 'params'					=> Array(
					Array(
						'type'				=> 'textfield'
						, 'heading'			=> __( "User listing setting", 'javo')
						, 'holder'			=> 'div'
						, 'class'				=> ''
						, 'description'	=> __( "Default (10 New users) or Please add user id. comma (,) for a separator. ex) 11, 23, 44", 'javo' )
						, 'param_name'	=> 'user_ids'
						, 'value'				=> ''
					)
					, Array(
						'type'				=> 'textfield'
						, 'heading'			=> __('Display amount of users.', 'javo')
						, 'holder'			=> 'div'
						, 'group'			=> $groupAdvanced
						, 'class'				=> ''
						, 'param_name'	=> 'max_amount'
						, 'description'	=> __('(Only Number. recomend around 8)', 'javo')
						, 'value'				=> intVal( 0 )
					)
					, Array(
						'type'				=> 'textfield'
						, 'heading'			=> __('Total amount of author to display.', 'javo')
						, 'holder'			=> 'div'
						, 'group'			=> $groupAdvanced
						, 'class'				=> ''
						, 'param_name'	=> 'total_loading_items'
						, 'value'				=> ''
					)
					, Array(
						'type'				=> 'textfield'
						, 'heading'			=> __('Radius (0~50)', 'javo')
						, 'holder'			=> 'div'
						, 'group'			=> $groupStyle
						, 'class'				=> ''
						, 'param_name'	=> 'radius'
						, 'description'	=> __('Category image radius', 'javo')
						, 'value'				=> (int)0
					)
					, Array(
						'type'				=>'colorpicker'
						, 'heading'			=> __( "Author Name Color", 'javo')
						, 'holder'			=> 'div'
						, 'group'			=> $groupStyle
						, 'param_name'	=> 'inline_author_text_color'
						, 'value'				=> ''
					), Array(
						'type'				=>'colorpicker'
						, 'heading'			=> __('Name Hover Color', 'javo')
						, 'holder'			=> 'div'
						, 'group'			=> $groupStyle
						, 'param_name'	=> 'inline_cat_text_hover_color'
						, 'value'				=> ''
					), Array(
						'type'				=>'colorpicker'
						, 'heading'			=> __('Arrow Color', 'javo')
						, 'group'			=> $groupStyle
						, 'holder'			=> 'div'
						, 'param_name'	=> 'inline_author_arrow_color'
						, 'value'				=> ''
					)
				)
			));
*/

			// Mailchimp
			/*
			vc_map(array(
				'base'						=> 'jvfrm_spot_mailchimp'
				, 'name'						=> __( "Mailchimp 1", 'javo' )
				, 'icon'						=> 'jv-vc-shortcode-icon shortcode-mailchimp'
				, 'category'				=> __('Javo', 'javo')
				, 'params'					=> Array(
					Array(
						'type'				=> 'dropdown'
						, 'heading'			=> __("LIST ID", 'javo')
						, 'holder'			=> 'div'
						, 'class'				=> ''
						, 'param_name'	=> 'list_id'
						, 'description'	=> __('You need to create a list id on mailchimp site, if you don`t have', 'javo')
						, 'value'			=> apply_filters( 'jvfrm_spot_mail_chimp_get_lists',  Array(
							__( "Theme Setting > General > Plugin > API KEY (Please add your API key)", 'javo') => ''
						) )
					)
				)
			) );
			*/
		endif;


		do_action( 'jvfrm_spot_shortcodes_loaded', $arrShortcodes );

		// Parse Modules
		if( ! empty( $arrModules ) ) : foreach( $arrModules as $artName => $artAttr ) {
			if( ! file_exists( $artAttr[ 'file'] ) )
				continue;
			require_once $artAttr[ 'file' ];
		} endif;

		do_action( 'jvfrm_spot_modules_loaded', $arrModules );
	}

	// Shortcode contents output
	function jvfrm_spot_parse_shortcode( $attr, $content=null, $tag )
	{
		if( !class_exists( $tag ) )
			return '';

		$obj	= new $tag();
		return $obj->output( $attr, $content );
	}
endif;
