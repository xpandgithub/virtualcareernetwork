<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php

function vcn_pla_college_courses() {
	return drupal_get_form('vcnpla_college_courses_form');
}

function vcnpla_college_courses_form($form, &$form_state) {
	
	$form = array();
	
	$form['school_name'] = array (
			'#type' => 'textfield',
			'#title' => t('School Name'),
			'#required' => TRUE,
	);
	
	$form['program_name'] = array (
			'#type' => 'textfield',
			'#title' => t('Program Name'),
	);
	
	$form['course_name'] = array (
			'#type' => 'textfield',
			'#title' => t('Course Name'),
			'#required' => TRUE,
			'#maxlength' => 100,
	);
	
	$form['course_number'] = array (
			'#type' => 'textfield',
			'#title' => t('Course Number'),
			'#maxlength' => 30,
	);
	
	$form['final_grade'] = array (
			'#type' => 'textfield',
			'#title' => t('Final Grade'),
			'#size' => 5,
			'#maxlength' => 3,
	);
	
	$form['year_course_completed'] = array (
			'#type' => 'textfield',
			'#title' => t('Year Course Completed'),
			'#size' => 10,
			'#maxlength' => 4,
	);
	
	$form['num_credit_hours'] = array (
			'#type' => 'textfield',
			'#title' => t('Number of Credit Hours'),
			'#size' => 10,
			'#maxlength' => 4,
	);
	
	$form['vcnpla_uid'] = array (
			'#type' => 'hidden',
			'#attributes' => array ('id' => array('vcnpla_uid'))
	);
	
	$form['college_courses_submit'] = array (
			'#type' => 'submit',
			'#name' => 'college_courses_submit',
			'#value' => t('Save to Learning Inventory'),
			'#attributes' => array (
					'class' => array('vcn-button'),
					'title' => "Save to Learning Inventory"
			)
	);
	
	return $form;
	
}

function vcnpla_college_courses_form_submit($form, &$form_state) {
	
	$school_name = $form['school_name']['#value']; //required fields, drupal form will take care
	$course_name = $form['course_name']['#value']; //required fields, drupal form will take care
	
	$program_name = trim($form['program_name']['#value']);
	$course_number = trim($form['course_number']['#value']);
	$final_grade = trim($form['final_grade']['#value']);
	$year_course_completed = trim($form['year_course_completed']['#value']);
	$num_credit_hours = trim($form['num_credit_hours']['#value']);
	
	$program_name = empty($program_name) ? NULL : $program_name;
	$course_number = empty($course_number) ? NULL : $course_number;
	$final_grade = empty($final_grade) ? NULL : $final_grade;
	$year_course_completed = empty($year_course_completed) ? NULL : $year_course_completed.'-01-01'; //logic copied over from Drupal 6
	$num_credit_hours = empty($num_credit_hours) ? NULL : $num_credit_hours;
	
	//this code should go away if we move the generating of user_id to D7, then we only need to do $user_id = (int)$GLOBALS['vcn_user_id'];
	$user_id = $form['vcnpla_uid']['#value'];
	if (empty($user_id)) {
		$user_id = (int)$GLOBALS['vcn_user_id'];
	}
	//end of comment above $user_id = $form['vcnpla_uid']['#value'];
	
	$params = array (
			'school_name' => $school_name,
			'course_name'	=> $course_name,
			'program_name' => $program_name,
			'course_number' => $course_number,
			'final_grade' => $final_grade,
			'num_credit_hours' => $num_credit_hours,
			'year_course_completed' => $year_course_completed,
			'user_id' => $user_id
	);
	
	$call_result = vcn_rest_wrapper('vcnplasvc', 'vcnpla', 'save-pla-college-credit-to-cma', $params, 'json', 'post', false);
	
	if (isset($call_result) && ($call_result->result == TRUE)) {
		drupal_set_message(t('College course successfully saved to Learning Inventory.'), 'status');
	} else {
		drupal_set_message(t('An error occurred while saving the college course to Learning Inventory.'), 'error');
	}
	
}

function vcnpla_college_courses_form_validate($form, &$form_state) {
	$year_course_completed = trim($form['year_course_completed']['#value']);
	if (!empty($year_course_completed)) {
		if ($year_course_completed < 1900) {
			form_set_error('year_course_completed', t('Year should be greater than or equal to 1900.'));
		}
		if ($year_course_completed > date('Y')) {
			form_set_error('year_course_completed', t('Year cannot be greater than current year.'));
		}
	}
}