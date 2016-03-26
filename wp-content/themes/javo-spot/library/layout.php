<?php
class Jv_layout_function
{

	public $path;

	public static $instance;

	public $footer_path;

	public $widgets	= Array(
		'single-slider' => 'Jvfrm_Spot_Single_Post_Slider'
	);

	public function __construct()
	{
		$this->path			= get_template_directory() . '/library/';
		$this->footer_path	= $this->path . 'footer/';
		$this->widget_path	= $this->path . 'widgets/';

		add_action( 'wp_head', Array( $this, 'theme_slug_render_title' ) );

		add_action( 'jvfrm_spot_body_after', Array( $this, 'footer' ) );
		add_action( 'wp_footer', Array( $this, 'common_load' ) );
		add_action( 'wp_footer', Array( $this, 'common_script') );
		add_action( 'widgets_init', Array( $this, 'widgets_init' ) );

		add_action( 'after_setup_theme', Array( $this, 'init' ) );

		// General Post
		{
			//add_action( 'jvfrm_spot_post_content_after', Array( $this, 'single_author_information' ) );
			add_action( 'jvfrm_spot_post_content_after', Array( $this, 'relative_posts' ) );
		}
	}

	public function init(){
		add_filter( 'wc_get_template', Array( $this, 'vender_template' ), 10, 2 );
	}


	public function theme_slug_render_title(){
		if( function_exists( '_wp_render_title_tag' ) )
			return false;
		?><title><?php wp_title( '|', true, 'right' ); ?></title><?php
	}

	public function footer()
	{
		$this->getContent( 'widget'	, 'footer-top' );
		$this->getContent( 'banner'	, 'footer' );
		$this->getContent( 'widget'	, 'footer-body' );
		$this->getContent( 'widget'	, 'footer-bottom' );
		$this->getContent( 'html'	, 'contact-type' );

		get_template_part('templates/parts/modal', 'contact-us');	// modal contact us
		get_template_part('templates/parts/modal', 'map-brief');	// Map Brief
		get_template_part("templates/parts/modal", "emailme");	// Link address send to me
		get_template_part("templates/parts/modal", "claim");		// claim
		$this->footer_function();
	}

	public function getContent( $strType ='', $strName='', $args=Array() )
	{
		global $jvfrm_spot_tso;
		if( !empty( $args ) )
			extract( $args, EXTR_SKIP );

		$strFilename		= $this->footer_path .  $strType . '-' . $strName . '.php';
		if( file_exists( $strFilename ) )
			require_once $strFilename;
	}

	public function common_load() {
		$this->getLoginPart();
	}

	public function common_script()
	{
		$mail_alert_msg = Array(
			'to_null_msg'			=> esc_html__('Please, type email address.', 'javospot')
			, 'from_null_msg'		=> esc_html__('Please, type your email adress.', 'javospot')
			, 'subject_null_msg'	=> esc_html__('Please, type your name.', 'javospot')
			, 'content_null_msg'	=> esc_html__('Please, type your message', 'javospot')
			, 'failMsg'				=> esc_html__('Sorry, failed to send your message', 'javospot')
			, 'successMsg'			=> esc_html__('Successfully sent!', 'javospot')
			, 'confirmMsg'			=> esc_html__('Do you want to send this email ?', 'javospot')
		);
		$jvfrm_spot_favorite_alerts = Array(
			"nologin"				=> esc_html__('If you want to add it to your favorite, please login.', 'javospot')
			, "save"				=> esc_html__('Saved', 'javospot')
			, "unsave"				=> esc_html__('Unsaved', 'javospot')
			, "error"				=> esc_html__('Sorry, server error.', 'javospot')
			, "fail"				=> esc_html__('favorite register fail.', 'javospot')
		); ?>

		<script type="text/javascript">
		jQuery( function($){
			"use strict";
			jQuery("#jvfrm_spot_rb_contact_submit").on("click", function(){
				var options = {
					subject: $("#jvfrm_spot_rb_contact_name")
					, to:"<?php bloginfo('admin_email');?>"
					, from: $("#jvfrm_spot_rb_contact_from")
					, content: $("#jvfrm_spot_rb_contact_content")
					, to_null_msg: "<?php echo esc_attr( $mail_alert_msg['to_null_msg'] );?>"
					, from_null_msg: "<?php echo esc_attr( $mail_alert_msg['from_null_msg'] );?>"
					, subject_null_msg: "<?php echo esc_attr( $mail_alert_msg['subject_null_msg'] );?>"
					, content_null_msg: "<?php echo esc_attr( $mail_alert_msg['content_null_msg'] );?>"
					, successMsg: "<?php echo esc_attr( $mail_alert_msg['successMsg'] );?>"
					, failMsg: "<?php echo esc_attr( $mail_alert_msg['failMsg'] );?>"
					, confirmMsg: "<?php echo esc_attr( $mail_alert_msg['confirmMsg'] );?>"
					, url:"<?php echo esc_url( admin_url('admin-ajax.php' ) );?>"
				};
				$.jvfrm_spot_mail(options);
			});
			if( typeof jQuery.fn.jvfrm_spot_favorite != 'undefined' )
			{
				$("a.jvfrm_spot_favorite").jvfrm_spot_favorite({
					url				: "<?php echo esc_url( admin_url('admin-ajax.php' ) );?>"
					, user			: "<?php echo esc_attr( get_current_user_id() );?>"
					, str_nologin	: "<?php echo esc_attr( $jvfrm_spot_favorite_alerts['nologin'] );?>"
					, str_save		: "<?php echo esc_attr( $jvfrm_spot_favorite_alerts['save'] );?>"
					, str_unsave	: "<?php echo esc_attr( $jvfrm_spot_favorite_alerts['unsave'] );?>"
					, str_error		: "<?php echo esc_attr( $jvfrm_spot_favorite_alerts['error'] );?>"
					, str_fail		: "<?php echo esc_attr( $jvfrm_spot_favorite_alerts['fail'] );?>"
					, before		: function(){
						if( !( $('.javo-this-logged-in').length > 0 ) ){
							$('#login_panel').modal();
							return false;
						};
						return;
					}
				}, function(){
					if( $( this ).hasClass('remove') ){
						$( this ).closest('tr').remove();
					}
				});
			}
		});
		</script>
		<?php
	}

