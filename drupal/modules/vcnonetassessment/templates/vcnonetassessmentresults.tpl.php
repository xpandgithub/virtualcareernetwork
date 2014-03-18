<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<h1 class="title">Match Your Interests to Careers</h1>

<div class="vcn-profiler-result">
  <div>
  	<?php if (count($careers_of_current_industry) > 0 || count($careers_of_other_industry) > 0) { ?>
	  	<p>
	      Here are your Interest Profiler results (RIASEC:   
	      Realistic=<?php print $score_realistic; ?>, Investigative=<?php print $score_investigative; ?>, 
	      Artistic=<?php print $score_artistic; ?>, Social=<?php print $score_social; ?>, 
	      Enterprising=<?php print $score_enterprising; ?>, Conventional=<?php print $score_conventional; ?>)!     
		      Think of your interests as work you like to do.
	        Your interests can help you find careers you might like to explore. The more a career meets 
	        your interests, the more likely it will be satisfying and rewarding to you.  For information
	        on how the Interest Profiler determines the results <a href="<?php print vcn_drupal7_base_path(); ?>interest-profiler-info/industry/<?php print $industry; ?>">click here</a>.
	    </p>
	<?php } ?>
	<div class="vcn-left-sidebar">
	  <div class="vcn-sidebar-tools-box rndcrnr">
	  	<div class="vcn-sidebar-tools-content">
		  <?php  
		  if (count($careers_of_current_industry) > 0) {
		  ?>	  	
		    <p class="vcn-profiler-result-header">
		      The following are the top careers in <span class="strong"><?php echo vcn_get_industry_name(); ?></span> matching your interests based on the results:
		    </p>
		  <?php
		    foreach ($careers_of_current_industry as $career) {
		  ?>
	      <div class="profiler-result-outer-container">
	        <div class="profiler-result-inner-container-left">
	          <p><img src="<?php print $career['image_url']; ?>" class="profiler-result-career-image" alt="career image" />
	        </div>
	        <div class="profiler-result-inner-container-right">
	          <p><strong>#<?php print $career['rank']; ?>: <?php print $career['title']; ?></strong></p>
	          <p><?php print $career['description']; ?></p>
	          <p><a href="<?php print $career['details_url']; ?>" <?php print ($is_using_lightbox) ? 'target="_blank"' : ''; ?>>View Details</a></p>
	        </div>
	        <div class="allclear"></div>
		    </div>
		  <?php
		    }
		  }else{
		  ?>
		    <p>Based upon the interests you have expressed, no careers were found in <span class="strong"><?php echo vcn_get_industry_name(); ?></span>.</p>
		    <p>To try again, <a href="<?php print vcn_drupal7_base_path(); ?>interest-profiler/industry/<?php print $industry; ?><?php print ($is_using_lightbox) ? '/lightbox' : ''; ?>">click here</a></p>
		  <?php
		  }
		  ?>
		</div>
	  </div>			
	</div>
	
	<div class="vcn-right-sidebar">
	  <?php  
	  if (count($careers_of_other_industry) > 0) { ?>
	    <div class="vcn-sidebar-tools-box rndcrnr">
	  	  <div class="vcn-sidebar-tools-content">	
		   <div> 
		      <br/><span>The following are the top careers <span class="strong">outside <?php echo vcn_get_industry_name(); ?></span> matching your interests based on the results:</span>
		   </div>
		  <?php
		    foreach ($careers_of_other_industry as $career) { ?>
	      	  <div>        
			    <p><span class="strong">#<?php print $career['rank']; ?></span>: <?php print $career['title']; ?><?php //print $career['onetcode']; ?></p>
		      </div>
		  <?php
		    } ?>
		  </div>
		</div>
	  <?php 
	  } ?>
	</div>
  </div>	
  <?php if (vcn_external_client_calling_interest_profiler()) { ?>
    <br/>
    <button onclick="location='<?php print vcn_drupal7_base_path(); ?>interest-profiler/industry/<?php print $industry; ?>'" class="vcn-button <?php print $alt_color_class; ?>">Go Back</button>
  <?php } else { ?>
    <!-- VCN Navigation bar -->
    <div class="vcn-user-navigation-bar allclear">
      <div class="nav-bar-left"><div><a title="Back" href="javascript:history.go(-1);">Go Back</a></div></div>	 
      <div class="nav-bar-right"><div><button title="Continue Exploring Careers" onclick="location.href='<?php echo $vcn_drupal7_base_path;?>explorecareers';" class="vcn-button">Continue Exploring Careers</button></div></div>      
      <div class="allclear"></div>		      	
    </div>
    <!-- End of VCN Navigation bar -->
  <?php } ?>
    
	<br/><br/>

  <div class="profiler-onet-logo">
    <a href="http://www.onetonline.org" target="_blank"><img src="<?php print vcn_image_path(); ?>site_logo_images/onet.png" height="60" alt="ONet logo" /></a>
  </div>
  
</div>