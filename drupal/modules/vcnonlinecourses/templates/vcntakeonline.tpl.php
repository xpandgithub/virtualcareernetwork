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
 * Default theme implementation to display a online courses.
 * 
 */
?>
<div id="online-courses">
	<h1 class="title-top">Take a Course Online</h1>
	<?php print $take_a_course_online_text; ?>
	<?php print $learning_exchange; ?>	
	<?php print $nter_online_courses; ?>
	
	<?php if (count($resourceslist_by_cat)>0) { ?>
	<h3 class="courses-listing-title"> Free Online Courses </h3>
	<div class="courses-listing-detail">
	  <?php print $free_online_courses; ?>			
	  <?php foreach($resourceslist_by_cat as $key => $list_by_cat  ) {
		if(count($list_by_cat) > 0) { ?>
		  <div class="resources-title strong" ><?php echo $list_by_cat["catname"]; ?></div>			
		  <ul class="nodot">
		  <?php
		    if((count($list_by_cat)-1) > $max_listing){
		    	$max_display = $max_listing;
		    }else {
		    	$max_display = (count($list_by_cat)-1);
		    }
		   
		   	  for ($i = 0; $i < $max_display; $i++) { ?>
		   	  <li> 
				<?php echo vcn_build_link_window_opener($list_by_cat[$i][1],$list_by_cat[$i][0], false);  ?>
			  </li>
			  <?php  
			  } 
			  
			  
			if((count($list_by_cat)-1) > $max_listing){ ?>
			</ul></br>
	  		<div id="<?php echo $key; ?>showmore" class="smlink" style="display:block">(<a id="mdnonelink" href="javascript:void(0);" onclick="workshowhide('<?php echo $key; ?>show');">see more</a>)<div><br/></div></div>
	        <div id="<?php echo $key; ?>showless" class="smlink" style="display:none">(<a id="mdnonelink" href="javascript:void(0);" onclick="workshowhide('<?php echo $key; ?>show');">see less</a>)<div><br/></div></div>
	        <ul id="<?php echo $key; ?>show" style="display:none;">
            <?php		      	   
  		   	  for ($i = $max_listing; $i < (count($list_by_cat)-1); $i++) { ?>
  		   	  <li>   				
  				<?php echo vcn_build_link_window_opener($list_by_cat[$i][1],$list_by_cat[$i][0], false); ?>
  			  </li>
  			  <?php  
  			  } ?>	
            </ul>
            <?php 
            }else{
	        ?>	
		  </ul> <?php
		  }
		}		
      }?>
    </div><?php 
	}?>
</div>
<!-- VCN Navigation bar -->
      <div class="vcn-user-navigation-bar allclear">
      	<div class="nav-bar-left"><div><a title="Go Back" href="javascript:history.go(-1);" >Go Back</a></div></div>	      	
      	<div class="nav-bar-right"><div>&nbsp;</div></div>		
      	<div class="allclear"></div>		      	
      </div>
<!-- End of VCN Navigation bar -->	