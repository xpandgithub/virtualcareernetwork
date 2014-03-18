<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php 

//Program detail page
function vcn_program_detail($programid, $cipcode, $onetcode) {
	return theme('vcn_program_detail_template', array('programid' => $programid, 'cipcode' => $cipcode, 'onetcode' => $onetcode));
}


function vcn_program_detail_facebook_like(&$variables) {

	// Facebook Like button
	$current_url = "http://" . $_SERVER['SERVER_NAME'] . vcn_drupal7_base_path() . 'get-qualified/program/' . $variables['programid'].'/cipcode/' . $variables['cipcode'].'/onetcode/' . $variables['onetcode'];
	$facebookTitle = "VCN.org Program: " . $variables['programdetails']->programname;
	// metatags for the Facebook Like button
	$facebookMetatags = new vcnFacebookMetatag($facebookTitle, $current_url, $variables['programdetails']->programdescription);
	$facebookMetatags->getTags();
	// Generate Facebook Like button
	$facebookLikeButton = new vcnFacebookLike($current_url);
	$facebookLikeButton->width = '80';
	$facebookLikeButton->shiftTop = '1';
	$facebookLikeButton->shiftLeft = '0';

	return $facebookLikeButton->getButton();
}


function vcn_program_detail_process(&$variables) {

	$programdetails = $variables['programdetails'];
	$programentrancetests = $variables['programentrancetests'];
	$programreqcourses = $variables['programrequiredcourses'];
	$programreqeducation = $variables['programrequirededucation'];
	$programaccredited = $variables['programaccredited'];

	$providerdetail = $variables['providerdetail'];
	$providerentrancetests = $variables['providerentrancetests'];
	$providerrequiredcourses = $variables['providerrequiredcourses'];

	$variables['facebook_like'] = ''; //vcn_program_detail_facebook_like($variables);

	// Program detail
	if (vcn_chk_not_null($programdetails->programurl) && $programdetails->programurlflag) {
		$variables['programurl'] = vcn_build_link_window_opener($programdetails->programurl, $programdetails->programname, false, true, "", "strong extlink");
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
	$variables['otherrequirements'] = 'N/A';
	if (vcn_chk_not_null($programdetails->otherrequirements)) {
		$variables['otherrequirements'] = vcn_display_formatted_text(vcn_convert_urls_to_links((string)$programdetails->otherrequirements));
	}

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

	/*if (count($programaccredited->item) > 0) {
		$variables['paccredited'] = "";
		foreach ($programaccredited->item as $creditor) {
			$variables['paccredited'] .= "<div>".$creditor->name."</div>";
		}
	}*/

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
	$entrancetests_prog_array = array();
	if (count($programentrancetests->item) > 0) {
		/*$variables['entrancetests_prog'] = '<div class="strong allclear">Program Entrance Tests:<br/></div>
											<div class="program-edu-req strong allclear">
												<div>&nbsp;</div>
												<div>Minimum Score</div>
												<div>Test Description</div>
											</div>';*/	
		
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
				
			/*$variables['entrancetests_prog'] .= '<div class="program-edu-req allclear">';
			$variables['entrancetests_prog'] .= '<div><b>'.$tname.'</b>&nbsp;</div>';
			$variables['entrancetests_prog'] .= '<div>'.$testminscore.'&nbsp;</div>';
			$variables['entrancetests_prog'] .= '<div>'.$test->testdescription.'&nbsp;</div>';
			$variables['entrancetests_prog'] .= '</div>';*/
			
			$entrancetests_prog_array[] = array(
					'tname' => '<b>'.(string)$tname.'</b>',
					'tminscore' => (string)$testminscore,
					'tdesc' => (string)$test->testdescription,				
			);				
		}		
	} else {
		$variables['entrancetests_prog_na'] = 'N/A';
	}
	$entrancetests_prog_json = json_encode($entrancetests_prog_array);
	drupal_add_js(array('vcngetqualified_program_entrancetests' => array('program_entrancetests_data' => $entrancetests_prog_json)),array('type' => 'setting'));
	
	
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
	drupal_add_js(array('vcngetqualified_provider_entrancetests' => array('provider_entrancetests_data' => $entrancetests_prov_json)),array('type' => 'setting'));
	

	//Program Required courses
	$programreqcourses_array = array();
	if (count($programreqcourses->item) > 0 && $programreqcourses->item[0]->coursetitle != "") {
		/*$variables['requiredcourses_prog'] = '<div class="strong allclear">Program Prerequisite Courses:<br/></div>
											  <div class="pro-required-courses strong allclear">
												  <div>&nbsp;</div>
												  <div>Course Level</div>
												  <div>Minimum GPA</div>
											  </div>';*/
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
				/*$variables['requiredcourses_prog'] .= '<div class="pro-required-courses allclear">';
				$variables['requiredcourses_prog'] .= '<div><b>'.$requiredcourses->coursetitle.'</b>'.$divider.$requiredcourses->description.'&nbsp;</div>';
				$variables['requiredcourses_prog'] .= '<div>'.$level.'&nbsp;</div>';
				$variables['requiredcourses_prog'] .= '<div>'.$requiredcourses->mingpa.'&nbsp;</div>';
				$variables['requiredcourses_prog'] .= '</div>';*/
				
				$programreqcourses_array[] = array(
						'cname' => '<b>'.(string)$requiredcourses->coursetitle.'</b>'.$divider.(string)$requiredcourses->description,
						'level' => (string)$level,
						'mingpa' => (string)$requiredcourses->mingpa,
				);
			}
		}
	} else {
		$variables['requiredcourses_prog_na'] = 'N/A';
	}
	$programreqcourses_json = json_encode($programreqcourses_array);
	drupal_add_js(array('vcngetqualified_programreqcourses' => array('programreqcourses_data' => $programreqcourses_json)),array('type' => 'setting'));
	

	//Provider Required courses
	$providerreqcourses_array = array();
	if (count($providerrequiredcourses->item) > 0 && $providerrequiredcourses->item[0]->coursetitle != "") {
		/*$variables['requiredcourses_prov'] = '<div class="strong allclear"><br/>Provider Prerequisite Courses:<br/></div>
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
	drupal_add_js(array('vcngetqualified_providerreqcourses' => array('providerreqcourses_data' => $providerreqcourses_json)),array('type' => 'setting'));
	

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

	$curriculum_program_courses = vcn_get_program_curriculum_courses($variables['programid']);
	$variables['curriculum_program_courses'] = $curriculum_program_courses;

	$curriculum_program_courses_array = array();

	foreach($curriculum_program_courses->item as $program_course) {
		$curriculum_program_courses_array[] = array(
				'course_description' => (string)$program_course->coursedescription,
				'course_title' => (string)$program_course->coursetitle,
				'course_duration' => (string)$program_course->courseduration,
				'course_total_credits' => (string)$program_course->coursetotalcredits,
		);
	}

	$curriculum_program_courses_json = json_encode($curriculum_program_courses_array);
	drupal_add_js(array('vcngetqualified_curriculum_program_courses' => array('curriculum_program_courses_data' => $curriculum_program_courses_json)),array('type' => 'setting'));

	return true;
}

function vcn_get_program_curriculum_courses($programid) {
	$curriculum_courses = vcn_rest_wrapper('vcntrainingsvc', 'vcnprograms', 'get-program-curriculum-courses', array('programid' => $programid), 'xml');
	return $curriculum_courses;
}


function vcn_program_detail_tabs(&$variables) {

	//Program Overview , Program Requirements ,	General Admission Requirements , School Information , Entrance Tests , Required courses
	$vcn_tabs_list = array();
	$vcn_tabs_list[] = array("pover","Overview");
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