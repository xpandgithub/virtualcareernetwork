<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<h1 class="title">Career Exploration Summary</h1>
<div id="vcn-cma-target">
	<div class="vcn-left-sidebar">	
		<div class="vcn-cma-target-congrats">Congratulations! You have now completed the first stage of VCN.</div><br/>
        <div class="strong">Your Target Career is:</div><br/>
		<div id="vcn-cma-target-info" class="rndcrnr">
			<div class="floatleft">
				<div class="tab-header"><?php echo $variables['vcnuser_get_targeted_career']->displaytitle;?></div>
				<div><?php echo $variables['vcnuser_get_targeted_career']->description;?>...</div>
				<div><a title="View Career Details" href="<?php echo $vcn_drupal7_base_path;?>careers/<?php echo $variables['vcnuser_get_targeted_career']->onetcode; ?>" >View Career Details</a></div>
			</div>
		 	<div class="floatright"><img  alt="Career image" src="<?php print $vcn_image_path; ?>career_images/<?php print $image_name; ?>"></img></div>
		 	<div class="allclear"></div>		 
		</div>	
		<div>
			<br/><br/>
			<div class="vcn-cma-target-text">You can now continue to the next stage to explore available education and training programs to <span class="strong vcn-red-text">Get Qualified</span> in your target career or you can <span class="strong vcn-industry-text">Go To MyVCN</span> to review all your other career development choices.</div><br/>
			<div>If this is not the target career you wanted, you can also go <a title="Back to Review Saved Careers" href="javascript:history.go(-1);">Back to Review Saved Careers</a> to pick a different one.</div><br/>
		</div>	
		<?php echo $user_nav_bar_html; ?>
	</div>	 
	<div class="vcn-right-sidebar vcn-cma-target-sidebar">
		<?php print theme('vcn_account_setup_box'); ?>
	</div>	
	<div class="allclear"></div>		 
</div>