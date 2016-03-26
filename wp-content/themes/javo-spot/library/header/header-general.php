<?php
global
	$post,
	$jvfrm_spot_tso,
	$jvfrm_spot_headerParams,
	$jvfrm_spot_tso_db;

/* Logo */{

	$post					= get_post();

	if( empty( $post ) ) {
		$post				= new stdClass();
		$post->ID			= 0;
		$post->post_type	= '';

	}

	$post_id				= $post->ID;

	// Default JavoThemes Logo
	$jvfrm_spot_nav_logo = JVFRM_SPOT_IMG_DIR.'/jv-logo2.png';
	$jvfrm_spot_nav_logo_default_light = JVFRM_SPOT_IMG_DIR.'/jv-logo1.png';
	$jvfrm_spot_nav_logo_base = $jvfrm_spot_nav_logo;
	$jvfrm_spot_defulat_theme_logo = $jvfrm_spot_nav_logo_base;
	$jvfrm_spot_nav_logo_sticky	= $jvfrm_spot_nav_logo;
	$jvfrm_spot_nav_logo_single	= $jvfrm_spot_tso->get( 'single_item_logo', false ) ;

	// ThemeSettings Logo
	$jvfrm_spot_hd_options = get_post_meta( $post_id, 'jvfrm_spot_hd_post', true );
	$jvfrm_spot_post_skin = new jvfrm_spot_array( $jvfrm_spot_hd_options );

	// Dark Logo ( User Default upload logo )
	if(  false === ( $jvfrm_spot_nav_logo_dark = $jvfrm_spot_tso->get( 'logo_url', false ) ) )
	{
		$jvfrm_spot_nav_logo_dark = $jvfrm_spot_nav_logo;
	}

	// Light Logo
	if(  false === ( $jvfrm_spot_nav_logo_light = $jvfrm_spot_tso->get( 'logo_light_url', false ) ) || '' == $jvfrm_spot_nav_logo_light  )
	{
		$jvfrm_spot_nav_logo_light = $jvfrm_spot_nav_logo_default_light;
	}

	// Setup Default Logo
	switch( $jvfrm_spot_post_skin->get("header_skin", jvfrm_spot_tso()->header->get( 'header_skin', false ) ) )
	{
		case "light":	$jvfrm_spot_nav_logo_base		= $jvfrm_spot_nav_logo_light; break;
		case "dark":
		default:		$jvfrm_spot_nav_logo_base		= $jvfrm_spot_nav_logo_dark;
	}


	/* Single Post Fixed */
	if( $post->post_type === 'post' && is_single($post))
		$jvfrm_spot_nav_logo_base = $jvfrm_spot_nav_logo_light;

	// Setup Sticky Default Logo
	switch( $jvfrm_spot_post_skin->get("sticky_header_skin", jvfrm_spot_tso()->header->get( 'sticky_header_skin', false ) ) )
	{
		case "light":	$jvfrm_spot_nav_logo_sticky	= $jvfrm_spot_nav_logo_light; break;
		case "dark":
		default:		$jvfrm_spot_nav_logo_sticky	= $jvfrm_spot_nav_logo_dark;
	}
}


/* System Menu */{

	$jvfrm_spot_nav_sys_buttons = Array();

	/* Logged Out */
	$jvfrm_spot_nav_sys_buttons['logged_out']	= Array(
		'url'		=> esc_url( wp_logout_url( home_url( '/' ) ) )
		, 'label'	=> esc_html__("Logout", 'javospot')
	);
}



/* Quick Menu */ {

	$is_login				= is_user_logged_in();
	$jvfrm_spot_this_user			= new WP_User( get_current_user_id() );
	$jvfrm_spot_this_user_avatar_id	= $jvfrm_spot_this_user->avatar;
	$jvfrm_spot_this_user_avatar	= $jvfrm_spot_tso->get('no_image', JVFRM_SPOT_IMG_DIR.'/no-image.png');

	if( (boolean) $_META	= wp_get_attachment_image_src( $jvfrm_spot_this_user_avatar_id, 'jvfrm-spot-tiny') ) {
		$jvfrm_spot_this_user_avatar	= $_META[0];
	}

	/* List Button */{
		ob_start();
		if( ! empty( $jvfrm_spot_nav_sys_buttons ) ) :
			?>
			<ul class="dropdown-menu" role="menu">
				<?php
				foreach( $jvfrm_spot_nav_sys_buttons as $button ) {
					echo "<li><a href=\"{$button['url']}\">{$button['label']}</a></li>";
				} ?>
			</ul>
			<?php
		endif;
		$jvfrm_spot_featured_list_append = ob_get_clean();
	}

	/* List Button */{
		ob_start();
		echo "<ul class=\"widget_top_menu_wrap hidden-xs\">";
		if( function_exists( 'dynamic_sidebar' ) )
			dynamic_sidebar('menu-widget-1');

			?>
			<!-- <li><?php do_action( 'jvfrm_spot_wpml_switcher' ); ?></li> -->
			<?php
		echo "</ul>";
		$jvfrm_spot_menu_widget_append = ob_get_clean();
	}

	$jvfrm_spot_featured_menus	= Array(
		'menu_widget'		=> Array(
			'enable'		=> true
			, 'li_class'	=> 'dropdown right-menus javo-navi-mylist-button'
			, 'append'		=> $jvfrm_spot_menu_widget_append
		)
	);
	$jvfrm_spot_featured_menus	= apply_filters( 'jvfrm_spot_featured_menus_filter', $jvfrm_spot_featured_menus );
} ?>

