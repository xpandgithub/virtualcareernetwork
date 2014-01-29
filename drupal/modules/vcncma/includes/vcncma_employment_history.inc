<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php 

function vcn_cma_employment_history_form_validate($form, &$form_state) {

	if ($form_state['values']['employer_name'] != "" && !preg_match('/^[a-zA-Z\-\'\s]+$/', $form_state['values']['employer_name'])) {
		form_set_error('employer_name', t("Employer Name can only contain alphabets, space, dash and apostrophe ( -')."));
	}
	
	if ($form_state['values']['zipcode'] != "" && !preg_match('/^[0-9\-\s]+$/', $form_state['values']['zipcode'])) {
		form_set_error('zipcode', t('Please enter a valid ZIP code.'));
	}
	
	if ($form_state['values']['contact_name'] != "" && !preg_match('/^[a-zA-Z\-\'\s]+$/', $form_state['values']['contact_name'])) {
		form_set_error('contact_name', t("Contact Name can only contain alphabets, space, dash and apostrophe ( -')."));
	}	
	
	if (!is_numeric(trim($form_state['input']['contact_phone'])) && trim($form_state['input']['contact_phone']) != "") {
		form_set_error('contact_phone', t('Phone number must contain a valid numeric values only.'));
	}
	
	if($form_state['values']['contact_email'] != "" && !valid_email_address($form_state['values']['contact_email'])) {
		form_set_error('contact_email', t('Please Enter a valid email address.'));
	}
	
	if ($form_state['values']['job_title'] != "" && !preg_match('/^[a-zA-Z\-\'\s]+$/', $form_state['values']['job_title'])) {
		form_set_error('job_title', t("Job title can only contain alphabets, space, dash and apostrophe ( -')."));
	}
	
	if(!valid_date_yyyymmdd($form_state['values']['start_date'])) {
		form_set_error('start_date', t('Please Enter a valid start date.'));
	}
	
	if($form_state['values']['end_date'] != "" && !valid_date_yyyymmdd($form_state['values']['end_date'])) {
		form_set_error('end_date', t('Please Enter a valid end date.'));
	}
		
}

function valid_date_yyyymmdd( $postedDate ) { // ^(\d{4})(\/|-|\.)(\d{2})\2(\d{2})$  // ^(\d{2})(\/|-|\.)(\d{2})\2(\d{4})$
	if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $postedDate, $datebit)) {
		return checkdate($datebit[2] , $datebit[3] , $datebit[1]);
	} else {
		return false;
	}
}

function vcn_cma_employment_history_form_submit($form, &$form_state) {		//$form_state['redirect'] = FALSE;
	$user = vcn_get_user_info();
	$userid = $user['vcn_user_id'];

	$params = array('userid' => $userid, 
					'actiontype' => $form_state['input']['action_type'],
					'useremploymentid' => $form_state['input']['user_employment_id'],
					'employername' => $form_state['input']['employer_name'], 
					'address1' => $form_state['input']['address1'],
					'address2' => $form_state['input']['address2'],
					'city' => $form_state['input']['city'],
					'state' => $form_state['input']['state'],
					'zipcode' => $form_state['input']['zipcode'],
					'country' => $form_state['input']['country'],
					'contactname' => $form_state['input']['contact_name'],
					'contactphone' => $form_state['input']['contact_phone'],
					'contactemail' => $form_state['input']['contact_email'],
					'jobtitle' => $form_state['input']['job_title'],
					'responsibilities' => $form_state['input']['responsibilities'],
					'startdate' => $form_state['input']['start_date'],
					'enddate' => $form_state['input']['end_date'],
					'professionalachievements' => $form_state['input']['professional_achievements']
			  ); 
	$update_info = vcn_rest_wrapper('vcncmasvc', 'vcncmaemployment', 'add-update-employment-history', $params, 'xml', 'post', false);
	$update_info_status = isset($update_info) ? (int) $update_info->item : 0;

	if($update_info_status) {
		drupal_set_message("Info has been ".($form_state['input']['action_type'] == "add" ? "add" : "updat")."ed succesfully.");
		$form_state['redirect'] = "cma/employment-history";
	}else {
		drupal_set_message("There was a problem ".($form_state['input']['action_type'] == "add" ? "add" : "updat")."ing info. Please try again.");
	}
	
	
	
}

function vcn_get_cma_employment_history_detail_info($user_employment_id) {
	$user = vcn_get_user_info();
	$userid = $user['vcn_user_id'];

	$params = array('useremploymentid' => $user_employment_id, 'userid' => $userid);
	$emphistory = vcn_rest_wrapper('vcncmasvc', 'vcncmaemployment', 'get-employment-history-detail', $params, 'xml', 'post', false);
	return $emphistory->item;
	/* End REST Call */
}