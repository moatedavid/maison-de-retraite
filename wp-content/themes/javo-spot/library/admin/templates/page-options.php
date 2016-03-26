<?php
$arrPageSettings			= Array(
	'page-option-general'	=> Array(
		'label'						=> esc_html__( "General", 'javospot' ),
		'icon'						=> 'fa fa-bookmark-o',
		'post_type'				=> Array( 'page' ),
		'active'					=> true,
	),
	'page-option-sidebar'	=> Array(
		'label'						=> esc_html__( "Sidebar", 'javospot' ),
		'icon'						=> 'fa fa-columns',
		'post_type'				=> Array( 'post', 'page' ),
	),
	'page-option-header'	=> Array(
		'label'						=> esc_html__( "Header", 'javospot' ),
		'icon'						=> 'fa fa-film',
		'post_type'				=> Array( 'page' ),
	),
	'page-option-navi'		=> Array(
		'label'						=> esc_html__( "Menu", 'javospot' ),
		'icon'						=> 'fa fa-navicon',
		'post_type'				=> Array( 'page' ),
	),
	'page-option-footer'	=> Array(
		'label'						=> esc_html__( "Footer", 'javospot' )	,
		'icon'						=> 'fa fa-file',
		'post_type'				=> Array( 'post', 'page' ),
	)
);
$arrPageSettings				= apply_filters(
	'jvfrm_spot_admin_page_options'
	, $arrPageSettings
);
$arrPageSettingNav			= $arrPageSettingContents = Array();
$hasActiveNav					= false;

if( !empty( $arrPageSettings ) ) : foreach( $arrPageSettings as $option => $optionMeta ) {

	if( !in_Array( get_post_type( get_the_ID() ), $optionMeta[ 'post_type' ] ) )
		continue;

	$hasActiveNav						= isset( $optionMeta[ 'active' ] ) || $hasActiveNav;
	$arrPageSettingNav[]			= Array(
		'ID'				=> $option
		, 'label'			=> $optionMeta[ 'label' ]
		, 'icon'			=> $optionMeta[ 'icon' ]
		, 'active'			=> isset( $optionMeta[ 'active' ] )
		, 'require'		=> isset( $optionMeta[ 'require' ] )? $optionMeta[ 'require' ] : false

	);
	$arrPageSettingContents[]	= Array(
		'ID'				=> $option
		, 'label'			=> $optionMeta[ 'label' ]
		, 'file'				=> apply_filters(
			'jvfrm_spot_admin_page_options_template'
			, self::$instance->template_part . '/' . $option . '.php'
			, $option
		)
		, 'active'		=> isset( $optionMeta[ 'active' ] )
	);
} endif;

if( !$hasActiveNav )
{
	$keyFirstNav	= Array_Keys( $arrPageSettingNav );
	$keyFirstNav	= isset( $keyFirstNav[0] ) ? $keyFirstNav[0] : false;
	if( $keyFirstNav  !== false) {
		if( isset( $arrPageSettingNav[ $keyFirstNav ] ) )
			$arrPageSettingNav[ $keyFirstNav ][ 'active' ]			= true;
		if( isset( $arrPageSettingContents[ $keyFirstNav ] ) )
			$arrPageSettingContents[ $keyFirstNav ][ 'active' ]	= true;
	}
}

if( false === ( $jvfrm_spot_header_option = get_post_meta( get_the_ID(), 'jvfrm_spot_hd_post', true ) ) )
	$jvfrm_spot_header_option = Array();
