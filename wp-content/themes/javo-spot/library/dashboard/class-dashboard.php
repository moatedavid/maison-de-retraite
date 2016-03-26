<?php
class jvfrm_spot_dashboard
{

	public $page_style;

	public $is_db_page = false;

	private static $pages = Array();

	public static $instance = false;

	public function __construct()
	{
		global $jvfrm_spot_tso;

		if( $style = $jvfrm_spot_tso->get( 'mypage_style', false ) )
			$this->page_style				= $style;

		// Fixed Style B
		$this->page_style				= 'type-b';
		add_action( 'init'						, Array( __class__, 'define_slug' ) );
		add_action( 'init'						, Array( __class__, 'rewrite' ) );
		add_filter( 'template_include'	, Array( __class__, 'dashboard_template'), 1, 1 );
		require_once 'functions-dashboard.php';
	}

	public static function define_slug()
	{
		self::$pages = apply_filters(
			'jvfrm_spot_dashboard_slugs'
			, Array(
				'JVFRM_SPOT_MEMBER_SLUG'		=> 'member'
				, 'JVFRM_SPOT_PROFILE_SLUG'		=> 'edit-my-profile'
				, 'JVFRM_SPOT_CHNGPW_SLUG'		=> 'change-password'
				, 'JVFRM_SPOT_MANAGE_SLUG'		=> 'manage'
			)
		);
		foreach( self::$pages as $key => $value )
			define( $key, $value);
	}

	public function is_dashboard_page(){
		return$this->is_db_page;
	}

	static function dashboard_template( $template )
	{
		global
			$wp_query
			, $jvfrm_spot_current_user;

		$GLOBALS[ 'jvfrm_spot_dashboard_type' ] = $page_style = jvfrm_spot_dashboard()->page_style;

		if( get_query_var('pn') == 'member' )
		{
			$jvfrm_spot_current_user = get_user_by('login', str_replace("%20", " ", get_query_var('user')));

			if( !empty( $jvfrm_spot_current_user ) )
			{
				self::$instance->is_db_page = true;
				add_filter( 'body_class'			, Array(__class__, 'jvfrm_spot_dashboard_bodyclass_callback'));
				if( jvfrm_spot_dashboard()->page_style =='type-b'){
					add_action( 'wp_enqueue_scripts'	, Array( __class__, 'mypage_header_apply_type_b' ) );
				}else{
					add_action('wp_enqueue_scripts'	, Array( __class__, 'mypage_header_apply' ) );
				}

				$GLOBALS[ 'jvfrm_spot_curUser' ]		= $jvfrm_spot_current_user;
				$GLOBALS[ 'manage_mypage' ]		= $jvfrm_spot_current_user->ID === get_current_user_id();

				if( in_Array( get_query_var('sub_page'), self::$pages ) )
				{
					add_action( 'wp_enqueue_scripts', Array( __class__, 'wp_media_enqueue_callback' ) );
					add_filter( 'wp_title', Array( __class__, 'jvfrm_spot_dashbarod_set_title_callback' ), 99 );
					return apply_filters(
						'jvfrm_spot_dashboard_custom_template_url'
						, JVFRM_SPOT_DSB_DIR . '/' . $page_style . '/mypage-' . get_query_var( 'sub_page' ) . '.php'
						, get_query_var( 'sub_page' )
					);
				}
				else
				{
					add_filter( 'wp_title', Array(__class__, 'jvfrm_spot_dashbarod_set_title_callback'));
					return JVFRM_SPOT_DSB_DIR.'/' . $page_style . '/mypage-member.php';
				}
			}
			else
			{
				return JVFRM_SPOT_DSB_DIR.'/mypage-no-user.php';
			}
		}
		else if( get_query_var( 'pn' ) == 'addform' )
		{
			$output_url		= Array();
			$output_url[]	= esc_url( home_url( '/' ) );
			$output_url[]	= 'member';
			$output_url[]	= wp_get_current_user()->user_login;
			$output_url[]	= JVFRM_SPOT_ADDITEM_SLUG . '/';

			wp_redirect( @implode( '/', $output_url ) );
			die();
		}

		return $template;
	}
	static function jvfrm_spot_dashbarod_set_title_callback(){
		$jvfrm_spot_this_return = '';
		switch( get_query_var('sub_page') ){
			case JVFRM_SPOT_PROFILE_SLUG:			$jvfrm_spot_this_return = esc_html__('Edit My Profile', 'javospot'); break;
			case JVFRM_SPOT_CHNGPW_SLUG:			$jvfrm_spot_this_return = esc_html__('Change Password', 'javospot'); break;
			case JVFRM_SPOT_MANAGE_SLUG:			$jvfrm_spot_this_return = esc_html__( "My Items", 'javospot'); break;
			case JVFRM_SPOT_ADDITEM_SLUG:			$jvfrm_spot_this_return = esc_html__('Post an Item', 'javospot'); break;
			case JVFRM_SPOT_MEMBER_SLUG: default:	$jvfrm_spot_this_return = esc_html__('My Dashboard', 'javospot');
		}
		$jvfrm_spot_this_return = $jvfrm_spot_this_return ." | ";
		return $jvfrm_spot_this_return . esc_html__('My Dashboard', 'javospot').' | '.get_bloginfo('name') ;
	}

