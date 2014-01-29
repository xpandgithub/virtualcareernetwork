<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<h1 class="title">Earn College Credit for Prior Learning</h1>
<div id="vcn-pla">
	<div class="vcn-left-sidebar">
		<?php print $vcn_tabs_header; ?>
		<div id="vcn-tabs-blueborder">
		<div id="vcn-pla-college-courses-main-content">
		  <span class="tab-header">College Courses</span>
		    
		  <?php print vcn_node_text_by_urlalias('college-credit-text'); ?>
		  
		  <div class="pla-form-box">
		    <h3>Add Course:</h3>
		    <div class="vcn-pull-left">
		    <?php print $school_name; ?>
		    <?php print $program_name; ?>
		    <?php print $course_name; ?>
		    <?php print $course_number; ?>
		    </div>
		    <div class="vcn-pull-left pla-courses-form-right-container">
		    <?php print $year_course_completed; ?>
		    <?php print $num_credit_hours; ?>
		    <?php print $final_grade; ?>
		    </div>
		    <div style="clear:both;"></div>
		    <?php print $college_courses_submit; ?>
		    <br/><br/>
		  </div>
		  
		  <p>Go to <a href="<?php print $vcn_drupal7_base_path; ?>pla/my-learning-inventory">Review Inventory</a> tab to see your saved courses.</p>
		    
		  <p><span class="strong">Hint:</span> Remember that each college determines how your prior learning might apply to a specific degree program.</p>
		</div>
		</div>
		<?php print $children; ?>
	</div>
	<div class="vcn-right-sidebar">
		<?php echo $pla_sidebar;?>
	</div>
</div>