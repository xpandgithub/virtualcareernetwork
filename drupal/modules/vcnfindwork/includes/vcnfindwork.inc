<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php

function vcn_findwork_tabs(&$variables) {		
	$vcn_drupal7_base_path = vcn_drupal7_base_path();

	$vcn_tabs_list = array();
	$vcn_tabs_list[] = array("overview", "Overview", "findajob_overview.jpg", $vcn_drupal7_base_path."findwork");
	$vcn_tabs_list[] = array("findwork-results", "Job Search", "findajob_job_search.jpg", $vcn_drupal7_base_path."findwork-results");
	$variables['vcn_tabs_list'] = $vcn_tabs_list;

	$vcn_tabs_keys = array("overview", "findwork-results");
	$variables['vcn_tabs_keys'] = $vcn_tabs_keys;

	return true;
}

function vcn_find_job_nav_bar_html(&$variables) {
	$drupal_basepath = vcn_drupal7_base_path();
	$vcn_tabs_list = $variables['vcn_tabs_list'];
	$vcn_tabs_keys = $variables['vcn_tabs_keys'];
	$selected_tab_key_index = $variables['selected_tab_key_index'];

	$nav_bar_html = '<!-- Navigation bar -->
      <div class="vcn-navigation-bar allclear">
      	<div class="bar-left"><div>&nbsp;</div></div>
      	<div class="bar-middle"><div>'.vcn_tab_navigation_bar(count($vcn_tabs_keys), $selected_tab_key_index+1).'</div></div>
      	<div class="bar-right">
      		<div>';	
		
	if(($selected_tab_key_index) < (count($vcn_tabs_keys)-1)) {
		$nav_bar_html .= '<button title="Next" onclick="location.href=\''.$vcn_tabs_list[$selected_tab_key_index+1][3].'\';" class="vcn-button vcn-next">Next</button>';
	}
	 
	$nav_bar_html .= '
	      	</div>
      	</div>
      	<div class="allclear"></div>
      </div>
    <!-- End of Navigation bar -->';
	
	$user_nav_bar_html = '<!-- VCN Navigation bar -->
					      <div class="vcn-user-navigation-bar allclear">
					      	<div class="nav-bar-left"><div><a title="Back to Get Qualified" href="'.$drupal_basepath.'get-qualified" >Back to Get Qualified</a></div></div>
					      	<div class="nav-bar-right"><div>&nbsp;</div></div>
					      	<div class="allclear"></div>
					      </div>
					    <!-- End of VCN Navigation bar -->	';

	$variables['nav_bar_html'] = $nav_bar_html;
	$variables['user_nav_bar_html'] = $user_nav_bar_html;

	return true;
}

/*================ Find work Overview Tab ======================= */

function vcn_findwork() { // Callback fucntion
	//return drupal_get_form('vcn_findwork_form');
	return theme('vcn_findwork_overview');
}

/*================ Find work results ======================= */

// Get jobs count by onetcode and zipcode ajax callback
function vcn_job_count_by_onet_and_zip_ajax_callback($zipcode, $onetcode, $career_title) {
	$params = array('zipcode' => $zipcode, 'onetcode' => $onetcode, 'career_title' => urldecode($career_title), 'distance' => $GLOBALS['default_distance']);
	$data = vcn_findwork_results_get_data_by_onetcode($params, FALSE);
	$count = count($data);	
	echo $count > 0 ? $count : "NA" ; 	
}

// Callback fucntions
function vcn_findwork_results() {
	$additional_parameters = array('search_term' => '');
	return drupal_get_form('vcn_findwork_results_form', $additional_parameters);
}

function vcn_findwork_results_search($search_term) {
	$search_term = urldecode($search_term);
	$additional_parameters = array('search_term' => $search_term);
	return drupal_get_form('vcn_findwork_results_form', $additional_parameters);
}

