<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class JV_CanvasMenu_Button extends WP_Widget {
	function __construct() {
		parent::__construct(
			'jvfrm_spot_menu_btn_right_menu',
			esc_html__('[JAVO] Canvas Menu Button','javospot'),
			Array( 'description' => esc_html__( 'Listing submit button (only for menu).', 'javospot' )
		) );
	}

	/**
	 * Display widget
	 */
	function widget( $args, $instance )
	{
		extract( $args, EXTR_SKIP );

		$jvfrm_spot_query			= new jvfrm_spot_array( $instance );
		$jvfrm_spot_button_style	= '';


		if( false !== $jvfrm_spot_query->get( 'button_style', false ) )
		{
			$jvfrm_spot_button_styles = Array(
				'color'		=> $jvfrm_spot_query->get( 'btn_txt_color', 'inherit' )
			);

			foreach( $jvfrm_spot_button_styles as $attribute => $option )
			{
				$jvfrm_spot_button_style .= "{$attribute}:{$option}; ";
			}
			$jvfrm_spot_button_style = trim( $jvfrm_spot_button_style );
		} ?>

		<li class="widget_top_menu javo-in-mobile x-<?php echo esc_attr( $jvfrm_spot_query->get( 'btn_visible', '') );?>">
			<div class="javo-wg-menu-right-menu right-menu-wrap">
				<button
					class		= "right-menu btn"
					style		= "<?php echo esc_attr( $jvfrm_spot_button_style ); ?>"
					type		= "button"
					data-toggle	= "offcanvas"
					data-recalc	= "false"
					data-target	= ".navmenu"
					data-canvas	= ".canvas, #header-one-line">
					<i class="fa fa-bars"></i>
				</button>
			</div><!-- /.right-menu-wrap -->

		</li><!-- /.widget_top_menu -->
		<?php
	}

	function form( $instance )
	{
		/* Set up some default widget settings. */
		$defaults			= array(
			'btn_txt_color'	=> ''
			, 'btn_visible'	=> ''
		);
		$instance			= wp_parse_args( $instance, $defaults );
		$jvfrm_spot_query			= new jvfrm_spot_array( $instance );

		?>
		<div class="javo-dtl-trigger" data-javo-dtl-el="[name='<?php echo esc_attr( $this->get_field_name( 'button_style' ) ); ?>']" data-javo-dtl-val="set" data-javo-dtl-tar=".javo-button-right-menu-detail-style">
			<dl>
				<dt>
					<label><?php esc_html_e( "Show on mobile", 'javospot'); ?></label>
				</dt>
				<dd>
					<label>
						<input
							name="<?php echo esc_attr( $this->get_field_name( 'btn_visible' ) ); ?>"
							type="radio"
							value=""
							<?php checked( '' == $jvfrm_spot_query->get('btn_visible') );?>
						>
						<?php esc_html_e( "Enable", 'javospot' );?>
					</label>
					<label>
						<input
							name="<?php echo esc_attr( $this->get_field_name( 'btn_visible' ) ); ?>"
							type="radio"
							value="hide"
							<?php checked( 'hide' == $jvfrm_spot_query->get('btn_visible') );?>
						>
						<?php esc_html_e( "Hide", 'javospot' );?>
					</label>
				</dd>
			</dl>
			<dl>
				<dt>
					<label><?php esc_html_e( "Style Setting", 'javospot'); ?></label>
				</dt>
				<dd>
					<label>
						<input
							name="<?php echo esc_attr( $this->get_field_name( 'button_style' ) ); ?>"
							type="radio"
							value=""
							<?php checked( '' == $jvfrm_spot_query->get('button_style') );?>>
						<?php esc_html_e( "Same as navi menu color", 'javospot' );?>
					</label>
					<br>
					<label>
						<input
							name="<?php echo esc_attr( $this->get_field_name( 'button_style' ) ); ?>"
							type="radio"
							value="set"
							<?php checked( 'set' == $jvfrm_spot_query->get('button_style') );?>>
						<?php esc_html_e( "Setup own custom color", 'javospot' );?>
					</label>
				</dd>
			</dl>
			<div class="javo-button-right-menu-detail-style">
				<dl class="no-margin">
					<dt>
						<label> <?php esc_html_e( 'Button text color', 'javospot' ); ?> : </label>
					</dt>
					<dd>
						<input
							name="<?php echo esc_attr( $this->get_field_name( 'btn_txt_color' ) ); ?>"
							type="text"
							value="<?php echo esc_attr( $jvfrm_spot_query->get('btn_txt_color', '#ffffff' ) ); ?>"
							class="wp_color_picker"
							data-default-color="#ffffff">
					</dd>
				</dl>
			</div><!-- /.javo-button-right-menu-detail-style -->
		</div>
		<?php
	}
}

/**
 * Register widget.
 *
 * @since 1.0
 */
add_action( 'widgets_init', create_function( '', 'register_widget( "JV_CanvasMenu_Button" );' ) );