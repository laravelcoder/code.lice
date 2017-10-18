<?php

// disable direct access to the file	
defined('DYNAMO_WP') or die('Access denied');

global $dynamo_tpl;

?>

<?php if(get_option($dynamo_tpl->name . '_login_link', 'Y') == 'Y') : ?>
<div id="dp-popup-login">	
	<div class="dp-popup-wrap">
		<?php if ( is_user_logged_in() ) : ?>
			<?php 
				
				global $current_user;
				get_currentuserinfo();
			
			?>
<div id="logoutscreen">
<h3><?php __('Log out', 'dp-theme'); ?></h3>  			
			<p>
				<?php echo __('Hi, ', 'dp-theme') . ($current_user->user_firstname) . ' ' . ($current_user->user_lastname) . ' (' . ($current_user->user_login) . ') '; ?>
			</p>
			<p>
				 <a href="<?php echo wp_logout_url(); ?>" class="button" title="<?php __('Logout', 'dp-theme'); ?>">
					 <?php __('Logout', 'dp-theme'); ?>
				 </a>
			</p>
            </div>
		
		<?php else : ?>

<div id="loginscreen">
		<h3><?php __('Log in', 'dp-theme'); ?></h3>    
			<?php 
				wp_login_form(
					array(
						'echo' => true,
						'form_id' => 'loginform',
						'label_username' => __( 'Username', 'dp-theme' ),
						'label_password' => __( 'Password', 'dp-theme' ),
						'label_remember' => __( 'Remember Me', 'dp-theme' ),
						'label_log_in' => __( 'Log In', 'dp-theme' ),
						'id_username' => 'user_login',
						'id_password' => 'user_pass',
						'id_remember' => 'rememberme',
						'id_submit' => 'wp-submit',
						'remember' => true,
						'value_username' => NULL,
						'value_remember' => false 
					)
				); 
			?><?php wp_register('', ''); ?>
<p><a href="<?php echo wp_registration_url(); ?>"><?php _e( 'New user?','dp-theme'); ?></a> | <a href="<?php echo wp_lostpassword_url(); ?>"><?php _e( 'Forgot your password?','dp-theme'); ?></a> </p>
</div>            		
		<?php endif; ?>
	</div>
</div>

<div id="dp-popup-overlay"></div>
<?php endif; ?>