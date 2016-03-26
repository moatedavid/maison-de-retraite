<?php

if( !defined( 'ABSPATH' ) )
	die;

class jvfrm_spot_Directory_Template extends jvfrm_spot_Directory
{
	public $is_archive = false;

	private $map_slug = '';

	private $filter_position = false;

	private $single_type = 'type-a';

	private static $instance = false;

	public $is_review_plugin_active = false;

	public $json_file = '';

	public function __construct()
	{
		global $jvfrm_spot_tso;

		if( $this->option			= $jvfrm_spot_tso->get( 'lv_listing_single_type', false ) )
			$this->single_type		= $this->option;

		// Fixed Type A
		$this->single_type			= 'type-a';

		$this->map_slug				= 'multipleBox';
		self::$instance				= &$this;
		$this->active_hooks();

		if( function_exists( 'lava_directory' ) )
			$this->json_file = lava_directory()->core->getJsonFileName();

		if( class_exists( 'Lava_Directory_Review' ) )
			$this->is_review_plugin_active	= true;
	}

	public function active_hooks() {

		/* Common */

			// Mobile Menu
			add_action( 'wp_footer', Array( $this, 'custom_mobile_menul' ) );
			add_filter( 'jvfrm_spot_core_post_type', Array( $this, 'getSlug' ) );

		/* Single */ {

			// Header
			add_filter( 'body_class' , Array( $this, 'custom_single_body_class' ) );

			add_filter( 'jvfrm_spot_' . self::SLUG . '_single_title', Array( $this, 'rating_title' ) );
			///add_filter( 'get_header' , Array( $this, 'single_template_remove_margin' ) );
			add_filter( 'jvfrm_spot_post_title_header', Array( $this, 'hidden_sintle_title' ), 10, 2 );
			add_filter( 'jvfrm_spot_single_post_types_array', Array( $this, 'custom_single_transparent' ) );
			add_action( 'lava_' . self::SLUG . '_single_container_before', Array( $this, 'custom_single_header' ) );

			add_action( 'jvfrm_spot_' . self::SLUG . '_single_body', Array( $this, 'custom_single_body' ) );
			add_action( 'lava_' . self::SLUG . '_manager_single_enqueues', Array( $this, 'custom_single_enqueues' ) );

			add_action( 'jvfrm_spot_' . self::SLUG . '_single_description_before', Array( $this, 'append_video' ) );
			add_action( 'jvfrm_spot_' . self::SLUG . '_single_description_before', Array( $this, 'append_3DViewer' ) );
			add_action( 'jvfrm_spot_' . self::SLUG . '_single_description_before', Array( $this, 'append_vendor_produce' ) );

			// Custom CSS
			add_filter( 'jvfrm_spot_custom_css_rows', Array( $this, 'custom_single_template_css_row' ), 20 );

			// Get Direction
			add_action( 'jvfrm_spot_' . self::SLUG . '_single_map_after', Array( $this, 'append_get_direction' ) );

			// Navigation
			add_filter( 'jvfrm_spot_detail_item_nav', Array( $this, 'custom_single_header_nav' ) );

			// Footer
			add_action( 'lava_' . self::SLUG . '_single_container_after', Array( $this, 'custom_single_dot_nav' ) );
		}

		/* Map */ {

			// Map Data
			add_action( 'lava_' . self::SLUG . '_setup_mapdata', Array( $this, 'custom_map_parameter' ), 10, 3 );

			// Map Template Hooks
			add_action( 'lava_' . self::SLUG . '_map_wp_head', Array( $this, 'custom_map_hooks' ) );

			// Custom CSS
			add_filter( 'jvfrm_spot_custom_css_rows', Array( $this, 'custom_map_template_css_row' ), 30 );

			// Header
			add_action( 'lava_' . self::SLUG . '_map_box_enqueue_scripts', Array( $this, 'custom_map_enqueues' ) );
			add_action( 'lava_' . self::SLUG . '_map_container_before', Array( $this, 'custom_before_setup' ) );

			// Body
			add_action( 'jvfrm_spot_' . self::SLUG . '_map_body', Array( $this, 'custom_load_map' ) );

			// Body Class
			add_filter( 'lava_' . self::SLUG . '_map_classes', Array( $this, 'custom_map_classes' ) );

			// Footer
			add_action( 'lava_' . self::SLUG . '_map_container_after', Array( $this, 'custom_map_scripts' ) );

			// Map List Type
			add_filter( 'jvfrm_spot_' . self::SLUG . '_map_list_filters', Array( $this, 'custom_mapList_filters' ) );
			// add_filter( 'jvfrm_spot_' . self::SLUG . '_map_list_wrap_before', Array( $this, 'listing_mobile_filter' ) );

			// Load Templates
			add_filter( 'lava_' . self::SLUG . '_map_htmls' , Array( $this, 'custom_map_htmls' ), 10, 2 );

			add_filter( 'jvfrm_spot_template_map_module_options', Array( $this, 'map_no_lazyload' ) );
			add_filter( 'jvfrm_spot_template_list_module_options', Array( $this, 'map_no_lazyload' ) );

			//Filter
			$this->setFilterFields();

			add_action( 'jvfrm_spot_map_output_class', Array( $this, 'mapOutput_class' ) );
			add_action( 'jvfrm_spot_map_list_output_class', Array( $this, 'listOutput_class' ) );

			// Option
			// add_action( 'jvfrm_spot_core_map_template_layout_setting_after', Array( $this, 'layout_setting' ), 10, 2 );
		}

		/* Archive */
		{
			add_filter( 'jvfrm_spot_template_list_module', Array( $this, 'archive_map_list_module' ), 10, 2 );
			add_filter( 'jvfrm_spot_template_list_module_loop', Array( $this, 'archive_map_list_module_loop' ), 10, 3 );
			add_filter( 'jvfrm_spot_template_map_module', Array( $this, 'archive_map_module' ), 10, 2 );
			add_filter( 'jvfrm_spot_template_map_module_loop', Array( $this, 'archive_map_module_loop' ), 10, 3 );

			add_filter( 'lava_' . self::SLUG . '_get_template' , Array( $this, 'custom_archive_page' ), 10, 3 );
			add_filter( 'jvfrm_spot_map_class', Array( $this, 'custom_map_class' ), 30, 2 );
			add_filter( 'jvfrm_spot_' . self::SLUG . '_map_list_content_column_class', Array( $this, 'custom_list_content_column' ), 10, 3 );
			add_action( 'jvfrm_spot_' . self::SLUG . '_map_list_container_before', Array( $this, 'map_list_container_before' ), 15 );
			add_action( 'jvfrm_spot_' . self::SLUG . '_map_list_container_after', Array( $this, '_map_list_container_after' ) );
		}

		/* My Page */ {
			add_action( 'template_redirect', Array( $this, 'price_table_redirect' ), 9 );
			add_filter( 'jvfrm_spot_dashboard_slugs' , Array( $this, 'custom_register_slug' ) );
			add_filter( 'jvfrm_spot_mypage_sidebar_args', Array( $this, 'custom_mypage_sidebar' ) );
			add_filter( 'jvfrm_spot_dashboard_custom_template_url', Array( $this, 'append_properties' ), 50, 2 );
			add_filter( 'jvfrm_spot_dashboard_type-b_nav', Array( $this, 'custom_type_b_mypage_nav' ) );
			add_filter( 'jvfrm_spot_dashboard_mylists', Array( $this, 'dashboard_mylists' ) );
			add_action( 'jvfrm_spot_module_html_after', Array( $this, 'dashboard_mylists_edit_button' ), 10, 2);
		}


		/* Widget */{
			add_filter( 'jvfrm_spot_recent_posts_widget_excerpt', Array( $this, 'core_recentPostsWidget' ), 10, 4 );
			add_filter( 'jvfrm_spot_recent_posts_widget_describe_type_options', Array( $this, 'core_recentPostsWidgetOption' ), 10, 1 );
		}
	}


