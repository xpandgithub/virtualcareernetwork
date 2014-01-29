<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php 
/*
 * VCN custom functions for dynamic header links.
 * Change vcn_drupal6_base_path() to vcn_drupal7_base_path() for 3rd parameter of $headerLeftLinks[] to move link from D6 to D7
 * Add all list of possible url alias as a 5th element in array of $headerLeftLinks.
 * 
 */

function vcn_header_template_preprocess(&$variables) {
	
	$variables['vcn_industry_image_path'] = vcn_industry_image_path();
	$variables['industry_name'] = vcn_get_industry_name();
  
	$variables['left_link_string'] = vcn_header_left_links();
	$variables['right_link_string'] = vcn_header_left_links("main_right");
	
	$variables['headerlinksfront'] = "vcn-header-links-front";
	if($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] == '/index.php') {
		$variables['headerlinksfront'] = "vcn-header-links-front-dark"; 
	}

	// prepare search box
	$searchblock = module_invoke('search', 'block_view');
	$searchbox = $searchblock['content'];
	$variables['searchbox'] = str_replace('<div class="container-inline">','<div class="container-inline" style="height:20px;">',$searchbox);
	
	// determine if this is a category 2 client or standard vcn or vcn subsite
	
	$logoclass = 'header-logo';
	$cat2site = vcn_determine_vcn_or_category2();
	$subsite = vcn_determine_vcn_subsite();
	
	if ($cat2site == 'goodwill') {
		$logoclass = 'goodwill-header-logo';
	} else if ($cat2site == 'ser') {
		$logoclass = 'ser-header-logo';
	} else if ($cat2site == 'iajvs') {
		$logoclass = 'iajvs-header-logo';
	}
	
	$variables['cat2site'] = $cat2site;
	$variables['subsite'] = $subsite;
	$variables['logoclass'] = $logoclass;
	
	// get the VCN's app properties
	$vcnAppProperties = new vcnAppProperties();
	$variables['current_drupal_version'] = $vcnAppProperties->getCurrentDrupalVersion();
	$variables['current_moodle_version'] = $vcnAppProperties->getCurrentMoodleVersion();
	
	$variables['is_logged_in_user'] = $GLOBALS['is_user_logged_in'];
	$variables['is_logged_in_user_text'] = ($variables['is_logged_in_user']) ? 'true' : 'false';
	$variables['vcn_notebook_item_count'] = $GLOBALS['vcn_notebook_item_count'];
	$variables['has_notebook_items'] = ($GLOBALS['vcn_notebook_item_count'] > 0) ? true : false;
	$variables['has_notebook_items_text'] = ($variables['has_notebook_items']) ? 'true' : 'false';
	
	$variables['is_provider_user'] = ($GLOBALS['user_provider_id'] > 0) ? true : false;
	$variables['is_provider_user_text'] = ($variables['is_provider_user']) ? 'true' : 'false';
	$variables['vcn_user_id'] = $GLOBALS['vcn_user_id'];
	$variables['d6_user_id'] = $GLOBALS['d6_uid'];
	$variables['d6_user_name'] = $GLOBALS['d6_name'];
	$variables['vcn_notebook_item_count'] = $GLOBALS['vcn_notebook_item_count'];
	$variables['logged_in_user_default_zipcode'] = $GLOBALS['logged_in_user_default_zipcode'];
	$variables['vcn_config_php_server_name'] = $GLOBALS['vcn_config_php_server_name'];
	
	// Header right( first half) links
	// Only Resourses link is there. So no need to do dynamic like left part
	// Variable to set resources tab active.
	$variables['liTagResoursesClass'] = "leaf first";
	
	$resources = vcn_drupal7_base_path()."resources";
	if($_SERVER['REQUEST_URI'] == $resources) {
		$variables['liTagResoursesClass'] = "leaf first active-trail";
	}
	
	return true;
}


