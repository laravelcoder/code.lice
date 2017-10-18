<?php
/*
Template Name: Login Page
*/

global $dynamo_tpl;

dp_load('header');
dp_load('before');

?>

<section id="dp-mainbody" class="loginpage">
	<?php the_post(); ?>
	
		<header>
			<?php get_template_part( 'layouts/content.post.header' ); ?>
		</header>
	
	<article>
		<section class="intro">
			<?php the_content(); ?>
		</section>
		
		<?php if ( is_user_logged_in() ) : ?>
			<?php 
			$current_user = wp_get_current_user();
			
			?>
			
			<p>
				<?php echo __('Hi, ', 'dp-theme') . ($current_user->user_firstname) . ' ' . ($current_user->user_lastname) . ' (' . ($current_user->user_login) . ') '; ?>
				 <a href="<?php echo wp_logout_url(); ?>" title="<?php __('Logout', 'dp-theme'); ?>">
					 <?php __('Logout', 'dp-theme'); ?>
				 </a>
			</p>
		
		<?php else : ?>
		    
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
			?>
			
			<nav class="small">
				<ul>
					<li>
						<a href="<?php echo home_url(); ?>/wp-login.php?action=lostpassword" title="<?php __('Password Lost and Found', 'dp-theme'); ?>"><?php __('Lost your password?', 'dp-theme'); ?></a>
					</li>
					<li>
						<a href="<?php echo home_url(); ?>/wp-login.php?action=register" title="<?php __('Not a member? Register', 'dp-theme'); ?>"><?php __('Register', 'dp-theme'); ?></a>
					</li>
				</ul>
			</nav>
		
		<?php endif; ?>
	
	</article>
</section>

<?php

dp_load('after');
dp_load('footer');

// EOF