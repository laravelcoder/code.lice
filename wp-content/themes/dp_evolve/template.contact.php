<?php

/*
Template Name: Contact Form
*/

global $dynamo_tpl;

//
// check if reCAPTCHA isn't loaded earlier by other plugin
if(!function_exists('_recaptcha_qsencode')) {
	include_once('dynamo_framework/classes/class.recaptchalib.php');
}
// get the params
$params = get_post_custom();
$params_templates = isset($params['dynamo-post-params-templates']) ? $params['dynamo-post-params-templates'][0] : false;


if($params_templates) {
	$params_templates = unserialize(unserialize($params_templates));
	$params_contact = $params_templates['contact'];


	if($params_contact != '' && count($params_contact) > 0) {
		$params_contact = explode(',', $params_contact); // [0] - name, [1] - e-mail, [2] - send copy   
	}
}


$params_name = true;
$params_email = true;
$params_copy = true;


if(count($params_contact) == 3) {
	$params_name = $params_contact[0] == 'Y';
	$params_email = $params_contact[1] == 'Y';
	$params_copy = $params_contact[2] == 'Y';
}

// flag used to detect if the page is validated
$validated = true;
// flag to detect if e-mail was sent
$messageSent = false;
// variable to store the errors, empty string means no error 
$errors = array(
	"name" => '',
	"email" => '',
	"message" => '',
	"recaptcha" => ''
);
// variable for the input fields output
$output = array(
	"name" => '',
	"email" => '',
	"message" => ''
);
// if the form was sent
if(isset($_POST['message-send'])) {
	// check the name
	if($params_name) {
		if(trim($_POST['contact-name']) === '') {
			$validated = false;
			$errors['name'] = __('please enter your name', 'dp-theme');
		} else {
			$output['name'] = trim($_POST['contact-name']);
		}
	}
	// check the e-mail
	if($params_email) {
		if(trim($_POST['email']) === '' || !eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", trim($_POST['email']))) {
			$validated = false;
			$errors['email'] = __('please enter correct email address.', 'dp-theme');
		} else {
			$output['email'] = trim($_POST['email']);
		}
	}
	// check the message content
	if(trim($_POST['comment-text']) === '') {
		$validated = false;
		$errors['message'] = __('please enter a text of the message.', 'dp-theme');
	} else {
		$output['message'] = htmlentities(stripslashes(trim(htmlspecialchars($_POST['comment-text']))), ENT_QUOTES, 'utf-8');
	}
	// reCAPTCHA validation
	if(
		get_option($dynamo_tpl->name . '_recaptcha_state', 'N') == 'Y' && 
		get_option($dynamo_tpl->name . '_recaptcha_public_key', '') != '' &&
		get_option($dynamo_tpl->name . '_recaptcha_private_key', '') != ''
	) {
		$privatekey = get_option($dynamo_tpl->name . '_recaptcha_private_key', '');
		$resp = recaptcha_check_answer ($privatekey,
		                            $_SERVER["REMOTE_ADDR"],
		                            $_POST["recaptcha_challenge_field"],
		                            $_POST["recaptcha_response_field"]);
		
		if (!$resp->is_valid) {
			// What happens when the CAPTCHA was entered incorrectly
			$validated = false;
			$errors['recaptcha'] = __("The reCAPTCHA wasn't entered correctly. Go back and try it again.", 'dp-theme');
		}
	}
	// if the all fields was correct
	if($validated) {
		// send an e-mail
		$email = get_option($dynamo_tpl->name . '_contact_template_email', '');
		// if the user specified blank e-mail or not specified it
		if(trim($email) == '') {
			$email = get_option('admin_email');
		}
		// e-mail structure
		if($params_name) {
			$subject = __('From ', 'dp-theme') . $output['name'];
		} else if(!$params_name && $params_email) {
			$subject = __('From ', 'dp-theme') . $output['email'];
		} else {
			$subject = __('From ', 'dp-theme') . get_bloginfo('name');
		}


		$body = "<html>";
		$body .= "<body>";
		$body .= "<h1 style=\"font-size: 24px; border-bottom: 4px solid #EEE; margin: 10px 0; padding: 10px 0; font-weight: normal; font-style: italic;\">".__('Message from', 'dp-theme')." <strong>".get_bloginfo('name')."</strong></h1>";


		if($params_name) {
			$body .= "<div>";
			$body .= "<h2 style=\"font-size: 16px; font-weight: normal; border-bottom: 1px solid #EEE; padding: 5px 0; margin: 10px 0;\">".__('Name:', 'dp-theme')."</h2>";
			$body .= "<p>".$output['name']."</p>";
			$body .= "</div>";
		}


		if($params_email) {
			$body .= "<div>";
			$body .= "<h2 style=\"font-size: 16px; font-weight: normal; border-bottom: 1px solid #EEE; padding: 5px 0; margin: 10px 0;\">".__('E-mail:', 'dp-theme')."</h2>";
			$body .= "<p>".$output['email']."</p>";
			$body .= "</div>";
		}


		$body .= "<div>";
		$body .= "<h2 style=\"font-size: 16px; font-weight: normal; border-bottom: 1px solid #EEE; padding: 5px 0; margin: 10px 0;\">".__('Message:', 'dp-theme')."</h2>";
		$body .= $output['message'];
		$body .= "</div>";
		$body .= "</body>";
		$body .= "</html>";


				if($params_name && $params_email) {
                        $headers[] = 'From: '.$output['name'].' <'.$output['email'].'>';
                        $headers[] = 'Reply-To: ' . $output['email'];
                        $headers[] = 'Content-type: text/html';
                } else if($params_name && !$params_email) {
                        $headers[] = 'From: '.$output['name'];
                        $headers[] = 'Content-type: text/html';
                } else if(!$params_name && $params_email) {
                        $headers[] = 'From: '.$output['email'].' <'.$output['email'].'>';
                        $headers[] = 'Reply-To: ' . $output['email'];
                        $headers[] = 'Content-type: text/html';
                } else {
                        $headers[] = 'Content-type: text/html';
                }

		wp_mail($email, $subject, $body, $headers);
		
		if($params_copy && $params_email && isset($_POST['send_copy'])) { 
			wp_mail($output['email'], $subject, $body, $headers);
		}
		
		$messageSent = true;
	}

} 

