<?php
/**
 *	Javo Map Get Inforwindow Content
 *
 * @type		filter
 *	@function	jvfrm_spot_map_info_window_content
 */
add_action( 'wp_ajax_jvfrm_spot_map_info_window_content'			, 'jvfrm_spot_map_info_window_content' );
add_action( 'wp_ajax_nopriv_jvfrm_spot_map_info_window_content'	, 'jvfrm_spot_map_info_window_content' );

function jvfrm_spot_map_info_window_content()
{
	global $jvfrm_spot_tso;

	header( 'Content-Type: application/json; charset=utf-8' );

	$jvfrm_spot_query		= new jvfrm_spot_array( $_POST );
	$jvfrm_spot_result		= Array( "state" => "fail" );

	if( false !== ( $post_id = $jvfrm_spot_query->get( "post_id", false ) ) )
	{
		$post					= get_post( $post_id );

		//
		if( false == ( $jvfrm_spot_this_author		= get_userdata( $post->post_author ) ) )
		{
			$jvfrm_spot_this_author					= new stdClass();
			$jvfrm_spot_this_author->display_name		= '';
			$jvfrm_spot_this_author->user_login		= '';
			$jvfrm_spot_this_author->avatar			= 0;
		}


		// Post Thumbnail
		if( '' !== ( $jvfrm_spot_this_thumb_id		= get_post_thumbnail_id( $post->ID ) ) )
		{
			$jvfrm_spot_this_thumb_url					= wp_get_attachment_image_src( $jvfrm_spot_this_thumb_id , 'jvfrm-spot-box-v' );

			if( isset( $jvfrm_spot_this_thumb_url[0] ) ) {
				$jvfrm_spot_this_thumb					= $jvfrm_spot_this_thumb_url[0];
			}
		}


		// If not found this post a thaumbnail
		if( empty( $jvfrm_spot_this_thumb ) ) {
			$jvfrm_spot_this_thumb		=	$jvfrm_spot_tso->get( 'no_image', JVFRM_SPOT_IMG_DIR . '/no-image.png' );
		}
		$jvfrm_spot_this_thumb			= apply_filters( 'jvfrm_spot_map_list_thumbnail', $jvfrm_spot_this_thumb, $post );
		$jvfrm_spot_this_thumb			= "<div class=\"javo-thb\" style=\"background-image:url({$jvfrm_spot_this_thumb});\"></div>";

		$strAddition_meta			= '';
		if( class_exists( 'Jvfrm_Spot_Module' ) && class_exists( 'jvfrm_spot_Directory_Shortcode') ) {
			add_filter( 'jvfrm_spot_Jvfrm_Spot_Module_additional_meta', Array( jvfrm_spot_Directory_Shortcode::$scdInstance, 'additional_meta' ), 10, 2 );
			$objShortcode			= new Jvfrm_Spot_Module( $post );
			$strAddition_meta		= "<i class='fa fa-map-marker'></i> ".$objShortcode->c( 'listing_location', __( "Not Set", 'javospot' ) )." <i class='fa fa-folder-o'></i> ".$objShortcode->c( 'listing_category', __( "Not Set", 'javospot' ) );
		}

		// Other Informations
		$jvfrm_spot_result					= Array(
			'state'					=> 'success'
			, 'meta'				=> $strAddition_meta
			, 'post_id'				=> $post->ID
			, 'post_title'			=> $post->post_title
			, 'permalink'			=> get_permalink( $post->ID )
			, 'thumbnail'			=> $jvfrm_spot_this_thumb
			, 'author_name'			=> $jvfrm_spot_this_author->display_name
		);
	}
	die( json_encode( $jvfrm_spot_result ) );
}

/**
 *	Javo Map Get Lists Module
 *
 * @type		filter
 *	@function	jvfrm_spot_map_list_content
 */
add_action( 'wp_ajax_nopriv_jvfrm_spot_map_list'	, 'jvfrm_spot_map_list_content' );
add_action( 'wp_ajax_jvfrm_spot_map_list'			, 'jvfrm_spot_map_list_content' );

