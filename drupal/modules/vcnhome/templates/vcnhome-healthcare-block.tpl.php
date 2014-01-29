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
 *   Themes the VCN Healthcare Home block
 */
?>
<div class="fp-container1" id="fp-container1">
  <img src="<?php echo $vcn_industry_image_path; ?>home_images/banner_learn.jpg" alt="button navigation image map" usemap="#topButtonNav"/ style="outline:none;">
  <map name="topButtonNav" title="Splash Image">
    <area shape="poly" coords="78,91,275,91,279,110,275,130,78,130" href="<?php echo vcn_drupal7_base_path(); ?>explorecareers" alt="Choose a Career" title="Pick the right healthcare career.">
    <area shape="poly" coords="78,133,246,133,267,152,246,172,78,172" href="<?php echo vcn_drupal7_base_path(); ?>get-qualified" alt="Get Qualified" title="Locate the education or training you need to succeed.">
    <area shape="poly" coords="78,175,312,175,333,195,312,214,78,214" href="<?php echo vcn_drupal7_base_path(); ?>online-courses/take-online" alt="Take a Course Online" title="Use the VCN's Learning Exchange to find and take courses online.">
    <area shape="poly" coords="78,217,225,217,248,236,225,256,78,256" href="<?php echo vcn_drupal7_base_path(); ?>findwork" alt="Find a Job" title="Find your job in healthcare.">
    <area shape="rect" coords="19,330,149,459" rel="lightvideo" href="<?php echo $vcn_video_path; ?>Why_healthcare_AACC_VCN0.flv" toptions="width = 425, height = 240, resizable = 1, layout=flatlook, shaded=1" alt="Watch a Video" title="Watch a video about a career in healthcare">
    <area shape="rect" coords="188,334,325,464" href="<?php echo vcn_drupal7_base_path(); ?>new-to-vcn" alt="New to VCN" title="New to VCN">
    <area shape="rect" coords="355,334,480,461" href="<?php echo vcn_drupal7_base_path(); ?>pla/getting-started" alt="Earn College Credits" title="Get College Credits">
    <area shape="rect" coords="528,295,592,316" onclick="display(0);" href="javascript:void(0);" alt="Learn" title="Learn" class="cursorpointer">
    <area shape="rect" coords="638,295,701,316" onclick="display(1);" href="javascript:void(0);" alt="Earn" title="Earn" class="cursorpointer">
    <area shape="rect" coords="750,295,815,316" onclick="display(2);" href="javascript:void(0);" alt="Advance" title="Advance" class="cursorpointer">
    <area shape="rect" coords="862,285,925,316" onclick="display(3);" href="javascript:void(0);" alt="Serve" title="Serve" class="cursorpointer">
  </map>
  <div class="rotating-text">
  </div>
</div>

<div class="fp-container2" id="fp-container2" style="display:none">
  <img src="<?php echo $vcn_industry_image_path; ?>home_images/banner_earn.jpg" alt="button navigation image map" usemap="#topButtonNav"/ style="outline:none;">
  <map name="topButtonNav" title="Splash Image">
    <area shape="poly" coords="78,91,275,91,279,110,275,130,78,130" href="<?php echo vcn_drupal7_base_path(); ?>explorecareers" alt="Choose a Career" title="Pick the right healthcare career.">
    <area shape="poly" coords="78,133,246,133,267,152,246,172,78,172" href="<?php echo vcn_drupal7_base_path(); ?>get-qualified" alt="Get Qualified" title="Locate the education or training you need to succeed.">
    <area shape="poly" coords="78,175,312,175,333,195,312,214,78,214" href="<?php echo vcn_drupal7_base_path(); ?>online-courses/take-online" alt="Take a Course Online" title="Use the VCN's Learning Exchange to find and take courses online.">
    <area shape="poly" coords="78,217,225,217,248,236,225,256,78,256" href="<?php echo vcn_drupal7_base_path(); ?>findwork" alt="Find a Job" title="Find your job in healthcare.">
    <area shape="rect" coords="19,330,149,459" rel="lightvideo" href="<?php echo $vcn_video_path; ?>Why_healthcare_AACC_VCN0.flv" toptions="width = 425, height = 240, resizable = 1, layout=flatlook, shaded=1" alt="Watch a Video" title="Watch a video about a career in healthcare">
    <area shape="rect" coords="188,334,325,464" href="<?php echo vcn_drupal7_base_path(); ?>new-to-vcn" alt="New to VCN" title="New to VCN">
    <area shape="rect" coords="355,334,480,461" href="<?php echo vcn_drupal7_base_path(); ?>pla/getting-started" alt="Earn College Credits" title="Get College Credits">
    <area shape="rect" coords="528,295,592,316" onclick="display(0);" href="javascript:void(0);" alt="Learn" title="Learn" class="cursorpointer">
    <area shape="rect" coords="638,295,701,316" onclick="display(1);" href="javascript:void(0);" alt="Earn" title="Earn" class="cursorpointer">
    <area shape="rect" coords="750,295,815,316" onclick="display(2);" href="javascript:void(0);" alt="Advance" title="Advance" class="cursorpointer">
    <area shape="rect" coords="862,285,925,316" onclick="display(3);" href="javascript:void(0);" alt="Serve" title="Serve" class="cursorpointer">
  </map>
  <div class="rotating-text">
  </div>
</div>

