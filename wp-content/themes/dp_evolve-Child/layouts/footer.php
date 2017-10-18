<?php	/**	 *	 * Template footer	 *	 **/	// create an access to the template main object	global $dynamo_tpl, $post;	// disable direct access to the file	defined('DYNAMO_WP') or die('Access denied');?>
<div id="dp-footer-wrap" >
	<?php if(dp_is_active_sidebar('footer')) : ?>
	<div id="dp-footer" class="dp-page widget-area">	
		<?php dp_dynamic_sidebar('footer'); ?>
	</div>
	<?php endif; ?>
	<div id="dp-footer" class="dp-page widget-area">
		<div class="vc_col-sm-4 no-margin-right">
			<?php if(dp_is_active_sidebar('footer1')) : ?>
			<div id="dp-footer" class="dp-page widget-area">	<?php dp_dynamic_sidebar('footer1'); ?>
			</div>
			<?php endif; ?>
		</div>
		<div class="vc_col-sm-4 no-margin-right">
			<?php if(dp_is_active_sidebar('footer2')) : ?>
			<div id="dp-footer" class="dp-page widget-area">	<?php dp_dynamic_sidebar('footer2'); ?>
			</div>
			<?php endif; ?>
		</div>
		<div class="vc_col-sm-4  no-margin-right">
			<?php if(dp_is_active_sidebar('footer3')) : ?>
			<div id="dp-footer" class="dp-page widget-area">	<?php dp_dynamic_sidebar('footer3'); ?>
			</div>
			<?php endif; ?>
		</div>
		<div class="clearboth"> </div>
	</div>
