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
 * Get/Set CMA User id  
 */
function vcn_get_cma_userid_ajax_callback(){
	vcnuser_get_user_cma_info(true);

	global $user_info;	
	echo $user_info['vcn_user_id'];	 
}

/**
 * User cma notebook items list as requested.
 */
function vcnuser_get_notebook_items($vcnuserid, $item_type = "ALL", $targeted = '0', $details = FALSE) { //$item_type = 'OCCUPATION'/'PROGRAM'/'CERTIFICATE'/'LICENSE'/'ALL'
	/* REST Call */
	$industry = vcn_get_industry();

	$params = array('userid' => $vcnuserid, 'industry' => $industry, 'type' => $item_type, 'targeted' => $targeted, 'details' => $details);
	$user_notebook_items = vcn_rest_wrapper('vcncmasvc', 'vcnnotebook', 'get-notebook-items', $params, 'xml', 'post', false);

	return $user_notebook_items;

	/* End REST Call */
}

/** 
 * VCNUser functions to get/set user url/session/cma values for zipcode, education level, aw level, onetcode, distance
 */
function vcnuser_get_zip() {
  $vcnuser = $GLOBALS['vcnuser'];
  return $vcnuser->get_zip();
}

function vcnuser_get_distance() {
  $vcnuser = $GLOBALS['vcnuser'];
  return $vcnuser->get_distance();
}

function vcnuser_get_zip_st() {
  $vcnuser = $GLOBALS['vcnuser'];
  return $vcnuser->get_zip_st();
}

function vcnuser_get_st() {
  $vcnuser = $GLOBALS['vcnuser'];
  return $vcnuser->get_st();
}

function vcnuser_get_onetcode() {
  $vcnuser = $GLOBALS['vcnuser'];
  return $vcnuser->get_onetcode();
}

function vcnuser_get_awlevel() {
  $vcnuser = $GLOBALS['vcnuser'];
  return $vcnuser->get_awlevel();
}

function vcnuser_get_career_name() {
  $career_name = null;
 
  $onetcode = vcnuser_get_onetcode();
  if (!empty($onetcode)) {
    $occlist = vcnuser_get_occupation_list();
    foreach ($occlist->careerdata as $occ) { 
       if ($occ->onetcode==$onetcode) {
		  $career_name = $occ->title;
		  break;
	   }
    }
  }
  return $career_name;
}

function vcnuser_get_edulevel() {
  $edulevel = null;
  
  $awlevel = vcnuser_get_awlevel();
  if (!empty($awlevel)) {
	$edulist = vcnuser_get_education_levels_list();
    foreach ($edulist->categories->item as $lev) { 
      if ($lev->educationcategoryid==$awlevel) {
		$edulevel = $lev->educationcategoryname;
	    break;
	  }
	}
  }
  return $edulevel;
}

function vcnuser_get_occupation_list() {
  $vcnuser = $GLOBALS['vcnuser'];
  return $vcnuser->get_occupation_list();
}

function vcnuser_get_education_levels_list() {
  $vcnuser = $GLOBALS['vcnuser'];
  return $vcnuser->get_education_levels_list();
}

/**
 * Returns the ZIP Code if it exists in the URL
 * @return $zipcode
 */
function vcnuser_get_zipcode_from_url() {
	$current_path_array = explode("/", $_SERVER['REQUEST_URI']);
	$find_zip_key = array_search('zip', $current_path_array);
	if ($find_zip_key) {
		$zipcode_value = $current_path_array[$find_zip_key + 1];
	} else {
		$zipcode_value = NULL;
	}
	return $zipcode_value;
}
  
/**
 * 
 * @return $zipcode
 */
function vcnuser_get_zipcode_from_cookie() {
	if (isset($_COOKIE['d6_d7_zipcode_share'])) {
		$zipcode = $_COOKIE['d6_d7_zipcode_share'];
	} else {
		$zipcode = NULL;
	}
	return $zipcode;
}

/**
 * Create cookie values
 */
function vcnuser_set_zip_cookie($zip) {
	setcookie("d6_d7_zipcode_share", $zip, time()-86400, '/');
	setcookie("d6_d7_zipcode_share", $zip, 0, '/');
}

