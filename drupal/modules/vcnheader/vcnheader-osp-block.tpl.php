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
 * Themes the vcn header block for OSP pages
 */

global $user;

if (isset($_GET['debug']) && strlen($_GET['debug']) && array_search("administrator", $user->roles) == true) {  // Values to be display via browser view source for debugging ?><!--
is_provider_user=<?php echo $is_provider_user_text; ?>;
vcn_user_id=<?php echo $vcn_user_id; ?>;
drupal_user_id=<?php echo $drupal_user_id; ?>;
drupal_user_name=<?php echo $drupal_user_name; ?>;
is_logged_in_user=<?php echo $is_logged_in_user_text; ?>;
logged_in_user_default_zipcode=<?php echo $logged_in_user_default_zipcode; ?>;
vcn_config_php_server_name=<?php echo $vcn_config_php_server_name; ?>;
current_drupal_version=<?php echo $current_drupal_version; ?>;
current_moodle_version=<?php echo $current_moodle_version; ?>;
--><?php 
}

if (vcn_header_footer_should_display()) { 
   ?><div id="vcn-industry-header" class="noresize" >
	  <div id="industry-header-topbar" >
	  	<div class="industry-header-left"><a href="<?php echo vcn_drupal7_base_path(); ?>" title="<?php echo vcn_get_industry_name(); ?> Home"><div id="header-osp-logo" ></div></a></div>
	  	<div class="industry-header-right">
	  		<div class="industry-header-right-inner-top noresize" >
          <div id="vcn-header-welcome-links" class="noresize">
              <?php if (!$is_logged_in_user) { ?>	        
                <a title="Sign In" href="<?php echo vcn_drupal7_base_path(); ?>user">Sign In</a> 
                <a title="Zoom Page" id="zoompage" href="javascript:void(0);"><span class="font-smaller noresize">A</span>A<span class="font-larger noresize">A</span></a> 
              <?php }else{ ?>                
                <span class="welcome-message noresize"><?php print $welcome_message; ?></span>
                <a title="Sign Out" href="<?php echo vcn_drupal7_base_path(); ?>user/logout">Sign Out</a> 
                <a title="Zoom Page" id="zoompage" href="javascript:void(0);"><span class="font-smaller noresize">A</span>A<span class="font-larger noresize">A</span></a>           
            <?php } ?>	      	
          </div> 		  			  			
	  		</div>
	  		<div class="industry-header-right-inner-bottom allclear"></div>		
	  	</div>
	  </div>
	  <div id="industry-header-bottombar" > 
	      <div id="vcn-header-links" class="front">
	  	    <div id="<?php echo $headerlinksfront;?>"></div>
	        <div id="vcn-primary-links">
	    	  <ul class="menu">
	      	    <?php echo $left_link_string; ?> 
	          </ul>
	        </div>
	        <div id="vcn-secondary-links">
	    	  <ul class="menu">
	            <div id="<?php echo $headerlinksend;?>"></div>
	             <li class="leaf last vcn-header-links-search">
        	    	<?php print render($searchbox); ?>
              	</li>
	          </ul>
	        </div>
	      </div>
	   </div>   
   </div>
<?php } // vcn_display_header_footer() ?>