<header id="header-one-line" <?php echo $jvfrm_spot_headerParams[ 'classes' ]; ?>>

<?php if( jvfrm_spot_header()->getTopbarAllow() && false ){ ?>
<div class="javo-topbar" style="background:<?php echo $jvfrm_spot_tso->get('topbar_bg_color');?>; color:<?php echo $jvfrm_spot_tso->get('topbar_text_color'); ?>">
	<div class="container">
		<div class="pull-left javo-topbar-left">
			<div class="topbar-wpml">
				<?php if($jvfrm_spot_tso->get('topbar_wpml_hidden')!='disabled') do_action('icl_language_selector'); ?>
			</div><!-- topbar-wpml -->
			<ul class="topbar-quickmenu">
				<li class="">
					<a href="javascript:" data-toggle="modal" data-target="#login_panel">
						<?php esc_html_e( "Submit", 'javospot' );?>
					</a>
				</li>
				<li class="visible-logged">
					<a href="<?php echo jvfrm_spot_getCurrentUserPage( 'add-lv_listing' ); ?>">
						<?php esc_html_e( "Submit", 'javospot' );?>
					</a>
				</li>
				<li class="">
					<a href="javascript:" data-toggle="modal" data-target="#login_panel">
						<?php esc_html_e( "Login", 'javospot' );?>
					</a>
				</li>
				<li class="visible-logged">
					<a href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>">
						<?php esc_html_e( "Logout", 'javospot' );?>
					</a>
				</li>
				<?php if( get_option( 'users_can_register' ) ) : ?>
				<li class="">
					<a href="javascript:" data-toggle="modal" data-target="#register_panel">
						<?php esc_html_e( "Sign up", 'javospot' );?>
					</a>
				</li>
				<?php endif; ?>
				<li class="visible-all">
					<a href="http://javothemes.com/spot/demo4/contact-us-1/">
						<?php esc_html_e('Contact','javospot'); ?>
					</a>
				</li>
				<li class="visible-all">
					<a href="http://javothemes.com/spot/demo4/shop/">
						<?php esc_html_e('Buy Now','javospot'); ?>
					</a>
				</li>
			</ul><!-- topbar-quickmenu -->
		</div>
		<div class="pull-right javo-topbar-right">
			<a href="#"><i class="fa fa-facebook"></i></a>
			<a href="#"><i class="fa fa-twitter"></i></a>
			<a href="#"><i class="fa fa-google-plus"></i></a>
			<a href="#"><i class="fa fa-instagram"></i></a>
			<a href="#"><i class="fa fa-pinterest"></i></a>
			<a href="#"><i class="fa fa-flickr"></i></a>

		</div><!-- javo-topbar-right -->
	</div><!-- container-->
</div><!-- javo-topbar -->
<?php } ?>

	<nav class="navbar javo-main-navbar javo-navi-bright<?php echo is_singular( jvfrm_spot_core()->slug ) ? false : ' affix-top'; ?>">
		<div class="container">
			<div class="container-fluid">
				<div class="row">
					<!-- Brand and toggle get grouped for better mobile display -->
					<div class="navbar-header">
						<div class="pull-left visible-xs col-xs-2">
							<button type="button" class="navbar-toggle javo-mobile-left-menu" data-toggle="collapse" data-target="#javo-navibar">
								<i class="fa fa-bars"></i>
							</button>
						</div><!--"navbar-header-left-wrap-->
						<div class="pull-right visible-xs col-xs-3">
							<button type="button" class="btn javo-in-mobile <?php echo sanitize_html_class( $jvfrm_spot_tso->get( 'btn_header_right_menu_trigger' ) );?>" data-toggle="offcanvas" data-recalc="false" data-target=".navmenu" data-canvas=".canvas">
								<i class="fa fa-bars"></i>
							</button>
						</div>
						<div class="navbar-brand-wrap col-xs-7 col-sm-3" >
							<div class="navbar-brand-inner">
								<a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) );?>" data-origin="<?php echo esc_attr( $jvfrm_spot_headerParams['height'] );?>" style="height:<?php echo esc_attr(  $jvfrm_spot_headerParams['height'] );?>;line-height:<?php echo esc_attr(  $jvfrm_spot_headerParams['height'] );?>;">
									<?php

									$jvfrm_spot_mobile_logo = $jvfrm_spot_tso->get( 'mobile_logo_url', '' );
									if( $jvfrm_spot_mobile_logo != '' )
									{
										$jvfrm_spot_mobile_logo = " data-javo-mobile-src=\"{$jvfrm_spot_mobile_logo}\"";
									}

									if( $jvfrm_spot_nav_logo_single && is_single() && $post->post_type=='lv_listing' ) {
										printf( "<img src=\"{$jvfrm_spot_nav_logo_single}\" data-javo-sticky-src=\"{$jvfrm_spot_nav_logo_single}\" id=\"javo-header-logo\"{$jvfrm_spot_mobile_logo} alt='%s'>", get_bloginfo('name'));
									}else{
										if($jvfrm_spot_nav_logo_base!=''){
											// setting logos
											printf( "<img src=\"{$jvfrm_spot_nav_logo_base}\" data-javo-sticky-src=\"{$jvfrm_spot_nav_logo_sticky}\" id=\"javo-header-logo\"{$jvfrm_spot_mobile_logo} alt='%s'>", get_bloginfo('name'));
										}else{
											printf( "<img src=\"{$jvfrm_spot_defulat_theme_logo}\" data-javo-sticky-src=\"{$jvfrm_spot_nav_logo_sticky}\" id=\"javo-header-logo\"{$jvfrm_spot_mobile_logo} alt='%s'>", get_bloginfo('name'));
										}
									} ?>
								</a>
								<?php do_action( 'jvfrm_spot_header_inner_logo_after'); ?>
							</div><!--navbar-brand-inner-->
						</div><!--navbar-brand-wrap-->
						<div class="hidden-xs col-sm-9 jv-contact-nav-widget" style="height:<?php echo esc_attr( $jvfrm_spot_headerParams['height'] );?>;">
							<div class="javo-toolbars-wrap">
								<?php do_action( 'jvfrm_spot_header_toolbars_body'); ?>
							</div><!-- /.container -->
						</div>
						<?php do_action( 'jvfrm_spot_header_brand_wrap_after'); ?>
					</div>

					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse" id="javo-navibar">
						<?php
						wp_nav_menu( array(
							'menu_class'		=> jvfrm_spot_header()->get_classes( 'nav navbar-nav navbar-left jv-nav-ul' ),
							'theme_location'	=> 'primary',
							'depth'				=> 4,
							'container'			=> false,
							'fallback_cb'		=> 'wp_bootstrap_navwalker::fallback',
							'walker'			=> new wp_bootstrap_navwalker()
						)); ?>

						<?php do_action( 'jvfrm_spot_header_logo_after' ); ?>

						<ul class="nav navbar-nav navbar-right hidden-xs" id="javo-header-featured-menu">
							<?php
							foreach( $jvfrm_spot_featured_menus as $key => $option )
							{
								if( $option['enable'] )
								{
									echo "\n<li class=\"{$option['li_class']}\">";

									if( isset( $option['a_inner'] ) )
									{
										echo "\n\t<a href=\"{$option['url']}\"" . ' ';
										echo "class=\"{$option['a_class']}\"" . ' ';
										echo isset( $option['a_title'] ) ? "title=\"{$option[ 'a_title' ]}\" " : '';
										echo isset( $option['a_attribute'] ) ? $option[ 'a_attribute' ] : '';
										echo ">\n\t\t";
											echo $option['a_inner'];
										echo "\n\t</a>\n";
									}

									if( isset( $option[ 'append' ] ) )
										echo $option[ 'append' ];

									echo "\n</li>";
								}
							} ?>
						</ul>

						<div class="navbar-mobile">
							<ul class="navbar-modile-nav">
								<li class="nav-item">
									<?php if( ! is_user_logged_in() ) : ?>
										<a href="javascript:" data-toggle="modal" data-target="#login_panel">
											<?php esc_html_e( "Login", 'javospot' );?>
										</a>
									<?php else: ?>
										<a href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>">
											<?php esc_html_e( "Logout", 'javospot' );?>
										</a>
									<?php endif; ?>
								</i>
								<li class="nav-item">
									<?php if( ! is_user_logged_in() ) : ?>
										<a href="javascript:" data-toggle="modal" data-target="#login_panel">
											<?php esc_html_e( "Submit", 'javospot' );?>
										</a>
									<?php else: ?>
										<a href="<?php echo jvfrm_spot_getCurrentUserPage( 'add-lv_listing' ); ?>">
											<?php esc_html_e( "Submit", 'javospot' );?>
										</a>
									<?php endif; ?>
									</a>
								</i>
							</ul>
						</div>

					</div><!-- /.navbar-collapse -->
				</div><!--/.row-->
			</div><!-- /.container-fluid -->
		</div>
	</nav>
</header>