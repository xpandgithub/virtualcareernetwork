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
<h1 class="title">Frequently Asked Questions</h1>
<?php echo $help; 

if (count($videos->item)>0) { ?>
<div><br/>For information on how to navigate the VCN explore the following videos: <br/><br/>
  <select name="videobox" id="videobox" class="faq-video-play" ONCHANGE="if (this.options[this.selectedIndex].value.length>1) window.open(this.options[this.selectedIndex].value,'_blank');">		
    <option value="">VCN Tutorial Videos</option>
	  <?php 
   	  for ($i = 0; $i < count($videos->item); $i++) {
		if(strpos($videos->item[$i]->resourcelink,"http://")===false) {
		  $videos->item[$i]->resourcelink="http://".$videos->item[$i]->resourcelink;
		}
		 ?>
		<option value="<?php print $videos->item[$i]->resourcelink; ?>"><?php print $videos->item[$i]->resourcename; ?></option>
	  <?php 
	  } ?>
  </select>
</div>
<?php 
} ?>
