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
			<div id="pla-mylearning-inventory-main-content">
		    <span class="tab-header">Review Learning Inventory</span>		    
			<?php echo $my_learning_inventory;?>
			</div>
			<?php if($courses_listing != "") {?>		
				<div id="vcnpla-mylearning-inventory-div" class="allclear">		      
					<?php echo $courses_listing; ?>		
				</div>
				<div class="allclear">&nbsp;</div>
		        <div class="vcnpla-mylearning-buttons-div">
		          <div class="floatleft"><a href="<?php echo vcn_drupal7_base_path();?>pla/view-my-learning-inventory" title="View My Learning Inventory" target="_blank"><div class="vcn-button vcnpla-mylearning-button noresize" ><img alt="PDF Icon" src="<?php echo vcn_image_path();?>miscellaneous/pdf.png" class="vcnpla-mylearning-button-icon"> View My Learning Inventory</div></a></div>
		          <div class="floatleft">&nbsp;&nbsp;&nbsp;</div> 
		          <div class="floatleft"><a href="<?php echo vcn_drupal7_base_path();?>pla/view-my-cover-letter" title="View My Cover Letter" target="_blank"><div class="vcn-button vcnpla-mylearning-button noresize" ><img alt="PDF Icon" src="<?php echo vcn_image_path();?>miscellaneous/pdf.png" class="vcnpla-mylearning-button-icon"> View My Cover Letter</div></a></div>
		          <div class="allclear">&nbsp;</div>
		        </div>
			<?php }else { ?>
				<div class="vcn-note">*No courses have been saved.</div>
				<div class="allclear">&nbsp;</div>
			<?php } ?>
		</div>
	</div>
	<div class="vcn-right-sidebar">
		<?php echo $pla_sidebar;?>
	</div>
</div>