<?php
if ( !defined( 'ABSPATH' ) )
	die;

class jvfrm_spot_Menu_button_item_submit extends WP_Widget
{

	public function __construct()
	{
		parent::__construct(
			'jvfrm_spot_menu_btn_submit_item',
			esc_html__( "[JAVO] Menu button - Submit Item", 'javospot' ),
			array(
				'description'	=> esc_html__( "Item submit button (only for menu).", 'javospot' )
			)
		);
	}

	public function widget( $args, $instance )
	{
		extract( $args, EXTR_SKIP );
		$instance			= !empty( $instance ) ? $instance : Array();
		$jvfrm_spot_query			= new jvfrm_spot_array( $instance );
		$jvfrm_spot_this_style	= $this->getButtonStyles( $instance );
		$jvfrm_spot_attribute		= $this->getButtonAttribute();
		$jvfrm_spot_text			= esc_html__( "Submit a list", 'javospot' );
		$jvfrm_spot_mobile_css	= '';

		if( !empty( $instance[ 'btn_txt' ] ) )
			$jvfrm_spot_text			= esc_html( $instance[ 'btn_txt' ] );

		if( !empty( $instance[ 'btn_visible' ] ) )
			$jvfrm_spot_mobile_css	= ' ' . esc_attr( 'x-' . $instance[ 'btn_visible' ] );

		echo join( "\n",
			Array(
				"<li class=\"widget_top_menu javo-in-mobile{$jvfrm_spot_mobile_css};\">",
				"<a {$jvfrm_spot_attribute} style=\"{$jvfrm_spot_this_style}\">{$jvfrm_spot_text}</a>",
				"</li>",
			)
		);
	}

	public function getButtonStyles( $instance )
	{
		$arrStyles		= Array();
		$arrOutput	= Array();
		if( ! empty( $instance[ 'button_style' ] ) ) {

			if( !empty( $instance[ 'btn_bg_color' ] ) )
				$arrStyles[ 'background-color' ]	= $instance[ 'btn_bg_color' ];

			if( !empty( $instance[ 'btn_border_color' ] ) )
				$arrStyles[ 'border-color' ]	= $instance[ 'btn_border_color' ];

			if( !empty( $instance[ 'btn_txt_color' ] ) )
				$arrStyles[ 'color' ]	= $instance[ 'btn_txt_color' ];

			if( !empty( $instance[ 'btn_radius' ] ) )
				$arrStyles[ 'border-raidus' ]	= intVal( $instance[ 'btn_radius' ] ) . 'px';
		}

		foreach( $arrStyles as $option => $value )
			$arrOutput[]	= "{$option}:{$value} !important;";

		return esc_attr( join( ' ', $arrOutput ) );
	}

	public function getButtonAttribute()
	{

		$arrAttribute	= Array(
			'class'			=> 'btn',
			'href'				=>  esc_url(
				home_url(
					JVFRM_SPOT_DEF_LANG.JVFRM_SPOT_MEMBER_SLUG . '/' . wp_get_current_user()->user_login . '/' . JVFRM_SPOT_ADDITEM_SLUG . '/'
				)
			)
		);
		$arrOutput		= Array();

		if( !is_user_logged_in() ) {
			$arrAttribute[ 'href' ] = 'javascript:';
			$arrAttribute[ 'data-toggle' ]	= 'modal';
			$arrAttribute[ 'data-target' ]	= '#login_panel';
		}
		foreach( $arrAttribute as $attribute => $value )
			$arrOutput[] = "{$attribute}=\"" . esc_attr( $value ) . "\"";

		return  join( ' ', $arrOutput );
	}

