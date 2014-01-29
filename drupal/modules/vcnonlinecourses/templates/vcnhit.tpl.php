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
<h1 class="title-top">Healthcare Information Technology (HIT) Instructional Program</h1>
<div id="content-area">
	<?php echo $hittop;?>
	<table>
	  <tr>
		<td id="hitleft" >
			<?php echo $hitleft;?>
			<ul><?php		
				foreach ($course->item as $v) {  ?>
				<li><?php 
					if ($v->coursecomingsoonyn=='N' && strlen($v->onlinecourseurl)) { ?>
						<a href="<?php echo $vcn_drupal7_base_path.$v->onlinecourseurl; ?>" ><?php echo $v->coursetitle; ?></a><?php
					} ?>					
				</li><?php  				
				}  ?>
			</ul>
		</td>	
		<td id="hitright" >
			<?php echo $hitright;?>
		</td>
	  </tr>
	</table>
</div>
<!-- VCN Navigation bar -->
      <div class="vcn-user-navigation-bar allclear">
      	<div class="nav-bar-left"><div><a title="Go Back" href="javascript:history.go(-1);" >Go Back</a></div></div>	      	
      	<div class="nav-bar-right"><div>&nbsp;</div></div>		
      	<div class="allclear"></div>		      	
      </div>
<!-- End of VCN Navigation bar -->	