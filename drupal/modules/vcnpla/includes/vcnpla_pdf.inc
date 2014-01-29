<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php

function vcn_view_my_cover_letter() { // Menu Call back function
	ob_start();
	$user = vcn_get_user_info();
	$variables['name'] = '';
	if (strlen($user['vcn_user_name'])) {
		$variables['name'] = ($user['vcn_user_name']) ; 
	}
	
	$cover_letter_html = theme('vcn_view_my_cover_letter', $variables);
	
	$tcpdf = tcpdf_get_instance();
	
	$tcpdf->setPrintHeader(false);
	$tcpdf->setPrintFooter(false);
	
	$tcpdf->DrupalInitialize(array(
			'title' => 'My Cover Letter - VCN.org',
			'author' => 'VCN.org',	
			'footer' => array('html' => '',),
			'header' => array('html' => '',),
	));	
	
	$tcpdf->writeHTML($cover_letter_html);
	
	ob_end_clean();
	$tcpdf->Output('mycoverletter.pdf', 'I');
	
	return null;
}

function vcn_view_my_learning_inventory() { // Menu Call back function
	ob_start();
	$user = vcn_get_user_info();
	
	$variables['name'] = '';
	if (strlen($user['vcn_user_name'])) {
		$variables['name'] = ($user['vcn_user_name']) ; 
	}
	
	// Instead of theme preprocess, using function call to preprocess all data and get variables here in callback function directly to print Addendum at new page of pdf.
	vcn_pla_pre_process_vcn_view_my_learning_inventory($variables);
	
	$variables['addendum'] = "0";
	$learning_inventory_html = theme('vcn_view_my_learning_inventory', $variables);
	$variables['addendum'] = "1";
	$all_course_details_html = theme('vcn_view_my_learning_inventory', $variables);

	$tcpdf = tcpdf_get_instance(); 

	$tcpdf->DrupalInitialize(array(
			'title' => 'My Learning Inventory - VCN.org',
		  	'author' => 'VCN.org',
			'header' => array(
					'html' => '<table style="width:100%; color:#b2b2b2; font-family:Verdana; font-size:8pt; border-bottom:1px solid #b2b2b2;">
					<tr>
						<td style="width:50%;">VCN.org - Summary of Career Learning</td>
						<td style="width:50%; text-align:right;">' . $variables['name'] . '</td>
					</tr>
				</table>',
			),
		    'footer' => array(
		      	'html' => '<div style="color:#000000; font-family:Verdana; font-size:8pt;">
		    		NOTE: This Learning Inventory is a resource for you and your admissions
						officer or academic advisor. The credits listed here will not necessarily transfer as college credit in your degree program. 
						Each college and university decides how your prior learning might apply to a specific degree program.    		
		    		</div>',
		    ),		  		
		)
	);
	$tcpdf->SetFooterMargin(15);
	
	$tcpdf->writeHTML($learning_inventory_html);
	
	if($variables['all_course_details_html'] != ""){
		$tcpdf->AddPage();
		$tcpdf->writeHTML($all_course_details_html);
	}
	
	ob_end_clean();
	$tcpdf->Output('mylearninginventory.pdf', 'I');

	return null;
}

function vcn_pla_pre_process_vcn_view_my_learning_inventory(&$variables){
	$variables['industry'] = vcn_get_industry();
	$variables['industry_code'] = vcn_get_industry_code();
	
	$user = vcn_get_user_info();
	$variables['vcn_user_id'] = $user['vcn_user_id'];
	$variables['email'] = $user['mail'];
	
	$variables['logo'] = vcn_drupal7_base_path() . "sites/all/themes/vcnstark/images/industry/".$variables['industry_code']."/site_logo_images/".$variables['industry_code']."_logo.jpg";
	
	$allCourseDetailsArr = array();
	$allCourseDetailsArr['military'] = array();
	$allCourseDetailsArr['professional'] = array();
	$allCourseDetailsArr['nationalexams'] = array();	
	$variables['allCourseDetailsArr'] = $allCourseDetailsArr;	
	
	if($variables['vcn_user_id'] > 0) {
		$params = array('userid' => $variables['vcn_user_id'], 'coursedetail' => true); // optional params coursedetail=true coursetype=college_courses/military_courses/professional_courses/national_courses/vcn_courses
		$variables['credit_courses'] = vcn_rest_wrapper('vcnplasvc', 'vcnpla', 'get-credit-courses-by-userid', $params, 'xml', 'post', false);
	
		$params = array('userid' => $variables['vcn_user_id'], 'industry' => $variables['industry']); 
		$variables['targeted_notebook_info'] = vcn_rest_wrapper('vcncmasvc', 'vcnnotebook', 'get-targeted-notebook-items-detail-by-userid', $params, 'xml', 'post', false);
	}
		
	vcn_pla_targeted_notebook_info($variables);
	vcn_pla_get_college_courses_html($variables);
	vcn_pla_get_military_training_html($variables);
	vcn_pla_get_professional_training_html($variables);
	vcn_pla_get_national_exams_html($variables);
	vcn_pla_get_vcn_courses_html($variables);
	
	vcn_pla_get_all_course_addendum_html($variables); // Addendum
}


