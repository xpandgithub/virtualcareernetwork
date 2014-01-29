<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php


class VCN_Model_VCNPLA extends VCN_Model_Base_VCNBase {
	
	//gets the list of Military courses
	public function getMilitaryCoursesList($params) {
	
		try {
	
			$db = Resources_PdoMysql::getConnection();
	
			$sql = "SELECT ";
			$columns = "a.ace_id AS ace_id,
      						a.first_title AS first_title,
      						a.second_title AS second_title,
      						b.course_id AS course_id,
      						a.start_date AS start_date,
      						a.end_date AS end_date,
      						a.objective AS objective ";
	
			//to be used to find the total number of records
			$from = "FROM vcn_ace_course a,vcn_ace_military_course b ";
	
			$where = "WHERE a.ace_id=b.ace_id AND a.start_date=b.start_date
							AND a.end_date=b.end_date AND a.branch=:branch ";
			if (isset($params['search_term'])) {
				$search_term = '%'.$params['search_term'].'%';
				$where .= " AND (a.first_title LIKE :searchTerm OR a.second_title LIKE :searchTerm OR b.course_id LIKE :searchTerm)";
			}
	
			$group_by = " GROUP BY first_title, start_date, end_date ";
	
			$sql .= $columns.$from.$where.$group_by;
	
			if (isset($params['is_dataTable']) && $params['is_dataTable']) {
				$datatableUtil = Resources_DatatableUtilities::getDatatableCommon($params);
				$sortDir = $datatableUtil['sSortDir'];
				$orderByCol = $datatableUtil['sOrderColumn'];
				$limitIndex = $datatableUtil['iDisplayStart'];
				$displayLength = $datatableUtil['iDisplayLength'];
				$column_display_order_datatable = $datatableUtil['column_display_order_datatable'];
			}
	
			//need this switch statement because of the way branch is stored in the DB. select distinct(branch) from vcn_ace_course
			switch ($params['branch']) {
				case 'air_force':
					$branch = 'air force';
					break;
				case 'coast_guard':
					$branch = 'coast guard';
					break;
				case 'marine_corps':
					$branch = 'marine corps';
					break;
				case 'dod':
					$branch = 'Dept of Defense';
					break;
				case 'army':
					$branch = 'army';
					break;
				case 'navy':
					$branch = 'navy';
					break;
				default:
					$branch = 'air_force'; //just a precaution, making it match the default drop down value for branch found at health-care/pla/military-credit
					break;
			}
	
			if (isset($params['is_dataTable']) && $params['is_dataTable']) {
				$sql .= " ORDER BY $column_display_order_datatable[$orderByCol] $sortDir";
				$sql .= " LIMIT $limitIndex, $displayLength";
			}
	
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':branch', $branch, PDO::PARAM_STR);
			if (isset($params['search_term'])) {
				$stmt->bindParam(':searchTerm', $search_term, PDO::PARAM_STR);
			}
			$stmt->execute();
			$result = $stmt->fetchAll();
	
			//getting total number of records
			$sql_count = "SELECT COUNT(a.ace_id) AS count_military_courses
      		FROM (SELECT a.ace_id AS ace_id, a.first_title AS first_title, a.start_date AS start_date, a.end_date AS end_date ".$from.$where.$group_by.") a";
			$stmt_count = $db->prepare($sql_count);
			$stmt_count->bindParam(':branch', $branch, PDO::PARAM_STR);
			if (isset($params['search_term'])) {
				$stmt_count->bindParam(':searchTerm', $search_term, PDO::PARAM_STR);
			}
			$stmt_count->execute();
			$number_of_rows = $stmt_count->fetchColumn();
	
			//end getting total number of records
	
			$data = array();
			if ($number_of_rows != 0) {
				foreach ($result as $row) {
					//preserving original values of start and end date, to be used in the details page for the courses
					$row['start_date_original'] = $row['start_date'];
					$row['end_date_original'] = $row['end_date'];
	
					//logic copied over from drupal 6, employer_training.module, line 296 (line number subject to change)
					if ($row['end_date'] == '999999') {
						$row['end_date'] = 'Present';
					} else {
						$row['end_date'] = substr($row['end_date'], 4, 2)."/".substr($row['end_date'], 0, 4);
					}
					$row['start_date'] = substr($row['start_date'], 4, 2)."/".substr($row['start_date'], 0, 4);
	
					$data['result'][] = $row;
				}
			}
	
			$data['num_rows'] = $number_of_rows;
	
			$this->setResult($data);
	
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
	
		return $this->result;
	}
	
	
	//gets the list of Training or Exam courses
	public function getPLATrainingOrExamCoursesList($params) {
	
		try {
	
			$db = Resources_PdoMysql::getConnection();
			
			if (isset($params['branch_type'])) {
				$branch_type = $params['branch_type'];
			} else {
				$branch_type = ''; // just to prevent the query from breaking in case this is not being set
			}
	
			$sql = "SELECT ";
			$columns = "ace_id, first_title, second_title, start_date, end_date ";
	
			//to be used to find the total number of records
			$count_column = "COUNT(ace_id) AS count_programs ";
			$from = "FROM vcn_ace_national_course ";
			$where = "WHERE branch = :branch AND ace_id LIKE :organization ";
			
			$organization = '%'.$params['organization'].'%';
			
			if (isset($params['search_term'])) {
				$search_term = '%'.$params['search_term'].'%';
				$where .= " AND (first_title LIKE :searchTerm OR second_title LIKE :searchTerm)";
			}
	
			$sql .= $columns.$from.$where;
			
			if (isset($params['is_dataTable']) && $params['is_dataTable']) {
				$datatableUtil = Resources_DatatableUtilities::getDatatableCommon($params);
				$sortDir = $datatableUtil['sSortDir'];
				$orderByCol = $datatableUtil['sOrderColumn'];
				$limitIndex = $datatableUtil['iDisplayStart'];
				$displayLength = $datatableUtil['iDisplayLength'];
				$column_display_order_datatable = $datatableUtil['column_display_order_datatable'];
			}
	
			if (isset($params['is_dataTable']) && $params['is_dataTable']) {
				$sql .= " ORDER BY $column_display_order_datatable[$orderByCol] $sortDir";
				$sql .= " LIMIT $limitIndex, $displayLength";
			}
			
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':organization', $organization, PDO::PARAM_STR);
			$stmt->bindParam(':branch', $branch_type, PDO::PARAM_STR);
			if (isset($params['search_term'])) {
				$stmt->bindParam(':searchTerm', $search_term, PDO::PARAM_STR);
			}
			$stmt->execute();
			$result = $stmt->fetchAll();
	
			//getting total number of records
			$sql_count = "SELECT ".$count_column.$from.$where;
			$stmt_count = $db->prepare($sql_count);
			$stmt_count->bindParam(':organization', $organization, PDO::PARAM_STR);
			$stmt_count->bindParam(':branch', $branch_type, PDO::PARAM_STR);
			if (isset($params['search_term'])) {
				$stmt_count->bindParam(':searchTerm', $search_term, PDO::PARAM_STR);
			}
			$stmt_count->execute();
			$number_of_rows = $stmt_count->fetchColumn();
	
			//end getting total number of records
			
			$data = array();
			if ($number_of_rows != 0) {
				foreach ($result as $row) {
					//preserving original values of start and end date, to be used in the details page for the courses
					$row['start_date_original'] = $row['start_date'];
					$row['end_date_original'] = $row['end_date'];
					
					//logic copied over from drupal 6, employer_training.module, line 296 (line number subject to change)
					if ($row['end_date'] == '999999') {
						$row['end_date'] = 'Present';
					} else {
						$row['end_date'] = substr($row['end_date'], 4, 2)."/".substr($row['end_date'], 0, 4);
					}
					$row['start_date'] = substr($row['start_date'], 4, 2)."/".substr($row['start_date'], 0, 4);
	
					$data['result'][] = $row;
				}
			}
	
			$data['num_rows'] = $number_of_rows;
	
			$this->setResult($data);
	
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
	
		return $this->result;
	}
	
