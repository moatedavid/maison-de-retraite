<?php
class Javo_Spot_Core_Admin extends Javo_Spot_Core
{

	private $modules = Array();

	private $module_option_format = 'jvfrm_spot_term_%s_module';

	public function __construct() {
		add_action( 'jvfrm_spot_modules_loaded', Array( $this, 'get_modules' ) );
		add_filter( 'manage_edit-category_columns' , Array( $this, 'custom_category_column' ) );
		add_filter( 'manage_category_custom_column' , Array( $this, 'custom_category_contents' ), 10, 3 );
		add_action( 'category_add_form_fields', Array( $this, 'append_module_selector' ) );
		add_action( 'category_edit_form_fields', Array( $this, 'append_module_selector' ) );
		add_action( 'created_category', Array( $this, 'update_module_name' ), 10, 2 );
		add_action( 'edited_category', Array( $this, 'update_module_name' ), 10, 2 );
		add_action( 'deleted_term_taxonomy', Array( $this, 'trash_module_name' ) );
	}

	public function get_term_module( $term_id, $default=false ) {
		return get_option( sprintf( $this->module_option_format, $term_id ), $default );
	}

	public function get_term_module_post_length( $term_id, $default=false ) {
		return get_option( sprintf( $this->module_option_format . '_length' , $term_id ), $default );
	}

	public function get_term_module_columns( $term_id, $default=false ) {
		return get_option( sprintf( $this->module_option_format . '_columns' , $term_id ), $default );
	}

	public function get_modules( $modules ) {
		$this->modules	= Array_diff(
			Array_keys( $modules ),
			Array(
				'module2'
			)
		);
	}

	public function custom_category_column( $columns ) {
		return wp_parse_args(
			$columns,
			Array(
				'cb'				=> '<input type="checkbox">',
				'name'			=>__( "Name", 'javo' ),
				'description'	=>__( "Description", 'javo' ),
				'jvfrm_spot_module'	=> __( "Module Name", 'javo' ),
			)
		);
	}

	public function custom_category_contents( $dep, $column_name, $term_id ) {
		switch( $column_name ) {
			case 'jvfrm_spot_module' :
				if(  ! $module_name = $this->get_term_module( $term_id ) )
					$module_name	 = __( "None", 'javo' );
				echo $module_name;
			break;
		}
	}

	public function append_module_selector( $objTaxonomy ) {

		if( ! is_Array( $this->modules ) )
			return;

		$term_id	= 0;
		if( is_object( $objTaxonomy ) )
			$term_id = $objTaxonomy->term_id;

		$arrOptionModules					=
		$arrOptionModules_columns	= Array();
		foreach( $this->modules as $module_name )
			$arrOptionModules[]	= sprintf(
				"<option value=\"{$module_name}\"%s>{$module_name}</option>",
				selected( $module_name == $this->get_term_module( $term_id ), true, false )
			);

		for( $intColumn=1; $intColumn <= 3; $intColumn++ )
			$arrOptionModules_columns[]	= sprintf(
				"<option value=\"{$intColumn}\"%s>{$intColumn} %s</option>",
				selected( $intColumn == $this->get_term_module_columns( $term_id ), true, false ),
				_n( "Column", "Columns", $intColumn, 'javo' )
			);

		echo join( "\n",
			Array(
				'<tr>',
					'<th>',
						__( "Archive Module", 'javo' ),
					'</th>',
					'<td>',
						sprintf( '<h4>%s</h4>', __( "Module Name", 'javo' ) ),
						'<fieldset class="inner">',
							'<select name="jvfrm_spot_category[_module]">',
							'<option value="">' . __( "Default Template", 'javo' ) . '</option>',
							join( "\n", $arrOptionModules ),
							'</select>',
						'</fieldset>',
						sprintf( '<h4>%s</h4>', __( "Module Columns", 'javo' ) ),
						'<fieldset class="inner">',
							'<select name="jvfrm_spot_category[_module_columns]">',
							join( "\n", $arrOptionModules_columns ),
							'</select>',
						'</fieldset>',
						sprintf( '<h4>%s</h4>', __( "Post Content Length", 'javo' ) ),
						'<fieldset class="inner">',
							sprintf( '<input type="number" name="jvfrm_spot_category[_module_length]" value="%s">', $this->get_term_module_post_length( $term_id ) ),
						'</fieldset>',
					'</td>',
				'</tr>',
			)
		);
	}

	public function update_module_name( $term_id, $taxonomy_id ){

		if( empty( $term_id ) || empty( $_POST[ 'jvfrm_spot_category' ] ) )
			return;

		foreach( $_POST[ 'jvfrm_spot_category'] as $key_name => $value )
			update_option( 'jvfrm_spot_term_' . $term_id . $key_name, $value );
	}

	public function trash_module_name( $term_id ) {
		delete_option( $this->module_option_format );
		delete_option( $this->module_option_format . '_length' );
		delete_option( $this->module_option_format . '_columns' );
	}
}