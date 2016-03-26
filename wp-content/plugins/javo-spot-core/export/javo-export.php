<?php
if (!function_exists ('add_action')) {
		header('Status: 403 Forbidden');
		header('HTTP/1.1 403 Forbidden');
		exit();
}
class jvfrm_spot_Export {

	function jvfrm_spot_Export() {
		add_action('admin_menu', array(&$this, 'jvfrm_spot_admin_export'));
	}
	function init_jvfrm_spot_Export() {
		if(isset($_REQUEST['export_option'])) {
			$export_option = $_REQUEST['export_option'];
			if($export_option == 'widgets') {
				$this->export_widgets_sidebars();
			} elseif($export_option == 'custom_sidebars') {
				$this->export_custom_sidebars();
			} elseif($export_option == 'jvfrm_spot_theme_settings'){
				$this->export_theme_settings();
			}elseif($export_option == 'jvfrm_spot_menus'){
				$this->export_jvfrm_spot_menus();
			}elseif($export_option == 'setting_pages'){
				$this->export_settings_pages();
			}
		}
	}

	public function export_custom_sidebars(){
		$custom_sidebars = get_option("jvfrm_spot_sidebars");
		$output = base64_encode(serialize($custom_sidebars));
		$this->save_as_txt_file("custom_sidebars.txt", $output);
	}
	public function export_theme_settings(){
		$jvfrm_spot_theme_settings = get_option("jvfrm_spot_themes_settings");
		$output = base64_encode(serialize($jvfrm_spot_theme_settings));
		$this->save_as_txt_file("theme_settings.txt", $output);
	}

	public function export_widgets_sidebars(){
		$this->data = array();
		$this->data['sidebars'] = $this->export_sidebars();
		$this->data['widgets'] 	= $this->export_widgets();
		$output = base64_encode(serialize($this->data));
		$this->save_as_txt_file("widgets.txt", $output);
	}
	public function export_widgets(){

		global $wp_registered_widgets;
		$all_jvfrm_spot_widgets = array();

		foreach ($wp_registered_widgets as $jvfrm_spot_widget_id => $widget_params)
			$all_jvfrm_spot_widgets[] = $widget_params['callback'][0]->id_base;

		foreach ($all_jvfrm_spot_widgets as $jvfrm_spot_widget_id) {
			$jvfrm_spot_widget_data = get_option( 'widget_' . $jvfrm_spot_widget_id );
			if ( !empty($jvfrm_spot_widget_data) )
				$widget_datas[ $jvfrm_spot_widget_id ] = $jvfrm_spot_widget_data;
		}
		unset($all_jvfrm_spot_widgets);
		return $widget_datas;

	}
	public function export_sidebars(){
		$jvfrm_spot_sidebars = get_option("sidebars_widgets");
		$jvfrm_spot_sidebars = $this->exclude_sidebar_keys($jvfrm_spot_sidebars);
		return $jvfrm_spot_sidebars;
	}
	private function exclude_sidebar_keys( $keys = array() ){
		if ( ! is_array($keys) )
			return $keys;

		unset($keys['wp_inactive_widgets']);
		unset($keys['array_version']);
		return $keys;
	}

	public function export_jvfrm_spot_menus(){
		global $wpdb;

		$this->data = array();
		$locations = get_nav_menu_locations();

		$terms_table = $wpdb->prefix . "terms";
		foreach ((array)$locations as $location => $menu_id) {
			$menu_slug = $wpdb->get_results("SELECT * FROM $terms_table where term_id={$menu_id}", ARRAY_A);
			$this->data[ $location ] = $menu_slug[0]['slug'];
		}
		$output = base64_encode(serialize( $this->data ));
		$this->save_as_txt_file("menus.txt", $output);
	}
	public function export_settings_pages(){
		$jvfrm_spot_static_page = get_option("page_on_front");
		$jvfrm_spot_post_page = get_option("page_for_posts");
		$jvfrm_spot_show_on_front = get_option("show_on_front");
		$jvfrm_spot_settings_pages = array(
			'show_on_front' => $jvfrm_spot_show_on_front,
			'page_on_front' => $jvfrm_spot_static_page,
			'page_for_posts' => $jvfrm_spot_post_page
		);
		$output = base64_encode(serialize($jvfrm_spot_settings_pages));
		$this->save_as_txt_file("settingpages.txt", $output);
	}
	function save_as_txt_file($file_name, $output){
		header("Content-type: application/text",true,200);
		header("Content-Disposition: attachment; filename=$file_name");
		header("Pragma: no-cache");
		header("Expires: 0");
		echo $output;
		exit;

	}

	function jvfrm_spot_admin_export() {
		if(isset($_REQUEST['export'])){
			$this->init_jvfrm_spot_Export();
		}
		//Add the javo options page to the Themes' menu
		add_menu_page('Javo Theme', esc_html__('Javo Export', 'javo'), 'manage_options', 'jvfrm_spot_options_export_page', array(&$this, 'jvfrm_spot_generate_export_page'));

	}

	function jvfrm_spot_generate_export_page() {

		?>
		<div class="wrapper">
				<div class="content">
					<table class="form-table">
						<tbody>
							<tr><td scope="row" width="150"><h2><?php esc_html_e('Export', 'javo'); ?></h2></td></tr>
							<tr valign="middle">

								<td>
		    						<form method="post" action="">
									<input type="hidden" name="export_option" value="widgets" />
									<input type="submit" value="Export Widgets" name="export" />
		    						</form>
		    						<br />
		    						<form method="post" action="">
									<input type="hidden" name="export_option" value="custom_sidebars" />
									<input type="submit" value="Export Custom Sidebars" name="export" />
		    						</form>
		    						<br />
		    						<form method="post" action="">
									<input type="hidden" name="export_option" value="jvfrm_spot_theme_settings" />
									<input type="submit" value="Export Theme Settings" name="export" />
		    						</form>
		    						<br />
		    						<form method="post" action="">
									<input type="hidden" name="export_option" value="jvfrm_spot_menus" />
									<input type="submit" value="Export Menus" name="export" />
		    						</form>
		    						<br />
		    						<form method="post" action="">
									<input type="hidden" name="export_option" value="setting_pages" />
									<input type="submit" value="Export Setting Pages" name="export" />
		    						</form>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
		</div>

<?php
	}
}