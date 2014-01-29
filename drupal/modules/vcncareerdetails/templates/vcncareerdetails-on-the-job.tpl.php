<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<div class="vcncareerdetails-on-the-job">
	
	<?php $data_exists = false; ?>
	
	<?php if(strlen($day_in_life_description) || isset($day_in_life_url) || strlen($day_in_life_url)) { $data_exists = true; ?>
	<fieldset>
		<legend><b>A Day In The Life</b></legend>
		<div>
			<p><?php echo $day_in_life_description; ?></p>
			<?php if (isset($day_in_life_url) && strlen($day_in_life_url)) { ?>
				<p>To see more,<?php vcn_build_link_window_opener($day_in_life_url, 'Click here'); ?></p>
			<?php } ?>
		</div>
	</fieldset>
	<?php } ?>
	
	
	<?php if(isset($interview_url) || isset($additional_interview_array)) { $data_exists = true; ?>
	<fieldset>
		<legend><b>Interview</b></legend>
		<div>			
			<?php if(isset($interview_url) && strlen($interview_url)) { ?>
				<p>See a<?php vcn_build_link_window_opener($interview_url, 'career interview'); ?> with an actual <?php echo $careertitle; ?>.</p>
			<?php } ?>
			
			<?php if(isset($additional_interview_array)) { ?>
				<?php foreach($additional_interview_array as $additional_interview) { ?>
					<?php	$addInterviewUrl = (string)$additional_interview['interviewurl'][0]; ?>
	                      	<p>See an<?php vcn_build_link_window_opener($addInterviewUrl, 'additional interview'); ?></p>	                  
				<?php } ?>
			<?php } ?>
		</div>
	</fieldset>
	<?php } ?>
	
	<?php if (!$data_exists) { ?>
    	No information available.<br/><br/>
    <?php } ?> 
	
</div>	
		