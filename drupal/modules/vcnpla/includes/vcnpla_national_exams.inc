<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php

function vcn_pla_national_exams($organization = NULL, $search_term = NULL) {
	$additional_parameters = array();
	if(!empty($organization)) {
		$additional_parameters['organization'] = $organization;
	}
	if(!empty($search_term)) {
		$additional_parameters['search_term'] = $search_term;
	}
	
	return drupal_get_form('vcnpla_national_exams_form', $additional_parameters);
}

function vcnpla_national_exams_form($form, &$form_state, $additional_parameters = array()) {
	$form = array();
	$form['#form_state'] = $form_state;
	
	$organizations = vcn_pla_get_training_exams_organizations('exam');
	
	if(isset($additional_parameters['organization'])) {
		$default_val = $additional_parameters['organization'];
	} else {
		$temp_org_array = $organizations; // using $temp_org_array because don't want a risk of changing the original array
		reset($temp_org_array);
		$default_val = key($temp_org_array);
	}
	
	$form['national_exams_organization'] = array (
			'#type' => 'select',
			'#title' => t('Select testing organization'),
			'#multiple' => false,
			'#options' => $organizations,
			'#default_value' => $default_val,
	);
	
	$form['national_exams_search_box'] = array (
			'#type' => 'textfield',
			'#title' => t('Enter key words and/or phrases to search on or leave blank to see all exams'),
			'#size' => 85,
			'#default_value' => isset($additional_parameters['search_term']) ? $additional_parameters['search_term'] : '',
	);
	
	$form['national_exams_submit'] = array (
			'#type' => 'submit',
			'#name' => 'national_exams_submit',
			'#value' => t('Search'),
			'#attributes' => array (
					'class' => array('vcn-button'),
					'title' => "Search"
			)
	);
	
	$form['national_exams_show_grid'] = array (
			'#type' => 'hidden',
			'#value' => empty($additional_parameters) ? FALSE : TRUE
	);
	
	return $form;
	
}

function vcnpla_national_exams($organization) {
	$page = 'rest-dt-pla-national-exams';
	vcnpla_national_exams_create_dataTable($page, $organization);
}

function vcnpla_national_exams_search_term($organization, $search_term) {
	$page = 'rest-dt-pla-national-exams';
	vcnpla_national_exams_create_dataTable($page, $organization, $search_term);
}


function vcnpla_national_exams_create_dataTable($page, $organization, $search_term = NULL) {
	$params_for_dataTable_rest_call = array();
	
	$params_for_dataTable_rest_call['page'] = $page;
	$params_for_dataTable_rest_call['organization'] = $organization;
	$params_for_dataTable_rest_call['branch_type'] = 'Exam';
	
	if ($search_term) {
		$params_for_dataTable_rest_call['search_term'] = $search_term;
	}
	vcn_rest_datatable($params_for_dataTable_rest_call);
}

function vcnpla_national_exams_form_submit($form, &$form_state) {
	
	$organization = $form['national_exams_organization']['#value'];
	
	$national_exams_search_box = trim($form['national_exams_search_box']['#value']);
	if (!empty($national_exams_search_box) && (strlen($national_exams_search_box) > 2)) {
		$search_term = $national_exams_search_box;
		$search_term = str_replace('/', '~', $search_term);
		$search_term = str_replace('\\', '*', $search_term);
	} else {
		$search_term = null;
	}
	
	$redirect_url = 'pla/national-exams/'.$organization;
	if (!empty($search_term)) {
		$redirect_url .= '/'.$search_term;
	}
	
	$form_state['redirect'] = $redirect_url;
	
	
}