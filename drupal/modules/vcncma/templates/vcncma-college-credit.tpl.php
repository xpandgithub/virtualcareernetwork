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
  
<h1 class="title"><?php echo $cmatitle;?>: College Credits</h1>

<?php if ($link_as_per_user_state && isset($counselor_student_display_name) && strlen($counselor_student_display_name)) : ?>
  <div class="vcn-cma-counselor-student-display-name"><strong>Attention:</strong> You are viewing the career information for <strong><?php print $counselor_student_display_name; ?></strong></div>
<?php endif; ?>
  
<?php print $vcn_tabs_header; ?>
<div id="vcn-tabs-blueborder" class="<?php print $tab_counselor_class; ?>">
	<div id="cma-college-credit" class="cma-main-content">
		<?php if($cma_no_collegecredits_saved): ?>
			<div class="vcn-note">*No college credit have been saved.</div>
		<?php else: ?>
      <?php if (!$is_councelor_viewing_student_data) : ?>
			<div class="cma-main-content-pdfs vcnpla-mylearning-buttons-div">
        <div class="floatleft"><a href="<?php echo vcn_drupal7_base_path();?>pla/view-my-learning-inventory" title="View My Learning Inventory" target="_blank"><div class="vcn-button vcnpla-mylearning-button noresize" ><img alt="PDF Icon" src="<?php echo vcn_image_path();?>miscellaneous/pdf.png" class="vcnpla-mylearning-button-icon"> View My Learning Inventory</div></a></div>
        <div class="floatleft">&nbsp;&nbsp;&nbsp;</div> 
        <div class="floatleft"><a href="<?php echo vcn_drupal7_base_path();?>pla/view-my-cover-letter" title="View My Cover Letter" target="_blank"><div class="vcn-button vcnpla-mylearning-button noresize" ><img alt="PDF Icon" src="<?php echo vcn_image_path();?>miscellaneous/pdf.png" class="vcnpla-mylearning-button-icon"> View My Cover Letter</div></a></div>
        <div class="allclear"></div>
      </div>
      <?php endif; ?>
			<div id="vcncma-college-credit-summary">Your learning inventory summarizes prior learning that might be applied to your intended degree program.</div>
			<div id="vcncma-college-credit-listing">
				<table id="cma-college-credit-listing" class="dttable">
					<thead><tr><th class="dtheader sorting">Course Name</th><th class="dtheader sorting">Type</th><th class="dtheader">Action</th></tr></thead>
					<tbody>
						<tr><td colspan="3" class="dataTables_empty">Loading data from server</td></tr>
					</tbody>
				</table>
			</div>
		<?php endif; ?>
		<div class="allclear"></div>
	</div>
	<?php echo $nav_bar_html; ?>
</div>
<?php echo $user_nav_bar_html; ?>