function vcn_pla_targeted_notebook_info(&$variables){
	$targetCareer =  $targetProgramName =  $targetProgramAward =  $targetProgramSchool = '';
	
	if($variables['vcn_user_id'] > 0) {
		$numRows = count($variables['targeted_notebook_info']->item);
		if ($numRows > 0) {
			foreach($variables['targeted_notebook_info']->item As $rec) {
				if (strlen($rec->onettitle)) {
					$targetCareer = $rec->onettitle;
				}
				if (strlen($rec->programname)) {
					$targetProgramName = $rec->programname;
				}
				if (strlen($rec->programaward)) {
					$targetProgramAward = $rec->programaward;
				}
				if (strlen($rec->school)) {
					$targetProgramSchool = $rec->school;
				}
			}
		}
	}
	$variables['targetCareer'] = $targetCareer;
	$variables['targetProgramName'] = $targetProgramName;
	$variables['targetProgramAward'] = $targetProgramAward;
	$variables['targetProgramSchool'] = $targetProgramSchool;
	
	return true;
} 


function vcn_pla_get_college_courses_html(&$variables) {
	
	$html = '';
	if($variables['vcn_user_id'] > 0) {		  
		$courseInfo = '';			
		if (count($variables['credit_courses']->collegecourses->item) > 0) {		
			foreach($variables['credit_courses']->collegecourses->item As $rec) {
				$courseCode = (strlen($rec->code)) ? $rec->code : 'No course number';			
				$courseCredit = (strlen($rec->credit)) ? $rec->credit : 'None';			
				$courseName = (strlen($rec->name)) ? ucwords($rec->name) : 'No course name';			
				$schoolName = (strlen($rec->school)) ? ucwords($rec->school) : 'No school name';			
				$yearCompleted = (strlen($rec->completed)) ? ' - ' .date('Y', strtotime($rec->completed)) : '';	
									 
				$companyName = $schoolName . $yearCompleted;
											
				$courseInfo .= vcn_pla_get_course_detail_html_row($courseName, $courseCode, $companyName, '', $courseCredit);										
			}	
			$html = vcn_pla_get_course_detail_html($courseInfo, 'college');
		}
	}	 
	$variables['college_courses_html'] = $html;	
	return true;
}

