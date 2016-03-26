<?php
/**
 * Plugin Name: Javo Spot Core
 * Description: This plugin is requested for javo spot wordpress theme. it loads shortcodes and some custom code for javo spot theme.
 * Version: 1.0.4
 * Author: Javo Themes
 * Author URI: http://javothemes.com/spot/
 * Text Domain: javo
 * Domain Path: /languages/
 * License: GPLv2 or later */

if( ! defined( 'ABSPATH' ) )
	die;

if( ! class_exists( 'Javo_Spot_Core' ) ) :

	class Javo_Spot_Core
	{
/* Const : */
		/**
		 * Debug mode on/off
		 * @const boolean
		 * @since 1.0.0
		 */
		const DEBUG				= false;

/* Private : */
		/**
		 * Core Theme Template Name
		 * @var string
		 * @since 1.0.0
		 */
		private $theme_name		= 'javo_spot';

		/**
		 * Get Theme Information Object
		 * @var object
		 * @since 1.0.0
		 */
		private $theme			= null;

/* Public : */

		/**
		 * Instance object
		 * @var object
		 * @since 1.0.0
		 */
		public static $instance;

/* Protected : */
		/**
		 * Get Import Directory
		 * @var string
		 * @since 1.0.0
		 */
		protected $import_path	= false;

		/**
		 * Get Export Directory
		 * @var string
		 * @since 1.0.0
		 */
		protected $export_path	= false;

		public function __construct( $file )
		{
			$this->file							= $file;
			$this->folder					= basename( dirname( $this->file ) );
			$this->path						= dirname( $this->file );
			$this->assets_url				= esc_url( trailingslashit( plugins_url( '/assets/', $this->file ) ) );
			$this->import_path			= trailingslashit( $this->path ) . 'import';
			$this->export_path			= trailingslashit( $this->path ) . 'export';
			$this->include_path			= trailingslashit( $this->path ) . 'includes';
			$this->module_path			= trailingslashit( $this->path ) . 'modules';
			$this->shortcode_path	= trailingslashit( $this->path ) . 'shortcodes';

			if( $this->theme_check( $this->theme_name ) ) {
				$this->load_files();
				$this->register_hooks();
			}

			do_action( 'jvfrm_spot_core_init' );
		}

		public function theme_check( $theme_name )
		{
			$this->theme					= wp_get_theme();
			$this->template				= $this->theme->get( 'Name' );
			if( $this->theme->get( 'Template' ) ) {
				$this->parent				= wp_get_theme(  $this->theme->get( 'Template' ) );
				$this->template			= $this->parent->get( 'Name' );
			}
			$this->template				= str_replace( ' ', '_', strtolower( $this->template ) );
			if( sanitize_key( $this->template ) === $theme_name )
				return true;
			return false;
		}

		public function load_files()
		{
			$arrFIles		= Array();
			$arrFIles[]		= $this->shortcode_path . '/core-shortcodes.php';
			$arrFIles[]		= $this->import_path . '/javo-import.php';
			$arrFIles[]		= $this->export_path . '/javo-export.php';
			$arrFIles[]		= $this->include_path . '/class-admin.php';

			if( !empty( $arrFIles ) ) foreach( $arrFIles as $filename )
				if( file_exists( $filename ) )
					require_once $filename;
		}

		public function register_hooks() {
			add_action( 'init'				, Array( $this, 'load_core' ), 99 );

			if( class_exists( 'Javo_Spot_Core_Admin' ) )
				new Javo_Spot_Core_Admin;
		}

		public function load_core()
		{
			if( function_exists( 'jvfrm_spot_register_shortcodes' ) )
				jvfrm_spot_register_shortcodes( $this->template . '_' );

			if( class_exists( 'jvfrm_spot_Import' ) )
				$GLOBALS[ 'jvfrm_spot_Import' ]	= new jvfrm_spot_Import;

			if( class_exists( 'jvfrm_spot_Export' ) && self::DEBUG )
				$GLOBALS[ 'jvfrm_spot_Export' ]	= new jvfrm_spot_Export;
		}

		public static function get_instance( $file=null )
		{
			if( null === self::$instance )
				self::$instance = new Javo_Spot_Core( $file );

			return self::$instance;
		}
	}
endif;
$GLOBALS[ 'jvfrm_spot_core' ] = Javo_Spot_Core::get_instance( __FILE__ );