	function form( $instance )
	{
		$defaults = array(
			'btn_txt'			=> '',
			'btn_txt_color'		=> '',
			'btn_bg_color'		=> '',
			'btn_border_color'	=> '',
			'btn_radius'		=> '',
			'btn_visible'		=> '',
			'date'				=> true,
		);
		$instance				= wp_parse_args( (array) $instance, $defaults );
		$btn_txt				= esc_attr( $instance['btn_txt'] );
		$btn_txt_color			= esc_attr( $instance['btn_txt_color'] );
		$btn_bg_color			= esc_attr( $instance['btn_bg_color'] );
		$btn_border_color		= esc_attr( $instance['btn_border_color'] );
		$btn_radius				= esc_attr( $instance['btn_radius'] );
		$btn_visible			= esc_attr( $instance['btn_visible'] );
		$jvfrm_spot_var				= new jvfrm_spot_array( $instance );
	?>
	<div class="javo-dtl-trigger" data-javo-dtl-el="[name='<?php echo esc_attr( $this->get_field_name( 'button_style' ) ); ?>']" data-javo-dtl-val="set" data-javo-dtl-tar=".javo-button-submit-detail-style">
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'btn_txt' ) ); ?>"><?php esc_html_e( 'Button Text:', 'javospot' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'btn_txt' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'btn_txt' ) ); ?>" type="text" value="<?php echo esc_attr( $btn_txt ); ?>" >
		</p>

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
						<?php checked( '' == $jvfrm_spot_var->get('button_style') );?>>
					<?php esc_html_e( "Same as navi menu color", 'javospot' );?>
				</label>
				<br>
				<label>
					<input
						name="<?php echo esc_attr( $this->get_field_name( 'button_style' ) ); ?>"
						type="radio"
						value="set"
						<?php checked( 'set' == $jvfrm_spot_var->get('button_style') );?>>
					<?php esc_html_e( "Setup own custom color", 'javospot' );?>
				</label>
			</dd>
		</dl>
		<div class="javo-button-submit-detail-style">
			<p class="no-margin">
				<label for="<?php echo esc_attr( $this->get_field_id( 'btn_txt_color' ) ); ?>"><?php esc_html_e( 'Button text color:', 'javospot' ); ?></label>
				<input name="<?php echo esc_attr( $this->get_field_name( 'btn_txt_color' ) ); ?>" type="text" class="wp_color_picker" data-default-color="#ffffff" value="<?php echo esc_attr( $btn_txt_color ); ?>" >
			</p>
			<p class="no-margin">
				<label for="<?php echo esc_attr( $this->get_field_id( 'btn_bg_color' ) ); ?>"><?php esc_html_e( 'Button background color:', 'javospot' ); ?></label>
				<input name="<?php echo esc_attr( $this->get_field_name( 'btn_bg_color' ) ); ?>" type="text" class="wp_color_picker" data-default-color="#ffffff" value="<?php echo esc_attr($btn_bg_color ); ?>" >
			</p>
			<p class="no-margin">
				<label for="<?php echo esc_attr( $this->get_field_id( 'btn_border_color' ) ); ?>"><?php esc_html_e( 'Button border color:', 'javospot' ); ?></label>
				<input name="<?php echo esc_attr( $this->get_field_name( 'btn_border_color' ) ); ?>" type="text" class="wp_color_picker" data-default-color="#ffffff" value="<?php echo esc_attr( $btn_border_color ); ?>" >
			</p>
			<p class="no-margin">
				<label for="<?php echo esc_attr( $this->get_field_id( 'btn_radius' ) ); ?>"><?php esc_html_e( 'Button radius (only number):', 'javospot' ); ?></label>
				<input name="<?php echo esc_attr( $this->get_field_name( 'btn_radius' ) ); ?>"  type="text" value="<?php echo esc_attr( $btn_radius ); ?>" >
			</p>
		</div><!-- /.javo-button-submit-detail-style -->

		<p>
			<label><?php esc_html_e( "Show on mobile", 'javospot'); ?></label>

			<label>
				<input
					name="<?php echo esc_attr( $this->get_field_name( 'btn_visible' ) ); ?>"
					type="radio"
					value=""
					<?php checked( '' == $btn_visible );?>
				>
				<?php esc_html_e( "Enable", 'javospot' );?>
			</label>

			<label>
				<input
					name="<?php echo esc_attr( $this->get_field_name( 'btn_visible' ) ); ?>"
					type="radio"
					value="hide"
					<?php checked( 'hide' == $btn_visible );?>
				>
				<?php esc_html_e( "Hide", 'javospot' );?>
			</label>
		</p>
	</div><!-- /.javo-dtl-trigger -->
	<?php
	}
}
/**
 * Register widget.
 *
 * @since 1.0
 */
add_action( 'widgets_init', create_function( '', 'register_widget( "jvfrm_spot_Menu_button_item_submit" );' ) );