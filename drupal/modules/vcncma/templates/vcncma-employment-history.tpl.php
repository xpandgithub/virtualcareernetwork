<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php if (strlen($counselor_users_dropdown)) : ?>
  <div id="vcn-cma-counselor-users-container"><?php print $counselor_users_dropdown; ?></div>
<?php endif; ?>
  
<h1 class="title"><?php echo $cmatitle;?>: Employment History</h1>

<?php if ($link_as_per_user_state && isset($counselor_student_display_name) && strlen($counselor_student_display_name)) : ?>
  <div class="vcn-cma-counselor-student-display-name"><strong>Attention:</strong> You are viewing the career information for <strong><?php print $counselor_student_display_name; ?></strong></div>
<?php endif; ?>
  
<?php print $vcn_tabs_header; ?>
<div id="vcn-tabs-blueborder" class="<?php print $tab_counselor_class; ?>">
	<div id="cma-employment-history" class="cma-main-content">
    <?php if (!$is_councelor_viewing_student_data) : ?>
		<div id="vcncma-employment-history-add" class="floatright">
			<input title="Add" value="Add" class="cma-employment-history-add vcn-button grid-action-button" name="<?php echo $vcn_drupal7_base_path.'cma/employment-history/add'; ?>" type="button">
		</div>
    <?php endif; ?>
		<?php if($cma_no_employment_history_saved): ?>
			<div class="vcn-note">*No employment history has been saved.</div>
		<?php else: ?>
			<div id="vcncma-employment-history-listing">
				<table id="cma-employment-history-listing" class="dttable">
					<thead>
						<tr><th class="dtheader sorting">Employer Name</th><th class="dtheader sorting">Job Title</th><th class="dtheader sorting">Start Date</th><th class="dtheader sorting">End Date</th><th class="dtheader">Action</th></tr>
					</thead>
					<tbody>
						<tr><td colspan="5" class="dataTables_empty">Loading data from server</td></tr>
					</tbody>
				</table>
			</div>
		<?php endif; ?>
		<div class="allclear"></div>
	</div>
	<?php echo $nav_bar_html; ?>
</div>
<?php echo $user_nav_bar_html; ?>