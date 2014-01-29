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
 * Default theme implementation to display a Office Locator page.
 * 
 */
?>
<h1 class="title">Office Locator</h1>
<div id="office-locator" >
	<p>If you are interested in receiving additional help or career counseling you can contact the 
    <?php if ($is_healthcare_industry) : ?>
    nearest participating VCN Partner Office, 
    <?php endif; ?>
    American Job Center or Community College. 
    </p>
    <?php echo $form_zip;//print render($form['zip']); ?>
    <?php echo $form_submit;//print render($form['submit']); ?>
    <?php echo $form_hidden;//print drupal_render_children($form); ?>
    <br/><br/><br/>
    <?php if(isset($zipcode) && $zipcode > 0) {?>
	<div id="office-locator-list">
    <?php if ($is_healthcare_industry) : ?>
      <div>
        <div class="strong">VCN Partner Office</div>
        <br/><?php echo $partners; ?>
      </div>
    <?php endif; ?>
		<div>
			<div class="strong">American Job Center</div>
			<br/><?php echo $one_stop_careers_centers; ?>
		</div>
		<div>
			<div class="strong">Community College</div>
			<br/><?php echo $community_colleges; ?>
		</div>
		<br style="clear: left;" />
	</div>
	<?php echo $contact_method_text; ?>
	<?php } ?>
</div>
<!-- VCN Navigation bar -->
      <div class="vcn-user-navigation-bar allclear">
      	<div class="nav-bar-left"><div><a title="Go Back" href="javascript:history.go(-1);" >Go Back</a></div></div>	      	
      	<div class="nav-bar-right"><div>&nbsp;</div></div>		
      	<div class="allclear"></div>		      	
      </div>
<!-- End of VCN Navigation bar -->	