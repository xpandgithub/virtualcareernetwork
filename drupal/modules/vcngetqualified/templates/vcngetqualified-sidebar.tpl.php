<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php if($learning_exchange != "") { ?>
<div class="vcn-sidebar-tools-box rndcrnr" >
	<h3 class="vcn-sidebar-tools-header">Learning Exchange</h3>
	<div class="vcn-sidebar-tools-content <?php echo $selected_tab_index == "req" ? "element-hidden" : ""?>"> 	
	  <?php echo $learning_exchange;?>  	    	    	    
	</div>
</div>	
<?php } ?>

<div class="vcn-sidebar-tools-box rndcrnr" >
	<h3 class="vcn-sidebar-tools-header">Education Tools</h3>
	<div class="vcn-sidebar-tools-content <?php echo $selected_tab_index == "prog" ? "element-hidden" : ""?>">
		
	  <h4>No High School Diploma?</h4>	   
	  <p><?php echo vcn_build_link_window_opener($vcn_d7_path."online-courses/take-online?state=A", "Find Virtual High Schools", false, false, "Find Virtual High Schools", "");?></p>
		
	  <h4>How to choose a College</h4>	   
	  <p><?php echo vcn_build_link_window_opener("http://collegesearch.collegeboard.com/search/index.jsp", "College Search", false, true);?></p>
	  <p><?php echo vcn_build_link_window_opener("http://www.collegebound.net/college", "All About Four-Year Colleges and Universities", false, true);?></p>
	  
	  <h4>Prerequisite Tests for College Admission</h4>	   
	  <p><?php echo vcn_build_link_window_opener($vcn_d7_path."sat-information", "SAT", false, false, "Scholastic Aptitude Test", "");?></p>
	  <p><?php echo vcn_build_link_window_opener($vcn_d7_path."actinformation", "ACT", false, false, "ACT Test", "");?></p>
	  <p><?php echo vcn_build_link_window_opener($vcn_d7_path."compass-description", "COMPASS", false, false, "COMPASS Test", "");?></p>
	  <p><?php echo vcn_build_link_window_opener($vcn_d7_path."accuplacer", "ACCUPLACER", false, false, "ACCUPLACER", "");?></p>
	  
	  <h4>How to Apply to College</h4>	    
	  <p><?php echo vcn_build_link_window_opener("http://bigfuture.collegeboard.org/get-in/applying-101/college-application-checklist", "College Board Application Checklist", false, true);?></p>
	  <p><?php echo vcn_build_link_window_opener("http://admissionpossible.com/Completing_Applications.html", "Application completion process", false, true);?></p>
	  <p><?php echo vcn_build_link_window_opener("http://www.drexel.com/tools/transcript.aspx?process=alpha&letter=A", "Where to get Transcripts for a college attended", false, true);?></p>
	     
	  <h4>How to get Financial Aid</h4>	   
	  <p><?php echo vcn_build_link_window_opener($vcn_d7_path."get-qualified/financialaid", "Learn about financial aid options", false, false, "Learn about financial aid options", "");?></p>
	    	    	    
	</div>
</div>	