	public function load_template( $template_name, $extension='.php' , $params=Array(), $_once=true ) {

		if( !empty( $params ) )
			extract( $params );

		$strFileName = jvfrm_spot_core()->template_path . '/' . $template_name . $extension;

		if( file_exists( $strFileName ) ) {
			if( $_once ) {
				require_once $strFileName;
			}else{
				require $strFileName;
			}
			return true;
		}

		return false;

	}

	public function custom_single_body_class( $body_classes=Array() )
	{
		$body_classes[]		= $this->single_type;

		if( get_post_type() == self::SLUG )
			$body_classes[]	= 'no-sticky';

		return $body_classes;
	}

	public function rating_title( $title )
	{
		global $post;

		if( ! $this->is_review_plugin_active )
			return  $title;

		if( is_singular( self::SLUG ) ) {
			$title	.= $this->get_header_rating( $post->ID );
		}

		return $title;
	}

	public function get_header_rating( $post_id ){

		$intRatingScore	= $intReviewers = 0;
		$strRatingStars	= '';
		if( $this->is_review_plugin_active ) {
			$intRatingScore		= call_user_func( Array( lv_directoryReview()->core, 'get' ), 'average' );
			$intReviewers		= call_user_func( Array( lv_directoryReview()->core, 'get' ), 'count'  );
			$strRatingStars		= call_user_func( Array( lv_directoryReview()->core, 'fa_get' ) );
		}
		return join(
			"\n",
			Array(
				'<a href="#javo-item-review-section" class="link-review">',
					$strRatingStars,
					'<span class="review-score">',
						$intRatingScore,
					'</span>',
					'<span class="review-count">',
						$intReviewers . ' ',
						_n( "Vote", "Votes", intVal( $intReviewers ), 'javospot' ),
					'</span>',
				'</a>',
			)
		);
	}

