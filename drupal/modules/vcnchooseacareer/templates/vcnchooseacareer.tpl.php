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
 * Default theme implementation to display a Office Locator page.
 * 
 */

?>
<h1 class="title"><?php print $page_title; ?></h1>
<div id="choose-a-career" >
	<!-- Left sidebar -->
	<div id="cac-left">
		<div id="cac-maintext"><?php print $cactext; ?></div>
    
    <?php if (is_array($onet_assessment_matching_careers) || $display_careers_match_by_default) :?>
      <div id="cac-match-interest-outer">
        <strong class="cac-highlight">Top Careers That Match Your Interests</strong>
        <div id="cac-match-interest-inner">
          <?php 
          if (is_array($onet_assessment_matching_careers)) : 
            if (count($onet_assessment_matching_careers) > 0) :
              foreach ($onet_assessment_matching_careers as $career) :
          ?>
                <a href="<?php print $career['details_url']; ?>"><?php print $career['title']; ?></a><br/>
            <?php
              endforeach;
            ?>
              <br/> Try again, <a href="<?php print $vcn_drupal7_base_path; ?>interest-profiler">Match Your Interests to Careers</a>.
            <?php
            else :
            ?>
              Based upon the interests you have expressed, no careers were found. <br/> <br/>
              Try again, <a href="<?php print $vcn_drupal7_base_path; ?>interest-profiler">Match Your Interests to Careers</a>. 
            <?php
            endif;
            ?>
          <?php else: ?>
             Your interests can help you find careers you might like to explore. The more a career meets your interests, the more likely it will be satisfying and rewarding to you. <br/> <br/>
             Try to <a href="<?php print $vcn_drupal7_base_path; ?>interest-profiler">Match Your Interests to Careers</a>.
          <?php endif; ?>
        </div>
      </div>
    <?php endif; ?>
    
		<strong class="cac-highlight">Explore Careers by Category below</strong>, or <a class="strong cac-highlight" href="<?php echo $vcn_drupal7_base_path; ?>careergrid">Search All <?php print $industry_name; ?> Careers</a>
		<br/><br/>
		<?php echo $vcn_tabs_header; ?>	
		<?php echo $vcn_tabs_body_start; ?>	
			<?php
   			if (count($work_categories->item)>0) { 
   				for ($i = 0; $i < count($work_categories->item); $i++) { ?>
				    <div id="<?php echo $vcn_tabs_body_id_prefix.$work_categories->item[$i]->workcategorycode; ?>" class="thetabs" style="display: <?php if($i == 0){ ?>block<?php }else { ?>none<?php } ?>">
				      <div class="cac-tab-cat-top" > 
				      	<div class="cac-tab-cat-top-div-left" >
				          <span class="tab-header" ><?php echo $work_categories->item[$i]->workcategoryname; ?></span><br/><br/>
				          <?php $workcategorydesc = vcn_process_work_type_desc($work_categories->item[$i]->workcategorydesc, 400); //echo $work_categories->item[$i]->workcategorydesc; 
							$shortdesc_regular_text = $workcategorydesc['shortdesc_regular_text'];
							$shortdesc_hidden_text = $workcategorydesc['shortdesc_hidden_text'];?>
				          <div id="career-worktype-description">
							<div id="career-worktype-description-regular-text">
								<?php print $shortdesc_regular_text; ?><?php if (isset($shortdesc_hidden_text) && strlen($shortdesc_hidden_text)) { ?><span class="moredetail"> ...</span><?php }?>
							</div>
							<?php if (isset($shortdesc_hidden_text) && strlen($shortdesc_hidden_text)) { ?>
							<div id="career-worktype-description-hidden-text"  class="element-hidden">
								<?php print $shortdesc_hidden_text; ?>
							</div>
							<a class="morelink" href="javascript:void(0);" title="More Details">More Details</a>
							<?php } ?>			
				         </div>
				          <br/><br/>
                  
		                  <?php $careerladder = $vcn_industry_image_path . "career_pathways/cp." . strtolower($work_categories->item[$i]->workcategorycode) . ".jpg"; ?>
		                  <?php if (file_exists("..".$careerladder)) : ?>
		                  	See the <a target="_blank" href="<?php echo $vcn_drupal7_base_path; ?>careerladder/<?php echo strtolower($work_categories->item[$i]->workcategorycode);?>/lightbox" title="Career Pathway By Education" >Career Pathway By Education</a> for this category<br/><br/>
		                  <?php endif; ?>
		                </div>
		                <?php 
	                  $image_name = 'photo.' . strtolower($work_categories->item[$i]->workcategorycode) . '.jpg';
	                  $filename = $GLOBALS['vcn_config_http_or_https'] . '://' . $GLOBALS['vcn_config_base_url'] . $vcn_industry_image_path . 'tab_images/' . $image_name;
	                  $image_src = $vcn_industry_image_path.'tab_images/'.$image_name;
                		?>
				      	<div class="cac-tab-cat-top-div-right" >
				      	  <img height="125" width="125" align="right" src="<?php echo $image_src; ?>" alt="<?php echo $work_categories->item[$i]->workcategoryname; ?> image" style="border: 1px solid #c6c6c6;"/>
				      	</div>
				      </div>				      
				      <div class="allclear" >&nbsp;</div>
				      
				      <div class="cac-tab-cat-bottom allclear" >
                		<span class="tab-header" >Careers in <?php //echo $work_categories->item[$i]->workcategoryname; ?>this category organized by typical educational requirements</span><br/><br/>
                
				      <?php 
				      $wtkey = "wt-".$work_categories->item[$i]->workcategorycode; 	

				      for ($edu = 1; $edu <= 3; $edu++) {

		                $edulist_key = "group_".$edu;
		                $edulist_title = isset($education_group_list->item) ? $education_group_list->item[($edu-1)]->groupname : "";
		                $edulist_title_class = ($edu == 2) ? "tabmiddle nowrap strong underline cac-tab-cat-title" : "nowrap strong underline cac-tab-cat-title";
		              ?>
						
		                <div class="cac-tab-cat-bottom-div">
						      		<div class="<?php echo $edulist_title_class;?>" ><?php echo $edulist_title;?></div>	
		                  <?php
		                  if (isset($careers_byworktype_edu[$wtkey][$edulist_key]) && count($careers_byworktype_edu[$wtkey][$edulist_key])>0) {
		                  ?>			      		
				      		  <ul>
							  <?php
							    if((count($careers_byworktype_edu[$wtkey][$edulist_key])) > $max_listing){
							    	$max_display = $max_listing;
							    }else {
							    	$max_display = (count($careers_byworktype_edu[$wtkey][$edulist_key]));
							    }
							   
							   	  for ($e = 0; $e < $max_display; $e++) { ?>
							   	  <li> 
									<a href="<?php echo $vcn_drupal7_base_path; ?>careers/<?php echo $careers_byworktype_edu[$wtkey][$edulist_key][$e][1]; ?>"><?php echo $careers_byworktype_edu[$wtkey][$edulist_key][$e][0] ;?></a>
								  </li>
								  <?php  
								  }							 
								  
								if((count($careers_byworktype_edu[$wtkey][$edulist_key])) > $max_listing){ ?>
				                  </ul>
												
				                  <div id="<?php echo $work_categories->item[$i]->workcategorycode.$edu; ?>showmore" class="smlink" style="display:block">(<a id="<?php echo $work_categories->item[$i]->workcategorycode.$edu; ?>link" href="javascript:void(0);" onclick="workshowhide('<?php echo $work_categories->item[$i]->workcategorycode.$edu; ?>show');">see more</a>)</div>
								  <div id="<?php echo $work_categories->item[$i]->workcategorycode.$edu; ?>showless" class="smlink" style="display:none">(<a id="<?php echo $work_categories->item[$i]->workcategorycode.$edu; ?>link" href="javascript:void(0);" onclick="workshowhide('<?php echo $work_categories->item[$i]->workcategorycode.$edu; ?>show');">see less</a>)</div>
								            
				                    <ul id="<?php echo $work_categories->item[$i]->workcategorycode.$edu; ?>show" class="career-sm-ul" style="display:none;">
						            <?php		      	   
						  		   	  for ($e = $max_listing; $e < count($careers_byworktype_edu[$wtkey][$edulist_key]); $e++) { ?>
						  		   	  <li> 
				                        <a href="<?php echo $vcn_drupal7_base_path; ?>careers/<?php echo $careers_byworktype_edu[$wtkey][$edulist_key][$e][1]; ?>"><?php echo $careers_byworktype_edu[$wtkey][$edulist_key][$e][0] ;?></a>
				                      </li>
						  			  <?php  
						  			  } ?>	
						            </ul>
					            <?php 
					            }else{
						        ?>	
							  </ul> <?php
							  } 
							   
				      		} else { ?>
		 					<ul><!-- <li>None</li> --></ul><?php 
							} ?>			      		
				      	</div>
				      	
						<?php
					  }			      
				      ?>				      			     
					  </div>				     
				      <div class="allclear" >&nbsp;</div>
				      
					  <div>
					  	<span class="strong"><a href="<?php echo $vcn_drupal7_base_path; ?>careergrid/education-level/0/work-type/<?php echo $work_categories->item[$i]->workcategorycode; ?>">See All <?php echo $work_categories->item[$i]->workcategoryname; ?> Careers</a></span>
				      </div>
				      
				      <!-- Navigation bar -->
				      <div class="vcn-navigation-bar allclear">
				      	<div class="bar-left"><div>&nbsp;</div></div>
				      	<div class="bar-middle"><div><?php echo vcn_tab_navigation_bar(count($work_categories->item), $i+1);?></div></div>
				      	<div class="bar-right"><div><?php if($i < (count($work_categories->item)-1)) { ?><button title="Next" onclick="vcn_change_tab('<?php echo $work_categories->item[$i+1]->workcategorycode; ?>','<?php echo implode(",", $vcn_tabs_keys); ?>');" class="vcn-button vcn-next">Next</button><?php } else {?>&nbsp;<?php } ?></div></div>		
				      	<div class="allclear"></div>		      	
				      </div>
				      <!-- End of Navigation bar -->
				         
				    </div><?php 
			 	}
   			} ?>		    
		<?php echo $vcn_tabs_body_end; ?>
		<!-- VCN Navigation bar -->
	      <div class="vcn-user-navigation-bar allclear">
	      	<div class="nav-bar-left"><div><a title="Back to Get Started" href="<?php echo $vcn_drupal7_base_path; ?>get-started" >Back to Get Started</a></div></div>	      	
	      	<div class="nav-bar-right"><div><button title="<?php if($targeted_career_count < 1){ ?>No Career Saved<?php }else{?>Review Saved Careers<?php }?>" <?php if($targeted_career_count > 0){ ?>onclick="location.href='<?php echo $vcn_drupal7_base_path;?>cma/careers';"<?php }?> class="vcn-button vcn-red-button <?php if($targeted_career_count < 1){ echo "vcn-button-disable"; }?>">Review Saved Careers</button></div></div>		
	      	<div class="allclear"></div>		      	
	      </div>
	    <!-- End of VCN Navigation bar -->				
	</div>	
	<!-- Right sidebar -->
	<div id="cac-right">
			<?php print theme('vcn_advanced_career_tools'); ?>
			<?php print theme('vcn_account_setup_box'); ?>
	</div>	
</div>