</div>
<div id="dp-copyright" class="dp-page">
	<div id="dp-copyright-inner">
		<div class="dp-copyrights">
			<img src="/wp-content/uploads/2016/08/footer_logo.png" class="foot-logo" /> 
			<?php if(get_option($dynamo_tpl->name . "_social_icons_bottom_state") == 'Y') { ?>					
			<ul id="footer-social-bar">
				<?php if(get_option($dynamo_tpl->name . "_social_facebook") != '') : ?>	        
				<li>
					<a target="_blank" href="<?php  echo get_option($dynamo_tpl->name . "_social_facebook")?>"class="dp-tipsy1 facebook" data-tipcontent="Facebook">
					</a>
				</li>
				<?php endif; ?>	        <?php if(get_option($dynamo_tpl->name . "_social_twitter") != '') : ?>	        
				<li>
					<a target="_blank" href="<?php echo get_option($dynamo_tpl->name . "_social_twitter")  ?>" class="dp-tipsy1 twitter" data-tipcontent="Twitter">
					</a>
				</li>
				<?php endif; ?>	        <?php if(get_option($dynamo_tpl->name . "_social_linkedin") != '') : ?>	        
				<li>
					<a target="_blank" href="<?php echo get_option($dynamo_tpl->name . "_social_linkedin") ?>" class="dp-tipsy1 linkedin" data-tipcontent="Linkedin">
					</a>
				</li>
				<?php endif; ?>	        <?php if(get_option($dynamo_tpl->name . "_social_dribbble") != '') : ?>	        
				<li>
					<a target="_blank" href="<?php echo get_option($dynamo_tpl->name . "_social_dribbble") ?>" class="dp-tipsy1 dribbble" data-tipcontent="Dribble">
					</a>
				</li>
				<?php endif; ?>	        <?php if(get_option($dynamo_tpl->name . "_social_pinterest") != '') : ?>	        
				<li>
					<a target="_blank" href="<?php echo get_option($dynamo_tpl->name . "_social_pinterest") ?>" class="dp-tipsy1 pinterest" data-tipcontent="Pinterest">
					</a>
				</li>
				<?php endif; ?>	        <?php if(get_option($dynamo_tpl->name . "_social_flickr") != '') : ?>	        
				<li>
					<a target="_blank" href="<?php echo get_option($dynamo_tpl->name . "_social_flickr") ?>" class="dp-tipsy1 flickr" data-tipcontent="Flickr">
					</a>
				</li>
				<?php endif; ?>	        <?php if(get_option($dynamo_tpl->name . "_social_youtube") != '') : ?>	        
				<li>
					<a target="_blank" href="<?php echo get_option($dynamo_tpl->name . "_social_youtube") ?>" class="dp-tipsy1 youtube" data-tipcontent="Youtube">
					</a>
				</li>
				<?php endif; ?>	        <?php if(get_option($dynamo_tpl->name . "_social_vimeo") != '') : ?>	        
				<li>
					<a target="_blank" href="<?php echo get_option($dynamo_tpl->name . "_social_vimeo") ?>" class="dp-tipsy1 vimeo" data-tipcontent="Vimeo">
					</a>
				</li>
				<?php endif; ?>	        <?php if(get_option($dynamo_tpl->name . "_social_rss") != '') : ?>	        
				<li>
					<a target="_blank" href="<?php echo get_option($dynamo_tpl->name . "_social_rss") ?>" class="dp-tipsy1 rss" data-tipcontent="RSS">
					</a>
				</li>
				<?php endif; ?>	        <?php if(get_option($dynamo_tpl->name . "_social_steam") != '') : ?>	        
				<li>
					<a target="_blank" href="<?php echo get_option($dynamo_tpl->name . "_social_steam") ?>" class="dp-tipsy1 steam" data-tipcontent="Steam">
					</a>
				</li>
				<?php endif; ?>	        <?php if(get_option($dynamo_tpl->name . "_social_tumblr") != '') : ?>	        
				<li>
					<a target="_blank" href="<?php echo get_option($dynamo_tpl->name . "_social_tumblr") ?>" class="dp-tipsy1 tumblr" data-tipcontent="Tumblr">
					</a>
				</li>
				<?php endif; ?>	        <?php if(get_option($dynamo_tpl->name . "_social_github") != '') : ?>	        
				<li>
					<a target="_blank" href="<?php echo get_option($dynamo_tpl->name . "_social_github") ?>" class="dp-tipsy1 github" data-tipcontent="Github">
					</a>
				</li>
				<?php endif; ?>	        <?php if(get_option($dynamo_tpl->name . "_social_delicious") != '') : ?>	        
				<li>
					<a target="_blank" href="<?php echo get_option($dynamo_tpl->name . "_social_delicious") ?>" class="dp-tipsy1 delicious" data-tipcontent="Delicious">
					</a>
				</li>
				<?php endif; ?>	        <?php if(get_option($dynamo_tpl->name . "_social_reddit") != '') : ?>	        
				<li>
					<a target="_blank" target="_blank" href="<?php echo get_option($dynamo_tpl->name . "_social_reddit") ?>" class="dp-tipsy1 reddit" data-tipcontent="Reddit">
					</a>
				</li>
				<?php endif; ?>	        <?php if(get_option($dynamo_tpl->name . "_social_lastfm") != '') : ?>	        
				<li>
					<a target="_blank" href="<?php echo get_option($dynamo_tpl->name . "_social_lastfm") ?>" class="dp-tipsy1 lastfm" data-tipcontent="Lastfm">
					</a>
				</li>
				<?php endif; ?>	        <?php if(get_option($dynamo_tpl->name . "_social_digg") != '') : ?>	        
				<li>
					<a target="_blank" href="<?php echo get_option($dynamo_tpl->name . "_social_digg") ?>" class="dp-tipsy1 digg" data-tipcontent="Digg">
					</a>
				</li>
				<?php endif; ?>	        <?php if(get_option($dynamo_tpl->name . "_social_forrst") != '') : ?>	        
				<li>
					<a target="_blank" href="<?php echo get_option($dynamo_tpl->name . "_social_forrst") ?>" class="dp-tipsy1 forrst" data-tipcontent="Forrst">
					</a>
				</li>
				<?php endif; ?>	        <?php if(get_option($dynamo_tpl->name . "_social_stumbleupon") != '') : ?>	        
				<li>
					<a target="_blank" href="<?php echo get_option($dynamo_tpl->name . "_social_stumbleupon")  ?>" class="dp-tipsy1 stumbleupon" data-tipcontent="Stumbleupon">
					</a>
				</li>
				<?php endif; ?>	        <?php if(get_option($dynamo_tpl->name . "_social_instagram") != '') : ?>	        
				<li>
					<a target="_blank" href="<?php echo get_option($dynamo_tpl->name . "_social_instagram")  ?>" class="dp-tipsy1 instagram" data-tipcontent="Instagram">
					</a>
				</li>
				<?php endif; ?>	        <?php if(get_option($dynamo_tpl->name . "_social_viadeo") != '') : ?>	        
				<li>
					<a target="_blank" href="<?php echo get_option($dynamo_tpl->name . "_social_viadeo")  ?>" class="dp-tipsy1 viadeo" data-tipcontent="Viadeo">
					</a>
				</li>
				<?php endif; ?>	        <?php if(get_option($dynamo_tpl->name . "_social_xing") != '') : ?>	        
				<li>
					<a target="_blank" href="<?php echo get_option($dynamo_tpl->name . "_social_xing")  ?>" class="dp-tipsy1 xing" data-tipcontent="Xing">
					</a>
				</li>
				<?php endif; ?>	        <?php if(get_option($dynamo_tpl->name . "_social_googleplus") != '') : ?>	        
				<li>
					<a target="_blank" href="<?php echo get_option($dynamo_tpl->name . "_social_googleplus")  ?>" class="dp-tipsy1 gplus" data-tipcontent="Google+">
					</a>
				</li>
				<?php endif; ?>	        
			</ul>
			<?php } ?>        <?php if(get_option($dynamo_tpl->name . '_template_footer_logo') == 'css') : ?>		<img src="<?php echo get_template_directory_uri(); ?>/images/branding-logo.png" class="dp-copyright-logo css" alt="Lice Clinics of America Logo" />		<?php endif; ?>        <?php if(get_option($dynamo_tpl->name . '_template_footer_logo') == 'image' && get_option($dynamo_tpl->name . '_template_footer_logo_image') !='') : ?>        <?php echo '<img src="'.get_option($dynamo_tpl->name . "_template_footer_logo_image", '').'" alt="" width="'.get_option($dynamo_tpl->name . "_footer_logo_image_width", 128).'" height="'.get_option($dynamo_tpl->name . "_footer_logo_image_height", 128).'" class="dp-copyright-logo" />'; ?>		<?php endif; ?>        
			<div class="dp-copyrights-text">
				<?php echo str_replace('\\', '', htmlspecialchars_decode(get_option($dynamo_tpl->name . '_template_copyright_text', ''))); ?>
			</div>
			<div class="dp-designedby-text">
				<?php echo str_replace('\\', '', htmlspecialchars_decode(get_option($dynamo_tpl->name . '_template_designedby_text', ''))); ?>
			</div>
		</div>
	</div>
