<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php 
function vcn_cma_include_variables(&$variables) {

	if (empty($variables['industry'])) {
		$variables['industry'] = vcn_get_industry(); //$variables['industry_name'] = vcn_get_industry_name();
	}
	
	$variables['vcn_drupal7_base_path'] = vcn_drupal7_base_path();
	$variables['vcn_image_path'] = vcn_image_path();
	//$variables['vcn_industry_image_path'] = vcn_industry_image_path();

	$user = vcn_get_user_info();
	$variables['drupaluserid'] = $user['uid'];
	$variables['userid'] = $user['vcn_user_id'];
	$variables['is_user_logged_in'] = $user['is_user_logged_in'];
	
	$variables['cmadesc'] = "";
	
	if(isset($variables['career-wishlist']) && $variables['career-wishlist'] == 1) {	//if(!$variables['is_user_logged_in']) {
		$variables['cmatitle'] = "Career Wishlist:";
	}else {
		$variables['cmatitle'] = "MyVCN Account";
	}

	$variables['targeted_career_count'] = $variables['targeted_program_count'] = 0;
	if($variables['userid'] > 0) {
		
		$career_details = (isset($variables['cma-target']) && $variables['cma-target'] == 1) ? TRUE : FALSE;
		$program_details = (isset($variables['cma-target']) && $variables['cma-target'] == 2) ? TRUE : FALSE;
	
		$vcnuser_get_targeted_career = vcnuser_get_notebook_items($variables['userid'], 'OCCUPATION', 1, $career_details);
		$variables['targeted_career_count'] = count($vcnuser_get_targeted_career->item) > 0 ? 1 : 0;
		$variables['vcnuser_get_targeted_career'] = $vcnuser_get_targeted_career->item;
	
		$vcnuser_get_targeted_program = vcnuser_get_notebook_items($variables['userid'], 'PROGRAM', 1, $program_details);
		$variables['targeted_program_count'] = count($vcnuser_get_targeted_program->item) > 0 ? 1 : 0;
		$variables['vcnuser_get_targeted_program'] = $vcnuser_get_targeted_program->item;	
		
		drupal_add_js(array('vcncma' => array('industry' => $variables['industry'], 'userid' => $variables['userid'], 'isUserLoggedIn' => $variables['is_user_logged_in'] )), array('type' => 'setting'));
		drupal_add_js(drupal_get_path('module', 'vcncma') .'/js/vcncma.js', 'file');
	}
	//vcn_cma_target_access_validation($variables);
	vcn_cma_nav_bar($variables);
	
  // get the variables for counselor (if applicable)
  vcn_cma_include_counselor_variables($variables);
  
	return true;
}

function vcn_cma_target_access_validation(&$variables) {	
	if(isset($variables['cma-target']) && $variables['cma-target'] == 1 && $variables['targeted_career_count'] < 1) {
		$access = false;
	} else if(isset($variables['cma-target']) && $variables['cma-target'] == 2 && $variables['targeted_program_count'] < 1) {
		$access = false;
	}else {
		$access = true;
	}

	if(!$access) {
		//drupal_set_message('You are not authorized to access this page. ', 'error');
		//drupal_access_denied();
		//die(); // To stop execution for theme processors.
		
		$url = $GLOBALS['vcn_config_http_or_https'] . '://' . $_SERVER['SERVER_NAME'] . vcn_drupal7_base_path();
		header('Location: ' . $url);
		
		// the die is needed in case somehow user is able to get past the header line (http://thedailywtf.com/Articles/WellIntentioned-Destruction.aspx)
		die();
		
	}
	return true;			
}

