<?php 
	
	/**
	 *
	 * Template elements before the page content
	 *
	 **/
	
	// create an access to the template main object
	global $dynamo_tpl,$post;
	if (!is_search() && !is_404()) $params = get_post_custom();
	$params_title = isset($params['dynamo-post-params-title']) ? esc_attr( $params['dynamo-post-params-title'][0] ) : 'Y';
	$params_custom_headerbg =  isset( $params['dynamo-post-params-header_img'] ) ? esc_attr( $params['dynamo-post-params-header_img'][0] ) : '';
	$params_subheader_use =  isset( $params['dynamo-post-params-subheader_use'] ) ? esc_attr( $params['dynamo-post-params-subheader_use'][0] ) : 'Y';
	$params_custom_subheaderbg =  isset( $params['dynamo-post-params-subheader_img'] ) ? esc_attr( $params['dynamo-post-params-subheader_img'][0] ) : '';								    $params_custom_title =  isset( $params['dynamo-post-params-custom_title'] ) ? esc_attr( $params['dynamo-post-params-custom_title'][0] ) : '';
	$params_custom_subtitle =  isset( $params['dynamo-post-params-custom_subtitle'] ) ? esc_attr( $params['dynamo-post-params-custom_subtitle'][0] ) : '';
    $classes = get_body_class();
	// disable direct access to the file	
	defined('DYNAMO_WP') or die('Access denied');
	
	// check if the sidebar is set to be a left column
	$args_val = $args == null || ($args != null && $args['sidebar'] == true);
	
	$dp_mainbody_class = '';
	
	if(dp_is_active_sidebar('sidebar') && $args_val) {
		$dp_mainbody_class .= ' dp-sidebar-left';
	}
	
	if($dp_mainbody_class != '') {
		$dp_mainbody_class .= ' class="'.$dp_mainbody_class.'" ';
	}
?>
<body <?php do_action('dynamowp_body_attributes'); ?>>
	<?php if(get_option($dynamo_tpl->name . "_use_page_preloader") == 'Y') : ?>
    <div id="dp_preloader">
        <div id="dp_status"><div class="spin"></div></div>
    </div>
    <?php endif; ?>
