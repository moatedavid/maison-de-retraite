<?php

class Lava_Directory_Manager_Admin extends Lava_Directory_Manager_Func
{
	const __OPTION_GROUP__					= 'lava_directory_manager_group';

	private $admin_dir;

	private static $form_loaded					= false;
	private static $is_wpml_actived;
	private static $item_refresh_message;

	public $options;


	public function __construct()
	{
		$this->admin_dir									= trailingslashit( dirname( __FILE__ ) . '/admin' );
		$this->post_type									= self::SLUG;
		$this->featured_term							= self::getFeaturedTerm();

		$this->options = get_option( $this->getOptionFieldName() );

		self::$is_wpml_actived							= function_exists( 'icl_object_id' );

		// Admin Initialize
		add_action( 'admin_init'												, Array( $this, 'register_options' ) );
		add_action( 'admin_menu'											, Array( $this, 'register_setting_page' ) );
		add_action( 'admin_footer'											, Array( $this, 'admin_form_scripts' ) );
		add_action( 'save_post'												, Array( $this, 'save_post' ) );
		add_action( 'add_meta_boxes'									, Array( $this, 'reigster_meta_box' ), 0 );
		add_action( 'admin_enqueue_scripts'							, Array( $this, 'load_admin_page' ) );

		add_filter( "lava_{$this->post_type}_json_addition"		, Array( $this, 'json_addition' ), 10, 3 );
		add_filter( "lava_{$this->post_type}_categories"			, Array( $this, 'json_categories' ) );
		add_filter( 'lava_directory_listing_featured_no_image'	, Array( $this, 'noimage' ) );
		add_filter( "lava_{$this->post_type}_login_url"			, Array( $this, 'login_url' ) );

		// Custom Back-end column
		add_filter( 'manage_edit-' . $this->post_type . '_columns', Array( $this, 'add_manage_column' ), 8 );
		add_action( 'manage_' . $this->post_type . '_posts_custom_column', Array( $this, 'custom_manage_column_content' ), 10, 2 );

		// Custom Category Marker
		add_action( 'admin_enqueue_scripts'								, Array($this, "admin_enqueue_callback"));
		add_action( "{$this->featured_term}_edit_form_fields"	, Array($this,'edit_featured_term'), 10, 2);
		add_action( "{$this->featured_term}_add_form_fields"	, Array($this, 'add_featured_term'));
		add_action( "created_{$this->featured_term}"				, Array($this, 'save_featured_term'), 10, 2);
		add_action( "edited_{$this->featured_term}"					, Array($this, 'save_featured_term'), 10, 2);
		add_action( 'deleted_term_taxonomy'								, Array($this, 'remove_featured_term'));
		add_action( 'lava_file_script'											, Array($this, 'lava_file_script_callback'));
		add_filter( "manage_edit-{$this->featured_term}_columns" , Array($this, 'featured_term_columns'));
		add_filter( "manage_{$this->featured_term}_custom_column" , Array($this, 'manage_featured_term_columns'), 10, 3);


		require_once 'functions-admin.php';

		if( isset( $_POST[ 'lava_' . self::SLUG . '_refresh' ] ) )
			self::item_refresh();
	}

	public function load_admin_page() {
		wp_enqueue_script( 'lava-directory-manager-gmap-v3' );
	}

	public function reigster_meta_box()
	{
		foreach(
			Array( 'postexcerpt', 'commentstatusdiv', 'commentsdiv', 'slugdiv' )
			as $keyMetaBox
		) remove_meta_box( $keyMetaBox, self::SLUG, 'normal' );

		add_meta_box(
			'lava_directory_manager_metas'
			, __( "Item Additional Meta", 'Lavacode' )
			, Array( $this, 'lava_directory_manager_addition_meta' )
			, self::SLUG
			, 'advanced'
			, 'high'
		);

	}

