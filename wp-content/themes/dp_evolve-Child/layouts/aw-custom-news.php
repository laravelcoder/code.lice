
		<div class="greyjoy">
			<h3><strong>Videos</strong></h3>
		</div>

		<div class="vc_row wpb_row vc_inner vc_row-fluid">

		<?php if( have_rows('videos') ): ?>

		<?php while( have_rows('videos') ): the_row(); 

			// vars
			$image = get_sub_field('press_release_image');
			$link = get_sub_field('press_link');
			$title = get_sub_field('press_release_title');
			$subtitle = get_sub_field('press_release_subtitle');

			?>

			<div class="vc_col-sm-4 press-box" style="margin-top: 15px; margin-bottom: 15px;">
				<div class="vc_column-inner"> 

					<?php if( $link ): ?>
						<a href="<?php echo $link; ?>" target="_blank">
					<?php endif; ?>

						<div style="background: url('<?php echo $image['url']; ?>'); width: 100%; height: 225px; background-size: cover; background-position: center;"></div>

					<?php if( $link ): ?>
						</a>
					<?php endif; ?>

					<?php if( $link ): ?>
						<a href="<?php echo $link; ?>" target="_blank">
					<?php endif; ?>

						<h3 style="margin-top: 15px;" class="green-title"><?php echo $title; ?></h3>

					<?php if( $link ): ?>
						</a>
					<?php endif; ?>

					<p><?php echo $subtitle; ?></p>

				</div>

			</div>

		<?php endwhile; ?>

		

<?php endif; ?>
</div>

	<div class="clearfix"></div>