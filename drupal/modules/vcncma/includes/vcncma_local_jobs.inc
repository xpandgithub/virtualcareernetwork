<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php 

function vcn_cma_local_jobs_form_validate($form, &$form_state) {
	
	$contact_email = trim($form_state['values']['local_jobs_contact_email']);
	if (!empty($contact_email) && !filter_var($contact_email, FILTER_VALIDATE_EMAIL)) {
		form_set_error('local_jobs_contact_email', t('Please enter a valid E-mail'));
	}
	
}


function vcn_cma_local_jobs_form_submit($form, &$form_state) {
	
	$userinfo = $form['#userinfo'];
	
	$params = array();
	
	$params['userid'] = $userinfo['vcn_user_id'];
	$params['jobtitle'] = trim($form_state['values']['local_jobs_job_title']);
	$params['joburl'] = trim($form_state['values']['local_jobs_job_url']);
	$params['employername'] = trim($form_state['values']['local_jobs_employer_name']);
	$params['employerurl'] = trim($form_state['values']['local_jobs_employer_url']);
	$params['contactname'] = trim($form_state['values']['local_jobs_contact_name']);
	$params['contactphone'] = trim($form_state['values']['local_jobs_contact_phone']);
	$params['contactemail'] = trim($form_state['values']['local_jobs_contact_email']);
	$params['note'] = trim($form_state['values']['local_jobs_note']);
	
	if ($form_state['input']['local_jobs_action_type'] == 'add') {
		$add_info = vcn_rest_wrapper('vcncmasvc', 'vcncmalocaljobs', 'add-cma-local-job', $params, 'xml', 'post', false);
		$add_info_status = isset($add_info) ? (int) $add_info->item : 0;
		if($add_info_status) {
			drupal_set_message(t('Local job has been added succesfully.'));
			$form_state['redirect'] = 'cma/local-jobs';
		} else {
			drupal_set_message(t('There was a problem adding the local job. Please try again'), 'error');
		}
	} else if($form_state['input']['local_jobs_action_type'] == 'update') {
		$params['localjobid'] = $form_state['input']['local_jobs_id'];
		$update_info = vcn_rest_wrapper('vcncmasvc', 'vcncmalocaljobs', 'update-cma-local-job', $params, 'xml', 'post', false);
		$update_info_status = isset($update_info) ? (int) $update_info->item : 0;
		if($update_info_status) {
			drupal_set_message(t('Local job has been updated succesfully.'));
      $form_state['redirect'] = 'cma/local-jobs';
		} else {
			drupal_set_message(t('There was a problem updating the local job. Please try again'), 'error');
		}
	}
	

}