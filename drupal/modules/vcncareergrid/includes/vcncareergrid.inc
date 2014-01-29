<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php

function vcn_career_grid($education_level = NULL, $work_type = NULL) {
	$additional_params = array();
	$additional_params['education_level'] = empty($education_level) ? '0' : $education_level;
	$additional_params['work_type'] = empty($work_type) ? '0' : $work_type;
	// special condition to accomodate the "Advanced Search" on Career Explorer page
	if (isset($_POST['jobtitle'])) {
		$additional_params['search_term'] = urldecode($_POST['jobtitle']);
	}
	return drupal_get_form('vcncareergrid_form', $additional_params);
}

function vcn_career_grid_search_term($education_level, $work_type, $search_term) {
	$search_term = urldecode($search_term);
	$additional_parameters = array('search_term' => $search_term, 'education_level' => $education_level, 'work_type' => $work_type);
	return drupal_get_form('vcncareergrid_form', $additional_parameters);
}


function vcncareergrid_form($form, &$form_state, $additional_params) {
	
	$params = array();
	$params['industry'] = vcn_get_industry();
	
	$form = array();
	
	$form['careergrid_edu_level'] = array (
			'#type' => 'select',
			'#title' => t('Typical Education:'),
			'#multiple' => false,
			'#options' => vcn_careergrid_get_careers_byworktype_edu(),
			'#default_value' => $additional_params['education_level'],
	);
	
	
	$form['careergrid_type_of_work'] = array (
			'#type' => 'select',
			'#title' => t('Work Category:'),
			'#multiple' => false,
			'#options' => vcn_careergrid_get_work_categories($params),
			'#default_value' => $additional_params['work_type'],
	);
	
	$form['careergrid_search_box'] = array (
			'#type' => 'textfield',
			'#title' => t('Interested Career:'),
			'#size' => 20,
			'#default_value' => isset($additional_params['search_term']) ? $additional_params['search_term'] : '',
	);
	
	$form['careergrid_submit'] = array (
			'#type' => 'submit',
			'#name' => 'careergrid_submit',
			'#value' => t('Search'),
			'#attributes' => array (
					'class' => array('vcn-button'),
					'title' => "Search"
			)
	);
	
	return $form;
	
}


function vcn_careergrid_get_work_categories($params) {
	
	$work_categories = vcn_get_work_categories($params);
	$data = array(0 => 'All');
	foreach ($work_categories->item as $work_category) {
		$data[(string)$work_category->workcategorycode] = (string)$work_category->workcategorynameabbrev;
	}
	
	return $data;
}


function vcn_careergrid_get_careers_byworktype_edu() {
	
	$edu_categories = vcn_rest_wrapper('vcnlookupsvc', 'vcnlookup', 'get-education-category', array());
	$data = array(0 => 'Any Education Level');
	foreach($edu_categories->categories->item as $edu_category) {
		$data[(int)$edu_category->educationcategoryid] = (string)$edu_category->educationcategoryname;
	}
	
	return $data;
}

function vcncareergrid_form_submit($form, &$form_state) {
	$redirect_url = 'careergrid/education-level/'.$form['careergrid_edu_level']['#value'].'/work-type/'.$form['careergrid_type_of_work']['#value'];
	$career_search_term = trim($form['careergrid_search_box']['#value']);
	if (!empty($career_search_term)) {
		$redirect_url .= '/search-term/'.$career_search_term;
	}
	$form_state['redirect'] = $redirect_url;
}

function vcn_careergrid_rest($education_level, $work_type, $search_term = NULL) {
	$page = 'rest-dt-careergrid';
	vcn_careergrid_create_dataTable($page, $education_level, $work_type, $search_term);
}

function vcn_careergrid_create_dataTable($page, $education_level, $work_type, $search_term = NULL) {
	
	$params_for_dataTable_rest_call = array();
	
	$params_for_dataTable_rest_call['page'] = $page;
	$params_for_dataTable_rest_call['education_level'] = $education_level;
	$params_for_dataTable_rest_call['work_type'] = $work_type;
	$params_for_dataTable_rest_call['search_term'] = $search_term;
	
	vcn_rest_datatable($params_for_dataTable_rest_call);
	
}

function vcn_career_overview_display($onetcode) {

	module_load_include('inc', 'vcngeneral', '/includes/vcngeneral');

  $industry = vcn_get_industry();
  
	$params = array('onetcode' => $onetcode, 'industry' => $industry);
	$content = vcn_rest_wrapper('vcnoccupationsvc', 'vcncareer', 'get-career', $params);
	$detailed_description = vcn_text_into_paragraphs((string)$content->career->description);

	if (!empty($detailed_description['regular_text_array'])) {
		$detailed_description_regular_text = "";
		foreach ($detailed_description['regular_text_array'] as $value) {
			$detailed_description_regular_text .= '<p>'.$value.'</p>';
		}
	} else {
		$detailed_description_regular_text = "";
	}

	if (!empty($detailed_description['hidden_text_array'])) {
		$detailed_description_hidden_text = "";
		foreach ($detailed_description['hidden_text_array'] as $value) {
			$detailed_description_hidden_text .= '<p>'.$value.'</p>';
		}
	} else {
		$detailed_description_hidden_text = "";
	}
	$description = $detailed_description_regular_text . ' ' . $detailed_description_hidden_text;


	echo '<div class="lightbox-content">
	  <p><b>Career Overview</b></p>
	  <p>
	    '.$description.'
	  </p>
	</div>';

}