function vcn_pla_get_military_training_html(&$variables) {

	$html = '';
	if($variables['vcn_user_id'] > 0) {
		$courseInfo = '';
	
		$totalRecs = count($variables['credit_courses']->militarycourses->item);
		if ($totalRecs > 0) {
			
			$services = array('AF' => 'Air Force', 'AR' => 'Army', 'CG' => 'Coast Guard', 'DD' => 'Department of Defense', 'MC' => 'Marine Corps', 'NV' => 'Navy');
			$courseCodeId = '';
			$count = 1;
			
			foreach($variables['credit_courses']->militarycourses->item As $rec) {
				if ($rec->code != $courseCodeId) {
					$courseCredits = 0;
					$courseName = $companyName = $courseDetails = $courseNameSecond = $courseObjective = $courseInstruction = $courseAddCreditInfo = $courseDescription = $courseGoals = '';
			
					if ($count > 1) {
						array_push( $variables['allCourseDetailsArr']['military'], array('name' => $prevCourseName, 'code' => $prevCourseCode, 'company' => $prevCompanyName, 'namesecond' => $prevCourseNameSecond, 'objective' => $prevCourseObjective, 'instruction' => $prevCourseInstruction, 'additionalcreditinfo' => $prevCourseAddCreditInfo, 'description' => $prevCourseDescription, 'goals' => $prevCourseGoals) );
						$courseInfo .= vcn_pla_get_course_detail_html_row($prevCourseName, $prevCourseCode, $prevCompanyName, $prevCourseDetails, $prevCourseCredits);
					}
				}
										
				$prevCourseName = $courseName = (strlen($rec->name)) ? ucwords($rec->name) : 'No course name';
				$prevCompanyName = $companyName = (strlen($rec->company)) ? $services[strtoupper($rec->company)] : 'No company name';	
				$prevCourseCode = $courseCode = (strlen($rec->code)) ? $rec->code : 'No course number';
				$prevCourseNameSecond = $courseNameSecond = (strlen($rec->code)) ? $rec->namesecond : '';
				$prevCourseObjective = $courseObjective = (strlen($rec->objective) && (!stristr($rec->objective, 'Pending evaluation'))) ? $rec->objective : '';				
				$prevCourseInstruction = $courseInstruction = (strlen($rec->instruction) && (!stristr($rec->instruction, 'Pending evaluation'))) ? $rec->instruction : '';
				$prevCourseAddCreditInfo = $courseAddCreditInfo = (strlen($rec->additionalcreditinfo) && (!stristr($rec->additionalcreditinfo, 'Additional Details: P'))) ? $rec->additionalcreditinfo : '';
				 				
				if (strlen($rec->credit)) {
					$courseCredits += (int) $rec->credit;
				}
				$prevCourseCredits = $courseCredits;				
				
				if(strlen($rec->subject)){
					$unit = 'hours';
					if (strlen($rec->credit)) {
						if (is_numeric($rec->credit) && (int) $rec->credit == 1) {
							$unit = 'hour';
						}
						$creditText = ": " . $rec->credit . " " . $unit;
					}
					$courseDetails .= ucwords($rec->subject) . $creditText . "<br/>";					
				}
				$prevCourseDetails = $courseDetails;
				
				$prevCourseDescription = $courseDescription;
				$prevCourseGoals = $courseGoals;				
			  
				if ($count == $totalRecs) {
					array_push( $variables['allCourseDetailsArr']['military'], array('name' => $courseName, 'code' => $courseCode, 'company' => $companyName, 'namesecond' => $courseNameSecond, 'objective' => $courseObjective, 'instruction' => $courseInstruction, 'additionalcreditinfo' => $courseAddCreditInfo, 'description' => $courseDescription, 'goals' => $courseGoals) );
					$courseInfo .= vcn_pla_get_course_detail_html_row($courseName, $courseCode, $companyName, $courseDetails, $courseCredits);
				}							
				 
				$courseCodeId = (string) $rec->code;
				$count++;
			}	
			$html = vcn_pla_get_course_detail_html($courseInfo, 'military');
		} 
	}
	$variables['military_training_html'] = $html;	
	return true;

}

function vcn_pla_get_professional_training_html(&$variables) {

	$html = '';
	if($variables['vcn_user_id'] > 0) { 
		$courseInfo = '';
			
		$totalRecs = count($variables['credit_courses']->professionalcourses->item);
		if ($totalRecs > 0) {
			$courseCodeId = '';
			$count = 1;
			foreach($variables['credit_courses']->professionalcourses->item As $rec) {
				
				if ($rec->code != $courseCodeId) {
					$courseCredits = 0;
					$courseName = $companyName = $courseDetails = $courseNameSecond = $courseObjective = $courseInstruction = $courseAddCreditInfo = $courseDescription = $courseGoals = '';
	
					if ($count > 1) {
						array_push( $variables['allCourseDetailsArr']['professional'], array('name' => $prevCourseName, 'code' => $prevCourseCode, 'company' => $prevCompanyName, 'namesecond' => $prevCourseNameSecond, 'objective' => $prevCourseObjective, 'instruction' => $prevCourseInstruction, 'additionalcreditinfo' => $prevCourseAddCreditInfo, 'description' => $prevCourseDescription, 'goals' => $prevCourseGoals) );
						$courseInfo .= vcn_pla_get_course_detail_html_row($prevCourseName, $prevCourseCode, $prevCompanyName, $prevCourseDetails, $prevCourseCredits);
					}
				}

				$prevCourseName = $courseName = (strlen($rec->name)) ? ucwords($rec->name) : 'No course name';
				$prevCompanyName = $companyName = (strlen($rec->company)) ? ucwords($rec->company) : 'No company name';	
				$prevCourseCode = $courseCode = (strlen($rec->code)) ? $rec->code : 'No course number';
				$prevCourseNameSecond = $courseNameSecond = (strlen($rec->code)) ? $rec->namesecond : '';
				$prevCourseObjective = $courseObjective = (strlen($rec->objective)) ? $rec->objective : '';				
				$prevCourseInstruction = $courseInstruction = (strlen($rec->instruction)) ? $rec->instruction : '';
				$prevCourseAddCreditInfo = $courseAddCreditInfo = (strlen($rec->additionalcreditinfo)) ? $rec->additionalcreditinfo : '';
				$prevCourseDescription = $courseDescription = (strlen($rec->description)) ? $rec->description : '';
				$prevCourseGoals = $courseGoals = (strlen($rec->goals)) ? $rec->goals : '';				
				
				if (strlen($rec->credit)) {
					$courseCredits += (int) $rec->credit;
				}
				$prevCourseCredits = $courseCredits;				
				
				if(strlen($rec->subject)){
					$unit = 'hours';
					if (strlen($rec->credit)) {
						if (is_numeric($rec->credit) && (int) $rec->credit == 1) {
							$unit = 'hour';
						}
						$creditText = ": " . $rec->credit . " " . $unit;
					}
					$courseDetails .= ucwords($rec->subject) . $creditText . "<br/>";					
				}		
				$prevCourseDetails = $courseDetails;
					
				if ($count == $totalRecs) {
					array_push( $variables['allCourseDetailsArr']['professional'], array('name' => $courseName, 'code' => $courseCode, 'company' => $companyName, 'namesecond' => $courseNameSecond, 'objective' => $courseObjective, 'instruction' => $courseInstruction, 'additionalcreditinfo' => $courseAddCreditInfo, 'description' => $courseDescription, 'goals' => $courseGoals) );
					$courseInfo .= vcn_pla_get_course_detail_html_row($courseName, $courseCode, $companyName, $courseDetails, $courseCredits);
				}
									
				$courseCodeId = (string) $rec->code;
				$count++;
			}
			$html = vcn_pla_get_course_detail_html($courseInfo, 'professional');
		}
	}
	$variables['professional_training_html'] = $html;	
	return true;	
}

