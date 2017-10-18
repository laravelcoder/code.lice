<?php

	/**
	 *
	 * Template elements before the page content
	 *
	 **/

	// create an access to the template main object
	global $dynamo_tpl;
	if (!is_search()) $params = get_post_custom();
	$params_title = isset($params['dynamo-post-params-title']) ? esc_attr( $params['dynamo-post-params-title'][0] ) : 'Y';
	$params_custom_headerbg =  isset( $params['dynamo-post-params-header_img'] ) ? esc_attr( $params['dynamo-post-params-header_img'][0] ) : '';
	$params_subheader_use =  isset( $params['dynamo-post-params-subheader_use'] ) ? esc_attr( $params['dynamo-post-params-subheader_use'][0] ) : 'Y';
	$params_custom_subheaderbg =  isset( $params['dynamo-post-params-subheader_img'] ) ? esc_attr( $params['dynamo-post-params-subheader_img'][0] ) : '';								    $params_custom_title =  isset( $params['dynamo-post-params-custom_title'] ) ? esc_attr( $params['dynamo-post-params-custom_title'][0] ) : '';
	$params_custom_subtitle =  isset( $params['dynamo-post-params-custom_subtitle'] ) ? esc_attr( $params['dynamo-post-params-custom_subtitle'][0] ) : '';

	// disable direct access to the file
	defined('DYNAMO_WP') or die('Access denied');

	// check if the sidebar is set to be a left column
	$args_val = $args == null || ($args != null && $args['sidebar'] == true);

	$dp_mainbody_class = '';

	if(get_option($dynamo_tpl->name . '_sidebar_position', 'right') == 'left' && dp_is_active_sidebar('sidebar') && $args_val) {
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

                    <a href="#" id="dp-mainmenu-toggle"><i class="icon-menu-1"></i></a>

                <!--    <a href="tel:8445423247" class="hotline-bling" style="text-align: center;">Head Lice Hotline<br /><i class="fa fa-phone" aria-hidden="true"></i> 844-LICE 24 7</a> -->

                    <?php dp_load('buttons'); ?>
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
        <div class="dp-secondary-menu-wrapper sf-grey">
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
        <?php if(is_single() || is_page() ) : ?>
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
        <h1 class="main-title"><?php the_title(); ?></h1>
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

        <?php if(is_category() ) : ?>
        <div class="dp-subheader-wraper">
        <section class="dp-subheader dp-page ">
        <?php if(get_option($dynamo_tpl->name . '_archive_title_state') == 'Y') : ?>
        <h1 class="main-title"><?php	echo		'<span>' . single_cat_title( '', false ) . '</span>' ?></h1>
        <?php endif; ?>
        <?php if(dp_show_breadcrumbs()) : ?>
			<section id="dp-breadcrumb-fontsize";>
				<?php dp_breadcrumbs_output(); ?>
			</section>
        <?php endif; ?>
        </section>
        </div>
        </div>
        <?php endif; ?>
        <?php endif; ?>

        <!--   End subheader wrapper -->

<div class="clearboth"></div>
<section class="dp-page-wrap">
				<!-- Mainbody, breadcrumbs -->


	<section id="dp-mainbody-columns" <?php echo $dp_mainbody_class; ?>>
			<section>
            <div id="dp-content-wrap">