function vcn_cma_get_tabs_list(&$variables) {

	$base_path = vcn_drupal7_base_path();
	
	$vcn_tabs_keys = $vcn_tabs_list = array();
	if(isset($variables['career-wishlist']) && $variables['career-wishlist'] == 1) {
		
		$wishlist_user_state = (strpos($_SERVER["REQUEST_URI"], "/wishlist")) ? "/wishlist" : "";
				
		if(isset($variables['is_user_logged_in']) && $variables['is_user_logged_in'] == true) {
			$vcn_tabs_keys[] = "myvcn";
			$vcn_tabs_list[] = array("myvcn", "MyVCN", "", $base_path."cma/career-wishlist");
		}
		
		$vcn_tabs_keys[] = "careers";
		$vcn_tabs_list[] = array("careers", "Careers", "", $base_path."cma/careers".$wishlist_user_state);
		
    $vcn_tabs_keys[] = "programs";
		$vcn_tabs_list[] = array("programs", "Education Programs", "", $base_path."cma/programs".$wishlist_user_state);
    
		$vcn_tabs_keys[] = "certifications";
		$vcn_tabs_list[] = array("certifications", "Job Certifications", "", $base_path."cma/certifications".$wishlist_user_state);
		
    $vcn_tabs_keys[] = "licenses";
		$vcn_tabs_list[] = array("licenses", "Required Licenses", "", $base_path."cma/licenses".$wishlist_user_state);
		
	} else {
   
	  if (!vcn_cma_is_councelor_viewing_student_data()) {
	    $vcn_tabs_keys[] = "profile";
	    $vcn_tabs_list[] = array("profile", "Profile", "", $base_path."cma/profile");
	  }
	
	  $vcn_tabs_keys[] = "careerwishlist";
	  $vcn_tabs_list[] = array("careerwishlist", "<img class=\"vcn-career-wishlist-tab-icon\" alt=\"Career Wishlist\" src=\"".vcn_image_path()."buttons/star.png\"> Career Wishlist", "", $base_path."cma/career-wishlist");
	
	  $vcn_tabs_keys[] = "localjobs";
	  $vcn_tabs_list[] = array("localjobs", "Local Jobs", "", $base_path."cma/local-jobs");
	
	  $vcn_tabs_keys[] = "networkcontacts";
	  $vcn_tabs_list[] = array("networkcontacts", "Network Contacts", "", $base_path."cma/network-contacts");
	    
	  $vcn_tabs_keys[] = "collegecredit";
		$vcn_tabs_list[] = array("collegecredit", "College Credits", "", $base_path."cma/college-credit");
		
		$vcn_tabs_keys[] = "emphistory";
		$vcn_tabs_list[] = array("emphistory", "Employment History", "", $base_path."cma/employment-history");
		
		$vcn_tabs_keys[] = "jobscouts";
	  $vcn_tabs_list[] = array("jobscouts", "Job Scouts", "", $base_path."cma/job-scouts");
	}
	return array('vcn_tabs_list' => $vcn_tabs_list, 'vcn_tabs_keys' => $vcn_tabs_keys);

}

