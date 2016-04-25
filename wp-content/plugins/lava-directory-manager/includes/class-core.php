<?php
class Lava_Directory_Manager_Func extends Lava_Directory_Manager
{

	public $slug = false;

	const SLUG = 'lv_listing';

	const NAME = 'listing';

	const FEATURED_TERM = 'category';

	protected $featured_term = false;

	public static $instance = false;

	private static $is_wpml_actived = false;

	public function __construct()
	{
		$this->slug					= self::SLUG;
		$this->featured_term	= self::getFeaturedTerm();

		self::$instance				= &$this;

		add_action( 'plugins_loaded'		, Array( __CLASS__, 'parse_request' ) );
		add_action( 'lava_directory_manager_init'		, Array( $this, 'init_post_type' ) );
		add_action( 'lava_directory_post_registered'	, Array( $this, 'init_taxonomies' ) );
		//add_filter( 'single_template'					, Array( $this, 'single_template' ) );

		add_action( 'wp_enqueue_scripts' , Array( $this, 'parse_single_page' ) );
		add_filter( 'the_content' , Array( $this, 'singleTemplate' ) );

		add_filter( 'lava_' . self::SLUG . '_more_meta'	, Array( __CLASS__, 'lava_more_meta_vars' ) );
		add_filter( 'lava_' . self::SLUG . '_taxonomies', Array( __CLASS__, 'lava_extend_item_taxonomies' ) );


		if( ! has_filter( 'lava_get_selbox_child_term_lists' ) )
			add_filter('lava_get_selbox_child_term_lists'	, Array( $this, 'selbox_child_term_lists_callback' ), 10, 7);

		// Auto Items Update
		add_action( 'lava_' . self::SLUG . '_json_update' ,  Array( __CLASS__, 'lava_auto_generator_trigger_callback' ), 10, 3);
		add_action( 'transition_post_status'	,  Array( __CLASS__, 'lava_auto_generator_transition_trigger_callback' ), 10, 3 );
		add_action( 'wp_trash_post'				,  Array( __CLASS__, 'lava_auto_generator_delete_trigger_callback' ) );
		add_action( 'before_delete_post'		,  Array( __CLASS__, 'lava_auto_generator_delete_trigger_callback' ) );

		// WPML
		add_action( 'icl_make_duplicate'		, Array( __CLASS__, 'lava_auto_generator_wpml_trigger_callback' ), 10, 4 );



		require_once 'functions-core.php';
		require_once 'functions-ajaxMap.php';
	}

	public static function getFeaturedTerm() {
		return  join( '_', Array( self::NAME, self::FEATURED_TERM ) );
	}

	public static function getSlug() {
		return self::SLUG;
	}

	public static function parse_request() {
		self::$is_wpml_actived				= function_exists( 'icl_object_id' );
	}

	public function load_admin_template( $template_name, $param=Array() ) {
		$this->include_template( parent::$instance->path . '/includes/admin', $template_name, $param );
	}

	public function load_template( $template_name, $param=Array() ) {
		$this->include_template( $this->template_path, $template_name, $param );
	}

	public function include_template( $strPath, $template_name, $param=Array() )
	{
		$strFilename	= trailingslashit( $strPath ) . $template_name;
		if( !empty( $param ) && is_array( $param ) ) {
			extract( $param );
		}

		if( file_exists( $strFilename ) )
			require_once( $strFilename );
	}

	public function is_dashboard( $body_class ) {
		return wp_parse_args( Array( 'lava-dashboard' ), $body_class );
	}

