<?php
if (!function_exists ('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}
class jvfrm_spot_Import extends Javo_Spot_Core{

    public $message = "";

    public $attachments = false;

	private $dirCurrent = false;

	private $dirFiles = false;

    function __construct()
	{
		if( !class_exists( 'Javo_Spot_Core') )
			return;

		$jvfrm_spot_home_core		= self::$instance;
		$this->dirCurrent	= $jvfrm_spot_home_core->import_path;
		$this->dirFiles		= $this->dirCurrent . '/files';

        add_action('jvfrm_spot_admin_helper_register_menu', array(&$this, 'jvfrm_spot_admin_import'));
        add_action('admin_init', array(&$this, 'jvfrm_spot_register_theme_settings'));
    }

    function jvfrm_spot_register_theme_settings() {
        register_setting( 'jvfrm_spot_options_import_page', 'jvfrm_spot_options_import');
    }

    function jvfrm_spot_init_import() {
        if(isset($_REQUEST['import_option'])) {
            $import_option = $_REQUEST['import_option'];
            if($import_option == 'content'){
                $this->import_content('proya_content.xml');
            }elseif($import_option == 'custom_sidebars') {
                $this->import_custom_sidebars('custom_sidebars.txt');
            } elseif($import_option == 'widgets') {
                $this->import_widgets('widgets.txt');
            } elseif($import_option == 'theme_settings'){
                $this->import_theme_settings('jvfrm_spot_themes_settings.txt');
            }elseif($import_option == 'menus'){
                $this->import_menus('menus.txt');
            }elseif($import_option == 'settingpages'){
                $this->import_settings_pages('settingpages.txt');
            }elseif($import_option == 'complete_content'){
                $this->import_content('proya_content.xml');
                $this->import_theme_settings('jvfrm_spot_themes_settings.txt');
                $this->import_widgets('widgets.txt');
                $this->import_menus('menus.txt');
                $this->import_settings_pages('settingpages.txt');
                $this->message = __("Content imported successfully", "javo");
            }
        }
    }

    public function import_content($file){
        if (!class_exists('WP_Importer')) {
            ob_start();
            $class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
            require_once($class_wp_importer);
            require_once $this->dirCurrent . '/class.wordpress-importer.php';
            $jvfrm_spot_Import = new WP_Import();
            set_time_limit(0);
            $path = $this->dirFiles . '/' . $file;

            $jvfrm_spot_Import->fetch_attachments = $this->attachments;
            $returned_value = $jvfrm_spot_Import->import($path);
            if(is_wp_error($returned_value)){
                $this->message = __("An Error Occurred During Import", "javo");
            }
            else {
                $this->message = __("Content imported successfully", "javo");
            }
            ob_get_clean();
        } else {
            $this->message = __("Error loading files", "javo");
        }
    }

    public function import_widgets($file){
        $this->import_custom_sidebars('custom_sidebars.txt');
        $options = $this->file_options($file);
        foreach ((array) $options['widgets'] as $jvfrm_spot_widget_id => $jvfrm_spot_widget_data) {
            update_option( 'widget_' . $jvfrm_spot_widget_id, $jvfrm_spot_widget_data );
        }
        $this->import_sidebars_widgets($file);
        $this->message = __("Widgets imported successfully", "javo");
    }

    public function import_sidebars_widgets($file){
        $jvfrm_spot_sidebars = get_option("sidebars_widgets");
        unset($jvfrm_spot_sidebars['array_version']);
        $data = $this->file_options($file);
        if ( is_array($data['sidebars']) ) {
            $jvfrm_spot_sidebars = array_merge( (array) $jvfrm_spot_sidebars, (array) $data['sidebars'] );
            unset($jvfrm_spot_sidebars['wp_inactive_widgets']);
            $jvfrm_spot_sidebars = array_merge(array('wp_inactive_widgets' => array()), $jvfrm_spot_sidebars);
            $jvfrm_spot_sidebars['array_version'] = 2;
            wp_set_sidebars_widgets($jvfrm_spot_sidebars);
        }
    }

    public function import_custom_sidebars($file){
        $options = $this->file_options($file);
        update_option( 'jvfrm_spot_sidebars', $options);
        $this->message = __("Custom sidebars imported successfully", "javo");
    }

    public function import_theme_settings($file){
        $options = $this->file_options($file);
        update_option( 'jvfrm_spot_themes_settings', $options);
        $this->message = __("Options imported successfully", "javo");
    }

    public function import_menus($file){
        global $wpdb;
        $jvfrm_spot_terms_table = $wpdb->prefix . "terms";
        $this->menus_data = $this->file_options($file);


        $menu_array = array();
        foreach ($this->menus_data as $registered_menu => $menu_slug) {
            $term_rows = $wpdb->get_results("SELECT * FROM $jvfrm_spot_terms_table where slug='{$menu_slug}'", ARRAY_A);

            if(isset($term_rows[0]['term_id'])) {
                $term_id_by_slug = $term_rows[0]['term_id'];
            } else {
                $term_id_by_slug = null;
            }
            $menu_array[$registered_menu] = $term_id_by_slug;
        }
        set_theme_mod('nav_menu_locations', array_map('absint', $menu_array ) );

    }
    public function import_settings_pages($file){
        $pages = $this->file_options($file);

        foreach($pages as $jvfrm_spot_page_option => $jvfrm_spot_page_id){
			echo( "Key=> {$jvfrm_spot_page_option}, Value => {$jvfrm_spot_page_id} <br>" );
            update_option( $jvfrm_spot_page_option, $jvfrm_spot_page_id);
        }
    }
    public function file_options($file){
        $file_content = "";
        $file_for_import = $this->dirFiles . '/' . $file;
        if ( file_exists($file_for_import) ) {
            $file_content = $this->jvfrm_spot_file_contents($file_for_import);
        } else {
            $this->message = __("File doesn't exist", "javo");
        }
        if ($file_content) {
            $unserialized_content = unserialize(base64_decode($file_content));
            if ($unserialized_content) {
                return $unserialized_content;
            }
        }
        return false;
    }

    function jvfrm_spot_file_contents( $path ) {
        $jvfrm_spot_content = '';
        if ( function_exists('realpath') )
            $filepath = realpath($path);
        if ( !$filepath || !@is_file($filepath) )
            return '';

        if( ini_get('allow_url_fopen') ) {
            $jvfrm_spot_file_method = 'fopen';
        } else {
            $jvfrm_spot_file_method = 'file_get_contents';
        }
        if ( $jvfrm_spot_file_method == 'fopen' ) {
            $jvfrm_spot_handle = fopen( $filepath, 'rb' );

            if( $jvfrm_spot_handle !== false ) {
                while (!feof($jvfrm_spot_handle)) {
                    $jvfrm_spot_content .= fread($jvfrm_spot_handle, 8192);
                }
                fclose( $jvfrm_spot_handle );
            }
            return $jvfrm_spot_content;
        } else {
            return file_get_contents($filepath);
        }
    }

    function jvfrm_spot_admin_import()
	{
		$this->name				= jvfrm_spot_admin_helper::$instance->name;

        if(isset($_REQUEST['import'])){
            //$this->jvfrm_spot_init_import();
        }

        //$this->pagehook = add_submenu_page('jvfrm_spot_options_proya_page', 'Javo Theme', esc_html__('Javo Import', 'javo'), 'manage_options', 'jvfrm_spot_options_import_page', array(&$this, 'jvfrm_spot_generate_import_page'));
        $this->pagehook = add_submenu_page(
			sanitize_title( $this->name )
			, esc_html__( "Javo Import", 'javo' )
			, esc_html__( "Demo Import", 'javo' )
			, 'manage_options'
			, sanitize_title( $this->name . '_import' )
			, array(&$this, 'jvfrm_spot_generate_import_page')
			,'dashicons-download'
		);

    }

    function jvfrm_spot_generate_import_page() {
        //wp_enqueue_style('bootstrap');   // Get style files if you need.

		do_action( 'jvfrm_spot_admin_helper_page_header' );
		do_action( 'jvfrm_spot_admin_helper_import_header' );

		$arrDemoSelector	= Array(
			'javo-demo'		=> Array( 'label' => __( "Demo 1", 'javo' ), 'div' => ' active', 'input' => 'checked'  )
			, 'javo-demo2'	=> Array( 'label' => __( "Demo 2", 'javo' ), 'div' => '', 'input' => '' )
			, 'javo-demo4'	=> Array( 'label' => __( "Demo 4", 'javo' ), 'div' => '', 'input' => '' )
			/*
			, 'javo-demo3'	=> Array( 'label' => __( "Demo 3", 'javo' ), 'div' => '', 'input' => '' )
			, 'javo-demo4'	=> Array( 'label' => __( "Demo 4", 'javo' ), 'div' => '', 'input' => '' )
			, 'javo-demo5'	=> Array( 'label' => __( "Demo 5", 'javo' ), 'div' => '', 'input' => '' )
			*/
		); ?>

        <div id="jv-metaboxes-general" class="wrap jv-a-page jv-a-page-info">
            <h2 class="jv-a-page-title"><?php _e('Javo One Click Import', 'javo') ?></h2>
            <form method="post" action="" id="importContentForm">
                <div class="jv-a-page-form">
                    <div class="jv-a-page-form-section-holder clearfix">
                        <p class="jv-a-page-section-title">Please select one of them and click Import.</p>
						<p class="" style="background:#FF5A5F; padding:10px; color:#fff; max-width:700px; width:100%;">Please make sure that you need to activate "Required plugins". <a href="wp-admin/admin.php?page=javo-spot_plugins" target="_blank" style="color:#fff; font-weight:bold;">Go to check</a>. ( <a href="http://javothemes.com/spot/documentation/plugin-install/" style="color:#fff;">Tutorial</a> )<br>
						Please enable "Optimized CSS, Optimized JS" on ultimate addons (plugin required). <a href="wp-admin/admin.php?page=ultimate-dashboard#css-settings" target="_blank" style="color:#fff; font-weight:bold;">Go to enable</a>. ( <a href="http://javothemes.com/spot/documentation/ultimate-vc-addons-optimized-css-js/" style="color:#fff;">Tutorial</a> )</p>
                        <div class="jv-a-page-form-section">
                            <div class="jv-a-section-content">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-lg-3">
											<div id="jv-importer-demo-selector">
												<?php
												if( !empty( $arrDemoSelector ) ) foreach( $arrDemoSelector as $optionName => $optionMeta )
													echo "
														<div class=\"jv-demo-item {$optionMeta[ 'div' ]}\">
															<label>
																<i></i>
																<span>
																	<input type='radio' name='import_example' value='{$optionName}' {$optionMeta[ 'input' ]}>
																	{$optionMeta[ 'label' ]}
																</span>
															</label>
														</div>
													";
												?>
											</div>
                                        </div>
                                        <div class="col-lg-3">
                                            <em class="jv-a-field-description">Import Type</em>
                                            <select name="import_option" id="import_option" class="form-control jv-a-form-element">
                                                <option value="">Please Select One</option>
                                                <option value="complete_content">All</option>
                                                <option value="content">Content</option>
                                                <option value="widgets">Widgets</option>
                                                <option value="menus">Menus</option>
                                                <option value="theme_settings">Theme Settings</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="jv-a-page-form-section" >
                            <div class="jv-a-field-desc">
                                <h4><?php esc_html_e('Import attachments', 'javo'); ?></h4>
                                <p>Do you want to import media files?</p>
                            </div>

                            <div class="jv-a-section-content">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <input type="checkbox" value="1" class="jv-a-form-element" name="import_attachments" id="import_attachments" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-button-section clearfix">
                                    <input type="submit" class="btn btn-primary btn-sm " value="Import" name="import" id="import_demo_data" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3"></div>

                        </div>

                        <div class="import_load"><span><?php _e('The import process may take some time. Please be patient.', 'javo') ?> </span><br />
                            <div class="jv-progress-bar-wrapper html5-progress-bar">
                                <div class="progress-bar-wrapper">
                                    <progress id="progressbar" value="0" max="100"></progress>
                                </div>
                                <div class="progress-value">0%</div>
                                <div class="progress-bar-message">
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-warning">
                            <strong><?php _e('Important notes:', 'javo') ?></strong>
                            <ul>
                                <li><?php _e('Please note that import process will take time needed to download all attachments from demo web site.', 'javo'); ?></li>
                                <li> <?php _e('Please make sure that you have installed all of plugins you need.', 'javo')?></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <script type="text/javascript">
		jQuery( function( $ ){
			"use strict";

			$(document)
				.on( 'change', 'input[name="import_example"]',
					function( e ) {
						var
							container	= $( this ).closest( "#jv-importer-demo-selector" );

						$( ".jv-demo-item", container ).removeClass( 'active' );
						$( this ).closest( '.jv-demo-item' ).addClass( 'active' );
					}
				)
				.on('click', '#import_demo_data', function(e)
				{
                    e.preventDefault();

                    if( $( "#import_option" ).val() == "" ) {
                    	alert('Please select Import Type.');
                    	return false;
                    }

                    if (confirm('Are you sure, you want to import Demo Data now?')) {

                        $('.import_load').css('display','block');

                        var progressbar		= $('#progressbar')
                        var import_opt		= $( "#import_option" ).val();
                        var import_expl		= $( "input[name='import_example']:checked" ).val();

                        var p = 0;
                        if( import_opt == 'content' ){
                            for( var i=1; i<10; i++ ){
                                var str;
                                if(i < 10) str = 'jvfrm_spot_demo_content_0'+i+'.xml';
                                else str = 'jvfrm_spot_demo_content_'+i+'.xml';

                                jQuery.ajax({
                                    type: 'POST',
                                    url: ajaxurl,
                                    data: {
                                        action: 'jvfrm_spot_dataImport',
                                        xml: str,
                                        example: import_expl,
                                        import_attachments: ($("#import_attachments").is(':checked') ? 1 : 0)
                                    },
                                    success: function(data, textStatus, XMLHttpRequest){
                                        p+= 10;
                                        $('.progress-value').html((p) + '%');
                                        progressbar.val(p);
                                        if (p == 90) {
                                            str = 'jvfrm_spot_demo_content_10.xml';
                                            jQuery.ajax({
                                                type: 'POST',
                                                url: ajaxurl,
                                                data: {
                                                    action: 'jvfrm_spot_dataImport',
                                                    xml: str,
                                                    example: import_expl,
                                                    import_attachments: ($("#import_attachments").is(':checked') ? 1 : 0)
                                                },
                                                success: function(data, textStatus, XMLHttpRequest){
                                                    p+= 10;
                                                    $('.progress-value').html((p) + '%');
                                                    progressbar.val(p);
                                                    $('.progress-bar-message').html('<div class="alert alert-success"><strong>Import is completed</strong></div>');
                                                },
                                                error: function(MLHttpRequest, textStatus, errorThrown){
                                                }
                                            });
                                        }
                                    },
                                    error: function(MLHttpRequest, textStatus, errorThrown){
                                    }
                                });
                            }
                        } else if(import_opt == 'widgets') {
                            jQuery.ajax({
                                type: 'POST',
                                url: ajaxurl,
                                data: {
                                    action: 'jvfrm_spot_widgetsImport',
                                    example: import_expl
                                },
                                success: function(data, textStatus, XMLHttpRequest){
                                    $('.progress-value').html((100) + '%');
                                    progressbar.val(100);
                                },
                                error: function(MLHttpRequest, textStatus, errorThrown){
                                }
                            });
                            $('.progress-bar-message').html('<div class="alert alert-success"><strong>Import is completed</strong></div>');
                        } else if(import_opt == 'menus'){
							 jQuery.ajax({
								xhr: function()
								{
									var xhr;

									if( window.XMLHttpRequest )
									{
										xhr = new window.XMLHttpRequest();
									}else{
										xhr = new ActiveXObject( "Microsoft.XMLHTTP" );
									}

									xhr.upload.addEventListener( 'progress', function( e ){
										if( e.lengthComputable )
										{
											progressbar.val( e.loaded / e.total * 50 );
											console.log( e.loaded / e.total * 50 );
										}
									}, false );

									xhr.addEventListener( 'progress', function( e ){
										if( e.lengthComputable )
										{
											progressbar.val( e.loaded / e.total * 50 );
											console.log( e.loaded / e.total * 50 );
										}
									}, false );

									return xhr;
								}
								, type: 'POST',
                                url: ajaxurl,
                                data: {
                                    action: 'jvfrm_spot_menusImport',
                                    example: import_expl
                                },
                                success: function(data, textStatus, XMLHttpRequest){
									console.log( data );
                                    $('.progress-value').html((100) + '%');
                                    progressbar.val(100);
                                },
                                error: function(MLHttpRequest, textStatus, errorThrown){
                                }
                            });
                            $('.progress-bar-message').html('<div class="alert alert-success"><strong>Import is completed</strong></div>');

                        } else if(import_opt == 'theme_settings'){
                            jQuery.ajax({
                                type: 'POST',
                                url: ajaxurl,
                                data: {
                                    action: 'jvfrm_spot_optionsImport',
                                    example: import_expl
                                },
                                success: function(data, textStatus, XMLHttpRequest){
                                    $('.progress-value').html((100) + '%');
                                    progressbar.val(100);
                                },
                                error: function(MLHttpRequest, textStatus, errorThrown){
                                }
                            });
                            $('.progress-bar-message').html('<div class="alert alert-success"><strong>Import is completed</strong></div>');
                        }else if(import_opt == 'complete_content'){
                            for(var i=1;i<10;i++){
                                var str;
                                if (i < 10) str = 'jvfrm_spot_demo_content_0'+i+'.xml';
                                else str = 'jvfrm_spot_demo_content_'+i+'.xml';
                                jQuery.ajax({
                                    type: 'POST',
                                    url: ajaxurl,
                                    data: {
                                        action: 'jvfrm_spot_dataImport',
                                        xml: str,
                                        example: import_expl,
                                        import_attachments: ($("#import_attachments").is(':checked') ? 1 : 0)
                                    },
                                    success: function(data, textStatus, XMLHttpRequest){
                                        p+= 10;
                                        $('.progress-value').html((p) + '%');
                                        progressbar.val(p);
                                        if (p == 90) {
                                            str = 'jvfrm_spot_demo_content_10.xml';
                                            jQuery.ajax({
                                                type: 'POST',
                                                url: ajaxurl,
                                                data: {
                                                    action: 'jvfrm_spot_dataImport',
                                                    xml: str,
                                                    example: import_expl,
                                                    import_attachments: ($("#import_attachments").is(':checked') ? 1 : 0)
                                                },
                                                success: function(data, textStatus, XMLHttpRequest){
                                                    jQuery.ajax({
                                                        type: 'POST',
                                                        url: ajaxurl,
                                                        data: {
                                                            action: 'jvfrm_spot_otherImport',
                                                            example: import_expl
                                                        },
                                                        success: function(data, textStatus, XMLHttpRequest){

																console.log( data );
                                                            $('.progress-value').html((100) + '%');
                                                            progressbar.val(100);
                                                            $('.progress-bar-message').html('<div class="alert alert-success">Import is completed.</div>');
                                                        },
                                                        error: function(MLHttpRequest, textStatus, errorThrown){
                                                        }
                                                    });
                                                },
                                                error: function(MLHttpRequest, textStatus, errorThrown){
                                                }
                                            });
                                        }
                                    },
                                    error: function(MLHttpRequest, textStatus, errorThrown){
                                    }
                                });
                            }
                        }
                    }
                    return false;
                });
			});
        </script>
        </div>
    <?php
	do_action( 'jvfrm_spot_admin_helper_page_footer' );
	do_action( 'jvfrm_spot_admin_helper_import_footer' );
	}
}

if(!function_exists('jvfrm_spot_dataImport'))
{
    function jvfrm_spot_dataImport()
    {
        global $jvfrm_spot_Import;

        if ($_POST['import_attachments'] == 1)
            $jvfrm_spot_Import->attachments = true;
        else
            $jvfrm_spot_Import->attachments = false;

        $folder = "javo-demo/";
        if (!empty($_POST['example']))
            $folder = $_POST['example']."/";
        $jvfrm_spot_Import->import_content($folder.$_POST['xml']);

        die();
    }

    add_action('wp_ajax_jvfrm_spot_dataImport', 'jvfrm_spot_dataImport');
}

if(!function_exists('jvfrm_spot_widgetsImport'))
{
    function jvfrm_spot_widgetsImport()
    {
        global $jvfrm_spot_Import;

        $folder = "javo-demo/";
        if (!empty($_POST['example']))
            $folder = $_POST['example']."/";

        $jvfrm_spot_Import->import_widgets($folder.'widgets.txt');

        die();
    }

    add_action('wp_ajax_jvfrm_spot_widgetsImport', 'jvfrm_spot_widgetsImport');
}

if(!function_exists('jvfrm_spot_menusImport'))
{
    function jvfrm_spot_menusImport()
    {
        global $jvfrm_spot_Import;

        $folder = "javo-demo/";
        if (!empty($_POST['example']))
            $folder = $_POST['example']."/";

        $jvfrm_spot_Import->import_menus($folder.'menus.txt');

        die();
    }

    add_action('wp_ajax_jvfrm_spot_menusImport', 'jvfrm_spot_menusImport');
}




if(!function_exists('jvfrm_spot_optionsImport'))
{
    function jvfrm_spot_optionsImport()
    {
        global $jvfrm_spot_Import;

        $folder = "javo-demo/";
        if (!empty($_POST['example']))
            $folder = $_POST['example']."/";

        $jvfrm_spot_Import->import_theme_settings($folder.'jvfrm_spot_themes_settings.txt');

        die();
    }

    add_action('wp_ajax_jvfrm_spot_optionsImport', 'jvfrm_spot_optionsImport');
}

if(!function_exists('jvfrm_spot_otherImport'))
{
    function jvfrm_spot_otherImport()
    {
        global $jvfrm_spot_Import;

        $folder = "javo-demo/";
        if (!empty($_POST['example']))
            $folder = $_POST['example']."/";

        $jvfrm_spot_Import->import_theme_settings($folder.'jvfrm_spot_themes_settings.txt');
        $jvfrm_spot_Import->import_widgets($folder.'widgets.txt');
        $jvfrm_spot_Import->import_menus($folder.'menus.txt');
        $jvfrm_spot_Import->import_settings_pages($folder.'settingpages.txt');

        die();
    }

    add_action('wp_ajax_jvfrm_spot_otherImport', 'jvfrm_spot_otherImport');
}