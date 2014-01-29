<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php

function vcn_pla_get_tabs_list() {
	
	$base_path = vcn_drupal7_base_path();
	$pla_images_basepath = vcn_image_path()."tab_images/";
	
	$vcn_tabs_list = array();
	$vcn_tabs_list[] = array("start", "Get<br/>Started", "pla_start.png", $base_path."pla/getting-started");
	$vcn_tabs_list[] = array("college_courses", "College<br/>Courses", "pla_cc.png", $base_path."pla/college-courses");
	$vcn_tabs_list[] = array("military_credit", "Military<br/>Training", "pla_mt.png", $base_path."pla/military-credit");
	$vcn_tabs_list[] = array("professional_training", "Professional<br/>Training", "pla_pt.png", $base_path."pla/professional-training");
	$vcn_tabs_list[] = array("national_exams", "National<br/>Exams", "pla_ne.png", $base_path."pla/national-exams");
	$vcn_tabs_list[] = array("e_transcript", "Review<br/>Inventory", "pla_mli.png", $base_path."pla/my-learning-inventory");
	//$vcn_tabs_list[] = array("resources", "PLA<br/>Resources", "pla_resources.png", $base_path."pla/resources");
	
	$vcn_tabs_keys = array("start", "college_courses", "military_credit", "professional_training", "national_exams", "e_transcript");
	
	
	return array('vcn_tabs_list' => $vcn_tabs_list, 'vcn_tabs_keys' => $vcn_tabs_keys);
	
}

function vcn_pla_getting_started() {
	return theme('vcn_pla_getting_started');
}