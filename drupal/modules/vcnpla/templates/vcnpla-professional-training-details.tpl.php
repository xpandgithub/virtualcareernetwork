<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<div id="vcnpla-details-page-top" class="allclear">
	<h1 class="title vcn-pla-title"><?php print $first_title; ?></h1>
	<div class="vcn-pla-buttons">
		<div id="vcnpla-details-page-search-again">
			<?php if(strpos($_SERVER["HTTP_REFERER"], 'cma')) : ?>
				<button class="vcn-button detail-back-buttons" id="button-back-to" onclick="location='<?php print vcn_drupal7_base_path(); ?>cma/college-credit'" title="Back to College Credits">Back to<br/> College Credits</button>
			<?php else: ?>
				<?php if (isset($page_from) && $page_from == 'mylearning_inventory'): ?>
					<button class="vcn-button detail-back-buttons" id="button-back-to" onclick="location='<?php print vcn_drupal7_base_path(); ?>pla/my-learning-inventory'" title="Back to Review Inventory">Back to<br/> Review Inventory</button>
				<?php else: ?>
					<button class="vcn-button detail-back-buttons" id="button-back-to" onclick="location='<?php print $professional_training_link; ?>'" title="Back to Professional Training Courses">Back to Professional<br/> Training Courses</button>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		<div id="vcnpla-details-page-save-button">
			<button class="vcn-button save-target-buttons save-button2 <?php if($is_saved_or_targeted_item > 0){ echo 'vcn-button-disable'; }?>" <?php if($is_saved_or_targeted_item < 1){ ?>id="save-to-wishlist"<?php }?> title="Save">Save to Learning Inventory</button>
		</div>
	</div>
</div>
<hr class="divider allclear"/>

<div id="vcnpla-professional-training-details-ace-id">
	<strong>ACE ID:</strong> <?php print $ace_id; ?>
</div>

<?php if(!empty($first_title)): ?>
	<div id="vcnpla-professional-training-details-first-title">
		<strong>Primary Title:</strong> <?php print $first_title; ?>
	</div>
<?php endif; ?>

<?php if(!empty($second_title)): ?>
	<div id="vcnpla-professional-training-details-secondary-title">
		<strong>Secondary Title:</strong> <?php print $second_title; ?>
	</div>
<?php endif; ?>

<?php if(!empty($objective)): ?>
<div id="vcnpla-professional-training-details-objective">
	<strong>Objective:</strong> <?php print $objective; ?>
</div>
<?php endif; ?>

<?php if(!empty($instruction)): ?>
<div id="vcnpla-professional-training-details-instruction">
	<strong>Instruction:</strong> <?php print $instruction; ?>
</div>
<?php endif; ?>

<?php if(!empty($course_credit_info_str)): ?>
<div id="vcnpla-professional-training-details-course-hours">
	<strong>Course Hours:</strong> <?php print $course_credit_info_str; ?>
</div>
<?php endif ?>