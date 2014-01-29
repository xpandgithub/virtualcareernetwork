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
					<div class="tab-header">Job Certifications</div>
					<div id="<?php echo $vcn_tabs_body_id_prefix; ?>start" class="vcn-get-qualified-tabs-body">
						<?php if ($bad_data): ?>
							<p>Please select a desired career to see the certifications data.</p>
						<?php else: ?>
							<div id="vcngetqualified-certifications-data-info">
									<?php if ($certifications_data_count == 0): ?>
										No Certification is required for <a href="<?php print $career_title_link?>"><?php print $career_title; ?></a>
									<?php else: ?>
										<p class="vcngetqualified-results-heading">The following is a list of Certifications associated with <a href="<?php print $career_title_link?>"><?php print $career_title; ?></a>.</p>
										<p>Review the details of the certifications by clicking on the names of the Certifications.</p>
										<span class="vcngetqualified-results-heading">
						        <?php if ($certifications_data_count == 1): ?>
											<?php print $certifications_data_count; ?> Certification found for <a href="<?php print $career_title_link?>"><?php print $career_title; ?></a>.
										<?php else: ?>
											<?php print $certifications_data_count; ?> Certifications found for <a href="<?php print $career_title_link?>"><?php print $career_title; ?></a>.
										<?php endif ?>
						        </span>
									<?php endif ?>
							</div>
						
							<?php if (isset($certifications_table)): ?>
								<?php print theme_table($certifications_table); ?>
								<div id="current-career-title" class="element-hidden"><?php echo $current_career_title; ?></div> 
							<?php endif ?> 
							
							<div id="vcngetqualified-cos-logo">
								<a href="http://www.careeronestop.org" target="_blank"><img src="<?php print vcn_image_path(); ?>site_logo_images/careeronestoplogo.png" alt="COS Logo" title="CareerOneStop" /></a>
							</div>							
							
					  <?php endif ?>
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