function vcn_cma_nav_bar(&$variables) {	 //Navigation bar for "My VCN" (default), "Career Wishlist" and "Target Career/Program" Pages.

	$targeted_career_count = $variables['targeted_career_count'];
	$targeted_program_count  = $variables['targeted_program_count'];

	//My VCN
	$nav_bar_back_link = (strpos($_SERVER["HTTP_REFERER"], "/add") || strpos($_SERVER["HTTP_REFERER"], "/edit")) ? '<a title="Go Back" href="javascript:history.go(-3);" >Go Back</a>' :'<a title="Go Back" href="javascript:history.go(-1);" >Go Back</a>';
	$nav_bar_buttons = '&nbsp;';

	//Career Wishlist
	vcncma_get_user_state($variables);
	$user_state_career_wishlist_referer = $variables['user_state_career_wishlist_referer'];
	$link_as_per_user_state = $variables['link_as_per_user_state'];
	
	if(isset($variables['career-wishlist']) && $variables['career-wishlist'] == 1) {			
		
		$variables['disable_inactive_tab'] = !(bool) ($link_as_per_user_state);		
		
		$nav_bar_left_btn = $nav_bar_right_btn = "";
		if($link_as_per_user_state == 0){ // Set buttons as per cookie
			
			$variables['cmatitle'] = "Review Saved";
			
			$variables['cmadesc'] = ($variables['selectedTabIndex'] == "careers") ? "Select one of these careers as your Target Career to pursue.<br/><br/>Click on Education and Jobs to preview which education programs and jobs are available.<br/><br/>" : (($variables['selectedTabIndex'] == "programs") ? "Select one of these education programs as your target program to apply to.<br/><br/>" : "");
			
			if($user_state_career_wishlist_referer == "explorecareers") {
				$nav_bar_left_btn = '<button title="Continue Exploring Careers" onclick="location.href=\''.$variables['vcn_drupal7_base_path'].'explorecareers\';" class="vcn-button">Continue Exploring Careers</button>';
				
				if($targeted_career_count > 0) {
					$nav_bar_right_btn = '<button title="Done with Exploring Careers" onclick="location.href=\''.$variables['vcn_drupal7_base_path'].'cma/target-career\';" class="vcn-button vcn-red-button">Done with Exploring Careers</button>';
				}else {
					$nav_bar_right_btn = '<button title="No Career Targeted" class="vcn-button vcn-button-disable">Done with Exploring Careers</button>';
				}
			}else if($user_state_career_wishlist_referer == "get-qualified/programs") {
				$nav_bar_left_btn = '<button title="Continue Exploring Programs" onclick="location.href=\''.$variables['vcn_drupal7_base_path'].$user_state_career_wishlist_referer.'\';" class="vcn-button">Continue Exploring Programs</button>';
				 
				if($targeted_career_count > 0 && $targeted_program_count > 0) {
					$nav_bar_right_btn = '<button title="Done with Get Qualified" onclick="location.href=\''.$variables['vcn_drupal7_base_path'].'cma/target-program\';" class="vcn-button vcn-red-button">Done with Get Qualified</button>';				
				}else {
					$nav_bar_right_btn = '<button title="No Program Targeted" class="vcn-button vcn-button-disable">Done with Get Qualified</button>';
				}
			}
		}else { // Set buttons as per user state (Target items)
			
			/*if($targeted_career_count > 0 && $targeted_program_count > 0) { // Default + if $link_as_per_user_state == 1
				$nav_bar_left_btn = '<button title="Continue Exploring Programs" onclick="location.href=\''.$variables['vcn_drupal7_base_path'].'get-qualified\';" class="vcn-button">Continue Exploring Programs</button>';
				$nav_bar_right_btn = '<button title="Done with Get Qualified" onclick="location.href=\''.$variables['vcn_drupal7_base_path'].'cma/target-program\';" class="vcn-button vcn-red-button">Done with Get Qualified</button>';
			}else if($targeted_career_count > 0) {
				$nav_bar_left_btn = '<button title="Continue Exploring Programs" onclick="location.href=\''.$variables['vcn_drupal7_base_path'].'get-qualified\';" class="vcn-button">Continue Exploring Programs</button>';
				$nav_bar_right_btn = '<button title="Done with Exploring Careers" onclick="location.href=\''.$variables['vcn_drupal7_base_path'].'cma/target-career\';" class="vcn-button vcn-red-button">Done with Exploring Careers</button>';
			}else {
				$nav_bar_left_btn = '<button title="Continue Exploring Careers" onclick="location.href=\''.$variables['vcn_drupal7_base_path'].'explorecareers\';" class="vcn-button">Continue Exploring Careers</button>';
				$nav_bar_right_btn = '<button title="Done with Exploring Careers" class="vcn-button vcn-button-disable">Done with Exploring Careers</button>';
			}*/
			
		}

		$nav_bar_buttons .= $nav_bar_left_btn;
		$nav_bar_buttons .= '&nbsp;&nbsp;';
		$nav_bar_buttons .= $nav_bar_right_btn;

	}

	//Target Career/Program
	if(isset($variables['cma-target']) && $variables['cma-target'] > 0) {			
		
		if(isset($variables['cma-target']) && $variables['cma-target'] > 1) { // Target Program page
			//$nav_bar_back_link = '<a title="Back to Career Wishlist" href="'.$variables['vcn_drupal7_base_path'].'cma/programs" >Back to Career Wishlist</a>';		
			$nav_bar_back_link = '<a title="Back to Review Saved Programs" href="javascript:history.go(-1);" >Back to Review Saved Programs</a>';
			
			$nav_bar_buttons .= '<button title="Continue to Find a Job" onclick="location.href=\''.$variables['vcn_drupal7_base_path'].'findwork\';" class="vcn-button vcn-red-button floatright">Continue to Find a Job</button>';			
		}else { // Target Career page
			//$nav_bar_back_link = '<a title="Back to Career Wishlist" href="'.$variables['vcn_drupal7_base_path'].'cma/careers" >Back to Career Wishlist</a>';
			$nav_bar_back_link = '<a title="Back to Review Saved Careers" href="javascript:history.go(-1);" >Back to Review Saved Careers</a>';
			
     		$nav_bar_buttons .= '<button title="Continue to Get Qualified" onclick="location.href=\''.$variables['vcn_drupal7_base_path'].'get-qualified\';" class="vcn-button vcn-red-button floatright">Continue to Get Qualified</button>';			
		}
		
		$nav_bar_buttons .= '<div class="floatright">&nbsp;&nbsp;</div>';

		if($variables['is_user_logged_in'] == TRUE) { // Go to my vcn is a link to fix d6 bounceback issue. floatright + div is too fix IE pie.htc issue with a link text and vcn-button css.
			$nav_bar_buttons .= '<a title="Go to MyVCN" href="'.$variables['vcn_drupal7_base_path'].'cma/career-wishlist/my-vcn"><div class="vcn-button vcn-alink-button floatright">Go to MyVCN</div></a>';
		}else {
			$nav_bar_buttons .= '<a title="Go to MyVCN" href="'.$variables['vcn_drupal7_base_path'].'user"><div class="vcn-button vcn-alink-button floatright">Go to MyVCN</div></a>';
		}			
		
	}

	//Build HTML for Navigation bar
	$user_nav_bar_html = '<!-- VCN Navigation bar -->
					      <div class="vcn-user-navigation-bar allclear">
					      	<div class="nav-bar-left"><div>'.$nav_bar_back_link.'</div></div>
					      	<div class="nav-bar-right"><div>'.$nav_bar_buttons.'</div></div>
					      	<div class="allclear"></div>
					      </div>
					    <!-- End of VCN Navigation bar -->	';

	$variables['nav_bar_html'] = '';
	$variables['user_nav_bar_html'] = $user_nav_bar_html;

	return true;
}

