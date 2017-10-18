
<?php if (! is_page (['13829','13847']) ): // page: south-charlotte pages  ?> 
<div class="clinic-container">
    <div class="clinic-locator-button mobile mobile-yes">
        <a class="odd-btn mobile mobile-yes">
            <img src="/wp-content/uploads/2016/07/LCA_Clinic_Locator_Icon.png" width="25" align="left"/> 
        Clinic Locator
        </a>
    </div>
    <div class="locate-me">
        <?php echo do_shortcode('[clinic-near-you]'); ?>
    </div>
    <div class="dp-page">
        <div class="clinic-locator-button desktop mobile-no">
            <a id="open-locator" class="odd-btn desktop"></a>
            <div class="crowdfunding-campaign-container">
                <div class="crowdfunding-campaign-container-inner">
                    <a href="/become-an-investor" class="crowdfunding-campaign">
                        Join us on our journey!  We are currently allowing the public and our friends and family to invest in our equity crowdfunding.  CLICK HERE to learn more.
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
<?php else: ?>

<div class="clinic-container">
    <div class="clinic-locator-empty mobile mobile-yes">





    </div>
</div>



<?php endif; // is_page( '13829' ) page: south-charlotte pages  ?> 
 