function vcn_pla_get_national_exams_html(&$variables) {

	$html = '';
	if($variables['vcn_user_id'] > 0) { 
		$courseInfo = '';
	 			
		$totalRecs = count($variables['credit_courses']->nationalcourses->item);
		if ($totalRecs > 0) {
			$courseCodeId = '';
			$count = 1;
			foreach($variables['credit_courses']->nationalcourses->item As $rec) {
		
				if ($rec->code != $courseCodeId) {
					$courseName = $companyName = $courseDetails = $courseCredits = $courseNameSecond = $courseObjective = $courseInstruction = $courseAddCreditInfo = $courseDescription = $courseGoals = '';
		
					if ($count > 1) {
						array_push( $variables['allCourseDetailsArr']['nationalexams'], array('name' => $prevCourseName, 'code' => $prevCourseCode, 'company' => $prevCompanyName, 'namesecond' => $prevCourseNameSecond, 'objective' => $prevCourseObjective, 'instruction' => $prevCourseInstruction, 'additionalcreditinfo' => $prevCourseAddCreditInfo, 'description' => $prevCourseDescription, 'goals' => $prevCourseGoals) );
						$courseInfo .= vcn_pla_get_course_detail_html_row($prevCourseName, $prevCourseCode, $prevCompanyName, $prevCourseDetails, $prevCourseCredits);
					}
				}
				
				$prevCourseName = $courseName = (strlen($rec->name)) ? ucwords($rec->name) : 'No course name';
				$prevCompanyName = $companyName = (strlen($rec->company)) ? ucwords($rec->company) : 'No company name';
				$prevCourseCode = $courseCode = (strlen($rec->code)) ? $rec->code : 'No course number';
				$prevCourseNameSecond = $courseNameSecond = (strlen($rec->code)) ? $rec->namesecond : '';
				$prevCourseObjective = $courseObjective = (strlen($rec->objective)) ? $rec->objective : '';
				$prevCourseInstruction = $courseInstruction = (strlen($rec->instruction)) ? $rec->instruction : '';
				$prevCourseAddCreditInfo = $courseAddCreditInfo = (strlen($rec->additionalcreditinfo)) ? $rec->additionalcreditinfo : '';
				$prevCourseDescription = $courseDescription = (strlen($rec->description)) ? $rec->description : '';
				$prevCourseGoals = $courseGoals = (strlen($rec->goals)) ? $rec->goals : '';
									
				if(strlen($rec->credit)){
					$courseDetails .= $rec->credit . "<br/>";
				}
				$prevCourseDetails = $courseDetails;
				
				$prevCourseCredits = $courseCredits;
							
				if ($count == $totalRecs) {
					array_push( $variables['allCourseDetailsArr']['nationalexams'], array('name' => $courseName, 'code' => $courseCode, 'company' => $companyName, 'namesecond' => $courseNameSecond, 'objective' => $courseObjective, 'instruction' => $courseInstruction, 'additionalcreditinfo' => $courseAddCreditInfo, 'description' => $courseDescription, 'goals' => $courseGoals) );
					$courseInfo .= vcn_pla_get_course_detail_html_row($courseName, $courseCode, $companyName, $courseDetails, $courseCredits);
				}
						
				$courseCodeId = (string) $rec->code;
				$count++;
			}	
		$html = vcn_pla_get_course_detail_html($courseInfo, 'nationalexams');
		}	
	}
	$variables['national_exams_html'] = $html;
	return true;
}