function vcncma_get_user_state(&$variables){

	if(strpos($_SERVER["REQUEST_URI"], "careers") && !strpos($_SERVER["REQUEST_URI"], "/wishlist")) {
		vcn_set_cookie("user_state_career_wishlist_referer", "explorecareers");
		$user_state_career_wishlist_referer	= "explorecareers";
		$link_as_per_user_state = 0;
	}else if(strpos($_SERVER["REQUEST_URI"], "programs") && !strpos($_SERVER["REQUEST_URI"], "/wishlist")) {
		vcn_set_cookie("user_state_career_wishlist_referer", "get-qualified/programs");
		$user_state_career_wishlist_referer	= "get-qualified/programs";
		$link_as_per_user_state = 0;
	}else if(strpos($_SERVER["REQUEST_URI"], "certifications") && !strpos($_SERVER["REQUEST_URI"], "/wishlist")) {
		vcn_set_cookie("user_state_career_wishlist_referer", "get-qualified/certifications");
		$user_state_career_wishlist_referer	= "get-qualified/certifications";
		$link_as_per_user_state = 0;
	}else if(strpos($_SERVER["REQUEST_URI"], "licenses") && !strpos($_SERVER["REQUEST_URI"], "/wishlist")) {
		vcn_set_cookie("user_state_career_wishlist_referer", "get-qualified/licenses");
		$user_state_career_wishlist_referer	= "get-qualified/licenses";
		$link_as_per_user_state = 0;
	}else if(strpos($_SERVER["REQUEST_URI"], "/wishlist")) {
		vcn_set_cookie("user_state_career_wishlist_referer", "wishlist");
		$user_state_career_wishlist_referer	= "wishlist";
		$link_as_per_user_state = 1;
	}else if(isset($_COOKIE['user_state_career_wishlist_referer']) && $_COOKIE['user_state_career_wishlist_referer'] =="wishlist") { // User state
		$user_state_career_wishlist_referer	= "wishlist";
		$link_as_per_user_state = 1;
	}else if(isset($_COOKIE['user_state_career_wishlist_referer']) && in_array($_COOKIE['user_state_career_wishlist_referer'], array("explorecareers", "get-qualified/programs", "get-qualified/certifications", "get-qualified/licenses"))){
		$user_state_career_wishlist_referer	= $_COOKIE['user_state_career_wishlist_referer'];
		$link_as_per_user_state = 0;
	}else {
		$user_state_career_wishlist_referer	= "wishlist";
		$link_as_per_user_state = 1;
	}

	$variables['user_state_career_wishlist_referer'] = $user_state_career_wishlist_referer;
	$variables['link_as_per_user_state'] = $link_as_per_user_state;

	return true;
}

