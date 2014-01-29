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
 * Default theme implementation to Match Your Education to Careers.
 * 
 */

// This is temporary condition to open links in new window for drupal 6 career guide pages
$link_target = 'target="_self"';
if(vcn_drupal6_career_guide_D7_popup()) {
	$link_target = 'target="_blank"';
}

?>
<h1 class="title">Match Your Education to Careers</h1>
<p>Please provide the following information to determine the careers suitable for you.</p>
<div id="grey-match-box">
<form name="education_match_form" id="education_match_form" method="post" >
	<div>
		<div class="match-box">
			<b>Your Current Education Level</b>
		</div>
		<div class="match-box-title">
			<b>Education Level that you will work towards</b>
		</div>
	</div>
	<br />		
	<div>
		<div class="match-box">		
			<label for="edu_current_select">
				<select name="edu_current_select" id="edu_current_select" class="match-select" onchange="change_current_towards_options(this.value);"><?php
				  for($catcount=0; $catcount<count($occupation_cat_list->item); $catcount++) { ?>
				  	<option id="edu_current_select<?php echo $occupation_cat_list->item[$catcount]->educationcategoryid; ?>" <?php if ($edu_current==$occupation_cat_list->item[$catcount]->educationcategoryid) echo 'selected="selected"'; ?> value="<?php echo $occupation_cat_list->item[$catcount]->educationcategoryid; ?>"><?php echo $occupation_cat_list->item[$catcount]->educationcategoryname; ?></option>
				  <?php } ?>
				</select>
			</label>
		</div>
		<div class="match-box">
			<label for="edu_towards_select">
				<select name="edu_towards_select" id="edu_towards_select" class="match-select"><?php
				  for($catcount=0; $catcount<count($occupation_cat_list->item); $catcount++) { ?>
				  <option id="edu_towards_select<?php echo $occupation_cat_list->item[$catcount]->educationcategoryid; ?>" <?php if ($edu_towards==$occupation_cat_list->item[$catcount]->educationcategoryid) echo 'selected="selected"'; ?> value="<?php echo $occupation_cat_list->item[$catcount]->educationcategoryid; ?>"><?php echo $occupation_cat_list->item[$catcount]->educationcategoryname; ?></option>
				  <?php } ?>
				</select>
			</label>
		</div>
		<div class="match-box-button">
			<input type="submit" name="go" value="Go" class="vcn-button" alt="Go"  onclick="document.education_match_form.submit();" /> 	
		</div>
	</div>
	
</form>
</div>

<br/>

<div><span class="strong">Hint:&nbsp;</span><?php echo $hinttext;?></div>

<br/>

<div class="strong">Careers that you could pursue now:</div>
<div>
	<?php $titlear=array(); ?>
	<ul>
		<?php 
			for ($i=0; $i<count($occupation_current_careers->career); $i++) {
				if ($occupation_current_careers->career[$i]->displaytitle) { ?>
					<li><a <?php echo $link_target; ?> href="<?php echo vcn_drupal7_base_path(); ?>careers/<?php echo $occupation_current_careers->career[$i]->onetcode; ?>"><?php echo $occupation_current_careers->career[$i]->displaytitle; ?></a></li>
					<?php $titlear[]=(string)$occupation_current_careers->career[$i]->onetcode; 
			 	}
			 } ?>
	</ul>

</div>

<div class="strong">Careers that you could pursue after obtaining the higher education level that you have indicated:</div>
<div id="work-towards">
	<ul>
	<?php 
	$foundone = false;
	for ($i=0; $i<count($occupation_towards_careers->career); $i++) { 
		if ($occupation_towards_careers->career[$i]->displaytitle && !in_array((string)$occupation_towards_careers->career[$i]->onetcode,$titlear)) { 
			$foundone = true;
			?>
			<li><a <?php echo $link_target; ?> href="<?php echo vcn_drupal7_base_path(); ?>careers/<?php echo $occupation_towards_careers->career[$i]->onetcode; ?>"><?php echo $occupation_towards_careers->career[$i]->displaytitle; ?></a></li>
			<?php 
		} 
	} 
	?>
	</ul>
	
	<?php
	if (!$foundone) {
		?><ul><li class="nodot" >None</li></ul><?php 	
	}
	?>
</div>

<div class="strong"><a <?php echo $link_target; ?> href="<?php echo vcn_drupal7_base_path(); ?>careergrid">Explore All Careers</a></div>
<!-- VCN Navigation bar -->
      <div class="vcn-user-navigation-bar allclear">
      	<div class="nav-bar-left"><div><a title="Go Back" href="javascript:history.go(-1);" >Go Back</a></div></div>	      	
      	<div class="nav-bar-right"><div><button title="Continue Exploring Careers" onclick="location.href='<?php echo vcn_drupal7_base_path();?>explorecareers';" class="vcn-button">Continue Exploring Careers</button></div></div>		
      	<div class="allclear"></div>		      	
      </div>
<!-- End of VCN Navigation bar -->	
