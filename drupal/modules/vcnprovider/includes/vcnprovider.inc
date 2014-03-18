<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php 
// This function is used to make sure the user has a provider id.  If not redirect them to home page.
// Node 1188 in D6 is used to redirect the user from login to the provider portal summary page
function vcn_validate_provider_user($redirect=true) {
  global $user_info;
  
  if (!isset($user_info['user_provider_id']) || !strlen($user_info['user_provider_id']) || $user_info['user_provider_id'] == '0') {
    if (!$redirect) {
      return false;
    }
    
    $url = $GLOBALS['vcn_config_http_or_https'] . '://' . $_SERVER['SERVER_NAME'] . vcn_drupal7_base_path();
    header('Location: ' . $url);
    
    // the die is needed in case somehow user is able to get past the header line (http://thedailywtf.com/Articles/WellIntentioned-Destruction.aspx)
    die();
  } 
 
  return true;
}

function vcn_validate_user_provider_id($id) {
  global $user_info;
  
  $exists = false;
  if (isset($user_info['user_provider_id'])) {
    $providerIdsArr = explode(',', $user_info['user_provider_id']);
    
    foreach ($providerIdsArr as $value) {
      if (trim($value) == $id) {
        $exists = true;
        break;
      }
    }
  }
  
  if (!$exists) {
    drupal_set_message('You do not have access to this provider\'s information. ', 'error');
    drupal_access_denied();
  }
  
  return true;
}

//Provider REST calls by UNITID functions
function vcn_get_provider_detail(&$variables) {	
	
 	$useCache = vcn_use_cache($variables); 
	
	/* REST Call */
	// get the provider detail info by provider unitid
	$params = array('unitid' => $variables['unitid']);
	$providerdetail = vcn_rest_wrapper('vcnprovidersvc', 'vcnprovider', 'get-provider-detail-by-unitid', $params, 'xml', 'post', $useCache);

	return $providerdetail->providerdetail->item;
	/* End REST Call */
}

function vcn_get_provider_services(&$variables) {
	/* REST Call */
	// get the provider services by provider unitid
	$params = array('unitid' => $variables['unitid']);
	$providerservices = vcn_rest_wrapper('vcnprovidersvc', 'vcnprovider', 'get-provider-services-by-unitid', $params);

	return $providerservices->providerservices;
	/* End REST Call */
}

function vcn_get_provider_entrance_tests(&$variables) {

	$useCache = vcn_use_cache($variables);
	/* REST Call */
	// get the provider entrance tests by provider unitid
	$params = array('unitid' => $variables['unitid']);
	$providerentrancetests = vcn_rest_wrapper('vcnprovidersvc', 'vcnprovider', 'get-provider-entrance-tests-by-unitid', $params, 'xml', 'post', $useCache);

	return $providerentrancetests->providerentrancetests;
	/* End REST Call */
}

function vcn_get_provider_required_courses(&$variables) {

	$useCache = vcn_use_cache($variables);
	/* REST Call */
	// get the provider required courses by provider unitid
	$params = array('unitid' => $variables['unitid']);
	$providerrequiredcourses = vcn_rest_wrapper('vcnprovidersvc', 'vcnprovider', 'get-provider-required-courses-by-unitid', $params, 'xml', 'post', $useCache);

	return $providerrequiredcourses->providerrequiredcourses;
	/* End REST Call */
}

function vcn_get_provider_degrees_offered(&$variables) {
	/* REST Call */
	// get the provider degrees offered by provider unitid
	$params = array('unitid' => $variables['unitid']);
	$providerdegreesoffered = vcn_rest_wrapper('vcnprovidersvc', 'vcnprovider', 'get-provider-degrees-offered-by-unitid', $params);

	return $providerdegreesoffered->providerdegreesoffered;
	/* End REST Call */
}

function vcn_get_provider_faid_offered(&$variables) {
	/* REST Call */
	// get the provider faid offered by provider unitid
	$params = array('unitid' => $variables['unitid']);
	$providerfaidoffered = vcn_rest_wrapper('vcnprovidersvc', 'vcnprovider', 'get-provider-faid-offered-by-unitid', $params);

	return $providerfaidoffered->providerfaidoffered;
	/* End REST Call */
}
//Provider REST calls by UNITID functions END

//Provider profile edit page functions
function vcn_provider_profile_tabs(&$variables) {
	$vcn_tabs_list = array();
	$vcn_tabs_list[] = array("desc","Overview ");
	$vcn_tabs_list[] = array("deg","Degrees Offered");
	$vcn_tabs_list[] = array("test","Entrance Tests");
	$vcn_tabs_list[] = array("cost","Cost + Fees");
	$vcn_tabs_list[] = array("ser","Services");
	$vcn_tabs_list[] = array("demo","Student<br/>Demographics");
	$vcn_tabs_list[] = array("req","Prerequisite Courses<br/>for Admission");

	$variables['vcn_tabs_list'] = $vcn_tabs_list;
	$variables['vcn_tabs_keys'] = array("desc","deg","test","cost","ser","demo","req");

	$variables['vcn_tabs_header'] = vcn_tabs_header($variables['vcn_tabs_list'], $variables['vcn_tabs_keys']);

	$variables['vcn_tabs_body_start'] = vcn_tabs_body_start();
	$variables['vcn_tabs_body_id_prefix'] = vcn_tabs_body_id_prefix();
	$variables['vcn_tabs_body_end'] = vcn_tabs_body_end();

	return true;
}

function vcn_provider_profile(&$variables) {

	// Facebook Like button
	$variables['facebook_like'] = "";

	$variables['na'] = 'N/A';

	$providerdetail = $variables['providerdetail'];
	$providerdegreesoffered = $variables['providerdegreesoffered'];
	$providerentrancetests = $variables['providerentrancetests'];
	$providerfaidoffered = $variables['providerfaidoffered'];
	$providerrequiredcourses = $variables['providerrequiredcourses'];
	$providerservices = $variables['providerservices'];

	if ((strlen($providerdetail->logoimage)>1) ) {
		$prologo = $providerdetail->logoimage;
		$datatype='data:image/jpeg';
		$variables['school_logo'] =$datatype.";base64,".$prologo;
	} else {
		$variables['school_logo'] = vcn_image_path()."miscellaneous/school_default_logo.jpg";
	}

	if (strlen($providerdetail->missionstatement)) {
		$variables['missionstatement'] = $providerdetail->missionstatement;
	}

	if (strlen($providerdetail->missionstatementurl)>4 && $providerdetail->missionstatementurlflag) {
		$variables['missionstatementurl'] = vcn_build_link_window_opener($providerdetail->missionstatementurl, $providerdetail->name." Mission Statement", false, true);
	}

	if (strlen($providerdetail->applurl)>4 && $providerdetail->applurlflag) {
		$variables['applurl'] = vcn_build_link_window_opener($providerdetail->applurl, "Apply online", false, true);
	}

	if ($providerdetail->addr != 'null') {
		$variables['address'] = $providerdetail->addr;
	}
	$variables['address'] = $variables['address']."; ".$providerdetail->city.", ".$providerdetail->state." ".$providerdetail->zipcode;

	$variables['phone'] = vcn_format_phone($providerdetail->phone);

	if (strlen($providerdetail->webaddr)>4 && $providerdetail->webaddrflag) {
		$variables['webaddr'] = vcn_build_link_window_opener($providerdetail->webaddr, $providerdetail->webaddr, false, true);
	}

	if ($providerdetail->ipedsdesc != 'null') {
		$variables['ipedsdesc'] = $providerdetail->ipedsdesc;
	}else {
		$variables['ipedsdesc'] = 'N/A';
	}

	if (intval($providerdetail->percentadmittedtotal) < 1) {
		$variables['percentadmittedtotal'] = 'N/A';
	}else {
		$variables['percentadmittedtotal'] = number_format(intval($providerdetail->percentadmittedtotal), 0, '.', ',')."%";
	}

	$variables['degrees'] = "";
	foreach ($providerdegreesoffered->item as $degree) {
		$variables['degrees'] .= "<div class=\"school-list-item\">".$degree->codedesc."</div>";
	}

	$variables['services'] = "";
	if ($providerservices->item[0]->servicename != "") {
		foreach ($providerservices->item as $services) {
			$variables['services'] .= "<div class=\"school-list-item\">".$services->servicename."</div>";
		}
	}else {
		$variables['services'] = 'N/A';
	}

	$variables['entrancetests'] = '<div class="entrance-tests-row allclear"></div>';
	if (count($providerentrancetests->item) > 0) {
		foreach ($providerentrancetests->item as $test) {
			
			if (strlen($test->minscore) && (strtoupper($test->minscore) == 'NONE' || strtoupper($test->minscore) == 'N/A' || strtoupper($test->minscore) == 'NA')) {
				$testminscore = '';
			} else {
				$testminscore = $test->minscore;
			}
			
			$variables['entrancetests'] .= '<div class="entrance-tests-row allclear">
												<div><label><input type="text" class="testname" name="testname[]" value="'.$test->testname.'"></label></div>
												<div><label><input type="text" name="testdesc[]" value="'.$test->testdescription.'"></label></div>
												<div><label><input type="text" name="testminscore[]" value="'.$testminscore.'"></label></div>
												<div><input type="button" title="Delete" class="ent-tests-delete-this-row vcn-button" value="Delete" />
														<input type="hidden" name="testid[]" value="'.$test->testid.'">
												</div>
											</div>';		
		}
		
	} else {
		/*$variables['entrancetests'] = '<div class="entrance-tests-row allclear">
											<div><label><input type="text" class="testname" name="testname[]" value=""></label></div>
											<div><label><input type="text" name="testdesc[]" value=""></label></div>
											<div><label><input type="text" name="testminscore[]" value=""></label></div>
											<div><input type="button" class="ent-tests-delete-this-row vcn-button" value="Delete" />
													<input type="hidden" name="testid[]" value="">
											</div>
									   </div>';*/
	}

	if (strlen($providerdetail->faidurl)>4 && $providerdetail->faidurlflag) {
		$variables['faidurl'] = vcn_build_link_window_opener($providerdetail->faidurl, "Financial Aid (For this school)", false, true);
	}

	$variables['v2faidurl'] = "";
	if (count($providerfaidoffered->item) > 0) {
		foreach ($providerfaidoffered->item as $v2faidurl) {
			if (strlen($v2faidurl->v2loanid)) {
				$variables['v2faidurl'] .= "<div>".vcn_build_link_window_opener($v2faidurl->v3loanurl, $v2faidurl->v3loanname, false, true)."</div>";
			}
		}
	}

	$variables['requiredcourses'] = '<div class="required-courses-row allclear"></div>';
	if (count($providerrequiredcourses->item) > 0) {
		foreach ($providerrequiredcourses->item as $requiredcourses) {
			if (($requiredcourses->courseid) > 0) {
				switch (strtoupper($requiredcourses->courselevel)) {
					case 'C':
						$level = 'College';
						break;
					case 'H':
						$level = 'High School';
						break;
					case 'H or C':
						$level = 'High School or College';
						break;
					case 'B':
						$level = 'Bachelors';
						break;
					default:
						$level = $requiredcourses->courselevel;
						break;
				}
				$divider = '';
				if(strlen($requiredcourses->description) > 0) {
					$divider = ' - ';
				}
				
				$variables['requiredcourses'] .= '<div class="required-courses-row allclear">
													<div><label><input type="text" class="coursename" name="coursename[]" value="'.$requiredcourses->coursetitle.'"></label></div>
													<div><label><input type="text" name="coursedesc[]" value="'.$requiredcourses->description.'"></label></div>
													<div><label><input type="text" name="courselevel[]" value="'.$level.'"></label></div>
													<div><label><input type="text" name="coursemingpa[]" value="'.$requiredcourses->mingpa.'"></label></div>
													<div><input type="button" title="Delete" class="req-courses-delete-this-row vcn-button" value="Delete" />
															<input type="hidden" name="courseid[]" value="'.$requiredcourses->courseid.'">
													</div>
												</div>';
			}
		}
	}else {
		/*$variables['requiredcourses'] = '<div class="required-courses-row allclear">
											<div><label><input type="text" class="coursename" name="coursename[]" value=""></label></div>				
											<div><label><input type="text" name="coursedesc[]" value=""></label></div>
											<div><label><input type="text" name="courselevel[]" value=""></label></div>
											<div><label><input type="text" name="coursemingpa[]" value=""></label></div>
											<div><input type="button" class="req-courses-delete-this-row vcn-button" value="Delete" />
													<input type="hidden" name="courseid[]" value="">
											</div>
										</div>';*/		
	}
	
	return true;
}

