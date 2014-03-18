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
 * Default theme implementation to display a help/FAQ page.
 * 
 */
?>
<h1 class="title">Site Map</h1>
<?php echo $site_map_top; ?>
<li><a href="<?php echo $vcn_drupal7_base_path; ?>explorecareers">Choose A Career</a>		
	<?php  
	if (count($work_categories->item)>0) { ?>
		<ul>		
	 	<?php 
	   	  for ($i = 0; $i < count($work_categories->item); $i++) {		 
			 ?>
			<li><a href="<?php echo $vcn_drupal7_base_path; ?>careergrid/education-level/0/work-type/<?php print $work_categories->item[$i]->workcategorycode; ?>"><?php print $work_categories->item[$i]->workcategoryname; ?></a></li>
		  <?php 
		  } ?>
		</ul>
	<?php 
	} ?>	
</li>
<?php echo $site_map_bottom; ?>