	public function lava_directory_manager_addition_meta( $post )
	{
		global $post;

		self::$form_loaded		= 1;

		foreach(
			Array( 'lat', 'lng', 'street_lat', 'street_lng', 'street_heading', 'street_pitch', 'street_zoom', 'street_visible' )
			as $key
		) $post->$key	= floatVal( get_post_meta( $post->ID, 'lv_listing_' . $key, true ) );

		$lava_item_fields	= apply_filters( "lava_{$this->post_type}_more_meta", Array() );

		ob_start();
			do_action( "lava_{$this->post_type}_admin_metabox_before" , $post );
			require_once dirname( __FILE__) . '/admin/admin-metabox.php';
			do_action( "lava_{$this->post_type}_admin_metabox_after" , $post );
		ob_end_flush();
	}

	public function lava_directory_manager_map_meta( $post )
	{
		global $post;

		ob_start();
			do_action( "lava_{$this->post_type}_admin_map_meta_before" , $post );
			require_once dirname( __FILE__) . '/admin/admin-mapmeta.php';
			do_action( "lava_{$this->post_type}_admin_map_meta_after" , $post );
		ob_end_flush();
	}

	public function admin_form_scripts()
	{
		if( ! self::$form_loaded )
			return;

		wp_localize_script(
			sanitize_title( lava_directory()->enqueue->handle_prefix . 'admin-metabox.js' ),
			'lava_directory_manager_admin_meta_args',
			Array(
				'fail_find_address'	=> __( "You are not the author.", 'Lavacode' )
			)
		);

		wp_enqueue_script( sanitize_title( lava_directory()->enqueue->handle_prefix . 'admin-metabox.js' ) );
	}

	public function save_post( $post_id )
	{
		$lava_query		= new lava_Array( $_POST );
		$lava_PT		= new lava_Array( $lava_query->get( 'lava_pt', Array() ) );
		$lava_mapMETA	= $lava_query->get( 'lava_map_param' );
		$lava_moreMETA	= $lava_query->get( 'lava_additem_meta' );

		// More informations
		if( !empty( $lava_moreMETA ) ) : foreach( $lava_moreMETA as $key => $value ) {
			update_post_meta( $post_id, $key, $value );
		} endif;

		// Map informations
		if( !empty( $lava_mapMETA ) ) : foreach( $lava_mapMETA as $key => $value ) {
			update_post_meta( $post_id, $key, $value );
		} endif;

		// More detail picture or image ids meta
		update_post_meta( $post_id, 'detail_images', $lava_query->get( 'lava_attach' ) );

		// Google Map position meta
		if( false !== (boolean)( $meta = $lava_PT->get( 'map', false ) ) ) {
			foreach( $meta as $key => $value ) {
				update_post_meta( $post_id, "lv_listing_{$key}", $value );
			}
		}

		// Featured item meta
		update_post_meta( $post_id, '_featured_item', $lava_PT->get( 'featured', 0 ) );

		// Upldate Json
		do_action( "lava_{$this->post_type}_json_update", $post_id, get_post( $post_id ), null );

	}

	public function register_options() {
		register_setting( self::__OPTION_GROUP__ , $this->getOptionFieldName() );
	}

	public function getOptionFieldName( $option_name=false ){    // option field name

		$strFieldName = 'lava_directory_manager_settings';

		if( $option_name )
			$strFieldName = sprintf( '%1$s[%2$s]', $strFieldName, $option_name );

		return $strFieldName;
	}

	public function getOptionsPagesLists( $default )
	{
		$pages_output = Array();

		if( ! $pages = get_posts( Array( 'post_type' => 'page', 'posts_per_page' => -1 ) ) )
			return false;

		foreach( $pages as $page )
		{
			$pages_output[]	= "<option value=\"{$page->ID}\"";
			$pages_output[]	= selected( $default == $page->ID, true, false );
			$pages_output[]	= ">{$page->post_title}</option>";
		}

		return @implode( false, $pages_output );
	}