function vcn_pla_get_vcn_courses_html(&$variables) {

	$html = '';
	if($variables['vcn_user_id'] > 0) {	
		$courseInfo = '';		
		if (count($variables['credit_courses']->vcncourses->item) > 0) {
			foreach($variables['credit_courses']->vcncourses->item As $rec) {				
				$courseName = (strlen($rec->coursetitle)) ? ucwords($rec->coursetitle) : 'No course name';				
				$courseCode = (strlen($rec->coursecode)) ? $rec->coursecode : '';	
				$yearCompleted = (strlen($rec->completed)) ? 'Completed: '.date('M Y', strtotime($rec->completed)) : '';	
					
				$courseInfo .= vcn_pla_get_course_detail_html_row($courseName, '', $yearCompleted, '', '');				
			}	
		$html = vcn_pla_get_course_detail_html($courseInfo, 'vcncourses');
		}		 
	}
	$variables['vcn_courses_html'] = $html;
	return true;
}


function vcn_pla_get_course_detail_html($courseInfo, $type, $displayCredits = true) {

	$detailsArr = array('college' => array( 'courseheading' => 'College Courses', 'creditheading' => 'Credit Hours Earned' ),
						'military' => array( 'courseheading' => 'Military Training', 'creditheading' => 'Credit Hours<br/>Recommended by ACE' ),
						'professional' => array( 'courseheading' => 'Professional Training', 'creditheading' => 'Credit Hours<br/>Recommended by ACE' ),
						'nationalexams' => array( 'courseheading' => 'National Exams', 'creditheading' => '' ),
						'vcncourses' => array( 'courseheading' => 'VCN Courses', 'creditheading' => '' ) );

	$courseHeading = $detailsArr[$type]['courseheading'];

	$creditHeading = '';
	if ($displayCredits) {
		$creditHeading = $detailsArr[$type]['creditheading'];
	}

	$html = "<table border=\"0\" style=\"width:100%;\">
				<tr>
					<td class=\"coursehead\">$courseHeading</td>
					<td class=\"creditshead\">$creditHeading</td>
				</tr>
				<tr>
					<td colspan=\"2\" align=\"top\"><hr/></td>
				</tr>
				<tr>
					<td colspan=\"2\">
						<table border=\"0\" class=\"coursetable\">
							$courseInfo
						</table>
					</td>
				</tr>
				<tr>
					<td colspan=\"2\"><br/><br/></td>
				</tr>
			</table>";

	return $html;

}


function vcn_pla_get_course_detail_html_row($courseName, $courseCode, $companyName, $courseDetails, $courseCredits) {

	// strip out any html tags
	$courseName = strip_tags($courseName);
	$courseCode = strip_tags($courseCode);
	$companyName = strip_tags($companyName);
	
	$courseCodeText = (strlen($courseCode)) ?  "($courseCode)" : '';	
	
	$courseInfo = "
				<tr>
					<td class=\"coursedata\">
						<table border=\"0\" style=\"width:100%;\">
							<tr>
								<td style=\"width:100%;\" colspan=\"3\"><strong>$courseName $courseCodeText</strong></td>
							</tr>";
	
	if (strlen($companyName)) {
		$courseInfo .= "<tr>
							<td style=\"width:3%;\">&nbsp;</td>
							<td style=\"width:97%; font-style:italic;\" colspan=\"2\">$companyName</td>
						</tr>";
	}
	
	if (strlen($courseDetails)) {
		
		$courseDetails = substr_replace($courseDetails, '', -5); // remove the last <br/> that was added		
		$courseDetails = strip_tags($courseDetails, '<br>'); // strip out any html tags except BRs
	
		$courseInfo .= "	<tr>
								<td style=\"width:3%;\">&nbsp;</td>
								<td style=\"width:3%;\">&nbsp;</td>
								<td style=\"width:94%;\">$courseDetails</td>
							</tr>";
	}
		
	$courseInfo .= "	</table>
					</td>
					<td class=\"creditsdata\">$courseCredits</td>
				</tr>";
	
	return $courseInfo;
}

