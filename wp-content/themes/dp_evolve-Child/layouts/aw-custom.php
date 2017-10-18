<div class="interior-main vc_col-sm-5 no-pad">
		<?php 

			$image = get_field('image');

			if( !empty($image) ){ ?>

				<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />

			<?php }else{?>

				<img src="/wp-content/uploads/2016/06/clinicians.jpg" alt="default image" />

		<?php } ?>

		<h2><?php the_title(); ?></h2>
	</div>
	<div class="interior-excerpt vc_col-sm-7">
		<?php the_field('excerpt'); ?>
	</div>

	<div class="clearfix"></div>