	public function register_setting_page()
	{
		add_submenu_page(
			'edit.php?post_type=' . self::SLUG
			, __( "Lava Directory Manager Settings", 'Lavacode' )
			, __( "Settings", 'Lavacode' )
			, 'manage_options'
			, 'lava-' . self::SLUG . '-settings'
			, Array( $this, 'admin_page_template' )
		);
	}

	public function admin_page_template()
	{
		global $lava_directory_manager;

		wp_enqueue_script( sanitize_title( lava_directory()->enqueue->handle_prefix . 'admin.js' ) );

		$arrTabs_args		= Array(
			''				=>	Array(
				'label'		=> __( "Home", 'Lavocode' )
				, 'group'	=> self::__OPTION_GROUP__
				, 'file'	=> $this->admin_dir . 'admin-index.php'
			)
		);

		$arrTabs		= apply_filters( "lava_{$this->post_type}_admin_tab", $arrTabs_args );

		echo self::$item_refresh_message;
		echo "<div class=\"wrap\">";
			printf( "<h2>%s</h2>", __( "Lava Directory Manager Settings", 'Lavacode' ) );
			echo "<form method=\"post\" action=\"options.php\">";
			echo "<h2 class=\"nav-tab-wrapper\">";
			$strCurrentPage	= isset( $_GET[ 'index' ] ) && $_GET[ 'index' ] != '' ? $_GET[ 'index' ] : '';
			if( !empty( $arrTabs ) ) : foreach( $arrTabs as $key => $meta ) {
					printf(
						"<a href=\"%s\" class=\"nav-tab %s\">%s</a>"
						, esc_url(
								add_query_arg(
									Array(
										'post_type' => self::SLUG
										, 'page' => 'lava-' . self::SLUG . '-settings'
										, 'index' => $key
									)
									, admin_url( 'edit.php' )
								)
							)
						, ( $strCurrentPage == $key ? 'nav-tab-active' : '' )
						, $meta[ 'label' ]
					);

				}
				echo "</h2>";
				if( $strTabMeta = $arrTabs[ $strCurrentPage ] ) {
					settings_fields( $strTabMeta[ 'group' ] );
					if( file_exists( $strTabMeta[ 'file' ] ) )
						require_once $strTabMeta[ 'file' ];
				}
			endif;

			if( apply_filters( "lava_{$this->post_type}_admin_save_button", true ) )
				printf( "<button type=\"\" class=\"button button-primary\">%s</button>", __( "Save", 'Lavacode' ) );

			echo "</form>";
			echo "<form id=\"lava_common_item_refresh\" method=\"post\">";
			wp_nonce_field( "lava_{$this->post_type}_items", "lava_{$this->post_type}_refresh" );
			echo "<input type=\"hidden\" name=\"lang\">";
			echo "</form>";
		echo "</div>";
		wp_enqueue_media();
		do_action( 'lava_' . $this->post_type . '_admin_setting_page_after' );
	}

	public function admin_welcome_template()
	{
		if( file_exists( $this->admin_dir . 'admin-welcome.php' ) )
			require_once $this->admin_dir . 'admin-welcome.php';
	}


	public function json_categories( $args )
	{
		global $lava_directory_manager_func;

		$lava_exclude					= Array();

		$lava_taxonomies				= $lava_directory_manager_func->lava_extend_item_taxonomies();

		if( empty( $lava_taxonomies ) || !is_Array( $lava_taxonomies ) )
			return $args;

		if( !empty( $lava_exclude ) ) : foreach( $lava_exclude as $terms ) {
			if( in_Array( $terms, $lava_taxonomies ) )
				unset( $lava_taxonomies[ $terms] );
		} endif;

		return wp_parse_args( Array_Keys( $lava_taxonomies ), $args );
	}

	public function json_addition( $args, $post_id, $tax )
	{
		$lava_taxonomies	= $this->json_categories( Array() );

		if( !empty( $lava_taxonomies ) ) : foreach( $lava_taxonomies as $term ) {
			$args[ $term ]	= $tax->get( $term );
		} endif;

		return $args;
	}

