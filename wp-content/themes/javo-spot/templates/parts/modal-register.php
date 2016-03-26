<!-- Modal -->
<div class="modal fade" id="register_panel" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="register_panelLabel">
	<div class="modal-dialog">
		<div class="modal-content">
			<form data-javo-modal-register-form>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="register_panelLabel">
						<?php esc_html_e('Create Account', 'javospot');?>
					</h4>
				</div>
				<div class="modal-body">
					<?php do_action( 'jvfrm_spot_register_form_before' ); ?>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="" for="reg-username"><?php esc_html_e('Username', 'javospot');?></label>
								<input type="text" id="reg-username" name="user_login" class="form-control" required="" placeholder="<?php esc_attr_e( 'Username', 'javospot' );?>">
							</div>
						</div><!-- /.col-md-6 -->
						<div class="col-md-6">
							<div class="form-group">
								<label class="" for="reg-email"><?php esc_html_e('Email Address', 'javospot');?></label>
								<input type="text" id="reg-email" name="user_email" class="form-control" required="" placeholder="<?php esc_attr_e( 'Your email', 'javospot' );?>">
							</div>
						</div><!-- /.col-md-6 -->
					</div><!-- /.row -->
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="" for="firstname"><?php esc_html_e('First Name', 'javospot');?></label>
								<input type="text" id="firstname" name="first_name" class="form-control" required="" placeholder="<?php esc_attr_e( 'Your first name', 'javospot' );?>">

							</div>
						</div><!-- /.col-md-6 -->
						<div class="col-md-6">
							<div class="form-group">
								<label class="" for="lastname"><?php esc_html_e('Last Name', 'javospot');?></label>
								<input type="text" id="lastname" name="last_name" class="form-control" required="" placeholder="<?php esc_attr_e( 'Your last name', 'javospot' );?>">

							</div>
						</div><!-- /.col-md-6 -->
					</div><!-- /.row -->
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="" for="reg-password"><?php esc_html_e('Password', 'javospot');?></label>
								<input type="password" id="reg-password" name="user_pass" class="form-control" required="" placeholder="<?php esc_attr_e( 'Desired Password', 'javospot' );?>">

							</div>
						</div><!-- /.col-md-6 -->
						<div class="col-md-6">
							<div class="form-group">
								<label class="" for="reg-con-password"><?php esc_html_e('Confirm Password', 'javospot');?></label>
								<input type="password" id="reg-con-password" name="user_con_pass" class="form-control" required="" placeholder="<?php esc_attr_e( 'Confirm Password', 'javospot' );?>">

							</div>
						</div><!-- /.col-md-6 -->
					</div><!-- /.row -->
					<?php do_action( 'jvfrm_spot_register_form_after' ); ?>
				</div>

				<div class="modal-footer">

					<?php
					if( $agree_page = jvfrm_spot_tso()->get( 'agree_register', 0 ) )
					{
						echo "<div class=\"row\">";
							echo "<div class=\"col-md-12\">";
								echo "<div class=\"checkbox\">";
									echo "<label>";
										echo "<input type=\"checkbox\" class=\"javo-register-agree\">";
										printf(
											"<a href=\"%s\">%s</a>"
											, apply_filters( 'jvfrm_spot_wpml_link', $agree_page )
											, esc_html__( "I agree with the terms and conditions.", 'javospot' )
										);
									echo "</label>";
								echo "</div>";
							echo "</div>";
						echo "</div>";
					} ?>

					<div class="row">
						<div class="col-md-4 hidden-xs col-xs-4 text-left">
							<a href="#" data-toggle="modal" data-target="#login_panel" class="btn btn-default javo-tooltip" title="" data-original-title="Log-In"><?php esc_html_e('LOG IN', 'javospot' ); ?></a>

						</div><!-- /.col-md-4 -->
						<div class="col-md-8 text-right">
							<input type="hidden" name="action" value="register_login_add_user">
							<button type="submit" id="signup" name="submit" class="btn btn-primary"><?php esc_html_e('Create My Account', 'javospot');?></button> &nbsp;
							<button type="button" class="btn btn-default" data-dismiss="modal"><?php esc_html_e('Close', 'javospot');?></button>

						</div><!-- /.col-md-8 -->
					</div><!-- /.row -->
				</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<fieldset>
	<input type="hidden" name="jvfrm_spot_str_disagree" value="<?php esc_html_e( "You need to read and agree the terms and conditions to register.", 'javospot' ); ?>">
</fieldset>

<script type="text/javascript">
(function($){
	"use strict";
	$('body')
		.on('submit', '[data-javo-modal-register-form]', function(e){
			e.preventDefault();
			var $this = $(this);
			$(this).find('input').each(function(){
				if( $(this).val() == ""){
					if( ! $( this ).hasClass( 'no-require' ) )
						$(this).addClass('isNull');
				}else{
					$(this).removeClass('isNull');
				}
			});

			if( $(this).find('[name="user_pass"]').val() != $(this).find('[name="user_con_pass"]').val() ){
				$(this).find('[name="user_pass"], [name="user_con_pass"]').addClass('isNull');
				return false;
			}else{
				$(this).find('[name="user_pass"], [name="user_con_pass"]').removeClass('isNull');
			};
			if( $(this).find('[name="user_login"]').get(0).value.match(/\s/g) ){
				var str = "<?php esc_html_e('usernames with spaces should not be allowed.', 'javospot');?>";
				$.jvfrm_spot_msg({ content:str }, function(){ $(this).find('[name="user_login"]').focus(); });
				$(this).find('[name="user_login"]').addClass('isNull');
			}

			if( $(this).find('.isNull').length > 0 ) return false;

			if( $( ".javo-register-agree" ).length > 0 )
				if( ! $( ".javo-register-agree" ).is( ":checked" ) ) {
					$.jvfrm_spot_msg({ content: $( "[name='jvfrm_spot_str_disagree']" ).val() });
					return false;
				}

			$(this).find('[type="submit"]').button('loading');

			$.ajax({
				url:"<?php echo admin_url('admin-ajax.php');?>"
				, type:'post'
				, data: $(this).serialize()
				, dataType:'json'
				, error: function(e){  }
				, success: function(d){
					if( d.state == 'success'){
						$.jvfrm_spot_msg({content:"<?php esc_html_e('Register Complete', 'javospot');?>", delay:3000}, function(){
							document.location.reload();
						});
					}else{
						$.jvfrm_spot_msg({ content:"<?php esc_html_e('User Register failed. Please check duplicate email or Username', 'javospot');?>", delay:100000 });
					}
					$this.find('[type="submit"]').button('reset');
				}
			});
		}).on('click', 'a[data-target="#register_panel"]', function(e){
			$(this).closest('.modal').modal('hide');
		}).on('click', 'a[data-target="#login_panel"]', function(e){
			$(this).closest('.modal').modal('hide');
		});
})(jQuery);
</script>