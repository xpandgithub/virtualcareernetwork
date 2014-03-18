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
					<div class="tab-header">Career Requirements</div>
					<div id="<?php echo $vcn_tabs_body_id_prefix; ?>start" class="vcn-get-qualified-tabs-body">
						<?php if ($onetcode_set): ?>
							<div id="vcngetqualified-requirements-intro">Displayed below are the typical training, legal, physical and medical/health requirements for <a href="<?php print $career_title_link; ?>"><?php print $career_title; ?></a> 
						<?php if (isset($state)): ?>
							within <?php print $state; ?>
						<?php endif ?>
							</div>
							<div class="vcn-getqualified-wrapper">
								<div class="vcn-getqualified-requirements-paragraph-header">Typical Training for this Career:</div>
								<div id="vcn-getqualified-typical-education-requirements"><p><?php print $typical_education; ?></p></div>
							</div>
					
							<?php if (isset($preceding_career_title)): ?>
								<div class="vcn-getqualified-wrapper">
									<div class="vcn-getqualified-requirements-paragraph-header">Prior Career Experience:</div>
									<div id="vcn-getqualified-prior-experience-requirements"><p><a href="<?php print $vcn_d7_path; ?>careers/<?php print $preceding_career_onetcode; ?>"><?php print $preceding_career_title; ?></a></p></div>
								</div>
							<?php endif; ?>
					
							<div class="vcn-getqualified-wrapper">
								<div class="vcn-getqualified-requirements-subtitle">Legal Requirements:</div>
								<div class="vcn-getqualified-legal-requirements"><span class="strong"><br/>General/Nationwide</span>
									<?php if ($legal_nationwide_requirements): ?>
										<div id="career-legal-nationwide-requirements-regular-text">
											<p><?php print $legal_nationwide_requirements; ?></p>
										</div>
									<?php endif; ?>
									<?php if (isset($legal_nationwide_requirements_associated_url) && isset($legal_nationwide_requirements_associated_url_flag)): ?>
										<div id="career-legal-nationwide-requirements-hidden-text" class="element-hidden">
											<?php print vcn_build_link_window_opener($legal_nationwide_requirements_associated_url, 'Additional Information', false); ?>
										</div>
										<a class="morelink" href="javascript:void(0);" title="More Details">More Details</a>
									<?php endif; ?>
								</div>
								<div class="vcn-getqualified-legal-requirements"><span class="strong"><br/>State-Specific</span>
									<div id="career-legal-requirements">
										<div id="career-legal-requirements-regular-text">
											<?php print $legal_state_specific_requirements_regular_text; ?>
										</div>
										<div id="career-legal-requirements-hidden-text" class="element-hidden">
										  <?php if (isset($legal_state_specific_requirements_hidden_text)): ?>
												<?php print $legal_state_specific_requirements_hidden_text; ?>
										  <?php endif; ?>
										  <?php if(!empty($legal_state_specific_requirements_associated_url)): ?>
												<p class="paragraph-formatting"><?php print vcn_build_link_window_opener($legal_state_specific_requirements_associated_url, 'Additional Information', false); ?></p>
										  <?php endif; ?>
										</div>
										<?php if (!empty($legal_state_specific_requirements_hidden_text) || !empty($legal_state_specific_requirements_associated_url)): ?>
											<a class="morelink" href="javascript:void(0);" title="More Details">More Details</a>
										<?php endif; ?>
									</div>
									<div class="career-legal-requirements-disclaimer">Disclaimer: The legal requirements mentioned above may not be exhaustive or may not include special requirements of specific employers.</div>
								</div>
							</div>
							
							<div class="vcn-getqualified-wrapper">
								<div class="vcn-getqualified-requirements-subtitle">Physical/Medical/Health Requirements:</div>
								<div id="vcn-getqualified-physical-requirements">
									<div id="career-medical-health-requirements">
										<div id="career-medical-health-requirements-regular-text">
											<?php if (!empty($physical_health_requirements_regular_text)): ?>
												<?php print $physical_health_requirements_regular_text; ?>
											<?php else: ?>
												<p>No specific requirements have been identified.</p>
											<?php endif; ?>
										</div>
										<div id="career-medical-health-requirements-regular-text" class="element-hidden">
											<?php if (!empty($physical_health_requirements_hidden_text)): ?>
												<?php print $physical_health_requirements_hidden_text; ?>
											<?php endif; ?>
											<?php if (isset($physical_requirement_url) && isset($physical_requirement_url_flag)): ?>
												<?php print vcn_build_link_window_opener($physical_requirement_url, 'Additional Information', false); ?>
											<?php endif; ?>
										</div>
										<?php if (!empty($physical_health_requirements_hidden_text) || !empty($physical_requirement_url)): ?>
											<a class="morelink" href="javascript:void(0);" title="More Details">More Details</a><br/>
										<?php endif; ?>
									</div>
								</div>
							</div>									  	
				
						<?php else: ?>
							<p>Please select a desired career to see the requirements.</p>
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