	static function jvfrm_spot_dashboard_bodyclass_callback( $classes )
	{
		if( is_admin_bar_showing() )
			$classes[]	= 'admin-bar';
		$classes[]		= 'javo-dashboard';
		$classes[]		=self::$instance->page_style;
		return $classes;
	}

	static function wp_media_enqueue_callback()
	{
		wp_enqueue_media();
		wp_enqueue_script( 'bootstrap-datepicker' );
		wp_enqueue_script( 'google-map' );
		wp_enqueue_script( 'gmap-v3' );
		wp_enqueue_script( 'jQuery-javo-Favorites' );
		wp_enqueue_script( 'jQuery-chosen-autocomplete' );
		wp_enqueue_script( 'jQuery-Rating' );
		wp_enqueue_script( 'jQuery-Ajax-form' );
		wp_enqueue_script( 'bootstrap-tagsinput-min' );
		wp_enqueue_script( 'selectize-script' );
	}

	public static function mypage_header_apply_type_b()
	{
		global $jvfrm_spot_current_user;

		$staticImage	= true;

		$strBackground	= JVFRM_SPOT_IMG_DIR . '/bg/mypage-bg.png';

		if( !empty( $jvfrm_spot_current_user->mypage_header ) ) {
			$mypage_header_meta = wp_get_attachment_image_src( $jvfrm_spot_current_user->mypage_header, 'full' );
			if( !empty( $mypage_header_meta[0] ) ) {
				$strBackground = $mypage_header_meta[0];
				$staticImage	= false;
			}
		}

		$output_html	= Array();
		$output_html[]	= "<style type=\"text/css\">";
		$output_html[]	= sprintf(
			'%s{%s}'
			, 'body.javo-dashboard.type-b', "background-image:url({$strBackground}); background-size:cover; background-attachment:fixed; background-position:center center;"
		);
		if( $staticImage )
			$output_html[]	= "body.javo-dashboard.type-b{ background-size:auto; background-position:-30% bottom; background-repeat:repeat-x; background-color:#C4E2E9 !important; }";
		$output_html[]	= "</style>";
		echo @implode( "\n", $output_html );
	}

	public static function mypage_header_apply()
	{
		global $jvfrm_spot_current_user;

		$strBackground	= JVFRM_SPOT_IMG_DIR . '/bg/mypage-bg.png';

		if( !empty( $jvfrm_spot_current_user->mypage_header ) ) {
			$mypage_header_meta = wp_get_attachment_image_src( $jvfrm_spot_current_user->mypage_header, 'full' );
			if( !empty( $mypage_header_meta[0] ) )
				$strBackground = $mypage_header_meta[0];
		}

		$output_html	= Array();
		$output_html[]	= "<style type=\"text/css\">";
		$output_html[]	= sprintf(
			'%s{%s}'
			, 'body.javo-dashboard .jv-my-page .top-row', "background-image:url({$strBackground}); background-size:cover; background-attachment:fixed; background-position:center center;"
		);
		$output_html[]	= "</style>";
		echo @implode( "\n", $output_html );
	}

	/**
	Action : admin_init
	rewrite
	**/
	static function rewrite()
	{
		add_rewrite_tag('%pn%'										, '([^&]+)');
		add_rewrite_tag('%user%'									, '([^&]+)');
		add_rewrite_tag('%sub_page%'								, '([^&]+)');
		add_rewrite_tag('%edit%'									, '([^&]+)');
		add_rewrite_tag('%update%'									, '([^&]+)');
		add_rewrite_rule( 'lava-my-add-form/?$'						, 'index.php?pn=addform', 'top');
		add_rewrite_rule( 'member/([^/]*)/?$'						, 'index.php?pn=member&user=$matches[1]', 'top');
		add_rewrite_rule( 'member/([^/]*)/page/([^/]*)/?$'			, 'index.php?pn=member&user=$matches[1]&paged=$matches[2]', 'top');
		add_rewrite_rule( 'member/([^/]*)/([^/]*)/?$'				, 'index.php?pn=member&user=$matches[1]&sub_page=$matches[2]', 'top');
		add_rewrite_rule( 'member/([^/]*)/([^/]*)/page/([^/]*)/?$'	, 'index.php?pn=member&user=$matches[1]&sub_page=$matches[2]&paged=$matches[3]', 'top');
		add_rewrite_rule( 'member/([^/]*)/([^/]*)/edit/([^/]*)/?$'	, 'index.php?pn=member&user=$matches[1]&sub_page=$matches[2]&edit=$matches[3]', 'top');
		//flush_rewrite_rules();
	}

	public static function getInstance(){
		if( !self::$instance )
			self::$instance = new self;
		return self::$instance;
	}
}
if( !function_exists( 'jvfrm_spot_dashboard' ) ) :
	function jvfrm_spot_dashboard() {
		return jvfrm_spot_dashboard::getInstance();
	}
	jvfrm_spot_dashboard();
endif;