	public function load_file( $strFileName=false, $args=Array() ){
		if( is_Array( $args ) )
			extract( $args, EXTR_SKIP );

		if( file_exists( $strFileName ) ){
			require_once $strFileName;
			return true;
		}
		return false;
	}

	public function load_widget( $template_name=false, $args=Array() ) {
		$strFileName = $this->widget_path .'wg-javo-' . $template_name . '.php';
		return $this->load_file( $strFileName, $args );
	}

	public function widgets_init(){
		if( ! empty( $this->widgets ) ) : foreach( $this->widgets as $strFileName => $strClassName ) {
			if( $this->load_widget($strFileName) )
				register_widget( $strClassName );
		} endif;
	}

	public function vender_template( $located, $template_name ) {
		global $wc_product_vendors;

		if( is_object( $wc_product_vendors ) ){
			if( is_tax( $wc_product_vendors->token ) && 'archive-product.php' == $template_name ) {
				return get_stylesheet_directory() . '/woocommerce/taxonomy-shop_vendor.php';
			}
		}
		return $located;
	}

	public function getLoginPart()
	{
		global $jvfrm_spot_tso;

		switch( $jvfrm_spot_tso->get('login_modal_type', 2) ) {
			case 2: get_template_part('templates/parts/modal', 'login-type2'); break;
			case 1: default: get_template_part('templates/parts/modal', 'login-type1');
		}
		if( get_option( 'users_can_register' ) )
			get_template_part('templates/parts/modal', 'register');		// modal Register
	}

	public function footer_function()
	{
		global $jvfrm_spot_tso;
		echo stripslashes($jvfrm_spot_tso->get('analytics'));
		echo '<script type="text/javascript">'.stripslashes($jvfrm_spot_tso->get('custom_js', '')).'</script>';
	}

	public function single_author_information() {
		$this->section_title(
			esc_html__( "About the author", 'javospot' ),
			'author'
		);
		$this->getContent( 'html'	, 'single-footer-author' );
	}

	public function relative_posts()
	{
		global $wp_query;
		$post		= $wp_query->get_queried_object();
		$strBlock	= 'jvfrm_spot_block11';

		if( 'post' != $post->post_type || !class_exists( $strBlock ) )
			return;

		$this->section_title(
			esc_html__( "Relative Posts", 'javospot' ),
			'relative-posts'
		);

		$objBlock			= new $strBlock();
		$objOption		= Array(
			'filter_style'	=> 'paragraph',
			'count'			=> 3,
			'hide_filter'	=> true,
			'order_by'		=> 'date',
			'order_'			=> 'ASC',
			'columns'		=> 3,
			'display_category_tag' => 'hide',
			'thumbnail_size'	=> 'jvfrm-spot-box',
			'module_contents_length' => 10,
		);
		$thisTerms	= wp_get_post_terms( $post->ID, 'category', Array( 'fields' => 'ids' ) );
		if( $thisTerms[0] ) {
			$objOption[ 'filter_by' ]				= 'category';
			$objOption[ 'custom_filter' ]			= $thisTerms[0];
			$objOption[ 'custom_filter_by_post' ]	= true;
		}
		echo join(
			"\n",
			Array(
				'<div class="jv-single-footer-relative-posts">',
				$objBlock->output( $objOption ),
				'</div>',
			)
		);
	}

	public function section_title( $strLabel='No Label', $icon_id='' ){
		echo join( "\n",
			Array(
				'<div id="jv-single-'. $icon_id . '-title" class="jv-single-section-title ' . $icon_id . '">',
					'<h2 class="section-title">',
						$strLabel,
					'</h2>',
				'</div>'
			)
		);
	}

	public function is_woocommerce_page(){
		$is_woocommerce_page = false;
		foreach(
			Array(
				'shop',
				'cart',
				'checkout',
				'woocommerce',
				'account_page',
				'view_order_page',
				'checkout_pay_page',
				'lost_password_page',
				'order_received_page',
				'add_payment_method_page',
			) as $page_name
		) {
			if( function_exists( 'is_' . $page_name ) && call_user_func( 'is_' . $page_name ) ) {
				$is_woocommerce_page = true;
				break;
			}
		}
		return $is_woocommerce_page;
	}

	public static function getInstance() {
		if( !self::$instance )
			self::$instance = new self;
		return self::$instance;
	}
}

if( !function_exists( 'jvfrm_spot_layout' ) ) :
	function jvfrm_spot_layout() {
		return Jv_layout_function::getInstance();
	}
	jvfrm_spot_layout();
endif;