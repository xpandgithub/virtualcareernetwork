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
 * Default theme implementation to display Get Qualified/Find Learning program detail page.
 * 
 */
?>
<div id="program-detail" class="program-detail-edit allclear">
  <div id="school-profile-top">	
	<div id="program-top-title">
		<div>
			<h1 class="title"><?php echo $program_title;  ?></h1>			
		</div>		
		<hr class="divider">
		<div><?php  echo $form_program_name; ?></div>	
		<div><?php  echo $form_program_url; ?></div>	
		<!-- <div class="program-top-input">
			<div class="floatleft"><?php  echo $form_program_name; ?></div>	
			<div class="floatright"><?php  echo $form_program_url; ?></div>
			<div class="allclear"></div>
		</div>	
		<div class="program-top-select">
			<div class="floatleft"><?php  echo $form_program_length; ?></div>	
			<div class="floatright"><?php  echo $form_program_award_level; ?></div>
			<div class="allclear"></div>
		</div>		 -->	
	</div>
	<div class="program-top-buttons">
		<div class="program-edit-buttons">
			<?php echo $form_submit; ?>
		</div>
		<?php if($task == "edit") { ?>
		<div class="program-edit-buttons">			
			<input onClick="javascript:location.href = '<?php echo $vcn_d7_path; ?>provider/<?php echo $unitid; ?>/program';" type="button" value="Add New Program" title="Add New Program" name="add-new-program" id="add-new-program" class="vcn-button">
		</div>
		<div class="program-edit-buttons">			
			<input type="button" value="Delete Program" title="Delete Program" name="delete-program" id="delete-program" class="vcn-button">
		</div>
		<?php } ?>
		<div class="program-edit-buttons">			
			<input onClick="javascript:location.href = '<?php echo $vcn_d7_path; ?>provider/programs/unitid/<?php echo $unitid; ?>';" type="button" value="Programs List" title="Programs List" name="programs-list" id="programs-list" class="vcn-button">
		</div>			
	</div>		
	<div class="allclear"></div>
  </div>  
 
  <div id="school-profile-tabs">	
	<?php echo $vcn_tabs_header; ?>
	<?php echo $vcn_tabs_body_start; ?>
	
	<!-- Program Overview Tab body-->
	<div id="<?php echo $vcn_tabs_body_id_prefix;  ?>pover" class="school-profile-tabs-body">
		<div id="program-bottom" class="allclear">
			<div><?php echo $form_program_cipcode;  ?></div>
			<div><?php echo $form_program_award_level; ?></div>
			<div><?php echo $form_program_length; ?></div>	
			<div><?php echo $form_program_desc;  ?></div>
			<div class="program-contact">
				<div class="floatleft"><?php echo $form_program_contact_name;  ?></div>
				<div class="floatleft"><?php echo $form_program_contact_email;  ?></div>
				<div class="floatleft"><?php echo $form_program_contact_phone;  ?></div>
				<div class="allclear"></div>
			</div><?php   	  		
			if (isset($totalcredits)) { ?>		   			   	
		   	<div>
		   	  <div class="floatleft"><b>Total Credits:&nbsp;</b></div>
		   	  <div class="floatleft">
		   		<?php echo $totalcredits; ?>
		   	  </div>
		   	  <div class="allclear"></div>
		   	</div><?php
   	  		}
			if (isset($totalcourses)) { ?>		   			   	
		   	<div>
		   	  <div class="floatleft"><b>Total Courses:&nbsp;</b></div>
		   	  <div class="floatleft">
		   		<?php echo $totalcourses; ?>
		   	  </div>
		   	  <div class="allclear"></div>
		   	</div><?php
   	  		}
			/*if (isset($tuitioninstate)) { ?>		   			   	
		   	<div>
		   	  <div class="floatleft"><b>In-state Tuition:&nbsp;</b></div>
		   	  <div class="floatleft">
		   		$<?php echo $tuitioninstate; ?>
		   	  </div>
		   	  <div class="allclear"></div>
		   	</div><?php
   	  		}
			if (isset($tuitionoutstate)) { ?>		   			   	
		   	<div>
		   	  <div class="floatleft"><b>Out-of-state Tuition:&nbsp;</b></div>
		   	  <div class="floatleft">
		   		$<?php echo $tuitionoutstate; ?>
		   	  </div>
		   	  <div class="allclear"></div>
		   	</div><?php
   	  		}*/
			if (isset($othercost)) { ?>		   			   	
		   	<div>
		   	  <div class="floatleft"><b>Other Cost:&nbsp;</b></div>
		   	  <div class="floatleft">
		   		<?php echo $othercost; ?>
		   	  </div>
		   	  <div class="allclear"></div>
		   	</div><?php
   	  		}
			if (isset($paccredited)) { ?>		   			   	
		   	<div>
		   	  <div class="floatleft"><b>Accredited By:&nbsp;</b></div>
		   	  <div class="floatleft">
		   		<?php echo $paccredited; ?>
		   	  </div>
		   	  <div class="allclear"></div>
		   	</div><?php
   	  		}
			if (isset($transferpolicy)) { ?>		   			   	
		   	<div>
		   	  <div class="floatleft"><b>Transfer Policy:&nbsp;</b></div>
		   	  <div class="floatleft">
		   		<?php echo $transferpolicy; ?>
		   	  </div>
		   	  <div class="allclear"></div>
		   	</div><?php
   	  		}
			if (isset($mingpafortransfer)) { ?>		   			   	
		   	<div>
		   	  <div class="floatleft"><b>Minimum GPA for transfer:&nbsp;</b></div>
		   	  <div class="floatleft">
		   		<?php echo $mingpafortransfer; ?>
		   	  </div>
		   	  <div class="allclear"></div>
		   	</div><?php
   	  		} ?>	
		</div>
	</div>	
	
	<!-- Program Requirements Tab body-->
	<div id="<?php echo $vcn_tabs_body_id_prefix; ?>preq" class="school-profile-tabs-body tabhide">
		<div><?php
   	  	  if (isset($reqedu)) { ?>
	   	  <div class="floatleft"><b>Admission Requirements:&nbsp;</b></div>
	   	  <div class="floatleft program-req">
   	  		<!--  <div class="program-edu-req strong allclear">
				<div>&nbsp;</div>				
				<div>Minimum GPA</div>
				<div>&nbsp;</div>				
			</div> --><?php 
			echo $reqedu; ?>
	   	  </div>
	   	  <div class="allclear"></div><?php 
		  }else {
			echo $reqedu_na;
		  } ?>
	    </div>
	</div>
	
	<!-- General Admission Requirements Tab body-->
	<div id="<?php echo $vcn_tabs_body_id_prefix; ?>pareq" class="school-profile-tabs-body tabhide">
		<div>
	   	  <!-- <div class="floatleft"><b>General Admission Requirements:&nbsp;</b></div> -->
	   	  <div class="floatleft school-profile-tabs-detail">
	   	  	<?php echo $otherrequirements; ?>
	   	  </div>
	   	  <div class="allclear"></div>
	    </div>
	</div>
	
	<!-- School Information Tab body-->
	<div id="<?php echo $vcn_tabs_body_id_prefix; ?>sinfo" class="school-profile-tabs-body tabhide">
		<div>
	   	  <!-- <div class="floatleft"><b>School Information:&nbsp;</b></div> -->
	   	  <div class="floatleft school-profile-tabs-detail"><?php 
	   	  	if (isset($providerdetail)) { ?>
   	  		   	<div><?php echo $providerdetail; ?></div><?php
   	  		}
	   	  	if (isset($applurl)) { ?>
	   			<div><?php echo $applurl; ?></div><?php 
	   		}
			if (isset($faidurl)) { ?>
	   			<div><?php echo $faidurl; ?></div><?php
	   		} ?>
	   		<div><a target="_blank" href="<?php echo $vcn_d7_path; ?>get-qualified/financialaid">Financial Aid (General)</a></div>
	   	  </div>
	   	  <div class="allclear"></div>
	    </div>
	</div>	
	
	<!-- Entrance Tests Tab body-->
	<div id="<?php echo $vcn_tabs_body_id_prefix;  ?>enttest" class="school-profile-tabs-body tabhide">
		<div>
	   	  <!-- <div class="floatleft"><b>Entrance Tests:&nbsp;</b></div> -->
	   	  <div class="floatleft school-profile-tabs-detail">
	   	  	<div>
	   	  		<?php //if (isset($entrancetests_prog)) { echo $entrancetests_prog; } ?>
	   	  		<div class="entrance-tests">
					<div class="entrance-tests-form">
						<div class="strong allclear">Program Entrance Tests:<br/></div>
						<div class="entrance-tests-title-row allclear strong">
							<div>Test Name</div>
							<div>Test Description</div>
							<div>Minimum Score</div>
							<div>&nbsp;</div>
						</div>
						<?php echo $entrancetests_prog; ?>												
					</div>
				</div>
				<div class=""><input type="button" id="ent-tests-btn-add-more" value="Add New Entrance Test" title="Add New Entrance Test" class="vcn-button" /></div>  	  				  		  
	   	  	</div>	
	   	  	<div class="allclear"><br/></div>
	   	  	<div><?php if (isset($entrancetests_prov)) { echo $entrancetests_prov; } ?></div>	   	  	
			<div><?php if (isset($entrancetests_prog_na) && isset($entrancetests_prov_na)) { echo $entrancetests_prov_na; } ?></div>
	   	  </div>
	   	  <div class="allclear"></div>
	   	</div>
	</div>	
		
	<!-- Prerequisite Courses for Admission Tab body-->
	<div id="<?php echo $vcn_tabs_body_id_prefix; ?>preqcou" class="school-profile-tabs-body tabhide">		
	  	<div>
	  		<!-- <div class="floatleft"><b>Prerequisite Courses for Admission :&nbsp;</b></div> -->  		
	   	 	<div class="floatleft school-profile-tabs-detail">
	   	 		<div>
	   	 		<?php //if (isset($requiredcourses_prog)) { echo $requiredcourses_prog; } ?>
	   	 			<div class="required-courses">
						<div class="required-courses-form">
							<div class="strong allclear">Program Prerequisite Courses:<br/></div>
							<div class="required-courses-title-row allclear strong">
								<div>Course Title</div>
								<div>Course Description</div>
								<div>Course Level</div>
								<div>Minimum GPA</div>
								<div>&nbsp;</div>
							</div>
							<?php echo $requiredcourses_prog; ?>				
						</div>
					</div>
					<div class=""><input type="button" id="req-courses-btn-add-more" value="Add New Prerequisite Course" title="Add New Prerequisite Course" class="vcn-button" /></div>
	   	 		</div>	
	   	 		<div class="allclear"><br/></div>
		   	  	<div><?php if (isset($requiredcourses_prov)) { echo $requiredcourses_prov; } ?></div>	   	  	
				<div><?php if (isset($requiredcourses_prog_na) && isset($requiredcourses_prov_na)) { echo $requiredcourses_prov_na; } ?></div>
	  		</div>
	  		<div class="allclear"></div>
	  	</div>	  
	</div>
	
	<!--  Cirriculum Courses -->
	<div id="<?php echo $vcn_tabs_body_id_prefix; ?>currcou" class="school-profile-tabs-body tabhide">		
	  	<div>
	  		<!-- <div class="floatleft"><b>Prerequisite Courses for Admission :&nbsp;</b></div> -->  		
	   	 	<div class="floatleft school-profile-tabs-detail">
	   	 		<div>
	   	 		<?php //if (isset($requiredcourses_prog)) { echo $requiredcourses_prog; } ?>
	   	 			<div class="curriculum-courses">
						<div class="curriculum-courses-form">
							<div class="strong allclear">Program Curricilum Courses:<br/></div>
							<div class="curriculum-courses-title-row allclear strong">
								<div>Course Title</div>
								<div>Course Description</div>
								<div>Total Credits</div>
								<div>Course Duration</div>
								<div>&nbsp;</div>
							</div>
							<?php echo $curriculumcourses_prog; ?>				
						</div>
					</div>
					<div class=""><input type="button" id="curr-courses-btn-add-more" value="Add New Curriculum Course" title="Add New Curriculum Course" class="vcn-button" /></div>
	   	 		</div>	
	   	 		<div class="allclear"><br/></div>
		   	  	<div><?php //if (isset($requiredcourses_prov)) { echo $requiredcourses_prov; } ?></div>	   	  	
				<div><?php //if (isset($requiredcourses_prog_na) && isset($requiredcourses_prov_na)) { echo $requiredcourses_prov_na; } ?></div>
	  		</div>
	  		<div class="allclear"></div>
	  	</div>	  
	</div>
	
	<?php echo $vcn_tabs_body_end; ?>	
  </div>
  <div class="allclear">&nbsp;</div>      
  <?php echo $form_hidden; ?>    
</div>        