function vcn_findwork_results_search_with_zipcode($search_term, $zipcode, $distance) {
	$search_term = urldecode($search_term);
	$additional_parameters = array('search_term' => $search_term, 'zipcode' => $zipcode, 'distance' => $distance);
	return drupal_get_form('vcn_findwork_results_form', $additional_parameters);
}

function vcn_findwork_results_career($onetcode) {
	$additional_parameters = array('onetcode' => $onetcode);
	return drupal_get_form('vcn_findwork_results_form', $additional_parameters);
}

function vcn_findwork_results_career_with_zipcode($onetcode, $zipcode, $distance) {
	$additional_parameters = array('onetcode' => $onetcode, 'zipcode' => $zipcode, 'distance' => $distance);
	return drupal_get_form('vcn_findwork_results_form', $additional_parameters);
}


function vcn_findwork_results_form($form, &$form_state, $additional_parameters) {
	//http://api.drupal.org/api/drupal/includes!form.inc/function/drupal_get_form/7
	$search_term = '';
	if (isset($additional_parameters['search_term'])) {
		$search_term = $additional_parameters['search_term'];
		$search_term = str_replace('~', '/', $search_term);
		$search_term = str_replace('*', '\\', $search_term);
	}

	if (isset($form_state['input']['zipcode'])) {
		$zipcode = $form_state['input']['zipcode'];
	} elseif (isset($additional_parameters['zipcode'])) {
		$zipcode = $additional_parameters['zipcode'];
	} else {
		$zipcode = vcnuser_get_zip();
	}
	
	$onetcode = '';
	if (isset($form_state['input']['careers'])) {
		$onetcode = $form_state['input']['careers'];
	} elseif (isset($additional_parameters['onetcode'])) {
		$onetcode = $additional_parameters['onetcode'];
	} else if($search_term == ''){
			$onetcode = vcnuser_get_onetcode();		 
	}
	 
	$form = array();

	$form['zipcode'] = array (
			'#type' => 'textfield',
			'#title' => t('ZIP Code: '),
			'#maxlength' => 5,
			'#size' => 6,
			'#value' => $zipcode,
	);

	$form['distance'] = array (
			'#type'=>'select',
			'#title' => t('Distance: '),
			'#options' => array ('5'=>'5 miles', '15'=>'15 miles', '25'=>'25 miles','50'=>'50 miles', '100'=>'100 miles', '250'=>'250 miles','500'=>'500 miles'),
			'#multiple' => false,
			'#default_value' => vcnuser_get_distance()//isset($additional_parameters['distance']) ? $additional_parameters['distance'] : (string)$GLOBALS['default_distance'],
	);

	$form['careers'] = array (
			'#type'=>'select',
			'#title' => t('Careers: '),
			'#options' => vcn_get_career_names_select_list(),
			'#multiple' => false,
			'#default_value' => $onetcode,
	);

	$form['search_by_job_title'] = array (
			'#type' => 'textfield',
			'#title' => t('Job Title: '),			
			'#value' => $search_term,
			'#size' => 50,
	);

	$form['find_jobs_submit'] = array (
			'#type' => 'submit',
			'#name' => 'find_jobs_submit',
			'#value' => t('Search'),
			'#attributes' => array (
					'class' => array('vcn-button', 'vcnfindwork-submit-button'),
					'title' => "Search"
			)
	);

	$form['valid_zipcode'] = array (
			'#type' => 'hidden',
			'#value' => (isset($additional_parameters['zipcode']) ?
					$additional_parameters['zipcode'] : (isset($_COOKIE['d6_d7_zipcode_share']) ? $_COOKIE['d6_d7_zipcode_share'] : NULL))
	);

	//$form['#theme'] = 'vcnfindworkresults_form';

	return $form;

}

/**
 * Returns the data from api.us.jobs when users select a particular onetcode from the select list
 * @param array $params
 * @return array data from API US Jobs for an occupation
 */
