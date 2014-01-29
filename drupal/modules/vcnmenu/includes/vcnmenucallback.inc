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
* List of page callback functions, which is invoked when the registered path is requested
*/

// Choose a Career callback function
function vcn_explore_careers() {

	return theme('vcn_explore_careers_template');
}

//  Career pathway by worktype callback function
function vcn_career_ladder_by_worktype($worktype) {

	return theme('vcn_career_ladder_by_worktype_template', array('worktype' => $worktype));
}

//  Career pathway by onetcode callback function
function vcn_career_ladder_by_onetcode($onetcode) {

	return theme('vcn_career_ladder_by_onetcode_template', array('onetcode' => $onetcode));
}

// Get Qualified pages callback functions

function vcn_find_learning() {
    return theme('vcn_find_learning_template', array('testarg' => ''));
}

// Get Qualified pages callback functions end

//Provider portal pages
// School/Provider profile Edit page 
function vcn_providerprofile_edit_by_unitid($unitid) {
	
	return drupal_get_form('vcnprovider_form', $unitid);
	//return drupal_get_form('vcnprovider_form',  array('unitid' => $unitid));
	//return theme('vcn_providerprofile_edit_by_unitid_template', array('unitid' => $unitid));
}

// School/Provider program Add page
function vcn_provider_program_add($unitid) {

	return drupal_get_form('vcnprovider_program_form', $unitid, 0, 0);

}

// School/Provider program Edit page
function vcn_provider_program_edit($unitid, $programid, $cipcode) {

	return drupal_get_form('vcnprovider_program_form', $unitid, $programid, $cipcode);

}

function vcn_provider_summary($unitid) {

	return theme('vcn_provider_summary_template', array('unitid' => $unitid));
}

function vcn_provider_programs($unitid) {

	return theme('vcn_provider_programs_template', array('unitid' => $unitid));
}
//Provider portal pages end

// Help callback function
function vcn_help() {

	return theme('vcn_help_template');
}

// Resources callback function
function vcn_resources() {

	return theme('vcn_resources_template');
}

// Community groups callback function
function vcn_communitygroups() {

	return theme('vcn_communitygroups_template');
}

// Top 10 by xyz callback functions
function vcn_top10_by_jobs() {

	return theme('vcn_top10_by_jobs_template');
}

function vcn_top10_by_growth() {

	return theme('vcn_top10_by_growth_template');
}

function vcn_top10_by_pay() {

	return theme('vcn_top10_by_pay_template');
}
// Top 10 by xyz callback functions end

// career grid callback functions
function vcn_career_grid() {
 	return theme('vcn_career_grid_template');
}

// Online courses callback functions
function vcn_take_online() {

	if(isset($_GET['state'])) {
		return theme('vcn_take_online_bystate_template', array('var' => 'hello'));
	}else {
		return theme('vcn_take_online_template', array('var' => 'hello'));
	}
	
}

function vcn_non_credit_courses() {

	return theme('vcn_non_credit_courses_template');
}

function vcn_non_credit_specialized_courses() {

	return theme('vcn_non_credit_specialized_courses_template');
}

function vcn_hit() {

	return theme('vcn_hit_template');
}

function vcn_online_courses($val1, $val2) {

	return theme('vcn_online_courses_template', array('keyid' => $val1, 'idval' => $val2));
}
// Online courses callback functions end

// Match your education callback functions 

function vcn_education_match() {

	return theme('vcn_education_match_template');
}

// Match your education callback functions end

// Onet Assessment callback functions

function vcn_onet_assessment($val1, $val2) {

	return theme('vcn_onet_assessment_template', array('industry' => $val1, 'limit' => $val2));
}

function vcn_onet_assessment_results() {
	
  return theme('vcn_onet_assessment_results_template');	
}

function vcn_onet_assessment_details($val1) {

	return theme('vcn_onet_assessment_details_template', array('onetcode' => $val1));
}

function vcn_onet_assessment_info() {

	return theme('vcn_onet_assessment_info_template');
}

// Onet Assessment callback functions end

// VCN Rest custom CMS callback functions

function vcn_rest_custom_cms($val1, $val2) {

	return theme('vcn_rest_custom_cms_template', array('industry' => $val1, 'onetcode' => $val2));
}

// VCN Rest custom CMS callback functions end

?>