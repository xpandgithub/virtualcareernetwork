<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<h1 class="title"><?php echo $cmatitle;?>: Local Jobs</h1>
<?php print $vcn_tabs_header; ?>
<div id="vcn-tabs-blueborder">
	<div id="cma-local-jobs" class="cma-main-content">
		<?php if ($local_jobs_allow_edit_add): ?>			
			<div id="vcncma-local-jobs-form">							
				<?php print $local_jobs_job_title; ?>	
				<?php print $local_jobs_job_url; ?>
				<?php print $local_jobs_employer_name; ?>
				<?php print $local_jobs_employer_url; ?>
				<?php print $local_jobs_contact_name; ?>
				<?php print $local_jobs_contact_phone; ?>
				<?php print $local_jobs_contact_email; ?>
				<?php print $local_jobs_note; ?>
				<?php print $local_jobs_submit; ?>
				<?php print $local_jobs_cancel; ?>
			</div>
			<div class="allclear"></div>
		<?php else: ?>
			<div class="vcn-note">*You do not have permission to edit this Local Job record.</div>
			<p>Use the "Local Jobs" tab to see the records you are allowed to edit or add a new record.</p>
		<?php endif; ?>
	</div>
</div>
<?php print $children; ?>