dp_load('header');
dp_load('before');

?>

<section id="dp-mainbody" class="contactpage">
	<?php the_post(); ?>
	
		<header>
			<?php get_template_part( 'layouts/content.post.header' ); ?>
		</header>
	
	<article>
		<section class="intro">
			<?php the_content(); ?>
		</section>
	
		<?php if($messageSent == true) : ?>
		<p class="dp-contact-thanks"><?php __('Your message was sent to us successfully.', 'dp-theme'); ?></p>
		<p><a href="<?php echo home_url(); ?>"><?php __('Back to the homepage', 'dp-theme'); ?></a></p>
		<?php else : ?>
		
			<?php if(!$validated) : ?>
			<p class="dp-contact-error"><?php __('Sorry, an error occured.', 'dp-theme'); ?></p>
			<?php endif; ?>
		
			<form action="<?php the_permalink(); ?>" id="dp-contact" method="post">
				<dl>
                <?php if($params_name) : ?>
					<dt>
						<label for="contact-name"><?php __('Name:', 'dp-theme'); ?></label>
						<?php if($errors['name'] != '') : ?>
						<span class="error"><?php echo $errors['name'];?></span>
						<?php endif; ?>
					</dt>
					<dd>	
						<input type="text" name="contact-name" id="contact-name" value="<?php echo $output['message'];?>" />
					</dd>
				<?php endif; ?>
                <?php if($params_email) : ?>
					<dt>
						<label for="email"><?php __('Email:', 'dp-theme'); ?></label>
						<?php if($errors['email'] != '') : ?>
						<span class="error"><?php echo $errors['email'];?></span>
						<?php endif; ?>
					</dt>
					<dd>	
						<input type="text" name="email" id="email" value="<?php echo $output['email'];?>" />
					</dd>
				<?php endif; ?>
					<dt>
						<label for="comment-text"><?php __('Message:', 'dp-theme'); ?></label>
						<?php if($errors['message'] != '') : ?>
						<span class="error"><?php echo $errors['message'];?></span>
						<?php endif; ?>
					</dt>
					<dd>
						<textarea name="comment-text" id="comment-text" rows="6" cols="30"><?php echo $output['message']; ?></textarea>
					</dd>
				</dl>
                <?php if($params_copy && $params_email) : ?> 
				<p>
					<label>
						<input type="checkbox" name="send_copy" /> 
						<?php __('Send copy of the message to yourself', 'dp-theme'); ?>
					</label>
				</p>
				<?php endif; ?>
				<?php 
					if(
						get_option($dynamo_tpl->name . '_recaptcha_state', 'N') == 'Y' && 
						get_option($dynamo_tpl->name . '_recaptcha_public_key', '') != '' &&
						get_option($dynamo_tpl->name . '_recaptcha_private_key', '') != ''
					) : ?>
				<p>
					<script type="text/javascript">var RecaptchaOptions = { theme : 'clean' };</script>
					<?php if($errors['recaptcha'] != '') : ?>
					<span class="error"><?php echo $errors['recaptcha'];?></span>
					<?php endif; ?>
					<?php 
						$publickey = get_option($dynamo_tpl->name . '_recaptcha_public_key', ''); // you got this from the signup page
						echo recaptcha_get_html($publickey); 
					?>				
				</p>
				<?php endif; ?>
				
				<p>
					<input type="submit" value="<?php __('Send message', 'dp-theme'); ?>" />
				</p>
				<input type="hidden" name="message-send" id="message-send" value="true" />
			</form>
		<?php endif; ?>
	</article>
</section>

<?php

dp_load('after');
dp_load('footer');

// EOF