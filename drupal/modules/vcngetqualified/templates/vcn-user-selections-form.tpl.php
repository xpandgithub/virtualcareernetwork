<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<div id="vcn-user-selections" class="rndcrnr">
	<div id="vcn-user-selections-careers">
		<div class="vcn-user-selections-show-on-change"><?php print $vcn_user_selections_career; ?></div>
		<div class="vcn-user-selections-show-on-load"><?php print $vcn_user_selections_career_markup; ?></div>
	</div>
	<div id="vcn-user-selections-edu-level-parent">
		<div id="vcn-user-selections-edu-level">
			<div class="vcn-user-selections-show-on-change"><?php print $vcn_user_selections_edu_level; ?></div>
			<div class="vcn-user-selections-show-on-load"><?php print $vcn_user_selections_edu_level_markup; ?></div>
		</div>
		<div id="vcn-user-selections-edu-level-help-image">
			<img id="vcn-user-selections-edu-level-help" src="<?php print vcn_image_path(); ?>miscellaneous/help.png" alt="Help" />
		</div>
	</div>
	<div id="vcn-user-selections-edu-level-note"></div>
	<div id="vcn-user-selections-zipcode">
		<div class="vcn-user-selections-show-on-load"><?php print $vcn_user_selections_zipcode_markup; ?></div>
		<div class="vcn-user-selections-show-on-change"><?php print $vcn_user_selections_zipcode; ?></div>
	</div>
	<div id="vcn-user-selections-distance">
		<div class="vcn-user-selections-show-on-load"><?php print $vcn_user_selections_distance_markup; ?></div>
		<div class="vcn-user-selections-show-on-change"><?php print $vcn_user_selections_distance; ?></div>
	</div>
	<div id="vcn-user-selections-submit">
		<div class="vcn-user-selections-show-on-change"><?php print $vcn_user_selections_submit; ?></div>
		<div class="vcn-user-selections-show-on-load"><?php print $vcn_user_selections_edit; ?></div>
	</div>
</div>
<?php print $children; ?>