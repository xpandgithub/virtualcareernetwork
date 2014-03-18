<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php 
function vcn_careerdetails_include_variables(&$variables) {

	module_load_include('inc', 'vcngeneral', '/includes/vcngeneral');
	
	if (empty($variables['industry'])) {
		$variables['industry'] = vcn_get_industry(); //$variables['industry_name'] = vcn_get_industry_name();
	}
	 
	$variables['vcn_drupal7_base_path'] = vcn_drupal7_base_path();
	$variables['vcn_image_base_path'] = vcn_image_path();
	//$variables['vcn_industry_image_path'] = vcn_industry_image_path();

	$user = vcn_get_user_info();	
	$variables['userid'] = $user['vcn_user_id'];
	$variables['is_user_logged_in'] = $user['is_user_logged_in'];
	
	if($variables['zipcode'] == "" || $variables['zipcode'] < 0) {
		$zipcode = vcnuser_get_zip();
		$variables['zipcode'] = $zipcode;
	}	
	
	return true;
}

function vcn_careerdetails_get_tabs_list(&$variables) {

	$zipcode = $variables['zipcode'] > 0 ? "/".$variables['zipcode'] : "";	
	$base_path = vcn_drupal7_base_path();
	
	$vcn_tabs_keys = array("overview", "salary-outlook", "on-the-job", "education-training", "skills-tools");	
	
	$vcn_tabs_list = array();		
	$vcn_tabs_list[] = array("overview", "Overview", "", $base_path."careers/".$variables['onetcode']."/overview".$zipcode);	
	$vcn_tabs_list[] = array("salary-outlook", "Salary and Outlook", "", $base_path."careers/".$variables['onetcode']."/salary-outlook".$zipcode);
	$vcn_tabs_list[] = array("on-the-job", "On the Job", "", $base_path."careers/".$variables['onetcode']."/on-the-job".$zipcode);
	$vcn_tabs_list[] = array("education-training", "Education and Training", "", $base_path."careers/".$variables['onetcode']."/education-training".$zipcode);
	$vcn_tabs_list[] = array("skills-tools", "Skills and Tools", "", $base_path."careers/".$variables['onetcode']."/skills-tools".$zipcode);
	
	if($variables['tabid'] == "resources" || (isset($variables['resources']) && count($variables['resources']) > 0) || (isset($variables['fincancialaid_resources']) && count($variables['fincancialaid_resources']) > 0)) {
		$vcn_tabs_list[] = array("resources", "Resources", "", $base_path."careers/".$variables['onetcode']."/resources".$zipcode);
		$vcn_tabs_keys[] = "resources";
	}
	$variables['vcn_tabs_list'] = $vcn_tabs_list;
	$variables['vcn_tabs_keys'] = $vcn_tabs_keys;
	return array('vcn_tabs_list' => $vcn_tabs_list, 'vcn_tabs_keys' => $vcn_tabs_keys);
}

function vcn_careerdetail_user_state(&$variables) {
	$nothing_found = 0;
	if($variables['tabid'] == "overview" && strpos($_SERVER["HTTP_REFERER"], "explorecareers")) { // Choose a Career
		vcn_set_cookie("user_state_career_detail_referer", "explorecareers");
		$variables['back_to_career_link'] =	$variables['vcn_drupal7_base_path']."explorecareers";
	}else if($variables['tabid'] == "overview" && strpos($_SERVER["HTTP_REFERER"], "careergrid")) { // Career Grid
		vcn_set_cookie("user_state_career_detail_referer", "careergrid");
		$variables['back_to_career_link'] =	$variables['vcn_drupal7_base_path']."careergrid";
	}else if($variables['tabid'] == "overview" && strpos($_SERVER["HTTP_REFERER"], "cma/careers")) { // Career Wishlist
		vcn_set_cookie("user_state_career_detail_referer", "cma/careers");
		$variables['back_to_career_link'] =	$variables['vcn_drupal7_base_path']."cma/careers";
		if(isset($_COOKIE['user_state_career_wishlist_referer']) && $_COOKIE['user_state_career_wishlist_referer'] =="wishlist") {
			$variables['back_to_career_link'] =	$variables['vcn_drupal7_base_path']."cma/careers/wishlist";
		}
	}else if(isset($_COOKIE['user_state_career_detail_referer']) && $_COOKIE['user_state_career_detail_referer'] !="") { // User state
		$variables['back_to_career_link'] =	$variables['vcn_drupal7_base_path'].$_COOKIE['user_state_career_detail_referer'];
		$nothing_found = 1;
	}else {
		$variables['back_to_career_link'] =	$variables['vcn_drupal7_base_path']."explorecareers"; // Nothing found
		$nothing_found = 1;
	}
	
	if (stristr($variables['back_to_career_link'], 'careergrid')) {
		$variables['back_to_career_link_text'] = "Search for More Careers";
	}else if (stristr($variables['back_to_career_link'], 'cma/careers')) {
		$variables['back_to_career_link_text'] = (stristr($variables['back_to_career_link'], 'cma/careers/wishlist')) ? "Back to Career Wishlist" : "Back to Review Saved Careers";
	}else{
		$variables['back_to_career_link_text'] = "Back to Choose a Career";
	}
	
	if($nothing_found < 1) {
		$variables['back_to_career_link'] = "javascript:history.go(-1);";
	}
	
	return true;
}