	//gets the list of the organizations which offer training courses or exams
	public function getCoursesExamsOrganizationList($params) {
	
		try {
				
			$type = $params['type'];
				
			$db = Resources_PdoMysql::getConnection();
				
			$sql = "SELECT ace_code, company_name FROM vcn_ace_national_course_company WHERE ace_type=:type ORDER BY company_name";
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':type', $type, PDO::PARAM_STR);
			$stmt->execute();
			$result = $stmt->fetchAll();
				
			$data = array();
			foreach ($result as $row) {
				$data[] = array('ace_code' => $row['ace_code'], 'company_name' => $row['company_name']);
			}
				
			$this->setResult($data);
				
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
	
		return $this->result;
	}
	
	public function getCollegeCourseDetails($params) {
		
		$requiredParams = array('usercourseid');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
		
		try {
		
			$db = Resources_PdoMysql::getConnection();
		
			$sql = "SELECT course_code AS code,
						course_credit AS credit,
						course_name AS name,
						institution_name AS school,
						course_grade AS grade,
						program_name AS program,
						DATE_FORMAT(date_completed,'%m-%d-%Y') AS yearcompleted
					FROM vcn_cma_user_course					
					WHERE USER_COURSE_ID = :usercourseid ";
			
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':usercourseid', $params['usercourseid'], PDO::PARAM_INT);
			$stmt->execute();
			$course_info = $stmt->fetchAll();
			
			$this->setResult($course_info);
		
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
		
		return $this->result;
	}
	
