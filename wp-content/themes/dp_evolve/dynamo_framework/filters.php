<?php

// disable direct access to the file	
defined('DYNAMO_WP') or die('Access denied');	

/**
 *
 * Function used to add the Google Profile URL in the user profile
 *
 * @param methods - array of the contact methods
 *
 * @return the updated arra of the contact methods
 *
 **/

function dynamo_google_profile( $methods ) {
  // Add the Google Profile URL field
  $methods['google_profile'] = __('Google Profile URL', 'dp-theme');
  // return the updated contact methods
  return $methods;
}

add_filter( 'user_contactmethods', 'dynamo_google_profile', 10, 1);



/**
 *
 * Functions used to generate post excerpt
 *
 * @return HTML output
 *
 **/

function dynamo_excerpt($text) {
    return $text;
}


add_filter( 'get_the_excerpt', 'dynamo_excerpt', 999 );



function dynamo_excerpt_length($length) {
    global $dynamo_tpl;
    return get_option($dynamo_tpl->name . '_excerpt_len', 55);
}


add_filter( 'excerpt_length', 'dynamo_excerpt_length', 999 );


/**
 *
 * Function used for disable widget titles if the first character is "#"
 *
 *
 **/
add_filter( 'widget_title', 'remove_widget_title' );
function remove_widget_title( $widget_title ) {
	if ( substr ( $widget_title, 0, 1 ) == '#' )
		return;
	else 
		return ( $widget_title );
}



/**
 *
 * Function used in the attachment page image links
 *
 * @return the additional class in the links
 *
 **/


function dynamo_img_link_class( $link )
{
    $class = 'next_image_link' === current_filter() ? 'next' : 'prev';


    return str_replace( '<a ', '<a class="btn-nav nav-'.$class.'"', $link );
}


add_filter( 'previous_image_link', 'dynamo_img_link_class' );
add_filter( 'next_image_link',     'dynamo_img_link_class' );

/**
 *
 * Function used to add target="_blank" in the comments links
 *
 * @return comment code
 *
 **/


if(get_option($dynamo_tpl->name . '_comments_autoblank', 'Y') == 'Y') {
	function dynamo_comments_autoblank($text) {
		$return = str_replace('<a', '<a target="_blank"', $text);
		return $return;
	}


	add_filter('comment_text', 'dynamo_comments_autoblank');
}


/**
 *
 * Code used to disable autolinking in comments
 *
 **/
 
if(get_option($dynamo_tpl->name . '_comments_autolinking', 'Y') == 'N') {
	remove_filter('comment_text', 'make_clickable', 9);
}

/**
 *
 * Function used as a die handler
 *
 * @return the proper die handler function name
 *
 **/

function dynamo_die_handler() {
	return 'dynamo_custom_die_handler';
}

/**
 *
 * Function used as real die handler
 *
 * @param message - message for the error page
 * @param title - title of the error page
 * @param args - additional params
 *
 * @return null
 *
 **/

function dynamo_custom_die_handler( $message, $title = '', $args = array() ) {
	if(!defined('DPTPLNAME')) {
		define('DPTPLNAME', 'Evolve');
	}
	
	$defaults = array( 'response' => 404 );
	$r = wp_parse_args($args, $defaults);

	$have_gettext = function_exists('__');

	if ( function_exists( 'is_wp_error' ) && is_wp_error( $message ) ) {
		if ( empty( $title ) ) {
			$error_data = $message->get_error_data();
			if ( is_array( $error_data ) && isset( $error_data['title'] ) )
				$title = $error_data['title'];
		}
		$errors = $message->get_error_messages();
		switch ( count( $errors ) ) :
		case 0 :
			$message = '';
			break;
		case 1 :
			$message = "<p>{$errors[0]}</p>";
			break;
		default :
			$message = "<ul>\n\t\t<li>" . join( "</li>\n\t\t<li>", $errors ) . "</li>\n\t</ul>";
			break;
		endswitch;
	} elseif ( is_string( $message ) ) {
		$message = "<p>$message</p>";
	}

	if ( isset( $r['back_link'] ) && $r['back_link'] ) {
		$back_text = $have_gettext? __('&laquo; Back', 'dp-theme') : '&laquo; Back';
		$message .= "\n<p><a href='javascript:history.back()'>$back_text</a></p>";
	}

	if ( !function_exists( 'did_action' ) || !did_action( 'admin_head' ) ) :
		if ( !headers_sent() ) {
			status_header( $r['response'] );
			nocache_headers();
			header( 'Content-Type: text/html; charset=utf-8' );
		}

		if ( empty($title) )
			$title = $have_gettext ? __('WordPress &rsaquo; Error', 'dp-theme') : 'WordPress &rsaquo; Error';

		$text_direction = 'ltr';
		if ( isset($r['text_direction']) && 'rtl' == $r['text_direction'] )
			$text_direction = 'rtl';
		elseif ( function_exists( 'is_rtl' ) && is_rtl() )
			$text_direction = 'rtl';
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php if ( function_exists( 'language_attributes' ) && function_exists( 'is_rtl' ) ) language_attributes(); else echo "dir='$text_direction'"; ?>>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo $title ?></title>
	<link href="<?php echo dynamo_file_uri('fonts/Colaborate/stylesheet.css'); ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo dynamo_file_uri('css/error.css'); ?>" rel="stylesheet" type="text/css" />
</head>
<body id="error-page">
<?php endif; ?>
	<a href="./index.php" class="cssLogo"><?php echo $title ?></a>
	<h1><?php echo str_replace('WordPress &rsaquo; ', '', $title); ?></h1>
	<?php echo $message; ?>
	<p class="errorinfo"><a href="./index.php"><?php __('Homepage', 'dp-theme'); ?></a></p>
</body>
</html>
<?php
	die();
}

// EOF