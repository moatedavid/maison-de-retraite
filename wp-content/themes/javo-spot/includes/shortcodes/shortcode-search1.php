<?php
class jvfrm_spot_search1
{

	public $loaded = false;

	private $sID;

	private $is_mobile = false;

	private $numCols = 1;

	public function __construct(){

		// Shortcode Resgistered
		add_filter( 'jvfrm_spot_other_shortcode_array', Array( $this, 'register_shortcode' ) );  // Avoid themecheck : not allowed to create shortcodes in theme. plugin only.
		add_action( 'vc_before_init', Array( $this, 'register_shortcode_with_vc' ), 11 );

		// Shortcode Enqueues & Scripts
		add_action( 'wp_footer', Array( $this, 'scripts' ) );

		// Coulmn Actions
		add_action( 'jvfrm_spot_search1_element_keyword'			, Array( $this, 'keyword' ) );
		add_action( 'jvfrm_spot_search1_element_google_search'		, Array( $this, 'google_search' ) );
		add_action( 'jvfrm_spot_search1_element_listing_category'		, Array( $this, 'listing_category' ) );
		add_action( 'jvfrm_spot_search1_element_listing_location'		, Array( $this, 'listing_location' ) );
		add_action( 'jvfrm_spot_search1_element_listing_amenities'		, Array( $this, 'amenities' ), 10, 2);
	}

	public function register_shortcode( $shortcode )
	{
		return wp_parse_args(
			Array(
				'jvfrm_spot_search1' => Array( $this, 'config' )
			)
			, $shortcode
		);
	}

	/*
	Shortcode parameter : fieldParameter for VC.
	 */
	public static function fieldParameter( $param_name, $label=false ){
		return Array(
			'type'					=> 'dropdown'
			, 'heading'			=> $label
			, 'holder'			=> 'div'
			, 'group'				=> esc_html__( "Fields", 'javospot' )
			, 'class'				=> ''
			, 'param_name'	=> $param_name
			, 'value'				=> Array(
				esc_html__( "None", 'javospot' )						=> ''
				, esc_html__( "Mots clés", 'javospot' )				=> 'keyword'
				, esc_html__( "Google Search", 'javospot' )	=> 'google_search'
				, esc_html__( "Catégories", 'javospot' )				=> 'listing_category'
				, esc_html__( "Villes", 'javospot' )				=> 'listing_location'
			)
		);
	}

