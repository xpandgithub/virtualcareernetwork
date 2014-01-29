<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<div class="vcn-sidebar-tools-box rndcrnr" >
	<h3 class="vcn-sidebar-tools-header">Job Search Tools</h3>
	<div class="vcn-sidebar-tools-content<?php echo $selectedTabIndex == "overview" ? " element-hidden" : ""; ?>">
		<h4 class="toolbox-title-small">Create Personal Marketing Tools</h4>
		<p><a href="<?php print $vcn_drupal7_base_path;?>findwork/research_employers">Research Employers</a></p>
		<p><a href="<?php print $vcn_drupal7_base_path;?>findwork/resume">Build a Resume</a></p>
		
		<h4 class="toolbox-title-small">Conquer the Job Application</h4>
		<p><a href="<?php print $vcn_drupal7_base_path;?>findwork/interviews">Ace your Interviews</a></p>
		<!-- <p>Online Job Applications</p> -->
		
		<h4 class="toolbox-title-small">Meet People Who Know People</h4>
		<p><a href="<?php print $vcn_drupal7_base_path;?>findwork/use_network">Use your Network</a></p>
		<p><a href="<?php print $vcn_drupal7_base_path;?>findwork/network_online">Network Online</a></p>
		
		<h4 class="toolbox-title-small">Connect with Organizations that Find Candidates for Employers</h4>
		<!-- <p>SCSEP Locator</p> -->
		<p><a href="<?php print $vcn_drupal7_base_path;?>office-locator">Office Locator</a></p>
		
		<h4 class="toolbox-title-small">Take Time to Focus on Yourself and Stay Strong</h4>
		<p><a href="<?php print $vcn_drupal7_base_path;?>findwork/job_search_plan">Plan your Job Search</a></p>
		<!-- <p>(Links in 7 Smart Strategies for Supportive Services and AARP FDN Resources)</p> -->
		<p><a href="<?php print $vcn_drupal7_base_path;?>findwork/jobsinfo">Find more Jobs Information</a></p>
    
		<?php if (!empty($job_search_resources_link->resourceslist)) { ?>
			<?php foreach ($job_search_resources_link->resourceslist as $item) { ?>
				<p><?php print vcn_build_link_window_opener($item->RESOURCE_LINK, $item->RESOURCE_NAME, false) ?></p>
			<?php }?>
		<?php } ?>	        
		
		<p>&nbsp;</p>
		<div id="vcnfindwork-employer-help">
			<div class="vcn-sidebar-tools-title">Employer Help</div>
			<div id="vcnfindwork-employer-help-content">
				<?php echo $find_job_tools_employer_help; ?>				
			</div>			
		</div>
	</div>
</div>