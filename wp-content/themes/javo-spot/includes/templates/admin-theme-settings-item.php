<div class="jvfrm_spot_ts_tab javo-opts-group-tab hidden" tar="lv_listing">
	<h2> <?php esc_html_e( "Item Settings", 'javospot'); ?> </h2>

	<table class="form-table">
	<tr><th>
		<?php esc_html_e( "Single Item", 'javospot');?>
		<span class="description">	</span>
	</th><td>

		<h4><?php esc_html_e( "Header Cover Style", 'javospot' ); ?></h4>
		<fieldset class="inner">
			<select name="jvfrm_spot_ts[lv_listing_single_header_cover]">
				<?php
				$arrSingleItemHeaderCover	= apply_filters(
					'jvfrm_spot_lv_listing_header',
					Array(
						''				=> esc_html__( "None", 'javospot' ),
						'shadow'	=> esc_html__( "Top & Bottom Shadow", 'javospot' ),
						'overlay'	=> esc_html__( "Full Cover Overlay", 'javospot' ),
					)
				);
				if( !empty( $arrSingleItemHeaderCover ) )
					foreach( $arrSingleItemHeaderCover as $classes => $label ) {
						printf(
							"<option value=\"{$classes}\" %s>{$label}</option>",
							selected( $jvfrm_spot_tso->get( 'lv_listing_single_header_cover', '' ) == $classes, true, false )
						);
					}
				?>
			</select>
		</fieldset>

		<h4><?php esc_html_e( "Widget Sticky", 'javospot' ); ?></h4>
		<fieldset class="inner">
			<select name="jvfrm_spot_ts[<?php echo jvfrm_spot_core()->slug;?>_single_sticky_widget]">
			<?php
			foreach(
				Array(
					''			=> esc_html__( "Enable", 'javospot' ),
					'disable'	=> esc_html__( "Disable", 'javospot' ),
				)
			as $strOption => $strLabel )
				printf(
					"<option value=\"{$strOption}\" %s>{$strLabel}</option>",
					selected( $jvfrm_spot_tso->get( jvfrm_spot_core()->slug . '_single_sticky_widget', '' ) == $strOption, true, false )
				);
			?>
			</select>
		</fieldset>

		<h4><?php esc_html_e( "Map max width", 'javospot' ); ?></h4>
		<fieldset class="inner">
			<select name="jvfrm_spot_ts[<?php echo jvfrm_spot_core()->slug;?>_map_width_type]">
			<?php
			foreach(
				Array(
					''			=> esc_html__( "Full width", 'javospot' ),
					'boxed'	=> esc_html__( "Inner width", 'javospot' ),
				)
			as $strOption => $strLabel )
				printf(
					"<option value=\"{$strOption}\" %s>{$strLabel}</option>",
					selected( $jvfrm_spot_tso->get( jvfrm_spot_core()->slug . '_map_width_type', '' ) == $strOption, true, false )
				);
			?>
			</select>
		</fieldset>

		<?php
		/** Single page type - Temporary disabled.
		<h4><?php esc_html_e( "Single Type", 'javospot' ); ?></h4>
		<fieldset class="inner">
			<select name="jvfrm_spot_ts[lv_listing_single_type]">
				<?php
				$arrSingleItemHeaderCover	= apply_filters(
					'jvfrm_spot_lv_listing_header',
					Array(
						''				=> esc_html__( "Type A (default)", 'javospot' ),
						'type-b'		=> esc_html__( "Type B", 'javospot' ),
					)
				);
				if( !empty( $arrSingleItemHeaderCover ) )
					foreach( $arrSingleItemHeaderCover as $classes => $label ) {
						printf(
							"<option value=\"{$classes}\" %s>{$label}</option>",
							selected( $jvfrm_spot_tso->get( 'lv_listing_single_type', '' ) == $classes, true, false )
						);
					}
				?>
			</select>
		</fieldset>
		*/ ?>

	</td></tr><tr><th>
		<?php esc_html_e( "Mobile Search", 'javospot');?>
		<span class="description">	</span>
	</th><td>
		<h4><?php esc_html_e( "Search Result Map Page", 'javospot' ); ?></h4>
		<fieldset class="inner">
			<select name="jvfrm_spot_ts[lv_listing_mobile_search_requester]">
				<?php
				$arrTemplates	= apply_filters( 'jvfrm_spot_get_map_templates',
					Array( esc_html__( "Not Set", 'javospot' ) => '' )
				);
				foreach( $arrTemplates as $label => $value )
					printf( "<option value=\"{$value}\" %s>{$label}</option>",
						selected( $jvfrm_spot_tso->get( 'lv_listing_mobile_search_requester' ) == $value, true, false )
					);
				?>
			</select>
		</fieldset>

		<h4><?php esc_html_e( "Columns", 'javospot' ); ?></h4>
		<fieldset class="inner">
			<?php
			$arrColumns		= Array();
			for( $intCount=1; $intCount <= 3; $intCount++ )
				$arrColumns[]	= sprintf(
					"<option value=\"{$intCount}\" %s>{$intCount} %s</option>",
					selected( $jvfrm_spot_tso->get( 'lv_listing_mobile_search_columns' , 1) == $intCount, true, false ) ,
					_n( 'Column', 'Columns', $intCount, 'javospot' )
				);


			// Columns
			echo join( '\n',
				Array(
					'<select name="jvfrm_spot_ts[lv_listing_mobile_search_columns]">',
					join( '\n', $arrColumns ),
					'</select>',
				)
			);

			// Linked
			if( method_exists( 'jvfrm_spot_search1', 'fieldParameter' ) ) {
				$arrParamsArgs		= jvfrm_spot_search1::fieldParameter( null );
				$arrColumnFields		= isset( $arrParamsArgs[ 'value' ] ) ? $arrParamsArgs[ 'value' ] :Array();
				if( !empty( $arrColumnFields ) ) {
					for( $intCount=1; $intCount <= 3; $intCount++ ) {
						$strThisValue	= $jvfrm_spot_tso->get( 'lv_listing_mobile_search_column' . $intCount );
						printf( "
							<p>
								<label>%s {$intCount}</label>
								<select name=\"jvfrm_spot_ts[lv_listing_mobile_search_column{$intCount}]\">"
							, esc_html__( "Column", 'javospot' )
						);
							foreach( $arrColumnFields as $label => $key )
								printf( "<option value=\"{$key}\" %s>{$label}</option>",
									selected( $strThisValue == $key, true, false )
								);
							echo "</select>";
						echo "</p>";
					}
				}
			} ?>
		</fieldset>
	</td></tr>
	</table>
</div>