<?php
class jvfrm_spot_enqueue_func
{

	public static $instance;

	private $protocal			= 'http://';

	private $theme				= false;

	private $theme_ver			= '1.0';

	public $html_fonts			= '';

	public $cssWoo_prefix		= 'html body.woocommerce.woocommerce-page';
	public $cssWooCART_prefix	= 'html body.woocommerce-cart.woocommerce-page';
	public $cssWooCHECK_prefix	= 'html body.woocommerce-checkout.woocommerce-page';
	public $cssWooACCOUNT_prefix = 'html body.woocommerce-account.woocommerce-page';

	public function __construct()
	{
		$this->protocal		= is_ssl() ? 'https://' : 'http://';
		$this->theme		= wp_get_theme();
		$this->theme_ver	= $this->theme->get( 'Version' );

		require_once 'wp-less.php';

		add_action('wp_enqueue_scripts', Array( __class__, 'enqueue_scripts'), 9 );
		add_action('wp_enqueue_scripts', Array( __class__, 'post_enqueue_scripts_callback'), 99 );
		add_action('wp_head', Array( __class__, 'jvfrm_spot_custom_style_apply_func'), 9);
		add_action( 'wp_enqueue_scripts', Array( $this, 'get_custom_fonts' ) );
		add_action( 'wp_enqueue_scripts', Array( $this, 'last_enqueues' ), 99 );
		add_action( 'wp_head', Array( $this, 'output_fontCSS' ) );
		add_action('wp_footer', Array( __class__, 'jvfrm_spot_mobile_check_and_logo_change_unc'), 9);
		add_action('admin_enqueue_scripts', Array( __class__, 'admin_enqueue_less_callback' ), 1 );
		add_action('admin_enqueue_scripts', Array( __class__, 'admin_enqueue_scripts_callback'), 9 );
		add_action('wp_print_scripts', Array( __class__, 'jvfrm_spot_dequeue_scripts_callback' ), 100 );

		add_filter( 'body_class', Array( __class__, 'jvfrm_spot_add_transparent_class_callback' ), 10 );

		// Defer Javascripts
		// Defer jQuery Parsing using the HTML5 defer property
		if(! is_admin() )
			 add_filter( 'clean_url', Array( $this, 'defer_scripts' ), 11, 1 );
	}

	// WP_ENQUEUE_SCRIPTS
	static function enqueue_scripts()
	{
		global $jvfrm_spot_tso;

		$jvfrm_spot_register_scripts							= Array(
			'oms.min.js'								=> 'oms-same-position-script'
			, 'common.js'								=> 'javo-common-script'
			, 'widget.js'								=> 'javo-widget-script'
			, 'map-template.js'							=> 'javo-map-template-script'
			, 'chosen.jquery.min.js'					=> 'jQuery-chosen-autocomplete'
			, 'jquery.javo.msg.js'						=> 'javoThemes-Message-Plugin'
			, 'flexmenu.min.js'							=> 'flex-menu'
			, 'google.map.infobubble.js'				=> 'Google-Map-Info-Bubble'
			, 'pace.min.js'								=> 'Pace-Script'
			, 'single-reviews-modernizr.custom.79639.js'	=> 'single-reviews-modernizr.custom'
			, 'jquery.easing.min.js'					=> 'jQuery-Easing'
			, 'jquery.form.js'							=> 'jQuery-Ajax-form'
			, 'sns-link.js'								=> 'sns-link'
			, 'jquery.parallax.min.js'					=> 'jQuery-parallax'
			, 'jquery.javo.mail.js'						=> 'jQuery-javo-Emailer'
			, 'bootstrap.hover.dropmenu.min.js'			=> 'bootstrap-hover-dropdown'
			, 'bootstrap-tagsinput.min.js'				=> 'bootstrap-tagsinput-min'
			, 'javo-footer.js'							=> 'javo-Footer-script'
			, 'jquery.quicksand.js'						=> 'jQuery-QuickSnad'
			, 'jquery.nouislider.min.js'				=> 'jQuery-nouiSlider'
			, 'jquery.slight-submenu.min.js'			=> 'slight-submenu.min-Plugin'
			, 'jquery.typehead.js'						=> 'jquery-type-header'
			, 'jasny-bootstrap.min.js'					=> 'jasny-bootstrap'
			, 'single-reviews-slider.js'				=> 'single-reviews-slider'
			, 'owl.carousel.min.js'						=> 'owl-carousel-script'
			, 'jquery.mixitup.min.js'					=> 'mixitup'
			, 'jquery.lazyload.min.js'					=> 'javo-lazeLoad'
			, 'selectize.min.js'						=> 'selectize-script'
			, 'jquery.sticky.js'						=> 'jquery-sticky'
			, 'jquery.javo.single.js'					=> 'jquery-javo-single'
			, 'jquery.javo.login.js'					=> 'jquery-javo-login'
			, 'ZeroClipboard.min.js'					=> 'ZeroClipboard-min'
			, 'jv-woocommerce.js'						=> 'script-jv-woocommerce'
			, 'lightgallery-all.min.js'					=> 'light-gallery'
			, 'tweenlite.min.js'						=> 'tweenLite-min'
			,'jquery.smartmenus.min.js' => 'jquery-smartmenus-min'
			,'jquery.smartmenus.bootstrap.min.js' => 'jquery-smartmenus-bootstrap'

			/* Widget */
			, 'javo-wg-featured-scripts.js'			=> 'javo-wg-featured-scripts'

			/* Shortcode */
			, 'theia-sticky-sidebar.js'					=> 'sticky-sidebar'
			, 'jquery.javo_ajaxShortcode.js'			=> 'javo-ajaxShortcode'
		);

		$jvfrm_spot_google_api = '';

		foreach( $jvfrm_spot_register_scripts as $src => $handle )
		{
			wp_register_script( $handle, get_template_directory_uri()."/assets/js/{$src}", Array( 'jquery') , '0.1', true );
		}

		if( $jvfrm_spot_google_api	= $jvfrm_spot_tso->get( 'google_api_key', false ) )
			$jvfrm_spot_google_api	= "&key={$jvfrm_spot_google_api}";

		if( $jvfrm_spot_google_lang	= $jvfrm_spot_tso->get( 'google_lang_code', false ) )
			$jvfrm_spot_google_api	= "&language={$jvfrm_spot_google_lang}";

		wp_enqueue_script(
			'bootstrap'
			, get_template_directory_uri()."/assets/js/bootstrap.min.js"
			, Array( 'jquery', 'jquery-ui-button' )
			, '0.1
			', false
		);


		/*
		*
		**	Load Style And Scripts
		*/

		// Styles css
		$theme_data = wp_get_theme();
		wp_enqueue_style( 'javoThemes-directory', get_stylesheet_uri(), array(), $theme_data['Version'] );

		// Images LazyLoad
		// wp_enqueue_script( 'javo-lazeLoad' );

		// Javo Alert Message
		wp_enqueue_script( 'javoThemes-Message-Plugin' );

		// Javo Favorite
		wp_enqueue_script( 'jQuery-javo-Favorites' );

		// Preloading
		wp_enqueue_script( 'Pace-Script' );

		// Javo VC Row and Affix and Etc..
		// wp_enqueue_script( 'javo-common-script' );
		wp_localize_script(
			'javo-common-script',
			'jvfrm_spot_common_args',
			Array(
				'ajax_url' => admin_url( 'admin-ajax.php' )
			)
		);

		// Sticky Sidebar
		wp_enqueue_script( 'sticky-sidebar' );

		// Common Script
		wp_enqueue_script( 'javo-common-script' );

		// Go to top and Contact and Etc..
		wp_enqueue_script( 'javo-Footer-script' );

		// Canvas Menu
		wp_enqueue_script( 'jasny-bootstrap' );

		// Background Effect
		wp_enqueue_script( 'jQuery-Parallax' );

		// Login
		wp_enqueue_script( 'jquery-javo-login' );

		// Woocommerce
		wp_enqueue_script( 'script-jv-woocommerce' );

		$jvfrm_spot_general_styles				= Array(
			'jquery.nouislider.min.css'	=> 'jquery-noUISlider-style',
			'jasny-bootstrap.min.css'	=> 'jasny-bootstrap-min',
			'lightgallery.min.css'	=> 'light-gallery-css',
		);

		/** Load Styles **/
		foreach( $jvfrm_spot_general_styles as $src => $handle )
			wp_enqueue_style( $handle, JVFRM_SPOT_THEME_DIR . "/assets/css/{$src}" );

		// Custom css - Javo themes option
		if( $jvfrm_spot_theme_css = get_option( 'jvfrm_spot_themes_settings_css' ) )
			printf( "\n<style type='text/css'>\n%s\n</style>\n", $jvfrm_spot_theme_css );

		wp_enqueue_style( 'commonStyle', JVFRM_SPOT_THEME_DIR . "/assets/css/common-style.less" );
		wp_enqueue_style( 'mapStyle', JVFRM_SPOT_THEME_DIR . "/assets/css/maps.less" );
	}

	public static function admin_enqueue_less_callback() {
		echo "<link rel=\"stylesheet/less\" type=\"text/css\" href=\"".JVFRM_SPOT_THEME_DIR . "/assets/css/theme-settings.less\" />\n";
	}

	// ADMIN_ENQUEUE_SCRIPTS
	static function admin_enqueue_scripts_callback()
	{

		$jvfrm_spot_admin_css = Array(
			'admin.css' => 'javo-admin-styles',
			'javo_admin_theme_settings-extend.css' => 'javo-ts-extends',
			'javo_admin_post_meta.css' => 'javo-admin-post-meta-css'
		);

		$jvfrm_spot_admin_jss = Array(
			'admin.js'								=> 'javo-admin-script'
		);

		foreach( $jvfrm_spot_admin_css as $src => $id){ jvfrm_spot_get_asset_style($src, $id); }
		foreach( $jvfrm_spot_admin_jss as $src => $id){ jvfrm_spot_get_asset_script($src, $id); }

		wp_enqueue_script(
			'less-min'
			, get_template_directory_uri()."/assets/js/less.min.js"
			, false
			, '0.1'
			, false
		);

		wp_enqueue_style( 'wp-color-picker');
		wp_enqueue_style( "jQuery-chosen-autocomplete-style", JVFRM_SPOT_THEME_DIR."/assets/css/chosen.min.css", null, "0.1" );

		wp_enqueue_script( 'thickbox');
		wp_enqueue_script( 'wp-color-picker');
		wp_enqueue_script( 'my-script-handle', JVFRM_SPOT_THEME_DIR.'/assets/js/admin-color-picker.js', array( 'wp-color-picker' ), false, true );
	}