	public function selbox_child_term_lists_callback(
		$taxonomy
		, $attribute=Array()
		, $el='ul'
		, $default=Array()
		, $parent=0
		, $depth=0
		, $separator='&nbsp;&nbsp;&nbsp;&nbsp;'
	){

		if( !taxonomy_exists( $taxonomy ) ){
			printf( __( "%s is invalid taxonomy name.", 'Lavacode' ), $taxonomy );
			return;
		}

		$lava_this_args			= Array(
			'parent'			=> $parent
			, 'hide_empty'		=> false
			, 'fields'			=> 'id=>name'
		);

		$lava_this_terms		= (Array) get_terms( $taxonomy, $lava_this_args );
		$lava_this_return		= '';
		$lava_this_attribute	= '';

		if( ! sizeof( $lava_this_terms ) )
			return;

		if( !isset( $attribute['style'] ) )
			$attribute['style'] = '';

		if( !empty( $attribute ) ){
			foreach( $attribute as $attr => $value){
				$lava_this_attribute .= $attr . '="'. $value .'" ';
			}
		}

		$depth++;

		if( is_wp_error( $lava_this_terms ) )
			echo $lava_this_terms->get_error_message();

		else
			if( !empty( $lava_this_terms ) )
				foreach( $lava_this_terms as $term_id => $term_name ){
					switch( $el ){
					case 'select':
						$lava_this_return	.= sprintf('<option value="%s"%s>%s%s</option>%s'
							, ( $taxonomy == 'listing_keyword' ? $term_name : $term_id )
							, selected( in_Array( $term_id, (Array)$default), true, false )
							, str_repeat( $separator, $depth-1 ).' '
							, $term_name
							, $this->selbox_child_term_lists_callback($taxonomy, $attribute, $el, $default, $term_id, $depth, $separator)
						);
					break;
					case 'ul':
					default:
						$lava_this_return	.= sprintf('<li %svalue="%s" data-filter data-origin-title="%s">%s %s</li>%s'
							, $lava_this_attribute
							, $term_id
							, $term_name
							, str_repeat( '-', $depth-1 )
							, $term_name
							, $this->selbox_child_term_lists_callback($taxonomy, $attribute, $el, $default, $term_id, $depth, $separator)
						);
					}; // End Switch
				};

		return $lava_this_return;


	}

	public static function lava_more_meta_vars()
	{
		return Array(
			'_website'			=> Array(
				'label'			=> __( "Website", 'Lavacode' )
				, 'element'		=> 'input'
				, 'type'		=> 'text'
				, 'class'		=> 'all-options'
			)
			, '_email'		=> Array(
				'label'			=> __( "Email", 'Lavacode' )
				, 'element'		=> 'input'
				, 'type'		=> 'email'
				, 'class'		=> 'all-options'
			)
			, '_address'		=> Array(
				'label'			=> __( "Address", 'Lavacode' )
				, 'element'		=> 'input'
				, 'type'		=> 'text'
				, 'class'		=> 'all-options'
			)
			, '_phone1'			=> Array(
				'label'			=> __( "Contact Phone 1", 'Lavacode' )
				, 'element'		=> 'input'
				, 'type'		=> 'text'
				, 'class'		=> 'all-options'
			)
			, '_phone2'	=> Array(
				'label'			=> __( "Contact Phone 2", 'Lavacode' )
				, 'element'		=> 'input'
				, 'type'		=> 'text'
				, 'class'		=> 'all-options'
			), '_prix'	=> Array(
				'label'			=> __( "Tarif / mensuel", 'Lavacode' )
				, 'element'		=> 'input'
				, 'type'		=> 'text'
				, 'class'		=> 'all-options'
			)

		);
	}