$jvfrm_spot_query					= new jvfrm_spot_array( $jvfrm_spot_header_option );
$jvfrm_spot_options				= Array(
	'header_type'			=> apply_filters(
			'jvfrm_spot_options_header_types'
			, Array(
				esc_html__( "Default as theme settings", 'javospot' )	=> '',
				esc_html__( "Disable header", 'javospot' )	=> 'disable-header',

			)
	)
	, 'page_layout' => Array(
		esc_html__( "Default as theme settings", 'javospot' )	=> ''
		, esc_html__( "Wide (width : 1170px)", 'javospot' )		=> 'wide'
		, esc_html__( "Wide (Width : 1400px)", 'javospot' )		=> 'wide-1400'
		, esc_html__( "Boxed (Width : 1170px)", 'javospot' )	=> 'active'
		, esc_html__( "Boxed (Width : 1400px)", 'javospot' )	=> 'active-1400'
	)
	, 'footer_layout' => Array(
		esc_html__( "Default as theme settings", 'javospot' )	=> ''
		, esc_html__( "Wide", 'javospot' )						=> 'wide'
		, esc_html__( "Boxed", 'javospot' )						=> 'active'
		, esc_html__( "Disable footer", 'javospot' )			=> 'disable-footer'
	)
	, 'header_skin' => Array(
		esc_html__( "Default as theme settings", 'javospot' )	=> ""
		, esc_html__( "Light", 'javospot' )						=> "light"
		, esc_html__( "Dark", 'javospot' )						=> "dark"
	)
	, 'able_disable' => Array(
		esc_html__( "Default as theme settings", 'javospot' )	=> ""
		, esc_html__( "Able", 'javospot' )						=> "enable"
		, esc_html__( "Disable", 'javospot' )					=> "disabled"
	)
	, 'default_able' => Array(
		esc_html__( "Default as theme settings", 'javospot' )	=> ""
		, esc_html__( "Custom setting for this page", 'javospot' )	=> "enable"
	)
	, 'header_fullwidth' => Array(
		esc_html__("Default as theme settings", 'javospot')		=> ""
		, esc_html__("Center", 'javospot')						=> "fixed"
		, esc_html__("Wide", 'javospot')						=> "full"
	)
	, 'header_relation' => Array(
		esc_html__("Default as theme settings", 'javospot')		=> ""
		, esc_html__("Default menu", 'javospot')				=> "relative"
		, esc_html__("Transparency menu", 'javospot')			=> "absolute"
	)
);
?>
<div class="jv-page-settings-wrap">

	<ul class="jv-page-settings-nav">
		<?php
		if( !empty( $arrPageSettingNav ) ) : foreach( $arrPageSettingNav as $nav ) :
			$isActive				= (boolean) $nav[ 'active' ];
			$activeClass			= $isActive ? ' active' : null;
			$reqTemplate		= '';
			if( $templateName = $nav[ 'require' ] ){
				$activeClass		.= ' hidden require-template';
				$reqTemplate	= " data-require='{$templateName}'";
			}
			echo "<li class=\"jv-page-settings-nav-item {$nav[ 'ID' ]}{$activeClass}\"{$reqTemplate}" . ' ';
			echo "data-content=\".{$nav[ 'ID' ]}\"><i class=\"{$nav[ 'icon' ]}\"></i> {$nav[ 'label' ]}";
		endforeach; else:
			echo "<li></li>";
		endif ?>
	</ul>

	<div class="jv-page-settings-contents">
		<?php
		if( !empty( $arrPageSettingContents ) ) : foreach( $arrPageSettingContents as $content ) :
			$isActive		= (boolean) $content[ 'active' ];
			$activeClass	= $isActive ? ' active' : null;
			echo "<div class=\"jv-page-settings-content {$content[ 'ID' ]}{$activeClass}\">";
			printf( "<h3 class=\"jv-page-settings-content-label\">{$content[ 'label' ]} %s</h3>", esc_html__( "Settings", 'javospot' ) );
				if( file_exists( $content[ 'file' ] ) )
					require_once $content[ 'file' ];
			echo "</div>";
		endforeach; else:
			printf( "<h3 class='jv-page-settings-content-label'>%s</h3>", esc_html__( "Not found settings.", 'javospot' ) );
		endif; ?>
	</div>
</div>