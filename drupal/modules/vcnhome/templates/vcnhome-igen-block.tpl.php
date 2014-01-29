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
 *   Themes the VCN Igen Home block
 */
?>
<div>
  <div id="igen-home-top-img">	
  <?php for($i=1; $i <= $home_banner_count; $i++) {?>
	<div class="" id="fp-container<?php echo $i; ?>">
	  <img src="<?php echo $vcn_industry_image_path;?>home_images/home-banner-<?php echo $i; ?>.png" alt="home" width="960px" height="350px" border="0" usemap="#map"  />
	  <map name="map">
		<area shape="rect" coords="135,186,377,284" alt="Choose a Career" href="<?php echo $vcn_drupal7_base_path;?>explorecareers" title="Get Started Now! Choose a Career" />	
	  </map>	  
	</div>
 <?php }?>	
 
 <div id="fp-container-pagination"> 
 <?php for($i=1; $i <= $home_banner_count; $i++) {?> 	
	<div class="floatleft">
	  <a id="fp-container-pagination<?php echo $i-1; ?>" onclick="display(<?php echo $i-1; ?>);" href="javascript:void(0);" alt="Learn" title="Learn">&nbsp;&nbsp;</a>
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
  <div id="igen-home-middle">  
	<div class="igen-home-submain">
		<div class="igen-home-submain-left">			
			<div class="igen-home-submain-left-box">
				<img src="<?php echo $vcn_industry_image_path;?>home_images/thumb-why-green.jpg" title="Why-green" width="130px" height="130px" class="igen-home-img">
				<h4>Why Green?</h4>
				<p class="igen-home-submain-box-text">
					<?php print $whyindustry; ?><a title="Why Green" href="<?php echo $vcn_drupal7_base_path;?>why-industry">Read more</a>
				</p>
			</div>
			<div class="igen-home-submain-left-box">
				<img src="<?php echo $vcn_industry_image_path;?>home_images/thumb-new-to-vcn.jpg" title="New to VCN" width="130px" height="130px" class="igen-home-img">
				<h4>New to VCN?</h4>
				<p class="igen-home-submain-box-text">
					The Primary goal of VCN is to prepare you for your desired Career, which can be achieved in 3 major steps. <a title="New to VCN" href="<?php echo $vcn_drupal7_base_path;?>new-to-vcn">Read more</a>
				</p>
			</div>
			<div class="allclear"></div>			
		</div>
		<div class="igen-home-submain-right noresize">
			<?php echo $hometext; ?>		  			
		</div>
	</div>
  </div>
  <div class="allclear"></div>   
</div>