	public static function lava_extend_item_taxonomies()
	{
		return Array(
			'listing_amenities'				=> Array(
				'post_type'					=> self::SLUG
				, 'args'						=> Array(
					'hierarchical'			=> true
					, 'label'					=> _x( "Listing Amenities", "Amenities Label", 'Lavacode')
					, 'labels'					=> Array(
						'menu_name'		=> _x( "Amenities", "Amenities Name", 'Lavacode')
					)
					, 'rewrite'				=> Array(
						'slug'					=> self::NAME . '_amenities'
					)
				)
			)
			, 'listing_category'			=> Array(
				'post_type'					=> self::SLUG
				, 'args'						=> Array(
					'hierarchical'			=> true
					, 'label'					=> _x( "Listing Categories", "Item Types Label", 'Lavacode')
					, 'labels'					=> Array(
						'menu_name'		=> _x( "Categories", "Item Types Name", 'Lavacode')
					)
					, 'rewrite'				=> Array(
						'slug'					=> self::NAME . '_category'
					)
				)
			)
			, 'listing_location'			=> Array(
				'post_type'				=> self::SLUG
				, 'args'				=> Array(
					'hierarchical'		=> true
					, 'label'			=> _x( "Listing Location", "Location Label", 'Lavacode')
					, 'labels'			=> Array(
						'menu_name'		=> _x( "Locations", "Location Name", 'Lavacode')
					)
					, 'rewrite'			=> Array(
						'slug'			=> self::NAME . '_location'
					)
				)
			)
			, 'listing_keyword'			=> Array(
				'post_type'				=> self::SLUG
			, 'args'				=> Array(
					'hierarchical'		=> true
				, 'label'			=> _x( "Listing Keyword", "Keyword Label", 'Lavacode')
				, 'labels'			=> Array(
						'menu_name'		=> _x( "Keyword", "Keyword Name", 'Lavacode')
					)
				, 'rewrite'			=> Array(
						'slug'			=> self::NAME . '_keyword'
					)
				)
			)
		);
	}

	public static function init_post_type()
	{
		$default_post_type_args	=
			Array(
				'public'				=> true
				, 'publicly_queryable'	=> true
				, 'show_ui'				=> true
				, 'show_in_menu'		=> true
				, 'query_var'			=> true
				, 'map_meta_cap'		=> true
				, 'has_archive'			=> true
				, 'hierarchical'		=> false
				, 'menu_position'		=> null
				, 'supports'			=> Array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
			);

		$lava_post_types_args =
			Array(
				self::SLUG	=>
					Array(
						'args' =>
							Array(
								'label'		=> __( "Listings", 'Lavacode' )
								, 'labels'	=> Array(
									'name'               => _x( 'Listings', 'post type general name', 'Lavacode' )
									, 'singular_name'      => _x( 'Listing', 'post type singular name', 'Lavacode' )
									, 'menu_name'          => _x( 'Listings', 'admin menu', 'Lavacode' )
									, 'name_admin_bar'     => _x( 'Listing', 'add new on admin bar', 'Lavacode' )
									, 'add_new'            => _x( 'Add New', 'Listing', 'Lavacode' )
									, 'add_new_item'       => __( 'Add New Listing', 'Lavacode' )
									, 'new_item'           => __( 'New Listing', 'Lavacode' )
									, 'edit_item'          => __( 'Edit Listing', 'Lavacode' )
									, 'view_item'          => __( 'View Listing', 'Lavacode' )
									, 'all_items'          => __( 'All Listings', 'Lavacode' )
									, 'search_items'       => __( 'Search Listings', 'Lavacode' )
									, 'parent_item_colon'  => __( 'Parent Listings:', 'Lavacode' )
									, 'not_found'          => __( 'Not found Listings.', 'Lavacode' )
									, 'not_found_in_trash' => __( 'Not found properties in trash.', 'Lavacode' )
								)
								, 'rewrite'	=> Array( 'slug' => self::NAME )
							)
					)
			);

		$lava_post_types = apply_filters( 'lava_directory_post_type_args', $lava_post_types_args );

		if( !empty( $lava_post_types ) )
		{
			foreach( $lava_post_types as $post_type => $options )
			{
				$argsPostType	= wp_parse_args( $options['args'], $default_post_type_args );
				register_post_type( $post_type, $argsPostType );
			}
		}

		do_action( 'lava_directory_post_registered' );
	}

	public function init_taxonomies()
	{
		if( ! $lava_taoxnomies = self::lava_extend_item_taxonomies() )
			return false;

		foreach( $lava_taoxnomies as $taxonomy => $options ) {
			register_taxonomy( $taxonomy, $options[ 'post_type' ], $options[ 'args' ] );
		}

	}

