<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<h1 class="title"><?php echo $cmatitle;?>: Employment History</h1>
<?php print $vcn_tabs_header; ?>
<div id="vcn-tabs-blueborder">
	<div id="cma-employment-history" class="cma-main-content">
		<?php if ($allow_edit_add): ?>			
			<div id="vcncma-employment-history-form">							
				<?php echo $form_fields; ?>			
				<?php echo $form_submit; ?>	
				<?php echo $form_cancel; ?>	
			</div>
		<?php else: ?>
			<div class="vcn-note">*You do not have permission to edit this Employer History record.</div>
			<p>Use the "Employer History" tab to see the records you are allowed to edit or add a new record.</p>
		<?php endif; ?>
		<div class="allclear"></div>	
	</div>
</div>