	public function getMilitaryCourseDetails($params) {
		
		try {
		
			$db = Resources_PdoMysql::getConnection();
		
			$sql_course_info = "SELECT ace_id AS ace_id, 
													first_title AS first_title,
													second_title AS second_title,
													objective AS objective,
													instruction AS instruction,
													start_date AS start_date,
													end_date AS end_date 
													FROM vcn_ace_course 
													WHERE ace_id = :ace_id AND start_date = :start_date AND end_date = :end_date";
			
			$stmt_course_info = $db->prepare($sql_course_info);
			$stmt_course_info->bindParam(':ace_id', $params['ace_id'], PDO::PARAM_STR);
			$stmt_course_info->bindParam(':start_date', $params['start_date'], PDO::PARAM_INT);
			$stmt_course_info->bindParam(':end_date', $params['end_date'], PDO::PARAM_INT);
			$stmt_course_info->execute();
			$result_course_info = $stmt_course_info->fetchAll();
			
			$sql_military_course_id = "SELECT course_id FROM vcn_ace_military_course WHERE ace_id = :ace_id AND start_date = :start_date AND end_date = :end_date";
			$stmt_military_course_id = $db->prepare($sql_military_course_id);
			$stmt_military_course_id->bindParam(':ace_id', $params['ace_id'], PDO::PARAM_STR);
			$stmt_military_course_id->bindParam(':start_date', $params['start_date'], PDO::PARAM_INT);
			$stmt_military_course_id->bindParam(':end_date', $params['end_date'], PDO::PARAM_INT);
			$stmt_military_course_id->execute();
			$result_military_course_id = $stmt_military_course_id->fetchAll();
			
			$sql_course_credit_info = "SELECT course_credit, 
																course_credit_unit, course_condition, course_subject, course_conjunction 
																FROM vcn_ace_course_credit WHERE ace_id = :ace_id AND start_date = :start_date AND end_date = :end_date ORDER BY course_conjunction ASC";
			$stmt_course_credit_info = $db->prepare($sql_course_credit_info);
			$stmt_course_credit_info->bindParam(':ace_id', $params['ace_id'], PDO::PARAM_STR);
			$stmt_course_credit_info->bindParam(':start_date', $params['start_date'], PDO::PARAM_INT);
			$stmt_course_credit_info->bindParam(':end_date', $params['end_date'], PDO::PARAM_INT);
			$stmt_course_credit_info->execute();
			$result_course_credit_info = $stmt_course_credit_info->fetchAll();
			
			$consolidated_data = array();
			foreach ($result_course_info as $row_course_info) {
				$consolidated_data['course_info'] = $row_course_info;
			}
			
			$military_course_id_array = array();
			foreach ($result_military_course_id as $row_military_course_id) {
				$consolidated_data['military_course_id'][] = $row_military_course_id;
			}
			
			$course_credit_info_array = array();
			foreach ($result_course_credit_info as $row_course_credit_info) {
				$consolidated_data['course_credit_info'][] = $row_course_credit_info;
			}
			
			$this->setResult($consolidated_data);
		
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
		
		return $this->result;
		
	}
	
	
	function getProfessionalTrainingCourseDetails($params) {
		
		try {
			
			$db = Resources_PdoMysql::getConnection();
			
			$sql_course_info = "SELECT ace_id AS ace_id,
													first_title AS first_title,
													second_title AS second_title,
													objective AS objective,
													instruction AS instruction,
													start_date as start_date,
													end_date as end_date
													FROM vcn_ace_national_course
													WHERE ace_id = :ace_id AND start_date = :start_date AND end_date = :end_date AND branch = :branch";
			
			$stmt_course_info = $db->prepare($sql_course_info);
			$stmt_course_info->bindParam(':ace_id', $params['ace_id'], PDO::PARAM_STR);
			$stmt_course_info->bindParam(':start_date', $params['start_date'], PDO::PARAM_INT);
			$stmt_course_info->bindParam(':end_date', $params['end_date'], PDO::PARAM_INT);
			$stmt_course_info->bindParam(':branch', $params['branch'], PDO::PARAM_STR);
			$stmt_course_info->execute();
			$result_course_info = $stmt_course_info->fetchAll();
			
			$sql_course_credit_info = "SELECT course_credit, 
																course_credit_unit, course_condition, course_subject, course_conjunction 
																FROM vcn_ace_national_course_credit WHERE ace_id = :ace_id AND start_date = :start_date AND end_date = :end_date ORDER BY course_conjunction ASC";
			$stmt_course_credit_info = $db->prepare($sql_course_credit_info);
			$stmt_course_credit_info->bindParam(':ace_id', $params['ace_id'], PDO::PARAM_STR);
			$stmt_course_credit_info->bindParam(':start_date', $params['start_date'], PDO::PARAM_INT);
			$stmt_course_credit_info->bindParam(':end_date', $params['end_date'], PDO::PARAM_INT);
			$stmt_course_credit_info->execute();
			$result_course_credit_info = $stmt_course_credit_info->fetchAll();
			
			$consolidated_data = array();
			foreach ($result_course_info as $row_course_info) {
				$consolidated_data['course_info'] = $row_course_info;
			}
			
			$course_credit_info_array = array();
			foreach ($result_course_credit_info as $row_course_credit_info) {
				$consolidated_data['course_credit_info'][] = $row_course_credit_info;
			}
			
			$this->setResult($consolidated_data);
			
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
		
		return $this->result;
		
	}
	
	function getCreditCoursesByUserid($params) {
		
		$requiredParams = array('userid'); // optional params coursedetail=true coursetype=college_courses/military_courses/professional_courses/national_courses
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
		
		try {
			 
			$db = Resources_PdoMysql::getConnection();
			$result_data = array();
			
			if(!isset($params['coursetype']) || (isset($params['coursetype']) &&  $params['coursetype'] == "college_courses")) {
			
				// College Courses
				$sql = " SELECT user_course_id AS usercourseid, military_yn AS coursetype,
								course_code AS code,
								course_credit AS credit,
								course_name AS name,
								institution_name AS school,
								date_completed AS completed
							FROM vcn_cma_user_course
							WHERE USER_ID = :userid
							AND MILITARY_YN is NULL
							ORDER BY course_name ";
			
				$binds = array(
						':userid' => $params['userid'],					
				);
			
				$stmt = $db->prepare($sql);
				$stmt->execute($binds);
			
				$collegecourse_result = $stmt->fetchAll();			
				
				$result_data['collegecourses'] = $collegecourse_result;
				
			}
			
			if(!isset($params['coursetype']) || (isset($params['coursetype']) &&  $params['coursetype'] == "military_courses")) {
				
				// Military Courses 
				$sql = " SELECT c.user_course_id AS usercourseid, c.military_yn AS coursetype,
				              	SUBSTRING_INDEX(course_code, '|', 1) as code,
						        SUBSTRING_INDEX(SUBSTRING_INDEX(course_code, '|', 2), '|', -1) as startdate,
						        SUBSTRING_INDEX(course_code, '|', -1) as stopdate, 
						        SUBSTRING_INDEX(SUBSTRING_INDEX(course_code, '|', 1), '-', 1) as acecode,
						        a.first_title AS name ";
				
				if(isset($params['coursedetail']) &&  $params['coursedetail'] == true) { // Only if detail is required
				  $sql .= " 	,
								a.second_title AS namesecond,
				  				a.objective AS objective,
								a.instruction AS instruction,
								a.credit_info AS additionalcreditinfo,
								b.course_credit AS credit,
								b.course_subject AS subject,
								SUBSTRING_INDEX(SUBSTRING_INDEX(course_code, '|', 1), '-', 1) AS company ";
				}
				
				$sql .= " 	FROM vcn_cma_user_course c ";				
				$sql .= "	LEFT OUTER JOIN vcn_ace_course a ON (a.ace_id = SUBSTRING_INDEX(c.course_code, '|', 1)) ";
				
				if(isset($params['coursedetail']) &&  $params['coursedetail'] == true) { // Only if detail is required
				  $sql .= "	LEFT OUTER JOIN vcn_ace_course_credit b ON ( (b.ace_id = a.ace_id) AND (b.start_date = a.start_date) AND (b.end_date = a.end_date) ) ";
				}
								
				$sql .= "	WHERE user_id = :userid 
							AND military_yn = 'Y'
							AND a.start_date = SUBSTRING_INDEX(SUBSTRING_INDEX(c.course_code, '|', 2), '|', -1)
							AND a.end_date = SUBSTRING_INDEX(c.course_code, '|', -1) 
							ORDER BY a.first_title";				
					
				$binds = array(
						':userid' => $params['userid'],
				);
					
				$stmt = $db->prepare($sql);
				$stmt->execute($binds);
					
				$militarycourse_result = $stmt->fetchAll();
			
				$result_data['militarycourses'] = $militarycourse_result;
			
			}
			
			if(!isset($params['coursetype']) || (isset($params['coursetype']) &&  $params['coursetype'] == "professional_courses")) {
					 
				// Professional Training Courses
				$sql = " SELECT c.user_course_id AS usercourseid, c.military_yn AS coursetype,
								SUBSTRING_INDEX(course_code, '|', 1) as code,
								SUBSTRING_INDEX(SUBSTRING_INDEX(course_code, '|', 2), '|', -1) as startdate,
								SUBSTRING_INDEX(course_code, '|', -1) as stopdate,
								SUBSTRING_INDEX(SUBSTRING_INDEX(course_code, '|', 1), '-', 1) as acecode,
								a.first_title AS name ";
			
				if(isset($params['coursedetail']) &&  $params['coursedetail'] == true) { // Only if detail is required
					$sql .= " 	,
								a.second_title AS namesecond,
								a.objective AS objective,
								a.instruction AS instruction,
								a.credit_info AS additionalcreditinfo,
								a.description AS description,
								a.goals AS goals,
								b.course_credit AS credit,
								b.course_subject AS subject,
								d.company_name AS company ";
				}
			
				$sql .= " 	FROM vcn_cma_user_course c ";
				$sql .= "	LEFT OUTER JOIN vcn_ace_national_course a ON (a.ace_id = SUBSTRING_INDEX(c.course_code, '|', 1)) ";
			
				if(isset($params['coursedetail']) &&  $params['coursedetail'] == true) { // Only if detail is required
					$sql .= "	LEFT OUTER JOIN vcn_ace_national_course_credit b 
									ON ( (b.ace_id = a.ace_id) AND (b.start_date = a.start_date) AND (b.end_date = a.end_date) )
								LEFT OUTER JOIN vcn_ace_national_course_company d 
									ON ( (d.ace_code = SUBSTRING_INDEX(SUBSTRING_INDEX(course_code, '|', 1), '-', 1)) AND d.ace_type = 'course' ) ";
				}
			
				$sql .= "	WHERE user_id = :userid
							AND military_yn = 'C'
							AND a.start_date = SUBSTRING_INDEX(SUBSTRING_INDEX(c.course_code, '|', 2), '|', -1)
							AND a.end_date = SUBSTRING_INDEX(c.course_code, '|', -1)
							ORDER BY a.first_title ";
					
				$binds = array(
						':userid' => $params['userid'],
				);
					
				$stmt = $db->prepare($sql);
				$stmt->execute($binds);
					
				$professionalcourse_result = $stmt->fetchAll();
					
				$result_data['professionalcourses'] = $professionalcourse_result;
					
			}
		
			if(!isset($params['coursetype']) || (isset($params['coursetype']) &&  $params['coursetype'] == "national_courses")) {
				
				// National Exams Courses					
				$sql = " SELECT c.user_course_id AS usercourseid, c.military_yn AS coursetype,
								SUBSTRING_INDEX(course_code, '|', 1) as code,
								SUBSTRING_INDEX(SUBSTRING_INDEX(course_code, '|', 2), '|', -1) as startdate,
								SUBSTRING_INDEX(course_code, '|', -1) as stopdate,
								SUBSTRING_INDEX(SUBSTRING_INDEX(course_code, '|', 1), '-', 1) as acecode,
								a.first_title AS name ";
					
				if(isset($params['coursedetail']) &&  $params['coursedetail'] == true) { // Only if detail is required
					$sql .= " 	,
								a.second_title AS namesecond,
								a.objective AS objective,
								a.instruction AS instruction,
								a.credit_info AS additionalcreditinfo,
								a.description AS description,
								a.goals AS goals,
								b.credit_info AS credit,
								d.company_name AS company ";
				}
					
				$sql .= " 	FROM vcn_cma_user_course c ";
				$sql .= "	LEFT OUTER JOIN vcn_ace_national_course a ON (a.ace_id = SUBSTRING_INDEX(c.course_code, '|', 1)) ";
					
				if(isset($params['coursedetail']) &&  $params['coursedetail'] == true) { // Only if detail is required
					$sql .= "	LEFT OUTER JOIN vcn_ace_national_exam_credit b 
									ON ( (b.ace_id = a.ace_id) AND (b.start_date = a.start_date) AND (b.end_date = a.end_date) )
								LEFT OUTER JOIN vcn_ace_national_course_company d 
									ON ( (d.ace_code = SUBSTRING_INDEX(SUBSTRING_INDEX(course_code, '|', 1), '-', 1)) AND d.ace_type = 'exam' ) ";
				}
					
				$sql .= "	WHERE user_id = :userid
							AND military_yn = 'E'
							AND a.start_date = SUBSTRING_INDEX(SUBSTRING_INDEX(c.course_code, '|', 2), '|', -1)
							AND a.end_date = SUBSTRING_INDEX(c.course_code, '|', -1)
							ORDER BY a.first_title ";
					
				$binds = array(
						':userid' => $params['userid'],
				);
					
				$stmt = $db->prepare($sql);
				$stmt->execute($binds);
					
				$nationalcourse_result = $stmt->fetchAll();
					
				$result_data['nationalcourses'] = $nationalcourse_result;
					
			}
			
			if(!isset($params['coursetype']) || (isset($params['coursetype']) &&  $params['coursetype'] == "vcn_courses")) {
					
				// VCN Courses 
				$sql = " SELECT vcuc.user_course_id AS usercourseid, vcuc.military_yn AS coursetype, vcuc.date_completed AS completed,
								 vc.COURSE_ID, vc.COURSE_CODE, vc.COURSE_TITLE, vc.ONLINE_COURSE_URL, vc.ONLINE_COURSE_URL_FLAG
							FROM vcn_course vc, vcn_cma_user_course vcuc
							WHERE vcuc.USER_ID = :userid AND vcuc.MILITARY_YN = 'V' AND vcuc.COURSE_ID=vc.COURSE_ID
							ORDER BY vc.COURSE_TITLE ";			
					
				$binds = array(
						':userid' => $params['userid'],
				);
					
				$stmt = $db->prepare($sql);
				$stmt->execute($binds);
					
				$vcncourse_result = $stmt->fetchAll();
					
				$result_data['vcncourses'] = $vcncourse_result;
					
			}
			
			
			$this->setResult($result_data);
			 
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
		
		return $this->result;
		
	}
	
	public function deleteCreditCoursesByUserCourseid($params) {
	
		$requiredParams = array('userid', 'usercourseid');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
		
		try {
			$success = array(false);
						
			$db = Resources_PdoMysql::getConnection();
						
			$sql = "DELETE from vcn_cma_user_course         				
					WHERE USER_COURSE_ID = :usercourseid AND USER_ID = :userid";							
			$stmt = $db->prepare($sql);						
			$stmt->bindParam(':userid', $params['userid'], PDO::PARAM_INT);
			$stmt->bindParam(':usercourseid', $params['usercourseid'], PDO::PARAM_INT);			
			$stmt->execute();		
				
			$success = array(true);		
			$this->setResult($success);
		
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
		
		return $this->result;
			
	}	
	
	function savePlaCoursesToCma($params) {
		
		try {
			
			$ace_id = isset($params['ace_id']) ? $params['ace_id'] : 'NA';
			$start_date = isset($params['start_date']) ? $params['start_date'] : 'NA';
			$end_date = isset($params['end_date']) ? $params['end_date'] : 'NA';
			$user_id = isset($params['user_id']) ? $params['user_id'] : 0;
			$military_yn = $params['military_yn'];
			
			$course_code = $ace_id.'|'.$start_date.'|'.$end_date;
			
			$db = Resources_PdoMysql::getConnection();
			
			$sql = "SELECT COUNT(user_course_id) AS count_course FROM vcn_cma_user_course WHERE user_id = :user_id AND course_code = :course_code";
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':course_code', $course_code, PDO::PARAM_STR);
			$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
			$stmt->execute();
			$result_count_course = $stmt->fetch();
			
			if ($result_count_course['count_course'] == 0) {
				$sql_insert = "INSERT INTO vcn_cma_user_course(course_code, military_yn, created_time, user_id) VALUES(:course_code, :military_yn, now(), :user_id)";
				$stmt = $db->prepare($sql_insert);
				$stmt->bindParam(':course_code', $course_code, PDO::PARAM_STR);
				$stmt->bindParam(':military_yn', $military_yn, PDO::PARAM_STR);
				$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
					
				$exec = $stmt->execute();
				if ($exec) {
					$result = TRUE;
				} else {
					$result = FALSE;
				}
			} else {
				$result = TRUE;
			}
			
			$this->setResult(array('result' => $result));
			
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
		
		return $this->result;
		
	}
	
	function getNationalExamDetails($params) {
	
		try {
				
			$db = Resources_PdoMysql::getConnection();
				
			$sql_course_info = "SELECT ace_id AS ace_id,
													first_title AS first_title,
													second_title AS second_title,
													objective AS objective,
													instruction AS instruction,
													start_date as start_date,
													end_date as end_date
													FROM vcn_ace_national_course
													WHERE ace_id = :ace_id AND start_date = :start_date AND end_date = :end_date AND branch = :branch";
				
			$stmt_course_info = $db->prepare($sql_course_info);
			$stmt_course_info->bindParam(':ace_id', $params['ace_id'], PDO::PARAM_STR);
			$stmt_course_info->bindParam(':start_date', $params['start_date'], PDO::PARAM_INT);
			$stmt_course_info->bindParam(':end_date', $params['end_date'], PDO::PARAM_INT);
			$stmt_course_info->bindParam(':branch', $params['branch'], PDO::PARAM_STR);
			$stmt_course_info->execute();
			$result_course_info = $stmt_course_info->fetchAll();
				
			$sql_course_credit_info = "SELECT credit_info FROM vcn_ace_national_exam_credit WHERE ace_id = :ace_id AND start_date = :start_date AND end_date = :end_date";
			$stmt_course_credit_info = $db->prepare($sql_course_credit_info);
			$stmt_course_credit_info->bindParam(':ace_id', $params['ace_id'], PDO::PARAM_STR);
			$stmt_course_credit_info->bindParam(':start_date', $params['start_date'], PDO::PARAM_INT);
			$stmt_course_credit_info->bindParam(':end_date', $params['end_date'], PDO::PARAM_INT);
			$stmt_course_credit_info->execute();
			$result_course_credit_info = $stmt_course_credit_info->fetchAll();
				
			$consolidated_data = array();
			foreach ($result_course_info as $row_course_info) {
				$consolidated_data['course_info'] = $row_course_info;
			}
				
			$course_credit_info_array = array();
			foreach ($result_course_credit_info as $row_course_credit_info) {
				$consolidated_data['course_credit_info'][] = $row_course_credit_info;
			}
				
			$this->setResult($consolidated_data);
				
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
	
		return $this->result;
	
	}
	
	
	function savePlaCollegeCreditToCma($params) {
		
		try {
			
			$db = Resources_PdoMysql::getConnection();
			
			$sql = "INSERT INTO vcn_cma_user_course(institution_name, course_code, course_name, course_credit, course_grade, program_name, date_completed, created_time, user_id) 
							VALUES(:school_name, :course_number, :course_name, :course_credit, :course_grade, :program_name, :year_course_completed, now(), :user_id)";
			
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':school_name', $params['school_name'], PDO::PARAM_STR);
			$stmt->bindParam(':course_number', $params['course_number'], PDO::PARAM_STR);
			$stmt->bindParam(':course_name', $params['course_name'], PDO::PARAM_STR);
			$stmt->bindParam(':course_credit', $params['num_credit_hours'], PDO::PARAM_INT);
			$stmt->bindParam(':course_grade', $params['final_grade'], PDO::PARAM_STR);
			$stmt->bindParam(':program_name', $params['program_name'], PDO::PARAM_STR);
			$stmt->bindParam(':year_course_completed', $params['year_course_completed'], PDO::PARAM_STR);
			$stmt->bindParam(':user_id', $params['user_id'], PDO::PARAM_INT);
			
			$exec = $stmt->execute();
			if ($exec) {
				$result = TRUE;
			} else {
				$result = FALSE;
			}
			
			$this->setResult(array('result' => $result));
			
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
		
		return $this->result;
		
	}
	
	public function getUserCourseByCourseInfo($params) {
			
		$requiredParams = array('userid', 'coursetype', 'courseid', 'coursestartdate', 'courseenddate');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
		
		try {
			$db = Resources_PdoMysql::getConnection();
	
			$coursetype = strtoupper($params['coursetype']);			
			 
			$binds = array();			
	
			$sql = " SELECT c.USER_COURSE_ID AS usercourseid
			FROM vcn_cma_user_course c
			WHERE c.user_id = :user_id			 
			AND UPPER(c.MILITARY_YN) = :coursetype
			AND c.COURSE_CODE = :coursecode 
			";
					
			$binds[':user_id'] = $params['userid'];			 
			$binds[':coursetype'] = $coursetype;
			$binds[':coursecode'] = $params['courseid']."|".$params['coursestartdate']."|".$params['courseenddate'];
				
			$stmt = $db->prepare($sql);
			$stmt->execute($binds);
	
			$result = $stmt->fetchAll();
						
			$this->setResult($result);
	
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage()); error_log($e->getMessage(), 3, ini_get('error_log'));
	}
	
	return $this->result;
	}

	
}
 