function vcnprovider_form_validate($form, &$form_state) { 	
	
	if (trim($form_state['input']['provider_name']) == "") {
		form_set_error('provider_name', t('Please enter a Provider Name.'));
	}	
	
	if (!is_numeric(trim($form_state['input']['provider_phone'])) && trim($form_state['input']['provider_phone']) != "") {
		form_set_error('provider_phone', t('Phone number must contain a valid numeric values only.'));
	}	
	/*
	 $validators = array(
			//'file_validate_is_image' => array(),
			'file_validate_extensions' => array("png jpg gif"),
			'file_validate_image_resolution' => array("152x152"),
			//'file_validate_size' => array(10 * 1024),
	);
	
	$file = file_save_upload('provider_logo', $validators);	
	if ($file != 0) {		
		$form_state['values']['provider_logo'] = $file;			
	}
	*/
	
}

	
function vcnprovider_form_submit($form, &$form_state) { 
	
	//$form_state['redirect'] = FALSE;	// To display latest uploaded logo
	
	$validators = array(
			//'file_validate_is_image' => array(),
			'file_validate_extensions' => array("png jpg gif"),
			'file_validate_image_resolution' => array("152x152"),
			//'file_validate_size' => array(10 * 1024),
	);
	$file = file_save_upload('provider_logo', $validators); 
	if ($file != NULL) {
		$form_state['values']['provider_logo'] = $file;		
	}
	
	// Update Provider Detail
	vcn_provider_insert_delete_update($form_state);
	
	// Send mail to Provider admin.
	vcn_provider_mail_send($form_state);		
	
	if ($file != NULL) {	
		drupal_set_message("Provider Info has been updated succesfully.");
	}else {
		//drupal_set_message("Provider Info has been updated succesfully but there was a problem saving the logo image. Please try again.");
		drupal_set_message("Provider Info has been updated succesfully.");
	}	 
}


function vcn_provider_insert_delete_update(&$form_state) {
	

	$user = vcn_get_user_info(); 
	$updatedby = $user['uid'];	
	$unitid = $form_state['input']['provider_unitid']; 
	
	// Update Provider Basic Info	
	$params_info = array('rand' => rand(5, 5), 'unitid' => $unitid, 'updatedby' => $updatedby, 'name' => $form_state['input']['provider_name'], 'addr' => $form_state['input']['provider_addr'],
					 'city' => $form_state['input']['provider_city'], 'state' => $form_state['input']['provider_state'], 'zipcode' => $form_state['input']['provider_zipcode'],
					 'phone' => $form_state['input']['provider_phone'], 'applurl' => $form_state['input']['provider_applurl'],
					 'faidurl' => $form_state['input']['provider_faidurl'], 'webaddr' => $form_state['input']['provider_webaddr']);

	$update_info = vcn_rest_wrapper('vcnprovidersvc', 'vcnprovider', 'update-provider-info-by-unitid', $params_info, 'xml', 'post', false);
	$update_info_status = (int) $update_info->item;

	// Update Provider Detail Info	//dsm(check_markup($form_state['input']['provider_missionstatement'], 'user_input')); // plain_text filtered_html full_html vcn_text php_code var_dump(filter_formats());	
	$params_detail = array('rand' => rand(5, 5), 'unitid' => $unitid, 'updatedby' => $updatedby,
			'missionstatement' => $form_state['input']['provider_missionstatement'], 'missionstatementurl' => $form_state['input']['provider_missionstatementurl']);

	$update_detail = vcn_rest_wrapper('vcnprovidersvc', 'vcnprovider', 'update-provider-detail-by-unitid', $params_detail, 'xml', 'post', false);
	$update_detail_status = (int) $update_detail->item;	
	
	// Update Entrance Test	
	$oldtestidlist = "";
	$providerentrancetests = $form_state["complete form"]["#providerentrancetests"];
	if (count($providerentrancetests->item) > 0) {
		$old_testid_list = array();
		foreach ($providerentrancetests->item as $test) {				
			$old_testid_list[] = $test->testid;
		}
		$oldtestidlist = join(",", $old_testid_list);
	}	

	if(@count($form_state['input']['testid']) > 0 || count($providerentrancetests->item) > 0) {	
		$newtestidcount = count($form_state['input']['testid']);
	
		$params_entrance_tests = array('rand' => rand(5, 5), 'unitid' => $unitid, 'updatedby' => $updatedby, 'oldtestidlist' => $oldtestidlist, 'newtestidcount' => $newtestidcount,
				'testnamelist' => $form_state["input"]["testname"], 'testdesclist' => $form_state['input']['testdesc'], 'testminscorelist' => $form_state['input']['testminscore']);
		
		$update_entrance_tests = vcn_rest_wrapper('vcnprovidersvc', 'vcnprovider', 'update-provider-entrance-tests-by-unitid', $params_entrance_tests, 'xml', 'post', false);
		$update_entrance_tests_status = (int) ($update_entrance_tests->item[0]);
		
	}
	// Update Required Courses
	$oldcourseidlist = "";
	$providerreqcourses = $form_state["complete form"]["#providerrequiredcourses"];
	if (count($providerreqcourses->item) > 0) {
		$old_courseid_list = array();
		foreach ($providerreqcourses->item as $course) {
			$old_courseid_list[] = $course->courseid;
		}
		$oldcourseidlist = join(",", $old_courseid_list);
	}
	
	if(@count($form_state['input']['courseid']) > 0 || count($providerreqcourses->item) > 0) {
		$newcourseidcount = @count($form_state['input']['courseid']);
		if(isset($form_state['input']['courselevel']) && count($form_state['input']['courselevel']) > 0) {
			$cl=0;
			foreach ($form_state['input']['courselevel'] as $courselevel) {
				switch ($courselevel) {
					case 'College':
						$level = 'C';
						break;
					case 'High School':
						$level = 'H';
						break;
					case 'High School or College':
						$level = 'H or C';
						break;
					case 'Bachelors':
						$level = 'B';
						break;
					default:
						$level = $courselevel;
						break;
				}
				$form_state['input']['courselevel'][$cl] = $level;
				$cl++;
			}
		}
		
		$params_req_courses = array('rand' => rand(5, 5), 'unitid' => $unitid, 'updatedby' => $updatedby, 'oldcourseidlist' => $oldcourseidlist, 'newcourseidcount' => $newcourseidcount,
									'coursenamelist' => @$form_state["input"]["coursename"], 'coursedesclist' => @$form_state['input']['coursedesc'], 
									'courselevellist' => @$form_state['input']['courselevel'], 'coursemingpalist' => @$form_state['input']['coursemingpa']);
		
		$update_req_courses = vcn_rest_wrapper('vcnprovidersvc', 'vcnprovider', 'update-provider-required-courses-by-unitid', $params_req_courses, 'xml', 'post', false);
		$update_req_courses_status = (int) $update_req_courses->item[0];
		
	}
	
	// Update provider Logo		
	if(isset($form_state['values']['provider_logo']) && $form_state['values']['provider_logo'] != "") { 
		$logo_file = $form_state['values']['provider_logo'];
		$logo_extract = fopen($logo_file->uri, 'r');
		$logo_image = fread($logo_extract, filesize($logo_file->uri));
		$logo_image = base64_encode($logo_image);
		fclose($logo_extract);
		
		$params_logo = array('rand' => rand(5, 5), 'unitid' => $unitid, 'updatedby' => $updatedby, 'logo_image' => $logo_image);
		
		$update_logo = vcn_rest_wrapper('vcnprovidersvc', 'vcnprovider', 'update-provider-logo-by-unitid', $params_logo, 'xml', 'post', false);
		$update_logo_status = (int) $update_logo->item;
		
		/*$datatype='data:image/jpeg';
		$test11 =$datatype.";base64,".$logo_image;
		echo '<img src="'.$test11.'" alt="School logo">'; //exit;
		echo '<img src="'.$test11.'" alt="School logo" width="152" height="152">'; //exit;*/
	}
}