// Career detail callback functions // return theme('vcn_cma_careers_template', array('param' => $param));
	
// %: 1 onetcode = save/target, 2 tabid = overview/salary-outlook/on-the-job/education-training/skills-tools/resources  [optional], 3 = zipcode [optional]
function vcn_careerdetails($onetcode, $tabid = "overview", $zipcode = "") {
	$tabid = $tabid == "" ? "overview" : $tabid;	
	return theme('vcn_careerdetails_template', array('onetcode' => $onetcode, 'tabid' => $tabid, 'zipcode' => $zipcode));	
}

function vcn_careerdetails_common_bar(&$variables){
	$onetcode = $variables['onetcode'];
	$zipcode = $variables['zipcode'];

	/* REST Call */
	if ($zipcode) {
		$state = vcnStateUtil::getStateAbbrevFromZip($zipcode); // to get the state, use the vcn_state_util class getStateAbbrevFromZip method
		$params = array('onetcode' => $onetcode, 'zipcode' => $zipcode, 'state' => $state, 'industry' => $variables['industry']);
		$zipcode_wages = $variables['zipcode_wages'] = vcn_rest_wrapper('vcnoccupationsvc', 'vcnwages', 'detail', $params, 'xml', 'post');
		$zipcode_jobtrends = $variables['zipcode_jobtrends'] = vcn_rest_wrapper('vcnoccupationsvc', 'vcnjobtrends', 'detail', $params, 'xml', 'post');
	} else {
		$state = NULL;
		$params = array('onetcode' => $onetcode, 'industry' => $variables['industry']);
		$national_wages = $variables['national_wages'] = vcn_rest_wrapper('vcnoccupationsvc', 'vcnwages', 'detail', $params, 'xml', 'post');
		$national_jobtrends = $variables['national_jobtrends'] = vcn_rest_wrapper('vcnoccupationsvc', 'vcnjobtrends', 'detail', array('onetcode' => $onetcode), 'xml', 'post');
	}
	$variables['state'] = $state;
	
	// get the main career details
	$career_data = $variables['career_data'] = vcn_rest_wrapper('vcnoccupationsvc', 'vcncareer', 'get-career-requirements', $params, 'xml', 'post');
	
	$params = array('onetcode' => $onetcode, 'industry' => $GLOBALS['vcn_config_default_industry_id'], 'restriction' => 'front_end'); 
	$laytitles_string = vcn_rest_wrapper('vcnoccupationsvc', 'vcnlaytitle', 'getactivelaytitlesforoccupation', $params, 'json', 'post');
	/* End REST Call */	
	
	// Career title
	$variables['careertitle'] = (string)$career_data->careerdata->title; 
	
	// Other names
	$other_names = vcn_careerdetail_other_names($laytitles_string);
	$variables['other_names_regular_text'] = empty($other_names['other_names_regular_text_array']) ? NULL : implode(", ", $other_names['other_names_regular_text_array']);
	$variables['other_names_hidden_text'] = empty($other_names['other_names_hidden_text_array']) ? NULL : implode(", ", $other_names['other_names_hidden_text_array']);
		
	// Career image and video link variables
	$careerImg = (string)$career_data->careerdata->careerimageyn[0];
	if (isset($careerImg) && strtoupper($careerImg) == 'Y') {
		$variables['image_name'] = 'photo.'.vcn_derive_imagename_from_onetcode($variables['onetcode']).'.jpg';
	} else {  // if career image doesn't exist then put in a stock image
		$variables['image_name'] = 'photo.none.png';
	}	
	$variables['videolink'] = (string)$career_data->careerdata->videolink[0];
	$variables['careerladderyn'] = (string)$career_data->careerdata->careerladderyn;
	
	// variables for Job Trends Sidebar	
	$variables['typical_training'] = (string)$career_data->careerdata->typicaleducation[0];
	
	if ($zipcode) {
		$variables['percent_job_growth_value_for_zipcode'] = isset($zipcode_jobtrends->statedata->item->percentjobchange[0]) ?
		vcn_generic_number_formatter((float)$zipcode_jobtrends->statedata->item->percentjobchange[0], 0, '') : NULL;
			
		$variables['percent_job_growth_value_for_national'] = isset($zipcode_jobtrends->nationaldata->item->percentjobchange[0]) ?
		vcn_generic_number_formatter((float)$zipcode_jobtrends->nationaldata->item->percentjobchange[0], 0, '') : NULL;
			
	} else {
		$variables['percent_job_growth_value_for_national'] = isset($national_jobtrends->nationaldata->item->percentjobchange[0]) ?
		vcn_generic_number_formatter((float)$national_jobtrends->nationaldata->item->percentjobchange[0], 0, '') : NULL;
	}
	
	if (isset($national_jobtrends->nationaldata->item->jobtrends[0])) {
		$variables['national_job_growth_text'] = "% - ".(string)$national_jobtrends->nationaldata->item->jobtrends[0];
	} else {
		$variables['national_job_growth_text'] = "";
	}
	if ($zipcode) {
		if (isset($zipcode_jobtrends->statedata->item->jobtrends[0])) {
			$variables['state_job_growth_text'] = "% - ".(string)$zipcode_jobtrends->statedata->item->jobtrends[0];
		} else {
			$variables['state_job_growth_text'] = "";
		}
	}	
	
	// Sidebar salary ranges for Typical Annual Salary and Typical Hourly Wages
	// get the wage period year so we can display it
	$variables['period_year'] = getWagePeriodYear();
	
	$variables['annual_salary_for_national'] = $annual_salary_for_national = isset($zipcode) ? vcn_get_annual_salary($zipcode_wages->nationaldata) : vcn_get_annual_salary($national_wages->nationaldata);
	$variables['typical_annual_salary_range_national'] = vcn_careerdetail_salary_range_sidebar($annual_salary_for_national['pct25'], $annual_salary_for_national['pct75']);
	
	$variables['hourly_wages_for_national'] = $hourly_wages_for_national = isset($zipcode) ? vcn_get_hourly_wages($zipcode_wages->nationaldata) : vcn_get_hourly_wages($national_wages->nationaldata);
	$variables['typical_hourly_wages_range_national'] = vcn_careerdetail_salary_range_sidebar($hourly_wages_for_national['pct25'], $hourly_wages_for_national['pct75']);
	
	if ($zipcode) {
		$variables['annual_salary_for_zipcode'] = $annual_salary_for_zipcode = vcn_get_annual_salary($zipcode_wages->metrodata);
		$variables['typical_annual_salary_range_zipcode'] = vcn_careerdetail_salary_range_sidebar($annual_salary_for_zipcode['pct25'], $annual_salary_for_zipcode['pct75']);
			
		$variables['hourly_wages_for_zipcode'] = $hourly_wages_for_zipcode = vcn_get_hourly_wages($zipcode_wages->metrodata);
		$variables['typical_hourly_wages_range_zipcode'] =  vcn_careerdetail_salary_range_sidebar($hourly_wages_for_zipcode['pct25'], $hourly_wages_for_zipcode['pct75']);
			
		//state data
		$variables['annual_salary_for_state'] = $annual_salary_for_state = vcn_get_annual_salary($zipcode_wages->statedata);
		$variables['hourly_wages_for_state'] = $hourly_wages_for_state = vcn_get_hourly_wages($zipcode_wages->statedata);
	}	
	
}


