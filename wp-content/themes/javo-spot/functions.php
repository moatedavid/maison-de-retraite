<?php
/**
 *	Javo Themes functions and definitions
 *
 * @package WordPress
 * @subpackage Javo
 * @since Javo Themes 1.0
 */

 // Path Initialize
define( 'JVFRM_SPOT_APP_PATH'		, get_template_directory() );				// Get Theme Folder URL : hosting absolute path
define( 'JVFRM_SPOT_THEME_DIR'		, get_template_directory_uri() );			// Get http URL : ex) http://www.abc.com/
define( 'JVFRM_SPOT_SYS_DIR'		, JVFRM_SPOT_APP_PATH."/library");			// Get Library path
define( 'JVFRM_SPOT_TP_DIR'			, JVFRM_SPOT_APP_PATH."/templates");		// Get Tempate folder
define( 'JVFRM_SPOT_ADM_DIR'		, JVFRM_SPOT_SYS_DIR."/admin");				// Administrator Page
define( 'JVFRM_SPOT_IMG_DIR'		, JVFRM_SPOT_THEME_DIR."/assets/images");	// Images folder
define( 'JVFRM_SPOT_WG_DIR'			, JVFRM_SPOT_SYS_DIR."/widgets");			// Widgets Folder
define( 'JVFRM_SPOT_HDR_DIR'		, JVFRM_SPOT_SYS_DIR."/header");			// Get Headers
define( 'JVFRM_SPOT_CLS_DIR'		, JVFRM_SPOT_SYS_DIR."/classes");			// Classes
define( 'JVFRM_SPOT_DSB_DIR'		, JVFRM_SPOT_SYS_DIR."/dashboard");			// Dash Board
define( 'JVFRM_SPOT_FUC_DIR'		, JVFRM_SPOT_SYS_DIR."/functions");			// Functions
define( 'JVFRM_SPOT_PLG_DIR'		, JVFRM_SPOT_SYS_DIR."/plugins");			// Plugin folder
define( 'JVFRM_SPOT_ADO_DIR'		, JVFRM_SPOT_SYS_DIR . "/addons");			// Addons folder

define( 'JVFRM_SPOT_CUSTOM_HEADER', false );

// Includes : Basic or default functions and included files
require_once JVFRM_SPOT_SYS_DIR	. "/define.php";								// defines
require_once JVFRM_SPOT_SYS_DIR	. "/load.php";									// loading functions, classes, shotcode, widgets
require_once JVFRM_SPOT_SYS_DIR	. "/enqueue.php";								// enqueue js, css
require_once JVFRM_SPOT_SYS_DIR	. "/wp_init.php";								// post-types, taxonomies
require_once JVFRM_SPOT_ADM_DIR	. "/class-admin-theme-settings.php";			// theme options
require_once JVFRM_SPOT_DSB_DIR	. "/class-dashboard.php";						// theme screen options tab.

$jvfrm_spot_core_include	= apply_filters( 'jvfrm_spot_core_function_path', JVFRM_SPOT_APP_PATH .'/includes/class-core.php' );

if( file_exists( $jvfrm_spot_core_include ) ) require_once $jvfrm_spot_core_include;

do_action( 'jvfrm_spot_themes_loaded' );