function jvfrm_spot_map_list_content()
{
	header( 'Content-Type: application/json; charset=utf-8' );

	$argsPosts						= isset( $_REQUEST[ 'post_ids' ] ) ? (Array) $_REQUEST[ 'post_ids' ] : Array();
	$argsTemplate					= isset( $_REQUEST[ 'template' ] ) ? $_REQUEST[ 'template' ] : 0;

	$clsMapName					= 'module1';

	if( !class_exists( jvfrm_spot_core()->getCoreName( 'Map' ) ) )
		$clsMapName					= 'module12';

	$clsListName					= 'module1';

	$clsMapName					= apply_filters( 'jvfrm_spot_template_map_module', $clsMapName, $argsTemplate );
	$clsListName					= apply_filters( 'jvfrm_spot_template_list_module', $clsListName, $argsTemplate );

	$strBasicTemplate			= "<div class=\"col-md-12\">%s</div>";
	$strMapTemplate			= $strBasicTemplate;

	if( !class_exists( jvfrm_spot_core()->getCoreName( 'Map' ) ) )
		$strMapTemplate	= "<div class=\"col-md-6\">%s</div>";

	$arrBasicModuleOption	= Array(
		'length_content'			=> 12,
		'length_title'					=> 10,
	);

	$strMapColumn				= apply_filters( 'jvfrm_spot_template_map_module_loop', $strMapTemplate, $clsMapName, $argsTemplate );
	$strListColumn					= apply_filters( 'jvfrm_spot_template_list_module_loop', $strBasicTemplate, $clsListName, $argsTemplate );
	$arrMapModuleOption		= apply_filters( 'jvfrm_spot_template_map_module_options', $arrBasicModuleOption, $argsTemplate );
	$arrListModuleOption		= apply_filters( 'jvfrm_spot_template_list_module_options', $arrBasicModuleOption, $argsTemplate );

	if( empty( $argsPosts ) )
		die;

	$arrOutput			= Array( 'map' => Array(), 'list' => Array() );
	//$arrPosts				= get_posts( Array( 'post_type' => jvfrm_spot_core()->slug, 'include' => $argsPosts ) );

	do_action( 'jvfrm_spot_template_all_module_loop_before', $argsTemplate );
	if( !empty( $argsPosts ) ) : foreach( $argsPosts as $post_id ) {

		if( ! $post = get_post( $post_id ) )
			continue;

		if( class_exists( $clsMapName ) && class_exists( $clsListName ) ) {
			$objModuleMap		= new $clsMapName( $post, $arrMapModuleOption );
			$objModuleList			= new $clsListName( $post, $arrListModuleOption );
			$arrOutput['map'][]	= sprintf( $strMapColumn, $objModuleMap->output() );
			$arrOutput['list'][]		= sprintf( $strListColumn, $objModuleList->output() );
		}else{
			$arrOutput['map'][] = $arrOutput['list'][] = join( '',
				Array(
					'<div class="alert alert-warning text-center">',
						$clsMapName,
						esc_html__( "You must activate Javo Core Pluign (required plugin) to work properly. please activate the plugin.", 'javospot' ),
					'</div>',
				)
			);
		}
	} endif;
	do_action( 'jvfrm_spot_template_all_module_loop_after', $argsTemplate );

	die( json_encode( Array( 'list' => join( '', $arrOutput['list'] ), 'map' => join( '', $arrOutput['map'] ) ) ) );
}




/**
 *	Javo Map Get Berief Informations in Google Map InfoWindow
 *
 * @type		filter
 *	@function	jvfrm_spot_map_brief_callback
 */
add_action("wp_ajax_nopriv_jvfrm_spot_map_brief", 'jvfrm_spot_map_brief_callback');
add_action("wp_ajax_jvfrm_spot_map_brief", 'jvfrm_spot_map_brief_callback');
function jvfrm_spot_map_brief_callback(){
	global $jvfrm_spot_tso;
	$jvfrm_spot_query = new jvfrm_spot_array( $_POST );

	if( (int)$jvfrm_spot_query->get('post_id', 0) <= 0){
		die( json_encode( Array( 'html' => '' ) ) );
	}

	$post_id = $jvfrm_spot_query->get('post_id', 0);

	$jvfrm_spot_meta_query = new jvfrm_spot_GET_META( $post_id );
	$jvfrm_spot_this_author_avatar_id = get_the_author_meta('avatar');
	$jvfrm_spot_this_author_name = sprintf('%s %s', get_the_author_meta('first_name'), get_the_author_meta('last_name'));
	ob_start();?>

	<div class="row">
		<div class="col-md-12 text-center">
			<a href="<?php echo get_permalink( $post_id );?>">
				<?php echo $jvfrm_spot_meta_query->image('thumbnail', Array('class'=>'img-circle img-inner-shadow'));?>
			</a>
		</div><!-- /.col-md-4 -->
	</div><!-- /.row -->
	<div class="row">
		<div class="col-md-12 text-center">
			<a href="<?php echo get_permalink( $post_id );?>">
				<h1><?php echo $jvfrm_spot_meta_query->post_title; ?></h1>
			</a>
		</div>
	</div><!-- /.row -->
	<div class="row">
		<div class="col-md-6 text-right"></div><!-- /.col-md-6 -->
		<div class="col-md-6">
			<ul class="list-unstyled">
				<li><?php printf('%s : %s', esc_html__( 'Phone', 'javospot'), get_post_meta( $post_id, 'lv_item_phone', true ));?></li>
				<li><?php printf('%s : %s', esc_html__( 'Email', 'javospot'), get_post_meta( $post_id, 'lv_item_email', true ));?></li>
				<li><?php printf('%s : %s', esc_html__( 'Website', 'javospot'),	get_post_meta( $post_id, 'lv_item_website', true ));?></li>
			</ul>
		</div><!-- /.col-md-6 -->
	</div><!-- /.row -->
	<div class="row">
		<div class="col-md-12 text-center alert alert-light-gray">
			<?php echo $jvfrm_spot_meta_query->excerpt(400);?>
		</div><!-- /.col-md-12 -->
	</div><!-- /.row -->

	<?php
	$jvfrm_spot_bf_output = ob_get_clean();
	echo json_encode(Array(
		"html"=> $jvfrm_spot_bf_output
	));
	exit();
}