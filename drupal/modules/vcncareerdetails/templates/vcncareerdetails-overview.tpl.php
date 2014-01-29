<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<div class="vcncareerdetails-overview">

	<?php if($detailed_description_regular_text != "") {?>
	<fieldset>
		<legend><b>Description</b></legend>
		<div id="career-description">
			<div id="career-description-regular-text">
				<?php print $detailed_description_regular_text; ?>
			</div>
			<?php if (isset($detailed_description_hidden_text)) { ?>
			<div id="career-description-hidden-text"  class="element-hidden">
				<?php print $detailed_description_hidden_text; ?>
			</div>
			<a class="morelink" href="javascript:void(0);" title="More Details">More Details</a>
			<?php } ?>			
        </div>
	</fieldset>
	<?php } ?>	
	
	<fieldset>
		<legend><b>Physical/Medical/Health Requirements</b></legend>
		<div id="career-medical-health-requirements">
			<div id="career-medical-health-requirements-regular-text">
				<?php print $physical_health_requirements_regular_text; ?>
			</div>
			<?php if (isset($physical_health_requirements_hidden_text) || strlen($physical_requirement_url)) { ?>
			<div id="career-medical-health-requirements-hidden-text" class="element-hidden">
				<?php print $physical_health_requirements_hidden_text; ?>
				
				<?php if (strlen($physical_requirement_url)) {  
				  		vcn_build_link_window_opener($physical_requirement_url, 'Additional Information');  
			  	   	} ?>
			  	
			</div>
			<a class="morelink" href="javascript:void(0);" title="More Details">More Details</a>
			<?php } ?>
        </div>
	</fieldset>
	
	<fieldset>
		<legend><b>Legal Requirements</b></legend>		
		
		<?php if($legal_nationwide_requirements_regular_text != "" || strlen($legal_nationwide_requirement_url)) {?>
		<div class="vcncareerdetails-fieldset-subtitle">General/Nationwide</div>	 
		<div id="career-legal-nationwide-requirements">
			<div id="career-legal-nationwide-requirements-regular-text">
				<?php print $legal_nationwide_requirements_regular_text; ?>
			</div>
			<div id="career-legal-nationwide-requirements-hidden-text"  class="element-hidden">
			  <?php if (isset($legal_nationwide_requirements_hidden_text)) { ?>
				<?php print $legal_nationwide_requirements_hidden_text; ?>
			  <?php } ?>
			  <?php if (strlen($legal_nationwide_requirement_url)) { ?>
				<?php vcn_build_link_window_opener($legal_nationwide_requirement_url, 'Additional Information'); ?>
			  <?php } ?>
			</div>
			<?php if (!empty($legal_nationwide_requirements_hidden_text) || strlen($legal_nationwide_requirement_url)) { ?>
			<a class="morelink" href="javascript:void(0);" title="More Details">More Details</a>
			<?php } ?>
        </div>	 
		<?php } ?>		
		<div class="allclear"></div>
		
		<div class="vcncareerdetails-fieldset-subtitle">State-Specific</div>		
		<div id="career-legal-requirements">
		
			<div id="career-legal-requirements-regular-text">
				<?php print $legal_state_specific_requirements_regular_text; ?>
			</div>
			<div id="career-legal-requirements-hidden-text" class="element-hidden">
			  <?php if (isset($legal_state_specific_requirements_hidden_text)) { ?>
				<?php print $legal_state_specific_requirements_hidden_text; ?>
			  <?php } ?>
			  <?php if(!empty($legal_state_specific_requirements_associated_url)) { ?>
				<p class="paragraph-formatting"><?php print vcn_build_link_window_opener($legal_state_specific_requirements_associated_url, 'Additional Information', false); ?></p>
			  <?php } ?>
			</div>
			<?php if (!empty($legal_state_specific_requirements_hidden_text) || !empty($legal_state_specific_requirements_associated_url)) { ?>
			<a class="morelink" href="javascript:void(0);" title="More Details">More Details</a>
			<?php } ?> 		
			
        </div>
	</fieldset>	
	
	<?php if ($similar_careers) { ?>
	<fieldset>
		<legend><b>Similar Careers</b></legend>
		<div id="similar-careers-listing">
			<div><?php 
				foreach ($similar_careers as $career) { ?>
					<p><b><a href="<?php echo vcn_drupal7_base_path() . 'careers/' . $career['similar_onetcode']; ?> "><?php echo $career['similar_display_title']; ?></a></b><br/>
					<b>Typical Education: </b><?php echo $career['similar_typical_training_title']; ?><br/><?php 
					
					if (isset($zipcode)) { ?>
						<b>Salary (<?php echo $zipcode; ?>): </b><?php echo $career['similar_wage_displayed']; ?><br/></p><?php 
					} else { ?>
						<b>Salary (National): </b><?php echo  $career['similar_wage_displayed']; ?><br/></p><?php
					}
				} ?>
			</div>			
			<div>
				(Data Drawn from O*NET)
			</div>			
        </div>
	</fieldset>
	<?php } ?>
	
</div>	
		