<?php

/**
 *
 * Attachment page
 *
 **/

global $dynamo_tpl;

dp_load('header');
dp_load('before');

?>

<section id="dp-mainbody">
<?php while ( have_posts() ) : the_post(); ?>
	<article>
		
		<section class="intro">
			<a href="<?php echo get_permalink( $post->post_parent ); ?>" title="<?php esc_attr(printf(__('Return to %s', 'dp-theme'), get_the_title($post->post_parent))); ?>" rel="gallery">
				<?php printf(__('<span>&larr;</span> %s', 'dp-theme' ), get_the_title($post->post_parent)); ?>
			</a>
            <div class="space15"></div>
		
			<?php if ( wp_attachment_is_image() ) :
				$attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
				//
				foreach ( $attachments as $k => $attachment ) {
					if ( $attachment->ID == $post->ID )
						break;
				}
				$k++;
				// If there is more than 1 image attachment in a gallery
				if (count($attachments) > 1) {
					if(isset($attachments[$k])) {
						$next_attachment_url = get_attachment_link($attachments[$k]->ID);
					} else {
						$next_attachment_url = get_attachment_link($attachments[0]->ID);
					}
				} else {
					$next_attachment_url = wp_get_attachment_url();
				}
			?>			
				<p>
					<a href="<?php echo $next_attachment_url; ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment">
						<?php echo wp_get_attachment_image( $post->ID, array( 900, 9999 ) ); ?>
					</a>
				</p>
				
				<?php previous_image_link( false ); ?>
				<?php next_image_link( false ); ?>
			<?php else : ?>
			<a href="<?php echo wp_get_attachment_url(); ?>" title="<?php echo esc_attr(get_the_title()); ?>" rel="attachment">
				<?php echo basename(get_permalink()); ?>
			</a>
			<?php endif; ?>
		</section>
		
		<?php if ( is_search() || is_archive() || is_tag() ) : ?>
		<section class="summary">
			<?php the_excerpt(); ?>
		</section>
		<?php else : ?>
		<section class="content">
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'dp-theme' ) ); ?>
			<?php dp_post_links(); ?>
		</section>
		<?php endif; ?>
		
    <?php get_template_part( 'layouts/content.post.footer' ); ?>
	</article>
	
	<?php comments_template('', true); ?>
<?php endwhile; ?>
</section>

<?php

dp_load('after');
dp_load('footer');

// EOF