	/*
	Register shortcode define details for VC
	 */
	public function register_shortcode_with_vc()
	{

		if( !function_exists( 'vc_map' ) )
			return;

		$strGroupFilter		= esc_html__( "Filtre", 'javospot' );
		$arrColumn			= Array();
		for( $intCount = 1; $intCount <= 3; $intCount ++ )
			$arrColumn[ $intCount . ' ' . _n( "Column", "Columns", $intCount, 'javospot' ) ] = $intCount;

		vc_map(
			Array(
				'base'						=> 'jvfrm_spot_search1'
				, 'name'						=> esc_html__( "Search 1", 'javospot')
				, 'icon'						=> 'jv-vc-shortcode-icon shortcode-search1'
				, 'category'				=> esc_html__('Javo', 'javospot')
				, 'params'					=> Array(

				/**
				 *	@group : general
				 */
					 Array(
						'type'			=> 'textfield'
						, 'heading'		=> esc_html__( "Title", 'javospot' )
						, 'holder'		=> 'div'
						, 'class'		=> ''
						, 'param_name'	=> 'title'
						, 'value'		=> ''
					)

					, Array(
						'type'			=> 'dropdown'
						, 'heading'		=> esc_html__('Please select search result page', 'javospot')
						, 'holder'		=> 'div'
						, 'class'		=> ''
						, 'param_name'	=> 'query_requester'
						, 'value'		=> apply_filters(
							'jvfrm_spot_get_map_templates'
							, Array( esc_html__("Default Search Page", 'javospot') => '' ) )
					)
				/**
				 *	@group : Style
				 */
					, Array(
						'type'			=> 'colorpicker'
						, 'group'		=> esc_html__( "Style", 'javospot' )
						, 'heading'		=> esc_html__( "Button Background Color", 'javospot')
						, 'holder'		=> 'div'
						, 'class'		=> ''
						, 'param_name'	=> 'button_bg_color'
						, 'value'		=> ''
					)

					, Array(
						'type'			=> 'colorpicker'
						, 'group'		=> esc_html__( "Style", 'javospot' )
						, 'heading'		=> esc_html__( "Button Text Color", 'javospot')
						, 'holder'		=> 'div'
						, 'class'		=> ''
						, 'param_name'	=> 'button_text_color'
						, 'value'		=> ''
					)

				 /*
					, Array(
						'type'					=> 'textfield'
						, 'group'				=> esc_html__( "Style", 'javospot' )
						, 'heading'			=> esc_html__( "Border Weight", 'javospot')
						, 'holder'			=> 'div'
						, 'class'				=> ''
						, 'description'	=> esc_html__( "Pixel", 'javospot' )
						, 'param_name'	=> 'border_width'
						, 'value'				=> '0'
					)

					, Array(
						'type'				=> 'colorpicker'
						, 'group'			=> esc_html__( "Style", 'javospot' )
						, 'heading'			=> esc_html__( "Border Color", 'javospot')
						, 'holder'			=> 'div'
						, 'class'				=> ''
						, 'param_name'	=> 'border_color'
						, 'value'				=> '#000000'
					)
				*/
				/**
				 *	@group : Fields
				 */
					,
					Array(
						'type'					=> 'dropdown'
						, 'group'				=> esc_html__( "Fields", 'javospot' )
						, 'heading'			=> esc_html__( "Columns", 'javospot')
						, 'holder'			=> 'div'
						, 'class'				=> ''
						, 'param_name'	=> 'columns'
						, 'value'				=> $arrColumn
					),

					wp_parse_args(
						Array(
							'dependency'	=> Array(
								'element'	=> 'columns',
								'value'		=> Array( '1', '2', '3' )
							)
						)
						, self::fieldParameter( 'column1', esc_html__( "1 column", 'javospot'  ) )
					),

					wp_parse_args(
						Array(
							'dependency'	=> Array(
								'element'	=> 'columns',
								'value'		=> Array( '2', '3' )
							)
						)
						, self::fieldParameter( 'column2', esc_html__( "2 column", 'javospot'  ) )
					),

					wp_parse_args(
						Array(
							'dependency'	=> Array(
								'element'	=> 'columns',
								'value'		=> Array(  '3' )
							)
						)
						, self::fieldParameter( 'column3', esc_html__( "3 column", 'javospot'  ) )
					),

					Array(
						'type' => 'dropdown'
						, 'group'				=> esc_html__( "Fields", 'javospot' )
						, 'heading'			=> esc_html__( "Columns", 'javospot')
						, 'holder'			=> 'div'
						, 'class'				=> ''
						, 'param_name'	=> 'columns'
						, 'value'				=> $arrColumn
					)
				)
			)
		);
	}

	/*
	Shortcode call back
	 */
	public function config( $request_attr, $content="")
	{
		$attr = shortcode_atts(
			Array(
				'title'				=> false
				, 'query_requester'	=> 0
				, 'border_color'	=> '#000000'
				, 'border_width'	=> 1
				, 'button_bg_color' => ''
				, 'button_text_color'	=> ''
				, 'columns'			=> 1
				, 'column1'			=> ''
				, 'column2'			=> ''
				, 'column3'			=> ''
				, 'mobile'			=> false
			)
			, $request_attr
		);
		$this->loaded		= true;
		$this->sID			= 'jvfrm_spot_scd' . md5( wp_rand( 0 , 500 ) .time() );
		$this->is_mobile	= (boolean) $attr[ 'mobile' ];
		$this->numCols	= intVal( $attr[ 'columns' ] );
		return $this->output( $attr );
	}

