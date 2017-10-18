 <div class="social-tablet">

    <?php echo do_shortcode('[social]');?>

 </div>

 <div class="top-buttons">
    <ul class="connect">
      <li class="help">
          <a href="#">Help Now</a>

          <div class="mcTooltipWrapper hidden" style="position:absolute; z-index:999; display: none; padding: 18px;  visibility: visible; transition: all 300ms;">
              <div id="mcTooltip" class="" style="transition: none; width: 287px; height: auto;">
                <h2>We're Here To Help</h2>
                <div class="main-copy">
                  <p>No Parent should have to go through this alone.</p>
                  <p>Speak directly to your local clinician.</p>
                  <p><a class="btn" href="<?php bloginfo('siteurl'); ?>/clinics-near-you/">Get Help Here</a></p>
                </div>
              </div>
              <div id="mcttCo" style="height: 18px; width: 27.9px; display: none; transition: none; left:165.5px; top: 2px;"><b style="height: 18px; width: 18px; transform: translate(4.5px, 9px) rotate(45deg); background-color: #818286;"></b></div><div id="mcttCloseButton" style="display: none;"></div>
          </div>

      </li>
      <li><a href="<?php bloginfo('siteurl'); ?>/clinics-near-you/">Find A Clinic</a></li>
      <li class="pricing">
        <a href="#">Pricing</a>

        <div class="mcTooltipWrapper hidden" style="position:absolute; z-index:999; display: none; padding: 18px;  visibility: visible; transition: all 300ms;">
            <div id="mcTooltip" class="" style="transition: none; width: 287px; height: auto;">
              <h2>Pricing</h2>
              <div class="main-copy">
                <p>Lice Clinics of America offers solutions for all infestation levels at a price to suit your family's budget.  </p>
                <p>Treatment options available from $45 to $200.00.</p>
                <p><a class="btn" href="/clinics-near-you/">Book Online Now</a></p>
              </div>
            </div>
            <div id="mcttCo" style="height: 18px; width: 27.9px; display: none; transition: none; left:165.5px; top: 2px;"><b style="height: 18px; width: 18px; transform: translate(4.5px, 9px) rotate(45deg); background-color: #818286;"></b></div><div id="mcttCloseButton" style="display: none;"></div>
        </div>

      </li>

      <li class="connect-item">
        <a href="#">Connect</a>

        <div class="mcTooltipWrapper hidden" style="position: absolute; z-index:999; display: none; padding: 18px;  visibility: visible; transition: all 300ms;">
            <div id="mcTooltip" class="" style="transition: none; width: 287px; height: 140px;">
              <h2>Connect With Us</h2>
              <div class="main-copy">
                  <?php echo do_shortcode('[social]');?>
              </div>
            </div>
            <div id="mcttCo" style="height: 18px; width: 27.9px; display: none; transition: none; left:165.5px; top: 2px;"><b style="height: 18px; width: 18px; border-top: 2px solid #818286; border-left: 2px solid #818286;; transform: translate(4.5px, 9px) rotate(45deg); background-color: #818286;"></b></div><div id="mcttCloseButton" style="display: none;"></div>
        </div>
      </li>
    </ul>
</div>
<div class="navigation">
        <?php
        if(has_nav_menu('mainmenu')) {
        dynamo_menu('mainmenu', 'dp-main-menu', array('walker' => new DPMenuWalker()),'sf-menu');                           }
        else {
            echo 'No menu assigned!';
        }
        ?>

</div>