	public function single_template( $single )
	{
		global
			$post
			, $lava_directory_manager;

		if( empty( $post ) )
			return $single;

		if( get_post_type( $post ) === self::SLUG ) {

			self::setupdata( $post );
			return "{$lava_directory_manager->template_path}/single-template.php";
		}

		return $single;
	}

	public function parse_single_page() {
		global $post;

		if( !empty( $post->post_type ) && $post->post_type === self::SLUG ) {
			add_action( 'wp_enqueue_scripts' , Array( $this, 'single_enqueue' ), 11 );

			/* Wordpress Emoji & Google StreetView Clash */
			remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
			remove_action( 'wp_print_styles', 'print_emoji_styles' );
		}
	}

	public function singleTemplate( $content ) {
		global $post, $lava_directory_manager;

		if( in_the_loop() && is_singular( self::SLUG ) ) {

			remove_filter( 'the_content', Array( $this, 'singleTemplate' ) );
			ob_start();
			require_once "{$lava_directory_manager->template_path}/content.php";
			$content = ob_get_clean();

			add_filter( 'the_content', Array( $this, 'singleTemplate' ) );
		}
		return $content;

	}

	public function getTermsNameInItems( $post_id, $taxonomy=false, $sep=', ' )
	{

		$output_terms = Array();

		if( $terms = wp_get_object_terms( $post_id, $taxonomy, Array( 'fields' => 'names' ) ) )
		{
			$output_terms = @implode( $sep, $terms );
			$output_terms = trim( $output_terms );
			// $output_terms = substr( $output_terms, 0, -1 );
		}else{
			$output_terms = '';
		}
		return $output_terms;
	}

	function single_enqueue(){
		global $WP_Views;

			// Stylesheets
			wp_enqueue_style( 'flexslider-css' );

			// Script files
			wp_enqueue_script( 'google-maps' );
			wp_enqueue_script( 'lava-directory-manager-gmap-v3' );
			wp_enqueue_script( 'lava-directory-manager-Google-Map-Info-Bubble' );
			wp_enqueue_script( 'lava-directory-manager-okVideo-Plugin' );
			wp_enqueue_script( 'lava-directory-manager-jQuery-lava-Favorites' );
			wp_enqueue_script( 'lava-directory-manager-jQuery-flex-Slider' );
			wp_enqueue_script( 'lava-directory-manager-jquery-magnific-popup' );
			wp_enqueue_script( 'lava-directory-manager-jQuery-Rating' );
			wp_enqueue_script( 'lava-directory-manager-lava-single-js' );
			wp_enqueue_script( 'lava-directory-manager-jquery-flexslider-min-js' );

			remove_action('wp_print_styles', array($WP_Views, 'add_render_css'));
			remove_action('wp_head', 'wpv_add_front_end_js');

			do_action( 'lava_' . self::SLUG . '_manager_single_enqueues' );
	}

	public function get_edit_link( $post_id=false )
	{
		global $post;

		$output_url			= false;
		$temp_post			= $post;

		if( $post_id )
			$post			= get_post( $post_id );

		if(
			intVal( lava_directory_manager_get_option( 'page_add_' . self::SLUG ) ) > 0	&&
			( $add_itemPage = get_permalink( lava_directory_manager_get_option( 'page_add_' . self::SLUG ) ) ) &&
			( $post->post_author == get_current_user_id() || current_user_can( 'manage_options' ) )
		)	$output_url		= add_query_arg( Array( 'edit' => $post->ID ), $add_itemPage );

		$output_url			= apply_filters( 'lava_directory_edit_link', $output_url, $post );
		$post				= $temp_post;

		return esc_url( $output_url );
	}