function vcn_provider_mail_send(&$form_state) {
	
	/* REST Call */
	// get Provider's Email Id
	$params = array('prop_id' => 2);
	$content = vcn_rest_wrapper('vcnpropertiessvc', 'vcnproperties', 'get-properties', $params);
	$provider_portal_email = (string) $content->properties->item->value;
	/* End REST Call */
	
	$user = vcn_get_user_info(); 
	$user_id = $user['uid'];
	$user_name = $user['name'];
	$user_email =$user['mail'];
	
	$mail_subject = 'VCN Provider Changes';
	$body = "";
	
	$body .= '<strong style="text-decoration:underline;">School Information:</strong><br/>' . "\r\n";
	$body .= '<strong>Provider Id: </strong> '.$form_state['input']['provider_unitid'].'<br/>' . "\r\n";
	$body .= '<strong>School Name:</strong> '.$form_state['input']['provider_name'].'<br/>' . "\r\n";
	$body .= '<strong>Address:</strong> '.$form_state['input']['provider_addr'].'<br/>' . "\r\n";
	$body .= '<strong>City:</strong> '.$form_state['input']['provider_city'].'<br/>' . "\r\n";
	$body .= '<strong>State:</strong> '.$form_state['input']['provider_state'].'<br/>' . "\r\n";
	$body .= '<strong>Zipcode:</strong> '.$form_state['input']['provider_zipcode'].'<br/>' . "\r\n";
	$body .= '<strong>Phone:</strong> '.$form_state['input']['provider_phone'].'<br/>' . "\r\n";
	$body .= '<strong>School URL:</strong> '.$form_state['input']['provider_webaddr'].'<br/>' . "\r\n";
	$body .= '<strong>Application URL:</strong> '.$form_state['input']['provider_applurl'].'<br/>' . "\r\n";
	$body .= '<strong>Financial Aid URL:</strong> '.$form_state['input']['provider_faidurl'].'<br/><br/>' . "\r\n";
	$body .= '<strong>Mission Statement:</strong> '.$form_state['input']['provider_missionstatement'].'<br/><br/>' . "\r\n";
	$body .= '<strong>Mission Statement URL:</strong> '.$form_state['input']['provider_missionstatementurl'].'<br/><br/>' . "\r\n";
	
	
	if(isset($form_state['input']['testid']) && count($form_state['input']['testid']) > 0) {
		$p_ent_tests = '';
		for($p=0,$i=1;$p<count($form_state['input']['testid']);$p++,$i++) {
			if(trim($form_state['input']['testname'][$p]) != "") {
				//$p_ent_tests .= '<strong>Status '.$i.':</strong> '.$form_state['input']['testname'][$p].'<br/>' . "\r\n";
				$p_ent_tests .= '<strong>Test '.$i.' Name:</strong> '.$form_state['input']['testname'][$p].'<br/>' . "\r\n";
				$p_ent_tests .= '<strong>Test '.$i.' Description:</strong> '.$form_state['input']['testdesc'][$p].'<br/>' . "\r\n";
				$p_ent_tests .= '<strong>Test '.$i.' Minimum Score:</strong> '.$form_state['input']['testminscore'][$p].'<br/><br/>' . "\r\n";
			}
		}
		if (strlen($p_ent_tests) > 0) {
			$body .= '<br/><strong style="text-decoration:underline;">School Entrance Test Requirements:</strong><br/>' . "\r\n";
			$body .= $p_ent_tests;
		}
	}
	
	if(isset($form_state['input']['courseid']) && count($form_state['input']['courseid']) > 0) {
		$p_req_courses = '';
		for($p=0,$i=1;$p<count($form_state['input']['courseid']);$p++,$i++) {
			if(trim($form_state['input']['coursename'][$p]) != "") {
				//$p_req_courses .= '<strong>Status '.$i.':</strong> '.$form_state['input']['coursename'][$p].'<br/>' . "\r\n";
				$p_req_courses .= '<strong>Course '.$i.' Name:</strong> '.$form_state['input']['coursename'][$p].'<br/>' . "\r\n";
				$p_req_courses .= '<strong>Course '.$i.' Description:</strong> '.$form_state['input']['coursedesc'][$p].'<br/>' . "\r\n";
				$p_req_courses .= '<strong>Course '.$i.' Level:</strong> '.$form_state['input']['courselevel'][$p].'<br/>' . "\r\n";
				$p_req_courses .= '<strong>Course '.$i.' Minimum GPA:</strong> '.$form_state['input']['coursemingpa'][$p].'<br/><br/>' . "\r\n";				
			}
		}
		if (strlen($p_req_courses)) {
			$body .= '<br/><strong style="text-decoration:underline;">Prerequsite Courses for Admission to School:</strong><br/>' . "\r\n";
			$body .= $p_req_courses;
		}	
	}
	
	$body .="<br/><strong>School URL:</strong> https://".$_SERVER['SERVER_NAME'].vcn_drupal7_base_path()."get-qualified/school/".$form_state['input']['provider_unitid'];
			
	$body .= '<br/><strong>Submitted By User:</strong> '. vcn_custom_aes_decrypt($user_name) . ' &#160;&#160; ' . $user_email . ' &#160;&#160; (' . $user_id . ')' . "\r\n";
	
	$mail_body = $body;
	// These value can remain empty.
	$my_module = '';
	$my_mail_token = '';
	
	$from =  $user_email;	//$from = variable_get('system_mail', $user_email);
	
	$header  = 'MIME-Version: 1.0' . "\r\n";
	$header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$header .= "From: $from \r\n";
	$header .= "Reply-To: $from \r\n";
	
	$mail_sent = mail($provider_portal_email, $mail_subject, $mail_body, $header );
	
	/*$message = array(
			'id' => $my_module . '_' . $my_mail_token,
			'to' => $provider_portal_email,
			'subject' => $mail_subject,
			'body' => array($mail_body),
			'headers' => array(
					'From' => $from,
					'Sender' => $from,
					'Return-Path' => $from,
					'MIME-Version' => '1.0',
					//'Content-Type' => 'text/plain; charset=UTF-8; format=flowed; delsp=yes',
					//'Content-Type' => 'text/html; charset=UTF-8; format=flowed; delsp=yes',
					'Content-Type' => 'text/html; charset=iso-8859-1; format=flowed',
					//'Content-Type' => 'text/html; charset=iso-8859-1',
					'Content-Transfer-Encoding' => '8Bit',
					'X-Mailer' => 'Drupal',
			),
	);
	
	$system = drupal_mail_system($my_module, $my_mail_token);
	
	// The format function must be called before calling the mail function.
	$message = $system->format($message);
	
	if ($system->mail($message)) { */
	if($mail_sent == true) {
		// Success.
		drupal_set_message("Provider Info changes email has been sent succesfully.");
	}
	else {
		// Failure.
		drupal_set_message(t('Unable to send e-mail. Contact the site administrator if the problem persists.'), 'error');
	}
	
}
//Provider profile edit page functions END

// Provider REST call by PROGRAMID functions
function vcn_get_provider_detail_by_programid(&$variables) {

	$useCache = vcn_use_cache($variables);

	/* REST Call */
	// get the provider detail info by programid
	$params = array('programid' => $variables['programid']);
	$providerdetail = vcn_rest_wrapper('vcnprovidersvc', 'vcnprovider', 'get-provider-by-programid', $params, 'xml', 'post', $useCache);

	return $providerdetail->providerdetail->item;
	/* End REST Call */
}

function vcn_get_provider_entrance_tests_by_programid(&$variables) {

	$useCache = vcn_use_cache($variables);
	/* REST Call */
	// get the provider entrance tests by programid
	$params = array('programid' => $variables['programid']);
	$providerentrancetests = vcn_rest_wrapper('vcnprovidersvc', 'vcnprovider', 'get-provider-entrance-tests-by-programid', $params, 'xml', 'post', $useCache);

	return $providerentrancetests->providerentrancetests;
	/* End REST Call */
}

function vcn_get_provider_required_courses_by_programid(&$variables) {

	$useCache = vcn_use_cache($variables);
	/* REST Call */
	// get the provider required courses by programid
	$params = array('programid' => $variables['programid']);
	$providerrequiredcourses = vcn_rest_wrapper('vcnprovidersvc', 'vcnprovider', 'get-provider-required-courses-by-programid', $params, 'xml', 'post', $useCache);

	return $providerrequiredcourses->providerrequiredcourses;
	/* End REST Call */
}
// Provider REST call by PROGRAMID functions END