</div>
</div>    
<div id="back-to-top">
</div>
<?php dp_load('social'); ?>	
<?php dp_load('login'); ?>	
<?php do_action('dynamowp_footer'); ?>	
<?php echo stripslashes(htmlspecialchars_decode(str_replace( '&#039;', "'", get_option($dynamo_tpl->name . '_footer_code', ''))));	?>	
<?php do_action('dynamowp_ga_code'); ?>    
<?php wp_footer(); ?>
</div>
</div>
<div id="dp-mobile-menu">
	<i id="close-mobile-menu" class="icon-cancel-circle-1">
	</i>
	<?php dynamo_menu_mobile('mainmenu', 'dp-main-menu', array('walker' => new DPMenuWalkerMobile()),'aside-menu'); ?>                
</div>

<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->
<script type="text/javascript">setTimeout(function(){var a=document.createElement("script");var b=document.getElementsByTagName("script")[0];a.src=document.location.protocol+"//script.crazyegg.com/pages/scripts/0014/2188.js?"+Math.floor(new Date().getTime()/3600000);a.async=true;a.type="text/javascript";b.parentNode.insertBefore(a,b)}, 1);</script>


<script>
	 $("#open-locator").click(function () {
	     $(".locate-me").stop().slideToggle();
	     return false;
	 });
</script>
</body>
</html>