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
<h1 class="title-top">Take a Course Online</h1>
<div id="content-area">
	<iframe id="moodleframe" name="moodleframe" alt="moodleframe" title="Moodle Classes" src="<?php echo $url; ?>" scrolling="auto" frameborder="0" style="background-color: #f2f2f2" width="1044" height="3800">
	This iframe goes to the moodle classes
  </iframe>
</div>

<script type="text/javascript">
function isUserLoggedIn() {
   <?php if ($GLOBALS['is_user_logged_in']) : ?>
     return true;
	 <?php else : ?>
	   return false;
	 <?php endif; ?>
}
function registerFromMoodleFrame() {
   // set location for a Create Account link inside the moodle frame so that when we return to the page after
   // creating the account, we remain on the framing page
   <?php if ($GLOBALS['is_user_logged_in']) : ?>
     location = <?php print vcn_drupal7_base_path(); ?> + "user/logout?destination=user/register";
	 <?php else : ?>
	   location = <?php print vcn_drupal7_base_path(); ?> + "user/register";
	 <?php endif; ?>
}

function forgotFromMoodleFrame() {
   // set location for a Create Account link inside the moodle frame so that when we return to the page after
   // creating the account, we remain on the framing page
   <?php if ($GLOBALS['is_user_logged_in']) : ?>
	   location = <?php print vcn_drupal7_base_path(); ?> + "user/logout?destination=user/password";
	 <?php else : ?>
	   location = <?php print vcn_drupal7_base_path(); ?> + "user/password";
	 <?php endif; ?>
}
</script>
