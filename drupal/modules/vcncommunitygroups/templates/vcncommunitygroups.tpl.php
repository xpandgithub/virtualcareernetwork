<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php

/**
 * @file
 * Default theme implementation to display a Community groups page.
 * 
 */

?>
<h1 class="title">Community Groups</h1>
<div id="communitygroups" >
	The following community groups allow you to discuss topics about specific careers and to get to know other people who are looking to get into the career or who have already been in the career for some time.<br><br>	
	<div class="strong underline">GoodWill Social Network:</div><br/>
	<?php echo vcn_convert_urls_to_links("http://goodprospects.goodwill.org/groups/", "GoodProspect"); ?><br/><br/> 	
	<?php if (count($communitygroupslist->item) > 0): ?>
		<div class="strong underline">Others:</div><br/>
		<?php for($i = 0; $i < count($communitygroupslist->item); $i++): ?>
			<div><?php echo vcn_build_link_window_opener($communitygroupslist->item[$i]->resourcelink,$communitygroupslist->item[$i]->resourcename, false); ?></div>
		<?php endfor; ?>
	<?php endif; ?>
</div>