function vcn_findwork_results_get_data_by_onetcode($params, $print_jurl=false, $jobpagemax=500) {

	$laytitles_array = vcn_rest_wrapper('vcnoccupationsvc', 'vcnlaytitle', 'getactivelaytitlesforoccupation',
			array('onetcode' => $params['onetcode'], 'industry' => vcn_get_industry()), 'json', 'post', false);
	$common_keywords = vcn_rest_wrapper('vcnoccupationsvc', 'vcncommonkeywords', 'getcommonkeywords', NULL, 'json', 'post', false);

	/*this code is used to merge the original occupation title with the laytitles in such a way that the original title always comes as the first in the search criteria
	 *we have to use stdClass because the $laytitles_array returned by the REST call is an array of objects
	* Try: var_dump($laytitles_array);
	* */
	$occupation_obj = new stdClass();
	$occupation_obj->laytitle = vcn_findwork_clean_up_title($params['career_title']);
	$occupation_obj->onetcode = $params['onetcode'];
	$occupation_obj_array = array($occupation_obj);
	/* ==== end === */

	$combined_array = array_merge($occupation_obj_array, $laytitles_array);

	$find_job_query_string = '';
	$i = 0;
	foreach ($combined_array as $value) {
		$laytitle_query_string = vcn_prepend_the_word_Title($value->laytitle, $common_keywords);
		if ($i == 0) {
			$find_job_query_string .= $laytitle_query_string;
		} else {
			$find_job_query_string .= ' or '.$laytitle_query_string;
		}
		$i++;
	}

	if ($params['zipcode']) {
		$find_job_query_string .= "&zc1=".$params['zipcode']."&rd1=".$params['distance'];
	}

	return vcn_get_data_from_apiusjobs($find_job_query_string, $print_jurl, $jobpagemax);
}


/**
 * Returns the data from api.us.jobs when users enter the job title (or laytitle) in the textbox
 * @param array $params
 * @return array data from API US Jobs for an occupation
 */
function vcn_findwork_results_get_data_freetext_search($params, $print_jurl=false, $jobpagemax=500) {

	$search_term = $params['search_term'];
	$search_term = str_replace('~', '/', $search_term);
	$search_term = str_replace('*', '\\', $search_term);

	$common_keywords = vcn_rest_wrapper('vcnoccupationsvc', 'vcncommonkeywords', 'getcommonkeywords', NULL, 'json', 'post', false);

	$find_job_query_string = vcn_prepend_the_word_Title($search_term, $common_keywords);
	if ($params['zipcode']) {
		$find_job_query_string .= "&zc1=".$params['zipcode']."&rd1=".$params['distance'];
	}

	return vcn_get_data_from_apiusjobs($find_job_query_string, $print_jurl, $jobpagemax);
}


function vcn_findwork_clean_up_title($text) {
	$text = strtolower($text);
	$text = str_replace('&', ' ', $text);
	$text = str_replace(',', ' ', $text);
	$text = str_replace('/', ' ', $text);
	$text = str_replace('\\', ' ', $text);
	$text = str_replace('(', ' ', $text);
	$text = str_replace(')', ' ', $text);
	$text = str_replace("'", "", $text);
	$text = str_replace(' or ', ' ', $text);
	$text = str_replace(' and ', ' ', $text);
	$text = str_replace(' not ', ' ', $text);
	$text = str_replace(' of ', ' ', $text);
	$text = str_replace(' the ', ' ', $text);
	$text = str_replace(' in ', ' ', $text);
	$text = str_replace(' for ', ' ', $text);
	$text = str_replace(' with ', ' ', $text);

	return $text;
}

function vcn_prepend_the_word_Title($string, $common_keywords) {
	$string = vcn_findwork_clean_up_title($string);
	$string_split = explode(' ', $string); //explode the title into individual words

	foreach ($string_split as $key => $value) {
		if (strlen($value) <= 1) { //if the laytitle is "Class B Driver", we have to omit the letter "B" from search query because api.us.jobs does not like single characters in query and returns 0 records
			$string_split[$key] = null;
		} else {
			if (in_array(strtolower($value), $common_keywords)) {
				$string_split[$key] = 'title:' . strtolower($value);
			} else {
				$string_split[$key] = strtolower($value); //anyways converting everything to lower case. need to check if this is actually required
			}
		}
		$query_string = implode(' ', $string_split); //reconstruct the query string from the array
	}

	return $query_string;
}