<div class="fp-container3" id="fp-container3" style="display:none">
  <img src="<?php echo $vcn_industry_image_path; ?>home_images/banner_advance.jpg" alt="button navigation image map" usemap="#topButtonNav"/ style="outline:none;">
  <map name="topButtonNav" title="Splash Image">
    <area shape="poly" coords="78,91,275,91,279,110,275,130,78,130" href="<?php echo vcn_drupal7_base_path(); ?>explorecareers" alt="Choose a Career" title="Pick the right healthcare career.">
    <area shape="poly" coords="78,133,246,133,267,152,246,172,78,172" href="<?php echo vcn_drupal7_base_path(); ?>get-qualified" alt="Get Qualified" title="Locate the education or training you need to succeed.">
    <area shape="poly" coords="78,175,312,175,333,195,312,214,78,214" href="<?php echo vcn_drupal7_base_path(); ?>online-courses/take-online" alt="Take a Course Online" title="Use the VCN's Learning Exchange to find and take courses online.">
    <area shape="poly" coords="78,217,225,217,248,236,225,256,78,256" href="<?php echo vcn_drupal7_base_path(); ?>findwork" alt="Find a Job" title="Find your job in healthcare.">
    <area shape="rect" coords="19,330,149,459" rel="lightvideo" href="<?php echo $vcn_video_path; ?>Why_healthcare_AACC_VCN0.flv" toptions="width = 425, height = 240, resizable = 1, layout=flatlook, shaded=1" alt="Watch a Video" title="Watch a video about a career in healthcare">
    <area shape="rect" coords="188,334,325,464" href="<?php echo vcn_drupal7_base_path(); ?>new-to-vcn" alt="New to VCN" title="New to VCN">
    <area shape="rect" coords="355,334,480,461" href="<?php echo vcn_drupal7_base_path(); ?>pla/getting-started" alt="Earn College Credits" title="Get College Credits">
    <area shape="rect" coords="528,295,592,316" onclick="display(0);" href="javascript:void(0);" alt="Learn" title="Learn" class="cursorpointer">
    <area shape="rect" coords="638,295,701,316" onclick="display(1);" href="javascript:void(0);" alt="Earn" title="Earn" class="cursorpointer">
    <area shape="rect" coords="750,295,815,316" onclick="display(2);" href="javascript:void(0);" alt="Advance" title="Advance" class="cursorpointer">
    <area shape="rect" coords="862,285,925,316" onclick="display(3);" href="javascript:void(0);" alt="Serve" title="Serve" class="cursorpointer">
  </map>
  <div class="rotating-text">
  </div>
</div>

<div class="fp-container4" id="fp-container4" style="display:none">
  <img src="<?php echo $vcn_industry_image_path; ?>home_images/banner_contribute.jpg" alt="button navigation image map" usemap="#topButtonNav"/ style="outline:none;">
  <map name="topButtonNav" title="Splash Image">
    <area shape="poly" coords="78,91,275,91,279,110,275,130,78,130" href="<?php echo vcn_drupal7_base_path(); ?>explorecareers" alt="Choose a Career" title="Pick the right healthcare career.">
    <area shape="poly" coords="78,133,246,133,267,152,246,172,78,172" href="<?php echo vcn_drupal7_base_path(); ?>get-qualified" alt="Get Qualified" title="Locate the education or training you need to succeed.">
    <area shape="poly" coords="78,175,312,175,333,195,312,214,78,214" href="<?php echo vcn_drupal7_base_path(); ?>online-courses/take-online" alt="Take a Course Online" title="Use the VCN's Learning Exchange to find and take courses online.">
    <area shape="poly" coords="78,217,225,217,248,236,225,256,78,256" href="<?php echo vcn_drupal7_base_path(); ?>findwork" alt="Find a Job" title="Find your job in healthcare.">
    <area shape="rect" coords="19,330,149,459" rel="lightvideo" href="<?php echo $vcn_video_path; ?>Why_healthcare_AACC_VCN0.flv" toptions="width = 425, height = 240, resizable = 1, layout=flatlook, shaded=1" alt="Watch a Video" title="Watch a video about a career in healthcare">
    <area shape="rect" coords="188,334,325,464" href="<?php echo vcn_drupal7_base_path(); ?>new-to-vcn" alt="New to VCN" title="New to VCN">
    <area shape="rect" coords="355,334,480,461" href="<?php echo vcn_drupal7_base_path(); ?>pla/getting-started" alt="Earn College Credits" title="Get College Credits">
    <area shape="rect" coords="528,295,592,316" onclick="display(0);" href="javascript:void(0);" alt="Learn" title="Learn" class="cursorpointer">
    <area shape="rect" coords="638,295,701,316" onclick="display(1);" href="javascript:void(0);" alt="Earn" title="Earn" class="cursorpointer">
    <area shape="rect" coords="750,295,815,316" onclick="display(2);" href="javascript:void(0);" alt="Advance" title="Advance" class="cursorpointer">
    <area shape="rect" coords="862,285,925,316" onclick="display(3);" href="javascript:void(0);" alt="Serve" title="Serve" class="cursorpointer">
  </map>
  <div class="rotating-text">
  </div>
</div>

<!-- Run the javascript to rotate the page-->
<script type="text/javascript">
	var divIds=new Array(); 
	divIds[0]="fp-container1"; 
	divIds[1]="fp-container2"; 
	divIds[2]="fp-container3"; 
	divIds[3]="fp-container4";
	
	display(0);
</script>
<!--break-->