function vcn_get_provider_summary(&$variables) {
	global $user_info;
	$variables['user_schools_array'] = array();

	// need to check to see if GLOBAL ['user_provider_id'] holds all the schools ids
	$schoolsArr = explode(',', $user_info['user_provider_id']);

  $schoolsArr = array_unique($schoolsArr, SORT_REGULAR);
 
  foreach ($schoolsArr as $schoolId) {
    $schoolId = trim($schoolId);

    $params = array('unitid' => $schoolId);
    $providerdetail = vcn_rest_wrapper('vcnprovidersvc', 'vcnprovider', 'get-provider-detail-by-unitid', $params, 'xml', 'post', false);

    $schoolName = $providerdetail->providerdetail->item->name;

    $variables['user_schools_array'][] = array('id' => $schoolId, 'name' => $schoolName);
  }
	
	// set a default unitid if one is not passed in via URL
	if (!isset($variables['unitid']) || !strlen($variables['unitid'])) {
		$variables['unitid'] = $variables['user_schools_array'][0]['id'];
	}

  $_SESSION['provider_selected_unit_id'] = $variables['unitid'];
  
  // want to make sure we dont use cache in case user changes profile or programs
  $variables['useCache'] = false;
  
	$providerDetail = vcn_get_provider_detail($variables);

	$variables['school_id'] = $providerDetail->unitid;
	$variables['school_name'] = $providerDetail->name;
	$variables['school_type'] = $providerDetail->ipedsdesc;
	$variables['percent_applicants_admitted'] = strlen($providerDetail->percentadmittedtotal) ? $providerDetail->percentadmittedtotal : 'N/A';

	$variables['total_students'] = strlen($providerDetail->totalenrollment) ? $providerDetail->totalenrollment : 'N/A';
	$variables['total_undergrads'] = strlen($providerDetail->undergraduateenrollment) ? $providerDetail->undergraduateenrollment : 'N/A';
	$variables['degree_seeking_freshmen'] = strlen($providerDetail->firsttimedegreecertificateundergradenrollment) ? $providerDetail->firsttimedegreecertificateundergradenrollment : 'N/A';
	$variables['graduate_enrollment'] = strlen($providerDetail->graduateenrollment) ? $providerDetail->graduateenrollment : 'N/A';

  $address = '';
  if (strlen($providerDetail->addr)) {
    $address .= $providerDetail->addr . '<br/>';
  }
  if (strlen($providerDetail->city) && strlen($providerDetail->state)) {
    $address .= $providerDetail->city . ', ' . $providerDetail->state;
  }
  if (strlen($providerDetail->zipcode)) {
    $address .= ' ' . $providerDetail->zipcode;
  }
  $variables['school_address']= $address;
  
	// get the programs
  $params = array('unitid' => $variables['unitid'], 'limit' => '5');
	$programs = vcn_rest_wrapper('vcntrainingsvc', 'vcnprograms', 'get-programs-by-unitid', $params, 'json', 'post', false);
  
  $variables['programs_array'] = array();
  
  $variables['programs_table_additional_class'] = 'nodata';
  
  if (count($programs) > 4) {
    $variables['programs_table_additional_class'] = '';
  }
  
  if (count($programs) > 0) {
    foreach ($programs as $program) {
      $variables['programs_array'][] = array(
        'name' => $program->name,
        'awardlevel' => $program->awardlevel,
      );
    }
  }
}

function vcn_get_provider_programs(&$variables) {
  
  drupal_add_js(drupal_get_path('module', 'vcnprovider') .'/js/vcnprovider.js', 'file');
	
  $variables['vcn_base_path'] = base_path();
  $variables['rendered_table'] = '';
  
  // want to make sure we dont use cache in case user changes profile or programs
  $variables['useCache'] = false;
  
  $params = array('unitid' => $variables['unitid']);
	$providerDetail = vcn_rest_wrapper('vcnprovidersvc', 'vcnprovider', 'get-provider-detail-by-unitid', $params, 'xml', 'post', false);

	$variables['school_name'] = $providerDetail->providerdetail->item->name;
      
  $header = array(
    array('data' => 'Program Name', 'class' => 'dtheader'), 
    array('data' => 'Program Description', 'class' => 'dtheader'), 
    array('data' => 'Program Length', 'class' => 'dtheader'), 
    array('data' => 'Award Level', 'class' => 'dtheader'),
    array('data' => 'Action', 'class' => 'dtheader'),
  );
	
  $params = array('unitid' => $variables['unitid']);
	$programs = vcn_rest_wrapper('vcntrainingsvc', 'vcnprograms', 'get-programs-by-unitid', $params, 'json', 'post', false);

  $programCount = 0;
  
	if (isset($programs)) {
    $programCount = count($programs);
    
    if ($programCount > 0) {
      $rows = array();
      foreach ($programs as $program) {
        $program_name = '<strong>' . $program->name . '</strong>';
        $description = (strlen($program->desc)) ? $program->desc.' ...' : 'N/A';

        $awardLevelVal = strlen($program->awardlevel) ? $program->awardlevel : 'N/A';
        $awardLevel = '<div style="display:none">' . $program->awardlevelid . '</div>' . $awardLevelVal;
            
        $rows[] = array(
          'data' => array(
            $program_name, 
            $description, 
            (strlen($program->duration)) ? $program->duration : 'N/A',
            $awardLevel,
            '<input type="button" title="Edit Program" value=" Edit " class="vcn-button" onclick="location.href=\'' . $variables['vcn_base_path'] . 'provider/'.$variables['unitid'].'/program/' . $program->id .'/cipcode/' . $program->cipcode . '\';" />',
          ),
          'no_striping' => TRUE,
        );
      }
    
      $table_variables = array();
      $table_variables['header'] = $header;
      $table_variables['rows'] = $rows;
      $table_variables['attributes'] = array('id' => 'provider-programs-table', 'class' => 'dttable');
      $table_variables['caption'] = null;
      $table_variables['colgroups'] = null;
      $table_variables['sticky'] = null;
      $table_variables['empty'] = null;

      $rendered_table = theme_table($table_variables);

      $variables['rendered_table'] = $rendered_table;    
    }
    
	}
  
  $variables['program_count'] = $programCount;
}


// Program REST call by PROGRAMID functions
function vcn_get_program_detail(&$variables) {

	$useCache = vcn_use_cache($variables);

	/* REST Call */
	// get the Program detail info
	$params = array('programid' => $variables['programid'], 'cipcode' => $variables['cipcode'], 'cipcodeyear' => $variables['cipcodeyear']);
	$programdetail = vcn_rest_wrapper('vcntrainingsvc', 'vcnprograms', 'get-program-detail', $params, 'xml', 'post', $useCache);

	return $programdetail->programdetail->item;
	/* End REST Call */

}

// Program REST call by PROGRAMID functions
function vcn_get_program_detail_for_provider(&$variables) {

	$useCache = vcn_use_cache($variables);

	/* REST Call */
	// get the Program detail info
	$params = array('programid' => $variables['programid'], 'unitid' => $variables['unitid'], 'cipcode' => $variables['cipcode'], 'cipcodeyear' => $variables['cipcodeyear']);
	$programdetail = vcn_rest_wrapper('vcntrainingsvc', 'vcnprograms', 'get-program-detail', $params, 'xml', 'post', $useCache);

	return $programdetail->programdetail->item;
	/* End REST Call */

}

function vcn_get_program_entrance_tests(&$variables) {

	$useCache = vcn_use_cache($variables);

	/* REST Call */
	// get the Program detail info
	$params = array('programid' => $variables['programid']);
	$programentrancetests = vcn_rest_wrapper('vcntrainingsvc', 'vcnprograms', 'get-program-entrance-tests', $params, 'xml', 'post', $useCache);

	return $programentrancetests->programentrancetests;
	/* End REST Call */

}

function vcn_get_program_required_courses(&$variables) {

	$useCache = vcn_use_cache($variables);

	/* REST Call */
	// get the Program detail info
	$params = array('programid' => $variables['programid'], 'cipcode' => $variables['cipcode'], 'cipcodeyear' => $variables['cipcodeyear']);
	$programrequiredcourses = vcn_rest_wrapper('vcntrainingsvc', 'vcnprograms', 'get-program-required-courses', $params, 'xml', 'post', $useCache);

	return $programrequiredcourses->programrequiredcourses;
	/* End REST Call */

}


function vcn_get_curriculum_required_courses(&$variables) {

	$useCache = vcn_use_cache($variables);

	/* REST Call */
	// get the Program detail info
	$params = array('programid' => $variables['programid'], 'cipcode' => $variables['cipcode'], 'cipcodeyear' => $variables['cipcodeyear']);
	$programcurriculumcourses = vcn_rest_wrapper('vcntrainingsvc', 'vcnprograms', 'get-program-curriculum-courses', $params, 'xml', 'post', $useCache);
	return $programcurriculumcourses;
	/* End REST Call */

}

function vcn_get_program_required_education(&$variables) {

	$useCache = vcn_use_cache($variables);

	/* REST Call */
	// get the Program detail info
	$params = array('programid' => $variables['programid']);
	$programrequirededucation = vcn_rest_wrapper('vcntrainingsvc', 'vcnprograms', 'get-program-required-education', $params, 'xml', 'post', $useCache);

	return $programrequirededucation->programrequirededucation;
	/* End REST Call */

}