function vcn_get_data_from_apiusjobs($query_string, $print_jurl = FALSE, $jobpagemax=500) {

	$jobpagemin = 1; //copied over from D6, occupations_findwork.tpl.php

	$joburl = $GLOBALS['vcn_config_dea_web_service_url'];
	$joburl .= '?kw='.$query_string;
	$joburl .= "&rs=".$jobpagemin."&re=".$jobpagemax."&cn=100&tm=60&key=".$GLOBALS['vcn_config_dea_web_service_key'];

	global $user;

	$debug_jurl = isset($_GET['debug']) ? $_GET['debug'] : '';
	if ($print_jurl && ($debug_jurl == '1') && array_search("administrator", $user->roles) == true) {
		echo "jurl: $joburl";
	}

	$objDOM = new DOMDocument();
	$objDOM->load($joburl);

	$data = array();

	$note = $objDOM->getElementsByTagName("job");
	foreach( $note as $value ) {
		$titles = $value->getElementsByTagName("title");
		$title  = $titles->item(0)->nodeValue;

		$companies = $value->getElementsByTagName("company");
		$company  = $companies->item(0)->nodeValue;

		$locations = $value->getElementsByTagName("location");
		$location  = $locations->item(0)->nodeValue;

		$datesacquired = $value->getElementsByTagName("dateacquired");
		$dateacquired  = $datesacquired->item(0)->nodeValue;
		if ($dateacquired) {
			$date_time_split = explode(' ', $dateacquired); // separate the time from the date, because if time starts with leading 0 as in 00:48, strtotime breaks and returns false
			$dateacquired = date('n/j/Y', strtotime($date_time_split[0]));
		} else {
			$dateacquired = '00/00/0000';
		}

		$urls = $value->getElementsByTagName("url");
		$url  = $urls->item(0)->nodeValue;

		$data[] = array(
				'job_title' => $title,
				'job_url' => $url,
				'company' => $company,
				'location' => $location,
				'date_acquired' => $dateacquired
		);
	}

	return $data;

}

function vcn_findwork_results_feedback($params) {
	// Ref table vcn_comment to figure out the parameters we need to construct here

	global $user_info;
	
	$params['vcn_user_id'] = (isset($user_info['vcn_user_id']) && $user_info['vcn_user_id'] > 0) ? $user_info['vcn_user_id'] : null;
	$params['vcn_user_drupal_email'] = ($user_info['is_user_logged_in'] === true) ? ((isset($user_info['mail']) && $user_info['mail'] != "") ? $user_info['mail'] : 'no-email') : 'anonymous-user';

	if (isset($params['search_term'])) {
		$data = vcn_findwork_results_get_data_freetext_search($params, FALSE);
		$subject = "Search term:".$params['search_term'];
		$query_url = 'search-term/'.$params['search_term'];
	} elseif (isset($params['onetcode'])) {
		$data = vcn_findwork_results_get_data_by_onetcode($params, FALSE);
		$subject = "Career Title:".$params['career_title']." Onetcode:".$params['onetcode'];
		$query_url = 'career/'.$params['onetcode'];
	} else {
		$data = array();
		$subject = "";
		$query_url = "";
	}

	if (isset($params['zipcode'])) {
		$subject .= " Zipcode:".$params['zipcode']." Distance:".$params['distance'];
		$query_url .= '/zip/'.$params['zipcode'].'/distance/'.$params['distance'];
	}
	$params['subject'] = $subject; //subject parameter

	$data_count = count($data);
	if ($data_count > 0) {
		$i = 1;
		$data_string = "COMPANY,JOB TITLE,JOB URL,LOCATION,DATE POSTED\r\n";
		foreach($data as $value) {
			$data_string .= '"'.$value['company'].'",';
			$data_string .= '"'.$value['job_title'].'",';
			$data_string .= '"'.$value['job_url'].'",';
			$data_string .= '"'.$value['location'].'",';
			$data_string .= '"'.$value['date_acquired'].'"';
			if ($i < $data_count) {
				$data_string .= "\r\n";
			}
			$i++;
		}
	} else {
		$data_string = "No data returned for the user's search criteria";
	}
	$params['job_search_data'] = $data_string; // job search results converted into a string that can be easily copied over and coverted into CSV file

	//page_url parameter
	$feedback_page_url = $GLOBALS['base_root'].vcn_drupal7_base_path().'findwork-results/'.$query_url;
	$params['page_url'] = $feedback_page_url;


	/* other parameters */
	$params['sender_comment'] = "Comment for Find a Job Results page"; //try to come up with a default comment, since we are not allowing users to type anything yet


	$call_result = vcn_rest_wrapper('vcncommentsvc', 'vcnjobsearchfeedback', 'insert-job-search-feedback', $params, 'json', 'post', false);
	echo json_encode($call_result);
}

