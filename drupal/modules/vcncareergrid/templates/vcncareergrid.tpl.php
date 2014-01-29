<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<h1 class="title">Career Search</h1><?php //vcncareergrid-info is just place holder to show number of records returned ?>
<div id="career-grid">
  <div class="vcn-left-sidebar">
	<!-- <p>This is what you searched for:</p> -->
	<div id="vcncareergrid-selections" class="rndcrnr">
		<div id="vcncareergrid-training" class="vcncareergrid-training-select"><?php print $careergrid_edu_level; ?></div>
		<div id="vcncareergrid-worktype" class="vcncareergrid-worktype-select"><?php print $careergrid_type_of_work; ?></div>
		<div id="vcncareergrid-suggest" class="vcncareergrid-suggest-select"><?php print $careergrid_search_box; ?></div>
		<div id="vcncareergrid-submit" class="vcncareergrid-submit-select noresize"><?php print $careergrid_submit; ?></div>
	</div>
	<div id="vcn-tabs-blueborder">
		<div id="vcncareergrid-main-content">
			<div id="vcncareergrid-table-abovetext"><span id="vcncareergrid-info"></span></div>
			<table id="vcncareergrid-table" class="dttable">
				<thead>
					<tr>
						<th class="dtheader sorting">Career</th>
						<th class="dtheader sorting">Typical Salary (Annual)</th>
						<th class="dtheader sorting">Typical Salary (Hourly)</th>
						<th class="dtheader">Typical Education</th>
					</tr>
				</thead>
				<tbody>
					<tr><td colspan="6" class="dataTables_empty">Loading data from server</td></tr>
				</tbody>
			</table>
		</div>
		<div id="vcncareergrid-cos-logo">
			<a href="http://www.careeronestop.org" target="_blank"><img src="<?php print vcn_image_path(); ?>site_logo_images/careeronestoplogo.png" alt="COS Logo" title="CareerOneStop"></a>
		</div>	
		<?php print $careergrid_children; ?>
		<div class="allclear"></div>
		<!-- Navigation bar  
	      <div class="vcn-navigation-bar allclear">
	      	<div class="bar-left"><div>&nbsp;</div></div>
	      	<div class="bar-middle"><div>&nbsp;</div></div>
	      	<div class="bar-right"><div>&nbsp;</div></div>		
	      	<div class="allclear"></div>		      	
	      </div>
	    <!-- End of Navigation bar -->
	</div>
	<!-- VCN Navigation bar -->
      <div class="vcn-user-navigation-bar allclear">
      	<div class="nav-bar-left"><div><a title="Back to Choose a Career" href="<?php echo vcn_drupal7_base_path();?>explorecareers" >Back to Choose a Career</a></div></div>	      	
      	<div class="nav-bar-right"><div><button title="<?php if($targeted_career_count < 1){ ?>No Career Saved<?php }else{?>Review Saved Careers<?php }?>" <?php if($targeted_career_count > 0){ ?>onclick="location.href='<?php echo vcn_drupal7_base_path();?>cma/careers'; return false;"<?php }?> class="vcn-button vcn-red-button <?php if($targeted_career_count < 1){ echo "vcn-button-disable"; }?>">Review Saved Careers</button></div></div>		
      	<div class="allclear"></div>		      	
      </div>
    <!-- End of VCN Navigation bar -->
  </div>
  <div class="vcn-right-sidebar">	
	<?php print theme('vcn_advanced_career_tools'); ?>
	<?php print theme('vcn_account_setup_box'); ?>
  </div>
</div>