	public static function item_refresh()
	{
		global $wpdb;
		if( empty( $_POST ) || !check_admin_referer( 'lava_' . self::SLUG . '_items', 'lava_' . self::SLUG . '_refresh' ) )
			return;

		$lava_query	= new lava_array( $_POST );

		$lang		= $lava_query->get('lang', '');

		$lava_this_response		= Array();

		/* wpml */
		{
			$wpml_join			= "";
			$wpml_where			= "";
			$wpml_req_language	= "";
			if( self::$is_wpml_actived && $lang != '' )
			{
				if(
					function_exists( 'icl_get_languages' ) &&
					false !== (bool)( $lava_wpml_langs = icl_get_languages( 'skip_missing=0' ) )
				){
					if( !empty( $lava_wpml_langs[ $lang ][ 'translated_name' ] ) ) {
						$wpml_req_language = $lava_wpml_langs[ $lang ][ 'translated_name' ];
					}
				}

				$wpml_join	= "INNER JOIN {$wpdb->prefix}icl_translations as w ON p.ID = w.element_id";
				$wpml_where	= $wpdb->prepare( "AND w.language_code=%s" , $lang);
			}
		}

		// wpml > Multilingual Content Setup > custom post > select use posts
		$lava_refresh_items = apply_filters( 'lava_json_to_use_types', Array( self::SLUG ) );

		$lava_all_posts = Array();
		$lava_all_items = $wpdb->get_results(
			$wpdb->prepare("SELECT DISTINCT ID, post_title FROM $wpdb->posts as p {$wpml_join} WHERE p.post_type=%s AND p.post_status=%s {$wpml_where} ORDER BY p.post_date ASC"
				, self::SLUG, 'publish'
			)
			, OBJECT
		);

		foreach( $lava_all_items as $item )
		{
			// Google Map LatLng Values
			$latlng = Array(
				'lat'			=> get_post_meta( $item->ID, 'lv_listing_lat', true )
				, 'lng'			=> get_post_meta( $item->ID, 'lv_listing_lng', true )
			);

			$category			= Array();
			$category_label		= Array();

			/* Taxonomies */
			{

				$lava_all_taxonomies					= apply_filters( 'lava_' . self::SLUG . '_categories', Array() );

				foreach( $lava_all_taxonomies as $taxonomy )
				{

					$results = $wpdb->get_results(
						$wpdb->prepare("
							SELECT t.term_id, t.name FROM $wpdb->terms AS t
							INNER JOIN $wpdb->term_taxonomy AS tt ON tt.term_id = t.term_id
							INNER JOIN $wpdb->term_relationships AS tr ON tr.term_taxonomy_id = tt.term_taxonomy_id
							WHERE tt.taxonomy IN (%s) AND tr.object_id IN ($item->ID)
							ORDER BY t.name ASC"
							, $taxonomy
						)
					);
					//$category[ $taxonomy ] = $results;
					foreach( $results as $result )
					{
						$category[ $taxonomy ][]		= $result->term_id;
						$category_label[ $taxonomy ][]	= $result->name;
					}
				}
			}

			$lava_categories			= new lava_ARRAY( $category );
			$lava_categories_label	= new lava_ARRAY( $category_label );

			/* Marker Icon */ {

				$category_icon			= $lava_categories->get( 'listing_category', Array() );
				$category_icon			= reset( $category_icon );
				$lava_set_icon			= get_option( "lava_listing_category_{$category_icon}_marker", '' );
			}

			if( !empty( $latlng['lat'] ) && !empty( $latlng['lng'] ) )
			{
				$lava_all_posts_args	= Array(
					'post_id'			=> $item->ID
					, 'post_title'		=> $item->post_title
					, 'lat'				=> $latlng['lat']
					, 'lng'				=> $latlng['lng']
					, 'icon'			=> $lava_set_icon
					, 'tags'			=> $lava_categories_label->get( 'listing_keyword' )
				);
				$lava_all_posts[]		= apply_filters( 'lava_' . self::SLUG . '_json_addition', $lava_all_posts_args, $item->ID, $lava_categories );
			}
		}

		$upload_folder					= wp_upload_dir();
		$blog_id							= get_current_blog_id();
		$lava_item_type				= self::SLUG;

		$json_file = "{$upload_folder['basedir']}/lava_all_{$lava_item_type}_{$blog_id}_{$lang}.json";

		$file_handle = @fopen( $json_file, 'w' );
		@fwrite( $file_handle, json_encode( $lava_all_posts ) );
		@fclose( $file_handle );

		ob_start();
		?>
		<div class="updated">
			<?php
			if( '' !== $wpml_req_language ) {
				echo "<h3>{$wpml_req_language}: </h3>";
			} ?>
			<strong><?php _e( "Successfully Generated!", 'Lavacode' ); ?></strong>
			<u><?php echo $json_file; ?></u>
		</div>
		<?php
		self::$item_refresh_message = ob_get_clean();
	}