function vcn_get_program_accredited(&$variables) {

	$useCache = vcn_use_cache($variables);

	/* REST Call */
	// get the Program detail info
	$params = array('programid' => $variables['programid']);
	$programaccredited = vcn_rest_wrapper('vcntrainingsvc', 'vcnprograms', 'get-program-accredited', $params, 'xml', 'post', $useCache);

	return $programaccredited->programaccredited;
	/* End REST Call */

}
// Program REST call by PROGRAMID functions END
function vcn_get_edu_cat_iped(&$variables) {
	
	/* REST Call */
	// get the Education category IPED (Award level) list 
	$params = array();
	$ipedcategories = vcn_rest_wrapper('vcnlookupsvc', 'vcnlookup', 'get-education-iped-category', $params);
	return $ipedcategories->categories;
	/* End REST Call */

}

function vcn_get_cipcode_list(&$variables) {

	/* REST Call */
	// get cipcode list
	$params = array('cipcodeyear' => $variables['cipcodeyear']);
	$cipcodelist = vcn_rest_wrapper('vcntrainingsvc', 'vcnprograms', 'get-cipcode-list', $params);
	return $cipcodelist->cipcodelist;
	/* End REST Call */

}

function vcn_provider_program_tabs(&$variables) {

	//Program Overview , Program Requirements ,	General Admission Requirements , School Information , Entrance Tests , Required courses
	$vcn_tabs_list = array();
	$vcn_tabs_list[] = array("pover","Overview ");
	$vcn_tabs_list[] = array("currcou","Curriculum<br/>Courses");
	$vcn_tabs_list[] = array("sinfo","School / Cost / <br/>Financial aid ");
	$vcn_tabs_list[] = array("preq","Program<br/>Requirements");
	$vcn_tabs_list[] = array("enttest","Entrance<br/>Tests");
	$vcn_tabs_list[] = array("preqcou","Prerequisite<br/>Courses");
	$vcn_tabs_list[] = array("pareq","Application<br/>Support");
	$vcn_tabs_list[] = array("otherreq","Other<br/>Information");

	$variables['vcn_tabs_list'] = $vcn_tabs_list;
	$variables['vcn_tabs_keys'] = array("pover","currcou","sinfo","preq","enttest","preqcou","pareq","otherreq");

	$variables['vcn_tabs_header'] = vcn_tabs_header($variables['vcn_tabs_list'], $variables['vcn_tabs_keys']);

	$variables['vcn_tabs_body_start'] = vcn_tabs_body_start();
	$variables['vcn_tabs_body_id_prefix'] = vcn_tabs_body_id_prefix();
	$variables['vcn_tabs_body_end'] = vcn_tabs_body_end();

	return true;
}

