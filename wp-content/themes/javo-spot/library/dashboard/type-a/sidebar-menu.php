<?php
/**
 *	Userm mypage sidebar menus
 *
 */
?>

<div class="col-xs-12 col-sm-2 sidebar-offcanvas main-content-left my-page-nav" id="sidebar">
	<div class="well sidebar-nav mypage-left-menu">
		<ul class="nav nav-sidebar">
			<li class="side-menu"><a><?php echo get_user_by( 'login', get_query_var( 'user' ) )->display_name; ?>	</a></li>
		</ul>
		<?php jvfrm_spot_getMypageSidebar() ; ?>
	</div><!--/.well -->
</div><!--/col-xs-->