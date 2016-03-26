<?php
function jvfrm_spot_getUserPage( $user_id, $slug='', $closechar='/' ) {

	$arrDashboard	= Array();

	$arrDashboard[]	= defined( 'JVFRM_SPOT_DEF_LANG' ) ?
		JVFRM_SPOT_DEF_LANG . JVFRM_SPOT_MEMBER_SLUG :
		JVFRM_SPOT_MEMBER_SLUG;

	$arrDashboard[]	= get_user_by( 'id', intVal( $user_id ) )->user_login;

	if( !empty( $slug ) )
		$arrDashboard[] = strtolower( $slug );

	$strDashboard	= @implode( '/', $arrDashboard );

	return esc_url( home_url( $strDashboard . $closechar ) );
}
function jvfrm_spot_getCurrentUserPage( $slug='', $closechar='/' ){
	return jvfrm_spot_getUserPage( get_current_user_id(), $slug, $closechar );
}
function jvfrm_spot_getMypageSidebar() {
	$arrMyMenus		= apply_filters(
		'jvfrm_spot_mypage_sidebar_args'
		, Array(
			Array(
				'li_class'		=> 'side-menu home'
				, 'url'			=> jvfrm_spot_getCurrentUserPage()
				, 'icon'		=> 'fa fa-tachometer'
				, 'label'		=> esc_html__("DASHBOARD", 'javospot')
			)
			, Array(
				'li_class'		=> 'side-menu home'
				, 'url'			=> jvfrm_spot_getCurrentUserPage( JVFRM_SPOT_PROFILE_SLUG )
				, 'icon'		=> 'fa fa-tachometer'
				, 'label'		=> esc_html__("Edit Profile", 'javospot')
			)
		)
	);

	echo "<ul class=\"nav nav-sidebar\">";

	if( !empty( $arrMyMenus ) ) foreach( $arrMyMenus as $menuMeta )
		echo "
			<li class=\"{$menuMeta['li_class']}\">
				<a href=\"{$menuMeta['url']}\" class=\"admin-color-setting\">
					<i class=\"{$menuMeta['icon']}\"></i> &nbsp;
					{$menuMeta['label']}
				</a>
			</li>";
	echo "</ul>";
}

if( !function_exists( 'jvfrm_spot_dashboard_msg' ) ) : function jvfrm_spot_dashboard_msg(){
	global $jvfrm_spot_change_message;
	return $jvfrm_spot_change_message;
} endif;