/**
 * utility function that returns other names for the occupation from the data returned by the REST call
 *
 * @param array $other_names
 * @return array
 */
function vcn_careerdetail_other_names($other_names, $regular_text_num_words = 95) {
	$other_names_regular_text_array = array();
	$other_names_hidden_text_array = array();	
		
	$i = 0;
	$char_count = 0;
	foreach ($other_names as $laytitle) {
		$char_count += strlen((string)$laytitle->laytitle);
		if ($char_count < $regular_text_num_words) { //if ($i < $regular_text_num_words) {
			$other_names_regular_text_array[] = (string)$laytitle->laytitle;
		} else {
			$other_names_hidden_text_array[] = (string)$laytitle->laytitle;
		}
		$i++;
	}
	
	return array(
			'other_names_regular_text_array' => $other_names_regular_text_array,
			'other_names_hidden_text_array' => $other_names_hidden_text_array,
	);
}

function vcn_careerdetail_salary_range_sidebar($low_value, $high_value) {

	if (isset($low_value) && isset($high_value)) {
		$typical_salary_range = vcn_generic_number_formatter($low_value)." - ".vcn_generic_number_formatter($high_value);
	} else {
		$typical_salary_range = "";
		if (!isset($low_value) && !isset($high_value)) {
			$typical_salary_range = "Not available";
		} else {
			$low_typical_salary_range = isset($low_value) ? vcn_generic_number_formatter($low_value) : "NA";
			$high_typical_salary_range = isset($high_value) ? vcn_generic_number_formatter($high_value) : "NA";
			$typical_salary_range = $low_typical_salary_range." - ".$high_typical_salary_range;
		}
	}

	return $typical_salary_range;

}