function vcn_provider_program(&$variables) {

	$programdetails = $variables['programdetails'];
	$programentrancetests = $variables['programentrancetests'];
	$programreqcourses = $variables['programrequiredcourses'];
	$programreqeducation = $variables['programrequirededucation'];
	$programaccredited = $variables['programaccredited'];
	$programcurrcourses = $variables['programcurriculumcourses'];

	$providerdetail = $variables['providerdetail'];
	$providerentrancetests = $variables['providerentrancetests'];
	$providerrequiredcourses = $variables['providerrequiredcourses'];

	
	// Program detail
	if (vcn_chk_not_null($programdetails->programurl) && $programdetails->programurlflag) {
		$variables['programurl'] = vcn_build_link_window_opener($programdetails->programurl, $programdetails->programname, false, true, "", "strong");
	}
	$variables['programdesc'] = vcn_display_formatted_text($programdetails->programdescription);
	
	if (vcn_chk_not_null($programdetails->admissionurl) && $programdetails->admissionurlflag) {
		$variables['admissionurl'] = vcn_build_link_window_opener($programdetails->admissionurl, $programdetails->admissionurl, false, true, "", "strong extlink");
	}

	if (vcn_chk_not_null($programdetails->programcontactname)) {
		$variables['programcontactname'] = $programdetails->programcontactname;
	}
	if (vcn_chk_not_null($programdetails->programcontactemail)) {
		$variables['programcontactemail'] = $programdetails->programcontactemail;
	}
	if (vcn_chk_not_null($programdetails->programcontactphone)) {
		$variables['programcontactphone'] = $programdetails->programcontactphone;
	}
	if (vcn_chk_not_null($programdetails->ipedcatname)) {
		$variables['awlevel'] = $programdetails->ipedcatname;
	}
	if (vcn_chk_not_null($programdetails->duration)) {
		$variables['plength'] = (string)$programdetails->duration;
	}
	if (vcn_chk_not_null($programdetails->totalcredits)) {
		$variables['totalcredits'] = $programdetails->totalcredits;
	}
	/*if (vcn_chk_not_null($programdetails->totalcourses)) {
		$variables['totalcourses'] = $programdetails->totalcourses;
	}*/
	if (vcn_chk_not_null($programdetails->tuitioninstateindistrict)) {
		$variables['tuitioninstateindistrict'] = (string)$programdetails->tuitioninstateindistrict;
	}
	if (vcn_chk_not_null($programdetails->tuitioninstateoutdistrict)) {
		$variables['tuitioninstateoutdistrict'] = (string)$programdetails->tuitioninstateoutdistrict;
	}
	if (vcn_chk_not_null($programdetails->tuitiononline)) {
		$variables['tuitiononline'] = (string)$programdetails->tuitiononline;
	}
	if (vcn_chk_not_null($programdetails->tuitionoutstate)) {
		$variables['tuitionoutstate'] = (string)$programdetails->tuitionoutstate;
	}
	if (vcn_chk_not_null($programdetails->othercost)) {
		$variables['othercost'] = (string)$programdetails->othercost;
	}
	if (vcn_chk_not_null($programdetails->online)) {
		$variables['online'] = (string)$programdetails->online;
	}
	if (vcn_chk_not_null($programdetails->othercost)) {
		$variables['othercost'] = (string)$programdetails->othercost;
	}
	if (vcn_chk_not_null($programdetails->otherrequirements)) {
		$variables['otherrequirements'] = (string)$programdetails->otherrequirements;
	} else {
		$variables['otherrequirements'] = 'N/A';
	}

	/*if (count($programaccredited->item) > 0) {
		$variables['paccredited'] = "";
		foreach ($programaccredited->item as $creditor) {
			$variables['paccredited'] .= "<div>".$creditor->name."</div>";
		}
	}*/
	
	if (vcn_chk_not_null($programdetails->howtoapply)) {
		$variables['howtoapply'] = vcn_display_formatted_text(vcn_convert_urls_to_links((string)$programdetails->howtoapply));
	}
	
	if (vcn_chk_not_null($programdetails->hsgradreq)) {
		$variables['hsgradreq'] = $programdetails->hsgradreq;
	}
	if (vcn_chk_not_null($programdetails->mingpa)) {
		$variables['mingpa'] = $programdetails->mingpa;
	}
	if (vcn_chk_not_null($programdetails->gedaccepted)) {
		$variables['gedaccepted'] = $programdetails->gedaccepted;
	}
	if (vcn_chk_not_null($programdetails->medicalreq)) {
		$variables['medicalreq'] = vcn_display_formatted_text(vcn_convert_urls_to_links((string)$programdetails->medicalreq));
	}
	if (vcn_chk_not_null($programdetails->immunizationreq)) {
		$variables['immunizationreq'] = vcn_display_formatted_text(vcn_convert_urls_to_links((string)$programdetails->immunizationreq));
	}
	if (vcn_chk_not_null($programdetails->legalreq)) {
		$variables['legalreq'] = vcn_display_formatted_text(vcn_convert_urls_to_links((string)$programdetails->legalreq));
	}

	//Program required education
	if (count($programreqeducation->item) > 0) {
		$variables['reqedu'] = "";
		foreach ($programreqeducation->item as $education) {
			$variables['reqedu'] .= '<div class="program-edu-req allclear">';
			$variables['reqedu'] .= '<div><b>'.$education->name.'</b>&nbsp;</div>';
			$variables['reqedu'] .= '<div>Minimum GPA('.$education->mingpa.')&nbsp;</div>';
				
			if ((string)$education->educationlevel == 2) {
				$variables['reqedu'] .= '<div><a class="small"  href="'.$variables['vcn_d7_path'].'online-courses/take-online?state=A">Virtual High Schools</a>&nbsp;</div>';
			} else {
				$variables['reqedu'] .= '<div>&nbsp;</div>';
			}
				
			$variables['reqedu'] .= '</div>';
		}
	}else {
		$variables['reqedu_na'] = 'N/A';
	}

	//Program Entrance Tests
	/*if (count($programentrancetests->item) > 0) {
		$variables['entrancetests_prog'] = '<div class="strong allclear">Program Entrance Tests:<br/></div>
											<div class="program-edu-req strong allclear">
												<div>&nbsp;</div>
												<div>Minimum Score</div>
												<div>Test Description</div>
											</div>';
		foreach ($programentrancetests->item as $test) {
			if (strlen($test->minscore) && (strtoupper($test->minscore) == 'NONE' || strtoupper($test->minscore) == 'N/A' || strtoupper($test->minscore) == 'NA')) {
				$testminscore = '';
			} else {
				$testminscore = $test->minscore;
			}
			$type = ($test->hsgradortransferstudent == 'T') ? 'T': 'H';
			if (vcn_chk_not_null($test->testurl)) {
				$testurl = $variables['vcn_d7_path'].(string)$test->testurl;
				$tname =  '<a href="'.$testurl.'">' . $test->testname . '</a>';
			} else {
				$tname = $test->testname;
			}
				
			$variables['entrancetests_prog'] .= '<div class="program-edu-req allclear">';
			$variables['entrancetests_prog'] .= '<div><b>'.$tname.'</b>&nbsp;</div>';
			$variables['entrancetests_prog'] .= '<div>'.$testminscore.'&nbsp;</div>';
			$variables['entrancetests_prog'] .= '<div>'.$test->testdescription.'&nbsp;</div>';
			$variables['entrancetests_prog'] .= '</div>';
				
		}
	} else {
		$variables['entrancetests_prog_na'] = 'N/A';
	}*/
	
	
	$variables['entrancetests_prog'] = '<div class="entrance-tests-row allclear"></div>';
	if (count($programentrancetests->item) > 0) {
		foreach ($programentrancetests->item as $test) {
				
			if (strlen($test->minscore) && (strtoupper($test->minscore) == 'NONE' || strtoupper($test->minscore) == 'N/A' || strtoupper($test->minscore) == 'NA')) {
				$testminscore = '';
			} else {
				$testminscore = $test->minscore;
			}
				
			$variables['entrancetests_prog'] .= '<div class="entrance-tests-row allclear">
												<div><label><input type="text" class="testname" name="testname[]" value="'.$test->testname.'"></label></div>
												<div><label><input type="text" name="testdesc[]" value="'.$test->testdescription.'"></label></div>
												<div><label><input type="text" name="testminscore[]" value="'.$testminscore.'"></label></div>
												<div><input type="button" title="Delete" class="ent-tests-delete-this-row vcn-button" value="Delete" />
														<input type="hidden" name="testid[]" value="'.$test->testid.'">
												</div>
											</div>';
		}
	
	}


	//Provider Entrance Tests
	$entrancetests_prov_array = array();
	if (count($providerentrancetests->item) > 0) {
		/*$variables['entrancetests_prov'] = '<div class="strong allclear">Provider Entrance Tests:<br/></div>
											<div class="program-edu-req strong allclear">
												<div>&nbsp;</div>
												<div>Minimum Score</div>
												<div>Test Description</div>
											</div>';*/
		foreach ($providerentrancetests->item as $test) {
			if (strlen($test->minscore) && (strtoupper($test->minscore) == 'NONE' || strtoupper($test->minscore) == 'N/A' || strtoupper($test->minscore) == 'NA')) {
				$testminscore = '';
			} else {
				$testminscore = $test->minscore;
			}
			$type = ($test->hsgradortransferstudent == 'T') ? 'T': 'H';
			if (vcn_chk_not_null($test->testurl)) {
				$testurl = $variables['vcn_d7_path'].(string)$test->testurl;
				$tname =  '<a href="'.$testurl.'">' . $test->testname . '</a>';
			} else {
				$tname = $test->testname;
			}
				
			/*$variables['entrancetests_prov'] .= '<div class="program-edu-req allclear">';
			$variables['entrancetests_prov'] .= '<div><b>'.$tname.'</b>&nbsp;</div>';
			$variables['entrancetests_prov'] .= '<div>'.$testminscore.'&nbsp;</div>';
			$variables['entrancetests_prov'] .= '<div>'.$test->testdescription.'&nbsp;</div>';
			$variables['entrancetests_prov'] .= '</div>';*/
			
			$entrancetests_prov_array[] = array(
					'tname' => '<b>'.(string)$tname.'</b>',
					'tminscore' => (string)$testminscore,
					'tdesc' => (string)$test->testdescription,
			);
				
		}
	} else {
		$variables['entrancetests_prov_na'] = 'N/A';
	}
	$entrancetests_prov_json = json_encode($entrancetests_prov_array);
	drupal_add_js(array('vcnprovider_provider_entrancetests' => array('provider_entrancetests_data' => $entrancetests_prov_json)),array('type' => 'setting'));
	

	//Program Required courses
	/*if (count($programreqcourses->item) > 0 && $programreqcourses->item[0]->coursetitle != "") {
		$variables['requiredcourses_prog'] = '<div class="strong allclear">Program Prerequisite Courses:<br/></div>
											  <div class="pro-required-courses strong allclear">
												  <div>&nbsp;</div>
												  <div>Course Level</div>
												  <div>Minimum GPA</div>
											  </div>';
		foreach ($programreqcourses->item as $requiredcourses) {
			if (($requiredcourses->courseid) > 0) {
				switch (strtoupper($requiredcourses->courselevel)) {
					case 'C':
						$level = 'College';
						break;
					case 'H':
						$level = 'High School';
						break;
					case 'H or C':
						$level = 'High School or College';
						break;
					case 'B':
						$level = 'Bachelors';
						break;
					default:
						$level = $requiredcourses->courselevel;
						break;
				}
				$divider = '';
				if(strlen($requiredcourses->description) > 0) {
					$divider = ' - ';
				}
				$variables['requiredcourses_prog'] .= '<div class="pro-required-courses allclear">';
				$variables['requiredcourses_prog'] .= '<div><b>'.$requiredcourses->coursetitle.'</b>'.$divider.$requiredcourses->description.'&nbsp;</div>';
				$variables['requiredcourses_prog'] .= '<div>'.$level.'&nbsp;</div>';
				$variables['requiredcourses_prog'] .= '<div>'.$requiredcourses->mingpa.'&nbsp;</div>';
				$variables['requiredcourses_prog'] .= '</div>';
			}
		}
	} else {
		$variables['requiredcourses_prog_na'] = 'N/A';
	}*/
	
	$variables['requiredcourses_prog'] = '<div class="required-courses-row allclear"></div>';
	if (count($programreqcourses->item) > 0) {
		foreach ($programreqcourses->item as $requiredcourses) {
			if (($requiredcourses->courseid) > 0) {
				switch (strtoupper($requiredcourses->courselevel)) {
					case 'C':
						$level = 'College';
						break;
					case 'H':
						$level = 'High School';
						break;
					case 'H or C':
						$level = 'High School or College';
						break;
					case 'B':
						$level = 'Bachelors';
						break;
					default:
						$level = $requiredcourses->courselevel;
						break;
				}
				$divider = '';
				if(strlen($requiredcourses->description) > 0) {
					$divider = ' - ';
				}
	
				$variables['requiredcourses_prog'] .= '<div class="required-courses-row allclear">
													<div><label><input type="text" class="coursename" name="coursename[]" value="'.$requiredcourses->coursetitle.'"></label></div>
													<div><label><input type="text" name="coursedesc[]" value="'.$requiredcourses->description.'"></label></div>
													<div><label><input type="text" name="courselevel[]" value="'.$level.'"></label></div>
													<div><label><input type="text" name="coursemingpa[]" value="'.$requiredcourses->mingpa.'"></label></div>
													<div><input type="button" title="Delete" class="req-courses-delete-this-row vcn-button" value="Delete" />
															<input type="hidden" name="courseid[]" value="'.$requiredcourses->courseid.'">
													</div>
												</div>';
			}
		}
	}
	
	//Program Curriculum Courses
	$variables['curriculumcourses_prog'] = '<div class="curriculum-courses-row allclear"></div>';
	if (count($programcurrcourses->item) > 0) {
		foreach($programcurrcourses->item as $curriculumcourse) {
			if ($curriculumcourse->courseid > 0) {
				$divider = '';
				if(strlen($curriculumcourse->description) > 0) {
					$divider = ' - ';
				}
				$variables['curriculumcourses_prog'] .= '<div class="curriculum-courses-row allclear allclear">
													<div><label><input type="text" class="currcoursename" name="currcoursename[]" value="'.$curriculumcourse->coursetitle.'"></label></div>
													<div><label><input type="text" name="currcoursedesc[]" value="'.$curriculumcourse->coursedescription.'"></label></div>
													<div><label><input type="text" name="currcourseduration[]" value="'.$curriculumcourse->courseduration.'"></label></div>
													<div><label><input type="text" name="currcoursetotalcredits[]" value="'.$curriculumcourse->coursetotalcredits.'"></label></div>
													<div><input type="button" title="Delete" class="req-courses-delete-this-row vcn-button" value="Delete" />
															<input type="hidden" name="currcourseid[]" value="'.$curriculumcourse->courseid.'">
													</div>
												</div>';
			}
		}
	}

	//Provider Required courses
	$providerreqcourses_array = array();
	if (count($providerrequiredcourses->item) > 0 && $providerrequiredcourses->item[0]->coursetitle != "") {
		/*$variables['requiredcourses_prov'] = '<div class="strong allclear">Provider Prerequisite Courses:<br/></div>
											  <div class="school-required-courses strong allclear">
												  <div>&nbsp;</div>
												  <div>Course Level</div>
												  <div>Minimum GPA</div>
											  </div>';*/
		foreach ($providerrequiredcourses->item as $requiredcourses) {
			if (($requiredcourses->courseid) > 0) {
				switch (strtoupper($requiredcourses->courselevel)) {
					case 'C':
						$level = 'College';
						break;
					case 'H':
						$level = 'High School';
						break;
					case 'H or C':
						$level = 'High School or College';
						break;
					case 'B':
						$level = 'Bachelors';
						break;
					default:
						$level = $requiredcourses->courselevel;
						break;
				}
				$divider = '';
				if(strlen($requiredcourses->description) > 0) {
					$divider = ' - ';
				}
				/*$variables['requiredcourses_prov'] .= '<div class="school-required-courses allclear">';
				$variables['requiredcourses_prov'] .= '<div><b>'.$requiredcourses->coursetitle.'</b>'.$divider.$requiredcourses->description.'&nbsp;</div>';
				$variables['requiredcourses_prov'] .= '<div>'.$level.'&nbsp;</div>';
				$variables['requiredcourses_prov'] .= '<div>'.$requiredcourses->mingpa.'&nbsp;</div>';
				$variables['requiredcourses_prov'] .= '</div>';*/
				
				$providerreqcourses_array[] = array(
						'cname' => '<b>'.(string)$requiredcourses->coursetitle.'</b>'.$divider.(string)$requiredcourses->description,
						'level' => (string)$level,
						'mingpa' => (string)$requiredcourses->mingpa,
				);
			}
		}
	} else {
		$variables['requiredcourses_prov_na'] = 'N/A';
	}
	$providerreqcourses_json = json_encode($providerreqcourses_array);
	drupal_add_js(array('vcnprovider_providerreqcourses' => array('providerreqcourses_data' => $providerreqcourses_json)),array('type' => 'setting'));
	

	//Provider detail
	$variables['providerdetail'] = vcn_program_provider_detail_format($providerdetail);

	if (strlen(trim($providerdetail->adminurl))>4 && $providerdetail->adminurlflag) {
		$variables['applurl'] = vcn_build_link_window_opener($providerdetail->admissionurl, "Admissions", false, true);
	}
		
	if (strlen(trim($providerdetail->faidurl))>4 && $providerdetail->faidurlflag) {
		$variables['faidurl'] = vcn_build_link_window_opener($providerdetail->faidurl, "Financial Aid (For this school)", false, true);
	}

	/*if (vcn_chk_not_null($providerdetail->transferpolicy)) {
		$variables['transferpolicy'] = (string)$providerdetail->transferpolicy;
		if (vcn_chk_not_null($providerdetail->mingpafortransfer)) {
			$variables['mingpafortransfer'] = (string)$providerdetail->mingpafortransfer;
		}
	}*/

	return true;
}