function vcn_header_left_links_main() {
  $headerLeftLinks = array(); // Header left( first half) links

  $vcn_drupal7_base_path = vcn_drupal7_base_path();
  
  // Add in order to display.
  // $headerLeftLinks[] = array("Title to display","Relative link","Base path if required(d6)","<a> tag title");
  
  $headerLeftLinks[0] = array('<img src="' . vcn_image_path() . 'miscellaneous/vcn_home_white.png" style="height:17px; width:auto;" alt="Top level">', "/index.php", "", "Main Site");
  $headerLeftLinks[1] = array("Home", "", vcn_drupal7_base_path(), vcn_get_industry_name()." Home");
  $headerLeftLinks[2] = array("Get Started", "get-started", vcn_drupal7_base_path(), "Understand how VCN helps you prepare for a better job.");  
  $headerLeftLinks[3] = array("Choose a Career", "explorecareers", vcn_drupal7_base_path(), "Choose a high-growth career after reviewing details such as expected salary, typical educational qualifications and other requirements.");
  $headerLeftLinks[4] = array("Get Qualified", "get-qualified", vcn_drupal7_base_path(), "Review education, training, certifications and licenses required to enter each high growth career.");
  $headerLeftLinks[5] = array("Find a Job", "findwork", vcn_drupal7_base_path(), "Search for local or national jobs in your chosen career.");

  //Add 5th element in array of $headerLeftLinks with all list of possible url alias for specific header link.
  $headerLeftLinks[0][] = array();
  $headerLeftLinks[1][] = array();
  $headerLeftLinks[2][] = array("/get-started");
  $headerLeftLinks[3][] = array("explorecareers", "careergrid", $vcn_drupal7_base_path."careers/", "top10byjobs", "top10bygrowth", "top10bypay", "careerladder");
  $headerLeftLinks[4][] = array("get-qualified", "sat-information", "actinformation", "compass-description", "accuplacer");
  $headerLeftLinks[5][] = array("findwork");
  
  return $headerLeftLinks;
}

function vcn_header_left_links_main_right() {
	$headerLeftLinks = array(); // Header left( first half) links

	// Add in order to display.
	// $headerLeftLinks[] = array("Title to display","Relative link","Base path if required(d6)","<a> tag title");

	$headerLeftLinks[0] = array("College Credits", "pla/getting-started", vcn_drupal7_base_path(), "Get free college credits for previous work experience and military or professional training."); 
	$headerLeftLinks[1] = array("Career Tools", "career-tools", vcn_drupal7_base_path(), "Links to various online career preparation and education tools plus a directory of support organizations that can assist you in your career development.");
	$headerLeftLinks[2] = array("FAQs", "help", vcn_drupal7_base_path(), "Frequently asked questions");

	//Add 5th element in array of $headerLeftLinks with all list of possible url alias for specific header link.
	$headerLeftLinks[0][] =array("/pla/");
	$headerLeftLinks[1][] =array("career-tools", "online-courses", "office-locator", "educationmatch", "interest-profiler", "jobseekers");
	$headerLeftLinks[2][] =array("help");

	return $headerLeftLinks;
	
}


function vcn_header_left_links_provider_front() {
  $headerLeftLinks = array(); // Header left( first half) links

  // Add in order to display.
  // $headerLeftLinks[] = array("Title to display","Relative link","Base path if required(d6)","Extra for <a> tag");
  $headerLeftLinks[0] = array("Home", "provider/summary", vcn_drupal7_base_path(), "Provider Portal Home");

  //Add 5th element in array of $headerLeftLinks with all list of possible url alias for specific header link.
  $headerLeftLinks[0][] = array("provider/summary");
  
  return $headerLeftLinks;
}

function vcn_header_left_links_provider_main() {
  $headerLeftLinks = array(); // Header left( first half) links

  $unitIdPath = '';
  $unitIdPath1 = '';
  $unitIdPath2 = '';
  if (isset($_SESSION['provider_selected_unit_id'])) {
    $unitId = $_SESSION['provider_selected_unit_id'];
    $unitIdPath1 = '/unitid/' . $unitId;
    $unitIdPath2 = '/' . $unitId;
  }
  
  // Add in order to display.
  // $headerLeftLinks[] = array("Title to display","Relative link","Base path if required(d6)","<a> tag title");
  $headerLeftLinks[0] = array("Home", "provider/summary" . $unitIdPath1, vcn_drupal7_base_path(), "Provider Portal Home");
  $headerLeftLinks[1] = array("Profile", "provider/profile" . $unitIdPath2, vcn_drupal7_base_path(), "Provider Profile");
  $headerLeftLinks[2] = array("Programs", "provider/programs" . $unitIdPath1, vcn_drupal7_base_path(), "Provider Programs");

  //Add 5th element in array of $headerLeftLinks with all list of possible url alias for specific header link.
  $headerLeftLinks[0][] = array("provider/summary");
  $headerLeftLinks[1][] = array("provider/profile");
  $headerLeftLinks[2][] = array("provider/programs", "/program");
  
  return $headerLeftLinks;
}

