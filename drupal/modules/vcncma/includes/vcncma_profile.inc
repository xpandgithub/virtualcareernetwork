<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php 

function vcn_cma_profile_form_validate($form, &$form_state) {
	
	$first_name = trim($form_state['values']['first_name']);
	$last_name = trim($form_state['values']['last_name']);
	
	if ($first_name != "" && !preg_match('/^[a-zA-Z\-\'\s]+$/', $first_name)) {
		form_set_error('first_name', t("Your First Name can only contain alphabets, space, dash and apostrophe ( -')."));
	}
	
	if ($first_name != "" && (strlen($first_name) > 30)) {
		form_set_error('first_name', t("First Name should not be greater than 30 characters."));
	}
	
	if ($last_name != "" && !preg_match('/^[a-zA-Z\-\'\s]+$/', $last_name)) {
		form_set_error('last_name', t("Your Last Name can only contain alphabets, space, dash and apostrophe ( -')."));
	}
	
	if ($last_name != "" && (strlen($last_name) > 30)) {
		form_set_error('last_name', t("Last Name should not be greater than 30 characters."));
	}
	
	if ($form_state['values']['zipcode'] != "" && !is_numeric($form_state['values']['zipcode'])) {
		form_set_error('zipcode', t('Please enter a valid US ZIP code.'));
	}
}

function vcn_cma_profile_form_submit($form, &$form_state) {		//$form_state['redirect'] = FALSE;	
	$user = vcn_get_user_info();
	$userid = $user['vcn_user_id'];
	
	$params = array('userid' => $userid, 'firstname' => vcn_custom_aes_encrypt($form_state['input']['first_name']), 'lastname' => vcn_custom_aes_encrypt($form_state['input']['last_name']), 'zipcode' => $form_state['input']['zipcode']);
	$update_info = vcn_rest_wrapper('vcncmasvc', 'vcncmauser', 'update-user-info-by-cma-userid', $params, 'xml', 'post', false);
	$update_info_status = (int) $update_info->item;
	
	if($update_info_status) {
		drupal_set_message(t('Info has been updated succesfully.'));
	}else {
		drupal_set_message(t('There was a problem updating info. Please try again.'), 'error');
	}
}

/*function vcn_get_cma_user_info() {	
	$user = vcn_get_user_info();
	$userid = $user['vcn_user_id'];
	
	$params = array('userid' => $userid);
	$cmauserinfo = vcn_rest_wrapper('vcncmasvc', 'vcncmauser', 'get-user-info', $params, 'xml', 'post', false); 

  return $cmauserinfo;
	 
}*/