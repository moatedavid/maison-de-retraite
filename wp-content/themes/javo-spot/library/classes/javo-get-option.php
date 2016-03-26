<?php
/*************************
** Javo Get Option
*************************/
class jvfrm_spot_get_theme_settings
{
	const OPTIONS_KEY = 'jvfrm_spot_themes_settings';

	private $options;

	public $map;

	public $header;

	public static $instance;

	public function __construct(){
		if( !$this->options )
			$this->get_options();

		$this->map = new jvfrm_spot_array( (Array) $this->get('map', Array() ) );
		$this->header = new jvfrm_spot_array( (Array) $this->get('hd', Array() ) );
	}

	public function get_options(){
		$strOptions		= $this->get_option_orgin();
		$this->options	=  maybe_unserialize( $strOptions );
	}

	public function get_option_orgin(){
		return get_option( self::OPTIONS_KEY );
	}

	public static function getAll(){
		return self::$instance->options;
	}

	public function get( $setting_key=false, $default=NULL )
	{
		if( !$setting_key || !is_array( $this->options ) )
			return $default;

		$strReturn		= $default;
		if( isset( $this->options[ $setting_key ] ) )
			$strReturn	= $this->options[ $setting_key ];
		return $strReturn;
	}

	public static function getInstance(){
		if( !self::$instance )
			self::$instance = new self;
		return self::$instance;
	}
}

if( !function_exists( 'jvfrm_spot_tso' ) ):
	function jvfrm_spot_tso() {
		$objInstance = jvfrm_spot_get_theme_settings::getInstance();
		$GLOBALS[ 'jvfrm_spot_tso' ] = $objInstance;
		$GLOBALS[ 'jvfrm_spot_tso' ]  = $objInstance;
		return $objInstance;
	}
	jvfrm_spot_tso();
endif;