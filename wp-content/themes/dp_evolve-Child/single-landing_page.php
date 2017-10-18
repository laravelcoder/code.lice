<?php
global $dynamo_tpl;

$fullwidth = true;

dp_load('header');
dp_load('before', null, array('sidebar' => false));
dp_load('clinic-locator-button');
?>

<div id="dp-mainbody">
    <main>
        <h2 class="cool-title"><?php echo get_the_title(); ?></h2>

        <!--<h2 class="landing-title multiple-treatments"><mark><?php //echo get_the_title();    ?></mark></h2>-->

        <div class="clearfix"></div>

        <div class="row vc_col-sm-6 pull-right">
            <div class="center">
                <div class="urgent-care inner">
                    <h3>URGENT CARE FOR LICE REMOVAL™</h3>
                    <h4 class="green">Call today for an appointment!<br />
                        <a href="tel:<?php the_field('phone'); ?>"><strong><?php the_field('phone'); ?></strong></a></h4>
                    <p class="address-yes">
                        <?php the_field('address'); ?>          
                    </p>
                    <?php if ((strtolower(get_field('use_booking_tool')) == "yes") && (!empty(get_field('booking_tool')))) { ?>
                        <br />
                        <h4 class="book-an-appointment-container">
                            <a class="btn dark-grey" style="background: #F8ED33; color: #000 !important;" href="/book-appointment?reference_number=<?php echo the_field('booking_tool'); ?>">
                                <span class="book-an-appointment">
                                    <i class="fa fa-plus-circle" aria-hidden="true" style="color: #000;"></i> Book An Appointment
                                </span>
                            </a>
                        </h4>
                        <br />
                    <?php } ?>

                    <div id="LiceGirls">
                        <h4>For more information, visit our website: <br />
                            <a href="http://<?php the_field("more_link") ?>" target="_blank"><?php the_field("more_link") ?></a></h4>
                        <h4>Operated by: <br />
                            <span class="green"><?php the_field("display_name") ?></span></h4>
                    </div>

                </div>
            </div>
        </div>

        <div class="clearfix mobile tablet"></div>

        <div class="row vc_col-sm-6">
            <div class="">

                <h3 align="center">See our treatment process in action below</h3>
                <iframe src="https://player.vimeo.com/video/178269527" width="100%" height="315" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>

            </div>
        </div>

        <div class="clearfix"></div>

        <div>
            <?php
            $terms = get_the_terms($post, 'services');
            $a = 1;

            foreach ($terms as $term):
                ?>
                <?php $term_obj = get_term($term); ?>
                <?php $id = str_replace('-', '', str_replace(' ', '', $term_obj->name)); ?>
                <div class="vc_col-sm-4">
                    <div class="landing-pad">
                        <?php echo '<div class="landing-services-header"><h3>' . $term_obj->name . '</h3></div>'; ?>
                        <div class="landing-services-desc">
                            <?php $images = get_field("images", $term_obj); ?>
                            <?php foreach ($images as $image): ?>
                                <div class="landing-image">

                                    <img width="100%" src="<?php echo $image['url']; ?>" class="example-image attachment-thumbnail" alt="<?php echo $image['alt']; ?>" sizes="(max-width: <?php echo $image['width']; ?>px) 100vw, <?php echo $image['width']; ?>px">
                                </div>
                            <?php endforeach; ?>

                            <p>
                                <?php echo $term_obj->description; ?>
                            </p>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
            <div class="vc_col-sm-4">
                <div class="landing-pad">  

                    <div class="landing-services-header">
                        <h3>Moms Talk About Head Lice</h3>
                    </div>  

                    <iframe src="https://player.vimeo.com/video/178232235" width="100%" height="171" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                    <div class="landing-services-desc">

                        <p style="margin-top: 15px;">Moms love us because...</p>

                        <ol>
                            <li>We end the nightmare in a single treatment.</li>
                            <li>We guarantee our service.</li>
                            <li>We get them back to what’s important in life.</li>
                        </ol>

                        Get the Facts:  <a href="http://staging.liceclinicsofamerica.com/wp-content/uploads/2016/08/LCA_GetTheFacts.pdf" target="_blank">See our comparison chart</a>
                    </div>
                </div>
            </div>



        </div>

        <div class="clearfix"></div>


        <div class="wpb_column vc_column_container vc_col-sm-12">
            <?php if (get_field('use_default_content') == "yes") { ?>

                <div class="vc_col-sm-4">
                    <div class="inner space">
                        <?php the_field('preferred_provider_clinic', 'options'); ?>
                        <div class="clearfix"></div>
                        <?php
                        $feat = get_field('featured_img');

                        if (!empty($feat)):
                            ?>

                            <img src="<?php echo $feat['url']; ?>" alt="<?php echo $feat['alt']; ?>" width="100%" />

                        <?php endif; ?>

                    </div>
                </div>
                <div class="vc_col-sm-4">

                    <div class="inner space">
                        <?php the_field('urgent_care_for_lice_removal', 'options'); ?>
                    </div>
                </div>

                <div class="vc_col-sm-4">
                    <div class="inner space">
                        <?php the_field('guarantee', 'options'); ?>
                    </div>
                </div>

            <?php }else { ?>


                <div class="inner space">
                    <h2>Preferred Provider Clinic</h2>
                    <?php the_field('preferred_provider_clinic_copy'); ?>
                    <?php
                    $feat = get_field('feature_img');

                    if (!empty($feat)):
                        ?>

                        <img src="<?php echo $feat['url']; ?>" alt="<?php echo $feat['alt']; ?>" class="feat" />

                    <?php endif; ?>
                </div>
            </div>
    </div>

    <div class="vc_col-sm-4">
        <div class="inner space">
            <h2>Urgent Care for Lice</h2>
            <?php the_field('urgent_care_for_lice_removal_copy'); ?>
        </div>
    </div>

    <div class="vc_col-sm-4">
        <div class="inner space">
            <h2>Guarantee</h2>
            <?php the_field('guarantee_copy'); ?>
        </div>
    </div>
<?php } ?>

</div>

</div>




</main>
</div>
<?php
dp_load('after-nosidebar', null, array('sidebar' => false));
dp_load('footer');

// EOF
?>
