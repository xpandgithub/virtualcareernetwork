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
 *   Themes the vcn footer block
 */

echo $footer_content;

if($footer_content == "") {
?>
<br/><br/><hr/> 
<div id="copyright">	 
  <a class="dol-seal" href="javascript:popit('http://dol.gov')" title="Department of Labor">
	<img  alt="department of labor seal" src="<?php echo vcn_image_path() ?>site_logo_images/logo_dol.jpg" alt="Department of Labor" />
  </a>
	 
  <p class="copyright-text">		  
	The VCN sponsored by the U.S. Department of Labor, Employment and Training Administration 
	under the leadership of the American Association of Community Colleges
	<br /><br />
	Copyright &copy; <?php echo date("Y")?> American Association of Community Colleges		  
  </p>
	 
  <a class="aacc-seal" href="javascript:popit('http://www.aacc.nche.edu')" title="American Association of Community Colleges">
	<img src="<?php echo vcn_image_path() ?>site_logo_images/aacc2.jpg" alt="American Association of Community Colleges" />
  </a>  
</div> 
<div style="clear:both;"></div>
<?php }?>