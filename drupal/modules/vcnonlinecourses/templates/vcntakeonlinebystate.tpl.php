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
 * Default theme implementation to display a online courses.
 * 
 */
?>
<h1 class="title-top">Take a Course Online</h1>
<div id="content-area">
	<div class="container" >
		<div id="accordion">
			<h3 class="courses-listing-title" > Virtual High Schools </h3>
			<div class="courses-listing-detail">
				<p>Don't have a high school diploma or GED? Don't worry if you do not. We can help. A "virtual" high school
				    is one you can attend online to get your diploma. Click on the name of the virtual high school in your state to find detailed information about
				    what you need to do; most "virtual" high school programs are free to state residents.
				</p>
				<div>			     
				  <div><strong>State Name Starting with:</strong> <span>&nbsp;&nbsp;<?php  echo $alphabetListing; ?></span></div><br/>
				  <div class="indentonline">
				  	<?php						
						foreach ($provider_list as $state_name => $p_list) {?>
							<div class="nodot"><strong><?php echo $state_name; ?><br/><br/></strong></div><?php 
							foreach ($p_list as $key => $value) {?>
								<?php echo $value; ?><br/><br/><?php 
							}
						}
				    ?>
				  </div>					
			   </div>
			</div>		
			<h3 class="courses-listing-title" > General Educational Development, or GED Test </h3>
			<div class="courses-listing-detail">
				<p>Another alternative is to earn a GED certificate (equivalent to a high school diploma) by passing a test. Information about obtaining a GED and taking the GED test can be found by 
			     <?php echo vcn_convert_urls_to_links("http://www.acenet.edu/AM/Template.cfm?Section=GED_TS", "clicking here"); ?>. 
			     Or <?php  $pdfpath =  vcn_drupal7_base_path().drupal_get_path("theme", "vcnstark"); echo vcn_build_link_window_opener($pdfpath."/media/GED_Testing_Program_Fact_Sheet_v2_2010.pdf", "click here", false, false, ""); ?> for a printable fact sheet.
			    </p>
			</div>
		</div>
	</div>
</div>
<!-- VCN Navigation bar -->
      <div class="vcn-user-navigation-bar allclear">
      	<div class="nav-bar-left"><div><a title="Go Back" href="javascript:history.go(-1);" >Go Back</a></div></div>	      	
      	<div class="nav-bar-right"><div>&nbsp;</div></div>		
      	<div class="allclear"></div>		      	
      </div>
<!-- End of VCN Navigation bar -->	