	public function get_settings( $option_key, $default=false )
	{
		if( array_key_exists( $option_key, (Array) $this->options ) )
			if( $value = $this->options[ $option_key ] )
				$default = $value;
		return $default;
	}

	public function noimage( $image_url ) {
		if( $noimage = $this->get_settings( 'blank_image' ) )
			return $noimage;
		return $image_url;
	}

	public function login_url( $login_url ) {
		if( $redirect = $this->get_settings( 'login_page' ) )
			return get_permalink( $redirect );
		return $login_url;
	}

	public function add_manage_column( $columns )
	{
		return wp_parse_args(
			$columns,
			Array(
				'cb'				=> '<input type="checkbox">',
				'thumbnail'	=> __( "Thumbnail", 'Lavacode' ),
			)
		);
	}

	public function custom_manage_column_content( $cols_id, $post_id=0 )
	{
		switch( $cols_id )
		{
			case 'thumbnail':
				the_post_thumbnail();
				break;
		}
	}

	public function admin_enqueue_callback(){
		if ( function_exists('wp_enqueue_media') ) {
			wp_enqueue_media();
		}
	}

	public function add_featured_term( $tag )
	{
		?>
		<div class="form-field">
			<img style="max-width:80%;"><br>
			<input type="hidden" name="lava_listing_category_marker" id="lava_listing_category_marker">
			<p class="description"><?php _e( "Category markers : you need to refresh map data after you upload or change pin (map marker). Theme settigns >> Maps", 'Lavacode');?></p>
			<button type="button" class="button button-primary fileupload" data-featured-field="[name='lava_listing_category_marker']" data-result-src><?php _e('Change', 'Lavacode');?></button>
			<button type="button" class="button fileupload-remove"><?php _e( 'Remove' , 'Lavacode');?></button>
		</div>

		<div class="form-field">
			<label for="lava_listing_category_featured"><?php _e('Category Featured Image', 'Lavacode');?></label>
			<img style="max-width:80%;"><br>
			<input type="hidden" name="lava_listing_category_featured" id="lava_listing_category_featured">
			<button type="button" class="button button-primary fileupload" data-featured-field="[name='lava_listing_category_featured']"><?php _e('Change', 'Lavacode');?></button>
			<button type="button" class="button fileupload-remove"><?php _e( 'Remove' , 'Lavacode');?></button>
		</div>
		<?php
		do_action('lava_file_script');
	}

