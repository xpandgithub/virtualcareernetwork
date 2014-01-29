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
 * Default theme implementation to display a Get Started.
 * 
 */

?>
<h1 class="title">Get Started</h1>
<div id="get-started" >
	<!-- Left sidebar -->
	<div class="vcn-left-sidebar">
		<div class="vcn-content-wrapper">
			<?php echo $getstarted_intro_text; ?>
		</div>	
		<div class="vcn-content-divider-large"></div>
		<div class="vcn-content-wrapper highlighted-box">			
			<div>
				<div class="strong">Start exploring careers by entering the following information:</div>
				<div class="get-started-form">
				<?php echo $form_start_tag;?>				
					<div><?php echo $form_zipcode;?></div>
					<div class="floatleft"><?php echo $form_edu_level;?></div>
					<div class="floatright"><?php echo $form_submit;?></div>
					<div class="allclear"><?php echo $form_hidden;?></div>
				<?php echo $form_end_tag;?>	
				</div> 					
			</div>
		</div>
		<div class="vcn-content-divider-large"></div>
		<div class="vcn-content-wrapper <?php echo (isset($display_career_list) && $display_career_list >= 0) ? "" : "element-hidden";?>">
			<?php if($display_career_list > 0) {?>
			<div class="strong">These local careers match your education level:<br/><br/></div>
			<div>
				<div class="get-started-career-list-title strong allclear"><div>Career</div><div>Salary Range</div><div>Number of Jobs in your area</div></div>
				<?php foreach($career_list->career as $career) { ?>
					<div class="get-started-career-list-data allclear">
						<div class="text-align-left"><a title="<?php echo (string)$career->title;?>" href="<?php echo $vcn_drupal7_base_path."careers/".(string)$career->onetcode;?>"><?php echo (string)$career->title;?></a></div>
						<div class="text-align-left"><?php if($career->pct25annual > 0){ echo vcn_generic_number_formatter((float)$career->pct25annual)." - ".vcn_generic_number_formatter((float)$career->pct75annual)." per year"; }else{ echo "NA"; }?></div>
						<div id="<?php echo $zipcode; ?>/<?php echo (string)$career->onetcode; ?>/<?php echo urlencode(vcn_clean_up_career_title((string)$career->title)); ?>" class="get-started-jobs-count"><img alt="Loading jobs count" src="<?php echo $vcn_image_path;?>miscellaneous/vcn_loading.gif" /></div>
					</div>
				<?php } ?>
			</div>
			<?php }else {?>
			<div class="strong">No matching career was found. However, with a little extra training you will have many more career options. Please Continue to <a href="<?php echo $vcn_drupal7_base_path;?>explorecareers" title="Choose a Career" >Choose a Career</a> - VCN will help you find careers based on your interests.</div>
			<?php }?>	
		</div>	
		<div class="vcn-content-divider-large"></div>
		<?php if($display_career_list > 0): ?>
			<img src="<?php print vcn_image_path(); ?>miscellaneous/get_started_chart.png" alt="Get Started Chart" />
			<p class="align-center strong">Good luck in your future career!</p>
		<?php endif; ?>
     
		<!-- VCN Navigation bar -->
	      <div class="allclear vcn-user-navigation-bar <?php echo (isset($display_career_list) && $display_career_list <= 0) ? 'vcn-user-navigation-bar-extra-top-margin' : '' ?>">
	      	<div class="nav-bar-left"><div><a title="Back to Home Page" href="<?php if(strpos($_SERVER["HTTP_REFERER"], $vcn_drupal7_base_path)) { echo $vcn_drupal7_base_path; ?><?php }else { ?>/index.php<?php } ?>" >Back to Home Page</a></div></div>	      	
	      	<div class="nav-bar-right"><div><button title="Continue to Choose a Career" onclick="location.href='<?php echo $vcn_drupal7_base_path;?>explorecareers';" class="vcn-button vcn-red-button">Continue to Choose a Career</button></div></div>		
	      	<div class="allclear"></div>		      	
	      </div>
	    <!-- End of VCN Navigation bar -->				
	</div>	
	<!-- Right sidebar -->
	<div class="vcn-right-sidebar">
	<?php if($getstarted_sidebar_text != "") { ?>
		<?php echo $getstarted_sidebar_text; ?>
		<div class="vcn-content-divider-large"></div>
		<?php } ?>		
		<?php echo theme('vcn_account_setup_box');?>			
	</div>	
</div>
