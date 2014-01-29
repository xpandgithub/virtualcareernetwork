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
					<div class="tab-header">How to Get Qualified</div>
					<div id="<?php echo $vcn_tabs_body_id_prefix; ?>start" class="vcn-get-qualified-tabs-body">					  
					    <?php echo $how_to_get_qualified_vocabulary;?>
					    
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