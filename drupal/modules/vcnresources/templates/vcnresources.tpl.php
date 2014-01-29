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
 * Default theme implementation to display a Resources page.
 * 
 */

?>
<h1 class="title">Resources</h1>
<div id="resources" >
	<?php if (count($resourceslist_by_cat)>0) { ?>
		<h3 class="courses-listing-title" id="expand1operator" > Helpful Links </h3>	
	  <?php $count = 1;
	   foreach($resourceslist_by_cat as $key => $list_by_cat  ) {
		if(count($list_by_cat) > 0) { 
		  if($key == "cat8" || $key == "cat9") { $count++; ?>
			<h3 class="courses-listing-title" id="expand<?php echo $count; ?>operator" > <?php echo $list_by_cat["catname"]; ?> </h3>
		  <?php }else {?>
			<div class="resources-title strong" ><?php echo $list_by_cat["catname"]; ?></div>
		<?php } ?>
		  <ul>
		  <?php
		    if((count($list_by_cat)-1) > $max_listing){
		    	$max_display = $max_listing;
		    }else {
		    	$max_display = (count($list_by_cat)-1);
		    }
		   
		   	  for ($i = 0; $i < $max_display; $i++) { ?>
		   	  <li> 
				<?php 
				  if($list_by_cat[$i][2] == "External") {
					echo vcn_build_link_window_opener($list_by_cat[$i][1],$list_by_cat[$i][0], false); 
				  }else {
					$list_by_cat[$i][1] = str_replace('$pdfpath.', $pdfpath, $list_by_cat[$i][1]);					
					if(strlen($list_by_cat[$i][3]) < 1 || $list_by_cat[$i][3] == null) {
						$list_by_cat[$i][3] = $list_by_cat[$i][0];
					}										
					echo vcn_build_link_window_opener($list_by_cat[$i][1],$list_by_cat[$i][0], false, false, $list_by_cat[$i][3]);
				  }?>
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
  				<?php 
				  if($list_by_cat[$i][2] == "External") {
					echo vcn_build_link_window_opener($list_by_cat[$i][1],$list_by_cat[$i][0], false); 
				  }else {
					$list_by_cat[$i][1] = str_replace('$pdfpath.', $pdfpath, $list_by_cat[$i][1]);
					if(strlen($list_by_cat[$i][3]) < 1 || $list_by_cat[$i][3] == null) {
						$list_by_cat[$i][3] = $list_by_cat[$i][0];
					}
					echo vcn_build_link_window_opener($list_by_cat[$i][1],$list_by_cat[$i][0], false, false, $list_by_cat[$i][3]);
				  }?>
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
      }
	}
	//echo $resources; ?>
</div>