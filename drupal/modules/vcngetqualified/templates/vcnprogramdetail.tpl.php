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
<div id="program-detail" class="allclear">
  <div id="school-profile-top">	
	<div id="program-top-title">
		<div>
			<h1 class="title"><?php echo $programdetails->programname;  ?> Program <?php echo $facebook_like;  ?></h1>			
		</div>    
		<hr class="divider">
		<br/>		
	</div>
	<div id="program-save-target" class="noresize">
		<button  title="<?php if($is_saved_or_targeted_item > 0){?>Program has already been saved<?php }else{?>Save this Program to My Wishlist<?php }?>" class="vcn-button save-target-buttons save-button <?php if($is_saved_or_targeted_item > 0){ echo 'vcn-button-disable'; }?>" id="save-to" <?php if($is_saved_or_targeted_item < 1){?>onclick="vcnSaveTarget('<?php echo $vcn_d7_path;?>cma/ajax/save-target-notebook-item/save/program/<?php echo $programid;  ?>/<?php echo $onetcode;  ?>/<?php echo $cipcode;  ?>', 'program', 'save', '<?php echo $vcn_user['vcn_user_id']; ?>', '<?php echo (int) $vcn_user['is_user_logged_in']; ?>', '<?php echo $onetcode;  ?>');"<?php }?> ><?php echo $save_button; ?></button>
		<!-- <button  title="Target this Program" class="vcn-button save-target-buttons target-button <?php //if($is_saved_or_targeted_item > 1){ echo 'vcn-button-disable'; }?>" id="save-to-target" <?php //if($is_saved_or_targeted_item < 2){?>onclick="vcnSaveTarget('<?php //echo $vcn_d7_path;?>cma/ajax/save-target-notebook-item/target/program/<?php //echo $programid;  ?>/<?php //echo $onetcode;  ?>/<?php //echo $cipcode;  ?>', 'program', 'target', '<?php //echo $vcn_user['vcn_user_id']; ?>', '<?php //echo (int) $vcn_user['is_user_logged_in']; ?>', '<?php //echo $onetcode;  ?>');"<?php //}?> >Make This<br/>My Target</button> -->
	</div>		
	
	<div class="allclear"></div>
  </div>  
 
  <div id="school-profile-tabs">	
	<?php echo $vcn_tabs_header; ?>
	<?php echo $vcn_tabs_body_start; ?>
	
	<!-- Program Overview Tab body-->
	<div id="<?php echo $vcn_tabs_body_id_prefix;  ?>pover" class="school-profile-tabs-body">
		<div id="school-profile-bottom" class="allclear"><?php
			if (isset($programurl)) { ?>
   	  		   	<div><?php echo $programurl; ?></div><?php
   	  		} 
			if (isset($programdesc)) {  ?>
				<div><?php echo $programdesc;  ?></div><?php  
			}					
			if (isset($awlevel)) { ?>		   			   	
		   	<div>
		   	  <div class="floatleft"><b>Award Level:&nbsp;</b></div>
		   	  <div class="floatleft">
		   		<?php echo $awlevel; ?>
		   	  </div>
		   	  <div class="allclear"></div>
		   	</div><?php
   	  		}
			if (isset($plength)) { ?>		   			   	
		   	<div>
		   	  <div class="floatleft"><b>Program Length:&nbsp;</b></div>
		   	  <div class="floatleft">
		   		<?php echo $plength; ?>
		   	  </div>
		   	  <div class="allclear"></div>
		   	</div><?php
   	  		}
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
   	  		} */
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
   	  		}
			if (isset($programcontactname)) { ?>	   		
	   		<div>
		   	  <div class="floatleft"><b>Contact Name:&nbsp;</b></div>
		   	  <div class="floatleft">
		   		<?php echo $programcontactname; ?>
		   	  </div>
		   	  <div class="allclear"></div>
		   	</div><?php
   	  		} 
   	  		if (isset($programcontactemail)) { ?>
	   		<div>
		   	  <div class="floatleft"><b>Contact Email:&nbsp;</b></div>
		   	  <div class="floatleft">
		   		<?php echo $programcontactemail; ?>
		   	  </div>
		   	  <div class="allclear"></div>
		   	</div><?php
   	  		} 
   	  		if (isset($programcontactphone)) { ?>		   			   	
		   	<div>
		   	  <div class="floatleft"><b>Contact Phone:&nbsp;</b></div>
		   	  <div class="floatleft">
		   		<?php echo $programcontactphone; ?>
		   	  </div>
		   	  <div class="allclear"></div>
		   	</div><?php
   	  		}
   	  		if (isset($admissionurl)) { ?>
   	  		<div>
   	  			<div class="floatleft"><b>Admission Information:&nbsp;</b></div>
   	  				 <div class="floatleft">
   	  				   	<?php echo $admissionurl; ?>
   	  				</div>
   	  			<div class="allclear"></div>
   	  		</div><?php
   	  		} ?>	
		</div>
		
		<div class="noresize">
			<br/>
			<button  title="<?php if($is_saved_or_targeted_item > 0){?>Program has already been saved<?php }else{?>Save this Program to My Wishlist<?php }?>" class="vcn-button save-target-buttons save-button <?php if($is_saved_or_targeted_item > 0){ echo 'vcn-button-disable'; }?>" id="save-to" <?php if($is_saved_or_targeted_item < 1){?>onclick="vcnSaveTarget('<?php echo $vcn_d7_path;?>cma/ajax/save-target-notebook-item/save/program/<?php echo $programid;  ?>/<?php echo $onetcode;  ?>/<?php echo $cipcode;  ?>', 'program', 'save', '<?php echo $vcn_user['vcn_user_id']; ?>', '<?php echo (int) $vcn_user['is_user_logged_in']; ?>', '<?php echo $onetcode;  ?>');"<?php }?> ><?php echo $save_button; ?></button>
			<div class="allclear"></div>
		</div>	
	</div>	
	
	<!-- Program Requirements Tab body-->
	<div id="<?php echo $vcn_tabs_body_id_prefix; ?>preq" class="school-profile-tabs-body tabhide">
    <?php 	    $preq_na = true;
		if (isset($reqedu)) { $preq_na = false; ?>
    	  <div>
    		 <div class="floatleft"><b>Education Requirements:&nbsp;</b></div>
    		 <div class="floatleft program-req">
    		   <?php echo $reqedu; ?>
    		 </div>
    		 <div class="allclear"></div>
    	  </div><?php
       	}
       	if (isset($hsgradreq)) { $preq_na = false; ?>
    	  <div>
    		 <div class="floatleft"><b>HS Grad Required?:&nbsp;</b></div>
    		 <div class="floatleft">
    		   <?php echo $hsgradreq; ?>
    		 </div>
    		 <div class="allclear"></div>
    	  </div><?php
       	}     	
       	if (isset($mingpa)) { $preq_na = false; ?>
    	  <div>
    		 <div class="floatleft"><b>Minimum GPA:&nbsp;</b></div>
    		 <div class="floatleft">
    		   <?php echo $mingpa; ?>
    		 </div>
    		 <div class="allclear"></div>
    	  </div><?php
       	}     	
       	if (isset($gedaccepted)) { $preq_na = false; ?>
    	  <div>
    		 <div class="floatleft"><b>GED Accepted?:&nbsp;</b></div>
    		 <div class="floatleft">
    		   <?php echo $gedaccepted; ?>
    		 </div>
    		 <div class="allclear"></div>
    	  </div><?php
       	}     	
       	if (isset($medicalreq)) { $preq_na = false; ?>
    	  <div>
    		 <div class="floatleft"><b>Medical Requirements:&nbsp;</b></div>
    		 <div class="floatleft">
    		   <?php echo $medicalreq; ?>
    		 </div>
    		 <div class="allclear"></div>
    	  </div><?php
       	}     	
       	if (isset($immunizationreq)) { $preq_na = false; ?>
    	  <div>
    		 <div class="floatleft"><b>Immunization Requirements:&nbsp;</b></div>
    		 <div class="floatleft">
    		   <?php echo $immunizationreq; ?>
    		 </div>
    		 <div class="allclear"></div>
    	  </div><?php
       	}     	
       	if (isset($legalreq)) { $preq_na = false; ?>
    	  <div>
    		 <div class="floatleft"><b>Legal Requirements:&nbsp;</b></div>
    		 <div class="floatleft">
    		   <?php echo $legalreq; ?>
    		 </div>
    		 <div class="allclear"></div>
    	  </div><?php
       	}
       	if ($preq_na){
		echo 'N/A';
		}
       	?>
       	<div class="noresize">
			<br/>
			<button  title="<?php if($is_saved_or_targeted_item > 0){?>Program has already been saved<?php }else{?>Save this Program to My Wishlist<?php }?>" class="vcn-button save-target-buttons save-button <?php if($is_saved_or_targeted_item > 0){ echo 'vcn-button-disable'; }?>" id="save-to" <?php if($is_saved_or_targeted_item < 1){?>onclick="vcnSaveTarget('<?php echo $vcn_d7_path;?>cma/ajax/save-target-notebook-item/save/program/<?php echo $programid;  ?>/<?php echo $onetcode;  ?>/<?php echo $cipcode;  ?>', 'program', 'save', '<?php echo $vcn_user['vcn_user_id']; ?>', '<?php echo (int) $vcn_user['is_user_logged_in']; ?>', '<?php echo $onetcode;  ?>');"<?php }?> ><?php echo $save_button; ?></button>
			<div class="allclear"></div>
		</div>
	</div>
	
	<!-- How to Apply Tab body-->
	<div id="<?php echo $vcn_tabs_body_id_prefix; ?>pareq" class="school-profile-tabs-body tabhide">
		<div>
	   	  <!-- <div class="floatleft"><b>How to Apply:&nbsp;</b></div> -->
	   	  <div class="floatleft school-profile-tabs-detail">
	   	  	<?php echo $howtoapply; ?>
	   	  </div>
	   	  <div class="allclear"></div>
	    </div>
	    <div class="noresize">
			<br/>
			<button  title="<?php if($is_saved_or_targeted_item > 0){?>Program has already been saved<?php }else{?>Save this Program to My Wishlist<?php }?>" class="vcn-button save-target-buttons save-button <?php if($is_saved_or_targeted_item > 0){ echo 'vcn-button-disable'; }?>" id="save-to" <?php if($is_saved_or_targeted_item < 1){?>onclick="vcnSaveTarget('<?php echo $vcn_d7_path;?>cma/ajax/save-target-notebook-item/save/program/<?php echo $programid;  ?>/<?php echo $onetcode;  ?>/<?php echo $cipcode;  ?>', 'program', 'save', '<?php echo $vcn_user['vcn_user_id']; ?>', '<?php echo (int) $vcn_user['is_user_logged_in']; ?>', '<?php echo $onetcode;  ?>');"<?php }?> ><?php echo $save_button; ?></button>
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
	    <div class="noresize">
			<br/>
			<button  title="<?php if($is_saved_or_targeted_item > 0){?>Program has already been saved<?php }else{?>Save this Program to My Wishlist<?php }?>" class="vcn-button save-target-buttons save-button <?php if($is_saved_or_targeted_item > 0){ echo 'vcn-button-disable'; }?>" id="save-to" <?php if($is_saved_or_targeted_item < 1){?>onclick="vcnSaveTarget('<?php echo $vcn_d7_path;?>cma/ajax/save-target-notebook-item/save/program/<?php echo $programid;  ?>/<?php echo $onetcode;  ?>/<?php echo $cipcode;  ?>', 'program', 'save', '<?php echo $vcn_user['vcn_user_id']; ?>', '<?php echo (int) $vcn_user['is_user_logged_in']; ?>', '<?php echo $onetcode;  ?>');"<?php }?> ><?php echo $save_button; ?></button>
			<div class="allclear"></div>
		</div>
	</div>	
	
	<!-- Entrance Tests Tab body-->
	<div id="<?php echo $vcn_tabs_body_id_prefix;  ?>enttest" class="school-profile-tabs-body tabhide">
		<div>
	   	  <!-- <div class="floatleft"><b>Entrance Tests:&nbsp;</b></div> -->
	   	  <div class="floatleft school-profile-tabs-detail">
	   	  	<div><?php if (isset($entrancetests_prog)) { echo $entrancetests_prog; } ?></div>	
	   	  	<div><?php if (isset($entrancetests_prov)) { echo $entrancetests_prov; } ?></div>	   	  	
			<div><?php if (isset($entrancetests_prog_na) && isset($entrancetests_prov_na)) { echo $entrancetests_prov_na; } ?></div>
	   	  </div>
	   	  <div class="allclear"></div>
	   	</div>
	   	<div class="noresize">
			<br/>
			<button  title="<?php if($is_saved_or_targeted_item > 0){?>Program has already been saved<?php }else{?>Save this Program to My Wishlist<?php }?>" class="vcn-button save-target-buttons save-button <?php if($is_saved_or_targeted_item > 0){ echo 'vcn-button-disable'; }?>" id="save-to" <?php if($is_saved_or_targeted_item < 1){?>onclick="vcnSaveTarget('<?php echo $vcn_d7_path;?>cma/ajax/save-target-notebook-item/save/program/<?php echo $programid;  ?>/<?php echo $onetcode;  ?>/<?php echo $cipcode;  ?>', 'program', 'save', '<?php echo $vcn_user['vcn_user_id']; ?>', '<?php echo (int) $vcn_user['is_user_logged_in']; ?>', '<?php echo $onetcode;  ?>');"<?php }?> ><?php echo $save_button; ?></button>
			<div class="allclear"></div>
		</div>
	</div>	
		
	<!-- Prerequisite Courses for Admission Tab body-->
	<div id="<?php echo $vcn_tabs_body_id_prefix; ?>preqcou" class="school-profile-tabs-body tabhide">		
	  	<div>
	  		<!-- <div class="floatleft"><b>Prerequisite Courses for Admission :&nbsp;</b></div> -->  		
	   	 	<div class="floatleft school-profile-tabs-detail">
	   	 		<div><?php if (isset($requiredcourses_prog)) { echo $requiredcourses_prog; } ?></div>	
		   	  	<div><?php if (isset($requiredcourses_prov)) { echo $requiredcourses_prov; } ?></div>	   	  	
				<div><?php if (isset($requiredcourses_prog_na) && isset($requiredcourses_prov_na)) { echo $requiredcourses_prov_na; } ?></div>
	  		</div>
	  		<div class="allclear"></div>
	  	</div>
	  	<div class="noresize">
			<br/>
			<button  title="<?php if($is_saved_or_targeted_item > 0){?>Program has already been saved<?php }else{?>Save this Program to My Wishlist<?php }?>" class="vcn-button save-target-buttons save-button <?php if($is_saved_or_targeted_item > 0){ echo 'vcn-button-disable'; }?>" id="save-to" <?php if($is_saved_or_targeted_item < 1){?>onclick="vcnSaveTarget('<?php echo $vcn_d7_path;?>cma/ajax/save-target-notebook-item/save/program/<?php echo $programid;  ?>/<?php echo $onetcode;  ?>/<?php echo $cipcode;  ?>', 'program', 'save', '<?php echo $vcn_user['vcn_user_id']; ?>', '<?php echo (int) $vcn_user['is_user_logged_in']; ?>', '<?php echo $onetcode;  ?>');"<?php }?> ><?php echo $save_button; ?></button>
			<div class="allclear"></div>
		</div>	  
	</div>
	
	<!-- Curriculum Courses -->
	<div id="<?php echo $vcn_tabs_body_id_prefix; ?>currcou" class="school-profile-tabs-body tabhide">
		<div>
			<div class="floatleft school-profile-tabs-detail">
				<div>
					<?php if (!empty($curriculum_program_courses->item)): ?>
						<table id="vcngetqualified-program-course-details" class="dttable">
							<thead>
								<tr><th class="dtheader sorting">Title</th><th class="dtheader sorting">Description</th><th class="dtheader sorting">Duration</th><th class="dtheader">Credits</th></tr>
							</thead>
							<tbody>
								<tr><td colspan="4" class="dataTables_empty">Loading data from server</td></tr>
							</tbody>
						</table>
						<?php else: ?>
							N/A
					<?php endif; ?>
				</div>
			</div>
			<div class="allclear"></div>
		</div>
		<div class="noresize">
			<br/>
			<button  title="<?php if($is_saved_or_targeted_item > 0){?>Program has already been saved<?php }else{?>Save this Program to My Wishlist<?php }?>" class="vcn-button save-target-buttons save-button <?php if($is_saved_or_targeted_item > 0){ echo 'vcn-button-disable'; }?>" id="save-to" <?php if($is_saved_or_targeted_item < 1){?>onclick="vcnSaveTarget('<?php echo $vcn_d7_path;?>cma/ajax/save-target-notebook-item/save/program/<?php echo $programid;  ?>/<?php echo $onetcode;  ?>/<?php echo $cipcode;  ?>', 'program', 'save', '<?php echo $vcn_user['vcn_user_id']; ?>', '<?php echo (int) $vcn_user['is_user_logged_in']; ?>', '<?php echo $onetcode;  ?>');"<?php }?> ><?php echo $save_button; ?></button>
			<div class="allclear"></div>
		</div>
	</div>
	
	<!-- General Admission Other Requirements Tab body-->
	<div id="<?php echo $vcn_tabs_body_id_prefix; ?>otherreq" class="school-profile-tabs-body tabhide">
		<div>
	   	  <!-- <div class="floatleft"><b>Other Requirements:&nbsp;</b></div> -->
	   	  <div class="floatleft school-profile-tabs-detail">
	   	  	<?php 
	   	  	//echo '<pre>'; 
	   	  	echo $otherrequirements; 
	   	  	//echo '</pre>'; ?>
	   	  </div>
	   	  <div class="allclear"></div>
	    </div>
	    <div class="noresize">
			<br/>
			<button  title="<?php if($is_saved_or_targeted_item > 0){?>Program has already been saved<?php }else{?>Save this Program to My Wishlist<?php }?>" class="vcn-button save-target-buttons save-button <?php if($is_saved_or_targeted_item > 0){ echo 'vcn-button-disable'; }?>" id="save-to" <?php if($is_saved_or_targeted_item < 1){?>onclick="vcnSaveTarget('<?php echo $vcn_d7_path;?>cma/ajax/save-target-notebook-item/save/program/<?php echo $programid;  ?>/<?php echo $onetcode;  ?>/<?php echo $cipcode;  ?>', 'program', 'save', '<?php echo $vcn_user['vcn_user_id']; ?>', '<?php echo (int) $vcn_user['is_user_logged_in']; ?>', '<?php echo $onetcode;  ?>');"<?php }?> ><?php echo $save_button; ?></button>
			<div class="allclear"></div>
		</div>
	</div>
	
	<?php echo $vcn_tabs_body_end; ?>
	<!-- VCN Navigation bar -->
      <div class="vcn-user-navigation-bar allclear">
      	<div class="nav-bar-left"><div><a title="Back to <?php echo strpos($_SERVER["HTTP_REFERER"], "/cma/") ? (strpos($_SERVER["HTTP_REFERER"], "/wishlist") ? "Career Wishlist" : "Review Saved Programs") : "Get Qualified"; ?>" href="<?php echo vcn_drupal7_base_path(); echo strpos($_SERVER["HTTP_REFERER"], "/cma/") ? "cma" : "get-qualified";?>/programs<?php echo $back_to_wishlist_link_suffix; ?>" >Back to <?php echo strpos($_SERVER["HTTP_REFERER"], "/cma/") ? (strpos($_SERVER["HTTP_REFERER"], "/wishlist") ? "Career Wishlist" : "Review Saved Programs") : "Get Qualified"; ?></a></div></div>	      	
      	<div class="nav-bar-right"><div>&nbsp;</div></div>		
      	<div class="allclear"></div>		      	
      </div>
    <!-- End of VCN Navigation bar -->			
  </div>
  
</div>
<div id="current-career-title" class="element-hidden"><?php echo $current_career_title; ?></div>        