function vcnuser_set_distance_cookie($distance) {
	setcookie("vcnuser_distance", $distance, time()-86400, '/');
	setcookie("vcnuser_distance", $distance, 0, '/');
}

function vcnuser_set_onetcode_session($onetcode) {
	$industry_code = vcn_get_industry_code();
	if (isset($_SESSION['vcnuser_onetcode_'.$industry_code])) {
		unset($_SESSION['vcnuser_onetcode_'.$industry_code]);
	}
	$_SESSION['vcnuser_onetcode_'.$industry_code] = $onetcode;
}

function vcnuser_set_awlevel_cookie($awlevel) {
	setcookie("vcnuser_awlevel", $awlevel, time()-86400, '/');
	setcookie("vcnuser_awlevel", $awlevel, 0, '/');
}

function vcnuser_unset_onetcode_session() {
  $industry_code = vcn_get_industry_code();
  
  if (isset($_SESSION['vcnuser_onetcode_'.$industry_code])) { unset($_SESSION['vcnuser_onetcode_'.$industry_code]); }
  if (isset($_SESSION['vcn_counselor_student_id'])) { unset($_SESSION['vcn_counselor_student_id']); }
}


/**
 * this function will help us identify if the user really cleared the ZIP code from input field and wanted to see the data across the entire country, so we are setting cookie value to "cleared"
 */
function vcnuser_clear_zip_cookie() {
	setcookie("d6_d7_zipcode_share", null, time()-86400, '/');
	setcookie("d6_d7_zipcode_share", "cleared", 0, '/');
}

function vcnuser_get_edu_level() {
	if (isset($_COOKIE['user_edu_level'])) {
		$user_edu_level = $_COOKIE['user_edu_level'];
	} else {
		$user_edu_level = 0;
	}
	return $user_edu_level;
}

/**
 * Custom error message for Disclaimer field for Provider Registration
 */
function _vcnuser_provider_register_validate($form, &$form_state) {
	if ($form_state['values']['disclaimer'] == 0) {
		form_set_error('disclaimer', t('Disclaimer: field is required.'));
	}
}


/**
 * Submit function to send out an e-mail for the Provider registration
 */
function _vcnuser_provider_registration_submit($form, &$form_state) {
	
	$disclaimer_response = ($form['disclaimer']['#checked'] === TRUE) ? 'Yes' : 'No';
	
	$params = array('prop_id' => 6);
	$content = vcn_rest_wrapper('vcnpropertiessvc', 'vcnproperties', 'get-properties', $params);
	
	$to = ((string)$content->properties->item->value);
	if(empty($to)) {
		$to = 'hvcp_provider_manager@xpandcorp.com';
	}

	$subject = 'Provider Portal Registration';

	$body = 'User First Name: '.$form['first_name']['#value'].'<br/>';
	$body .= 'User Last Name: '.$form['last_name']['#value'].'<br/>';
	$body .= 'Username: '.$form['user_name']['#value'].'<br/>';
	$body .= 'Email Address: '.$form['email']['#value'].'<br/>';
	$body .= 'User Title: '.$form['title']['#value'].'<br/>';
	$body .= 'User Phone: '.$form['phone_number']['#value'].'<br/>';
	$body .= 'Institution Name: '.$form['institution_name']['#value'].'<br/>';
	$body .= 'Institution Website: '.$form['institution_website']['#value'].'<br/>';
	$body .= 'Disclaimer Response: '.$disclaimer_response;
	
	//params is the array passed to hook_mail function
	$params = array(
			'subject' => $subject,
			'body' => $body,
	);

	drupal_mail('vcnuser', 'provider_registration', $to, language_default(), $params);

}

/*
 * Validating ZIP code for new user registration
 * */
function _vcnuser_register_zip_validation($form, &$form_state) {

	$zipcode = trim($form['zipcode']['#value']);
	$zipcode_validation = vcn_zipcode_validation($zipcode);
	
	if ((strlen($zipcode) != 5) || !(is_numeric($zipcode)) || (!$zipcode_validation)) {
		form_set_error('zipcode', t('Please enter a valid US ZIP Code.'));
	}

}