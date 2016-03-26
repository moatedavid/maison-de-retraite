<?php
/************************************************
**	Ajax Process
************************************************/
class jvfrm_spot_ajax_propcess{

	public function __construct()
	{
		// lister contact mail
		add_action("wp_ajax_nopriv_send_mail", Array($this, "send_mail"));
		add_action("wp_ajax_send_mail", Array($this, "send_mail"));

		// Register
		add_action("wp_ajax_nopriv_register_login_add_user", Array($this, "add_user_callback"));
		add_action("wp_ajax_register_login_add_user", Array($this, "add_user_callback"));

		add_action("wp_ajax_nopriv_jvfrm_spot_ajax_user_login"	, Array($this, "jvfrm_spot_ajax_user_login"));
		add_action("wp_ajax_jvfrm_spot_ajax_user_login"			, Array($this, "jvfrm_spot_ajax_user_login"));
	}

	public function add_user_callback()
	{
		$jvfrm_spot_query = new jvfrm_spot_array( $_POST );
		$jvfrm_spot_this_result = Array();
		$jvfrm_spot_new_user_args = Array('user_pass'=>null);

		if( isset( $_POST['user_login'] ) ){
			$jvfrm_spot_new_user_args['user_login'] = $jvfrm_spot_query->get('user_login');
		}
		if( isset( $_POST['user_name'] ) ){
			$jvfrm_spot_user_fullname	 = (Array) @explode(' ', $_POST['user_name']);

			$jvfrm_spot_new_user_args['first_name'] = $jvfrm_spot_user_fullname[0];

			if(
				!empty( $jvfrm_spot_user_fullname[1] ) &&
				$jvfrm_spot_user_fullname[1] != ''
			){
				$jvfrm_spot_new_user_args['last_name'] = $jvfrm_spot_user_fullname[1];
			}
		}

		if( isset( $_POST['first_name'] ) ){
			$jvfrm_spot_new_user_args['first_name'] = $jvfrm_spot_query->get('first_name');
		}
		if( isset( $_POST['last_name'] ) ){
			$jvfrm_spot_new_user_args['last_name'] = $jvfrm_spot_query->get('last_name');
		}
		if( isset( $_POST['user_pass'] ) ){
			$jvfrm_spot_new_user_args['user_pass'] = $jvfrm_spot_query->get('user_pass');

		}else{
			// Password is Empty ???
			$jvfrm_spot_new_user_args['user_pass'] = wp_generate_password( 12, false );
		}
		if( isset( $_POST['user_login'] ) ){
			$jvfrm_spot_new_user_args['user_email'] = $jvfrm_spot_query->get('user_email');
		}

		$user_id = wp_insert_user($jvfrm_spot_new_user_args, true);
		if( !is_wp_error($user_id) ){
			update_user_option( $user_id, 'default_password_nag', true, true );
			wp_new_user_notification($user_id, $jvfrm_spot_new_user_args['user_pass']);

			// Assign Post
			if( isset( $_POST['post_id'] ) && (int)$_POST['post_id'] > 0 ){
				$origin_post_id		= (int) $_POST['post_id'];
				$parent_post_id		= (int)get_post_meta( $origin_post_id, 'parent_post_id', true);

				$post_id = wp_update_post(Array(
					'ID'			=> $parent_post_id
					, 'post_author'	=> $user_id
				));

				update_post_meta($origin_post_id	, 'approve', 'approved');
				update_post_meta($post_id			, 'claimed', 'yes');
			}
			do_action( 'jvfrm_spot_new_user_append_meta', $user_id );
			$jvfrm_spot_this_result['state'] = 'success';

		}else{
			$jvfrm_spot_this_result['state']		= 'failed';
			$jvfrm_spot_this_result['comment']	= $user_id->get_error_message();

		}
		echo json_encode($jvfrm_spot_this_result);
		exit;
	}

	static function send_mail_content_type(){ return 'text/html';	}
	public function send_mail(){
		$jvfrm_spot_query					= new jvfrm_spot_array( $_POST );
		$jvfrm_spot_this_return			= Array();
		$jvfrm_spot_this_return['result'] = false;
		$meta = Array(
			'to'					=> $jvfrm_spot_query->get('to', NULL)
			, 'subject'				=> $jvfrm_spot_query->get('subject', esc_html__('Untitled Mail', 'javospot')).' : '.get_bloginfo('name')
			, 'from'				=> sprintf("From: %s<%s>\r\n"
										, get_bloginfo('name')
										, $jvfrm_spot_query->get('from', get_option('admin_email') )
									)
			, 'content'				=> $jvfrm_spot_query->get('content', NULL)
		);

		if(
			$jvfrm_spot_query->get('to', NULL) != null &&
			$jvfrm_spot_query->get('from', NULL) != null
		){
			add_filter( 'wp_mail_content_type', Array(__CLASS__, 'send_mail_content_type') );
			$mailer = wp_mail(
				$meta['to']
				, $meta['subject']
				, $meta['content']
				, $meta['from']
			);
			$jvfrm_spot_this_return['result'] = $mailer;
			remove_filter( 'wp_mail_content_type', Array(__CLASS__, 'send_mail_content_type'));
		};

		echo json_encode($jvfrm_spot_this_return);
		exit(0);
	}

	public function jvfrm_spot_ajax_user_login()
	{
		header( 'content-type:application/json; charset=utf-8' );
		check_ajax_referer( 'user_login', 'security' );

		$response		= Array();
		$query			= new jvfrm_spot_array( $_POST );
		$login_state	= wp_signon(
			Array(
				'user_login'		=> $query->get( 'log' )
				, 'user_password'	=> $query->get( 'pwd' )
				, 'remember'		=> $query->get( 'rememberme' )
			) , false
		);

		if( is_wp_error( $login_state ) ) {
			$response[ 'error' ]	= $login_state->get_error_message();
		}else{
			wp_set_current_user( $login_state->ID );
			wp_set_auth_cookie( $login_state->ID );
			do_action( 'wp_login', $login_state->user_login );
			$response[ 'state' ]	= 'OK';
		}
		die( json_encode( $response ) ) ;
	}
}
new jvfrm_spot_ajax_propcess;