	public function hidden_sintle_title( $post_title='', $post=null )
	{
		if( is_null( $post ) || get_post_type( $post ) === self::SLUG )
			$post_title = null;

		return $post_title;
	}

	public function custom_single_transparent( $post_types=Array() ){
		$post_types[]	= self::SLUG;
		return $post_types;
	}

	public function custom_single_enqueues(){
		wp_enqueue_style( 'single-css' );
		wp_enqueue_style( 'style-min-css' );
		wp_enqueue_script( 'jquery-sticky' );
		wp_enqueue_script( 'ZeroClipboard-min' );
		wp_enqueue_script( 'light-gallery' );

		wp_localize_script(
			sanitize_title( 'jv-single.js' ),
			'jvfrm_spot_custom_post_param',
			Array(
				'widget_sticky' => jvfrm_spot_tso()->get( jvfrm_spot_core()->slug . '_single_sticky_widget' ),
				'map_type' => jvfrm_spot_tso()->get( jvfrm_spot_core()->slug . '_map_width_type' )
			)
		);

		wp_enqueue_script( sanitize_title( 'jv-single.js' ) );
	}

	public function custom_single_header() {
		$this->load_template( 'part-single-header-' . $this->single_type );
	}

	public function custom_single_body() {
		$this->load_template( 'template-single-' . $this->single_type );
	}

	public function append_video()
	{
		if( !function_exists( 'lava_directory_video' ) )
			return;
		lava_directory_video()->core->append_video();
	}

	public function append_3DViewer()
	{
		if( !function_exists( 'lava_directory_3DViewer' ) )
			return;
		lava_directory_3DViewer()->core->append_3DViewer();
	}

	public function append_vendor_produce()
	{
		if( !function_exists( 'lava_directory_vendor' ) )
			return;

		if( ! $intVendorID = intVal( lava_directory_vendor()->core->getVendorID() ) )
			return;

		$this->load_template(
			'part-single-vendor-products',
			'.php',
			Array(
				'vendor_products' => lava_directory_vendor()->core->getProducts(
					Array(
						'vendor'	=> $intVendorID,
						'count'		=> 6,
					)
				)
			)
		);
	}

	public function custom_single_template_css_row( $rows=Array() ){
		$strPrefix = 'html body.single.single-' . jvfrm_spot_core()->slug . ' ';

		$rows[] = $strPrefix . 'header#header-one-line nav.javo-main-navbar';
		$rows[] = '{ top:auto; position:relative; left:auto; right:auto; }';

		return $rows;
	}

	public function append_get_direction(){
		if( !function_exists( 'lava_directory_direction'  ) )
			return;

		$this->load_template(
			'part-single-get-direction',
			'.php',
			Array(
				'objGetDirection' => lava_directory_direction()->template
			)
		);
	}

	public function custom_single_header_nav( $args=Array() ) {

		$arrAllowPostTypes						= apply_filters( 'jvfrm_spot_single_post_types_array', Array( 'lv_listing' ) );
		$append_args								= Array();
                
		$append_args[ 'javo-item-photos-section'	 ]	= Array(
			'label'									=> esc_html__( "Photos", 'javospot' )
			, 'class'									=> 'glyphicon glyphicon-tasks'
			, 'type'									=> $arrAllowPostTypes
		);                

		$append_args[ 'javo-item-describe-section' ]	= Array(
			'label'									=> esc_html__( "Description", 'javospot' )
			, 'class'									=> 'glyphicon glyphicon-align-left'
			, 'type'									=> $arrAllowPostTypes
		);
		$append_args[ 'javo-item-amenities-section'	 ]	= Array(
			'label'									=> esc_html__( "Amenities", 'javospot' )
			, 'class'									=> 'glyphicon glyphicon-ok-circle'
			, 'type'									=> $arrAllowPostTypes
		);                
		if( class_exists( 'Lava_Directory_Review' ) ) :
			$append_args[ 'javo-item-review-section' ]	= Array(
				'label'									=> esc_html__( "Review", 'javospot' )
				, 'class'									=> 'glyphicon glyphicon-comment'
				, 'type'									=> $arrAllowPostTypes
			);
		endif;
		$append_args[ 'javo-item-condition-section'	 ]	= Array(
			'label'									=> esc_html__( "Détail", 'javospot' )
			, 'class'									=> 'glyphicon glyphicon-tasks'
			, 'type'									=> $arrAllowPostTypes
		);                
		$append_args[ 'javo-item-location-section' ]		= Array(
			'label'									=> esc_html__( "Location", 'javospot' )
			, 'class'									=> 'glyphicon glyphicon-map-marker'
			, 'type'									=> $arrAllowPostTypes
		);

		return wp_parse_args( $append_args, $args );
	}