<section id="dp-page-box">
		<div id="search-form-wrapper"> 
		<!--   Begin Top Panel widget area -->
        <div id="dp-top-bar" >
        <section class="dp-page">
        <div class="top-contact-bar">
        <?php if(get_option($dynamo_tpl->name . "_top_contact_phone") != '') : ?>
        <div class= "top-bar-phone"><i class="icon-phone"></i><span><?php echo get_option($dynamo_tpl->name . "_top_contact_phone")?><span></div>
        <?php endif; ?>
        <?php if(get_option($dynamo_tpl->name . "_top_contact_email") != '') : ?>
        <div class= "top-bar-email"><i class="icon-mail"></i><span>
        <?php if(get_option($dynamo_tpl->name . "_top_contact_email_link") == 'Y') : ?>
        <a href="mailto:<?php echo get_option($dynamo_tpl->name . "_top_contact_email")?>">
        <?php endif; ?>
        <?php if((get_option($dynamo_tpl->name . "_top_contact_email_link") == 'Y') && (get_option($dynamo_tpl->name . "_top_contact_email_hide") == 'Y')){ 
		echo get_option($dynamo_tpl->name . "_top_contact_email_text");
		 } else { 
		echo get_option($dynamo_tpl->name . "_top_contact_email");
		} ?>
        </span></div>
        <?php if(get_option($dynamo_tpl->name . "_top_contact_email_link") == 'Y') : ?>
        </a>
        <?php endif; ?>
        <?php endif; ?>
        </div>
        <?php if(get_option($dynamo_tpl->name . "_social_icons_top_state") == 'Y') { ?>
        <ul id="top-social-bar">
        <?php if(get_option($dynamo_tpl->name . "_social_facebook") != '') : ?>        
        <li><a href="<?php  echo get_option($dynamo_tpl->name . "_social_facebook")?>"class=" facebook"></a></li>
        <?php endif; ?>
        <?php if(get_option($dynamo_tpl->name . "_social_twitter") != '') : ?>
        <li><a href="<?php echo get_option($dynamo_tpl->name . "_social_twitter")  ?>" class="twitter"></a></li>
        <?php endif; ?>
        <?php if(get_option($dynamo_tpl->name . "_social_linkedin") != '') : ?>
        <li><a href="<?php echo get_option($dynamo_tpl->name . "_social_linkedin") ?>" class="linkedin"></a></li>
        <?php endif; ?>
        <?php if(get_option($dynamo_tpl->name . "_social_dribbble") != '') : ?>
        <li><a href="<?php echo get_option($dynamo_tpl->name . "_social_dribbble") ?>" class="dribbble"></a></li>
        <?php endif; ?>
        <?php if(get_option($dynamo_tpl->name . "_social_pinterest") != '') : ?>
        <li><a href="<?php echo get_option($dynamo_tpl->name . "_social_pinterest") ?>" class="pinterest"></a></li>
        <?php endif; ?>
        <?php if(get_option($dynamo_tpl->name . "_social_flickr") != '') : ?>
        <li><a href="<?php echo get_option($dynamo_tpl->name . "_social_flickr") ?>" class="flickr"></a></li>
        <?php endif; ?>
        <?php if(get_option($dynamo_tpl->name . "_social_youtube") != '') : ?>
        <li><a href="<?php echo get_option($dynamo_tpl->name . "_social_youtube") ?>" class="youtube"></a></li>
        <?php endif; ?>
        <?php if(get_option($dynamo_tpl->name . "_social_vimeo") != '') : ?>
        <li><a href="<?php echo get_option($dynamo_tpl->name . "_social_vimeo") ?>" class="vimeo"></a></li>
        <?php endif; ?>
        <?php if(get_option($dynamo_tpl->name . "_social_rss") != '') : ?>
        <li><a href="<?php echo get_option($dynamo_tpl->name . "_social_rss") ?>" class="rss"></a></li>
        <?php endif; ?>
        <?php if(get_option($dynamo_tpl->name . "_social_steam") != '') : ?>
        <li><a href="<?php echo get_option($dynamo_tpl->name . "_social_steam") ?>" class="steam"></a></li>
        <?php endif; ?>
        <?php if(get_option($dynamo_tpl->name . "_social_tumblr") != '') : ?>
        <li><a href="<?php echo get_option($dynamo_tpl->name . "_social_tumblr") ?>" class="tumblr"></a></li>
        <?php endif; ?>
        <?php if(get_option($dynamo_tpl->name . "_social_github") != '') : ?>           
        <li><a href="<?php echo get_option($dynamo_tpl->name . "_social_github") ?>" class="github"></a></li>
        <?php endif; ?>
        <?php if(get_option($dynamo_tpl->name . "_social_delicious") != '') : ?>
        <li><a href="<?php echo get_option($dynamo_tpl->name . "_social_delicious") ?>" class="delicious"></a></li>
        <?php endif; ?>
        <?php if(get_option($dynamo_tpl->name . "_social_reddit") != '') : ?>
        <li><a href="<?php echo get_option($dynamo_tpl->name . "_social_reddit") ?>" class="reddit"></a></li> 
        <?php endif; ?>
        <?php if(get_option($dynamo_tpl->name . "_social_lastfm") != '') : ?>
        <li><a href="<?php echo get_option($dynamo_tpl->name . "_social_lastfm") ?>" class="lastfm"></a></li>
        <?php endif; ?>
        <?php if(get_option($dynamo_tpl->name . "_social_digg") != '') : ?>
        <li><a href="<?php echo get_option($dynamo_tpl->name . "_social_digg") ?>" class="digg"></a></li>
        <?php endif; ?>
        <?php if(get_option($dynamo_tpl->name . "_social_forrst") != '') : ?>
        <li><a href="<?php echo get_option($dynamo_tpl->name . "_social_forrst") ?>" class="forrst"></a></li>
        <?php endif; ?>
        <?php if(get_option($dynamo_tpl->name . "_social_stumbleupon") != '') : ?>
        <li><a href="<?php echo get_option($dynamo_tpl->name . "_social_stumbleupon")  ?>" class="stumbleupon"></a></li>
        <?php endif; ?>
        <?php if(get_option($dynamo_tpl->name . "_social_instagram") != '') : ?>
        <li><a href="<?php echo get_option($dynamo_tpl->name . "_social_instagram")  ?>" class="instagram"></a></li>
        <?php endif; ?>
        <?php if(get_option($dynamo_tpl->name . "_social_viadeo") != '') : ?>
        <li><a href="<?php echo get_option($dynamo_tpl->name . "_social_viadeo")  ?>" class="viadeo"></a></li>
        <?php endif; ?>
        <?php if(get_option($dynamo_tpl->name . "_social_xing") != '') : ?>
        <li><a href="<?php echo get_option($dynamo_tpl->name . "_social_xing")  ?>" class="xing"></a></li>
        <?php endif; ?>
        <?php if(get_option($dynamo_tpl->name . "_social_googleplus") != '') : ?>
        <li><a href="<?php echo get_option($dynamo_tpl->name . "_social_googleplus")  ?>" class="gplus"></a></li>
        <?php endif; ?>
        </ul>
        <?php } ?>
        </section>
        </div>
		<!--   End Top Panel widget area -->
        
        <!--   Begin Navigation area -->
		<div id="dp-navigation-wrapper">
        <div id="dp-head-wrap" class="semi-transparent">
            <section class="dp-page">
                <header id="dp-head" class="top-navigation">
                    <?php if(get_option($dynamo_tpl->name . "_branding_logo_type", 'css') != 'none') : ?>
                    <h1>
                        <a href="<?php echo home_url(); ?>" class="<?php echo get_option($dynamo_tpl->name . "_branding_logo_type", 'css'); ?>Logo"><?php dp_blog_logo(); ?></a>
                    </h1>
                    <?php endif; ?>
                    <?php if((get_option($dynamo_tpl->name . '_header_search', 'Y') == 'Y') || get_option($dynamo_tpl->name . '_login_link', 'Y') == 'Y') : ?>
                    <div id="dp-button-area">
                        <?php if(get_option($dynamo_tpl->name . '_login_link', 'Y') == 'Y') : ?>
                        <?php if (is_user_logged_in()) : ?> 
                        <a href="#" id="dp-logout" class="dp-tipsy" data-tipcontent="<?php _e('Logout', 'dp-theme'); ?>"><i class="ss-user"></i></a>
                        <?php else : ?>
                        <a href="#" id="dp-login" class="dp-tipsy" data-tipcontent="<?php _e('Login', 'dp-theme'); ?>"><i class="ss-user"></i></a>
                        <?php endif; ?>
                        <?php endif; ?>

                        <?php if(get_option($dynamo_tpl->name . '_search_link', 'Y') == 'Y') : ?>
                        <a href="#" id="dp-header-search"><i class="ss-search"></i></a>
						<?php endif; ?>
                    </div>
                    
                    <?php endif; ?>
                    
                    <a href="#" id="dp-mainmenu-toggle"><i class="icon-menu-1"></i></a>
							<?php 
							if(has_nav_menu('mainmenu')) {
							dynamo_menu('mainmenu', 'dp-main-menu', array('walker' => new DPMenuWalker()),'sf-menu');							}
							else {
								echo 'No menu assigned!';
							}
							?>
                    
                    
                </header>
            </section>
        </div>
        </div>
		<!--   End Navigation area -->
        <div id="dp-header-search-form">
        	<div class="dp-page">
                    <div id="cancel-search"><i class="ss-delete"></i></div>
                    <form method="get" id="searchform" action="<?php echo get_site_url(); ?>">
                    <input type="text" class="field" name="s" id="s" placeholder="Start typing..." value="">
                    </form>            
            		</div>
            </div>
        </div>
        <!--   Begin secondary menu area -->
        <?php if(dp_is_active_sidebar('secondary_menu')) : ?>
        <div class="dp-secondary-menu-wrapper">
        <div class="dp-page">
		<?php dp_dynamic_sidebar('secondary_menu'); ?>
        </div>
		</div>
		<?php endif; ?>        
        <!--   End secondary menu area -->
        <!--   Begin Slideshow area -->
		<?php if(dp_is_active_sidebar('slideshow')) : ?>
        <section id="dp-slideshow">
                <?php dp_dynamic_sidebar('slideshow'); ?>
        </section>
        <?php endif; ?>
        <!--   End slideshow area -->
        
        <!--   Begin Header widget area -->
        <?php if(dp_is_active_sidebar('header')) : ?>
        <div class="dp-header-wrapper">
        <?php 
        $hstyle='';
        if($params_custom_headerbg != '' || get_option($dynamo_tpl->name . '_header_area_bgimage') != '') : 
        if (get_option($dynamo_tpl->name . '_header_area_bgimage') != '') {$hstyle = 'height:100%; background: url('.get_option($dynamo_tpl->name . '_header_area_bgimage').'); background-size:cover;';}
        if ($params_custom_headerbg != '') {$hstyle = 'height:100%; background: url('.$params_custom_headerbg.'); background-size:cover;';}
        $hstyle='style="'.$hstyle.'"';
        ?>
        
        <div class="dp-header-wraper-inner" <?php echo $hstyle; ?>>
        <?php endif; ?>
        
            <section id="dp-header">
            <section class="dp-page">
                    <?php dp_dynamic_sidebar('header'); ?>
            </section>
            </section>
        
        <?php if($params_custom_headerbg != '' || get_option($dynamo_tpl->name . '_header_area_bgimage') != '') : ?>
        </div>
        <?php endif; ?> 
        </div>
		<?php endif; ?>
        <!--End of header widget area -->
        
        <!--   Begin subheader wrapper -->
		<?php if($params_title == 'Y') : ?>
        <?php if($params_subheader_use == 'Y' && !is_front_page()) : ?>
        <?php if(is_single() || is_page() || is_archive() || is_search() || is_404()  || ( is_home() && ! is_front_page() ) ) : ?>
        <div class="dp-subheader-wraper">
        <?php 
        $shstyle='';
        if($params_custom_subheaderbg != '' || get_option($dynamo_tpl->name . '_subheader_area_bgimage') != '') : 
        if ($params_custom_subheaderbg != '') {$shstyle = 'height:100%; background: url('.$params_custom_subheaderbg.'); background-size:cover;';}
        $shstyle='style="'.$shstyle.'"';
        ?>
        <div class="dp-subheader-wraper-inner" <?php echo $shstyle; ?>>
        <?php endif; ?>
        <section class="dp-subheader dp-page ">
        <?php if($params_custom_title != '') : ?>
        <h1 class="main-title"><?php echo $params_custom_title ?></h1>
        <?php else : ?>
        <?php
		if ( is_home() && ! is_front_page() ) { ?>
        <h1 class="main-title"><?php echo get_the_title( get_option('page_for_posts', true) ); ?> </h1>
		<?php } elseif (is_category()) { ?>
        <h1 class="main-title"><?php	echo		'<span>' . single_cat_title( '', false ) . '</span>' ?></h1>
		<?php } elseif (is_404()) { ?>
        <h1 class="main-title"><?php __( '404 Page', 'dp-theme' ); ?></h1>
		<?php } elseif (is_search()) { ?>
        <h1 class="main-title"><?php printf( __( 'Search Results for: %s', 'dp-theme' ), '<em>' . get_search_query() . '</em>' ); ?>
</h1>
		<?php } elseif (is_archive()) { ?>
        <h1 class="main-title">		<?php if ( is_day() ) : ?>
			<?php printf( __( 'Daily Archives: %s', 'dp-theme' ), '<span>' . get_the_date() . '</span>' ); ?>
		<?php elseif ( is_month() ) : ?>
			<?php printf( __( 'Monthly Archives: %s', 'dp-theme' ), '<span>' . get_the_date( 'F Y' ) . '</span>' ); ?>
		<?php elseif ( is_year() ) : ?>
			<?php printf( __( 'Yearly Archives: %s', 'dp-theme' ), '<span>' . get_the_date( 'Y' ) . '</span>' ); ?>
		<?php else : ?>
			<?php __( 'Blog Archives', 'dp-theme' ); ?>
		<?php endif; ?>
		</h1>
		<?php } elseif (is_tag()) { ?>
        <h1 class="main-title"><?php
				printf( __( 'Tag Archives: %s', 'dp-theme' ), '<strong>' . single_tag_title( '', false ) . '</strong>' );
			?></h1>
		<?php } else { ?>
        <h1 class="main-title"><?php the_title(); ?></h1>
		<?php }
		?>
        <?php endif; ?>
        <?php if($params_custom_subtitle != '') : ?>
        <p class="sub-title"><?php echo $params_custom_subtitle ?></p>
        <?php endif; ?>
        <?php endif; ?>
        <?php if(dp_show_breadcrumbs()) : ?>
			<div id="dp-breadcrumb-fontsize";>
				<?php dp_breadcrumbs_output(); ?>
			</div>
        <?php endif; ?>
        </section>
        <?php if($params_custom_subheaderbg != '' || get_option($dynamo_tpl->name . '_subheader_area_bgimage') != '') : ?>
        </div>
        <?php endif; ?> 
        </div>
        <?php endif; ?>
        </div>
        <?php endif; ?>

        <!--   End subheader wrapper -->

<div class="clearboth"></div>
<section class="dp-page-wrap dp-page">
				<!-- Mainbody, breadcrumbs -->
                <?php if(dp_show_breadcrumbs() && $params_subheader_use == 'N') : ?>
				<section id="dp-breadcrumb-fontsize";>
					<?php dp_breadcrumbs_output(); ?>
				</section>
                <?php endif; ?>
               

	<section id="dp-mainbody-columns" <?php echo $dp_mainbody_class; ?>>			
			<section>
            <div id="dp-content-wrap">