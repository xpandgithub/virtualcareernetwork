<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php

function vcn_cma_collegecredit_list($credit_courses) {
	$base_path7 = vcn_drupal7_base_path();
	$count = 0;
	
	$course_type = array('' => 'College Course', 'Y' => 'Military Course', 'C' => 'Professional Training', 'E' => 'National Exam', 'V' => 'VCN Course');
	$c_type_objects = array("collegecourses", "militarycourses", "professionalcourses", "nationalcourses", "vcncourses") ;
	$course_detail_page = array('' => 'college-course-details', 'Y' => 'military-credit-details', 'C' => 'professional-training-details', 'E' => 'national-exam-details');
	
	$cma_collegecredits_list_array = array();
	
	foreach ($c_type_objects as $key => $c_type) {
		if (count($credit_courses->$c_type->item)>0) {
			for ($i = 0; $i < count($credit_courses->$c_type->item); $i++) {
				$count++;
	
				if($c_type == "vcncourses") {
					$course_detail_url = $base_path7.$credit_courses->$c_type->item[$i]->onlinecourseurl; // Drupal internal link, no need to check for onlinecourseurlflag
					$course_title = "<a title='".$credit_courses->$c_type->item[$i]->coursetitle."' href='".$course_detail_url."'>".$credit_courses->$c_type->item[$i]->coursetitle."</a>";
				} else if($c_type == "collegecourses") {
					$course_detail_url = $base_path7."pla/".$course_detail_page[(string) $credit_courses->$c_type->item[$i]->coursetype]."/".$credit_courses->$c_type->item[$i]->usercourseid;
					$credit_course_str = $credit_courses->$c_type->item[$i]->name;
					$course_title = "<a title='".$credit_courses->$c_type->item[$i]->name."' href='".$course_detail_url."'>".$credit_course_str."</a>";
				} else {
					if ($credit_courses->$c_type->item[$i]->stopdate == '999999') {
						$end_date = 'Present';
					} else {
						$end_date = substr($credit_courses->$c_type->item[$i]->stopdate, 4, 2)."/".substr($credit_courses->$c_type->item[$i]->stopdate, 0, 4);
					}
					$start_date = substr($credit_courses->$c_type->item[$i]->startdate, 4, 2)."/".substr($credit_courses->$c_type->item[$i]->startdate, 0, 4);
						
					$course_time = ' (Course taken between ' .$start_date. ' and '.$end_date. ' )';
						
						
					$course_detail_url = $base_path7."pla/".$course_detail_page[(string) $credit_courses->$c_type->item[$i]->coursetype]."/".$credit_courses->$c_type->item[$i]->code."/".$credit_courses->$c_type->item[$i]->startdate."/".$credit_courses->$c_type->item[$i]->stopdate;
					$course_title = "<a title='".$credit_courses->$c_type->item[$i]->name."' href='".$course_detail_url."'>".$credit_courses->$c_type->item[$i]->name.$course_time."</a>";
				}
	
				$cma_collegecredits_list_array[] = array(
						'usercourseid' => (int)$credit_courses->$c_type->item[$i]->usercourseid,
						'course_type' => $course_type[(string)$credit_courses->$c_type->item[$i]->coursetype],
						'course_name' => (string)$credit_courses->$c_type->item[$i]->name,
						'course_title' => $course_title,
						'course_detail_url' => $course_detail_url
				);
				
			}
		}
	}
	
	return $cma_collegecredits_list_array;
	
}