function vcn_header_left_links_provider_main_right() {
  $headerLeftLinks = array(); // Header left( first half) links

  // Add in order to display.
  // $headerLeftLinks[] = array("Title to display","Relative link","Base path if required(d6)","Extra for <a> tag");
  $headerLeftLinks[0] = array("FAQs", "provider/faq", vcn_drupal7_base_path(), "Frequently Asked Questions");

  //Add 5th element in array of $headerLeftLinks with all list of possible url alias for specific header link.
  $headerLeftLinks[0][] = array("provider/faq");
  
  return $headerLeftLinks;
}

function vcn_header_left_links_osp_main_left() {
	$headerLeftLinks = array(); // Header left( first half) links

	// Add in order to display.
	// $headerLeftLinks[] = array("Title to display","Relative link","Base path if required(d6)","Extra for <a> tag");
	$headerLeftLinks[0] = array("Welcome", "osp/welcome", vcn_drupal7_base_path(), "Welcome");
	$headerLeftLinks[1] = array("Download", "osp/download", vcn_drupal7_base_path(), "Download");
	$headerLeftLinks[2] = array("Contribute", "osp/contribute", vcn_drupal7_base_path(), "Contribute");
	
	//Add 5th element in array of $headerLeftLinks with all list of possible url alias for specific header link.
	$headerLeftLinks[0][] = array("osp/welcome");
	$headerLeftLinks[1][] = array("osp/download");
	$headerLeftLinks[2][] = array("osp/contribute");

	return $headerLeftLinks;
}

// Dynamic generation of header left( first half) links
function vcn_header_left_links($type='main') {
  
  $headerLeftLinks = array();
  
  if (strtolower($type) == 'main') {
    $headerLeftLinks = vcn_header_left_links_main();
  } else if (strtolower($type) == 'main_right') {
    $headerLeftLinks = vcn_header_left_links_main_right();
  } else if (strtolower($type) == 'provider_front') {
    $headerLeftLinks = vcn_header_left_links_provider_front();
  } else if (strtolower($type) == 'provider_main') {
    $headerLeftLinks = vcn_header_left_links_provider_main();
  } else if (strtolower($type) == 'provider_main_right') {
    $headerLeftLinks = vcn_header_left_links_provider_main_right();
  } else if (strtolower($type) == 'osp_main') {
    $headerLeftLinks = vcn_header_left_links_osp_main_left();
  }

  $headerLeftLinksString = "";
  foreach ($headerLeftLinks as $key => $value) {
    $liTagClass = "leaf";

    if ($key == 0) { // if first item of links array
	    $liTagClass .= " first";
    } else if ($key == count($headerLeftLinks)-1) { // if last item of links array
	    $liTagClass .= " last";
    }
		
    if ($value["1"] == "" && $_SERVER['REQUEST_URI'] == $value["2"]) {  // if home page with just base path
	    $liTagClass .= " active-trail";
    } else if ($value["1"] != "" && vcn_check_active_url($value) === true) { // check for active url
	    $liTagClass .= " active-trail";
    }

    $headerLeftLinksString .= '<li class="'.$liTagClass.'"><a href="'.$value["2"].$value["1"].'" title="'.$value["3"].'" >'.$value["0"].'</a></li>';
  }

  return $headerLeftLinksString;
}


function vcn_check_active_url($headerButtonInfo) {
  foreach ($headerButtonInfo[4] as $key => $value) {
	  if (strpos($_SERVER['REQUEST_URI'],$value) !== false) {
	    return true;
	  } 
  }
  return false;
}
?>