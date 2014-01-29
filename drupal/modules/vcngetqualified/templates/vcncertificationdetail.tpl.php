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
 * Default theme implementation to display Get Qualified/Find Learning certification detail page.
 * 
 */
?>
<div id="certification-detail" class="allclear">
  <div id="certification-top">	
    <div id="certification-top-title">
      <div>
        <h1 class="title"><?php echo $name; ?> Certification <?php echo $facebook_like;  ?></h1>			
      </div>      
      <hr class="divider">	
      <br>	
    </div>	
    <div id="certification-save-target" class="noresize">
		<button title="<?php if($is_saved_or_targeted_item > 0){?>Certification has already been saved<?php }else{?>Save this Certification<?php }?>" class="vcn-button save-target-buttons save-button <?php if($is_saved_or_targeted_item > 0){ echo 'vcn-button-disable'; }?>"  id="save-to" <?php if($is_saved_or_targeted_item < 1){?>onclick="vcnSaveTarget('<?php echo $vcn_d7_path;?>cma/ajax/save-target-notebook-item/save/certification/<?php echo $certificationid;  ?>/<?php echo $onetcode;  ?>/00', 'certification', 'save', '<?php echo $vcn_user['vcn_user_id']; ?>', '<?php echo (int) $vcn_user['is_user_logged_in']; ?>', '<?php echo $onetcode;  ?>');"<?php }?> ><?php echo $save_button; ?></button>
	</div>	
    <div class="allclear"></div>
  </div> 
  
  <div>
  	<?php echo $vcn_tabs_header; ?>
	<?php echo $vcn_tabs_body_start; ?>
	
    <p><?php print $description; ?></p>
    
    <?php if (isset($url)) : ?>
      <p>
        <?php print $url; ?> to find out what you need to do to obtain this certification.
      </p>
    <?php endif; ?>
    
    <?php if (isset($org_name)) : ?>
      <p>
        <?php print $org_name; ?>
      </p>
    <?php endif; ?>
      
    <?php if (isset($org_address)) : ?>
      <p>
        <?php print $org_address; ?>
      </p>
    <?php endif; ?>    
    
    <div class="noresize">
    	<br/>
		<button title="<?php if($is_saved_or_targeted_item > 0){?>Certification has already been saved<?php }else{?>Save this Certification<?php }?>" class="vcn-button save-target-buttons save-button <?php if($is_saved_or_targeted_item > 0){ echo 'vcn-button-disable'; }?>"  id="save-to" <?php if($is_saved_or_targeted_item < 1){?>onclick="vcnSaveTarget('<?php echo $vcn_d7_path;?>cma/ajax/save-target-notebook-item/save/certification/<?php echo $certificationid;  ?>/<?php echo $onetcode;  ?>/00', 'certification', 'save', '<?php echo $vcn_user['vcn_user_id']; ?>', '<?php echo (int) $vcn_user['is_user_logged_in']; ?>', '<?php echo $onetcode;  ?>');"<?php }?> ><?php echo $save_button; ?></button>
		<div class="allclear"></div>
	</div>	
    
	<?php echo $vcn_tabs_body_end; ?>
	<!-- VCN Navigation bar -->
      <div class="vcn-user-navigation-bar allclear">
      	<div class="nav-bar-left"><div><a title="Back to <?php echo strpos($_SERVER["HTTP_REFERER"], "/cma/") ? (strpos($_SERVER["HTTP_REFERER"], "/wishlist") ? "Career Wishlist" : "Review Saved Certifications") : "Get Qualified"; ?>" href="<?php echo vcn_drupal7_base_path(); echo strpos($_SERVER["HTTP_REFERER"], "/cma/") ? "cma" : "get-qualified";?>/certifications<?php echo $back_to_wishlist_link_suffix; ?>" >Back to <?php echo strpos($_SERVER["HTTP_REFERER"], "/cma/") ? (strpos($_SERVER["HTTP_REFERER"], "/wishlist") ? "Career Wishlist" : "Review Saved Certifications") : "Get Qualified"; ?></a></div></div>	      	
      	<div class="nav-bar-right"><div>&nbsp;</div></div>		
      	<div class="allclear"></div>		      	
      </div>
    <!-- End of VCN Navigation bar -->		
  </div>
  
</div>        
<div id="current-career-title" class="element-hidden"><?php echo $current_career_title; ?></div> 