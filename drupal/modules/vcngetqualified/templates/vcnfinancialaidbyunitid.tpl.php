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
 * Default theme implementation to display Get Qualified/Find Learning.
 * 
 */
?>
<h1 class="title">Financial Aid</h1>
<?php echo $financial_aid; ?>
<div class="find-learning-financialaid-bottom allclear">
<h3>Provider Financial Aid</h3>
	<div class="strong">
		<?php 
    	if ((string)$webaddr !== 'NULL' AND trim((string)$webaddr) !== '') {
        	echo vcn_build_link_window_opener($webaddr, $name, false, true);            
        } else {
        	echo $name;
        }
    	?>
    	<br />
    </div>    
    <div>
    	<?php 
    	if ((string)$street !== 'NULL'  AND trim((string)$street) !== '' ) {
    		echo $street.'<br />'; 
    	}
        if ((string)$city !== 'NULL'  AND trim((string)$city) !== '' ) {
        	echo $city; 
        }
        if ((string)$state !== 'NULL' ) {
        	if ((string)$city !== 'NULL' AND trim((string)$city) !== '' ) {
        		echo ', '; 
        	}
            echo $state; 
        }
        if ((string)$zipcode !== 'NULL' AND trim((string)$zipcode) !== '' ) {
        	echo ' '. $zipcode;
        }
        echo '<br />';             
        if ((string)$phone !== 'NULL' AND trim((string)$phone) !== '' ) {
        	echo ' '. vcn_format_phone( $phone ).'<br />';
        }
        if ((string)$adminurl !== 'NULL' AND trim((string)$adminurl) != '') {
           	echo vcn_build_link_window_opener($adminurl, "Admissions", false, true);
        }	           	 
        if ((string)$faidurl !== 'NULL' AND trim((string)$faidurl) !== '') { 
           	echo vcn_build_link_window_opener($faidurl, "Provider Financial Aid", false, true);
        }
	 	?>
	</div>	 
</div> 
<!-- VCN Navigation bar -->
  <div class="vcn-user-navigation-bar allclear">
	<div class="nav-bar-left"><div><a title="Go Back" href="javascript:history.go(-1);" >Go Back</a></div></div>	      	
	<div class="nav-bar-right"><div><button title="Continue to Get Qualified" onclick="location.href='<?php echo vcn_drupal7_base_path();?>get-qualified';" class="vcn-button">Continue to Get Qualified</button></div></div>		
	<div class="allclear"></div>		      	
  </div>
<!-- End of VCN Navigation bar -->	