function vcn_careerdetails_overview(&$variables) {

	$career_data = $variables['career_data'];
	
	/* Detailed Description */
	$detailed_description = vcn_text_into_paragraphs((string)$career_data->careerdata->detaileddescription[0]);
	
	$detailed_description_regular_text = NULL;
	if (!empty($detailed_description['regular_text_array'])) {
		$detailed_description_regular_text = "";
		foreach ($detailed_description['regular_text_array'] as $value) {
			$detailed_description_regular_text .= $value;
		}
	}
	$variables['detailed_description_regular_text'] = $detailed_description_regular_text;
	
	$detailed_description_hidden_text = NULL;
	if (!empty($detailed_description['hidden_text_array'])) {
		$detailed_description_hidden_text = "";
		foreach ($detailed_description['hidden_text_array'] as $value) {
			$detailed_description_hidden_text .= $value;
		}
	}
	$variables['detailed_description_hidden_text'] = $detailed_description_hidden_text;
		
	/* Physical+Health requirements */
	$physical_health_requirements = vcn_text_into_paragraphs((string)$career_data->careerdata->physicalhealthrequirement[0]);
	
	$physical_health_requirements_regular_text = "<p>No specifc requirement is identified at this time.</p>";
	if (!empty($physical_health_requirements['regular_text_array'])) {
		$physical_health_requirements_regular_text = "";
		foreach ($physical_health_requirements['regular_text_array'] as $value) {
			$physical_health_requirements_regular_text .=  $value;
		}
	}
	$variables['physical_health_requirements_regular_text'] = $physical_health_requirements_regular_text;
	
	$physical_health_requirements_hidden_text = NULL;
	if (!empty($physical_health_requirements['hidden_text_array'])) {
		$physical_health_requirements_hidden_text = "";
		foreach ($physical_health_requirements['hidden_text_array'] as $value) {
			$physical_health_requirements_hidden_text .=  $value;
		}
	}
	$variables['physical_health_requirements_hidden_text'] = $physical_health_requirements_hidden_text;
	
	$variables['physical_requirement_url'] = (string)$career_data->careerdata->physicalrequirementurl[0];
	$variables['physical_requirement_url_flag'] = (string)$career_data->careerdata->physicalrequirementurlflag[0];
	
	if (!isset($variables['physical_requirement_url_flag']) || $variables['physical_requirement_url_flag'] !== '1') {
		$variables['physical_requirement_url'] = '';
	}
	
	
	/* Legal requirements (nationwide)*/	
	$legal_nationwide_requirements = vcn_text_into_paragraphs((string)$career_data->careerdata->nationwidelegalrequirementdesc[0]);
	
	$legal_nationwide_requirements_regular_text = "<p>No specific Legal requirements have been identified.</p>";
	if (!empty($legal_nationwide_requirements['regular_text_array'])) {
		$legal_nationwide_requirements_regular_text = "";
		foreach ($legal_nationwide_requirements['regular_text_array'] as $value) {
			$legal_nationwide_requirements_regular_text .=  $value;
		}
	}
	$variables['legal_nationwide_requirements_regular_text'] = $legal_nationwide_requirements_regular_text;
	
	$legal_nationwide_requirements_hidden_text = NULL;
	if (!empty($legal_nationwide_requirements['hidden_text_array'])) {
		$legal_nationwide_requirements_hidden_text = "";
		foreach ($legal_nationwide_requirements['hidden_text_array'] as $value) {
			$legal_nationwide_requirements_hidden_text .=  $value;
		}
	}
	$variables['legal_nationwide_requirements_hidden_text'] = $legal_nationwide_requirements_hidden_text;
	
	$variables['legal_nationwide_requirement_url'] = (string)$career_data->careerdata->nationwidelegalrequirementurl[0];
	$variables['legal_nationwide_requirement_url_flag'] = (string)$career_data->careerdata->nationwidelegalrequirementurlflag[0];
	
	if (!isset($variables['legal_nationwide_requirement_url_flag']) || $variables['legal_nationwide_requirement_url_flag'] !== '1') {
		$variables['legal_nationwide_requirement_url'] = '';
	}
	
	/* Legal requirements (state specific)*/
	$variables['legal_state_specific_requirements_regular_text'] = 'Please enter your <b>Preferred Location (ZIP Code)</b> to view the legal requirements for this occupation.';
	$variables['legal_state_specific_requirements_hidden_text'] = NULL;
	if (isset($variables['zipcode']) && $variables['zipcode'] > 0) {
		$variables['legal_state_specific_requirements_regular_text'] = "No specific Legal requirements have been identified in your state.";
				
		$legal_state_specific_all_requirements = "";
		
		$legal_state_specific_all_requirements.= (trim ((string)$career_data->careerdata->legalabsoluteprohibitions[0]) != "" ? "<p>".trim ((string)$career_data->careerdata->legalabsoluteprohibitions[0])."</p>" : "");
		$legal_state_specific_all_requirements.= (trim((string)$career_data->careerdata->legalrequirement[0]) != "" ? "<p>".trim ((string)$career_data->careerdata->legalrequirement[0])."</p>" : "");
		$legal_state_specific_all_requirements.= (trim ((string)$career_data->careerdata->legalhealthissues[0]) != "" ? "<p>".trim ((string)$career_data->careerdata->legalhealthissues[0])."</p>" : "");
		$legal_state_specific_all_requirements.= (trim ((string)$career_data->careerdata->legalgenericrequirements[0]) != "" ? "<p>".trim ((string)$career_data->careerdata->legalgenericrequirements[0])."</p>" : "");
				
		if($legal_state_specific_all_requirements != ""){
			$legal_state_specific_all_requirements_list = vcn_text_into_paragraphs((string)$legal_state_specific_all_requirements);		
			
			$legal_state_specific_requirements_regular_text = $variables['legal_state_specific_requirements_regular_text'];
			if (!empty($legal_state_specific_all_requirements_list['regular_text_array'])) {
				$legal_state_specific_requirements_regular_text = "";
				foreach ($legal_state_specific_all_requirements_list['regular_text_array'] as $value) {
					$legal_state_specific_requirements_regular_text .=  $value;
				}
			}
			$variables['legal_state_specific_requirements_regular_text'] = $legal_state_specific_requirements_regular_text;
			
			$legal_state_specific_requirements_hidden_text = $variables['legal_state_specific_requirements_hidden_text'];
			if (!empty($legal_state_specific_all_requirements_list['hidden_text_array'])) {
				$legal_state_specific_requirements_hidden_text = "";
				foreach ($legal_state_specific_all_requirements_list['hidden_text_array'] as $value) {
					$legal_state_specific_requirements_hidden_text .=  $value;
				}
			}
			$variables['legal_state_specific_requirements_hidden_text'] = $legal_state_specific_requirements_hidden_text;
			
		}
		
		$variables['legal_state_specific_requirements_associated_url'] = (string)$career_data->careerdata->legalassociatedurl[0];
		$variables['legal_state_specific_requirements_associated_url_flag'] = (string)$career_data->careerdata->legalassociatedurlflag[0];
			
		if (!isset($variables['legal_state_specific_requirements_associated_url_flag']) || $variables['legal_state_specific_requirements_associated_url_flag'] !== '1') {
			$variables['legal_state_specific_requirements_associated_url'] = '';
		}
		
	}	
	
	/* similar careers */
	$variables['similar_careers'] = vcn_get_similar_careers($variables['onetcode'], $variables['zipcode']);			 
	
}

function vcn_get_career_interview_data_array($carrer_interview_data_array) {
	$main_interview = array();
	$additional_interview = array();
	foreach ($carrer_interview_data_array as $career_interview) {
	  if (isset($career_interview->interviewurlflag) && $career_interview->interviewurlflag == '1' && !empty($career_interview->interviewurl)) {	
		if ($career_interview->interviewtype == 'main') {
			$main_interview[] = array('interviewurldescription' => $career_interview->interviewurldescription, 'interviewurl' => $career_interview->interviewurl, 'interviewurlflag' => $career_interview->interviewurlflag);
		} else if ($career_interview->interviewtype == 'additional') {
			$additional_interview[] = array('interviewurldescription' => $career_interview->interviewurldescription, 'interviewurl' => $career_interview->interviewurl, 'interviewurlflag' => $career_interview->interviewurlflag);
		}
	  }	
	}
	return array ('main_interview' => $main_interview, 'additional_interview' => $additional_interview);
}