	public function get_custom_fonts()
	{

		$arrFontTags = Array(
			'basic_font',
			'h1_font',
			'h2_font',
			'h3_font',
			'h4_font',
			'h5_font',
			'h6_font'
		);

		$arrLoadFonts	= $arrFontsApply = $arrOutput = Array();

		if( !empty( $arrFontTags ) ) foreach( $arrFontTags as $tag ) {
			if( $strFontFamily = jvfrm_spot_tso()->get( $tag, false ) ) {
				$sanitize_tag = @explode( '_', $tag );
				$sanitize_tag = isset( $sanitize_tag[0] ) ? $sanitize_tag[0] : null;

				/* Translators: If there are characters in your language that are not
				* supported by Open Sans, translate this to 'off'. Do not translate
				* into your own language. */
				if(
					$strFontFamily == 'Open Sans' &&
					'off' ===_x( 'on', 'Open Sans font: on or off', 'javospot' )
				) continue;

				/* Translators: If there are characters in your language that are not
				* supported by Raleway, translate this to 'off'. Do not translate
				* into your own language. */
				if(
					$strFontFamily == 'Raleway' &&
					'off' ===_x( 'on', 'Raleway font: on or off', 'javospot' )
				) continue;

				/* Translators: If there are characters in your language that are not
				* supported by Roboto Condensed, translate this to 'off'. Do not translate
				* into your own language. */
				if(
					$strFontFamily == 'Roboto Condensed' &&
					'off' ===_x( 'on', 'Roboto Condensed font: on or off', 'javospot' )
				) continue;

				/* Translators: If there are characters in your language that are not
				* supported by Montserrat, translate this to 'off'. Do not translate
				* into your own language. */
				if(
					$strFontFamily == 'Montserrat' &&
					'off' ===_x( 'on', 'Montserrat font: on or off', 'javospot' )
				) continue;

				$arrLoadFonts[] = $strFontFamily . ':300,400,500,600,700';
				$arrFontsApply[ $sanitize_tag ]	= $strFontFamily;
			}
		}

		if( empty( $arrLoadFonts ) )
			return;

		$arrLoadFonts	= join( '|', Array_unique( $arrLoadFonts ) );
		$strLoadFonts	= add_query_arg(
			Array(
				'family' => urlencode( $arrLoadFonts )
				, 'subset' => urlencode( 'latin,latin-ext' )
			)
			, 'https://fonts.googleapis.com/css'
		);
		$strLoadFonts	= esc_url_raw( $strLoadFonts );

		$arrOutput[]		= null;
		$arrOutput[]		= "<style type=\"text/css\">";
		if( !empty( $arrFontsApply ) ) foreach( $arrFontsApply as $tag => $family ) {
			if( $tag == 'basic' ) {
				$arrOutput[] = "html, body, div, form, label, a, b, p, pre, small, em,  blockquote, strong, u, em, ul, li, input, button, textarea, select{font-family:\"{$family}\", sans-serif; }";
			}else{
				$arrOutput[]	= "html body {$tag}, html body {$tag} a{font-family:\"{$family}\", sans-serif; }";
			}
		}
		$arrOutput[]		= "</style>";

		wp_enqueue_style( 'jv-google-fonts', $strLoadFonts, Array(), null );
		$this->html_fonts = join( false, $arrOutput ) . "\n";
	}

	public function last_enqueues(){
		wp_enqueue_style( 'style-jv-woocommerce', JVFRM_SPOT_THEME_DIR . "/assets/css/woocommerce.css" );
	}

	public function output_fontCSS(){
		if( empty( $this->html_fonts ) )
			return;
		echo $this->html_fonts;
	}

	public static function jvfrm_spot_custom_style_apply_func()
	{
		if( false !== ( $custom_css = jvfrm_spot_tso()->get('custom_css', false) ) )
		{
			// Custom CSS AREA
			printf("<style type='text/css'>\n/* Custom CSS From Theme Settings */\n%s\n</style>\n", stripslashes( $custom_css ) );
		}
	}

	public function admin_custom_style_apply_func( &$rows=Array() )
	{
		global $jvfrm_spot_tso;

		if(
			jvfrm_spot_tso()->get( 'footer_background_image_use' ) == 'use' &&
			jvfrm_spot_tso()->get( 'footer_background_image_url' ) != ''
		) :

			$rows[] = "footer.footer-wrap,";
			$rows[] = ".footer-bottom{background-color:transparent !important; border:none;}";
			$rows[] = "footer.footer-wrap .widgettitle_wrap .widgettitle span{background-color:transparent;}";

			$strBuf = '';
			foreach(
				Array(
					'footer_background_size' => 'background-size',
					'footer_background_repeat' => 'background-repeat',
					'footer_background_image_url' => 'background-image',
				)
			as $strOption => $strAttr
			) :
				$strValue = jvfrm_spot_tso()->get( $strOption, false );

				if( !$strValue )
					continue;

				$strValue = $strAttr == 'background-image' ? 'url("' . $strValue . '")' : $strValue;
				$strBuf .= sprintf( '%1$s:%2$s;' , $strAttr, $strValue );
			endforeach;

			$rows[] = sprintf( '%1$s{ %2$s }', '.footer-background-wrap', $strBuf );

		endif;

		/* theme setting - single page*/
		if( $strCSS = jvfrm_spot_tso()->get('single_page_box_background_color', false ) )
			$rows[] = sprintf(
				'%1$s{ %2$s:%3$s; }',
				'.single-item-tab #javo-single-content #javo-detail-item-content>.col-md-12',
				'background-color',
				$strCSS
			);

		/* Title Color */
		if( $strCSS = jvfrm_spot_tso()->get('single_page_title_color', false ) )
			$rows[] = sprintf(
				'%1$s{ %2$s:%3$s; }',
				'.single-item-tab #javo-single-content #javo-detail-item-content .col-md-12 > h3',
				'color',
				$strCSS
			);

		/* Title Color */
		if( $strCSS = jvfrm_spot_tso()->get('single_page_location_title_color', false ) )
			$rows[] = sprintf(
				'%1$s{ %2$s:%3$s; }',
				'.single-item-tab #javo-single-content #javo-item-location-section .col-md-12>h3',
				'color',
				$strCSS
			);

		/*description color*/
		if( $strCSS = jvfrm_spot_tso()->get('single_page_description_color', false ) ):
			$rows[] = "#javo-single-content #javo-detail-item-content .item-description .expandable-content-wrap p,";
			$rows[] = "#javo-single-content #javo-detail-item-content .item-description .expandable-content-wrap li,";
			$rows[] = "#javo-single-content #javo-detail-item-content .item-description .expandable-content-wrap strong,";
			$rows[] = "#javo-single-content #javo-detail-item-content #javo-item-keyword-section,";
			$rows[] = "#javo-single-content #javo-detail-item-content #javo-item-keyword-section a,";
			$rows[] = "#javo-single-content #javo-detail-item-content .item-condition .summary_items>.col-md-6 .col-md-4,";
			$rows[] = "#javo-single-content #javo-detail-item-content .item-condition .summary_items>.col-md-6 .col-md-8,";
			$rows[] = "#javo-single-content #javo-detail-item-content .item-summary .item-summary-inner .col-md-2 p,";
			$rows[] = "#javo-single-content #javo-detail-item-content .item-summary .item-summary-inner .col-md-2 span,";
			$rows[] = "#javo-single-content #javo-detail-item-content .item-summary .item-summary-inner .col-md-2 .javo-summary-author-name";
			$rows[] = sprintf( '{ color:%1$s; }', $strCSS );
		endif;

		/* theme setting - maps - bg */
		if( $strCSS = jvfrm_spot_tso()->get( 'map_page_listing_part_bg', false ) )
			$rows[] = sprintf(
				'%1$s{ %2$s:%3$s; }',
				'#javo-maps-listings-wrap #javo-listings-wrap', 'background-color', $strCSS
			);

		/* theme setting - maps - bg */
		if( $strCSS = jvfrm_spot_tso()->get( 'map_page_listing_part_bg_image', false ) )
			$rows[] = sprintf(
				'%1$s{ %2$s:url("%3$s"); background-attachment:fixed; background-size:100%; }',
				'#javo-maps-listings-wrap #javo-listings-wrap', 'background-image', $strCSS
			);

		if( $strCSS = jvfrm_spot_tso()->get( 'single_page_background_color', false ) )
			$rows[] = sprintf(
				'%1$s{ %2$s:%3$s; }',
				'.single-item-tab', 'background-color', $strCSS
			);

		if( $strCSS = jvfrm_spot_tso()->get( 'single_page_background_color', false ) )
			$rows[] = sprintf(
				'%1$s{ %2$s:%3$s; }',
				'.single-item-tab', 'background-color', $strCSS
			);

		ob_start();
		echo "\n";
?>
<style type="text/css">

	<?php if( jvfrm_spot_tso()->header->get('sticky_menu_shadow')=='enable'){ ?>
	html header#header-one-line nav.javo-main-navbar.affix{
		-webkit-box-shadow: 0 0 12px rgba(0, 0, 0, 0.15);
		-moz-box-shadow: 0 0 12px rgba(0, 0, 0, 0.15);
		-ms-box-shadow: 0 0 12px rgba(0, 0, 0, 0.15);
		-o-box-shadow: 0 0 12px rgba(0, 0, 0, 0.15);
		box-shadow: 0 0 12px rgba(0, 0, 0, 0.15);
	}
	<?php } ?>