// callback functions // return theme('vcn_cma_careers_template', array('param' => $param));
function vcn_cma_profile() {	
	vcn_validate_logged_in_user();
	return drupal_get_form('vcn_cma_profile_form');
}
function vcn_cma_career_wishlist() {
	vcn_validate_logged_in_user();
	return theme('vcn_cma_career_wishlist_template');
}
function vcn_cma_careers() { 
	return theme('vcn_cma_careers_template');	
}
function vcn_cma_programs() {
	return theme('vcn_cma_programs_template');
}
function vcn_cma_certifications() {
	return theme('vcn_cma_certifications_template');
}
function vcn_cma_licenses() {
	return theme('vcn_cma_licenses_template');
}
function vcn_cma_college_credit() {
	vcn_validate_logged_in_user();
	return theme('vcn_cma_college_credit_template');
}
function vcn_cma_employment_history() {
	vcn_validate_logged_in_user();
	return theme('vcn_cma_employment_history_template');
}
function vcn_cma_employment_history_add() {
	vcn_validate_logged_in_user();
	return drupal_get_form('vcn_cma_employment_history_form', "add", 0);	
}
function vcn_cma_employment_history_update($user_employment_id) {
	vcn_validate_logged_in_user();
	return drupal_get_form('vcn_cma_employment_history_form', "update", $user_employment_id);
}
function vcn_cma_job_scouts() {
	vcn_validate_logged_in_user();
	return theme('vcn_cma_job_scouts_template');
}
function vcn_cma_local_jobs() {
	vcn_validate_logged_in_user();
	return theme('vcn_cma_local_jobs_template');
}
function vcn_cma_local_job_update($local_job_id) {
	vcn_validate_logged_in_user();
	return drupal_get_form('vcn_cma_local_jobs_form', 'update', $local_job_id);
}
function vcn_cma_local_job_add() {
	vcn_validate_logged_in_user();
	return drupal_get_form('vcn_cma_local_jobs_form', 'add', 0);
}
function vcn_cma_network_contacts() {
	vcn_validate_logged_in_user();
	return theme('vcn_cma_network_contacts_template');
}
function vcn_cma_network_contact_add() {
	vcn_validate_logged_in_user();
	return drupal_get_form('vcn_cma_network_contacts_form', 'add', 0);
}
function vcn_cma_network_contact_update($user_contact_id) {
	vcn_validate_logged_in_user();
	return drupal_get_form('vcn_cma_network_contacts_form', 'update', $user_contact_id);
}
function vcn_cma_local_jobs_detail($local_job_id) {
	vcn_validate_logged_in_user();
	return theme('vcn_cma_local_jobs_detail', array('local_job_id' => $local_job_id));
}

