<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<div id="vcnpla-details-page-top" class="allclear">
	<h1 class="title vcn-pla-title"><?php print (!empty($college_course_detail->name)) ? $college_course_detail->name : 'N/A'; ?></h1>
	<div class="vcn-pla-buttons">
		<div id="vcnpla-details-page-search-again" class="vcnpla-details-back-btn">
			<?php if(strpos($_SERVER["HTTP_REFERER"], 'cma')) : ?>
				<button class="vcn-button detail-back-buttons" id="button-back-to" onclick="location='<?php print vcn_drupal7_base_path(); ?>cma/college-credit'" title="Back to College Credits">Back to<br/> College Credits</button>						
			<?php else: ?>
				<button class="vcn-button detail-back-buttons" id="button-back-to" onclick="location='<?php print vcn_drupal7_base_path(); ?>pla/my-learning-inventory'" title="Back to Review Inventory">Back to<br/> Review Inventory</button>					
			<?php endif; ?>
		</div>
		<div id="vcnpla-details-page-save-button">
			<button class="vcn-button save-target-buttons save-button2 vcn-button-disable" title="Save">Save to Learning Inventory</button>
		</div>
	</div><hr class="divider allclear"/>
</div>

<div>
	<p><span class="strong">School Name: </span><?php print (!empty($college_course_detail->school)) ? $college_course_detail->school : 'N/A'; ?></p>
	<p><span class="strong">Program Name: </span><?php print (!empty($college_course_detail->program)) ? $college_course_detail->program : 'N/A'; ?></p> 
	<p><span class="strong">Course Name: </span><?php print (!empty($college_course_detail->name)) ? $college_course_detail->name : 'N/A'; ?></p>
	<p><span class="strong">Course Number: </span><?php print (!empty($college_course_detail->code)) ? $college_course_detail->code : 'N/A'; ?></p>
	<p><span class="strong">Final Grade: </span><?php print (!empty($college_course_detail->grade)) ? $college_course_detail->grade : 'N/A'; ?></p>
	<p><span class="strong">Year Course Completed: </span><?php print (!empty($college_course_detail->yearcompleted)) ? substr($college_course_detail->yearcompleted, -4) : 'N/A'; ?></p>
  <p><span class="strong">Number of Credit Hours: </span><?php print (!empty($college_course_detail->credit)) ? $college_course_detail->credit : 'N/A'; ?></p>
</div>