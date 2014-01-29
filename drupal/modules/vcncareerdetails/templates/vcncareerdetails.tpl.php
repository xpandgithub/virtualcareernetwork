<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<div class="vcncareerdetails">  
  <div class="careerdetails-main-content">
    <div class="careerdetails-left-sidebar">  <!-- Career Left sidebar --> 
    	<h1 class="title"><?php echo $careertitle;?></h1> <!-- Career Title --> 
    	<div class="careerdetails-other-names"> <!-- Career Left Other names -->     		
            <div>
            	<em><?php print $other_names_regular_text; ?></em> &nbsp; 
            	<?php if (isset($other_names_hidden_text)): ?><a onclick="onamesmorelink();" href="javascript:void(0);" title="More Names"><span id="onamesmorelink">More Names</span></a><?php endif; ?>
            </div>
            <?php if (isset($other_names_hidden_text)) { ?>
	            <div class="element-hidden" id="onames">
	                <em><?php print $other_names_hidden_text; ?></em>
	            </div>        	
    	 	<?php } ?>							 
        </div>  	
		<?php print $vcn_tabs_header; ?> <!-- Career tabs header --> 
		<div id="vcn-tabs-blueborder">	<!-- Career tabs border --> 
			<div class="careerdetails-tabs-content"> <!-- Career tabs content --> 		
				<div class="">
					<?php echo $careerdetails_tab_content;?>	
				</div>	
				<div class="allclear"></div>
				
				<!-- Career Save Target Buttons -->  
				<div class="noresize" id="careerdetail-save-target">
				  <button title="<?php if($is_saved_or_targeted_item > 0){?>Career has already been saved<?php }else{?>Save this Career to My Wishlist<?php }?>" class="vcn-button save-target-buttons save-button <?php if($is_saved_or_targeted_item > 0){ echo 'vcn-button-disable'; }?>" <?php if($is_saved_or_targeted_item < 1){ ?>onclick="saveUserCareer('<?php echo $onetcode;?>', '<?php echo $is_user_logged_in;?>', '<?php echo $userid;?>');"<?php }?> >&nbsp;&nbsp;Save this Career<br>&nbsp;&nbsp;&nbsp;to My Wishlist</button>
				  <!-- <button title="Make This My Target" class="vcn-button save-target-buttons target-button <?php //if($is_saved_or_targeted_item > 1){ echo 'vcn-button-disable'; }?>" <?php //if($is_saved_or_targeted_item < 2){ ?>onclick="targetUserCareer('<?php //echo $onetcode;?>', '<?php //echo $is_user_logged_in;?>', '<?php //echo $userid;?>');"<?php //}?> >&nbsp;Make This<br>  My Target</button> -->
				</div>
				
				<div class="allclear"></div>
				
				<!-- Navigation bar -->
			    <div class="vcn-navigation-bar allclear">
              		<div class="bar-left"><div>&nbsp;</div></div>
              		<div class="bar-middle"><div><?php echo vcn_tab_navigation_bar(count($vcn_tabs_keys), $selected_tab_key_index+1);?></div></div>
			      	<div class="bar-right">
			      		<div>
			      		<?php if(($selected_tab_key_index) < (count($vcn_tabs_keys)-1)) { ?>
				      		<button title="Next" onclick="location.href='<?php echo $vcn_tabs_list[$selected_tab_key_index+1][3];?>';" class="vcn-button vcn-next">Next</button>
				      	<?php } else {?>
				      		&nbsp;
				      	<?php } ?>
				      	</div>
			      	</div>		
			      	<div class="allclear"></div>		      	
			    </div>
			    <!-- End of Navigation bar -->
				
			</div>
		</div>
		<!-- VCN Navigation bar -->
	      <div class="vcn-user-navigation-bar allclear">
	      	<div class="nav-bar-left"><div><a title="<?php echo $back_to_career_link_text; ?>" href="<?php echo $back_to_career_link;?>" ><?php echo $back_to_career_link_text; ?></a></div></div>	      	
	      	<div class="nav-bar-right"><div><button title="<?php if($targeted_career_count < 1){ ?>No Career Saved<?php }else{?>Review Saved Careers<?php }?>" <?php if($targeted_career_count > 0){ ?>onclick="location.href='<?php echo $vcn_drupal7_base_path;?>cma/careers';"<?php }?> class="vcn-button vcn-red-button <?php if($targeted_career_count < 1){ echo "vcn-button-disable"; }?>">Review Saved Careers</button></div></div>		
	      	<div class="allclear"></div>		      	
	      </div>
	    <!-- End of VCN Navigation bar -->
    </div>
    <div class="careerdetails-right-sidebar">  <!-- Career Right sidebar -->  
    	
    	<!-- Career Save Target Buttons -->  	
		<div class="floatright noresize careerdetail-save-target-top" id="careerdetail-save-target">
		  <button title="<?php if($is_saved_or_targeted_item > 0){?>Career has already been saved<?php }else{?>Save this Career to My Wishlist<?php }?>" class="vcn-button save-target-buttons save-button <?php if($is_saved_or_targeted_item > 0){ echo 'vcn-button-disable'; }?>" <?php if($is_saved_or_targeted_item < 1){ ?>onclick="saveUserCareer('<?php echo $onetcode;?>', '<?php echo $is_user_logged_in;?>', '<?php echo $userid;?>');"<?php }?> >&nbsp;&nbsp;Save this Career<br>&nbsp;&nbsp;&nbsp;to My Wishlist</button>
		  <!-- <button title="Make This My Target" class="vcn-button save-target-buttons target-button <?php //if($is_saved_or_targeted_item > 1){ //echo 'vcn-button-disable'; }?>" <?php //if($is_saved_or_targeted_item < 2){ ?>onclick="targetUserCareer('<?php //echo $onetcode;?>', '<?php //echo $is_user_logged_in;?>', '<?php //echo $userid;?>');"<?php //}?> >&nbsp;Make This<br>  My Target</button> -->
		</div>
		
		<!-- Career image and video link -->  
		<div class="allclear" id="careerdetails-video-link">
			<?php 
	        if (isset($videolink) && strlen($videolink)) {
	            $property = ' rel="lightvideo" ';
	            if (vcn_is_video_external_page($videolink)) {
	              $property = ' class="extlink extlink-no-css" ';
	            }
	        	$video = vcn_get_video_url($videolink);  ?>
	        	<a href="<?php print $video; ?>" <?php print $property; ?>>
					<img class="careerdetails-image" alt="Video" src="<?php print $vcn_image_base_path; ?>career_images/<?php print $image_name; ?>">
					<img class="careerdetails-play-button" alt="Play" src="<?php print $vcn_image_base_path; ?>buttons/play.png">		
				</a> <?php
          	} else { ?>
          	  	<img alt="Career image" src="<?php print $image_base_path; ?>career_images/<?php print $image_name; ?>"></img>
          	<?php
          	}  ?>			
		</div>		
		
		<div id="careerdetail-career-snapshot" class="rndcrnr">	<!-- Career Snapshot --> 				
			<h2 class="career-snapshot-title">Career Snapshot</h2>	
	
			<form id="zipcode-form" >
				<label for="zipcode-textbox" class="floatleft" style="font-weight:normal;">
          Enter ZIP Code to see locality specific information below:<br/><br/>
				</label>	
        <input type="text" id="zipcode-textbox" name="zip" maxlength="5" size="10" value="<?php print isset($zipcode) ? $zipcode : 'ZIP Code'; ?>"
            onfocus="if (this.value == 'ZIP Code') {this.value = '';}" onblur="if (this.value == '') { this.value = 'ZIP Code'; }">
				<input id="zipcode-go-button" type="submit" class="vcn-button go-button-small" value="Go"  alt="Go" title="Go" />
			</form>
									
			<div>
				<h4>Typical Education:</h4>
				<p>
					<?php print $typical_training; ?><br/>
					<span style="width:100%; text-align:right;">
						<?php if ($zipcode) { ?>
						<a href="<?php echo $vcn_drupal7_base_path; ?>get-qualified/programs/onetcode/<?php print $onetcode; ?>/zip/<?php print $zipcode; ?>" title="Find Programs in <?php print $zipcode; ?>">Find Programs in <?php print $zipcode; ?></a>
					<?php }else{ ?>
						<a href="<?php echo $vcn_drupal7_base_path; ?>get-qualified/programs/onetcode/<?php print $onetcode; ?>" title="Find Programs">Find Programs</a>
					<?php } ?>							
					</span>
				</p>
				
				<h4>Percent Job Growth:</h4>
				<p>
					<?php print (($zipcode) 
							? ($percent_job_growth_value_for_zipcode ? ($percent_job_growth_value_for_zipcode == -99 ? "Not Available" : $percent_job_growth_value_for_zipcode.$state_job_growth_text) : "Not Available") 
							: ($percent_job_growth_value_for_national ? ($percent_job_growth_value_for_national == -99 ?  "Not Available" : $percent_job_growth_value_for_national.$national_job_growth_text) : "Not Available")) 
					?><br/>
					<span style="width:100%; text-align:right;">
						<?php if ($zipcode) { ?>
							<a href="<?php echo $vcn_drupal7_base_path; ?>findwork-results/career/<?php print $onetcode; ?>/zip/<?php print $zipcode; ?>/distance/<?php print $GLOBALS['default_distance']; ?>" title="Find Jobs in <?php print $zipcode; ?>">Find Jobs in <?php print $zipcode; ?></a>
						<?php }else{ ?>
							<a href="<?php echo $vcn_drupal7_base_path; ?>findwork-results/career/<?php print $onetcode; ?>" title="Find Jobs">Find Jobs</a>
						<?php } ?>							
					</span>
				</p>
				
				<?php if ($zipcode){ print "<h4>Typical Wages in ".$zipcode.":</h4>"; }else{ print "<h4>Typical Wages:</h4>"; }?>
				<p><b>Annual:</b> <?php print (($zipcode) ? $typical_annual_salary_range_zipcode : $typical_annual_salary_range_national); ?></p>
				<p><b>Hourly:</b> <?php print (($zipcode) ? $typical_hourly_wages_range_zipcode : $typical_hourly_wages_range_national); ?></p>   
				<br/>
			</div>  						
		</div>
		
		<!-- Career Ladder link -->        
        <?php    		
 		if ($careerladderyn == "Y") {	?>
 			<div id="career-pathway-link">
            	<a target="_blank" href="<?php echo $vcn_drupal7_base_path; ?>careerladder/onetcode/<?php echo $onetcode;?>/lightbox" title="Career Pathway">View Career Pathway Map</a>
        	</div> 					
		<?php } ?>			
		
	</div>
    <div class="allclear"></div>
  </div>
</div>