	public function custom_mobile_menul() {
		$this->load_template( 'html-mobile-menu' );
		$this->load_template( 'html-modal-mobile-search' );
	}

	public function custom_single_dot_nav() {
		$this->load_template( 'part-single-dot-nav' );
	}

	public function custom_map_parameter( $obj, $get=false, $post=false )
	{
		global $wp_query;

		$objQueried					= $wp_query->queried_object;
		$objQueried->term_id	= isset( $objQueried->term_id ) ? $objQueried->term_id : 0;

		if( !is_a( $obj, 'WP_Post' )  || !$get | !$post )
			return;

		$obj->req_listing_category	= $get->get( 'category', $post->get( 'category', $objQueried->term_id ) );
		$obj->req_listing_location		= $get->get( 'location', $post->get( 'location', $objQueried->term_id ) );
		$obj->req_listing_amenities	= $get->get( 'amenity', $post->get( 'amenity', $objQueried->term_id ) );
		$obj->req_is_geolocation		= $get->get( 'geolocation', $post->get( 'geolocation', false ) );

		// Header Hidden
		add_filter( 'jvfrm_spot_post_title_header', '__return_false' );
	}

	public function custom_map_hooks() {

		$options = (Array) get_post_meta( get_the_ID(), 'jvfrm_spot_map_page_opt', true );
		$strOptionName = 'mobile_type';

		if(
			is_array( $options ) &&
			!empty( $options[ $strOptionName ] )  &&
			$options[ $strOptionName ] == 'ajax-top'
		){
			add_action( 'jvfrm_spot_header_inner_logo_after', Array( $this, 'addition_inner_switcher' ), 15 );
		}

	}

	public function custom_map_template_css_row( $rows ){
		$strPrefix		= 'html body.page-template.page-template-lava_' . jvfrm_spot_core()->slug . '_map' . ' ';
		$strPrimary		= jvfrm_spot_tso()->get( 'total_button_color', false );
		$strPrimary_text = jvfrm_spot_tso()->get( 'primary_font_color', false );
		$strPrimary_border = 'none';
		if(jvfrm_spot_tso()->get('total_button_border_use', false) == 'use' && jvfrm_spot_tso()->get( 'total_button_border_color')!='' ){
			$strPrimary_border = '1px solid '.jvfrm_spot_tso()->get( 'total_button_border_color', false );
		}
		$strPrimaryRGB	= apply_filters( 'jvfrm_spot_rgb', substr( $strPrimary, 1) );

		if( $strPrimary ){
			$rows[] = $strPrefix . ".javo-shortcode .module .meta-category:not(.no-background),";
			$rows[] = $strPrefix . ".javo-shortcode .module .media-left .meta-category:not(.no-background)";
			/** ----------------------------  */
			$rows[] = "{ background-color:{$strPrimary}; color:{$strPrimary_text}; border:{$strPrimary_border}; }";

			$rows[] = $strPrefix . ".javo-shortcode .module.javo-module12 .thumb-wrap:hover .javo-thb:after";
			/** ----------------------------  */
			$rows[] = "{ background-color:rgba({$strPrimaryRGB['r']}, {$strPrimaryRGB['g']}, {$strPrimaryRGB['b']}, .92); }";
		}

		return apply_filters( 'jvfrm_spot_' . self::SLUG . '_custom_map_css_rows', $rows, $strPrefix );
	}