	/*
	Search shortcode container class
	 */
	public function classes() {
		$arrOutput				= Array(
			'javo-shortcode',
			'shortcode-' . get_class( $this ),
			'column-' . $this->numCols,
			'active',
		);
		$arrOutput				= Array_Map( 'trim', $arrOutput );
		if( $this->is_mobile )
			$arrOutput[]		= 'is-mobile';
		$strOutput				= join( ' ', $arrOutput );
		printf( " class=\"%s\" ", $strOutput );
	}

	/*
	Keyword output
	 */

	public function keyword() {
		printf( "<input type=\"text\" name=\"%s\" placeholder=\"%s\" class=\"form-control\">"
			, 'keyword'
			, esc_html__( "Mots clés", 'javospot' )
		);
	}

	/*
	Google address search output
	 */
	public function google_search() {
		printf( "
			<div class=\"javo-search-form-geoloc\" style=\"width: 300px;\">
				<input type=\"text\" name=\"%s\" class=\"form-control\">
				<i class=\"fa fa-map-marker javo-geoloc-trigger\"></i>
			</div>"
			, 'radius_key'
		);
	}

	/*
	Listing category output
	 */
	public function listing_category() {
		printf( "<select name=\"%s\" data-selectize style=\"width: 300px;\">
			<option value=''>%s</option>%s</select>"
			, 'category'
			,  esc_html__( "Choisir un type d'établissement", 'javospot' )
			, apply_filters( 'jvfrm_spot_get_selbox_child_term_lists', 'listing_category', null, 'select', false, 0, 0, '-' )
		);
	}

	/*
	Listing location output
	 */
	public function listing_location() {
		printf( "<select name=\"%s\" data-selectize style=\"width: 300px;\">
			<option value=''>%s</option>%s</select>"
			, 'location'
			,  esc_html__( "Choisir une ville", 'javospot' )
			, apply_filters( 'jvfrm_spot_get_selbox_child_term_lists', 'listing_location', null, 'select', false, 0, 0, '-' )
		);
	}

	/*
	Amenities output
	 */
	public static function amenities( $attr=Array(), $columns=3 )
	{
		$strColumns = 'col-md-' . intVal( 12 / $columns );

		if(  $arrAmenities	= get_terms( 'listing_amenities', Array( 'fields' => 'id=>name' ) ) ){
			echo "<div class=\"row search-box-block\">";
			foreach( $arrAmenities as $id => $name )
			{
				printf( "
					<div class=\"%1\$s\">
						<label>
							<input type=\"checkbox\" name=\"%2\$s[]\" value=\"{$id}\">
							{$name}
						</label>
					</div>",
					$strColumns,
					'amenity'
				);
			}
			echo "</div>";
		}else{
			esc_html_e( "Aucun équipements trouvés", 'javospot' );
		}
	}

	/*
	Title for search shortcode
	 */
	public static function getHeader( $param )
	{
		if( empty( $param[ 'title' ] ) )
			return;

		$strHeader	= $param[ 'title' ];
		?>

		<div class="row jv-search1-header-row">
			<div class="col-xs-12">
				<div class="static-label admin-color-setting">
					<?php echo $strHeader; ?>
				</div>
			</div>
		</div>
		<?php
	}

