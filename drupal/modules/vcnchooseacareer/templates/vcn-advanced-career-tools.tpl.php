<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<div id="vcn-advanced-career-tools" class="vcn-sidebar-tools-box rndcrnr" >
	<h3 class="vcn-sidebar-tools-header">Advanced Career Tools</h3>
	<div class="vcn-sidebar-tools-content <?php echo $show_hide;?>">	
		<?php if(!strpos($_SERVER["REQUEST_URI"], "careergrid")) { ?>	
		<form name="searchform" action="<?php print vcn_drupal7_base_path(); ?>careergrid" id="searchform" method="post" >					   
		   <div class="cac-search form-item">
				 <label class="element-invisible" for="jobtitle">Advanced Career Search</label>
				 <input id="jobtitle" name="jobtitle" autocomplete="off" size="40" maxlength="60" type="text" value="Advanced Career Search" />		             
		   </div>		       
		</form>
	    <p>&nbsp;</p>  
	   	<?php } ?>
	    <p><a href="<?php print vcn_drupal7_base_path(); ?>educationmatch" title="Match Your Education to Careers">Match Your Education to Careers</a></p>
	    <p><a href="<?php print vcn_drupal7_base_path(); ?>interest-profiler" title="Take a Short Quiz and Match Your Current Interests to Careers">Match Your Interests to Careers</a></p>
      <?php if ($is_healthcare_industry) : ?>
        <p><a href="<?php print vcn_drupal7_base_path(); ?>jobseekers" title="Unlock your potential with an online Job Seekers Guide">Gateway to Careers</a></p>	
      <?php endif; ?>
	</div>
</div>