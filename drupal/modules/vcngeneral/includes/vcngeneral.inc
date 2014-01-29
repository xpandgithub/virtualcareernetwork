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
 * Utlility function used to set the URL for the tabs
*
* param string $tab_value
* The parameter value associated with each tab on Occupation Details page
*
* param string $delimiter
* The delimiter value for URL, default is '/'
*
* return string reconstructed URL
*
*/
function vcn_occupation_details_set_tab_url($tab_value = 'overview') {

	$current_path_array = explode("/", $_SERVER['REQUEST_URI']);
	$find_tab_key = array_search('tab', $current_path_array);
	if ($find_tab_key) {
		$current_path_array[$find_tab_key + 1] = $tab_value;
	} else {
		$current_path_array[] = 'tab';
		$current_path_array[] = $tab_value;
	}
	return implode("/", $current_path_array);
}


/*
 * Utility function to get the tab value from URL
*
* @param string $format
* if you want data in JSON format pass $format = 'json', default will be NULL
*
* @return string
* The value of the tab that has been selected, default value is 'overview'
*
* Search for the word 'tab' in the URL, if it exists return the value immediately after the "/" following the tab
* if 'tab' does not exist, return the value 'overview' (meaning the user is on the overview page)
*
* Eg: http://vcn.org/content/medical-assistant/tab/skills-and-tools/zip/20191, return "skills-and-tools
* 		http://vcn.org/content/medical-assistant, return "overview"
*/
function  vcn_occupation_details_get_tab_url($format = NULL) {

	$current_path_array = explode("/", $_SERVER['REQUEST_URI']);
	$find_tab_key = array_search('tab', $current_path_array);
	if ($find_tab_key) {
		$selected_tab_value = $current_path_array[$find_tab_key + 1];
	} else {
		$selected_tab_value = 'overview';
	}

	if ($format == 'json') {
		drupal_json_encode($selected_tab_value);
	} else {
		return $selected_tab_value;
	}
}


/*
 * Utlity function to convert array into html list
*
* param array $data_array
* the array that needs to be converted into HTML list
*
* param string $delimiter
* the delimiter used in the string, default value is ','
*
* return string $html_list
* the HTML list
*/
function vcn_generate_html_list($data_array, $limit = 10, $showMore = false) {

	$count_items = count($data_array);
	if ($count_items < $limit) {
		$limit = $count_items;
    $showMore = false;
	}

	$html_list = '<ul>';

	for($i = 0; $i < $limit; $i++) {
		$html_list .= '<li>';
		$html_list .= $data_array[$i];
		$html_list .= '</li>';
	}

	$html_list .= '</ul>';
  
  if ($showMore) {
    
    $html_list .= '<div style="display:none;"><ul>';

    for($i = $limit; $i < $count_items; $i++) {
      $html_list .= '<li>';
      $html_list .= $data_array[$i];
      $html_list .= '</li>';
    }

    $html_list .= '</ul></div>';
    
    $html_list .= '<a class="morelink" href="javascript:void(0);" title="More Details">More Details</a>';
  }
  
	return $html_list;
}

/**
 * utility function to return annual salary from the data returned by the REST call
 *
 * @param array $wageOcc
 * @return array
 */
function vcn_get_annual_salary($wageOcc) {
	if ($wageOcc && $wageOcc->item) {
		foreach ($wageOcc->item as $item) {
			if ((int)$item->ratetype[0] == 4) {
				return array(
				  'pct10' => $item->pct10[0] == '999999' ? NULL : (float)$item->pct10[0],
				  'pct25' => $item->pct25[0] == '999999' ? NULL : (float)$item->pct25[0],
				  'median' => $item->median[0] == '999999' ? NULL : (float)$item->median[0],
					'pct75' => $item->pct75[0] == '999999' ? NULL : (float)$item->pct75[0],
				  'pct90' => $item->pct90[0] == '999999' ? NULL : (float)$item->pct90[0],
				);
			}
		}
	}
}


/**
 * utility function to return hourly wages from the data returned by the REST call
 *
 * @param array $wageOcc
 * @return array
 */
function vcn_get_hourly_wages($wageOcc) {
	if ($wageOcc && $wageOcc->item) {
		foreach ($wageOcc->item as $item) {
			if ((int)$item->ratetype[0] == 1) {
				return array(
				  'pct10' => $item->pct10[0] == '9999.99' ? NULL : (float)$item->pct10[0],
				  'pct25' => $item->pct25[0] == '9999.99' ? NULL : (float)$item->pct25[0],
				  'median' => $item->median[0] == '9999.99' ? NULL : (float)$item->median[0],
					'pct75' => $item->pct75[0] == '9999.99' ? NULL : (float)$item->pct75[0],
				  'pct90' => $item->pct90[0] == '9999.99' ? NULL : (float)$item->pct90[0],
				);
			}
		}
	}
}