	public static function setupdata( &$post )
	{
		global $lava_directory_manager;
		setup_postdata( $post );


		$arrMoreMeta			= apply_filters( 'lava_' . self::SLUG . '_more_meta', Array() );

		$attachment_noimage		= apply_filters( 'lava_directory_listing_featured_no_image', $lava_directory_manager->image_url . 'no-image.png' );
		$avatar_meta			= wp_get_attachment_image_src( get_the_author_meta( 'avatar' ), 'large' );
		$strAvatarURL			= isset( $avatar_meta[0] ) ? $avatar_meta[0] : $attachment_noimage ;


		// Post Author Meta
		$post->avatar			= $strAvatarURL;
		$post->display_name		= get_the_author_meta( 'display_name' );
		$post->email			= get_the_author_meta( 'user_email' );
		$post->item_count	= count_user_posts( get_the_author_meta( 'ID' ), self::SLUG );

		// Post More Meta
		$post->attach			= get_post_meta( $post->ID, 'detail_images', true );

		if( !empty( $arrMoreMeta ) ) foreach( $arrMoreMeta as $key => $meta )
			$post->$key			= get_post_meta( $post->ID, $key, true );

	}

	public static function setup_mapdata( &$post )
	{
		global $lava_directory_manager;
		setup_postdata( $post );

		$post->crossdomain				= true;

		$lava_get_query						= new lava_Array( $_GET );
		$lava_post_query						= new lava_Array( $_POST );

		$post->lava_type = self::SLUG;
		$post->lava_current_key = $lava_get_query->get( 'keyword', $lava_post_query->get( 'keyword', null ) );
		$post->lava_current_geo = $lava_get_query->get( 'geolocation', $lava_post_query->get( 'geolocation', null ) );
		$post->lava_current_rad = $lava_get_query->get( 'radius_key', $lava_post_query->get( 'radius_key', null ) );
		$post->lava_current_dis = $lava_get_query->get( 'radius', $lava_post_query->get( 'radius', 0 ) );

		$post->json_file = lava_directory()->core->getJsonFileName();
		do_action( "lava_{$post->lava_type}_setup_mapdata", $post, $lava_get_query, $lava_post_query );
	}

	public function getJsonFileName(){

		$is_crossdomain = false;
		$strUploadDir = wp_upload_dir();
		$intBlogID = get_current_blog_id();
		$strCurrentLang =  defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : '';;
		$strJSON = $is_crossdomain ?
			"lava_all_{$this->slug}_{$intBlogID}_{$strCurrentLang}.json":
			"{$strUploadDir['baseurl']}/lava_all_{$this->slug}_{$intBlogID}_{$strCurrentLang}.json";

		return esc_url_raw( $strJSON );
	}

	public static function lava_auto_generator_trigger_callback( $post_id, $post, $update ) {
		self::lava_auto_generator_callback( $post_id, 'publish' !== get_post_status( $post_id ) );
	}

	public static function lava_auto_generator_wpml_trigger_callback( $master, $lang=false,  $post_args=Array(), $post_id=0 ) {
		self::lava_auto_generator_callback( $post_id, 'publish' !== get_post_status( $post_id ) );
	}

	public static function lava_auto_generator_delete_trigger_callback( $post_id  ) {
		self::lava_auto_generator_callback( $post_id, true );
	}

	public static function lava_auto_generator_transition_trigger_callback( $state, $old, $post ) {
		if( is_object( $post ) && !empty( $post ) )
			self::lava_auto_generator_callback( $post->ID, 'publish' !== $state );
	}

