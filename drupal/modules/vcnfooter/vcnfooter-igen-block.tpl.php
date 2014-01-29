<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php
/**
 * @file
 *   Themes the vcn footer block
 */
?>
<?php if (vcn_footer_links_should_display()) { ?>
<div id="vcn-footer">
  <div id="vcn-footer-navigation">
    <br/>
      <ul class="vcn-footer-links">
        <li><a href="/index.php" title="Main Site">VCN.org</a></li>
        <li><a href="<?php echo $vcn_drupal7_base_path; ?>" title="<?php print vcn_get_industry_name(); ?> Home">Home</a></li>
        <li><a href="<?php echo $vcn_drupal7_base_path; ?>get-started" title="Understand how VCN helps you prepare for a better job.">Get Started</a></li>
        <li><a href="<?php echo $vcn_drupal7_base_path; ?>explorecareers" title="Choose a high-growth career after reviewing details such as expected salary, typical educational qualifications and other requirements.">Choose a Career</a></li>
        <li><a href="<?php echo $vcn_drupal7_base_path; ?>get-qualified" title="Review education, training, certifications and licenses required to enter each high growth career.">Get Qualified</a></li>
        <li><a href="<?php echo $vcn_drupal7_base_path; ?>findwork" title="Search for local or national jobs in your chosen career.">Find a Job</a></li>
        <li><a href="<?php echo $vcn_drupal7_base_path; ?>pla/getting-started" title="Get free college credits for previous work experience and military or professional training.">College Credits</a></li>
        <li><a href="<?php echo $vcn_drupal7_base_path; ?>career-tools" title="Links to various online career preparation and education tools plus a directory of support organizations that can assist you in your career development.">Career Tools</a></li>   
        <li class="last">
        <?php
        if($GLOBALS['is_user_logged_in']) {
          echo '<a class="vcn-sign-out" href="' . vcn_drupal6_base_path() . 'logout" alt="Sign Out" title="Sign Out">Sign Out</a>';
        } else {
          echo '<a href="' . vcn_drupal6_base_path() . 'user" alt="Sign In" title="Sign In">Sign In</a>';
        }
        ?>
        </li>
      </ul>
      <ul class="vcn-footer-links">
        <li><a href="<?php echo $vcn_drupal7_base_path; ?>about-us" title="About Us">About Us</a></li>
        <li><a href="<?php echo $vcn_drupal7_base_path; ?>site-map" title="Site Map">Site Map</a></li>
        <li><a href="<?php echo $vcn_drupal7_base_path; ?>privacy-policy" title="Privacy Policy">Privacy Policy</a></li>       
        <?php if (!$GLOBALS['is_user_logged_in'] || $GLOBALS['user_provider_id'] > 0) : ?>
          <li><a class="provider-portal" href="<?php echo $vcn_drupal7_base_path; ?>provider/summary" title="Provider Portal">Provider Portal</a></li>
        <?php endif; ?>
        <li><a href="<?php echo $vcn_drupal7_base_path; ?>community/groups" title="Community Groups">Communities</a></li>      
        <li>
        	<div id="tell-us-dialog-form" style="display:none;"> </div>
	          <a href="javascript:void(0);" onclick="tellUsWhatYouThink('<?php global $user; echo $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]; ?>','<?php if($user->uid != 0){echo $user->mail;} ?>','<?php if($user->uid != 0){echo $user->uid;} ?>','<?php echo  date("Y-m-d,H:i:s");     ?>');"title="Tell Us What You Think">
	            Feedback<!-- <div class="tell-us-btn"></div> -->
	          </a>
        </li>    
        <li class="last"><a href="<?php echo $vcn_drupal7_base_path; ?>resources" title="Resources">Resources</a></li>
      </ul>   
  </div>
  <div id="vcn-footer-connect">   	
      <ul class="vcn-footer-links"> 
        <li class="last footer-download-links"><span>Download</span><br/>
        	<a href="http://www.adobe.com/go/getreader" class="extlink extlink-no-css" title="Download Adobe PDF Reader"><img alt="Download Adobe PDF Reader" title="Download Adobe PDF Reader" src="<?php echo vcn_image_path(); ?>miscellaneous/icon_PDF.bmp"></a>         
        	<a href="http://www.microsoft.com/en-us/download/details.aspx?id=6" class="extlink extlink-no-css" title="Download Microsoft PowerPoint Viewer"><img alt="Download Microsoft PowerPoint Viewer" title="Download Microsoft PowerPoint Viewer" src="<?php echo vcn_image_path(); ?>miscellaneous/icon_PP.bmp"></a>       
        	<a href="http://www.microsoft.com/en-us/download/details.aspx?id=4" class="extlink extlink-no-css" title="Download Microsoft Word Viewer"><img alt="Download Microsoft Word Viewer" title="Download Microsoft Word Viewer" src="<?php echo vcn_image_path(); ?>miscellaneous/icon_Word.bmp"></a>         
        	<a href="http://www.microsoft.com/en-us/download/details.aspx?id=10" class="extlink extlink-no-css" title="Download Microsoft Excel Viewer"><img alt="Download Microsoft Excel Viewer" title="Download Microsoft Excel Viewer" src="<?php echo vcn_image_path(); ?>miscellaneous/icon_Excel.bmp"></a>
        </li>
              
        <li class="last">          
          <!-- Configuring Add This info at https://www.addthis.com/help/destinations -->
          <!-- AddThis Button BEGIN 
          <div class="addthis_toolbox addthis_default_style ">
            <a class="addthis_button_compact"><div class="vcn-share-btn"></div></a>
          </div>
          <script type="text/javascript" src="https://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4e6a6c760f55fe46"></script>-->
          <!-- AddThis Button END -->
          <div class="vcn-footer-share">
		<!-- AddThis Button BEGIN -->
		<div class="addthis_toolbox addthis_default_style addthis_16x16_style">
		<a class="addthis_button_facebook"><span id="vcn-facebook"></span></a>
		<a class="addthis_button_twitter"><span id="vcn-twitter"></span></a>
		<a class="addthis_button_linkedin"><span id="vcn-linkedin"></span></a>
		<!-- <a class="addthis_button_email"><span id="vcn-email"></span></a> -->
		<a class="addthis_button_compact"><span id="vcn-share-plus"></span></a><!-- <a class="addthis_counter addthis_bubble_style"></a> -->
		</div>
		<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4e6a6c760f55fe46"></script>
		<!-- AddThis Button END -->
            </div>
        </li>
      </ul>  
  </div>
</div>
<?php } else { ?>
<br/><br/><hr/>
<?php } ?>
<div id="copyright">
  <a class="dol-seal" href="javascript:popit('http://dol.gov')" title="Department of Labor">
    <img  alt="department of labor seal" src="<?php echo vcn_image_path() ?>site_logo_images/logo_dol.jpg" alt="Department of Labor" />
  </a>
  <p class="copyright-text">
    The <?php echo vcn_get_industry_name() == "Green" ? "IGEN" : vcn_get_industry_name(); ?> VCN initiative is sponsored by the U.S. Department of Labor, Employment and Training Administration and the Illinois Green Economy Network under the leadership of the American Association of Community Colleges
    <br /><br />
    <a class="aacc-logo" href="javascript:popit('http://www.aacc.nche.edu')" title="American Association of Community Colleges">
    	<img src="<?php echo vcn_image_path() ?>site_logo_images/aacc2.jpg" alt="American Association of Community Colleges" />
  	</a>
  	<br /><br />
    Copyright &copy; <?php echo date("Y")?> American Association of Community Colleges
  </p>
  <a class="foundation-footer-logo" href="javascript:popit('http://www.igencc.org/')" title="IGEN" style="padding-top:15px; width:auto;">
    <img src="<?php echo vcn_industry_image_path() ?>home_images/logo-IGEN-notext.png" alt="IGEN" style="height:66px; width:auto;" />
  </a>
</div>
<div style="clear:both;"></div>