function vcn_cma_network_contacts_detail($user_contact_id) {
	vcn_validate_logged_in_user();
	return theme('vcn_cma_network_contacts_detail', array('user_contact_id' => $user_contact_id));
}
function vcn_cma_target_career() {
	return theme('vcn_cma_target_career_template');
}
function vcn_cma_target_program() {
	return theme('vcn_cma_target_program_template');
}
function vcn_cma_delete_from_job_scouts($job_scout_id) {
	
	$user_info = vcn_get_user_info();
	$user_id = $user_info['vcn_user_id'];
	
	if (!empty($user_id)) {
		$params['userid'] = $user_id;
	}
	
	$params['jobscoutid'] =  $job_scout_id;
	$call_result = vcn_rest_wrapper('vcncmasvc', 'vcncmajobscouts', 'delete-from-jobscouts', $params, 'xml', 'post', false);
	echo (string) $call_result->item;
}

function vcn_cma_job_scouts_update_subscription($job_scout_id) {
	
	$user_info = vcn_get_user_info();
	$user_id = $user_info['vcn_user_id'];
	
	if (!empty($user_id)) {
		$params['userid'] = $user_id;
	}
	
	$params['jobscoutid'] =  $job_scout_id;
	$call_result = vcn_rest_wrapper('vcncmasvc', 'vcncmajobscouts', 'update-subscription-jobscouts', $params, 'xml', 'post', false);
	echo (string) $call_result->item;

}

function vcn_cma_save_job_search($zip, $distance, $onetcode, $keyword) {
	
	$user_info = vcn_get_user_info();
	$user_id = $user_info['vcn_user_id'];
	
	if (!empty($user_id)) {
		$params['userid'] = $user_id;
	}
	
	if ($zip != 'null') {
		$params['zip'] = $zip;
		if ($distance != 'null') {
			$params['distance'] = $distance;
		} else {
			$params['distance'] = null;
		}
	} else {
		$params['zip'] = null;
	}
	
	if ($onetcode != 'null') {
		$params['onetcode'] = $onetcode;
	} else {
		$params['onetcode'] = null;
	}
	
	if ($keyword != 'null') {
		$keyword = str_replace("*", "\\", $keyword); //backslash is replaced by * in vcnfindworkresults.js file, so changing the * back to backslash
		$keyword = str_replace("~", "/", $keyword); //forward is replaced by ~ in vcnfindworkresults.js file, so changing the ~ back to forwardslash
		$params['keyword'] = $keyword;
	} else {
		$params['keyword'] = null;
	}
	
	$industry = vcn_get_industry();
	$params['industry'] = $industry;
	
	$call_result = vcn_rest_wrapper('vcncmasvc', 'vcncmajobscouts', 'save-jobsearch', $params, 'json', 'post', false);
	echo drupal_json_encode($call_result);
	
}

function vcn_cma_delete_local_job($local_job_id) {
	$user_info = vcn_get_user_info();
	$user_id = $user_info['vcn_user_id'];
	
	if (!empty($user_id)) {
		$params['userid'] = $user_id;
	}
	
	$params['localjobid'] =  $local_job_id;
	$call_result = vcn_rest_wrapper('vcncmasvc', 'vcncmalocaljobs', 'delete-from-cma-local-jobs', $params, 'xml', 'post', false);
	echo (string) $call_result->item;
}

function vcn_cma_delete_network_contact($user_contact_id) {
	$user_info = vcn_get_user_info();
	$user_id = $user_info['vcn_user_id'];

	if (!empty($user_id)) {
		$params['userid'] = $user_id;
	}

	$params['usercontactid'] =  $user_contact_id;
	$call_result = vcn_rest_wrapper('vcncmasvc', 'vcncmanetworkcontacts', 'delete-from-cma-network-contacts', $params, 'xml', 'post', false);
	echo (string) $call_result->item;
}