/**
 * utility function that returns other names for the occupation from the data returned by the REST call
 *
 * @param array $other_names
 * @return array
 */
function vcn_get_other_names_for_occupation($other_names, $regular_text_num_words = 15) {
	$other_names_regular_text_array = array();
	$other_names_hidden_text_array = array();
	$i = 0;
	foreach ($other_names as $laytitle) {
		if ($i < $regular_text_num_words) {
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


/**
 * utility function to return the top 3 highest values of education of the people already working in the specific career
 * Assuming that the data is already returned from the REST in a descending sort order of percentage(visual observation of data for 31-9091.00, 31-9092.00, 31-9093.00) and query is order by datavalue DESC
 * in VCNOccupationEducation.php in the REST folder
 * @param array $all_training_values
 * @return array
 */
function vcn_get_education_training_values($all_training_values) {
	if (isset($all_training_values)) {
		for($i = 0; $i < count($all_training_values); $i++) {
			if ($all_training_values[$i]) {
				$education_training_array[$i] = array('data_value' => (float)$all_training_values[$i]->datavalue, 'education' => (string)$all_training_values[$i]->educationcategoryname);
			}
		}
		return $education_training_array;
	}
}


/**
 *
 * utility function to return the Resources Name and Resource link categorized by the Category Name
 * eg: returned array will look like
 * array(
 * 	'Federal Resources' => array(array('resourcename' => 'Fed1', 'resourcelink' => 'Fedlink1'),
 * 															array('resourcename' => 'Fed2', 'resourcelink' => 'Fedlink2')
 * 													),
 * 	'Partner Site' => array(array('resourcename' => 'Partner 1', 'resourcelink' => 'Partnerlink1'))
 * )
 *
 * @param object $resources
 * @return array $resources_categorized_array
 */
function vcn_get_occupation_resources($resources) {
	$resources_categorized_array = array();
	foreach ($resources->item as $resource) {
		$resources_categorized_array[(string)$resource->categoryname[0]][] = array(
				'resourcename' => (string)$resource->resourcename[0],
				'resourcelink' => (string)$resource->resourcelink[0],
        'resourcelinkflag' => (string)$resource->resourcelinkflag[0],
		);
	}
	ksort($resources_categorized_array);
	return $resources_categorized_array;
}


/**
 * if the name of certification is too long, it breaks the graph on Education and Training tab, this function is a workaround to shorten the name
 * copied over from vcnchooseacareer.module file, it is called getTrainingD
 * need to delete it from vcnchooseacareer.module and use this function going forward
 * 
 * Typical training
 * coming from rest will normally look like this: Associates Degree (HS + 1-4 years).  This function keeps
 * just the "Associates Degree" part.
 *
 * @param string $training
 * @return string
 */
function vcn_get_shortened_training_name($training) {

	$pieces = explode(" - ", $training);
	$training = $pieces[0];

	$pieces = explode(" (", $training);
	$training = $pieces[0];

	$pieces = explode("(", $training);
	$training = $pieces[0];

	return $training;

}

/**
 * The purpose of this function is to pull a list of similar careers from REST and populate them in an array.
 *
 * @param string $onetcode
 * @param number $numberReturned [optional]
 * @return array
 */
function vcn_get_similar_careers($onetcode, $zipcode=null, $limit=3) {

	$similarCareersArr = false;

	$params = array(
    'industry' => $GLOBALS['vcn_config_default_industry_id'], 
    'onetcode' => $onetcode, 
    'zipcode' => $zipcode, 
    'limit' => $limit,
  );

	$content = vcn_rest_wrapper('vcnoccupationsvc', 'vcncareer', 'get-similar-careers', $params);

	if ($content) {
		$similarCareersArr = array();

    $careers = $content->career;
    
    foreach ($careers as $career) {
      $wageDisplayed = '';
      if (isset($career->wagepct25) && isset($career->wagepct75)) {
        $wagePct25 = intval($career->wagepct25);
        $wagePct25Formatted = "$" . number_format($wagePct25, 0, '.', ',');

        $wagePct75 = intval($career->wagepct75);
        $wagePct75Formatted = "$" . number_format($wagePct75, 0, '.', ',');

        $wageDisplayed = $wagePct25Formatted . ' - ' . $wagePct75Formatted;
      }
      
      $similarCareersArr[] = array(
        'input_onetcode' => $onetcode,
        'similar_onetcode' => (string)$career->onetcode,
        'similar_display_title' => (string)$career->title,
        'similar_typical_training_title' => (string)$career->typicaledutext,
        'similar_wage_displayed' => $wageDisplayed,
      );
    }
	}

	return $similarCareersArr;

}



function vcn_get_financialaid_resources($financialaid_resources) {
	$financialaid_resources_array = array();
	foreach ($financialaid_resources->item as $financialaid_resource) {
		$financialaid_resources_array[] = array(
			'financialaidname' => (string)$financialaid_resource->financialaidname[0],
			'financialaidurl'	=> 	(string)$financialaid_resource->financialaidurl[0],
      'financialaidurlflag'	=> 	(string)$financialaid_resource->financialaidurlflag[0],
		);
	}
	
	return $financialaid_resources_array;
}


/* These are temporary functions, to be used till users have the ability to input HTML tags like <p>, <br/>, <b> in their data (hopefully)*/

/**
 * Convert line breaks and <p> tags into a temporary special character to be used in vcn_text_into_paragraphs function
 *
 * @param string $string
 * @return string
 */
/*function vcn_custom_nl2br($string) {
	$string = str_replace(array("\r\n\r\n", "\r\r", "\n\n"), "~", $string);
	//$string = str_ireplace(array("<p>", "</p>", "<p></p>"), "", $string);
	//$string = str_replace("Credentials Needed:", "<b>Credentials Needed:</b>", $string);
	//$string = str_replace("Some Key Things to Remember:", "<b>Some Key Things to Remember:</b>", $string);
	return $string;
}*/


/**
 * used to separate the long text in separate paragraphs based on "~" character, used to create 2 different variables based, the first holding $num_paragraphs_to_show number of paragraphs to show,
 * while the seconf variable holding the remaining paragraphs which are hidden by default. This is used in Show More Details / Less Details functionality.
 * Invoked from node--occupation.tpl.php
 *
 * @param string $string
 * @param int $num_paragraphs_to_show
 * @return array
 */

function vcn_text_into_paragraphs($string, $num_paragraphs_to_show = 1) {

	$array_of_paragraphs = explode("</p>", $string);

	$regular_text_array = array();
	$hidden_text_array = array();
	$i = 0;

	foreach ($array_of_paragraphs as $value) {
		$value = trim($value);
		if($value != '<p>' && strlen($value)) {
      if ($i < $num_paragraphs_to_show) {
        $regular_text_array[] = $value . '</p>';
      } else {
        $hidden_text_array[] = $value . '</p>';
      }
      $i++;
		}		
	}
	
	return array(
			'regular_text_array' => $regular_text_array,
			'hidden_text_array' => $hidden_text_array,
	);

}

/* End of temporary functions*/


function vcn_get_interview_data($carer_interview_data_array) {
	$main_interview = array();
	$additional_interview = array();
	foreach ($carer_interview_data_array as $career_interview) {
		if ($career_interview->interviewtype == 'main') {
			$main_interview[] = array('interviewurldescription' => $career_interview->interviewurldescription, 'interviewurl' => $career_interview->interviewurl, 'interviewurlflag' => $career_interview->interviewurlflag);
		} else if ($career_interview->interviewtype == 'additional') {
			$additional_interview[] = array('interviewurldescription' => $career_interview->interviewurldescription, 'interviewurl' => $career_interview->interviewurl, 'interviewurlflag' => $career_interview->interviewurlflag);
		}
	}
	
	return array ('main_interview' => $main_interview, 'additional_interview' => $additional_interview);
}


function vcn_autocomplete_callback () {	
	$data = vcn_rest_wrapper('vcnoccupationsvc', 'vcnlaytitle', 'laytitleautosuggestresults', array('search_term' => $_GET['term'], 'industry_id' => $GLOBALS['vcn_config_default_industry_id']), 'json', 'post', false);
	echo drupal_json_output($data);	
}

function vcn_zipcode_validation_ajax_callback() {
	/* REST Call */
	
	$params = array('zipcode' => ($_GET['zipcode']));
	$zipcode_result = vcn_rest_wrapper('vcnlocationsvc', 'vcnlocation', 'get-location', $params);
	
	if(!empty($zipcode_result->location)) {
		print true;
	} else {
		print false;
	}
	
	/* End REST Call */
}

// Save/Target CMA Notebook Items
// %: 3 task = save/target, 4 item_type = career/program/certification/license, 
// 	  5 Itemid(career-onet/program/certification/license ID), 6 sub_item_id(onetcode), 7 secondary_info = cipcode/state_fip
function vcn_save_target_notebook_item_ajax_callback($task, $item_type, $item_id, $sub_item_id = 0, $secondary_info = 0) {
	
	$industry = vcn_get_industry();
	$user = vcn_get_user_info();
	$vcnUserid = $user['vcn_user_id'];
	
	if ($item_type == "" || $task == "" || $item_id == "") {
		echo  "Missing " . ucfirst($item_type) . " Info, nothing Updated"; exit;
	}
	
	$params = array('userid' => $vcnUserid, 
					'industry' => $industry, 
					'itemtype' => $item_type, 
					'task' => $task, 
					'itemid' => $item_id, 
					'subitemid' => $sub_item_id, 
					'secondaryinfo' => $secondary_info);
	//var_dump($params); //exit;
	$result = vcn_rest_wrapper('vcncmasvc', 'vcnnotebook', 'save-target-notebook-item', $params, 'xml', 'post', false);
	//var_dump($result); //($result->item[0] == 1) Success.
	echo (string)$result->action;
	
	if ($item_type == 'career' && ($task == 'target' || $task == 'save')) {
		$update_session_career = FALSE;
		switch ($task) {
			case 'target':
				$update_session_career = TRUE;
				break;
			case 'save':
				$cma_careers = vcn_rest_wrapper('vcncmasvc', 'vcnnotebook', 'get-notebook-career-items', array('userid' => $vcnUserid, 'industry' => $industry), 'json', 'post', false);
				if (count($cma_careers) == 1) {
					$update_session_career = TRUE;
				}
				break;
			default:
				$update_session_career = FALSE;
				break;
		}
		
		if ($update_session_career) {
			vcnuser_set_onetcode_session($sub_item_id);
			/*$max_education_array = vcn_calculate_max_edu_for_career($sub_item_id, FALSE);
			if (!empty($max_education_array)) {
				vcnuser_set_awlevel_cookie($max_education_array['max_education_id']);
			}*/
			$all_education_id = vcn_get_all_education_id();
			if (!empty($all_education_id)) {
				vcnuser_set_awlevel_cookie($all_education_id);
			}
		}
	}
	
	exit;
	
}

function vcn_get_cma_saved_careers_ajax_callback($vcnuserid) {
	/* REST Call */	
	$industry = vcn_get_industry();
	
	$params = array('userid' => $vcnuserid, 'industry' => $industry);
	$careers = vcn_rest_wrapper('vcncmasvc', 'vcnnotebook', 'get-notebook-career-items', $params, 'xml', 'post', false);
	
  $targeted_onet_title = '';
	$targeted_onet = 0;
	$career_list_array = array();
	$count = 0;
	foreach($careers->item as $key => $i) {	

		if($i->onetcode == "" ) {
			continue;
		}
		
		$career_list_array[$count] = (string)$i->onetcode;
		$count++;
		
		if($i->itemrank == 1) {
			$targeted_onet = (string)$i->onetcode;	
			$targeted_onet_title = (string)$i->displaytitle;
		}
	}
	$career_list = array("career_count" => $count, "career_list" => $career_list_array, "targeted_onet" => $targeted_onet, "targeted_onet_title" => $targeted_onet_title);	
	$career_list = json_encode($career_list);
	echo $career_list;	
	
	/* End REST Call */
}

function vcn_get_cma_targeted_career_ajax_callback($vcnuserid) {
  /* REST Call */
  $industry = vcn_get_industry();
	
  $params = array('userid' => $vcnuserid, 'targeted' => 1, 'industry' => $industry);
  $career = vcn_rest_wrapper('vcncmasvc', 'vcnnotebook', 'get-notebook-career-items', $params, 'xml', 'post', false);

	$targeted_career_array = array(
  	"onetcode" => (string) $career->item->onetcode, 
		"displaytitle" => (string) $career->item->displaytitle,
		"title" => (string) $career->item->title,
  );
  
	//header('Content-Type: application/json');
	$targeted_career = json_encode($targeted_career_array);
	echo $targeted_career;	

	/* End REST Call */
}

function vcn_set_cma_counselor_user_id_ajax_callback($vcnuserid) {
  $_SESSION['vcn_counselor_student_id'] = $vcnuserid;
  echo $vcnuserid;
}