	.admin-color-setting,
	.btn.admin-color-setting,
	.javo-txt-meta-area.admin-color-setting,
	.javo-left-overlay.bg-black .javo-txt-meta-area.admin-color-setting,
	.javo-left-overlay.bg-red .javo-txt-meta-area.admin-color-setting,
	.javo-txt-meta-area.custom-bg-color-setting
	{
		background-color: <?php echo $jvfrm_spot_tso->get('total_button_color');?>;
		<?php if( $jvfrm_spot_tso->get('total_button_border_use') == 'use'): ?>
		border-style:solid;
		border-width:1px;
		border-color: <?php echo $jvfrm_spot_tso->get('total_button_border_color');?>;
		<?php else:?>
		border:none;
		<?php endif;?>
	}
	.javo-left-overlay .corner-wrap .corner-background.admin-color-setting,
	.javo-left-overlay .corner-wrap .corner.admin-color-setting{
		border:2px solid <?php echo $jvfrm_spot_tso->get('total_button_color');?>;
		border-bottom-color: transparent !important;
		border-left-color: transparent !important;
		background:none !important;
	}
	.admin-border-color-setting{
		border-color:<?php echo $jvfrm_spot_tso->get('total_button_border_color');?>;
	}
	.custom-bg-color-setting,
	#javo-events-gall .event-tag.custom-bg-color-setting{
		background-color: <?php echo $jvfrm_spot_tso->get('total_button_color');?>;
	}
	.custom-font-color{
		color:<?php echo $jvfrm_spot_tso->get('total_button_color');?>;
	}
	.jvfrm_spot_pagination > .page-numbers.current{
		background-color:<?php echo $jvfrm_spot_tso->get('total_button_color');?>;
		color:#fff;
	}
	.progress .progress-bar{border:none; background-color:<?php echo $jvfrm_spot_tso->get('total_button_color');?>; color:<?php echo $jvfrm_spot_tso->get('primary_font_color'); ?>;}
	<?php echo $jvfrm_spot_tso->get('preloader_hide') == 'use'? '.pace{ display:none !important; }' : '';?>


	.single-<?php echo jvfrm_spot_core()->slug; ?> #header-one-line .navbar-nav>li>a,
	.single-<?php echo jvfrm_spot_core()->slug; ?> #header-one-line ul.widget_top_menu_wrap > li.widget_top_menu > a,
	.single-<?php echo jvfrm_spot_core()->slug; ?> #header-one-line ul.widget_top_menu_wrap > li.widget_top_menu button > i,
	.single-<?php echo jvfrm_spot_core()->slug; ?> #header-one-line #javo-navibar .navbar-right>li>a>span,
	.single-<?php echo jvfrm_spot_core()->slug; ?> #header-one-line #javo-navibar .navbar-right>li>a>img{color:<?php echo $jvfrm_spot_tso->get('single_page_menu_text_color'); ?> !important; }


	#javo-archive-sidebar-nav > li > a { background: <?php echo $jvfrm_spot_tso->get('total_button_color');?>; }
	#javo-archive-sidebar-nav > li.li-with-ul > span{ color:#fff; }
	#javo-archive-sidebar-nav .slight-submenu-button{ color: <?php echo $jvfrm_spot_tso->get('total_button_color');?>; }
	.javo-archive-header-search-bar>.container{background:<?php echo $jvfrm_spot_tso->get('archive_searchbar_bg_color'); ?>; border-color:<?php echo $jvfrm_spot_tso->get('archive_searchbar_border_color'); ?>;}
	ul#single-tabs li.active{ background: <?php echo $jvfrm_spot_tso->get('total_button_color');?> !important; border-color: <?php echo $jvfrm_spot_tso->get('total_button_color');?> !important;}
	ul#single-tabs li.active a:hover{ color:#ddd !important; background: <?php echo $jvfrm_spot_tso->get('total_button_color');?> !important; }
	ul#single-tabs li a:hover{ color: <?php echo $jvfrm_spot_tso->get('total_button_color');?> !important; }

	.footer-top-full-wrap h5,
	.footer-bottom-full-wrap h5,
	.footer-background-wrap footer.footer-wrap .widgettitle_wrap .widgettitle span,
	.footer-background-wrap .widgets-wraps .lava-featured-widget .lava-featured-widget-title h3,
	.footer-background-wrap .widgets-wraps .widgettitle span a.rsswidget,
	.footer-background-wrap footer.footer-wrap .jv-footer-info .jv-footer-info-text-wrap .jv-footer-info-text-title,
	.footer-background-wrap footer.footer-wrap .jv-footer-info .jv-footer-info-image-wrap .jv-footer-info-image-title{color: <?php echo $jvfrm_spot_tso->get('footer_title_color'); ?>;}

	.footer-top-full-wrap .latest-posts .col-md-12 h3 a,
	.footer-top-full-wrap .latest-posts .col-md-12 a span,
	.footer-bottom-full-wrap .latest-posts .col-md-12 h3 a,
	.footer-bottom-full-wrap .latest-posts .col-md-12 a span,
	footer.footer-wrap .jv-footer-column a,
	footer.footer-wrap .jv-footer-column a div,
	footer.footer-wrap .jv-footer-column li,
	footer.footer-wrap .javo-shortcode.shortcode-jvfrm_spot_slider2 .shortcode-container .shortcode-output .slider-wrap.flexslider .flex-viewport ul.slides .module.javo-module3 .section-excerpt > a .meta-excerpt,
	#menu-footer-menu>li>a,
	footer.footer-wrap .col-md-3 .lava-featured-widget-content>span,
	footer.footer-wrap .col-md-3 .lava-featured-widget-content>.price,
	footer.footer-wrap .widgets-wraps label,
	footer.footer-wrap .widgets-wraps #wp-calendar caption,
	footer.footer-wrap .widgets-wraps #wp-calendar th,
	footer.footer-wrap .widgets-wraps #wp-calendar td,
	footer.footer-wrap .widgets-wraps .textwidget p,
	.footer-background-wrap footer.footer-wrap .jv-footer-info .jv-footer-info-text-wrap .jv-footer-info-text,
	.footer-background-wrap footer.footer-wrap .jv-footer-info .jv-footer-info-logo-wrap .jv-footer-info-email a,
	.footer-background-wrap footer.footer-wrap .jv-footer-info .jv-footer-info-logo-wrap .jv-footer-info-email,
	.footer-background-wrap footer.footer-wrap .jv-footer-info .jv-footer-info-logo-wrap .jv-footer-info-working-hour,
	.footer-background-wrap .footer-wrap a{color: <?php echo $jvfrm_spot_tso->get('footer_content_color'); ?> !important;}
	footer.footer-wrap .widgettitle_wrap .widgettitle,
	footer.footer-wrap .widgettitle_wrap .widgettitle:after,
	footer.footer-wrap .lava-featured-widget-title{border-color: <?php echo $jvfrm_spot_tso->get('footer_title_underline_color', '#ffffff' ); ?>;}
	.footer-background-wrap .widgets-wraps .widget_posts_wrap .latest-posts .col-md-12:hover a,
	.footer-background-wrap .widgets-wraps .widget_posts_wrap .latest-posts .col-md-12:hover a span,
	.lava-featured-widget .lava-featured-widget-content a:hover,
	footer .widgets-wraps li a:hover{color: <?php echo $jvfrm_spot_tso->get('footer_content_link_hover_color', '#ffffff' ); ?> !important;}
	.footer-background-wrap .footer-sidebar-wrap .footer-copyright{color: <?php echo $jvfrm_spot_tso->get('copyright_color', '#ffffff' ); ?> !important;}


	<?php if(jvfrm_spot_tso()->header->get("jvfrm_spot_header_height")!='') echo 'header >.javo-main-navbar .navbar-header{height:'.jvfrm_spot_tso()->header->get("jvfrm_spot_header_height").'px;}'; ?>
	<?php if(jvfrm_spot_tso()->header->get("jvfrm_spot_header_padding_left")!='') echo 'header >.javo-main-navbar{padding-left:'.jvfrm_spot_tso()->header->get("jvfrm_spot_header_padding_left").'px;}'; ?>
	<?php if(jvfrm_spot_tso()->header->get("jvfrm_spot_header_padding_right")!='') echo 'header >.javo-main-navbar{padding-right:'.jvfrm_spot_tso()->header->get("jvfrm_spot_header_padding_right").'px;}'; ?>
	<?php if(jvfrm_spot_tso()->header->get("jvfrm_spot_header_padding_top")!='') echo 'header >.javo-main-navbar{padding-top:'.jvfrm_spot_tso()->header->get("jvfrm_spot_header_padding_top").'px;}'; ?>
	<?php if(jvfrm_spot_tso()->header->get("jvfrm_spot_header_padding_bottom")!='') echo 'header >.javo-main-navbar{padding-bottom:'.jvfrm_spot_tso()->header->get("jvfrm_spot_header_padding_bottom").'px;}'; ?>


