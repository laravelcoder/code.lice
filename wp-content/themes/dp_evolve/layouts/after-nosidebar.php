<?php 
	
	/**
	 *
	 * Template elements after the page content
	 *
	 **/
	
	// create an access to the template main object
	global $dynamo_tpl;
	// disable direct access to the file	
	defined('DYNAMO_WP') or die('Access denied');
	
?>
		
			</div><!-- end of the #dp-content-wrap -->
			
			</section><!-- end of the mainbody section -->
		</section><!-- end of the #dp-mainbody-columns -->
</section><!-- end of the .dp-page-wrap section -->	

<div id="dp-sticky-navigation-wrapper">
		<div id="search-form-wrapper-1">
        <div id="dp-head-wrap">
            <section class="dp-page">
                <header id="dp-head">
                    <?php if(get_option($dynamo_tpl->name . "_branding_logo_type", 'css') != 'none') : ?>
                    <h1>
                        <a href="<?php echo home_url(); ?>" class="<?php echo get_option($dynamo_tpl->name . "_branding_logo_type", 'css'); ?>Logo"><?php dp_blog_logo(); ?></a>
                    </h1>
                    <?php endif; ?>
                    <?php if((get_option($dynamo_tpl->name . '_header_search', 'Y') == 'Y') || get_option($dynamo_tpl->name . '_login_link', 'Y') == 'Y') : ?>
                    <div id="dp-button-area">
                        <?php if(get_option($dynamo_tpl->name . '_login_link', 'Y') == 'Y') : ?>
                        <?php if (is_user_logged_in()) : ?> 
                        <a href="#" id="dp-logout" class="dp-tipsy" data-tipcontent="<?php __('Logout', 'dp-theme'); ?>"><i class="ss-user"></i></a>
                        <?php else : ?>
                        <a href="#" id="dp-login" class="dp-tipsy" data-tipcontent="<?php __('Login', 'dp-theme'); ?>"><i class="ss-user"></i></a>
                        <?php endif; ?>
                        <?php endif; ?>

                        <?php if(get_option($dynamo_tpl->name . '_search_link', 'Y') == 'Y') : ?>
                        <a href="#" id="dp-header-search"><i class="ss-search"></i></a>
						<?php endif; ?>
                    </div>
                    
                    <?php endif; ?>
                   
                    
                    <?php if(dp_show_menu('mainmenu')) : ?>
                    <a href="#" id="dp-mainmenu-toggle">
                    </a>
                    
                    <div id="dp-mainmenu-collapse" class="menu-hidden" data-btn="dp-mainmenu-toggle">	
                        <?php dynamo_menu('mainmenu', 'dp-main-menu', array('walker' => new DPMenuWalker()),'sf-menu'); ?>
                    </div>
        <!--   Begin secondary menu area -->
        <div class="clearboth"></div>
        <?php if(dp_is_active_sidebar('secondary_menu')) : ?>
        <div class="dp-secondary-menu-wrapper">
        <div class="dp-page">
		<?php dp_dynamic_sidebar('secondary_menu'); ?>
        </div>
		</div>
		<?php endif; ?>        
        <!--   End secondary menu area -->
                    <?php endif; ?>
                </header>
            </section>
        </div>
        </div>
		<div id="dp-header-search-form">
        	<div class="dp-page">
                    <div id="cancel-search"><i class="ss-delete"></i></div>
                    <form method="get" id="searchform" action="<?php echo get_site_url(); ?>">
                    <input type="text" class="field" name="s" id="s" placeholder="Start typing..." value="">
                    </form>            
            		</div>
        </div>
        </div>

<div class="clearboth"></div>