<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php 
// Get Started callback function
function vcn_get_started() {
	return theme('vcn_get_started_template');
	//return drupal_get_form('vcngetstarted_form');
}

function vcngetstarted_form_validate($form, &$form_state) {
	
	if (!is_numeric($form_state['values']['zipcode']) || $form_state['values']['zipcode'] == "") {
		form_set_error('zipcode', t('Please enter a valid US ZIP code.'));
	}	
	
	if ($form_state['values']['edu_level'] < 1) {
		form_set_error('edu_level', t('Please select Education Level.'));
	}

}

function vcngetstarted_form_submit($form, &$form_state) {
	$form_state['redirect'] = FALSE;	
}

function vcngetstarted_get_edu_level() {

	$edu_categories = vcn_rest_wrapper('vcnlookupsvc', 'vcnlookup', 'get-education-category', array());
	$data = array(0 => 'Select Education Level');
	foreach($edu_categories->categories->item as $edu_category) {
		$data[(int)$edu_category->educationcategoryid] = (string)$edu_category->educationcategoryname;
	}

	return $data;
}

function vcn_clean_up_career_title($text) {
	$text = strtolower($text);
	$text = str_replace('&', ' ', $text);
	$text = str_replace(',', ' ', $text);
	$text = str_replace('/', ' ', $text);
	$text = str_replace('\\', ' ', $text);
	$text = str_replace('(', ' ', $text);
	$text = str_replace(')', ' ', $text);
	$text = str_replace("'", "", $text);
	$text = str_replace(' or ', ' ', $text);
	$text = str_replace(' and ', ' ', $text);
	$text = str_replace(' not ', ' ', $text);
	$text = str_replace(' of ', ' ', $text);
	$text = str_replace(' the ', ' ', $text);
	$text = str_replace(' in ', ' ', $text);
	$text = str_replace(' for ', ' ', $text);
	$text = str_replace(' with ', ' ', $text);

	return $text;
}