</style>
		<?php
		ob_end_flush();
	}

	public static function jvfrm_spot_dequeue_scripts_callback()
	{
		// Block to google Map of Visual Composer
		wp_dequeue_script( "googleapis" );
	}

	public static function post_enqueue_scripts_callback()
	{
		global
			$post
			, $jvfrm_spot_tso;

		if( empty( $post ) )
		{
			$post				= new stdClass();
			$post->ID			= 0;
		}

		$is_post				= is_singular( 'post' );
		$core_custom_postType	= apply_filters( 'jvfrm_spot_core_post_type', 'custom_type' );
		$jvfrm_spot_hd_options			= get_post_meta( $post->ID, 'jvfrm_spot_hd_post', true );

		if( $is_post )
			$jvfrm_spot_hd_options[ 'header_skin' ] = 'light';


		$jvfrm_spot_query				= new jvfrm_spot_array( $jvfrm_spot_hd_options );
		$jvfrm_spot_css_one_row			= Array();

		// Backgeound Color
		if( false !== ( $css = $jvfrm_spot_query->get("page_bg", jvfrm_spot_tso()->header->get( 'page_bg', false ) ) ) ){
			$jvfrm_spot_css_one_row[] = "html body{ background-color:{$css}; }";
			$jvfrm_spot_css_one_row[] = "html body #page-style{ background-color:{$css}; }";
		}

		// Navigation Background Color
		if( false !== ( $hex = $jvfrm_spot_query->get("header_bg", jvfrm_spot_tso()->header->get( 'header_bg', false ) ) ) )
		{
			if( $jvfrm_spot_query->get( 'header_opacity_as', '' ) != '' )
			{
				// 'enable' => Page Setting
				if( false === ( $opacity = (float)$jvfrm_spot_query->get( 'header_opacity', false ) ) ){
					$opacity = (float)1;
				}
			}else{
				// '' => Theme settings
				if( false === ( $opacity = (float)jvfrm_spot_tso()->header->get( 'header_opacity', false ) ) ){
					$opacity = (float)1;
				}
			}

			$jvfrm_spot_rgb = apply_filters( 'jvfrm_spot_rgb', substr( $hex, 1) );


			if($jvfrm_spot_query->get('header_type')=='jv-nav-row-2-lvl nav-transparent'){
				$jvfrm_spot_css_one_row[] = "html header#header-one-line nav.navbar.affix-top #javo-navibar > #menu-primary{ background-color:rgba( {$jvfrm_spot_rgb['r']}, {$jvfrm_spot_rgb['g']}, {$jvfrm_spot_rgb['b']}, {$opacity}); }";
			}else if($jvfrm_spot_query->get('header_type')==''){
				if(jvfrm_spot_tso()->header->get('header_type')=='jv-nav-row-2-lvl nav-transparent'){
					$jvfrm_spot_css_one_row[] = "html header#header-one-line nav.navbar.affix-top #javo-navibar > #menu-primary{ background-color:rgba( {$jvfrm_spot_rgb['r']}, {$jvfrm_spot_rgb['g']}, {$jvfrm_spot_rgb['b']}, {$opacity}); }";
				}else{
					$jvfrm_spot_css_one_row[] = "html header#header-one-line nav.navbar{ background-color:rgba( {$jvfrm_spot_rgb['r']}, {$jvfrm_spot_rgb['g']}, {$jvfrm_spot_rgb['b']}, {$opacity}); }";
				}
			}else{
				$jvfrm_spot_css_one_row[] = "html header#header-one-line nav.navbar{ background-color:rgba( {$jvfrm_spot_rgb['r']}, {$jvfrm_spot_rgb['g']}, {$jvfrm_spot_rgb['b']}, {$opacity}); }";
			}
		}

		// Sticky Navigation Background Color
		if( false !== ( $hex = $jvfrm_spot_query->get("sticky_header_bg", jvfrm_spot_tso()->header->get( 'sticky_header_bg', false ) ) ) )
		{
			if( $jvfrm_spot_query->get( 'sticky_header_opacity_as', '' ) != '' )
			{
				// 'enable' => Page Setting
				if( false === ( $opacity = (float)$jvfrm_spot_query->get( 'sticky_header_opacity', false ) ) ){
					$opacity = (float)1;
				}
			}else{
				// '' => Theme settings
				if( false === ( $opacity = (float)jvfrm_spot_tso()->header->get( 'sticky_header_opacity', false ) ) ){
					$opacity = (float)1;
				}
			}

			$jvfrm_spot_rgb = apply_filters( 'jvfrm_spot_rgb', substr( $hex, 1) );
			$jvfrm_spot_css_one_row[] = "html header.main nav.navbar.affix{ background-color:rgba( {$jvfrm_spot_rgb['r']}, {$jvfrm_spot_rgb['g']}, {$jvfrm_spot_rgb['b']}, {$opacity}) !important; }";
		}

		// Single page Navigation Background Color
		if($jvfrm_spot_tso->get( 'single_page_menu_bg_color' ) != '' && false !== ( $hex = $jvfrm_spot_tso->get( 'single_page_menu_bg_color', false ) ) )
		{
			if( false === ( $opacity = (float)jvfrm_spot_tso()->header->get( 'single_header_opacity', false ) ) ){
				$opacity = (float)1;
			}

			if($jvfrm_spot_query->get( 'single_header_relation' ) != 'absolute'){
				$jvfrm_spot_css_one_row[] = "html .single-{$core_custom_postType} #header-one-line>nav{ background-color:transparent !important; }";
			}

			$jvfrm_spot_rgb = apply_filters( 'jvfrm_spot_rgb', substr( $hex, 1) );
			$jvfrm_spot_css_one_row[] = "html .single header.main{ background-color:rgba( {$jvfrm_spot_rgb['r']}, {$jvfrm_spot_rgb['g']}, {$jvfrm_spot_rgb['b']}, {$opacity}) !important;     background-image: none !important;}";
		}

		// Dropdown Menu Color
		if(jvfrm_spot_tso()->header->get("header_dropdown_bg")!=''){
			$css=jvfrm_spot_tso()->header->get("header_dropdown_bg");
			$jvfrm_spot_css_one_row[] = "#header-one-line .javo-main-navbar .container .container-fluid #javo-navibar ul.jv-nav-ul .menu-item .dropdown-menu li a,";
			$jvfrm_spot_css_one_row[] = "#header-one-line .javo-main-navbar .container .container-fluid #javo-navibar ul.jv-nav-ul .menu-item .dropdown-menu li.dropdown-header{background:{$css};}";
			$jvfrm_spot_css_one_row[] = "#header-one-line .javo-main-navbar .container .container-fluid #javo-navibar ul.jv-nav-ul .menu-item .dropdown-menu li.divider{border-color:{$css}; margin-bottom:0px}";
			$jvfrm_spot_css_one_row[] = "header#header-one-line .javo-main-navbar .container .container-fluid #javo-navibar ul.jv-nav-ul > li.menu-item.menu-item-has-children > ul.dropdown-menu:after{border-bottom-color:{$css};}";
		}

		if(jvfrm_spot_tso()->header->get("header_dropdown_hover_bg")!=''){
			$css=jvfrm_spot_tso()->header->get("header_dropdown_hover_bg");
			$jvfrm_spot_css_one_row[] = "#header-one-line .javo-main-navbar .container .container-fluid #javo-navibar ul.jv-nav-ul .menu-item .dropdown-menu li.menu-item a:hover,";
			$jvfrm_spot_css_one_row[] = "header#header-one-line .javo-main-navbar .container .container-fluid #javo-navibar ul.navbar-left > li.menu-item.menu-item-has-children > ul.dropdown-menu li.menu-item-has-children ul.dropdown-menu li.menu-item > a:hover,";
			$jvfrm_spot_css_one_row[] = "#header-one-line .javo-main-navbar .container .container-fluid #javo-navibar ul.jv-nav-ul .menu-item .dropdown-menu li.menu-item.active a{background:{$css};}";
		}
		if(jvfrm_spot_tso()->header->get("header_dropdown_text")!=''){
			$css=jvfrm_spot_tso()->header->get("header_dropdown_text");
			$jvfrm_spot_css_one_row[] = "#header-one-line .javo-main-navbar .container .container-fluid #javo-navibar ul.jv-nav-ul .menu-item .dropdown-menu li.menu-item a{color:{$css} !important;}";
		}

		// Header Size
		{
			$intHeaderHeight			= 0;
			if( $jvfrm_spot_query->get( 'header_size_as', '' ) != '' )
			{
				// 'enable' => Page Setting
				$intHeaderHeight = intVal( $jvfrm_spot_query->get( 'header_size', 40 ) );
			}else{
				// '' => Theme settings
				$intHeaderHeight = intVal( jvfrm_spot_tso()->header->get( 'header_size', 40 ) );
			}

			$intHeaderHeight		.= 'px';
		}

		// Navigation Shadow
		if( "enable" != $jvfrm_spot_query->get("header_shadow", jvfrm_spot_tso()->header->get( 'header_shadow', false ) ) ){
			$jvfrm_spot_css_one_row[] = "html header#header-one-line nav.javo-main-navbar{ box-shadow:none; }";
			$jvfrm_spot_css_one_row[] = "html header#header-one-line:after{ content:none; }";
		}

		// Header Skin
		{
			switch( $jvfrm_spot_query->get("header_skin", jvfrm_spot_tso()->header->get( 'header_skin', false ) ) )
			{
				case "light":
					$jvfrm_spot_css_one_row[] = "html body header#header-one-line ul.nav > li.menu-item > a{ color:#fff; }";
					$jvfrm_spot_css_one_row[] = "html body header#header-one-line ul.widget_top_menu_wrap > li.widget_top_menu > a{ color:#fff; }";
					$jvfrm_spot_css_one_row[] = "html body header#header-one-line ul.widget_top_menu_wrap > li.widget_top_menu button.btn{ color:#fff; }";
				break;

				default:
				case "dark":
					/* Roboto
					$jvfrm_spot_css_one_row[] = "html body header#header-one-line ul.nav > li.menu-item > a{ color:#000; }";*/
					$jvfrm_spot_css_one_row[] = "html body header#header-one-line ul.nav > li.menu-item > a{ color:#3a3f45; }";
					$jvfrm_spot_css_one_row[] = "html body header#header-one-line ul.widget_top_menu_wrap > li.widget_top_menu > a{ color:#000; }";
					$jvfrm_spot_css_one_row[] = "html body header#header-one-line ul.widget_top_menu_wrap > li.widget_top_menu button.btn{ color:#000; }";
				break;
			}
		}

		// Navigation Full-Width
		if( "full" == $jvfrm_spot_query->get("header_fullwidth", jvfrm_spot_tso()->header->get( 'header_fullwidth', false ) ) ){
			$jvfrm_spot_css_one_row[]	= "html header#header-one-line .container{ width:100%; }";
			$jvfrm_spot_css_one_row[] = "html header#header-one-line div#javo-navibar{ text-align:right; }";
			$jvfrm_spot_css_one_row[] = "html header#header-one-line div#javo-navibar ul{ text-align:left; }";
			$jvfrm_spot_css_one_row[] = "html header#header-one-line div#javo-navibar ul.jv-nav-ul:not(.mobile){ float:none !important; display:inline-block; vertical-align:top; margin-top:6px; }";
			$jvfrm_spot_css_one_row[] = "html header#header-one-line div#javo-navibar ul.navbar-right:not(.mobile){ float:none !important; display:inline-block; vertical-align:top; }";
		}else{
			//$jvfrm_spot_css_one_row[] = "html header#header-one-line div#javo-navibar ul.navbar-right .widget_top_menu_wrap{padding-top:16px; }";
		}

		$jvfrm_spot_css_one_row[] = "html header#header-one-line div#javo-navibar ul.jv-nav-ul:not(.mobile){ margin-top:6px; }";

		// Navigation Menu Transparent
		if( false !== ( $css = $jvfrm_spot_query->get("header_relation", jvfrm_spot_tso()->header->get( 'header_relation', false ) ) ) )
		{
			if( ! $is_post && !is_archive() && !is_search() && !is_attachment() && !get_query_var( 'pn' ) )
			{
				$jvfrm_spot_css_one_row[] = "html header#header-one-line.main{ position:{$css}; }";
				if( $css == 'absolute' ) {
					$jvfrm_spot_css_one_row[] = "html header#header-one-line.main{ left:0; right:0; }";
				}
			}
		}

		// Sticky Menu
		{
			if( "disabled" == $jvfrm_spot_query->get("header_sticky", jvfrm_spot_tso()->header->get( 'header_sticky', false )  ) ){
				add_filter( 'body_class', Array( __CLASS__, 'append_parametter' ) );
			}
		}

		// Sticky Header Skin
		{
			switch( $jvfrm_spot_query->get("sticky_header_skin", jvfrm_spot_tso()->header->get( 'sticky_header_skin', false ) ) )
			{
				case "light":
					$jvfrm_spot_css_one_row[] = "html body header#header-one-line .affix #javo-navibar ul.nav > li.menu-item > a{ color:#fff; }";
					$jvfrm_spot_css_one_row[] = "html body header#header-one-line .affix ul.widget_top_menu_wrap > li.widget_top_menu > a{ color:#fff; }";
					$jvfrm_spot_css_one_row[] = "html body header#header-one-line .affix ul.widget_top_menu_wrap > li.widget_top_menu button.btn{ color:#fff; }";
				break;

				default:
				case "dark":
					$jvfrm_spot_css_one_row[] = "html body header#header-one-line .affix #javo-navibar ul.nav > li.menu-item > a{ color:#000; }";
					$jvfrm_spot_css_one_row[] = "html body header#header-one-line .affix ul.widget_top_menu_wrap > li.widget_top_menu > a{ color:#000; }";
					$jvfrm_spot_css_one_row[] = "html body header#header-one-line .affix ul.widget_top_menu_wrap > li.widget_top_menu button.btn{ color:#000; }";
				break;
			}
		}

		// Sticky Header Shadow
		if( $jvfrm_spot_query->get('sticky_menu_shadow')=='enable' ){
			$jvfrm_spot_css_one_row[] = "html header#header-one-line nav.javo-main-navbar.affix{
				-webkit-box-shadow: 0 0 12px rgba(0, 0, 0, 0.15);
				-moz-box-shadow: 0 0 12px rgba(0, 0, 0, 0.15);
				-ms-box-shadow: 0 0 12px rgba(0, 0, 0, 0.15);
				-o-box-shadow: 0 0 12px rgba(0, 0, 0, 0.15);
				box-shadow: 0 0 12px rgba(0, 0, 0, 0.15);
			}";
		}

		if( "enable" != $jvfrm_spot_query->get("sticky_menu_shadow", jvfrm_spot_tso()->header->get( 'sticky_menu_shadow', false ) ) ){
			$jvfrm_spot_css_one_row[] = "html header#header-one-line nav.javo-main-navbar.affix{ box-shadow:none; color:transparent;}";
			$jvfrm_spot_css_one_row[] = "html header#header-one-line:after{ content:none; }";
		}

		// Mobile Navigation
		if( false !== ( $hex = $jvfrm_spot_query->get("mobile_header_bg", jvfrm_spot_tso()->header->get( 'mobile_header_bg', false ) ) ) )
		{
			if( $jvfrm_spot_query->get( 'mobile_header_opacity_as', '' ) != '' )
			{
				// 'enable' => Page Setting
				if( false === ( $opacity = (float)$jvfrm_spot_query->get( 'mobile_header_opacity', false ) ) ){
					$opacity = (float)1;
				}
			}else{
				// '' => Theme settings
				if( false === ( $opacity = (float)jvfrm_spot_tso()->header->get( 'mobile_header_opacity', false ) ) ){
					$opacity = (float)1;
				}
			}

			$jvfrm_spot_rgb = apply_filters( 'jvfrm_spot_rgb', substr( $hex, 1) );
			$jvfrm_spot_css_one_row[] = "html body.mobile header#header-one-line.main .navbar{ background-color:rgba( {$jvfrm_spot_rgb['r']}, {$jvfrm_spot_rgb['g']}, {$jvfrm_spot_rgb['b']}, {$opacity}) !important; }";
			$jvfrm_spot_css_one_row[] = "body.mobile.page-template-lava_lv_listing_map #header-one-line #javo-navibar{ background-color:rgba( {$jvfrm_spot_rgb['r']}, {$jvfrm_spot_rgb['g']}, {$jvfrm_spot_rgb['b']}, {$opacity}) !important; }";
		}

		// Mobile Header Skin
		{
			switch( $jvfrm_spot_query->get("mobile_header_skin", jvfrm_spot_tso()->header->get( 'mobile_header_skin', false ) ) )
			{
				case "light":
					$jvfrm_spot_css_one_row[] = "html body.mobile header#header-one-line ul.nav > li.menu-item > a{ color:#fff; }";
					$jvfrm_spot_css_one_row[] = ".javo-mobile-left-menu,";
					$jvfrm_spot_css_one_row[] = "header#header-one-line .javo-navi-bright .container .navbar-header .pull-right button.javo-in-mobile,";
					$jvfrm_spot_css_one_row[] = "header#header-one-line .javo-navi-bright .container .navbar-header .pull-right button.javo-in-mobile:hover{color:#fff;}";
				break;

				default:
				case "dark":
					$jvfrm_spot_css_one_row[] = "html body.mobile header#header-one-line #javo-navibar ul.nav > li.menu-item > a{ color:#000 !important; }";
					$jvfrm_spot_css_one_row[] = "html body.mobile header#header-one-line .navbar-header>button>span{ background-color:#000 !important; }";
				break;
			}
		}

		/** SINGLE POST */{
			// Navigation Menu Transparent
			if( false !== ( $css = $jvfrm_spot_query->get( 'single_post_header_relation', jvfrm_spot_tso()->header->get( 'single_post_header_relation', 'absolute' ) ) ) )
			{
				if( $is_post )
				{
					$jvfrm_spot_css_one_row[] = "html header#header-one-line.main{ position:{$css}; }";
					if( $css == 'absolute' ) {
						$jvfrm_spot_css_one_row[] = "html header#header-one-line.main{ left:0; right:0; }";
					}
				}
			}

			// Single Post Header Option
			if( false !== ( $hex = jvfrm_spot_tso()->header->get( 'single_post_page_menu_bg_color', '#ffffff' ) ) ){
				if( false === ( $opacity = (float)$jvfrm_spot_tso->get( 'single_post_header_opacity', '0' ) ) ){
					$opacity = (float)1;
				}

				if( $jvfrm_spot_query->get( 'single_post_header_relation', jvfrm_spot_tso()->header->get( 'single_post_header_relation', false ) ) != 'absolute'){
					$jvfrm_spot_css_one_row[] = "html .single-post #header-one-line>nav:not(.affix){ background-color:transparent !important; }";
				}

				$jvfrm_spot_rgb = apply_filters( 'jvfrm_spot_rgb', substr( $hex, 1) );
				$jvfrm_spot_css_one_row[] = "html .single-post header.main{ background-color:rgba( {$jvfrm_spot_rgb['r']}, {$jvfrm_spot_rgb['g']}, {$jvfrm_spot_rgb['b']}, {$opacity}) !important; background-image: none !important;}";
				$jvfrm_spot_css_one_row[] = "html .single-post header.main > nav.navbar.affix-top	{ background-color:rgba( {$jvfrm_spot_rgb['r']}, {$jvfrm_spot_rgb['g']}, {$jvfrm_spot_rgb['b']}, {$opacity}) !important;}";
			}
		}

		/** SINGLE Custom Post Type */{
			// Navigation Menu Transparent
			if( false !== ( $css = $jvfrm_spot_query->get('single_header_relation', jvfrm_spot_tso()->header->get( 'single_header_relation', false ) ) ) )
			{
				if( ! $is_post ) {
					$jvfrm_spot_css_one_row[] = "html body.single-{$core_custom_postType} header#header-one-line.main{ position:{$css}; }";
					if( $css == "absolute" ) {
						$jvfrm_spot_css_one_row[] = "html body.single-{$core_custom_postType} header#header-one-line.main{ left:0; right:0;}";
						$jvfrm_spot_css_one_row[] = "html .single-{$core_custom_postType} #header-one-line>nav{ background-color:transparent !important; }";
					}
				}
			}

			if( 'use' === ( $jvfrm_spot_tso->get( 'single_page_menu_other_bg_color', '' ) ) )
			{
				if( false !== ( $css = $jvfrm_spot_query->get("single_header_relation", jvfrm_spot_tso()->header->get( 'single_header_relation', false ) ) ) )
				{
				}
			}
			 // Color
		}

		// Page Background Image
		if('' != ( $css = $jvfrm_spot_query->get('page_background_image', $jvfrm_spot_tso->get('page_background_image', false)))){
			$jvfrm_spot_css_one_row[] = "html body, html body.custom-background{background-image:url('{$css}'); background-size:cover; background-repeat:no-repeat; background-attachment:fixed;}";
		}


		$color = null;
		// Primary Color
		if( $color = $jvfrm_spot_tso->get( 'total_button_color', false ) )

			/**
			 *	Common Part
			 */
			$jvfrm_spot_css_one_row[] = "html body .admin-color-setting,";
			$jvfrm_spot_css_one_row[] = "html body a.admin-color-setting,";
			$jvfrm_spot_css_one_row[] = "html body button.admin-color-setting,";
			$jvfrm_spot_css_one_row[] = "html body .btn.admin-color-setting,";
			$jvfrm_spot_css_one_row[] = "html body .admin-color-setting:hover,";
			$jvfrm_spot_css_one_row[] = "html body a.admin-color-setting:hover,";
			$jvfrm_spot_css_one_row[] = "html body button.admin-color-setting:hover,";
			$jvfrm_spot_css_one_row[] = "html body .btn.admin-color-setting:hover,";
			$jvfrm_spot_css_one_row[] = "html body .admin-color-setting-hover:hover,";
			$jvfrm_spot_css_one_row[] = "html body .btn.admin-color-setting-hover:hover,";
			$jvfrm_spot_css_one_row[] = "html body button.btn.admin-color-setting-hover:hover,";

			$jvfrm_spot_css_one_row[] = "html body div.javo-shortcode.shortcode-jvfrm_spot_search2 div.row.jv-search2-actions-row button[type='submit'].admin-color-setting:hover,";
			$jvfrm_spot_css_one_row[] = "html body a.jv-button-transition,";
			$jvfrm_spot_css_one_row[] = "html body .btn.jv-button-transition,";
			$jvfrm_spot_css_one_row[] = "html body button.btn.jv-button-transition,";
			$jvfrm_spot_css_one_row[] = "html body button.btn.jv-button-transition:hover,";
			$jvfrm_spot_css_one_row[] = "html body a#back-to-top,";
			$jvfrm_spot_css_one_row[] = "html body a#back-to-top:hover,";


			/**
			 *	MyPage Part
			 */
			$jvfrm_spot_css_one_row[] = "html body.javo-dashboard .main-content-right .my-page-title a.btn-danger.admin-color-setting:hover,";
			$jvfrm_spot_css_one_row[] = "body.javo-dashboard .jv-my-page .top-row.container >.col-md-12 .profile-and-image-container .nav-tabs li a:hover,";
			$jvfrm_spot_css_one_row[] = "body.javo-dashboard .jv-my-page .second-container-content .jv-mypage-home .panel-default .panel-body .nav-tabs li a:hover,";

			/**
			 *	Single Part
			 */
			$jvfrm_spot_css_one_row[] = "html body.single button.lava_contact_modal_button,";
			$jvfrm_spot_css_one_row[] = "html body.single button.lava_contact_modal_button:hover,";
			$jvfrm_spot_css_one_row[] = "html body.single.type-b .sticky-wrapper.is-sticky .javo-spyscroll,";
			$jvfrm_spot_css_one_row[] = "html body.single div.single-item-tab div.container form.lava-wg-author-contact-form";
			$jvfrm_spot_css_one_row[] = "div.panel div.panel-body.author-contact-button-wrap button.btn.admin-color-setting:hover,";
			$jvfrm_spot_css_one_row[] = "html body.single div.single-item-tab div.container div.panel div.panel-body.author-contact-button-wrap";
			$jvfrm_spot_css_one_row[] = "button.btn.admin-color-setting-hover:hover,";

			/**
			 *	Booking Part
			 */
			 $jvfrm_spot_css_one_row[] = ".widget_lava_directory_booking_widget button.wc-bookings-booking-form-button,";

			/**
			 *	Map Part
			 */
			$jvfrm_spot_css_one_row[] = "html body div.javo-slider-tooltip,";
			$jvfrm_spot_css_one_row[] = "html body div.javo-my-position-geoloc .noUi-handle,";
			$jvfrm_spot_css_one_row[] = "html body div.jvfrm_spot_map_list_sidebar_wrap .noUi-handle,";
			$jvfrm_spot_css_one_row[] = "html body #javo-maps-listings-switcher > .switcher-right > .btn-group label:hover,";
			$jvfrm_spot_css_one_row[] = "html body #javo-maps-listings-switcher > .switcher-right > .btn-group label.active,";
			$jvfrm_spot_css_one_row[] = "html body .javo-maps-panel-wrap .javo-map-box-advance-filter-wrap-fixed #javo-map-box-advance-filter,";
			$jvfrm_spot_css_one_row[] = "html body .javo-maps-search-wrap #javo-map-box-advance-filter:hover,";
			$jvfrm_spot_css_one_row[] = "html body #javo-maps-wrap .javo-module3 .media-left .meta-category,";
			$jvfrm_spot_css_one_row[] = "end#primary-color{ background-color:{$color} !important; background:{$color} !important; }";

			/**
			 *	Primary Font Colors
			 */
			$jvfrm_spot_css_one_row[] = "html body #javo-maps-wrap .javo-module3 .media-body .meta-price ,";
			$jvfrm_spot_css_one_row[] = "end#primary-font-color{ color:{$color} }";

			/**
			 *	Primary Border Colors
			 */
			$jvfrm_spot_css_one_row[] = "html body h3.page-header,";
			$jvfrm_spot_css_one_row[] = "html body.javo-dashboard .my-page-title,";
			$jvfrm_spot_css_one_row[] = "end#primary-border-color{ border-color:{$color} !important; }";

			/**
			 *	Toolbar Left/Right
			 */
			$jvfrm_spot_css_one_row[] = "html body .jv-trans-menu-contact-left-wrap i.admin-color-setting,";
			$jvfrm_spot_css_one_row[] = "html body .jv-trans-menu-contact-right-wrap i.admin-color-setting";
			$jvfrm_spot_css_one_row[] = "{ background-color:transparent !important; color:{$color}; }";

			/**
			 *	Primary Color for Font Color
			 */
			$jvfrm_spot_css_one_row[] = "html body #dot-nav > ul > li.active,";
			$jvfrm_spot_css_one_row[] = "html body div.jv-custom-post-content > div.jv-custom-post-content-trigger,";
			$jvfrm_spot_css_one_row[] = "html body #javo-item-description-read-more{ color:{$color}; }";
			$jvfrm_spot_css_one_row[] = "html body .shortcode-jvfrm_spot_timeline1 .timeline-item .jv-data i{ color:{$color}; }";

		jvfrm_spot_enqueues()->woo_primary_color( $jvfrm_spot_css_one_row, $color );

		$color = null;
		// Primary Font Color
		if( $color = $jvfrm_spot_tso->get( 'primary_font_color', false ) )

			/**
			 *	Common Part
			 */
			$jvfrm_spot_css_one_row[] = "html body .admin-color-setting,";
			$jvfrm_spot_css_one_row[] = "html body a.admin-color-setting,";
			$jvfrm_spot_css_one_row[] = "html body button.admin-color-setting,";
			$jvfrm_spot_css_one_row[] = "html body .btn.admin-color-setting,";
			$jvfrm_spot_css_one_row[] = "html body .admin-color-setting:hover,";
			$jvfrm_spot_css_one_row[] = "html body a.admin-color-setting:hover,";
			$jvfrm_spot_css_one_row[] = "html body button.admin-color-setting:hover,";
			$jvfrm_spot_css_one_row[] = "html body .btn.admin-color-setting:hover,";
			$jvfrm_spot_css_one_row[] = "html body a.jv-button-transition,";
			$jvfrm_spot_css_one_row[] = "html body .btn.jv-button-transition,";
			$jvfrm_spot_css_one_row[] = "html body button.btn.jv-button-transition,";
			$jvfrm_spot_css_one_row[] = "html body button.btn.jv-button-transition:hover,";
			$jvfrm_spot_css_one_row[] = "html body a#back-to-top,";
			$jvfrm_spot_css_one_row[] = "html body a#back-to-top:hover,";

			/**
			 *	MyPage Part
			 */
			$jvfrm_spot_css_one_row[] = "html body.javo-dashboard .main-content-right .my-page-title a.btn-danger.admin-color-setting:hover,";
			$jvfrm_spot_css_one_row[] = "body.javo-dashboard .jv-my-page .top-row.container >.col-md-12 .profile-and-image-container .nav-tabs li a:hover,";

			/**
			 *	Single Part
			 */
			$jvfrm_spot_css_one_row[] = "html body.single button.lava_contact_modal_button,";
			$jvfrm_spot_css_one_row[] = "html body.single button.lava_contact_modal_button:hover,";
			$jvfrm_spot_css_one_row[] = "html body.single .lava-single-sidebar .panel-heading.admin-color-setting .col-md-12 h3,";


			/**
			 *	Map Part
			 */
			$jvfrm_spot_css_one_row[] = "html body div.javo-slider-tooltip,";
			$jvfrm_spot_css_one_row[] = "html body div.javo-my-position-geoloc .noUi-handle,";
			$jvfrm_spot_css_one_row[] = "html body div.jvfrm_spot_map_list_sidebar_wrap .noUi-handle,";
			$jvfrm_spot_css_one_row[] = "html body #javo-maps-listings-switcher > .switcher-right > .btn-group label:hover,";
			$jvfrm_spot_css_one_row[] = "html body #javo-maps-listings-switcher > .switcher-right > .btn-group label.active,";
			$jvfrm_spot_css_one_row[] = "html body .javo-maps-panel-wrap .javo-map-box-advance-filter-wrap-fixed #javo-map-box-advance-filter,";
			$jvfrm_spot_css_one_row[] = "html body .javo-maps-search-wrap #javo-map-box-advance-filter:hover,";
			$jvfrm_spot_css_one_row[] = "html body #javo-maps-wrap .javo-module3 .media-left .meta-category,";

			/**
			 *	Booking Part
			 */
			$jvfrm_spot_css_one_row[] = ".widget_lava_directory_booking_widget button.wc-bookings-booking-form-button,";

			/**
			 *  shortcode navi
			 */
			$jvfrm_spot_css_one_row[] = ".javo-shortcode .shortcode-output ul.pagination > li.active > span:hover,";
			$jvfrm_spot_css_one_row[] = ".javo-shortcode .shortcode-output .page-numbers.loadmore:hover,";
			$jvfrm_spot_css_one_row[] = ".javo-shortcode .shortcode-output ul.pagination > li > a:hover ,";
			$jvfrm_spot_css_one_row[] = ".javo-shortcode .shortcode-output ul.pagination > li.active > a ,";
			$jvfrm_spot_css_one_row[] = ".javo-shortcode .shortcode-output ul.pagination > li.active > a:hover ,";
			$jvfrm_spot_css_one_row[] = ".javo-shortcode .shortcode-output ul.pagination > li.active > a:focus ,";
			$jvfrm_spot_css_one_row[] = ".javo-shortcode .shortcode-output ul.pagination > li.active > span ,";
			$jvfrm_spot_css_one_row[] = ".javo-shortcode .shortcode-output ul.pagination > li.active > a:focus,";

			/**
			 *	Primary Font Colors
			 */
			$jvfrm_spot_css_one_row[] = "html body #javo-maps-wrap .javo-module3 .media-body .meta-price ,";
			$jvfrm_spot_css_one_row[] = "end#primary-font-color{ color:{$color} !important; }";

		$color = null;
		// Primary Border Color
		if( $color = $jvfrm_spot_tso->get( 'total_button_border_color', false ) ) :
			/**
			 *	Common Part
			 */
			$jvfrm_spot_css_one_row[] = "html body .admin-color-setting,";
			$jvfrm_spot_css_one_row[] = "html body a.admin-color-setting,";
			$jvfrm_spot_css_one_row[] = "html body button.admin-color-setting,";
			$jvfrm_spot_css_one_row[] = "html body .btn.admin-color-setting,";
			$jvfrm_spot_css_one_row[] = "html body .admin-color-setting:hover,";
			$jvfrm_spot_css_one_row[] = "html body a.admin-color-setting:hover,";
			$jvfrm_spot_css_one_row[] = "html body button.admin-color-setting:hover,";
			$jvfrm_spot_css_one_row[] = "html body .btn.admin-color-setting:hover,";
			$jvfrm_spot_css_one_row[] = "html body a.jv-button-transition,";
			$jvfrm_spot_css_one_row[] = "html body .btn.jv-button-transition,";
			$jvfrm_spot_css_one_row[] = "html body button.btn.jv-button-transition,";
			$jvfrm_spot_css_one_row[] = "html body button.btn.jv-button-transition:hover,";
			$jvfrm_spot_css_one_row[] = "html body a#back-to-top,";
			$jvfrm_spot_css_one_row[] = "html body a#back-to-top:hover,";

			/**
			 *	Single Part
			 */
			$jvfrm_spot_css_one_row[] = "html body.single button.lava_contact_modal_button,";
			$jvfrm_spot_css_one_row[] = "html body.single button.lava_contact_modal_button:hover,";
			$jvfrm_spot_css_one_row[] = "html body.single div.single-item-tab div.container form.lava-wg-author-contact-form";
			$jvfrm_spot_css_one_row[] = "div.panel div.panel-body.author-contact-button-wrap button.btn.admin-color-setting:hover,";
			$jvfrm_spot_css_one_row[] = "html body.single div.single-item-tab div.container div.panel";
			$jvfrm_spot_css_one_row[] = "div.panel-body.author-contact-button-wrap button.btn.admin-color-setting-hover:hover,";

			/**
			 *	Booking Part
			 */
			$jvfrm_spot_css_one_row[] = ".widget_lava_directory_booking_widget button.wc-bookings-booking-form-button,";
			
			/**
			 * mypage listing list
			 */
			$jvfrm_spot_css_one_row[] = "#page-style .jv-my-page .javo-shortcode .shortcode-container .shortcode-output ul.pagination > li.active > span:hover,";
			$jvfrm_spot_css_one_row[] = "#page-style .jv-my-page .javo-shortcode .shortcode-container .shortcode-output .page-numbers.loadmore:hover,";
			$jvfrm_spot_css_one_row[] = "#page-style .jv-my-page .javo-shortcode .shortcode-container .shortcode-output ul.pagination > li > a:hover ,";
			$jvfrm_spot_css_one_row[] = "#page-style .jv-my-page .javo-shortcode .shortcode-container .shortcode-output ul.pagination > li.active > a ,";
			$jvfrm_spot_css_one_row[] = "#page-style .jv-my-page .javo-shortcode .shortcode-container .shortcode-output ul.pagination > li.active > a:hover ,";
			$jvfrm_spot_css_one_row[] = "#page-style .jv-my-page .javo-shortcode .shortcode-container .shortcode-output ul.pagination > li.active > a:focus ,";
			$jvfrm_spot_css_one_row[] = "#page-style .jv-my-page .javo-shortcode .shortcode-container .shortcode-output ul.pagination > li.active > span ,";
			$jvfrm_spot_css_one_row[] = "#page-style .jv-my-page .javo-shortcode .shortcode-container .shortcode-output ul.pagination > li.active > a:focus,";

			/**
			 *	Map Part
			 */
			$jvfrm_spot_css_one_row[] = "html body div.javo-slider-tooltip,";
			$jvfrm_spot_css_one_row[] = "html body div.javo-my-position-geoloc .noUi-handle,";
			$jvfrm_spot_css_one_row[] = "html body div.jvfrm_spot_map_list_sidebar_wrap .noUi-handle,";
			$jvfrm_spot_css_one_row[] = "html body #javo-maps-listings-switcher > .switcher-right > .btn-group label:hover,";
			$jvfrm_spot_css_one_row[] = "html body #javo-maps-listings-switcher > .switcher-right > .btn-group label.active,";
			$jvfrm_spot_css_one_row[] = "html body .javo-maps-panel-wrap .javo-map-box-advance-filter-wrap-fixed #javo-map-box-advance-filter,";
			$jvfrm_spot_css_one_row[] = "html body .javo-maps-search-wrap #javo-map-box-advance-filter:hover,";
			$jvfrm_spot_css_one_row[] = "html body #javo-maps-wrap .javo-module3 .media-left .meta-category,";
			$jvfrm_spot_css_one_row[] = "end#primary-border-color{ border-color:{$color} !important; }";

		endif;

		jvfrm_spot_enqueues()->woo_primary_border_color( $jvfrm_spot_css_one_row, $color );

		// Footer Top Color
		//if( $color = $jvfrm_spot_tso->get( 'footer_top_background_color' , false ) )
		//	$jvfrm_spot_css_one_row[] = "html body > div.footer-top-full-wrap{ background-color:{$color}; }";

		// Footer Middle Color
		if( $color = $jvfrm_spot_tso->get( 'footer_middle_background_color' , false ) )
			$jvfrm_spot_css_one_row[] = "html body > div.footer-background-wrap{ background-color:{$color}; }";

		// Footer Bottom Color
		//if( $color = $jvfrm_spot_tso->get( 'footer_bottom_background_color' , false ) )
		//	$jvfrm_spot_css_one_row[] = "html body > div.row.footer-bottom-full-wrap{ background-color:{$color}; }";

		if( $strSLUG = jvfrm_spot_core()->slug )
			$jvfrm_spot_css_one_row[] = "html body.single.single-{$strSLUG} ul.nav-justified,";
			$jvfrm_spot_css_one_row[] = "html body.single.single-{$strSLUG} ul.nav-justified > li{ width:auto; }";

		jvfrm_spot_enqueues()->admin_custom_style_apply_func( $jvfrm_spot_css_one_row );



		// Output Stylesheet
		$jvfrm_spot_css_one_row = apply_filters( 'jvfrm_spot_custom_css_rows', $jvfrm_spot_css_one_row );

		echo join( false, Array(
			'<style type="text/css">',
			join( false, $jvfrm_spot_css_one_row ),
			"</style>\n",
		) );

	}

	public static function jvfrm_spot_add_transparent_class_callback( $classes )
	{
		global $post;

		$post_id				= 0;

		if( !empty( $post ) )
			$post_id			= $post->ID;

		$jvfrm_spot_hd_options		= get_post_meta( $post_id, 'jvfrm_spot_hd_post', true );
		$jvfrm_spot_query				= new jvfrm_spot_array( $jvfrm_spot_hd_options );
		$arrAllowPostTypes		= apply_filters( 'jvfrm_spot_single_post_types_array', Array( 'custom_type' ) );

		if(
			( !empty( $post->post_type ) && in_Array( $post->post_type, $arrAllowPostTypes ) && jvfrm_spot_tso()->header->get( 'single_header_relation' ) === 'absolute' ) ||
			( $jvfrm_spot_query->get("header_relation", jvfrm_spot_tso()->header->get( 'header_relation', false ) ) === 'absolute' )
		) $classes[]	= "jv-header-transparent";

		if( 'full' == $jvfrm_spot_query->get("header_fullwidth", jvfrm_spot_tso()->header->get( 'header_fullwidth', false ) ) )
			$classes[]	= "jv-header-fullwidth";

		return $classes;
	}

	public static function jvfrm_spot_mobile_check_and_logo_change_unc()
	{
		global $jvfrm_spot_tso;

		// Output Script
		ob_start();
		?>
		<script type="text/javascript">
		jQuery( function($){
			"use strict";

			var jvfrm_spot_pre_image_ = $( "header#header-one-line" ).find( "[data-javo-sticky-src]" ).attr( "src" );
			var jvfrm_spot_stk_image_ = $( "header#header-one-line" ).find( "[data-javo-sticky-src]" ).data( "javo-sticky-src" );
			var jvfrm_spot_mob_image_ = $( "header#header-one-line" ).find( "[data-javo-mobile-src]" ).data( "javo-mobile-src" );


			$( window )

			.on(
				'scroll resize'
				, function(){
					if( window.matchMedia( '(max-width:767px)' ).matches ) {
						$( 'body, #javo-navibar ul.jv-nav-ul' ).addClass( 'mobile' );
					}else{
						$( 'body, #javo-navibar ul.jv-nav-ul' ).removeClass( 'mobile' );
					}

					$( '.javo-in-mobile.x-hide' ).show();

					if( $( "body" ).hasClass( 'mobile' ) ) {

						$( '.javo-in-mobile.x-hide' ).hide();
						$( "header#header-one-line" ).find( "[data-javo-mobile-src]" ).prop( "src", jvfrm_spot_mob_image_ );

					}else if( $( "header#header-one-line" ).find( "nav" ).hasClass( "affix" ) ) {
						if( jvfrm_spot_stk_image_ )
							$( "header#header-one-line" ).find( "[data-javo-sticky-src]" ).prop( "src", jvfrm_spot_stk_image_ );
					}else{
						if( jvfrm_spot_pre_image_ )
							$( "header#header-one-line" ).find( "[data-javo-sticky-src]" ).prop( "src", jvfrm_spot_pre_image_ );
					}

				}
			).trigger('scroll resize');
		});
		</script>
		<?php
		$arrOutputScript		= Array();
		$arrOutputScript[]	= ob_get_clean();
		/*
		if( $jvfrm_spot_tso->get( 'lazyload' ) != 'disable' )
			$arrOutputScript[]	= "
				<script type=\"text/javascript\">
					jQuery( function( $ ) {
							$(\"img, .javo-thb, .lazy\").lazyload({
						effect: 'fadeIn'
						, failure_limit : 999
					});
				});
				</script>";
			*/
		echo join( '', $arrOutputScript );
	}

	public static function append_parametter( $classes )
	{
		$classes[]			= "no-sticky";
		return $classes;
	}

	public function defer_scripts( $url ) {
        if ( FALSE === strpos( $url, '.js' ) ) return $url;
        if ( strpos( $url, 'jquery.js' ) ) return $url;
        if ( strpos( $url, '.json' ) ) return $url;
        return "$url' defer onload='";
    }

	public function woo_primary_color( &$rows=Array(), $color ){
		if( is_null( $color ) )
			return $rows;

		$strCorePT	= apply_filters( 'jvfrm_spot_core_post_type', 'custom_type' );
		$cssSingle	= "html body.single.single-{$strCorePT} .woocommerce";

		/** Woocommerce primary */
		$rows[]	= ".woocommerce .add_to_cart_button,";
		$rows[]	= ".woocommerce .add_to_cart_button:hover,";
		$rows[]	= "div.woocommerce a.add_to_cart_button,";
		$rows[]	= "div.woocommerce a.add_to_cart_button:hover,";
		$rows[]	= "{$this->cssWoo_prefix} ul.products li.product .onsale-wrap .onsale-inner,";
		$rows[]	= "{$this->cssWoo_prefix}.single-product .onsale-inner,";
		$rows[]	= "{$this->cssWoo_prefix} a.button,";
		$rows[]	= "{$this->cssWoo_prefix} button.button,";
		$rows[]	= "{$this->cssWoo_prefix} input.button,";
		$rows[]	= "{$this->cssWoo_prefix} .added_to_cart,";
		$rows[]	= "{$this->cssWoo_prefix} .added_to_cart:hover,";
		$rows[]	= "{$this->cssWoo_prefix} button.button.single_add_to_cart_button,";
		$rows[]	= "{$this->cssWoo_prefix} button.button.single_add_to_cart_button:hover,";
		$rows[]	= "{$this->cssWooCART_prefix} .single-product .onsale-inner,";
		$rows[]	= "{$this->cssWooCART_prefix} a.button,";
		$rows[]	= "{$this->cssWooCART_prefix} button.button,";
		$rows[]	= "{$this->cssWooCART_prefix} input.button,";
		$rows[]	= "{$this->cssWooCART_prefix} a.button.checkout-button,";
		$rows[]	= "{$this->cssWooCART_prefix} a.button.checkout-button:hover,";
		$rows[]	= "{$this->cssWooCHECK_prefix} .single-product .onsale-inner,";
		$rows[]	= "{$this->cssWooCHECK_prefix} a.button,";
		$rows[]	= "{$this->cssWooCHECK_prefix} button.button,";
		$rows[]	= "{$this->cssWooCHECK_prefix} input.button,";
		$rows[]	= "{$this->cssWooCHECK_prefix} input[ type='submit' ].button,";
		$rows[]	= "{$this->cssWooACCOUNT_prefix} .single-product .onsale-inner,";
		$rows[]	= "{$this->cssWooACCOUNT_prefix} a.button,";
		$rows[]	= "{$this->cssWooACCOUNT_prefix} button.button,";
		$rows[]	= "{$this->cssWooACCOUNT_prefix} input.button,";
		$rows[]	= "{$this->cssWoo_prefix} #respond input#submit,";
		$rows[]	= "{$this->cssWoo_prefix} #content input.button,";
		$rows[]	= "{$this->cssWoo_prefix} div.product .woocommerce-tabs ul.tabs li.active,";
		$rows[]	= "{$this->cssWoo_prefix} #content div.product .woocommerce-tabs ul.tabs li.active,";
		$rows[]	= "{$this->cssWoo_prefix} div.product .woocommerce-tabs ul.tabs li.active,";
		$rows[]	= "{$this->cssWoo_prefix} #content div.product .woocommerce-tabs ul.tabs li.active,";
		$rows[]	= "{$this->cssWoo_prefix} .quantity .plus,";
		$rows[]	= "{$this->cssWoo_prefix} .quantity .minus,";
		$rows[]	= "{$this->cssWoo_prefix} #content .quantity .minus,";
		$rows[]	= "{$this->cssWoo_prefix} #content .quantity .minus,";
		$rows[]	= "{$this->cssWooCART_prefix} .quantity .plus,";
		$rows[]	= "{$this->cssWooCART_prefix} .quantity .minus";
		/** ----------------------------  */
		$rows[]	= "{background-color:{$color}; }";

		/** Woocommerce single add-cart background */
		$rows[]	= "{$cssSingle}.woocommerce-page > ul.products > li.module > div.jv-hover-wrap > a.button.jv-woo-button.add_to_cart_button:hover";
		/** ----------------------------  */
		$rows[]	= "{background-color:{$color} !important; }";

		/** Woocommerce primary border line */
		$rows[]	= "{$this->cssWoo_prefix} h1.page-title,";
		$rows[]	= "{$this->cssWoo_prefix} .pp-single-content h1.page-title,";
		$rows[]	= "{$this->cssWoo_prefix} h1.custom-header,";
		$rows[]	= "{$this->cssWooCART_prefix} h1.custom-header,";
		$rows[]	= "{$this->cssWooCHECK_prefix} h1.custom-header,";
		$rows[]	= "{$this->cssWooACCOUNT_prefix} h1.custom-header";
		$rows[]	= "body.woocommerce .woocommerce-error, body.woocommerce-page .woocommerce-error";
		/** ----------------------------  */
		$rows[]	= "{border-color:{$color};}";

		/** Woocommerce remove button */
		$rows[]	= "{$this->cssWoo_prefix} a.remove,";
		$rows[]	= "{$this->cssWooCART_prefix} a.remove,";
		$rows[]	= "{$this->cssWooCART_prefix} form .form-row .required, form .form-row .required";
		/** ----------------------------  */
		$rows[]	= "{color:{$color} !important;}";

		/** Woocommerce remove button hover */
		$rows[]	= "{$this->cssWoo_prefix} a.remove:hover,";
		$rows[]	= "{$this->cssWooCART_prefix} a.remove:hover";
		/** ----------------------------  */
		$rows[]	= "{background-color:{$color}; color:#fff !important; }";
	}

	public function woo_primary_border_color( &$rows=Array(), $color ){
		if( is_null( $color ) )
			return $rows;

		$strCorePT	= apply_filters( 'jvfrm_spot_core_post_type', 'custom_type' );
		$cssSingle	= "html body.single.single-{$strCorePT} .woocommerce";

		/** Woocommerce primary */
		$rows[]	= ".woocommerce .add_to_cart_button,";
		$rows[]	= ".woocommerce .add_to_cart_button:hover,";
		$rows[]	= "div.woocommerce a.add_to_cart_button,";
		$rows[]	= "div.woocommerce a.add_to_cart_button:hover,";
		$rows[]	= "{$this->cssWoo_prefix} ul.products li.product .onsale-wrap .onsale-inner,";
		$rows[]	= "{$this->cssWoo_prefix} .single-product .onsale-inner,";
		$rows[]	= "{$this->cssWoo_prefix} a.button,";
		$rows[]	= "{$this->cssWoo_prefix} button.button,";
		$rows[]	= "{$this->cssWoo_prefix} input.button,";
		$rows[]	= "{$this->cssWoo_prefix} .added_to_cart,";
		$rows[]	= "{$this->cssWoo_prefix} button.button.single_add_to_cart_button,";
		$rows[]	= "{$this->cssWoo_prefix} button.button.single_add_to_cart_button:hover,";
		$rows[]	= "{$this->cssWooCART_prefix} .single-product .onsale-inner,";
		$rows[]	= "{$this->cssWooCART_prefix} a.button,";
		$rows[]	= "{$this->cssWooCART_prefix} button.button,";
		$rows[]	= "{$this->cssWooCART_prefix} input.button,";
		$rows[]	= "{$this->cssWooCART_prefix} a.button.checkout-button,";
		$rows[]	= "{$this->cssWooCART_prefix} a.button.checkout-button:hover,";
		$rows[]	= "{$this->cssWooCHECK_prefix} a.button,";
		$rows[]	= "{$this->cssWooCHECK_prefix} button.button,";
		$rows[]	= "{$this->cssWooCHECK_prefix} input.button,";
		$rows[]	= "{$this->cssWooCHECK_prefix} input[ type='submit' ].button,";
		$rows[]	= "{$this->cssWooACCOUNT_prefix} .single-product .onsale-inner,";
		$rows[]	= "{$this->cssWooACCOUNT_prefix} a.button,";
		$rows[]	= "{$this->cssWooACCOUNT_prefix} button.button,";
		$rows[]	= "{$this->cssWooACCOUNT_prefix} input.button,";
		$rows[]	= "{$this->cssWoo_prefix} #respond input#submit,";
		$rows[]	= "{$this->cssWoo_prefix} #content input.button,";
		$rows[]	= "{$this->cssWoo_prefix} .quantity .plus,";
		$rows[]	= "{$this->cssWoo_prefix} .quantity .minus,";
		$rows[]	= "{$this->cssWoo_prefix} #content .quantity .minus,";
		$rows[]	= "{$this->cssWoo_prefix} #content .quantity .minus,";
		$rows[]	= "{$this->cssWooCART_prefix} .quantity .plus,";
		$rows[]	= "{$this->cssWooCART_prefix} .quantity .minus";
		/** ----------------------------  */
		$rows[]	= "{border-color:{$color};}";

		/** Woocommerce single add-cart button border */
		$rows[]	= "{$cssSingle}.woocommerce-page > ul.products > li.module > div.jv-hover-wrap > a.button.jv-woo-button.add_to_cart_button:hover";
		/** ----------------------------  */
		$rows[]	= "{border-color:{$color} !important; }";
	}

	public static function getInstance() {
		if( ! self::$instance )
			self::$instance = new self;
		return self::$instance;
	}
}

if( !function_exists( 'jvfrm_spot_enqueues' ) ) :
	function jvfrm_spot_enqueues(){
		return jvfrm_spot_enqueue_func::getInstance();
	}
	jvfrm_spot_enqueues();
endif;