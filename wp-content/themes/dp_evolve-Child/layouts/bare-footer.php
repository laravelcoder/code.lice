<div class="clearboth"> </div>
<div id="dp-footer-wrap" >
  <div id="dp-footer" class="dp-page widget-area">
  	<div class="clearboth"> </div>
  </div>
</div>
<div id="dp-copyright" class="dp-page">
   <div id="dp-copyright-inner">
      <div class="dp-copyrights">
         <img src="/wp-content/uploads/2016/08/footer_logo.png" class="foot-logo" /> 
        <?php if(get_option($dynamo_tpl->name . '_template_footer_logo') == 'css') : ?>		
        	<img src="<?php echo get_template_directory_uri(); ?>/images/branding-logo.png" class="dp-copyright-logo css" alt="Lice Clinics of America Logo" />	
        <?php endif; ?>        
        <?php if(get_option($dynamo_tpl->name . '_template_footer_logo') == 'image' && get_option($dynamo_tpl->name . '_template_footer_logo_image') !='') : ?>        
        	<?php echo '<img src="'.get_option($dynamo_tpl->name . "_template_footer_logo_image", '').'" alt="" width="'.get_option($dynamo_tpl->name . "_footer_logo_image_width", 128).'" height="'.get_option($dynamo_tpl->name . "_footer_logo_image_height", 128).'" class="dp-copyright-logo" />'; ?>
        <?php endif; ?>        
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
<div id="back-to-top"></div>
	<?php dp_load('social'); ?>	
	<?php dp_load('login'); ?>	
	<?php do_action('dynamowp_footer'); ?>	
	<?php echo stripslashes(htmlspecialchars_decode(str_replace( '&#039;', "'", get_option($dynamo_tpl->name . '_footer_code', ''))			)		);	?>	
	<?php do_action('dynamowp_ga_code'); ?>    
	<?php wp_footer(); ?>
</div>
</div>
<div id="dp-mobile-menu">
	<i id="close-mobile-menu" class="icon-cancel-circle-1"></i>
	<?php dynamo_menu_mobile('mainmenu', 'dp-main-menu', array('walker' => new DPMenuWalkerMobile()),'aside-menu'); ?>                
</div>
	<script type="text/javascript">
		setTimeout(function(){
			var a=document.createElement("script");
			var b=document.getElementsByTagName("script")[0];
			a.src=document.location.protocol+"//script.crazyegg.com/pages/scripts/0014/2188.js?"+Math.floor(new Date().getTime()/3600000);
			a.async=true;
			a.type="text/javascript";
			b.parentNode.insertBefore(a,b)
	    }, 1);
	</script>
</body>
</html>