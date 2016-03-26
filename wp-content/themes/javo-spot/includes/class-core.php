<?php
if( !defined( 'ABSPATH' ) )
	die;

class jvfrm_spot_Directory
{
	/**
	 *	Required Initialize Settings
	 */
	const SLUG			= 'lv_listing';
	const NAME			= 'listing';
	const CORE			= 'Javo_Spot_Core';
	const FEATURED_CAT	= '_category';
	const MAINPLUG		= 'Lava_Directory_Manager';

	/**
	 *	Additional Initialize Settings
	 */
	const REVIEW				= 'Lava_Directory_Review';
	private static $instance;

	public $slug;
	protected $template_path	= false;


	public static function get_instance( $file ) {
		if( !self::$instance )
			self::$instance = new self( $file );

		return self::$instance;
	}

	public function __construct( $file )
	{
		$this->initialize( $file );
		$this->load_files();
		$this->register_hoook();

		$this->template = new jvfrm_spot_Directory_Template;
		$this->shortcode = new jvfrm_spot_Directory_Shortcode;
	}

	public function initialize( $file )
	{
		$this->file				= $file;
		$this->folder			= ( dirname( $this->file ) );
		$this->dir				= trailingslashit( JVFRM_SPOT_THEME_DIR . '/includes' );
		$this->assets_dir		= trailingslashit( $this->dir . 'assets' );
		$this->path				= dirname( $this->file );
		$this->template_path	= trailingslashit( $this->path ) . 'templates';

		$this->slug				= self::SLUG;
	}

	public function load_files()
	{
		require_once "class-template.php";
		require_once "class-shortcode.php";
		require_once "function-lv_listing.php";
		require_once "vc-core.php";
	}

	public function register_hoook()
	{
		add_action( 'wp_enqueue_scripts', Array( $this, 'register_resources' ) );
		add_action( 'init', Array( $this, 'custom_object' ), 100 );
		add_filter( 'lava_' . self::SLUG . '_json_addition', Array( $this, 'json_append' ), 10, 3 );

		add_filter( 'jvfrm_spot_theme_setting_pages', Array( $this, 'main_slug_page' ) );
		add_filter( 'jvfrm_spot_theme_setting_pages', Array( $this, 'map_page' ) );
	}

	public function getSlug() {
		return self::SLUG;
	}

	public function getCoreName( $suffix=false ){
		$strSuffix = $suffix ? '_' . $suffix : false;
		return self::CORE . $strSuffix;
	}

	public function register_resources()
	{
		$jvfrm_spot_load_styles								=
		Array(
			'single.css'									=> '0.1.0'
			, 'style-min.css'								=> '0.1.0'
		);

		$jvfrm_spot_load_scripts							=
		Array(
			'single.js' => '1.0.0',
			'map-template.js' => '1.0.0',
			'jquery.javo_search_shortcode.js'	=> '0.1.0',
		);


		if( !empty( $jvfrm_spot_load_styles ) ) : foreach( $jvfrm_spot_load_styles as $filename => $version ) {
			wp_register_style(
				sanitize_title( $filename )
				, $this->assets_dir . "css/{$filename}"
				, Array()
				, $version
			);
		} endif;

		if( !empty( $jvfrm_spot_load_scripts ) ) : foreach( $jvfrm_spot_load_scripts as $filename => $version ) {
			wp_register_script(
				sanitize_title( 'jv-' . $filename )
				, $this->assets_dir . "js/{$filename}"
				, Array( 'jquery' )
				, $version
				, true
			);
		} endif;

		wp_enqueue_style( 'extra', $this->assets_dir . "css/extra.less" );
		wp_enqueue_style( 'jv-main-slug-shortcode', $this->assets_dir . "css/custom-shortcode.less" );
	}

	public function json_append( $args, $post_id, $objTerm )
	{
		global $lava_directory_manager_func;
		$arrResult			= Array();
		$arrAllMeta		= Array_Keys( apply_filters( 'lava_' . self::SLUG . '_more_meta', Array() ) );
		$arrExcludes		= Array( '_phone1', '_phone2', '_address', '_email', '_website' );

		$arrAllMeta		= array_diff( $arrAllMeta, $arrExcludes );

		if( !empty( $arrAllMeta ) )  foreach( $arrAllMeta as $metaKey )
			$arrResult[ $metaKey ] = get_post_meta( $post_id, $metaKey, true );

		$arrResult[ 'f' ] = get_post_meta( $post_id, '_featured_item', true );

		return wp_parse_args( $arrResult, $args );
	}

	public function main_slug_page( $pages ){
		return wp_parse_args(
			Array(
				'lv_listing'			=> Array(
					esc_html__( "Listing", 'javospot' ), false
					, 'priority'		=> 35
					, 'external'		=> $this->template_path . '/admin-theme-settings-item.php'
				)
			)
			, $pages
		);
	}

	public function map_page( $pages ) {
		return wp_parse_args(
			Array(
				'map'			=> Array(
					esc_html__( "Map", 'javospot' ), false
					, 'priority'		=> 32
					, 'external'		=> $this->template_path . '/admin-theme-settings-map.php'
				)
			)
			, $pages
		);
	}
	public function custom_object()
	{
		// Exclude Search
		$objPostType = get_post_type_object( self::SLUG );

		if( is_object( $objPostType ) )
			$objPostType->exclude_from_search = true;
	}
}

if( !function_exists( 'jvfrm_spot_core' ) ) {
	function jvfrm_spot_core() {
		$objInstance				= jvfrm_spot_Directory::get_instance( __FILE__ );
		$GLOBALS[ 'jvfrm_spot_Directory' ]	= $objInstance;
		return $objInstance;
	}
	jvfrm_spot_core();
}