function vcn_cma_get_counselor_users_dropdown() {
  
  $html = '';
  
  if (vcn_counselor_users_dropdown_should_display()) {
    vcn_cma_is_councelor_viewing_student_data();
    
    $user_info = vcn_get_user_info();
    $user_id = $user_info['vcn_user_id'];
    
    $params = array('counselor_id' => $user_id);
    $counselor_users = vcn_rest_wrapper('vcncmasvc', 'vcncmacounselor', 'get-users-by-counselorid', $params, 'json', 'post', false);
    
    $html = '<label id="vcn-cma-counselor-users-dropdown-label" for="vcn-cma-counselor-users-dropdown"><strong>Select a name to view their MyVCN Account:</strong></label>' .
            '<select id="vcn-cma-counselor-users-dropdown">' .
            '<option value="' . $user_id . '">My Account</option>';
    
    foreach($counselor_users->users as $user) {
      $name = '';
      if (strlen($user->firstname)) {
        $name .= vcn_custom_aes_decrypt($user->firstname) . ' ';
      }
      if (strlen($user->lastname)) {
        $name .= vcn_custom_aes_decrypt($user->lastname);
      }
      if (!strlen(trim($name))) {
      	
      	$drupalquery = db_select('users', 'u');
      	$result = $drupalquery
      	->fields('u', array('name', 'mail'))
      	->condition('uid', (int)$user->user_session_id)
      	->execute()
      	->fetchAssoc();
      	
      	$username = $result["name"]; // student username from drupal db
      	$email = $result["mail"]; // student email from drupal db
      	
        $name .= $username;
      }
      
      // check the session to see which userid is set and make  
      // sure the drop down has that user selected by default
      $selected = '';
      if (isset($_SESSION['vcn_counselor_student_id']) && (string)$user->userid === (string)$_SESSION['vcn_counselor_student_id']) {
        $selected = 'selected="selected"';
      }
      
      $html .= '<option value="' . $user->userid . '" ' . $selected . '>' . $name . '</option>';
    }
    
    $html .= '</select>';
  }
  
  return $html;
}

function vcn_cma_is_councelor_viewing_student_data() {
  $is_viewing = true;
  
  $user_info = vcn_get_user_info();
  $user_id = $user_info['vcn_user_id'];
  
  if (strpos($_SERVER["REQUEST_URI"], "/my-vcn") && $user_info['is_user_logged_in']) { // Do not set Session variable for anonymous user. Only if user is logged in, can be a counselor.
    $_SESSION['vcn_counselor_student_id'] = $user_id;
  }    

  // make sure the user isn't a counselor trying to look at their user's CMA
  if (!isset($_SESSION['vcn_counselor_student_id']) || (isset($_SESSION['vcn_counselor_student_id']) && (string)$_SESSION['vcn_counselor_student_id'] === (string)$user_id)) {
    $is_viewing = false;
  }

  return $is_viewing;
}

function vcn_cma_include_counselor_variables(&$variables) {
  $variables['counselor_users_dropdown'] = vcn_cma_get_counselor_users_dropdown();
  
  // get the student selected name/username (if the selected value is not the counselor)
  if (vcn_cma_is_councelor_viewing_student_data()) {
    $params = array('userid' => $_SESSION['vcn_counselor_student_id']);
    $info = vcn_rest_wrapper('vcncmasvc', 'vcncmauser', 'get-user-info-by-cma-userid', $params, 'json', 'post', false);
    
    $firstName = vcn_custom_aes_decrypt($info->first_name); // student firstname from hvcp db
    $lastName = vcn_custom_aes_decrypt($info->last_name);  // student lastname from hvcp db
    
   	$drupalquery = db_select('users', 'u');
	$result = $drupalquery
				->fields('u', array('name', 'mail'))
				->condition('uid', (int)$info->user_session_id)
				->execute()
				->fetchAssoc();	
	 
    $username = $result["name"]; // student username from drupal db
    $email = $result["mail"]; // student email from drupal db
    
    $variables['counselor_student_name'] = $firstName . " " . $lastName;
    $variables['counselor_student_username'] = $username;
    $variables['counselor_student_email'] = $email;
    
    $variables['counselor_student_display_name'] = strlen(trim($variables['counselor_student_name'])) ? $variables['counselor_student_name'] : $username;
  }
}