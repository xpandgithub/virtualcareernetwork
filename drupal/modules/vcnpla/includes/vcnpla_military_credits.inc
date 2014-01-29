<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php

function vcn_pla_military_credit($branch = NULL, $search_term = NULL) {
	$additional_parameters = array();
	if(!empty($branch)) {
		$additional_parameters['branch'] = $branch;
	}
	if(!empty($search_term)) {
		$additional_parameters['search_term'] = $search_term;
	}
	return drupal_get_form('vcnpla_military_credit_form', $additional_parameters);
}

function vcnpla_military_credit_form($form, &$form_state, $additional_parameters = array()) {
	
	$form = array();
	$form['#form_state'] = $form_state;
	
	$form['military_credit_branch'] = array (
			'#type' => 'select',
			'#title' => t('Select your branch'),
			'#multiple' => false,
			'#options' => array ('air_force'=>'Air Force', 'army'=>'Army', 'coast_guard'=>'Coast Guard','dod'=>'Department of Defense', 'marine_corps'=>'Marine Corps', 'navy'=>'Navy'),
			'#default_value' => isset($additional_parameters['branch']) ? $additional_parameters['branch'] : 'air_force',
	);
	
	$form['military_credit_search_box'] = array (
			'#type' => 'textfield',
			'#title' => t('Enter a military course id number, key words, and/or phrases in the title or leave blank to see all courses'),
			'#default_value' => isset($additional_parameters['search_term']) ? $additional_parameters['search_term'] : '',
			'#size' => 85,
	);
	
	$form['military_credit_submit'] = array (
			'#type' => 'submit',
			'#name' => 'military_credit_submit',
			'#value' => t('Search'),
			'#attributes' => array (
					'class' => array('vcn-button'),
					'title' => "Search"
			)
	);
	
	$form['military_credit_show_grid'] = array (
			'#type' => 'hidden',
			'#value' => empty($additional_parameters) ? FALSE : TRUE
	);
	
	return $form;

}

function vcnpla_military_credits($branch) {
	$page = 'rest-dt-pla-military-credits';
	vcnpla_military_courses_create_dataTable($page, $branch);
}

function vcnpla_military_credits_search_term($branch, $search_term) {
	$page = 'rest-dt-pla-military-credits';
	vcnpla_military_courses_create_dataTable($page, $branch, $search_term);
}


function vcnpla_military_courses_create_dataTable($page, $branch, $search_term = NULL) {
	$params_for_dataTable_rest_call = array();
	
	$params_for_dataTable_rest_call['page'] = $page;
	$params_for_dataTable_rest_call['branch'] = $branch;
	
	if ($search_term) {
		$params_for_dataTable_rest_call['search_term'] = $search_term;
	}
	vcn_rest_datatable($params_for_dataTable_rest_call);
}


function vcnpla_military_credit_form_submit($form, &$form_state) {
	
	$branch = $form['military_credit_branch']['#value'];
	
	$military_credit_search_box = trim($form['military_credit_search_box']['#value']);
	if (!empty($military_credit_search_box) && (strlen($military_credit_search_box) > 2)) {
		$search_term = $military_credit_search_box;
		$search_term = str_replace('/', '~', $search_term);
		$search_term = str_replace('\\', '*', $search_term);
	} else {
		$search_term = null;
	}
	
	$redirect_url = 'pla/military-credit/'.$branch;
	if (!empty($search_term)) {
		$redirect_url .= '/'.$search_term;
	}
	
	$form_state['redirect'] = $redirect_url;

}