	public function custom_map_enqueues() {
		global $post;

		$is_empty_post = false;

		wp_enqueue_script( 'jQuery-nouiSlider' );
		wp_enqueue_script( 'jquery-type-header' );
		wp_enqueue_script( 'selectize-script' );
		wp_enqueue_script( 'jquery-sticky' );
		wp_enqueue_script( 'jQuery-chosen-autocomplete' );

		if( !is_object( $post ) ){
			$is_empty_post = true;
			$post = new stdClass();
			$post->lava_type = $this->slug;
			$post->ID = 0;
		}

		$objOptions = new jvfrm_spot_Array( (Array)get_post_meta( $post->ID, 'jvfrm_spot_map_page_opt', true ) );

		wp_localize_script(
			sanitize_title( 'jv-map-template.js' ),
			'jvfrm_spot_core_map_param',
			Array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'json_file' => $this->json_file,
				'loadmore_amount' => $objOptions->get( 'loadmore_amount', 10 ),
				'template_id' => $post->ID,
				'map_primary_color' => $objOptions->get( 'map_primary_color' ),
				'allow_wheel' => jvfrm_spot_tso()->get( 'map_allow_mousewheel', 'a' ),
				'cluster' => $objOptions->get( 'cluster', '' ),
				'cluster_level' => $objOptions->get( 'cluster_level', 100 ),
				'distance_max' => $objOptions->get( 'distance_max', 100 ),
				'marker_type' => $objOptions->get( 'marker_type' ),
				'map_zoom' => $objOptions->get( 'init_map_zoom' ),
				'map_marker' => $objOptions->get( 'map_marker', jvfrm_spot_tso()->get( 'map_marker' ) ),
				'map_marker_hover' => $objOptions->get( 'after_map_marker' ),
				'link_type' => $objOptions->get( 'link_type' ),
				'marker_zoom_level' => $objOptions->get( 'marker_zoom_level' ),
				'panel_list_featured_first' => $objOptions->get( 'panel_list_featured_first' ),
				'panel_list_random' => $objOptions->get( 'panel_list_random' ),
				'selctize_terms' => Array( 'listing_category', 'listing_location' ),
				'javo-cluster-multiple' =>  apply_filters(
					"jvfrm_spot_{$post->lava_type}_map_cluster_str_multiple",
					esc_html__( "This place contains multiple places. please select one.", 'javospot' )
				),
				'strLocationAccessFail' => esc_html__( "Your position access failed.", 'javospot' ),
			)
		);

		if( $is_empty_post )
			$post=  null;

