<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php

function vcn_pla_professional_training($organization = NULL, $search_term = NULL) {
	$additional_parameters = array();
	if(!empty($organization)) {
		$additional_parameters['organization'] = $organization;
	}
	if(!empty($search_term)) {
		$additional_parameters['search_term'] = $search_term;
	}
	return drupal_get_form('vcnpla_professional_training_form', $additional_parameters);
}

function vcnpla_professional_training_form($form, &$form_state, $additional_parameters = array()) {
	$form = array();
	$form['#form_state'] = $form_state;
	
	$organizations = vcn_pla_get_training_exams_organizations('course');
	
	if(isset($additional_parameters['organization'])) {
		$default_val = $additional_parameters['organization'];
	} else {
		$temp_org_array = $organizations; // using $temp_org_array because don't want a risk of changing the original array
		reset($temp_org_array);
		$default_val = key($temp_org_array);
	}
	
	$form['professional_training_organization'] = array (
			'#type' => 'select',
			'#title' => t('Select the organization that provided the training from the list below'),
			'#multiple' => false,
			'#options' => $organizations,
			'#default_value' => $default_val,
	);
	
	$form['professional_training_search_box'] = array (
			'#type' => 'textfield',
			'#title' => t('Type in a course ID number, key words, and/or phrases in the course or leave blank to see all courses'),
			'#size' => 85,
			'#default_value' => isset($additional_parameters['search_term']) ? $additional_parameters['search_term'] : '',
	);
	
	$form['professional_training_submit'] = array (
			'#type' => 'submit',
			'#name' => 'professional_training_submit',
			'#value' => t('Search'),
			'#attributes' => array (
					'class' => array('vcn-button'),
					'title' => "Search"
			)
	);
	
	$form['professional_training_show_grid'] = array (
			'#type' => 'hidden',
			'#value' => empty($additional_parameters) ? FALSE : TRUE
	);
	
	return $form;
}

function vcnpla_professional_training($organization) {
	$page = 'rest-dt-pla-professional-training';
	vcnpla_professional_training_create_dataTable($page, $organization);
}

function vcnpla_professional_training_search_term($organization, $search_term) {
	$page = 'rest-dt-pla-professional-training';
	vcnpla_professional_training_create_dataTable($page, $organization, $search_term);
}


function vcnpla_professional_training_create_dataTable($page, $organization, $search_term = NULL) {
	$params_for_dataTable_rest_call = array();
	
	$params_for_dataTable_rest_call['page'] = $page;
	$params_for_dataTable_rest_call['organization'] = $organization;
	$params_for_dataTable_rest_call['branch_type'] = 'Course';
	
	if ($search_term) {
		$params_for_dataTable_rest_call['search_term'] = $search_term;
	}
	vcn_rest_datatable($params_for_dataTable_rest_call);
}

function vcnpla_professional_training_form_submit($form, &$form_state) {
	
	$organization = $form['professional_training_organization']['#value'];
	
	$professional_training_search_box = trim($form['professional_training_search_box']['#value']);
	if (!empty($professional_training_search_box) && (strlen($professional_training_search_box) > 2)) {
		$search_term = $professional_training_search_box;
		$search_term = str_replace('/', '~', $search_term);
		$search_term = str_replace('\\', '*', $search_term);
	} else {
		$search_term = null;
	}
	
	$redirect_url = 'pla/professional-training/'.$organization;
	if (!empty($search_term)) {
		$redirect_url .= '/'.$search_term;
	}
	
	$form_state['redirect'] = $redirect_url;
	
	
}