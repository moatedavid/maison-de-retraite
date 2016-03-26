<?php
class jvfrm_spot_theme_admin
{

	const NAV_FIELD_KEY = 'jvfrm_spot_nav_option';
	public $option_format = 'jvfrm_spot_%s_featured';

	public $notices = Array();

	public function __construct()
	{

		// Include FIles
		$this->load_files();

		// Uploader Memory Check
		$this->memoryCheck();

		// Display admin notice
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );

		// Custom Category
		add_action( 'admin_init', array( $this, 'customCategoryTerm' ) );

		// Custom Category Marker
		add_action( 'admin_enqueue_scripts'		, Array($this, 'admin_enqueue_callback' ));
		add_action( 'jvfrm_spot_file_script'			, Array($this, 'jvfrm_spot_file_script_callback'));

		// Navigation Additional More Variables
		add_action( 'admin_init', array( $this, 'customNavFields' ) );

	}

	public function load_files(){
		include_once JVFRM_SPOT_CLS_DIR . '/edit_custom_walker.php';
	}

	public function customCategoryTerm(){
		add_action( "category_edit_form_fields"	, Array($this,'edit_featured_term'), 10, 2);
		add_action( "category_add_form_fields"	, Array($this, 'add_featured_term'));
		add_action( "created_category"			, Array($this, 'save_featured_term'), 10, 2);
		add_action( "edited_category"			, Array($this, 'save_featured_term'), 10, 2);
		add_action( 'deleted_term_taxonomy'		, Array($this, 'remove_featured_term'));
		add_filter( "manage_edit-category_columns" , Array($this, 'featured_term_columns'));
		add_filter( "manage_category_custom_column" , Array($this, 'manage_featured_term_columns'), 10, 3);
	}

	public function customNavFields(){
		add_filter( 'wp_setup_nav_menu_item', array( $this, 'apparence_menu_define_vars' ) );
		add_action( 'wp_update_nav_menu_item', array( $this, 'apparence_menu_save_vars' ), 10, 3 );
		add_action( 'wp_edit_nav_menu_walker', array( $this, 'apparence_menu_walker' ), 10, 2 );
		add_action( 'wp_content_panel_nav_menu_item', array( $this, 'apparence_menu_append_option' ), 10, 2 );
	}

	public function admin_notices() {

		if( empty( $this->notices ) )
			return false;

		foreach( $this->notices as $notice_id => $arrNotice ) {

			switch( $arrNotice[ 'type' ] ) {
				case 'error' : $strNoticeType = 'notice-error'; break;
				case 'warning' : $strNoticeType = 'notice-warning'; break;
				case 'warning-lg' : $strNoticeType = 'notice-warning-lg'; break;
				case 'success' : $strNoticeType = 'notice-success'; break;
				case 'info' : default : $strNoticeType = 'notice-info';
			}

			printf(
				'<div class="jvfrm-notice %1$s %2$s"><p>%3$s</p></div>',
				$strNoticeType,
				$notice_id,
				$arrNotice[ 'comment' ]
			);
		}

	}

	public function addNotice( $err_id='', $typeMessage='', $strMessage='' ) {
		$this->notices[ $err_id ] = Array(
			'type' => $typeMessage,
			'comment' => $strMessage,
		);
	}

	public function removeNotice( $err_id ) {
		unset( $this->notices[ $err_id ] );
	}

	public function memoryCheck() {

		$intPHP_Memory = intVal( ini_get( 'memory_limit' ) );
		$intPHP_Memory_Allow = 128;

		$intWP_Memory = defined( 'WP_MEMORY_LIMIT' ) ? intVal( WP_MEMORY_LIMIT ) : 0;

		if( is_multisite() ){
			$intWP_Memory_Allow = 64;
		}else{
			$intWP_Memory_Allow = 40;
		}

		if(
			( $intPHP_Memory >= $intPHP_Memory_Allow ) &&
			( $intWP_Memory >= $intWP_Memory_Allow )
		) return false;

		$strDetailLink = sprintf(
			'<br><a href="%1$s" target="_blank">%2$s</a>',
			// esc_url( 'codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP' ),
			esc_url( 'wordimpress.com/how-to-easily-increase-wordpress-and-phps-memory-limit/' ),
			esc_html__( "How to increase", 'javospot' )
		);

		$this->addNotice(
			'memory_limit_error',
			'warning-lg',
			sprintf(
				__( '<strong>Javo Themes</strong><br>%5$sPHP Memory: %1$s( Recommended: %2$s ), Wordpress Memory: %3$s( Recommended: %4$s )%6$s', 'javospot' ),
				$intPHP_Memory,
				$intPHP_Memory_Allow,
				$intWP_Memory,
				$intWP_Memory_Allow,
				__( "Your max uploading size is less than 128MB. It may cause some problems. Please increase your uploading size.", 'javospot' ) . '<br>',
				$strDetailLink
			)
		);
	}

	public function admin_enqueue_callback(){
		if ( function_exists('wp_enqueue_media') ) {
			wp_enqueue_media();
		}
	}

	public function getFeatured( $term_id=0 ) {
		return get_option( sprintf( $this->option_format, $term_id ) );
	}

	public function setFeatured( $term_id=0, $value='' ) {
		return update_option( sprintf( $this->option_format, $term_id ), $value );
	}

	public function removeFeatured( $term_id=0 ) {
		return delete_option( sprintf( $this->option_format, $term_id ) );
	}

	public function add_featured_term( $tag )
	{
		?>
		<div class="form-field jv-uploader-wrap">
			<label for="jvfrm_spot_category_featured"><?php esc_html_e('Category Featured Image', 'javospot');?></label>
			<img style="max-width:80%;"><br>
			<input type="text" name="jvfrm_spot_category_featured" id="jvfrm_spot_category_featured" data-id class="hidden">
			<button type="button" class="button button-primary upload" data-featured-field="[name='jvfrm_spot_category_featured']"><?php esc_html_e('Change', 'javospot');?></button>
			<button type="button" class="button remove"><?php esc_html_e( 'Remove' , 'javospot');?></button>
		</div>
		<?php
		do_action('jvfrm_spot_file_script');
	}

	public function edit_featured_term($tag, $taxonomy) {
		$jvfrm_spot_featured		= $this->getFeatured( $tag->term_id );
		$jvfrm_spot_featured_src	= wp_get_attachment_image_src( $jvfrm_spot_featured );
		$jvfrm_spot_featured_src	= $jvfrm_spot_featured_src[0];
		?>
		<tr class="form-field">
			<th scope="row" valign="top">
				<label for="jvfrm_spot_category_featured"><?php esc_html_e('Category Featured Image', 'javospot');?></label>
			</th>
			<td class="jv-uploader-wrap">
				<img src="<?php echo $jvfrm_spot_featured_src;?>" style="max-width:80%;"><br>
				<input type="text" name="jvfrm_spot_category_featured" id="jvfrm_spot_category_featured" data-id value="<?php echo $jvfrm_spot_featured; ?>" class="hidden">
				<button type="button" class="button button-primary upload" data-featured-field="[name='jvfrm_spot_category_featured']"><?php esc_html_e('Change', 'javospot');?></button>
				<button type="button" class="button remove"><?php esc_html_e( 'Remove' , 'javospot');?></button>
			</td>
		</tr>
		<?php
		do_action('jvfrm_spot_file_script');
	}

	public function save_featured_term($term_id, $tt_id) {
		if (!$term_id) return;

		if (isset($_POST['jvfrm_spot_category_featured'])){
			$this->setFeatured( $term_id, $_POST['jvfrm_spot_category_featured'] );
		}
	}

	public function remove_featured_term( $id ) {
		$this->removeFeatured( $id );
	}

	public function featured_term_columns($category_columns) {
		$new_columns		= array(
			'cb'			=> '<input type="checkbox">'
			, 'name'		=> esc_html__('Name', 'javospot')
			, 'description'	=> esc_html__('Description', 'javospot')
			, 'featured'	=> esc_html__('Featured Preview', 'javospot')
			, 'slug'		=> esc_html__('Slug', 'javospot')
			, 'posts'		=> esc_html__('Items', 'javospot')
		);
		return $new_columns;
	}

	public function manage_featured_term_columns($output, $column_name, $cat_id){
		$jvfrm_spot_featured		= $this->getFeatured( $cat_id );
		$jvfrm_spot_featured_src	= wp_get_attachment_image_src( $jvfrm_spot_featured, 'thumbnail' );
		$jvfrm_spot_featured_src	= $jvfrm_spot_featured_src[0];

		switch ($column_name) {
			case 'featured':

				if(!empty($jvfrm_spot_featured)){
					$output .= '<img src="'.$jvfrm_spot_featured_src.'" style="max-width:100%;" alt="">';
				}
			break;
		};
		return $output;
	}

	public function jvfrm_spot_file_script_callback(){
		echo join( "\n", Array(
			"\n<script type=\"text/javascript\">",
			"\tvar jvfrm_spot_metabox_variable =" . json_encode(
				Array()
			) . ';',
			"</script>\n",
		) );
	}

	public function apparence_menu_define_vars( $_mnu_item ){
		$_mnu_item->anchor = get_post_meta( $_mnu_item->ID, '_menu_item_anchor', true );
		$_mnu_item->scrollspy = get_post_meta( $_mnu_item->ID, '_menu_item_scrollspy', true );
		return $_mnu_item;
	}

	public function apparence_menu_walker( $walker_name, $menu_id ){
		return 'Walker_Nav_Menu_Edit_Custom';
	}

	public function apparence_menu_save_vars( $_mnu_ID, $_mnu_item_ID, $args  ){
		$arrOptions = Array(
			'_menu_item_anchor', '_menu_item_scrollspy'
		);
		foreach( $arrOptions as $optionName ) {
			$strValue = isset(  $_POST[ $optionName ][ $_mnu_item_ID ] ) ? $_POST[ $optionName ][ $_mnu_item_ID ] : false;
			update_post_meta( $_mnu_item_ID, $optionName, $strValue );
		}
	}

	public function getNavField( $item_id=0, $item_field_key=''  ){
		// return sprintf( '%s[%s][%s]', self::NAV_FIELD_KEY, $item_id, $item_field_key );
		 return sprintf( '%s[%s]', $item_field_key, $item_id );
	}

	public function apparence_menu_append_option( $item, $item_id=0 ) {

		if( 'post_type' !== $item->type )
			return;
		?>
		<p class="field-custom description description-thin">
			<label for="edit-menu-item-subtitle-<?php echo $item_id; ?>">
				<br>
				<input type="checkbox" id="edit-menu-item-subtitle-<?php echo $item_id; ?>" class="widefat code edit-menu-item-custom" name="<?php echo $this->getNavField(  $item_id, '_menu_item_scrollspy' );?>" value="1" <?php checked( '1' == $item->scrollspy ); ?> />
				<?php esc_html_e( "Use as a scrollspy menu", 'javospot' ); ?>
			</label>
		</p>
		<p class="field-custom description description-thin">
			<label for="edit-menu-item-subtitle-<?php echo $item_id; ?>">
				<?php esc_html_e( "Scrollspy Anchor", 'javospot' ); ?><br />
				<input type="text" id="edit-menu-item-subtitle-<?php echo $item_id; ?>" class="widefat code edit-menu-item-custom" name="<?php echo $this->getNavField(  $item_id, '_menu_item_anchor' );?>" value="<?php echo esc_attr( $item->anchor ); ?>" />
			</label>
		</p>
		<?php
	}
}
new jvfrm_spot_theme_admin;