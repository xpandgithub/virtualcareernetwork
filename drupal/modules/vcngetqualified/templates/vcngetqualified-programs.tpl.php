<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<h1 class="title">Get Qualified</h1>
<div id="vcngetqualified">
	<div class="vcn-left-sidebar">
		<?php print render($vcn_user_selections['content']); ?>
		<div id="vcngetqualified-main-body">
			<div id="vcngetqualified-tabs">
				<?php echo $vcn_tabs_header; ?>
				<div id="vcn-tabs-blueborder">
					<div class="tab-header">Education Programs</div>
					<div id="<?php echo $vcn_tabs_body_id_prefix; ?>start" class="vcn-get-qualified-tabs-body">
						<?php if ($no_onetcode_set && $no_award_level_set): ?>
						  <p>Please select a <strong>Desired Career</strong> and <strong>Highest Education Level to Pursue</strong> to see the programs.</p>
						<?php elseif ($no_onetcode_set): ?>
							<p>Please select a Desired Career to see the programs.</p>
						<?php elseif ($no_award_level_set): ?>
							<p>Please select a Highest Education Level to Pursue to see the programs.</p>
						<?php else: ?>
							<div id="vcngetqualified-programs-data-info">
								<?php //data is populated in fnPreDrawCallback function in vcngetqualified_programs.js file, since it is difficult to get js data into php ?>
							</div>
							<table id="vcngetqualified-programs-table" class="dttable">
								<thead>
									<?php if(isset($zipcode) && !(is_null($zipcode))): ?>
										<tr><th class="dtheader sorting">Program Name</th><th class="dtheader">School Name</th><th class="dtheader">Education Level</th><th class="dtheader">Distance</th></tr>
									<?php else: ?>
										<tr><th class="dtheader sorting">Program Name</th><th class="dtheader">School Name</th><th class="dtheader">Education Level</th></tr>
									<?php endif; ?>
								</thead>
								<tbody>
								    <?php if(isset($zipcode)): ?>
									   <tr><td colspan="4" class="dataTables_empty">Loading data from server</td></tr>
								    <?php else: ?>
									   <tr><td colspan="3" class="dataTables_empty">Loading data from server</td></tr>
									<?php endif; ?>
								</tbody>
							</table>
							<div id="current-career-title" class="element-hidden"><?php echo $current_career_title; ?></div>														
						<?php endif; ?>
						<br/> 
						<?php echo $nav_bar_html; ?>
					</div>
				</div>
				<?php echo $user_nav_bar_html; ?>	
			</div>
		</div>
	</div>
	<div class="vcn-right-sidebar">
		<?php print theme('vcn_getqualified_sidebar', $variables); ?> 
	</div>
</div>