function vcn_chk_not_null($value) {
	if ((string)$value == 'NULL' || trim((string)$value) == '') {
		return false;
	}
	return true;
}

function vcn_program_provider_detail_format($providerdetail) {

	$html = "";
	$url = vcn_drupal7_base_path()."get-qualified/school/".$providerdetail->unitid;
	$html .= vcn_build_link_window_opener($url, $providerdetail->name, false, false ,"", "strong", false);

	if (strlen($providerdetail->addr) > 0) {
		$html .= '<br/>' . $providerdetail->addr;
	}

	if (strlen($providerdetail->city) && strlen($providerdetail->state)) {
		$html .= '<br/>' . $providerdetail->city . ', ' . $providerdetail->state;
	}

	if (strlen($providerdetail->zipcode) > 0) {
		if (!strlen($providerdetail->addr) && !strlen($providerdetail->city) && !strlen($providerdetail->state)) {
			$html .= '<br/>';
		}
		$html .= ' ' . $providerdetail->zipcode;
	}

	if (strlen($providerdetail->phone)) {
		$html .= '<br/>' . vcn_format_phone($providerdetail->phone);;
	}
	return $html;

}

function vcnprovider_program_form_validate($form, &$form_state) {

	$task = $form_state['input']['program_task'];
	
	if($task != "delete") {
		if (trim($form_state['input']['program_name']) == "") {
			form_set_error('program_name', t('Please enter a Program Name.'));
		}
		if (!is_numeric(trim($form_state['input']['program_contact_phone'])) && trim($form_state['input']['program_contact_phone']) != "") {
			form_set_error('program_contact_phone', t('Phone number must contain a valid numeric values only.'));
		}
	}

}


function vcnprovider_program_form_submit($form, &$form_state) {

	//dsm($form_state); $form_state['redirect'] = FALSE; 

	// Update Provider Detail
	vcn_program_insert_delete_update($form_state);	
}


function vcn_program_insert_delete_update(&$form_state) {
	
	$user = vcn_get_user_info();
	$updatedby = $user['uid'];
	
	$task = $form_state['input']['program_task'];
	
	$unitid = $form_state['input']['provider_unitid'];
	$programid = $form_state['input']['program_id'];
	$cipcode = $form_state['input']['program_cipcode'];
	$cipcodeyear = $form_state['input']['program_cipcodeyear'];
	
	if($task == "delete") {
		
		// Delete program
		$params = array('rand' => rand(5, 5), 'programid' => $programid);		
		$delete_info = vcn_rest_wrapper('vcntrainingsvc', 'vcnprograms', 'delete-program', $params, 'xml', 'post', false);
		$delete_status = (bool)$delete_info->item;
		
		drupal_set_message("Program has been deleted succesfully.");
				
		// Send mail to Provider admin.
		vcn_provider_program_mail_send($form_state);
		
		//redirect to program list page
		$redirecturl = 'provider/programs/unitid/'.$unitid;
		drupal_goto($redirecturl);
		
	}else{
		//Update/Add program
		//VALUES (:updatedby,Now(),:unitid,:programname,:awlevel,:programlength,:programurl,
		//:programdesc,:programcontactname,:programcontactemail,:programcontactphone)";
			
		// Insert/Update program details at programs and cipcode table
		$params = array('rand' => rand(5, 5), 'task' => $task, 'updatedby' => $updatedby, 'unitid' => $unitid
						, 'programid' => $programid, 'cipcode' => $cipcode, 'cipcodeyear' => $cipcodeyear
						, 'programname' =>  $form_state['input']['program_name'], 'awlevel' =>  $form_state['input']['program_award_level']
						, 'totalcredits' =>  $form_state['input']['program_total_credits']
						, 'programlength' =>  $form_state['input']['program_length'], 'programurl' =>  $form_state['input']['program_url']
						, 'programdesc' =>  $form_state['input']['program_desc'], 'programotherrequirements' =>  $form_state['input']['program_other_requirements']
						, 'programhowtoapply' =>  $form_state['input']['program_howtoapply'], 'programmedicalreq' =>  $form_state['input']['program_medicalreq']
						, 'programimmunizationreq' =>  $form_state['input']['program_immunizationreq'], 'programlegalreq' =>  $form_state['input']['program_legalreq']
						, 'programcontactname' =>  $form_state['input']['program_contact_name']
						, 'programcontactemail' =>  $form_state['input']['program_contact_email'], 'programcontactphone' =>  $form_state['input']['program_contact_phone']
						, 'programhsgradreq' =>  $form_state['input']['program_hsgradreq'], 'programmingpa' =>  $form_state['input']['program_mingpa']
						, 'programgedaccepted' =>  $form_state['input']['program_gedaccepted'], 'programtuitioninstateindistrict' =>  $form_state['input']['program_tuitioninstateindistrict']
						, 'programtuitioninstateoutdistrict' =>  $form_state['input']['program_tuitioninstateoutdistrict'], 'programtuitiononline' =>  $form_state['input']['program_tuitiononline']
						, 'programtuitionoutstate' =>  $form_state['input']['program_tuitionoutstate'], 'programothercost' =>  $form_state['input']['program_othercost']
						, 'programadmissionurl' => $form_state['input']['program_admissionurl']
					);
		
		$update_program = vcn_rest_wrapper('vcntrainingsvc', 'vcnprograms', 'update-program-info', $params, 'xml', 'post', false);
		$update_program_id = (int)$update_program->item;	
		
		if($task == "add") {
			// Update program id to use at Entrance test and Required courses update calls
			$form_state['input']['program_id'] = $update_program_id;
			$programid = $update_program_id; 
		}
		
		// Update Entrance Test
		$oldtestidlist = "";
		$programentrancetests = $form_state["complete form"]["#programentrancetests"];
		if (count($programentrancetests->item) > 0) {
			$old_testid_list = array();
			foreach ($programentrancetests->item as $test) {
				$old_testid_list[] = $test->testid;
			}
			$oldtestidlist = join(",", $old_testid_list);
		}
		
		if(@count($form_state['input']['testid']) > 0 || count($programentrancetests->item) > 0) {
			$newtestidcount = @count($form_state['input']['testid']);
		
			$params_entrance_tests = array('rand' => rand(5, 5), 'task' => $task, 'programid' => $programid, 'updatedby' => $updatedby, 'oldtestidlist' => $oldtestidlist, 'newtestidcount' => $newtestidcount,
					'testnamelist' => @$form_state["input"]["testname"], 'testdesclist' => @$form_state['input']['testdesc'], 'testminscorelist' => @$form_state['input']['testminscore']);
		
			$update_entrance_tests = vcn_rest_wrapper('vcntrainingsvc', 'vcnprograms', 'update-program-entrance-tests', $params_entrance_tests, 'xml', 'post', false);
			$update_entrance_tests_status = (int) ($update_entrance_tests->item[0]);

		}
		// Update Required Courses
		$oldcourseidlist = "";
		$programrequiredcourses = $form_state["complete form"]["#programrequiredcourses"];
		if (count($programrequiredcourses->item) > 0) {
			$old_courseid_list = array();
			foreach ($programrequiredcourses->item as $course) {
				$old_courseid_list[] = $course->courseid;
			}
			$oldcourseidlist = join(",", $old_courseid_list);
		}
		
		if(@count($form_state['input']['courseid']) > 0 || count($programrequiredcourses->item) > 0) {
			$newcourseidcount = @count($form_state['input']['courseid']);
			if(isset($form_state['input']['courselevel']) && count($form_state['input']['courselevel']) > 0) {
				$cl=0;
				foreach ($form_state['input']['courselevel'] as $courselevel) {
					switch ($courselevel) {
						case 'College':
							$level = 'C';
							break;
						case 'High School':
							$level = 'H';
							break;
						case 'High School or College':
							$level = 'H or C';
							break;
						case 'Bachelors':
							$level = 'B';
							break;
						default:
							$level = $courselevel;
							break;
					}
					$form_state['input']['courselevel'][$cl] = $level;
					$cl++;
				}
			}
		
			$params_req_courses = array('rand' => rand(5, 5), 'task' => $task, 'unitid' => $unitid, 'programid' => $programid, 'updatedby' => $updatedby, 'oldcourseidlist' => $oldcourseidlist, 'newcourseidcount' => $newcourseidcount,
					'coursenamelist' => @$form_state["input"]["coursename"], 'coursedesclist' => @$form_state['input']['coursedesc'],
					'courselevellist' => @$form_state['input']['courselevel'], 'coursemingpalist' => @$form_state['input']['coursemingpa']);
		
			$update_req_courses = vcn_rest_wrapper('vcntrainingsvc', 'vcnprograms', 'update-program-required-courses', $params_req_courses, 'xml', 'post', false);
			$update_req_courses_status = (int) $update_req_courses->item[0];
			
		}
		
		//Update Curriculum courses
		$oldcurrcourseidlist = "";
		$programcurriculumcourses = $form_state["complete form"]["#programcurriculumcourses"];
		if (count($programcurriculumcourses->item) > 0) {
			$old_currcourseid_list = array();
			foreach ($programcurriculumcourses->item as $course) {
				$old_currcourseid_list[] = $course->courseid;
			}
			$oldcurrcourseidlist = join(",", $old_currcourseid_list);
		}
		
		if(@count($form_state['input']['currcourseid']) > 0 || count($programcurriculumcourses->item) > 0) {
			$newcurrcourseidcount = @count($form_state['input']['currcourseid']);
			$cl=0;
			foreach ($form_state['input']['courselevel'] as $courselevel) {
				switch ($courselevel) {
					case 'College':
						$level = 'C';
						break;
					case 'High School':
						$level = 'H';
						break;
					case 'High School or College':
						$level = 'H or C';
						break;
					case 'Bachelors':
						$level = 'B';
						break;
					default:
						$level = $courselevel;
						break;
				}
				$form_state['input']['courselevel'][$cl] = $level;
				$cl++;
			}
			
			$params_curr_courses = array('rand' => rand(5, 5), 'task' => $task, 'unitid' => $unitid, 'programid' => $programid, 'updatedby' => $updatedby, 'oldcurrcourseidlist' => $oldcurrcourseidlist, 'newcurrcourseidcount' => $newcurrcourseidcount,
					'currcoursenamelist' => @$form_state["input"]["currcoursename"], 'currcoursedesclist' => @$form_state['input']['currcoursedesc'],
					'currcourseduration' => @$form_state['input']['currcourseduration'], 'currcoursetotalcredits' => @$form_state['input']['currcoursetotalcredits']);
			
			$update_curr_courses = vcn_rest_wrapper('vcntrainingsvc', 'vcnprograms', 'update-program-curriculum-courses', $params_curr_courses, 'xml', 'post', false);
			$update_curr_courses_status = (int) $update_curr_courses->item[0];
			
		}
		
		drupal_set_message("Program has been updated succesfully.");
		
		// Send mail to Provider admin.
		vcn_provider_program_mail_send($form_state);	

		
		//redirect to program's new edit page ( with latest program id and edited cipcode
		$redirecturl = 'provider/'.$unitid.'/program/'.$programid.'/cipcode/'.$cipcode;
		//drupal_set_message($redirecturl);
		drupal_goto($redirecturl);
		
	}
	
}


