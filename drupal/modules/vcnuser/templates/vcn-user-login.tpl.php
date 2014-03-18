<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<h1 class="title">MyVCN Account: Sign In</h1>
<div id="vcn-login-register">
	<div class="vcn-tabs-blueheader">
			<?php if($is_provider_sign_in) : ?>
				<a title="Create New Account" href="<?php print $base_path; ?>provider-register" class="vcn_tabs-links" id="istartlink">
			<?php else: ?>
				<a title="Create New Account" href="<?php print $base_path; ?>user/register" class="vcn_tabs-links" id="istartlink">
			<?php endif; ?>
				<div id="istartlinkinner">
					<div class="vcn-tabs-off-left" id="istartleft"></div>
					<div class="vcn-tabs-off" id="istartmiddle">
						<div class="sptext noresize">Create New Account</div>
					</div>
					<div class="vcn-tabs-off-right" id="istartright"></div>
				</div>
			</a>
			<img class="floatleft" width="3" src="<?php print $image_path; ?>miscellaneous/transparent.gif" alt="">
			<?php if($is_provider_sign_in): ?>
				<a title="Sign In" href="<?php print $base_path; ?>user?type=provider" class="vcn_tabs-links" id="istartlink">
			<?php else: ?>
				<a title="Sign In" href="<?php print $base_path; ?>user" class="vcn_tabs-links" id="istartlink">
			<?php endif; ?>
				<div id="istartlinkinner">
					<div class="vcn-tabs-on-left" id="istartleft"></div>
					<div class="vcn-tabs-on" id="istartmiddle">
						<div class="sptext noresize">Sign In</div>
					</div>
					<div class="vcn-tabs-on-right" id="istartright"></div>
				</div>
			</a>
			<img class="floatleft" width="3" src="<?php print $image_path; ?>miscellaneous/transparent.gif" alt="">
			<?php if($is_provider_sign_in): ?>
				<a title="Forgot Password" href="<?php print $base_path; ?>user/password?type=provider" class="vcn_tabs-links" id="istartlink">
			<?php else: ?>
				<a title="Forgot Password" href="<?php print $base_path; ?>user/password" class="vcn_tabs-links" id="istartlink">
			<?php endif; ?>
				<div id="istartlinkinner">
					<div class="vcn-tabs-off-left" id="istartleft"></div>
					<div class="vcn-tabs-off" id="istartmiddle">
						<div class="sptext noresize">Forgot Password</div>
					</div>
					<div class="vcn-tabs-off-right" id="istartright"></div>
				</div>
			</a>
	</div>
	<div id="vcn-login-register-form-wrapper">
		<fieldset>
			<legend>User Information</legend>
			<?php print drupal_render($form['name']); ?>
			<?php print drupal_render($form['pass']); ?>
		</fieldset>
  	<div class="vcn-login-register-form-buttons-wrapper">
			<?php print drupal_render($form['actions']['submit']); ?>
			<input class="vcn-button form-submit login-register-cancel-button" type="submit" id="login-cancel-button" name="op" value="Cancel" title="Cancel">
		</div>
	</div>
</div>
<?php print drupal_render_children($form); ?>