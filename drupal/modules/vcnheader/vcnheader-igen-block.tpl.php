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
 *   Themes the vcn header block
 */

global $user;

if (isset($_GET['debug']) && strlen($_GET['debug']) && array_search("administrator", $user->roles) == true) {  // Values to be display via browser view source for debugging ?><!--
is_provider_user=<?php echo $is_provider_user_text; ?>;
vcn_user_id=<?php echo $vcn_user_id; ?>;
d6_user_id=<?php echo $d6_user_id; ?>;
d6_user_name=<?php echo $d6_user_name; ?>;
vcn_notebook_item_count=<?php echo $vcn_notebook_item_count; ?>;
is_logged_in_user=<?php echo $is_logged_in_user_text; ?>;
has_notebook_items=<?php echo $has_notebook_items_text; ?>;
logged_in_user_default_zipcode=<?php echo $logged_in_user_default_zipcode; ?>;
vcn_config_php_server_name=<?php echo $vcn_config_php_server_name; ?>;
current_drupal_version=<?php echo $current_drupal_version; ?>;
current_moodle_version=<?php echo $current_moodle_version; ?>;
--><?php 
}

if (vcn_header_footer_should_display()) { 
   ?><div id="vcn-igen-header" class="noresize" >
	  <div id="igen-header-topbar" >
	  	<div class="igen-header-left"><a href="<?php echo vcn_drupal7_base_path(); ?>" title="<?php echo vcn_get_industry_name(); ?> Home"><div id="igen-header-logo" ></div></a></div>
	  	<div class="igen-header-right">
	  		<div class="igen-header-right-inner-top noresize" >
	  			<?php if (drupal_is_front_page()) { ?>	
          			<a href="http://www.igencc.org/" target="_blank" title="Illinois Green Economy Network"><img class="igen-logo" src="<?php echo $vcn_industry_image_path;?>home_images/logo-IGEN.png" alt="igen logo" border="0" /></a>
	  			<?php }else { ?>
			  			<div id="vcn-header-welcome-links" class="noresize">
				      	  <?php if (!$is_logged_in_user) { ?>	        
				      	    <a title="MyVCN" class="my-vcn-link-off" href="#">MyVCN</a>
				            <a title="Sign In" href="<?php echo vcn_drupal6_base_path(); ?>user">Sign In</a> 
				            <a title="Zoom Page" id="zoompage" href="javascript:void(0);"><span class="font-smaller noresize">A</span>A<span class="font-larger noresize">A</span></a> 
				          <?php }else{ ?>                
				            <span class="welcome-message noresize"><?php print $welcome_message; ?></span>
				            <a title="MyVCN" href="<?php echo vcn_drupal7_base_path(); ?>cma/career-wishlist/my-vcn" >MyVCN</a>
				            <a title="Sign Out" class="vcn-sign-out" href="<?php echo vcn_drupal6_base_path(); ?>logout">Sign Out</a> 
				            <a title="Zoom Page" id="zoompage" href="javascript:void(0);"><span class="font-smaller noresize">A</span>A<span class="font-larger noresize">A</span></a>           
					      <?php } ?>	      	
					    </div> 		  			
			  	<?php } ?>	  			
	  		</div>
	  		<div class="igen-header-right-inner-bottom allclear">
	  		<?php if (!drupal_is_front_page()) { ?>	
				<ul>
	  			  <li>
	        	    <a href="<?php echo vcn_drupal7_base_path(); ?>cma/careers/wishlist" title="Keeps track of the careers and training programs you like as you explore careers, education and jobs."><div class="vcn-button career-wishlist-btn noresize">Career Wishlist</div></a>
	              </li> 
	               <!--<li>
	                <?php print render($searchbox); ?>
	              </li>     -->	 
	          </ul>
	          <?php } ?>
	        </div>		
	  	</div>
	  </div>
	  <div id="igen-header-bottombar" > 
	      <div id="vcn-header-links" class="front">
	  	    <div id="<?php echo $headerlinksfront;?>"></div>
	        <div id="vcn-primary-links">
	    	  <ul class="menu">
	      	    <?php echo $left_link_string; ?> 
	          </ul>
	        </div>
	        <div id="vcn-secondary-links">
	    	  <ul class="menu">
	            <div id="vcn-header-links-end"></div>
	            <?php echo $right_link_string; ?> 
	          	  <li class="leaf last vcn-header-links-search">
	        	    <?php print render($searchbox); ?>
	              </li>
	          </ul>
	        </div>
	      </div>
	   </div>   
	   <div class="allclear"></div>
   </div>
<?php } // vcn_display_header_footer() ?>