		wp_enqueue_script( sanitize_title( 'jv-map-template.js' ) );
	}

	public function custom_before_setup( $post )
	{
		$is_core_map_actived = class_exists( 'Javo_Spot_Core_Map' );
		if( get_post_meta( get_post()->ID, '_map_filter_position', true ) == 'map-layout-search-top' && $is_core_map_actived )
			add_action( 'jvfrm_spot_' . self::SLUG . '_map_switcher_before', Array( $this, 'custom_map_listing_filter' ) );
		else
			add_action( 'jvfrm_spot_' . self::SLUG . '_map_lists_before', Array( $this, 'custom_map_listing_filter' ) );

	}

	public function addition_inner_switcher(){
		$this->load_template( 'part-map-filter-inner-switcher', '.php', Array( 'post' => $GLOBALS[ 'post' ] ), false );
	}

	public function custom_load_map() {

		$strFileName	= jvfrm_spot_core()->template_path .'/template-map-multipleBox.php';
		if( ! file_exists( $strFileName ) ) {
			esc_html_e( "Not found template type", 'javospot' );
			return;
		}
		require_once $strFileName;
	}

	public function custom_map_classes( $classes=Array() ){

		$classes[] = 'no-sticky';
		$classes[] = 'no-smoth-scroll';

		$options = (Array) get_post_meta( get_the_ID(), 'jvfrm_spot_map_page_opt', true );
		$strOptionName = 'mobile_type';

		if( is_array( $options ) && !empty( $options[ $strOptionName ] ) )
			$classes[] = 'mobile-' . $options[ $strOptionName ];

		return $classes;
	}

	public function custom_map_scripts() {
		global $jvfrm_spot_Directory;

		$strFileName		= Array();
		$strFileName[]		= $jvfrm_spot_Directory->template_path;
		$strFileName[]		= 'scripts-map-'. $this->map_slug . '.php';
		$strFileName		= @implode( '/', $strFileName );

		if( !file_exists( $strFileName ) ){
			echo $strFileName;
			return;
		}

		require_once $strFileName;
	}

	public function custom_mapList_filters( $filters=Array() ){

		global $post;

		return Array(
			wp_parse_args(
				Array(
					'geo-location'	=>
						Array(
							'label'		=> esc_html__( "Location", 'javospot' )
							, 'inner'		=> Array(
								Array(
									'ID'					=> 'javo-map-box-location-trigger'
									, 'type'				=> 'location'
									, 'class'				=> 'form-control javo-location-search'
									, 'value'				=> $post->jvfrm_spot_current_rad
									, 'placeholder'	=> esc_html__( "Location", 'javospot' )
								)
								, Array( 'type'		=> 'separator' )
								, Array(
									'type'				=> 'division'
									, 'class'				=> 'javo-geoloc-slider-trigger'
								)
							)
						)
					, 'filter-keyword'				=>
						Array(
							'label'						=> esc_html__( "Keyword", 'javospot' )
							, 'inner'						=> Array(
								Array(
									'ID'					=> 'jv-map-listing-keyword-filter'
									, 'type'				=> 'text'
									, 'class'				=> 'form-control jv-keyword-trigger'
									, 'value'				=> $post->lava_current_key
									, 'placeholder'	=> esc_html__( "Keyword", 'javospot' )
								)
							)
						)
					, 'filter-categories'				=>
						Array(
							'label'							=> esc_html__( "Types", 'javospot' )
							, 'inner'						=> Array(
								Array(
									'type'					=> 'select'
									, 'class'				=> 'form-control'
									, 'taxonomy'		=> 'listing_category'
									, 'value'				=> $post->req_listing_category
									, 'placeholder'	=> esc_html__( "Toutes les catégories", 'javospot' )
								)
								, Array(
									'type'				=> 'select'
									, 'class'				=> 'form-control'
									, 'taxonomy'		=> 'listing_location'
									, 'value'				=> $post->req_listing_location
									, 'placeholder'	=> esc_html__( "Toutes les localisations", 'javospot' )
								)
							)
						)
					, 'filter-amenities'				=>
						Array(
							'label'						=> esc_html__( "Amenities", 'javospot' )
							, 'inner'						=> Array(
								Array(
									'type'				=> 'checkbox'
									, 'class'				=> 'form-control'
									, 'taxonomy'		=> 'listing_amenities'
									, 'value'				=> (Array) $post->req_listing_amenities
								)
							)
						)
				)
			), $filters
		);
	}

	public function listing_mobile_filter( $post ) {
		$this->load_template( 'html-map-mobile-listing-menu', '.php', Array( 'post' => $post )  );
	}

	public function custom_map_htmls( $args, $plug_dir ) {
		global $jvfrm_spot_Directory;
		$tmpDir		= $jvfrm_spot_Directory->template_path . '/';
		return Array(
			'javo-map-loading-template'				=> $tmpDir . 'html-map-loading.php'
			, 'javo-map-box-panel-content'			=> $tmpDir . 'html-map-grid-template.php'
			, 'javo-map-box-infobx-content'		=> $tmpDir . 'html-map-popup-contents.php'
			, 'javo-list-box-content'						=> $tmpDir . 'html-list-box-contents.php'
			, 'javo-map-inner-control-template'	=> $tmpDir . 'html-map-inner-controls.php'
		);
	}

	public function map_no_lazyload( $args=Array() ){ return wp_parse_args( Array( 'no_lazy' => true ), $args ); }

	public function mapOutput_class( $classes=Array() ){

		$classes[] = 'module-hover-zoom-in';
		/* $classes[] = 'module-hover-dark-fade-in'; */
		return $classes;

	}

	public function listOutput_class( $classes=Array() ){

		$classes[] = 'module-hover-zoom-in';
		/* $classes[] = 'module-hover-dark-fade-in'; */
		return $classes;
	}

	public function layout_setting( $post, $objOption ) {

		$strOptionName = 'mobile_type';
		$strOptionBuffer = '';
		foreach(
			Array(
				''			=> __( "Default", 'javospot' ),
				'ajax-top'	=> __( "Ajax Top", 'javospot' ),
			) as $value => $label
		) $strOptionBuffer .= sprintf(
			"<option value=\"{$value}\"%s>{$label}</option>",
			selected( $value == $objOption->get( $strOptionName, '' ), true, false )
		);

		printf(
			'<tr><th> <label>%1$s</label> </th><td>
				<select name="jvfrm_spot_map_opts[%2$s]">%3$s</select>
			</td></tr>',
			__( "Mobile Map Search Type", 'javospot' ),
			$strOptionName,
			$strOptionBuffer
		);

	}

	public function setFilterFields() {
		$orderFields		= Array(
			Array(
				'position'	=> 'outer',
				'callback'	=> 'fieldAddress'
			),
			Array(
				'position'	=> 'outer',
				'callback'	=> 'fieldCategory'
			),
			Array(
				'position'	=> 'outer',
				'callback'	=> 'fieldKeyword'
			),
			Array(
				'position'	=> 'inner',
				'callback'	=> 'fieldLocation'
			),
			Array(
				'position'	=> 'inner',
				'callback'	=> 'fieldTax'
			),
			Array(
				'position'	=> 'inner',
				'callback'	=> 'fieldMeta'
			),
		);
		$arrFilters			= apply_filters( 'jvfrm_spot_' . self::SLUG . '_map_filter_orders', $orderFields );

		if( !empty( $arrFilters ) ) : foreach( $arrFilters as $index => $filter ) {
			if( method_exists( $this, $filter[ 'callback' ] ) )
				add_action( 'jvfrm_spot_map_template_filter_' . $filter[ 'position' ], Array( $this, $filter[ 'callback' ] ), intVal( $index + 10 ) );
		} endif;
	}

	public function fieldAddress(){
		$this->load_template( 'part-map-filter-address', '.php', Array( 'post' => $GLOBALS[ 'post' ] ), false );
	}

	public function fieldKeyword(){
		$this->load_template( 'part-map-filter-keyword', '.php', Array( 'post' => $GLOBALS[ 'post' ] ), false );
	}

	public function fieldLocation(){
		$this->load_template( 'part-map-filter-location', '.php', Array( 'post' => $GLOBALS[ 'post' ] ), false  );
	}

	public function fieldCategory(){
		$this->load_template( 'part-map-filter-category', '.php', Array( 'post' => $GLOBALS[ 'post' ] ), false  );
	}

	public function fieldMeta(){
		$this->load_template( 'part-map-filter-meta', '.php', Array( 'post' => $GLOBALS[ 'post' ] ), false  );
	}

	public function fieldTax(){
		$this->load_template( 'part-map-filter-taxonomy', '.php', Array( 'post' => $GLOBALS[ 'post' ] ), false  );
	}


	public function custom_archive_page( $template, $query=false, $obj=false )
	{
		$taxonomy			= empty( $query->queried_object->taxonomy ) ?
			Array() : get_taxonomy( $query->queried_object->taxonomy )->object_type;

		if(
			!empty( $query->queried_object->taxonomy ) &&
			!in_Array( $query->queried_object->taxonomy,
				Array( 'listing_keyword' )
			)
		) if( !empty( $query ) && !empty( $obj ) )
			if( $query->is_archive && in_array( self::SLUG, $taxonomy ) ) {
				$this->is_archive	= true;
				$template				= $obj->get_map_template();
			}

		return $template;
	}

	public function custom_map_class( $classes ){
		if( $this->is_archive ) {
			// hide-switcher
			$classes	= wp_parse_args(
				Array( 'hide-listing-filter' )
				, $classes
			);
		}
		return $classes;
	}

	public function custom_list_content_column( $class_name ) {
		if( $this->is_archive )
			$class_name	= 'col-sm-12';
		return $class_name;
	}

	public function archive_map_list_module( $module_name, $post_id ) {
		if( ! $post_id )
			$module_name		= 'module12';
		return $module_name;
	}

	public function archive_map_list_module_loop( $template, $class_name, $post_id ) {
		if( ! $post_id )
			$template				= "<div class=\"col-md-4\">%s</div>";
		return$template;
	}

	public function archive_map_module( $module_name, $post_id ) {
		if( ! $post_id )
			$module_name		= 'module12';
		return $module_name;
	}

	public function archive_map_module_loop( $template, $class_name, $post_id ) {
		if( ! $post_id )
			$template				= "<div class=\"col-md-6\">%s</div>";
		return$template;
	}

	public function map_list_container_before( $post ) {
		if( $this->is_archive ){
			$this->load_template( 'part-archive-container-header' );
		}
	}

	public function _map_list_container_after( $post ) {
		if( $this->is_archive ){
			$this->load_template( 'part-archive-container-footer' );
		}
	}

	public function custom_register_slug( $args ) {
		return wp_parse_args(
			Array('JVFRM_SPOT_ADDITEM_SLUG'			=> 'add-'.self::SLUG )
			, $args
		);
	}

	public function is_dashboard_page( $slug=false ) {

		$is_dashboard = false;
		if( get_query_var( 'pn' ) == 'member' ){
			$is_dashboard = true;
			if( $slug && get_query_var( 'sub_page' ) != $slug )
				$is_dashboard = false;
		}

		return $is_dashboard;
	}

	public function price_table_redirect(){
		if( $this->is_dashboard_page( 'add-' . self::SLUG ) )
			add_filter( 'lava_' . self::SLUG . '_payment_price_table_redirect', Array( $this, 'is_dashboard_writePage' ) );
	}

	public function is_dashboard_writePage( $oldValue ){
		/**
		 * true : is not write form
		 * false : is directory manager write form
		 */
		return false;
	}

	public function custom_mypage_sidebar( $args ) {

		return wp_parse_args(
			Array(

				Array(
					'li_class'		=> 'side-menu add-property'
					, 'url'			=> jvfrm_spot_getCurrentUserPage( 'add-' . self::SLUG )
					, 'icon'		=> 'fa fa-tachometer'
					, 'label'		=> esc_html__("New Item", 'javospot')
				)
				, Array(
					'li_class'		=> 'side-menu my-properties'
					, 'url'			=> jvfrm_spot_getCurrentUserPage( 'my-' . self::SLUG )
					, 'icon'		=> 'fa fa-tachometer'
					, 'label'		=> esc_html__("My Items", 'javospot')
				)
			), $args
		);
	}

	public function append_properties( $strFilename, $query ) {
		global $jvfrm_spot_Directory;

		if( $query === 'my-' . self::SLUG )
			$strFilename	= $jvfrm_spot_Directory->template_path . '/mypage-my-item-' . jvfrm_spot_dashboard()->page_style . '.php';

		elseif( $query === 'add-' . self::SLUG )
			$strFilename	= $jvfrm_spot_Directory->template_path . '/mypage-add-item-' . jvfrm_spot_dashboard()->page_style . '.php';

		return $strFilename;
	}

	public function custom_map_listing_filter( ) {
		global $jvfrm_spot_Directory, $post;

		$strFileName		= $jvfrm_spot_Directory->template_path . '/html-map-mainFilter.php';

		if( !file_exists( $strFileName ) ){
			echo $strFileName;
			return;
		}

		require_once $strFileName;
	}

	public function single_template_remove_margin() {
		echo "
			<style type='text/css'>
				html{
					overflow:hidden !important;
				}
				body{
					margin-top:32px !important;
				}
			</style>";
		remove_action('wp_head', '_admin_bar_bump_cb');
	}

	public function custom_type_b_mypage_nav( $nav_args ) {

		if( class_exists( 'Lava_Directory_Favorite' ) )
			$nav_args[ 'jv-favorite' ]	= Array(
				'label'		=> esc_html__( "Favorites", 'javospot' ),
			);

		if( class_exists( 'Lava_Directory_Payment' ) )
			$nav_args[ 'jv-payment' ]	= Array(
				'label'		=> esc_html__( "Payment", 'javospot' ),
			);

		return $nav_args;
	}

	public function dashboard_mylists( $user ){

		if( is_object( $user ) && class_exists( 'jvfrm_spot_block12' ) )  {
			$objShortcode	= new jvfrm_spot_block12();
			echo $objShortcode->output(
				Array(
					'title'					=> strtoupper( sprintf( esc_html__( "%s's Items", 'javospot' ), $user->display_name ) ),
					'count'				=> 6,
					'author'				=> $user->ID,
					'columns'			=> 3,
					'post_type'		=> jvfrm_spot_core()->slug,
					'filter_by'			=> 'listing_category',
					'pagination'		=> 'number',
					'is_dashboard'	=> 'true',
					'module_contents_length' => 8,
				)
			);

		}
	}

	public function dashboard_mylists_edit_button( $module_name, $obj ){

		if( $obj->getArgs( 'is_dashboard' ) != 'true' )
			return;

		$strEditLink = esc_url(
			add_query_arg(
				Array( 'edit' => $obj->post_id ),
				jvfrm_spot_getCurrentUserPage( 'add-' . jvfrm_spot_core()->slug )
			)
		);
		printf( '<a href="%1$s" target="_self">%2$s</a>', $strEditLink, esc_html__( "Edit", 'javospot' ) );
	}

	public function core_recentPostsWidget( $excerpt='', $length=0, $post=false, $args=null ){

		$isMoreMeta = is_array( $args ) &&
			!empty( $args[ 'describe_type' ] ) &&
			$args[ 'describe_type' ] == 'rating_category';

		if(
			$isMoreMeta &&
			class_exists( 'Jvfrm_Spot_Module' ) &&
			is_object( $post ) &&
			$post->post_type == jvfrm_spot_core()->slug
		) {

			$objModule = new Jvfrm_Spot_Module( $post );
			$excerpt = join( false, Array(
				'<div class="javo-shortcode">',
					'<div class="module">',
						sprintf( jvfrm_spot_core()->shortcode->contents_with_raty_star( '', $objModule ) ),
						'<div class="meta-moreinfo">',
							sprintf(
								'<span class="meta-category">%s</span>',
								$objModule->c( 'listing_category', esc_html__( "No Category", 'javospot'	 ) )
							),
							' / ',
							sprintf(
								'<span class="meta-location">%s</span>',
								$objModule->c( 'listing_location', esc_html__( "No Location", 'javospot'	 ) )
							),
						'</div>',
					'</div>',
				'</div>',
			) );
		}
		return $excerpt;
	}

	public function core_recentPostsWidgetOption( $options=Array() ){
		if( class_exists( 'Lava_Directory_Review' ) )
			$options[ 'rating_category' ] = esc_html__( "Rating & Category ( only 'listing' )", 'javospot' );

		return $options;
	}
}