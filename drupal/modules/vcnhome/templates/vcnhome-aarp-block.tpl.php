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
 *   Themes the VCN Industry Home block
 */
?>
<div>
  <div id="industry-home-top-img">	
    
    <div id="industry-home-tagline-text1" class="noresize">
      <?php print strtoupper($GLOBALS['vcn_config_default_industry_name']); ?>
    </div>
    <div id="industry-home-tagline-text2" class="noresize">
      <?php print $GLOBALS['vcn_config_default_industry_tagline']; ?>
    </div>
    <a title="Understand how VCN helps you prepare for a better job." href="<?php echo $vcn_drupal7_base_path; ?>get-started">
      <div title="Understand how VCN helps you prepare for a better job." id="industry-home-tagline-button" onclick="window.location.href = vcn_get_drupal7_base_path()+'get-started';" class="noresize">
        <p id="industry-home-tagline-button-line1" class="noresize">Get Started Now!</p>
      </div>
    </a>
    <?php for($i=1; $i <= $home_banner_count; $i++) {?>
    <div class="" id="fp-container<?php echo $i; ?>">
      <img src="<?php echo $vcn_industry_image_path;?>home_images/home-banner-<?php echo $i; ?>.jpg" alt="home" width="960px" height="350px" border="0" />      	  
    </div>
    <?php }?>	
 
    <div id="fp-container-pagination"> 
    <?php for($i=1; $i <= $home_banner_count; $i++) {?> 	
     <div class="floatleft">
       <a id="fp-container-pagination<?php echo $i-1; ?>" onclick="display(<?php echo $i-1; ?>);" href="javascript:void(0);" title="">&nbsp;&nbsp;</a>
     </div>
    <?php }?>	
    </div>	
 
	<!-- Run the javascript to rotate the page-->
	<script type="text/javascript">
		var divIds=new Array(); 
		<?php for($i=1; $i <= $home_banner_count; $i++) {?>
		divIds[<?php echo $i-1; ?>]="fp-container<?php echo $i; ?>"; 		
		<?php }?>	
		display(0);
	</script>
	<!--break-->
  </div>
  <div class="allclear"></div>
  <div id="industry-home-middle">  
	<div class="industry-home-submain">
		<div class="industry-home-submain-left">			
			<div class="industry-home-submain-left-box">
				<a title="Why Focus on 50+" href="<?php echo $vcn_drupal7_base_path;?>why-industry"><img src="<?php echo $vcn_industry_image_path;?>home_images/thumb-why-industry.jpg" title="Why Focus on 50+" width="130px" height="130px" class="industry-home-img" alt="Why Focus on 50+ image">
				<h4>Why Focus on 50+?</h4></a>				
			</div>
			<div class="industry-home-submain-left-box">
				<a title="New to VCN" href="<?php echo $vcn_drupal7_base_path;?>new-to-vcn"><img src="<?php echo $vcn_industry_image_path;?>home_images/thumb-new-to-vcn.jpg" title="New to VCN" width="130px" height="130px" class="industry-home-img" alt="New to VCN image">
				<h4>New to VCN?</h4></a>				
			</div>
			<div class="allclear"></div>			
		</div>
		<div class="industry-home-submain-right">
			<?php echo $hometext; ?>		  			
		</div>
	</div>
  </div>
  <div class="allclear"></div>   
</div>
