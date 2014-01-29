<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<h1 class="title"><?php echo $cmatitle; ?>: Profile</h1>

<?php print $vcn_tabs_header; ?>
<div id="vcn-tabs-blueborder">
	<div id="cma-profile" class="cma-main-content">		
	 	 <fieldset><legend><b>Personal Information</b></legend>		
			<div id="vcncma-profile-detail">
				<?php echo $form_first_name; ?>	
				<?php echo $form_last_name; ?>	
				<?php echo $form_zipcode; ?>	
				<?php echo $form_submit; ?>	
				<?php echo $form_hidden; ?>		
			</div>
	  	</fieldset>
		<div class="allclear"></div>
		<fieldset><legend><b>Password Reset</b></legend>
			<div class="description">
				<a href="<?php echo $vcn_drupal6_base_path;?>user/<?php echo $d6userid; ?>/edit">Click here</a> to change the Password
			</div>
		</fieldset>	
	</div>
	<?php echo $nav_bar_html; ?>
</div>
<?php echo $user_nav_bar_html; ?>