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
 * Default theme implementation to display Get Qualified/Find Learning school detail page.
 * 
 */
?>
<div id="school-profile" class="provider-profile allclear">
  <div id="school-profile-top">	
	<div id="school-profile-top-title">
		<div>
			<h1 class="title"><?php echo $providerdetail->name; ?><?php echo $facebook_like; ?></h1>			
		</div>
		<hr class="divider">
		<div id="school-profile-top-desc">
			<div class="provider-input-div">
				<div class="floatleft"><?php echo $form_provider_name; ?></div>
		   	  	<div class="floatleft"><?php echo $form_provider_phone; ?></div>
		   	  	<div class="allclear"></div>
		   	</div>
		   	<div class="provider-input-div">
				<div class="floatleft"><?php echo $form_provider_addr; ?></div>		   	  	
		   	  	<div class="allclear"></div>
		   	</div>
		   	<div class="provider-input-div">
				<div class="floatleft"><?php echo $form_provider_city; ?></div>
				<div class="floatleft"><?php echo $form_provider_state; ?></div>
		   	  	<div class="floatleft"><?php echo $form_provider_zipcode; ?></div>
		   	  	<div class="allclear"></div>
		   	</div>	
		</div>
	</div>
	<div id="school-profile-top-image">
		<img src="<?php echo $school_logo; ?>" width="152" height="152" alt="School logo"><?php echo $form_provider_logo; ?>
	</div>
	<div class="allclear"></div>
  </div>  
	 
  <div id="school-profile-tabs">	
	<?php echo $vcn_tabs_header;?>
	<?php echo $vcn_tabs_body_start;?>
	
	<!-- School Description Tab body-->
	<div id="<?php echo $vcn_tabs_body_id_prefix; ?>desc" class="school-profile-tabs-body">
		<div id="school-profile-bottom" class="allclear">			   	
		   	<div class="provider-input-div">
		   		<?php echo $form_provider_webaddr; ?>
		   	</div>			   	
		   	<div class="provider-input-div">
		   		<?php echo $form_provider_applurl; ?>
		   	</div>			   	
		   	<div class="provider-input-div">
		   		<?php echo $form_provider_faidurl; ?>
		   	</div>			   	
		   	<div class="provider-input-div">
		   		<?php echo $form_provider_missionstatementurl; ?>
		   	</div>
		   	<div class="provider-input-div">
		   		<?php echo $form_provider_missionstatement; ?>			   		
		   	</div> 		   			   		   		
	   		<div>
		   	  <div class="floatleft"><b>Type of School:&nbsp;</b></div>
		   	  <div class="floatleft">
		   		<?php echo $ipedsdesc; ?>
		   	  </div>
		   	  <div class="allclear"></div>
		   	</div>
			
	   		<div>
		   	  <div class="floatleft"><b>Percent applicants admitted:&nbsp;</b></div>
		   	  <div class="floatleft">
		   		<?php echo $percentadmittedtotal; ?>
		   	  </div>
		   	  <div class="allclear"></div>
		   	</div>
	   		
			<div>
		   	  <div class="floatleft"><b>Size:&nbsp;</b></div>
		   	  <div class="floatleft">
		   		<div>Total students: <?php echo number_format(intval($providerdetail->totalenrollment), 0, '.', ','); ?></div>
		   		<div>Total undergrads: <?php echo number_format(intval($providerdetail->undergraduateenrollment), 0, '.', ','); ?></div>
		   		<div>1st-time degree-seeking freshmen: <?php echo number_format(intval($providerdetail->firsttimedegreecertificateundergradenrollment), 0, '.', ','); ?></div>
		   		<div>Graduate enrollment: <?php echo number_format(intval($providerdetail->graduateenrollment), 0, '.', ','); ?></div>
		   	  </div>
		   	  <div class="allclear"></div>
		   	</div>
		   	
		   	<div>
		  		<div class="floatleft"><b>Financial Aid:&nbsp;</b></div>
		   	 	<div class="floatleft">			   	 		
		   	 		<div><a target="_blank" href="<?php echo $vcn_d7_path; ?>get-qualified/financialaid">Financial Aid (General)</a></div>
		   	 		<?php echo $v2faidurl; ?>
		  		</div>
		  		<div class="allclear"></div>
		  	</div>
		</div>
	</div>
	
	<!-- School Degrees Offered Tab body-->
	<div id="<?php echo $vcn_tabs_body_id_prefix; ?>deg" class="school-profile-tabs-body tabhide">
		<div>
	   	  <!-- <div class="floatleft"><b>Degrees Offered:&nbsp;</b></div> -->
	   	  <div class="floatleft school-profile-tabs-detail"><?php echo $degrees; ?></div>
	   	  <div class="allclear"></div>
	    </div>
	</div>
	
	<!-- School Entrance Tests Tab body-->
	<div id="<?php echo $vcn_tabs_body_id_prefix; ?>test" class="school-profile-tabs-body tabhide">
		<div>
	   	  <!-- <div class="floatleft"><b>Entrance Tests:&nbsp;</b></div> -->
	   	  <div class="floatleft school-profile-tabs-detail">		   	  	
	   	  	<div><?php 
			   	if (intval($providerdetail->satcriticalreading25thpercentilescore)>1 || intval($providerdetail->satcriticalreading75thpercentilescore)>1  ) { ?>
				<div class="school-ent-test strong allclear">
					<div>&nbsp;</div>
					<div>25th Percentile</div>			
					<div>75th Percentile</div>
				</div>
				<?php $flag=1;
				} ?>
				<?php if (intval($providerdetail->satcriticalreading25thpercentilescore)>1 || intval($providerdetail->satcriticalreading75thpercentilescore)>1  ) { ?>						
				<div class="school-ent-test allclear">
					<div>SAT Critical Reading</div>
					<div><?php $testpscore=intval($providerdetail->satcriticalreading25thpercentilescore); if ($testpscore<1) { echo $na; } else { echo $testpscore; } ?></div>
					<div><?php $testpscore=intval($providerdetail->satcriticalreading75thpercentilescore); if ($testpscore<1) { echo $na; } else { echo $testpscore; } ?></div>
				</div>
				<?php $flag=1;
				} ?>
				<?php if (intval($providerdetail->satmath25thpercentilescore)>1 || intval($providerdetail->satmath75thpercentilescore)>1  ) { ?>
				<div class="school-ent-test allclear">
					<div>SAT Math</div>
					<div><?php $testpscore=intval($providerdetail->satmath25thpercentilescore); if ($testpscore<1) { echo $na; } else { echo $testpscore; } ?></div>
					<div><?php $testpscore=intval($providerdetail->satmath75thpercentilescore); if ($testpscore<1) { echo $na; } else { echo $testpscore; } ?></div>							
				</div>
				<?php $flag=1;
				} ?>
				<?php if (intval($providerdetail->satwriting25thpercentilescore)>1 || intval($providerdetail->satwriting75thpercentilescore)>1  ) { ?>
				<div class="school-ent-test allclear">
					<div>SAT Writing</div>
					<div><?php $testpscore=intval($providerdetail->satwriting25thpercentilescore); if ($testpscore<1) { echo $na; } else { echo $testpscore; } ?></div>
					<div><?php $testpscore=intval($providerdetail->satwriting75thpercentilescore); if ($testpscore<1) { echo $na; } else { echo $testpscore; } ?></div>							
				</div>	
				<?php $flag=1;
				} ?>
				<?php if (intval($providerdetail->actcomposite25thpercentilescore)>1 || intval($providerdetail->actcomposite75thpercentilescore)>1  ) { ?>
				<div class="school-ent-test allclear">
					<div>ACT Composite</div>
					<div><?php $testpscore=intval($providerdetail->actcomposite25thpercentilescore); if ($testpscore<1) { echo $na; } else { echo $testpscore; } ?></div>
					<div><?php $testpscore=intval($providerdetail->actcomposite75thpercentilescore); if ($testpscore<1) { echo $na; } else { echo $testpscore; } ?></div>							
				</div>	
				<?php $flag=1;
				} ?>
				<?php if (intval($providerdetail->actenglish25thpercentilescore)>1 || intval($providerdetail->actenglish75thpercentilescore)>1  ) { ?>
				<div class="school-ent-test allclear">
					<div>ACT English</div>
					<div><?php $testpscore=intval($providerdetail->actenglish25thpercentilescore); if ($testpscore<1) { echo $na; } else { echo $testpscore; } ?></div>
					<div><?php $testpscore=intval($providerdetail->actenglish75thpercentilescore); if ($testpscore<1) { echo $na; } else { echo $testpscore; } ?></div>							
				</div>	
				<?php $flag=1;
				} ?>
				<?php if (intval($providerdetail->actmath25thpercentilescore)>1 || intval($providerdetail->actmath75thpercentilescore)>1  ) { ?>
				<div class="school-ent-test allclear">
					<div>ACT Math</div>
					<div><?php $testpscore=intval($providerdetail->actmath25thpercentilescore); if ($testpscore<1) { echo $na; } else { echo $testpscore; } ?></div>
					<div><?php $testpscore=intval($providerdetail->actmath75thpercentilescore); if ($testpscore<1) { echo $na; } else { echo $testpscore; } ?></div>							
				</div>	
				<?php $flag=1;
				} ?>						
				<?php if (intval($providerdetail->actwriting25thpercentilescore)>1 || intval($providerdetail->actwriting75thpercentilescore)>1  ) { ?>
				<div class="school-ent-test allclear">
					<div>ACT Writing</div>
					<div><?php $testpscore=intval($providerdetail->actwriting25thpercentilescore); if ($testpscore<1) { echo $na; } else { echo $testpscore; } ?></div>
					<div><?php $testpscore=intval($providerdetail->actwriting75thpercentilescore); if ($testpscore<1) { echo $na; } else { echo $testpscore; } ?></div>							
				</div>	
				<?php $flag=1;
				} ?>
			</div>				
	   	  </div>
	   	  
	   	  <div class="allclear">&nbsp;</div>
		  <div class="entrance-tests">
			<div class="entrance-tests-form">
				<div class="entrance-tests-title-row allclear strong">
					<div>Test Name</div>
					<div>Test Description</div>
					<div>Minimum Score</div>
					<div>&nbsp;</div>
				</div>
				<?php echo $entrancetests; ?>												
			</div>
		  </div>
		  <div class=""><input type="button" id="ent-tests-btn-add-more" value="Add New Entrance Test" title="Add New Entrance Test" class="vcn-button" /></div>
		  	   	  
	   	  <div class="allclear"></div>
	   	</div>
	</div>
	
	<!-- School Cost + Fees Tab body-->
	<div id="<?php echo $vcn_tabs_body_id_prefix; ?>cost" class="school-profile-tabs-body tabhide">
		<div>
	   	  <!-- <div class="floatleft"><b>Costs + Fees:&nbsp;</b></div> -->
	   	  <div class="floatleft school-profile-tabs-detail">	
			<div>		  
				<div class="school-cost strong allclear">
					<div>&nbsp;</div>
					<div>Living on-campus</div>
					<div>Living at home</div>
					<div>Commuting</div>
				</div>
				<div class="school-cost allclear">
					<div>In-state tuition:</div>
					<div><?php $price=intval($providerdetail->priceinstateoncampus); if ($price<1) { echo $na; } else { echo "$".number_format($price, 0, '.', ','); } ?></div>
					<div><?php $price=intval($providerdetail->priceinstateoffcampusfamily); if ($price<1) { echo $na; } else { echo "$".number_format($price, 0, '.', ','); } ?></div>
					<div><?php $price=intval($providerdetail->priceinstateoffcampusnofamily); if ($price<1) { echo $na; } else { echo "$".number_format($price, 0, '.', ','); } ?></div>
				</div>
				<div class="school-cost allclear">
					<div>Out-of-state tuition:</div>
					<div><?php $price=intval($providerdetail->priceoutofstateoncampus); if ($price<1) { echo $na; } else { echo "$".number_format($price, 0, '.', ','); } ?></div>
					<div><?php $price=intval($providerdetail->priceoutofstateoffcampusfamily); if ($price<1) { echo $na; } else { echo "$".number_format($price, 0, '.', ','); } ?></div>
					<div><?php $price=intval($providerdetail->priceoutofstateoffcampusnofamily); if ($price<1) { echo $na; } else { echo "$".number_format($price, 0, '.', ','); } ?></div>
				</div>
				<div class="school-cost allclear">
					<div>Room and board:</div>
					<div><?php $price=intval($providerdetail->combinedchargeroomboard); if ($price<1) { echo $na; } else { echo "$".number_format($price, 0, '.', ','); } ?></div>
					<div><?php $price=intval($providerdetail->combinedchargeroomboard); if ($price<1) { echo $na; } else { echo "$".number_format($price, 0, '.', ','); } ?></div>
					<div><?php $price=intval($providerdetail->combinedchargeroomboard); if ($price<1) { echo $na; } else { echo "$".number_format($price, 0, '.', ','); } ?></div>
				</div>
				<div class="school-cost allclear">
					<div>Books and supplies:</div>
					<div><?php $price=intval($providerdetail->booksandsupplies); if ($price<1) { echo $na; } else { echo "$".number_format($price, 0, '.', ','); } ?></div>
					<div><?php $price=intval($providerdetail->booksandsupplies); if ($price<1) { echo $na; } else { echo "$".number_format($price, 0, '.', ','); } ?></div>
					<div><?php $price=intval($providerdetail->booksandsupplies); if ($price<1) { echo $na; } else { echo "$".number_format($price, 0, '.', ','); } ?></div>
				</div>
			</div>
			<div class="allclear">&nbsp;</div>
			<div>
				<div class="school-fees strong allclear">
					<div>&nbsp;</div>
					<div>&nbsp;</div>
					<div>Undergraduates</div>
					<div>Graduates</div>
				</div>
				<div class="school-fees allclear">
					<div>Cost per credit hour</div>
					<div>(state):</div>
					<div><?php $price=intval($providerdetail->instatecreditchargeparttimeundergrad); if ($price<1) { echo $na; } else { echo "$".number_format($price, 0, '.', ','); } ?></div>
					<div><?php $price=intval($providerdetail->instatecreditchargeparttimegrad); if ($price<1) { echo $na; } else { echo "$".number_format($price, 0, '.', ','); } ?></div>
				</div>
				<div class="school-fees allclear">
					<div>Cost per credit hour</div>
					<div>(out-of-state):</div>
					<div><?php $price=intval($providerdetail->outofstatecreditchargeparttimeundergrad); if ($price<1) { echo $na; } else { echo "$".number_format($price, 0, '.', ','); } ?></div>
					<div><?php $price=intval($providerdetail->outofstatecreditchargeparttimegrad); if ($price<1) { echo $na; } else { echo "$".number_format($price, 0, '.', ','); } ?></div>
				</div>
			</div>			  	
	   	  </div>
	   	  <div class="allclear"></div>
	    </div>
	</div>
	
	<!-- School Services Tab body-->
	<div id="<?php echo $vcn_tabs_body_id_prefix; ?>ser" class="school-profile-tabs-body tabhide">
		<div>
	  		<!-- <div class="floatleft"><b>School Services:&nbsp;</b></div> -->
	   	 	<div class="floatleft school-profile-tabs-detail"><?php echo $services; ?></div>
	  		<div class="allclear"></div>
	  	</div>
	</div>
	
	<!-- Student Demographics Tab body-->
	<div id="<?php echo $vcn_tabs_body_id_prefix; ?>demo" class="school-profile-tabs-body tabhide">
		<div>
	  		<!-- <div class="floatleft"><b>Student Demographics:&nbsp;</b></div> -->
	   	 	<div class="floatleft school-profile-tabs-detail">
	   	 		<div class="school-student-demo allclear">
				  <div>Women:</div>
				  <div><?php echo $providerdetail->percentwomen; ?>%</div>
				</div>
				<div class="school-student-demo allclear">
				  <div>Men:</div>
				  <div><?php echo (100-$providerdetail->percentwomen); ?>%</div>
				</div>
				<div class="school-student-demo allclear">
				  <div>American Indian/Alaskan:</div>
				  <div><?php $p=$providerdetail->percentamericanindianoralaskanative; if ($p<1) { $p="<1"; } echo $p; ?>%</div>
				</div>
				<div class="school-student-demo allclear">
				  <div>Asian/Pacific Islander:</div>
				  <div><?php $p=$providerdetail->percentasiannativehawaiianpacificislander; if ($p<1) { $p="<1"; } echo $p; ?>%</div>
				</div>
				<div class="school-student-demo allclear">
				  <div>Black/Non-Hispanic:</div>
				  <div><?php $p=$providerdetail->percentblackorafricanamerican; if ($p<1) { $p="<1"; } echo $p; ?>%</div>
				</div>
				<div class="school-student-demo allclear">
				  <div>Hispanic:</div>
				  <div><?php $p=$providerdetail->percenthispaniclatino; if ($p<1) { $p="<1"; } echo $p; ?>%</div>
				</div>
				<div class="school-student-demo allclear">
				  <div>White/Non-Hispanic:</div>
				  <div><?php $p=$providerdetail->percentwhite; if ($p<1) { $p="<1"; } echo $p; ?>%</div>
				</div>
				<div class="school-student-demo allclear">
				  <div>Non-Resident Alien:</div>
				  <div><?php $p=$providerdetail->percentnonresidentalien; if ($p<1) { $p="<1"; } echo $p; ?>%</div>
				</div>
				<div class="school-student-demo allclear">
				  <div>Race/Ethnicity unreported:</div>
				  <div><?php $p=$providerdetail->percentraceethnicityunknown; if ($p<1) { $p="<1"; } echo $p; ?>%</div>
				</div>   	 		
	  		</div>
	  		<div class="allclear"></div>
	  	</div>
	</div>
		
	<!-- Prerequisite Courses for Admission Tab body-->
	<div id="<?php echo $vcn_tabs_body_id_prefix; ?>req" class="school-profile-tabs-body tabhide">
 		<div class="required-courses">
			<div class="required-courses-form">
				<div class="required-courses-title-row allclear strong">
					<div>Course Title</div>
					<div>Course Description</div>
					<div>Course Level</div>
					<div>Minimum GPA</div>
					<div>&nbsp;</div>
				</div>
				<?php echo $requiredcourses; ?>				
			</div>
		</div>
		<div class=""><input type="button" id="req-courses-btn-add-more" value="Add New Prerequisite Course" title="Add New Prerequisite Course" class="vcn-button" /></div>			
		
	</div>			
	<?php echo $vcn_tabs_body_end;?>	
  </div>
  <div class="allclear">&nbsp;</div>
  <div class="align-center"><?php echo $form_submit; ?></div>	    
  <?php echo $form_hidden; ?>    
</div>