	/*
	Shortcode output
	 */
	public function output( $attr )
	{
		global $jvfrm_spot_filter_prices, $jvfrm_spot_tso;
		/*
		$intBorderWidth	= intVal( $attr[ 'border_width' ] );
		$strBorderCSS		= '';
		if( $intBorderWidth > 0 ) {
			$strBorderCSS	= " style=\"border:solid {$intBorderWidth}px {$attr[ 'border_color' ]};\" ";
		}
		*/
		$strRequester	= apply_filters( 'jvfrm_spot_wpml_link', intVal( $attr[ 'query_requester' ] ) );

		if( !class_exists( 'Lava_Directory_Manager' ) )
			return sprintf(
				'<p align="center">%s</p>',
				esc_html__( "Please, active to the 'Lava Directory manager' plugin", 'javospot' )
			);

		$arrButtonStyles	= Array();
		$arrButtonClass		= Array(
			'btn',
			'btn-block',
			'jv-submit-button'
		);

		$strButtonStyles = $strButtonClass = '';

		if( $attr[ 'button_bg_color' ] != '' )
			$arrButtonStyles[ 'background-color' ] = $attr[ 'button_bg_color' ];

		if( $attr[ 'button_text_color' ] != '' )
			$arrButtonStyles[ 'color' ] = $attr[ 'button_text_color' ];

		if( empty( $arrButtonStyles ) )
			$arrButtonClass[] = 'admin-color-setting';

		$strButtonClass = join( ' ', $arrButtonClass );
		if( !empty( $arrButtonStyles ) ) : foreach( $arrButtonStyles as $strProperty => $strValue ){
			$strButtonStyles .= "{$strProperty}:{$strValue};";
		} endif;


		ob_start();?>
		<div id="<?php echo sanitize_html_class( $this->sID ); ?>" <?php $this->classes(); ?>>
			<?php self::getHeader( $attr ); ?>

			<div class="search-type-a-wrap">
				<form method="get" action="<?php echo esc_attr( $strRequester );?>" class="search-type-a-form">

					<div class="jv-search1-search-fields search-type-a-inner">
						<div class="row jv-search1-top-row">
							<?php
							for( $intCount=1; $intCount <= intVal( $attr[ 'columns' ] ); $intCount++ ){
								if( !empty( $attr[ 'column1' ] ) ) {
                                                                        echo "<div class=\"search-box-inline\" style=\"font-weight:600;\">";									
									do_action( 'jvfrm_spot_search1_element_' . $attr[ 'column' . $intCount ], $attr );
									echo "</div>";
								}
							}
							if( $this->is_mobile ) {
								printf(
									'<button type="button" class="btn btn-sm %1$s">
										<span class="more-filters">
											<i class="glyphicon glyphicon-chevron-down"></i> %2$s
										</span>
										<span class="less-filters">
											<i class="glyphicon glyphicon-chevron-up"></i> %2$s
										</span>
									</button>',
									'jv-search1-morefilter-opener',
									__( "Equipements", 'javospot' )
								);

								echo '<div class="jv-search1-morefilter-row" style="display:none;">
										<div class="col-md-8">';

								do_action( 'jvfrm_spot_search1_element_listing_amenities', $attr );
								echo '</div><!-- /.col-md-8 //--></div><!-- /.row //-->';
							} ?>
							<div class="search-box-inline">
								<button type="submit" class="<?php echo $strButtonClass; ?>" style="width: 300px; <?php echo $strButtonStyles; ?>"><?php esc_html_e( "Search", 'javospot');?></button>
							</div>
						</div> <!-- rows -->
						<!-- div class="bottom-amenities">
							<div class="bottom-amenities-content">
								<?php //do_action( 'jvfrm_spot_search1_element_listing_amenities', $attr ); ?>
								<div class="bottom-amenities-opener">
									<div class="bottom-amenities-opener-button">
										<span class="icon">Liste des prestations et des environnements &nbsp;</span>
									</div>
								</div>
							</div>
						</div -->
					</div> <!-- jv-search1-search-fields -->
				</form>
			</div>
		</div>
	<?php
		$arrOutput		= Array();
		$arrOutput[]	= "<script type=\"text/javascript\">";
		$arrOutput[]	= "jQuery( function($){ $.jvfrm_spot_search_shortcode( '#{$this->sID}' ); });";
		$arrOutput[]	= "</script>";
		echo join( '', $arrOutput );
		return ob_get_clean();
	}

	/*
	Enqueue script
	 */
	public function scripts() {
		if( !$this->loaded )
			return;
		wp_enqueue_script( 'jQuery-nouiSlider' );
		wp_enqueue_script( 'selectize-script' );
		wp_enqueue_script( sanitize_title( 'jv-jquery.javo_search_shortcode.js' ) );
	}
}

/*
Instance for shorcode class
 */

if( !function_exists( 'jvfrm_spot_search1_init') ){
	function jvfrm_spot_search1_init() {
		$GLOBALS[ 'jvfrm_spot_search1' ] = new jvfrm_spot_search1;
	}
	add_action( 'after_setup_theme', 'jvfrm_spot_search1_init' );
}