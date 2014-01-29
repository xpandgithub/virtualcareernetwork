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
					<div class="tab-header">Required Licenses</div>
					<div id="<?php echo $vcn_tabs_body_id_prefix; ?>start" class="vcn-get-qualified-tabs-body">
						<?php if ($bad_data): ?>
							<?php if (!$is_onetcode_set && !$is_location_set): ?>
								<p>Please select a desired career and a preferred location to see Licenses data.</p>
							<?php elseif (!$is_onetcode_set): ?>
								<p>Please select a desired career to see Licenses data.</p>
							<?php elseif (!$is_location_set): ?>
								<p>Please select a preferred location to see Licenses data.</p>
							<?php endif //end if (!$is_onetcode_set && !$is_location_set) ?>
						<?php else: ?>
							<div id="vcngetqualified-licenses-data-info">
						    <?php $state = (strtoupper($licensing_state) == 'DC') ? 'the District of Columbia' : 'state of '.$licensing_state; ?>
								<?php if ($licenses_data_count == 0): ?>
						      No specific License is required for <a href="<?php print $career_title_link; ?>"><?php print $career_title; ?></a> in <?php print $state; ?>.
						      <?php if (isset($preceding_career_link) && strlen($preceding_career_link)) : ?>
						        <p>However, the required prior career, <a href="<?php print $preceding_career_link; ?>"><?php print $preceding_career_title; ?></a>, may have license requirements.</p>
						      <?php endif; ?>
							    <?php else: ?>
										<p class="vcngetqualified-results-heading">The following is a list of Licenses associated with <a href="<?php print $career_title_link; ?>"><?php print $career_title; ?></a> in <?php print $state; ?>.</p>
										<p>Review the details of the Licenses by clicking on the names of the Licenses.</p>
										<span class="vcngetqualified-results-heading">
							      <?php if ($licenses_data_count == 1): ?>
											<?php print $licenses_data_count; ?> License found for <a href="<?php print $career_title_link; ?>"><?php print $career_title; ?></a> in <?php print $state; ?>.
										<?php else: ?>
											<?php print $licenses_data_count; ?> Licenses found for <a href="<?php print $career_title_link; ?>"><?php print $career_title; ?></a> in <?php print $state; ?>.
										<?php endif ?>
							      </span>
									<?php endif ?>
								</div>
								<?php if (isset($licenses_table)): ?>
									<?php print theme_table($licenses_table); ?>
									<div id="current-career-title" class="element-hidden"><?php echo $current_career_title; ?></div> 
								<?php endif //end if (isset($licenses_table))?>
							
								<div id="vcngetqualified-cos-logo">
									<a href="http://www.careeronestop.org" target="_blank"><img src="<?php print vcn_image_path(); ?>site_logo_images/careeronestoplogo.png" alt="COS Logo" title="CareerOneStop" /></a>
								</div>						
							
	  				<?php endif //if ($bad_data)?>
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