	public static function lava_auto_generator_callback( $post_id, $is_remove = false )
	{
		global $wpdb;

		$is_execusion		= $get_lang_by_post = $lava_is_update = false;

		if(
			get_post_type( $post_id ) === self::SLUG &&
			(
				get_post_status( $post_id )  === 'publish' ||
				$is_remove
			)
		) $is_execusion	= true;

		if( !$is_execusion )
			return $post_id;

		if( self::$is_wpml_actived && defined( 'ICL_LANGUAGE_CODE' ) )
			if( ! $get_lang_by_post	= $wpdb->get_var(
				$wpdb->prepare( "select language_code from {$wpdb->prefix}icl_translations where element_id=%d", $post_id )
			) ) return;

		$upload_folder			= wp_upload_dir();
		$blog_id					= get_current_blog_id();
		$json_file					= "{$upload_folder['basedir']}/lava_all_" . self::SLUG . "_{$blog_id}_{$get_lang_by_post}.json";

		if( file_exists( $json_file ) ) {
			$json_contents	= file_get_contents( $json_file );
			$lava_all_posts	= json_decode( $json_contents, true );
		}else{
			$lava_all_posts = Array();
		}

		// Google Map LatLng Values
		$latlng						= Array(
			'lat'						=> get_post_meta( $post_id, 'lv_listing_lat', true )
			, 'lng'						=> get_post_meta( $post_id, 'lv_listing_lng', true )
		);

		if( !$is_remove && ( empty( $latlng[ 'lat' ] ) || empty( $latlng[ 'lng' ] ) ) )
			return;

		$category					= Array();
		$category_label		= Array();

		/* Taxonomies */ {

			$lava_all_taxonomies				= apply_filters( 'lava_' . self::SLUG . '_categories', Array( 'post_tag' ) );

			if( !empty( $lava_all_taxonomies ) ) : foreach( $lava_all_taxonomies as $taxonomy )
			{

				$results = $wpdb->get_results(
					$wpdb->prepare("
						SELECT
							t.term_id, t.name
						FROM
							$wpdb->terms AS t
						INNER JOIN
							$wpdb->term_taxonomy AS tt
						ON
							tt.term_id = t.term_id
						INNER JOIN
							$wpdb->term_relationships AS tr
						ON
							tr.term_taxonomy_id = tt.term_taxonomy_id
						WHERE
							tt.taxonomy IN (%s)
						AND
							tr.object_id IN ($post_id)
						ORDER
							BY t.name ASC"
						, $taxonomy
					)
				);
				//$category[ $taxonomy ] = $results;
				foreach( $results as $result )
				{
					$category[ $taxonomy ][]			= $result->term_id;
					$category_label[ $taxonomy ][]	= $result->name;
				}
			} endif;
		}

		$lava_categories			= new lava_ARRAY( $category );
		$lava_categories_label	= new lava_ARRAY( $category_label );

		/* Marker Icon */ {
			$category_icon			= isset( $category[ 'listing_category' ][0] )  ? $category[ 'listing_category' ][0]  : false;
			$lava_set_icon			= get_option( "lava_listing_category_{$category_icon}_marker", '' );
		}

		$lava_result_args			= Array(
			'post_id'					=> $post_id
			, 'post_title'				=> get_the_title( $post_id )
			, 'lat'							=> $latlng['lat']
			, 'lng'							=> $latlng['lng']
			, 'icon'						=> $lava_set_icon
			, 'tags'						=> $lava_categories_label->get( 'post_tag' )
		);

		if( !empty( $lava_all_taxonomies ) ) : foreach( $lava_all_taxonomies as $taxonomy ) {
			$lava_result_args[ $taxonomy ]	= $lava_categories->get( $taxonomy );
		} endif;

		$lava_result		= apply_filters( 'lava_' . self::SLUG . '_json_addition', $lava_result_args, $post_id , $lava_categories );

		if( !empty( $lava_all_posts ) )
		{

			foreach( $lava_all_posts as $index => $post_object )
			{

				if( $post_object['post_id'] == $post_id )
				{
					if( ! $is_remove )
					{
						// Added Items
						$lava_all_posts[ $index ] = $lava_result;
					}else{
						// Removed Items
						unset( $lava_all_posts[ $index ] );
					}

					// Process?
					$lava_is_update = true;
				}
			}
		}
		if( ! $lava_is_update && ! $is_remove  ) {
			$lava_all_posts[] = $lava_result;
		}

		usort(
			$lava_all_posts
			, create_function(
				'$a,$b'
				,'return strlen($b["post_id"]) - strlen($a["post_id"]);'
			)
		);

		// Make JSON file
		$file_handler	= @fopen( $json_file, 'w' );
		@fwrite( $file_handler, json_encode( $lava_all_posts ) );
		@fclose( $file_handler );
	}
}