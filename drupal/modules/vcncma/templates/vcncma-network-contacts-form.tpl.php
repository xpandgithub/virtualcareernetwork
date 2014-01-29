<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<h1 class="title"><?php echo $cmatitle;?>: Network Contacts</h1>
<?php print $vcn_tabs_header; ?>
<div id="vcn-tabs-blueborder">
	<div id="cma-local-jobs" class="cma-main-content">
		<?php if ($network_contacts_allow_edit_add): ?>			
			<div id="vcncma-network-contacts-form">							
				<?php print $network_contacts_first_name; ?>	
				<?php print $network_contacts_last_name; ?>
				<?php print $network_contacts_company_name; ?>
				<?php print $network_contacts_company_title; ?>
				<?php print $network_contacts_phone_work; ?>
				<?php print $network_contacts_phone_mobile; ?>
				<?php print $network_contacts_email; ?>
				<?php print $network_contacts_note; ?>
				<?php print $network_contacts_submit; ?>
				<?php print $network_contacts_cancel; ?>
			</div>
			<div class="allclear"></div>
		<?php else: ?>
			<div class="vcn-note">*You do not have permission to edit this Network Contact record.</div>
			<p>Use the "Network Contacts" tab to see the records you are allowed to edit or add a new record.</p>
		<?php endif; ?>
	</div>
</div>
<?php print $children; ?>