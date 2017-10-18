<?php

/**
 *
 * Comments part
 *
 **/

?>

<?php if ( post_password_required() ) : ?>
<section id="comments">
	<p class="no-password"><?php __( 'This post is password protected. Enter the password to view any comments.', 'dp-theme' ); ?></p>
</section>
<?php
	return;/* Stop the rest of comments.php from being processed */	
	endif;
?>

<?php if ( have_comments() ) : ?>
<section id="comments">
	<div class="headline heading-line "><h3><?php printf(__( 'Comments <span class="comments-amount">(<i class="ss-chat"></i>%1$s)</span>', 'dp-theme'),number_format_i18n( get_comments_number() )); ?></h3></div>

	<?php if ( get_comment_pages_count() > 1 && get_option('page_comments' )) : ?>
	<nav>
		<div class="nav-prev">
			<?php previous_comments_link( __( '&larr; Older Comments', 'dp-theme' ) ); ?>
		</div>
		<div class="nav-next">
			<?php next_comments_link( __( 'Newer Comments &rarr;', 'dp-theme' ) ); ?>
		</div>
	</nav>
	<?php endif; ?>
	
	<ol>
		<?php wp_list_comments(array( 'callback' => 'dynamo_comment_template', 'style' => 'ol')); ?>	
	</ol>

	<?php if ( get_comment_pages_count() > 1 && get_option('page_comments' )) : ?>
	<nav>
		<div class="nav-prev">
			<?php previous_comments_link( __( '&larr; Older Comments', 'dp-theme' ) ); ?>
		</div>
		<div class="nav-next">
			<?php next_comments_link( __( 'Newer Comments &rarr;', 'dp-theme' ) ); ?>
		</div>
	</nav>
	<?php endif; ?>
	
	<?php dp_comment_form(); ?>
</section>
<?php elseif ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
<section id="comments" class="nocomments">	
	<p class="no-comments"><?php __( 'Comments are closed.', 'dp-theme' ); ?></p>
</section>
<?php else : ?>
<section id="comments" class="nocomments">

	<?php dp_comment_form(); ?>
</section>
<?php endif; ?>