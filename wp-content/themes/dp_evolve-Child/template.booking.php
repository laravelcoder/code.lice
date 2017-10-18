<?php
global $dynamo_tpl;

$location      = get_sl_location( $_GET['reference_number'] );
$booking_image = get_booking_image();

$fullwidth = TRUE;

dp_load( 'header' );
dp_load( 'before', NULL, [ 'sidebar' => FALSE ] );
?>
    <div class="booking-content" id="dp-mainbody">
        <div class="container">
            <main class="booking-main">
                <div class="title-block vc_col-sm-12"
                     style="text-align: center;">
                    <h1 class="color-blue">BOOK AN APPOINTMENT</h1>
                    <p>All fields are required.</p>
                </div>

                <div class="book-container clearfix" id="book-container">
                    <div class="vc_col-sm-9 book-left-block no-pad"
                         id="block-top">
						<?php echo do_shortcode( '[contact-form-7 id="4915" title="Booking Form"]' ); ?>
                        <div class="book-confirmation">
                            <h4 class="confirmation-thank-you"></h4>
                            <p class="confirmation-content">
								<?php
								$thankyou_slug = 'booking-thank-you';

								$args = [
									'name'        => $thankyou_slug,
									'post_type'   => 'page',
									'post_status' => 'publish',
									'numberposts' => 1,
								];

								$thankyou_post = get_posts( $args );
								if ( $thankyou_post ) :
									echo( $thankyou_post[0]->post_content );
								endif;
								?>
                            </p>
                            <div class="button-set">
                                <a href="<?php echo site_url(); ?>">RETURN TO
                                    HOMEPAGE</a>
                            </div>
                        </div>
                    </div>

                    <div class="vc_col-sm-3 book-right-block no-pad">
                        <div class="clinic-image-block"
                             style="background-image: url('<?php echo $booking_image ?>');">
                            <div class="overlay">
                                <h4>REQUESTED CLINIC:</h4>
                                <h3><?php echo $location->store; ?></h3>
                            </div>
                        </div>
                        <div class="clinic-information-block">
                            <h4 id="static-clinic-name"><?php echo $location->store; ?>
                                :</h4>
                            <div class="c-box">
                                <h5>Phone Number:</h5>
                                <p id="static-clinic-phone"><?php echo $location->phone; ?></p>
                                <h5>Email:</h5>
                                <p id="static-clinic-email"><?php echo $location->email; ?></p>
                                <h5>Address:</h5>
                                <p id="static-clinic-address"><?php echo $location->address; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="lca_map" style="visibility: hidden;" />
              </main>

                <script type="text/javascript">
                    (function ($) {
                        jQuery(document).ready(function() {
                            //Hide the confirmation to start
                            jQuery(".book_confirmation").hide();

                            //Setup the datepicker
                            jQuery('#datepicker').datepicker({
                                dateFormat: "MM dd, yy",
                                prevText: "",
                                nextText: "",
                                onSelect: function (dateText, inst) {
                                    jQuery('#date-input').val(dateText);
                                }
                            });

                            jQuery("[name=customername]").blur(function () {
                                var name = jQuery("[name=customername]").val().split(' '),
                                    firstName = name[0];
                                console.log(firstName);
                                jQuery('#client-firstname').val(firstName);
                            });

                            //Init todays date in the hidden date field
                            var the_date = new Date().toJSON().slice(0, 10);
                            jQuery('#date-input').val(the_date);


                            //Populate hidden fields 'Number of Family Members', 'How Long', and 'Requested Time'
                            jQuery('ul.num-family li .btn').on('click', function () {
                                jQuery('ul.num-family li .btn').removeClass('active');
                                jQuery('#family-number').val(jQuery(this).html());
                                jQuery(this).addClass('active');
                            });


                            jQuery('ul.how-long li .btn').on('click', function () {
                                jQuery('ul.how-long li .btn').removeClass('active');
                                var value = jQuery(this).data('id');
                                jQuery('#how-long').val(value);
                                jQuery(this).addClass('active');
                            });

                            jQuery('.time-block li .btn').on('click', function () {
                                jQuery('.time-block li .btn').removeClass('active');
                                var value = jQuery(this).data('id');
                                jQuery('#time-input').val(value);
                                jQuery(this).addClass('active');
                            });

                            //Populate some hidden fields

                            $('#requested-clinic').val("<?php echo $location->store; ?>");
                            $('#clinic-id').val("<?php echo $_GET['reference_number'] ?>");
                            $('#clinic-email').val("<?php echo $location->email; ?>");
                            $('#clinic-address').val("<?php echo $location->address; ?>");
                            $('#clinic-phone').val("<?php echo $location->phone; ?>");
                            $('#clinic-textnum').val("<?php echo $location->description; ?>");
                        })
                    })(jQuery);
            </script>
        </div>
    </div>
<?php

dp_load( 'after-nosidebar', NULL, [ 'sidebar' => FALSE ] );

if ( is_page (['13829','13847','3295','7131']) ):  

    get_template_part( 'layouts/bare-footer' );  

else:

    dp_load('footer');

endif; 