/* the reason we have separate call back functions is because we have separate menus depending on whether the user the searching jobs through text or drop down list
 * if we do not have separate menus, we have to pass null values through URL to make it complete so hook_menu does not give page not found
*/

function vcn_findwork_results_feedback_searchterm($search_term) {
	vcn_findwork_results_feedback(array('search_term' => $search_term));
}

function vcn_findwork_results_feedback_searchterm_with_zipcode($search_term, $zipcode, $distance = NULL) {
	if (is_null($distance)) {
		$distance = $GLOBALS['default_distance'];
	}
	vcn_findwork_results_feedback(array('search_term' => $search_term, 'zipcode' => $zipcode, 'distance' => $distance));
}

function vcn_findwork_results_feedback_career($onetcode, $career_title) {
	vcn_findwork_results_feedback(array('career_title' => $career_title, 'onetcode' => $onetcode));
}

function vcn_findwork_results_feedback_career_with_zipcode($onetcode, $career_title, $zipcode, $distance = NULL) {
	if (is_null($distance)) {
		$distance = $GLOBALS['default_distance'];
	}
	vcn_findwork_results_feedback(array('career_title' => $career_title, 'onetcode' => $onetcode, 'zipcode' => $zipcode, 'distance' => $distance));
}

function vcn_findworkresults_form_submit_by_career(&$form, &$form_state) {

	$zipcode = trim($form_state['input']['zipcode']);
	$zipcode = empty($zipcode) ? NULL : $zipcode;
	vcnuser_set_zip_cookie($zipcode); //recreating the cookie value

	$career = $form_state['input']['careers'];
	$redirect_url = 'findwork-results/career/'.$career;
	if ($zipcode) {
		$distance = $form_state['input']['distance'];
		$redirect_url .= '/zip/'.$zipcode.'/distance/'.$distance;
	}

	$form_state['redirect'] = $redirect_url;
}

function vcn_findworkresults_form_submit_by_job_title(&$form, &$form_state) {

	$zipcode = trim($form_state['input']['zipcode']);
	$zipcode = empty($zipcode) ? NULL : $zipcode;
	vcnuser_set_zip_cookie($zipcode);

	$search_term = $form_state['input']['search_by_job_title'];
	$redirect_url = 'findwork-results/search-term/'.urlencode($search_term);
	if ($zipcode) {
		$distance = $form_state['input']['distance'];
		$redirect_url .= '/zip/'.$zipcode.'/distance/'.$distance;
	}

	$form_state['redirect'] = $redirect_url;
}

function vcn_findwork_results_form_validate($form, &$form_state) {
	vcn_findwork_common_validation($form, $form_state);
}

