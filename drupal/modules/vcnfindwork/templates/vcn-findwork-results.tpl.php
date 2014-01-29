<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<h1 class="title">Find a Job</h1>
<div id="vcn-findwork">
	<div class="vcn-left-sidebar">		
		<div id="vcn-findwork-main-body">
			<div id="vcn-findwork-tabs">
				<?php echo $vcn_tabs_header; ?>
				<div id="vcn-tabs-blueborder">
					<!-- <div class="tab-header">Overview</div> -->
					<div class="vcn-findwork-tabs-body">	
									  	
						<div id="vcnfindwork-results-parent">
						
							<div id="vcnfindworkresults-submit-form-searchboxes-wrapper" class="rndcrnr">
								<div id="vcnfindworkresults-submit-form-zipbox">
									<div class="floatleft"><?php echo $zipcode; ?></div>
									<div class="floatleft"><?php echo $distance; ?></div>
									<div class="allclear"></div>
								</div>
								<div id="vcnfindworkresults-searchbox-hr" class="allclear"><hr></div>
								<div id="vcnfindworkresults-submit-form-searchbox">
									<div><?php echo $careers; ?></div>			
									<div id="vcnfindworkresults-searchbox-or"  class="floatleft">OR</div>						
									<div class="floatleft"><?php echo $search_by_job_title; ?></div>
									<div class="floatleft"><?php echo $find_jobs_submit; ?></div>		
									<div class="allclear"></div>						 
								</div>								 
								<div class="allclear"></div>
							</div>
							
							<?php if(isset($first_visit) && $first_visit != true){?>
							<!-- <div id="vcnfindwork-results-header-save-job-search"></div> -->
							
							<div>
								<div id="vcnfindwork-jobs-results-header">
									<b><?php echo $num_records_found; ?></b><?php echo $jobs_found_text; ?>.
									<?php if ($num_records_found == 0) { ?>
										Please widen your search.
									<?php }?>
								</div>
								<?php if ($GLOBALS['is_user_logged_in']) { ?>
									<div id="vcnfindwork-jobs-results-cma">
										<div id="vcnfindwork-save-job-image"><img id="save-to-job-scout" class="vcn-button save-target-icon" src="<?php echo vcn_image_path(); ?>buttons/save_icon2.png" alt="Save Button"/></div>
										<div id="vcnfindwork-save-job-text">Save this job criteria <br/>to MyVCN Account</div>
									</div>
								<?php } ?>
								<div class="allclear"></div>
							</div>
							
							<div id="vcnfindwork-results-data-table" class="clearall">
								<table id = "vcnfindwork-results-table" class = "dttable">
								<thead><tr><th class="dtheader sorting">Job Title</th><th class="dtheader sorting">Company</th><th class="dtheader sorting">Location</th><th class="dtheader sorting">Date Posted</th></tr></thead>
								<tbody><tr><td colspan="4" class="dataTables_empty">Loading data from server</td></tr></tbody>
								</table>
							</div>				
							
							<div>								
								<a id="vcnfindwork-results-feedback-link" href="">If this is not what you expected, <u>click here</u> to let us know.</a><br/><br/>							
							</div>									
							<?php }else {?>
							<div><?php echo $first_visit_guide_text; ?></div>
							<?php }?>													
							
						<?php echo $children; ?>
						</div>						
						
						<?php echo $nav_bar_html; ?>								        	
					</div>
				</div>
				<?php echo $user_nav_bar_html; ?>	
			</div>
		</div>
	</div>
	<div class="vcn-right-sidebar">
		<?php echo theme('vcn_findwork_tools', $variables); ?> 
	</div>
</div>