function vcn_pla_get_all_course_addendum_html(&$variables) {

	$allCourseDetailsArr = $variables['allCourseDetailsArr'];
	$html = "";

	if (count($allCourseDetailsArr['military']) > 0) {
		$courseInfo = vcn_pla_get_course_addendum_details_html($allCourseDetailsArr, 'military');
		$html .= vcn_pla_get_course_detail_html($courseInfo, 'military', false);
	}
	if (count($allCourseDetailsArr['professional']) > 0) {
		$courseInfo = vcn_pla_get_course_addendum_details_html($allCourseDetailsArr, 'professional');
		$html .= vcn_pla_get_course_detail_html($courseInfo, 'professional', false);
	}
	if (count($allCourseDetailsArr['nationalexams']) > 0) {
		$courseInfo = vcn_pla_get_course_addendum_details_html($allCourseDetailsArr, 'nationalexams');
		$html .= vcn_pla_get_course_detail_html($courseInfo, 'nationalexams', false);
	}
	
	if($html != ""){
		$html = "<table style=\"width:100%;\">
				<tr>
					<td><strong style=\"font-size:12pt;\">Addendum: Course Details</strong></td>
				</tr>
			 </table><br/><br/>".$html;
	}

	$variables['all_course_details_html'] = $html;	
	return true;

}


function vcn_pla_get_course_addendum_details_html($allCourseDetailsArr, $type) {

	$courseInfo = '';
	$additionalData = false;

	foreach ($allCourseDetailsArr[$type] as $dataArr) {
		$courseInfo .= '<tr><td style="width:100%;" colspan="3"><strong>' . strip_tags($dataArr['name']) . ' (' . strip_tags($dataArr['code']) .')</strong></td></tr>';

		if (strlen($dataArr['company'])) {
			$courseInfo .= '<tr><td style="width:3%;">&nbsp;</td><td style="width:97%; font-style:italic;" colspan="2">' . strip_tags($dataArr['company']) . '</td></tr>';
		}
		if (strlen($dataArr['namesecond'])) {
			$courseInfo .= '<tr><td style="width:3%;">&nbsp;</td><td style="width:3%;">&nbsp;</td><td style="width:94%;">Alternate Title: ' . strip_tags($dataArr['namesecond']) . '</td></tr>';
		}
		if (strlen($dataArr['objective'])) {
			$additionalData = true;
			$courseInfo .= '<tr><td style="width:3%;">&nbsp;</td><td style="width:3%;">&nbsp;</td><td style="width:94%;">Objective: ' . strip_tags($dataArr['objective']) . '</td></tr>';
		}
		if (strlen($dataArr['instruction'])) {
			$additionalData = true;
			$courseInfo .= '<tr><td style="width:3%;">&nbsp;</td><td style="width:3%;">&nbsp;</td><td style="width:94%;">Instruction: ' . strip_tags($dataArr['instruction']) . '</td></tr>';
		}
		if (strlen($dataArr['description'])) {
			$additionalData = true;
			$courseInfo .= '<tr><td style="width:3%;">&nbsp;</td><td style="width:3%;">&nbsp;</td><td style="width:94%;">Description: ' . strip_tags($dataArr['description']) . '</td></tr>';
		}
		if (strlen($dataArr['goals'])) {
			$additionalData = true;
			$courseInfo .= '<tr><td style="width:3%;">&nbsp;</td><td style="width:3%;">&nbsp;</td><td style="width:94%;">Goals: ' . strip_tags($dataArr['goals']) . '</td></tr>';
		}
		if (strlen($dataArr['additionalcreditinfo'])) {
			$additionalData = true;
			$courseInfo .= '<tr><td style="width:3%;">&nbsp;</td><td style="width:3%;">&nbsp;</td><td style="width:94%;">Additional Credit Info: ' . strip_tags($dataArr['additionalcreditinfo']) . '</td></tr>';
		}
		if (!$additionalData) {
			$courseInfo .= '<tr><td style="width:3%;">&nbsp;</td><td style="width:3%;">&nbsp;</td><td style="width:94%;">No Additional Details</td></tr>';
		}
	}

	return $courseInfo;
}