function vcn_findwork_results_form_submit($form, &$form_state) {
	vcn_findwork_results_redirect($form, $form_state);
}

/**
 * Gets the list of all careers (titles and onetcode) for a particular industry
 * @return array
 */
function vcn_get_career_names_select_list () {
	$data = vcn_rest_wrapper('vcnoccupationsvc', 'vcncareer', 'listcareers', array('industry' => vcn_get_industry()), 'json');
	$select_list_array = array('' => 'Select a Career');

	if (count($data) > 0) {
		foreach ($data as $value) {
			$select_list_array[$value->onetcode] = $value->title;
		}
	}
	return $select_list_array;
}

/**
 * Gets the count of careers for a particular industry
 * @return array
 */
function vcn_get_career_count() {	
	$data = vcn_rest_wrapper('vcnoccupationsvc', 'vcncareer', 'listcareers', array('industry' => vcn_get_industry(), 'ignoreworktype' => 'Y'), 'json');
	return count($data);
}

/**
 * Common validation function for input vcnfindwork landing page and vcnfindworkresults page form
 */

function vcn_findwork_common_validation($form, &$form_state) {
	$search_text_val = trim($form_state['input']['search_by_job_title']);
	$zipcode_val = trim($form_state['input']['zipcode']);

	if (!empty($zipcode_val)) {
		$zipcode_data = vcn_rest_wrapper('vcnlocationsvc', 'vcnlocation', 'get-location', array('zipcode' => $zipcode_val), 'json', 'post', false);
		if (empty($zipcode_data->location)) {
			form_set_error('zipcode', t('Please enter a valid US ZIP Code.'));
		}
	}

	if (empty($form['careers']['#value']) && empty($search_text_val)) {
		form_set_error('', t('Please enter a search term OR select a career from the list.'));
		form_set_error('careers');
		form_set_error('search_by_job_title');
	}
}

function vcn_findwork_results_redirect($form, &$form_state) {
	global $base_url;

	$zipcode = trim($form_state['input']['zipcode']);
	$zipcode = empty($zipcode) ? 'cleared' : $zipcode;
	vcnuser_set_zip_cookie($zipcode); //recreating the cookie value

	$career = $form_state['input']['careers'];

	//doing these replacements since '/' is a Drupal URL delimiter
	$search_term = $form_state['input']['search_by_job_title'];
	$search_term = str_replace('/', '~', $search_term);
	$search_term = str_replace('\\', '*', $search_term);

	$cat2site = vcn_determine_vcn_or_category2();
	if ($cat2site == 'goodwill') {
		$base_url = $GLOBALS['vcn_config_http_or_https'] . '://' . $GLOBALS['goodwill_config_base_url'] . substr($GLOBALS['vcn_config_default_base_path_drupal7'], 0, -1);
	} else if ($cat2site == 'ser') {
		$base_url = $GLOBALS['vcn_config_http_or_https'] . '://' . $GLOBALS['ser_config_base_url'] . substr($GLOBALS['vcn_config_default_base_path_drupal7'], 0, -1);
	} else if ($cat2site == 'iajvs') {
		$base_url = $GLOBALS['vcn_config_http_or_https'] . '://' . $GLOBALS['iajvs_config_base_url'] . substr($GLOBALS['vcn_config_default_base_path_drupal7'], 0, -1);
	}


	$redirect_url = 'findwork-results/';

	if(!empty($career) && !empty($search_term)) {
		$redirect_url .= 'search-term/'.$search_term;
	} elseif (!empty($career)) {
		$redirect_url .= 'career/'.$career;
	} else {
		$redirect_url .= 'search-term/'.$search_term;
	}

	if (isset($zipcode) && ($zipcode != 'cleared')) {
		$distance = $form_state['input']['distance'];
		$redirect_url .= '/zip/'.$zipcode.'/distance/'.$distance;
	}

	$form_state['redirect'] = $redirect_url;
}