function vcn_provider_program_mail_send(&$form_state) {

	// get Provider's Email Id
	$params = array('prop_id' => 2);
	$content = vcn_rest_wrapper('vcnpropertiessvc', 'vcnproperties', 'get-properties', $params);
	$provider_portal_email = (string) $content->properties->item->value;
	/* End REST Call */
	
	$user = vcn_get_user_info();
	$user_id = $user['uid'];
	$user_name = $user['name'];
	$user_email =$user['mail'];	
	
	$task = $form_state['input']['program_task'];
	
	if ($task == 'add') {
		$mail_subject = 'VCN Program Changes - Addition';
		$program_status = 'Added';
	}else if ($task == 'delete') {
		$mail_subject = 'VCN Program Changes - Deletion';		
		$program_status = 'Deleted';
	}else{
		$mail_subject = 'VCN Program Changes - Update';	
		$program_status = 'Updated';
	}
	
	$programid = $form_state['input']['program_id'];
	$cipcode = $form_state['input']['program_cipcode'];
	
	$body = "";	
	$body .= '<strong style="text-decoration:underline;">Program Information:</strong><br/>' . "\r\n";
	$body .= '<strong>Provider Id: </strong> '.$form_state['input']['provider_unitid'].'<br/>' . "\r\n";
	$body .= '<strong>Program Id: </strong> '.$programid.'<br/>' . "\r\n";
	$body .= '<strong>Program Status: </strong> '.$program_status.'<br/>' . "\r\n";
	$body .= '<strong>Program Name:</strong> '.$form_state['input']['program_name'].'<br/>' . "\r\n";
	$body .= '<strong>Award Level:</strong> '.$form_state['input']['program_award_level'].'<br/>' . "\r\n";
	$body .= '<strong>Total Credits:</strong> '.$form_state['input']['program_total_credits'].'<br/>' . "\r\n";
	$body .= '<strong>Cipcode:</strong> '.$form_state['input']['program_cipcode'].'<br/>' . "\r\n";
	$body .= '<strong>Program Length:</strong> '.$form_state['input']['program_length'].'<br/>' . "\r\n";
	$body .= '<strong>Program URL:</strong> '.$form_state['input']['program_url'].'<br/>' . "\r\n";
	$body .= '<strong>Program Description:</strong> '.$form_state['input']['program_desc'].'<br/>' . "\r\n";
	$body .= '<strong>Program Contact Name:</strong> '.$form_state['input']['program_contact_name'].'<br/>' . "\r\n";
	$body .= '<strong>Program Contact Email:</strong> '.$form_state['input']['program_contact_email'].'<br/>' . "\r\n";
	$body .= '<strong>Program Contact Phone:</strong> '.$form_state['input']['program_contact_phone'].'<br/>' . "\r\n";

	if(isset($form_state['input']['testid']) && count($form_state['input']['testid']) > 0) {
		$p_ent_tests = '';
		for($p=0,$i=1;$p<count($form_state['input']['testid']);$p++,$i++) {
			if(trim($form_state['input']['testname'][$p]) != "") {
				//$p_ent_tests .= '<strong>Status '.$i.':</strong> '.$form_state['input']['testname'][$p].'<br/>' . "\r\n";
				$p_ent_tests .= '<strong>Test '.$i.' Name:</strong> '.$form_state['input']['testname'][$p].'<br/>' . "\r\n";
				$p_ent_tests .= '<strong>Test '.$i.' Description:</strong> '.$form_state['input']['testdesc'][$p].'<br/>' . "\r\n";
				$p_ent_tests .= '<strong>Test '.$i.' Minimum Score:</strong> '.$form_state['input']['testminscore'][$p].'<br/><br/>' . "\r\n";
			}
		}
		if (strlen($p_ent_tests) > 0) {
			$body .= '<br/><strong style="text-decoration:underline;">Program Enrollment Test Requirements:</strong><br/>' . "\r\n";
			$body .= $p_ent_tests;
		}
	}
	
	if(isset($form_state['input']['courseid']) && count($form_state['input']['courseid']) > 0) {
		$p_req_courses = '';
		for($p=0,$i=1;$p<count($form_state['input']['courseid']);$p++,$i++) {
			if(trim($form_state['input']['coursename'][$p]) != "") {
				//$p_req_courses .= '<strong>Status '.$i.':</strong> '.$form_state['input']['coursename'][$p].'<br/>' . "\r\n";
				$p_req_courses .= '<strong>Course '.$i.' Name:</strong> '.$form_state['input']['coursename'][$p].'<br/>' . "\r\n";
				$p_req_courses .= '<strong>Course '.$i.' Description:</strong> '.$form_state['input']['coursedesc'][$p].'<br/>' . "\r\n";
				$p_req_courses .= '<strong>Course '.$i.' Level:</strong> '.$form_state['input']['courselevel'][$p].'<br/>' . "\r\n";
				$p_req_courses .= '<strong>Course '.$i.' Minimum GPA:</strong> '.$form_state['input']['coursemingpa'][$p].'<br/><br/>' . "\r\n";
			}
		}
		if (strlen($p_req_courses)) {
			$body .= '<br/><strong style="text-decoration:underline;">Prerequsite Courses for Enrollment in Program:</strong><br/>' . "\r\n";
			$body .= $p_req_courses;
		}
	}

	if ($task != 'delete') {
		$body .="<br/><strong>Program detail page URL:</strong> https://".$_SERVER['SERVER_NAME'].vcn_drupal7_base_path()."get-qualified/program/".$programid."/cipcode/".$cipcode."/onetcode/0";
	}
		
	$body .= '<br/><strong>Submitted By User:</strong> '. vcn_custom_aes_decrypt($user_name) . ' &#160;&#160; ' . $user_email . ' &#160;&#160; (' . $user_id . ')' . "\r\n";
	
	$mail_body = $body;
	
	$from =  $user_email;	
	
	$header  = 'MIME-Version: 1.0' . "\r\n";
	$header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$header .= "From: $from \r\n";
	$header .= "Reply-To: $from \r\n";
	
	$mail_sent = mail($provider_portal_email, $mail_subject, $mail_body, $header );	
	
	if($mail_sent == true) {
		// Success.
		drupal_set_message("Program Info changes email has been sent succesfully.");
	}
	else {
		// Failure.
		drupal_set_message(t('Unable to send e-mail. Contact the site administrator if the problem persists.'), 'error');
	}

}