	public function edit_featured_term($tag, $taxonomy) {
		$lava_marker			= get_option( 'lava_listing_category_'.$tag->term_id.'_marker', '' );
		$lava_featured			= get_option( 'lava_listing_category_'.$tag->term_id.'_featured', '' );
		$lava_featured_src	= wp_get_attachment_image_src( $lava_featured );
		$lava_featured_src	= $lava_featured_src[0];
		?>
		<tr class="form-field">
			<th scope="row" valign="top">
				<label for="lava_listing_category_marker"><?php _e('Map Marker', 'Lavacode');?></label>
			</th>
			<td>
				<img src="<?php echo $lava_marker;?>" style="max-width:80%;"><br>
				<input type="hidden" name="lava_listing_category_marker" id="lava_listing_category_marker" value="<?php echo $lava_marker; ?>">
				<p class="description"><?php _e( "Category markers : you need to refresh map data after you upload or change pin (map marker). Theme settigns >> Maps", 'Lavacode');?></p>
				<button type="button" class="button button-primary fileupload" data-featured-field="[name='lava_listing_category_marker']" data-result-src><?php _e('Change', 'Lavacode');?></button>
				<button type="button" class="button fileupload-remove"><?php _e( 'Remove' , 'Lavacode');?></button>
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top">
				<label for="lava_listing_category_featured"><?php _e('Category Featured Image', 'Lavacode');?></label>
			</th>
			<td>
				<img src="<?php echo $lava_featured_src;?>" style="max-width:80%;"><br>
				<input type="hidden" name="lava_listing_category_featured" id="lava_listing_category_featured" value="<?php echo $lava_featured; ?>">
				<button type="button" class="button button-primary fileupload" data-featured-field="[name='lava_listing_category_featured']"><?php _e('Change', 'Lavacode');?></button>
				<button type="button" class="button fileupload-remove"><?php _e( 'Remove' , 'Lavacode');?></button>
			</td>
		</tr>
		<?php
		do_action('lava_file_script');
	}

	public function save_featured_term($term_id, $tt_id) {
		if (!$term_id) return;

		if (isset($_POST['lava_listing_category_marker'])){
			$name = 'lava_listing_category_' .$term_id. '_marker';
			update_option( $name, $_POST['lava_listing_category_marker'] );
		}

		if (isset($_POST['lava_listing_category_featured'])){
			$name = 'lava_listing_category_' .$term_id. '_featured';
			update_option( $name, $_POST['lava_listing_category_featured'] );
		}

	}

	public function remove_featured_term($id) {
		delete_option( 'lava_listing_category_'.$id.'_marker' );
		delete_option( 'lava_listing_category_'.$id.'_featured' );
	}

	public function featured_term_columns($category_columns) {
		$new_columns		= array(
			'cb'        			=> '<input type="checkbox">'
			, 'name'      		=> __('Name', 'Lavacode')
			, 'description'	=> __('Description', 'Lavacode')
			, 'marker'			=> __('Marker Preview', 'Lavacode')
			, 'featured'		=> __('Featured Preview', 'Lavacode')
			, 'slug'				=> __('Slug', 'Lavacode')
			, 'posts'     		=> __('Items', 'Lavacode')
		);
		return $new_columns;
	}

	public function manage_featured_term_columns($out, $column_name, $cat_id){

		$marker					= get_option( 'lava_listing_category_'.$cat_id.'_marker', '' );
		$lava_featured			= get_option( 'lava_listing_category_'.$cat_id.'_featured', '' );
		$lava_featured_src	= wp_get_attachment_image_src( $lava_featured, 'thumbnail' );
		$lava_featured_src	= $lava_featured_src[0];

		switch ($column_name) {
			case 'marker':
				if(!empty($marker)){
					$out .= '<img src="'.$marker.'" style="max-width:100%;" alt="">';
				}
			break;
			case 'featured':
				if(!empty($lava_featured)){
					$out .= '<img src="'.$lava_featured_src.'" style="max-width:100%;" alt="">';
				}
			break;
		};
		return $out;
	}

	public function lava_file_script_callback(){
		wp_localize_script(
			sanitize_title( lava_directory()->enqueue->handle_prefix . 'admin-edit-term.js' ),
			'lv_edit_featured_taxonomy_variables',
			Array(
				'mediaBox_title'		=> __( "Select Category Featured Image", 'Lavacode' ),
				'mediaBox_select'	=> __( "Apply", 'Lavacode' ),
			)
		);
		wp_enqueue_script( sanitize_title( lava_directory()->enqueue->handle_prefix . 'admin-edit-term.js' ) );
	}
}