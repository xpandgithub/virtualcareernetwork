<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<h1 class="title">Program Exploration Summary</h1>
<div id="vcn-cma-target">
	<div class="vcn-left-sidebar">
		<div class="vcn-cma-target-congrats">Congratulations! You have now completed the major part of the second Stage.</div><br/>
        <div class="strong">Your Target Program is:</div><br/>	
		<div id="vcn-cma-target-info" class="rndcrnr">
			<div>
				<div class="tab-header"><?php echo $variables['vcnuser_get_targeted_program']->programname;?></div>
				<div>
					<p><?php if($variables['vcnuser_get_targeted_program']->programdescription != "") { echo $variables['vcnuser_get_targeted_program']->programdescription."..."; }?></p>
					<p><?php echo $variables['vcnuser_get_targeted_program']->instname; ?><br/>
						<?php echo $variables['vcnuser_get_targeted_program']->instaddress; ?><br/>
						<?php echo $variables['vcnuser_get_targeted_program']->instcity; ?>, <?php echo $variables['vcnuser_get_targeted_program']->inststateabbrev; ?> <?php echo $variables['vcnuser_get_targeted_program']->instzip; ?>
					</p>
				</div>
				<div><a title="View Program Details" href="<?php echo $vcn_drupal7_base_path;?>get-qualified/program/<?php echo $variables['vcnuser_get_targeted_program']->itemid; ?>/cipcode/<?php echo $variables['vcnuser_get_targeted_program']->cipcode; ?>/onetcode/<?php echo $variables['vcnuser_get_targeted_program']->onetcode; ?>" >View Program Details</a></div>
			</div>		 		 
		</div>
		<div>
			<br/><br/>
			<div class="vcn-cma-target-text">The next step is to apply and enroll in the selected training  program. Go to <span class="strong vcn-industry-text">MyVCN</span> to track the progress of your training program – add courses you have completed - you need to do this regularly until the program is completed. When its done Continue to <span class="strong vcn-red-text">Find a Job</span> to search for the better job.  While pursuing your education, you can Continue to Find a Job to search for jobs that you can take up  in the mean time.</div><br/>
			<div>If this is not the target program you wanted, you can also go <a title="Back to Review Saved Programs" href="javascript:history.go(-1);">Back to Review Saved Programs</a> to pick a different one.</div><br/>
		</div>			
		<?php echo $user_nav_bar_html; ?>
	</div>	 
	<div class="vcn-right-sidebar vcn-cma-target-sidebar">
		<?php print theme('vcn_account_setup_box'); ?>
	</div>	
	<div class="allclear"></div>		 
</div>