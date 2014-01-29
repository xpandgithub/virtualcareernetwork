<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php

/**
 * Ability Class
 * 
 * 
 * @package    VCN
 * @subpackage
 * @author     
 * @version    SVN: $Id:$
 */
class VCN_Model_VCNProvider extends VCN_Model_Base_VCNBase {
	
	// Gives provider's basic info including name and contact based on LAT-LONG
	public function getProvider($params) {
		
		$requiredParams = array('latitude', 'longitude');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
	
	    try {
	      
	      $db = Resources_PdoMysql::getConnection();
	
	      $sql = " SELECT instnm, addr, city, stabbr, oz.zip, gentele, 
	                   VCNGetDistanceBetweenTwoPoints(:latitude, :longitude, mz.latitude, mz.longitude ) as distance 
	             FROM vcn_provider oz 
	             JOIN vcn_master_zipcode mz ON (LEFT(oz.zip, 5) = mz.zip) 
	             WHERE oz.zip IS NOT NULL 
	             AND mz.latitude IS NOT NULL 
	             AND sector = 4 
	             ORDER BY distance ASC 
	             LIMIT 1 ";
	
	      $binds = array(
	          ':latitude' => $params['latitude'],
	          ':longitude' => $params['longitude'],
	      );
	
	      $stmt = $db->prepare($sql);
	      $stmt->execute($binds);
	
	      $result = $stmt->fetchAll();
	
	      $data = array('provider' => $result);
	
	      $this->setResult($data);  
	    
	    } catch (Exception $e) {
	      $this->setResult(NULL, $e->getMessage());
	    }
	
		return $this->result;
		 
	}
	
	// Gives provider's basic info including name and contact based on unitid
	public function getProviderByUnitid($params) {
	
		$requiredParams = array('unitid');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
	
	    try {
	      
	      $db = Resources_PdoMysql::getConnection();
	
	      $sql = " SELECT instnm, addr, city, stabbr, zip, gentele, webaddr, faidurl, faidurl_flag
	             FROM vcn_provider 			    
	             WHERE unitid = :unitid
	             LIMIT 1 ";
	
	      $binds = array(
	          ':unitid' => $params['unitid'],
	      );
	
	      $stmt = $db->prepare($sql);
	      $stmt->execute($binds);
	
	      $result = $stmt->fetchAll();
	
	      $data = array('provider' => $result);
	
	      $this->setResult($data);  
	    
	    } catch (Exception $e) {
	      $this->setResult(NULL, $e->getMessage());
	    }
		
		return $this->result;
			
	}
	
	// Gives provider's basic info based on programid
	public function getProviderByProgramid($params) {
	
		$requiredParams = array('programid');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
	
		try {
	
			$db = Resources_PdoMysql::getConnection();

			$sql = "SELECT v7.addr AS addr,
						v7.city AS city,
						v7.faidurl AS faidurl,
						v7.faidurl_flag AS faidurlflag,
						v7.gentele AS phone,
						v7.instnm AS name,
						v7.min_gpa_for_transfer AS mingpafortransfer,
						v7.stabbr AS state,
						v7.transfer_policy AS transferpolicy,
						v7.unitid AS unitid,
						v7.zip AS zipcode,
						(case when v.admission_url is not null then v.admission_url else v7.adminurl end) AS adminurl,
						(case when v.admission_url is not null then v.admission_url_flag else v7.adminurl_flag end) AS adminurlflag
					FROM vcn_program v
					INNER JOIN vcn_provider v7 ON v.unitid = v7.unitid
					WHERE v.program_id = :programid
	            	LIMIT 1 ";
	
			$binds = array(
					':programid' => $params['programid'],
			);
	
			$stmt = $db->prepare($sql);
			$stmt->execute($binds);
	
			$result = $stmt->fetchAll();
	
			$data = array('providerdetail' => $result);
	
			$this->setResult($data);
	
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
	
		return $this->result;
			
	}
	
	// Gives provider's detail info based on unitid
	public function getProviderDetailByUnitid($params) {
	
		$requiredParams = array('unitid');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
	
	    try {
	      
	      $db = Resources_PdoMysql::getConnection();
	
	      $sql = "SELECT v.unitid AS unitid, 
		            v.addr AS addr, 
		            v.applurl AS applurl, 
		            v.applurl_flag AS applurlflag, 
		            v.city AS city, 
		            v.faidurl AS faidurl, 
		            v.faidurl_flag AS faidurlflag, 
		            v.gentele AS phone, 
		            v.instnm AS name, 
		            v.stabbr AS state, 
		            v.status AS status, 
		            v.webaddr AS webaddr, 
		            v.webaddr_flag AS webaddrflag, 
		            v.logo_image AS logoimage,
		            v.zip AS zipcode, 
		            v2.act_composite_25th_percentile_score AS actcomposite25thpercentilescore, 
		            v2.act_composite_75th_percentile_score AS actcomposite75thpercentilescore, 
		            v2.act_english_25th_percentile_score AS actenglish25thpercentilescore, 
		            v2.act_english_75th_percentile_score AS actenglish75thpercentilescore, 
		            v2.act_math_25th_percentile_score AS actmath25thpercentilescore, 
		            v2.act_math_75th_percentile_score AS actmath75thpercentilescore, 
		            v2.act_writing_25th_percentile_score AS actwriting25thpercentilescore, 
		            v2.act_writing_75th_percentile_score AS actwriting75thpercentilescore, 
		            v2.books_and_supplies AS booksandsupplies, 
		            v2.combined_charge_room_board AS combinedchargeroomboard, 
		            v2.first_time_degree_certificate_undergrad_enrollment AS firsttimedegreecertificateundergradenrollment, 
		            v2.graduate_enrollment AS graduateenrollment, 
		            v2.in_state_credit_charge_part_time_grad AS instatecreditchargeparttimegrad, 
		            v2.in_state_credit_charge_part_time_undergrad AS instatecreditchargeparttimeundergrad, 
		            v2.mission_statement AS missionstatement, 
		            v2.mission_statement_url AS missionstatementurl, 
		            v2.mission_statement_url_flag AS missionstatementurlflag, 
		            v2.out_of_state_credit_charge_part_time_grad AS outofstatecreditchargeparttimegrad, 
		            v2.out_of_state_credit_charge_part_time_undergrad AS outofstatecreditchargeparttimeundergrad, 
		            v2.percent_admitted_total AS percentadmittedtotal, 
		            v2.percent_american_indian_or_alaska_native AS percentamericanindianoralaskanative, 
		            v2.percent_asian_native_hawaiian_pacific_islander AS percentasiannativehawaiianpacificislander, 
		            v2.percent_black_or_african_american AS percentblackorafricanamerican, 
		            v2.percent_hispanic_latino AS percenthispaniclatino, 
		            v2.percent_nonresident_alien AS percentnonresidentalien, 
		            v2.percent_race_ethnicity_unknown AS percentraceethnicityunknown, 
		            v2.percent_white AS percentwhite, 
		            v2.percent_women AS percentwomen, 
		            v2.price_in_state_off_campus_family AS priceinstateoffcampusfamily, 
		            v2.price_in_state_off_campus_no_family AS priceinstateoffcampusnofamily, 
		            v2.price_in_state_on_campus AS priceinstateoncampus, 
		            v2.price_out_of_state_off_campus_family AS priceoutofstateoffcampusfamily, 
		            v2.price_out_of_state_off_campus_no_family AS priceoutofstateoffcampusnofamily, 
		            v2.price_out_of_state_on_campus AS priceoutofstateoncampus, 
		            v2.sat_critical_reading_25th_percentile_score AS satcriticalreading25thpercentilescore, 
		            v2.sat_critical_reading_75th_percentile_score AS satcriticalreading75thpercentilescore, 
		            v2.sat_math_25th_percentile_score AS satmath25thpercentilescore, 
		            v2.sat_math_75th_percentile_score AS satmath75thpercentilescore, 
		            v2.sat_writing_25th_percentile_score AS satwriting25thpercentilescore, 
		            v2.sat_writing_75th_percentile_score AS satwriting75thpercentilescore, 
		            v2.total_enrollment AS totalenrollment, 
		            v2.undergraduate_enrollment AS undergraduateenrollment,
		            i.codedesc AS ipedsdesc
		              FROM vcn_provider v 
		          LEFT JOIN vcn_provider_detail v2 ON v.unitid = v2.unitid  
		          LEFT JOIN ipeds_lookup i ON v.sector = i.colcode AND ((i.coltitle = 'SECTOR' AND i.colcode = v.sector))
		          WHERE v.unitid = :unitid";
	
	      $binds = array(
	          ':unitid' => $params['unitid'],
	      );
	
	      $stmt = $db->prepare($sql);
	      $stmt->execute($binds);
	
	      $result = $stmt->fetchAll();
	
	      $data = array('providerdetail' => $result);
	
	      $this->setResult($data);  
	    
	    } catch (Exception $e) {
	      $this->setResult(NULL, $e->getMessage());
	    }
	
		return $this->result;
			
	}
	
	// Gives Provider's Services based on unitid
	public function getProviderServicesByUnitid($params) {
	
		$requiredParams = array('unitid');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
	
	    try {
	      
	      $db = Resources_PdoMysql::getConnection();
	
	      $sql = "SELECT v2.service_name AS servicename 
		          FROM vcn_provider v 
		          LEFT JOIN vcn_provider_service v1 ON v.unitid = v1.unitid 
		          LEFT JOIN vcn_provider_service_type v2 ON v1.service_id = v2.service_id 
		          WHERE v.unitid = :unitid 
		          ORDER BY servicename ";
	
	      $binds = array(
	          ':unitid' => $params['unitid'],
	      );
	
	      $stmt = $db->prepare($sql);
	      $stmt->execute($binds);
	
	      $result = $stmt->fetchAll();
	
	      $data = array('providerservices' => $result);
	
	      $this->setResult($data);  
	    
	    } catch (Exception $e) {
	      $this->setResult(NULL, $e->getMessage());
	    }
	
		return $this->result;
			
	}
	
	// Gives Provider's Entrance Tests based on unitid
	public function getProviderEntranceTestsByUnitid($params) {
	
		$requiredParams = array('unitid');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
	
	    try {
	      
	      $db = Resources_PdoMysql::getConnection();
	
	      $sql = "SELECT v20.min_score AS minscore,
		              v20.hs_grad_or_transfer_student as hsgradortransferstudent,
		              v21.test_name AS testname, 
		              v21.test_description AS testdescription,  
		              v21.test_url AS testurl,
		              v21.test_id AS testid 					
		          FROM vcn_provider v 
		          INNER JOIN vcn_provider_entrance_test v20 ON v.unitid = v20.unitid 
		          LEFT JOIN vcn_test v21 ON v20.test_id = v21.test_id 
		          WHERE v.unitid = :unitid ";
	
	      $binds = array(
	          ':unitid' => $params['unitid'],
	      );
	
	      $stmt = $db->prepare($sql);
	      $stmt->execute($binds);
	
	      $result = $stmt->fetchAll();
	
	      $data = array('providerentrancetests' => $result);
	
	      $this->setResult($data);  
	    
	    } catch (Exception $e) {
	      $this->setResult(NULL, $e->getMessage());
	    }
		
		return $this->result;
			
	}
	
	// Gives Provider's Entrance Tests based on Programid
	public function getProviderEntranceTestsByProgramid($params) {
	
		$requiredParams = array('programid');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
	
		try {
			 
			$db = Resources_PdoMysql::getConnection();
	
			$sql = "SELECT v20.min_score AS minscore,
						v20.hs_grad_or_transfer_student as hsgradortransferstudent,
						v21.test_name AS testname,
						v21.test_description AS testdescription,
						v21.test_url AS testurl,
	             		v21.test_id AS testid
					FROM vcn_program v
					INNER JOIN vcn_provider v7 ON v.unitid = v7.unitid
					INNER JOIN vcn_provider_entrance_test v20 ON v7.unitid = v20.unitid
					LEFT JOIN vcn_test v21 ON v20.test_id = v21.test_id
					WHERE v.program_id = :programid ";
			
			$binds = array(
					':programid' => $params['programid'],
			);
	
			$stmt = $db->prepare($sql);
			$stmt->execute($binds);
	
			$result = $stmt->fetchAll();
	
			$data = array('providerentrancetests' => $result);
	
			$this->setResult($data);
		  
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
	
		return $this->result;
			
	}
	
	// Gives Provider's Required Courses based on unitid
	public function getProviderRequiredCoursesByUnitid($params) {
	
		$requiredParams = array('unitid');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
	
	    try {
	      
	      $db = Resources_PdoMysql::getConnection();
	
	      $sql = "SELECT v22.course_id AS courseid,
		              v25.description AS deliverymodedescription,
		              v23.course_info_url AS courseinfourl, 
                  v23.course_info_url_flag AS courseinfourlflag,
		              v23.course_level AS courselevel,
		              v23.course_title AS coursetitle,
		              v23.course_type AS coursetype,
		              v23.delivery_mode AS deliverymode,
		              v23.description AS description,
		              v23.online_course_url AS onlinecourseurl,
                  v23.online_course_url_flag AS onlinecourseurlflag,
		              v24.description AS subjectareadescription, 
		              v22.min_gpa AS mingpa
		          FROM vcn_provider v 
		          LEFT JOIN vcn_provider_prereq_course v22 ON v.unitid = v22.unitid 
		          LEFT JOIN vcn_course v23 ON v22.course_id = v23.course_id 
		          LEFT JOIN vcn_subject_area v24 ON v23.subject_area = v24.subject_area 
		          LEFT JOIN vcn_course_delivery_mode v25 ON v23.delivery_mode = v25.delivery_mode 
		          WHERE v.unitid = :unitid ";
	
	      $binds = array(
	          ':unitid' => $params['unitid'],
	      );
	
	      $stmt = $db->prepare($sql);
	      $stmt->execute($binds);
	
	      $result = $stmt->fetchAll();
	
	      $data = array('providerrequiredcourses' => $result);
	
	      $this->setResult($data);  
	    
	    } catch (Exception $e) {
	      $this->setResult(NULL, $e->getMessage());
	    }
		
		return $this->result;
			
	}
	
	// Gives Provider's Required Courses based on Programid
	public function getProviderRequiredCoursesByProgramid($params) {
	
		$requiredParams = array('programid');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
	
		try {
			 
			$db = Resources_PdoMysql::getConnection();
			
			$sql = "SELECT v22.course_id AS courseid,
						v25.description AS deliverymodedescription,
						v23.course_info_url AS courseinfourl,
            v23.course_info_url_flag AS courseinfourlflag,
						v23.course_level AS courselevel,
						v23.course_title AS coursetitle,
						v23.course_type AS coursetype,
						v23.delivery_mode AS deliverymode,
						v23.description AS description,
						v23.online_course_url AS onlinecourseurl,
            v23.online_course_url_flag AS onlinecourseurlflag,
						v24.description AS subjectareadescription,
						v22.min_gpa AS mingpa
					FROM vcn_program v
					INNER JOIN vcn_provider v7 ON v.unitid = v7.unitid
					LEFT JOIN vcn_provider_prereq_course v22 ON v7.unitid = v22.unitid
					LEFT JOIN vcn_course v23 ON v22.course_id = v23.course_id
					LEFT JOIN vcn_subject_area v24 ON v23.subject_area = v24.subject_area
					LEFT JOIN vcn_course_delivery_mode v25 ON v23.delivery_mode = v25.delivery_mode
					WHERE v.program_id = :programid ";
	
			$binds = array(
					':programid' => $params['programid'],
			);
	
			$stmt = $db->prepare($sql);
			$stmt->execute($binds);
	
			$result = $stmt->fetchAll();
	
			$data = array('providerrequiredcourses' => $result);
	
			$this->setResult($data);
		  
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
	
		return $this->result;
			
	}
	
	// Gives Provider's Degrees Offered based on unitid
	public function getProviderDegreesOfferedByUnitid($params) {
	
		$requiredParams = array('unitid');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
	
	    try {
	      
	      $db = Resources_PdoMysql::getConnection();
	
	      $sql = "SELECT DISTINCT i2.codedesc AS codedesc 
		          FROM vcn_provider v 
		          INNER JOIN vcn_program v5 ON v.unitid = v5.unitid AND (v5.awlevel > 0) 
		          INNER JOIN ipeds_lookup i2 ON v5.awlevel = i2.colcode AND ((i2.coltitle = 'AWLEVEL' AND i2.colcode = v5.awlevel)) 
		          WHERE v.unitid = :unitid 
		          ORDER BY colcode";
	
	      $binds = array(
	          ':unitid' => $params['unitid'],
	      );
	
	      $stmt = $db->prepare($sql);
	      $stmt->execute($binds);
	
	      $result = $stmt->fetchAll();
	
	      $data = array('providerdegreesoffered' => $result);
	
	      $this->setResult($data);  
	    
	    } catch (Exception $e) {
	      $this->setResult(NULL, $e->getMessage());
	    }
	
		return $this->result;
			
	}
	
	// Gives Provider's FAID Offered based on unitid
	public function getProviderFaidOfferedByUnitid($params) {
	
		$requiredParams = array('unitid');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
	
	    try {
	      
	      $db = Resources_PdoMysql::getConnection();
	
	      $sql = "SELECT v2.unitid AS v2unitid, v2.loan_id AS v2loanid, v3.loan_id AS v3loanid,
		              v3.loan_name AS v3loanname, v3.loan_url AS v3loanurl, 
		              v3.loan_url_flag AS v3loanurlflag 
		          FROM vcn_provider v 
		          LEFT JOIN vcn_provider_additional_faid v2 ON v.unitid = v2.unitid 
		          LEFT JOIN vcn_additional_faid v3 ON v2.loan_id = v3.loan_id 	
		          WHERE v.unitid = :unitid ";
	
	      $binds = array(
	          ':unitid' => $params['unitid'],
	      );
	
	      $stmt = $db->prepare($sql);
	      $stmt->execute($binds);
	
	      $result = $stmt->fetchAll();
	
	      $data = array('providerfaidoffered' => $result);
	
	      $this->setResult($data);  
	    
	    } catch (Exception $e) {
	      $this->setResult(NULL, $e->getMessage());
	    }
	
		return $this->result;
			
	}
	
	// Updates provider's logo based on unitid
	public function updateProviderLogoByUnitid($params) {
	
		$requiredParams = array('unitid', 'updatedby', 'logo_image');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
	
		try {
	
			$db = Resources_PdoMysql::getConnection();
				
			$sql = "UPDATE vcn_provider
						SET logo_image = :logo_image,							
							updated_by = :updatedby,
							updated_time = Now()
	   			    WHERE unitid = :unitid";	
							
			$stmt = $db->prepare($sql);
			
			$stmt->bindParam(':logo_image', $params['logo_image'], PDO::PARAM_LOB); //$stmt->bindParam(':logo_image', $params['logo_image'], PDO::PARAM_STR);
			$stmt->bindParam(':updatedby', $params['updatedby'], PDO::PARAM_INT);
			$stmt->bindParam(':unitid', $params['unitid'], PDO::PARAM_INT);
			
			$stmt->execute();
			
			$this->setResult(array(true));
				
		} catch (Exception $e) { 
			$this->setResult(NULL, $e->getMessage());
		}
	
		return $this->result;
			
	}
	
	// Updates provider's info based on unitid
	public function updateProviderInfoByUnitid($params) {
	
		$requiredParams = array('unitid', 'updatedby', 'name', 'addr', 'city', 'state', 'zipcode', 'phone', 'applurl', 'faidurl', 'webaddr');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
	
		try {
		
			$db = Resources_PdoMysql::getConnection();
			
			$sql = "UPDATE vcn_provider
						SET instnm = :name,
							addr = :addr,
							city = :city,
							stabbr = :state,
							zip = :zipcode,
							gentele = :phone,				
							applurl = :applurl,
							faidurl = :faidurl,
							webaddr = :webaddr,
							updated_by = :updatedby,
							updated_time = Now()				
	   			    WHERE unitid = :unitid";			
			
			$stmt = $db->prepare($sql);
			
			$stmt->bindParam(':name', $params['name'], PDO::PARAM_STR);
			$stmt->bindParam(':addr', $params['addr'], PDO::PARAM_STR);
			$stmt->bindParam(':city', $params['city'], PDO::PARAM_STR);
			$stmt->bindParam(':state', $params['state'], PDO::PARAM_STR);
			$stmt->bindParam(':zipcode', $params['zipcode'], PDO::PARAM_STR);
			$stmt->bindParam(':phone', $params['phone'], PDO::PARAM_STR);
			$stmt->bindParam(':applurl', $params['applurl'], PDO::PARAM_STR);
			$stmt->bindParam(':faidurl', $params['faidurl'], PDO::PARAM_STR);
			$stmt->bindParam(':webaddr', $params['webaddr'], PDO::PARAM_STR);				
			$stmt->bindParam(':updatedby', $params['updatedby'], PDO::PARAM_INT);
			$stmt->bindParam(':unitid', $params['unitid'], PDO::PARAM_INT);
			
			$stmt->execute();
			
			$this->setResult(array(true));
			
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
		
		return $this->result;	
			
	}
	
	// Updates provider's detail info based on unitid
	public function updateProviderDetailByUnitid($params) {
	
		$requiredParams = array('unitid', 'updatedby', 'missionstatement', 'missionstatementurl');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
	
		try {
						
			$db = Resources_PdoMysql::getConnection();
		
			$sql = "UPDATE vcn_provider_detail
						SET mission_statement = :missionstatement,
							mission_statement_url = :missionstatementurl,
							updated_by = :updatedby,
							updated_time = Now()
	   			    WHERE unitid = :unitid";		
		
			$stmt = $db->prepare($sql);
			
			$stmt->bindParam(':missionstatement', $params['missionstatement'], PDO::PARAM_STR);
			$stmt->bindParam(':missionstatementurl', $params['missionstatementurl'], PDO::PARAM_STR);			
			$stmt->bindParam(':updatedby', $params['updatedby'], PDO::PARAM_INT);
			$stmt->bindParam(':unitid', $params['unitid'], PDO::PARAM_INT);
			
			$stmt->execute();
			
			$this->setResult(array(true));
		
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
		
		return $this->result;
			
	}
	
	// Updates Provider's Entrance Tests based on unitid
	public function updateProviderEntranceTestsByUnitid($params) {
	
		$requiredParams = array('unitid','updatedby','oldtestidlist','newtestidcount');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
		
		try {
			$success = array(true);
			
			$db = Resources_PdoMysql::getConnection();

			if(isset($params['oldtestidlist']) &&  $params['oldtestidlist'] != "") {
				// Delete from vcn_provider_entrance_test
				$sql = "DELETE FROM vcn_provider_entrance_test        				
						WHERE unitid = :unitid ";
								
				$stmt = $db->prepare($sql);
				
				$stmt->bindParam(':unitid', $params['unitid'], PDO::PARAM_INT);
				//$stmt->bindParam(':oldtestidlist', $params['oldtestidlist'], PDO::PARAM_STR);
				
				$stmt->execute();
				
				// Delete from vcn_test
				// FK constraint is there : Integrity constraint violation
				/*$sql = "DELETE FROM vcn_test
						WHERE test_id IN (:oldtestidlist)";				
					
				$stmt = $db->prepare($sql);
				
				$stmt->bindParam(':oldtestidlist', $params['oldtestidlist'], PDO::PARAM_STR);
				
				$stmt->execute();
				*/
				$success[] = "Delete success.";
			}
			
			if(isset($params['newtestidcount']) && $params['newtestidcount'] > 0) {
				for($i=0;$i<$params['newtestidcount'];$i++) {
					// Insert into vcn_test
					$sql = "INSERT INTO vcn_test
										(test_name, test_description, created_by, created_time)
								 VALUES (:test_name, :test_description, :updatedby, Now())";
			
					$stmt = $db->prepare($sql);
					
					$stmt->bindParam(':test_name', $params['testnamelist'][$i], PDO::PARAM_INT);
					$stmt->bindParam(':test_description', $params['testdesclist'][$i], PDO::PARAM_STR);
					$stmt->bindParam(':updatedby', $params['updatedby'], PDO::PARAM_INT);
					
					$stmt->execute();
					
					$lastInsertId = $db->lastInsertId();
					$success[] = " vcn test ". $lastInsertId ;
					// Insert into vcn_provider_entrance_test
					$sql = "INSERT INTO vcn_provider_entrance_test
										(unitid, test_id, min_score, created_by, created_time)
								 VALUES (:unitid, :testid, :minscore, :updatedby, Now())";
						
					$stmt = $db->prepare($sql);

					$stmt->bindParam(':unitid', $params['unitid'], PDO::PARAM_INT);
					$stmt->bindParam(':testid', $lastInsertId, PDO::PARAM_INT);
					$stmt->bindParam(':minscore', $params['testminscorelist'][$i], PDO::PARAM_STR);
					$stmt->bindParam(':updatedby', $params['updatedby'], PDO::PARAM_INT);
						
					$stmt->execute();
					
					$lastInsertId = $db->lastInsertId();
					$success[] = " vcn provider entrance test ". $lastInsertId ;
					
					$success[] = "Insert success.";
				}
			}
			
			$this->setResult($success);
		
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
		
		return $this->result;
			
	}
	
	// Updates Provider's Required Courses based on unitid
	public function updateProviderRequiredCoursesByUnitid($params) {
		
		$requiredParams = array('unitid','updatedby','oldcourseidlist','newcourseidcount');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
		
		try {
			$success = array(true);
			
			$db = Resources_PdoMysql::getConnection();

			if(isset($params['oldcourseidlist']) &&  $params['oldcourseidlist'] != "") {
				// Delete from vcn_provider_prereq_course
				$sql = "DELETE FROM vcn_provider_prereq_course        				
						WHERE unitid = :unitid ";
								
				$stmt = $db->prepare($sql);
				
				$stmt->bindParam(':unitid', $params['unitid'], PDO::PARAM_INT);
				//$stmt->bindParam(':oldcourseidlist', $params['oldcourseidlist'], PDO::PARAM_STR);
				
				$stmt->execute();
				
				// Delete from vcn_course
				// FK constraint is there : Integrity constraint violation
				/*$sql = "DELETE FROM vcn_course
						WHERE course_id IN (:oldcourseidlist)";				
					
				$stmt = $db->prepare($sql);
				
				$stmt->bindParam(':oldcourseidlist', $params['oldcourseidlist'], PDO::PARAM_STR);
				
				$stmt->execute();
				*/
				$success[] = "Delete success.";
			}
			
			if(isset($params['newcourseidcount']) && $params['newcourseidcount'] > 0) {
				for($i=0;$i<$params['newcourseidcount'];$i++) {
					// Insert into vcn_course
					$sql = "INSERT INTO vcn_course
										(unitid, course_title, description, course_level, created_by, created_time)
								 VALUES (:unitid, :course_name, :course_description, :course_level, :updatedby, Now())";
			
					$stmt = $db->prepare($sql);
					
					$stmt->bindParam(':unitid', $params['unitid'], PDO::PARAM_INT);
					$stmt->bindParam(':course_name', $params['coursenamelist'][$i], PDO::PARAM_INT);
					$stmt->bindParam(':course_description', $params['coursedesclist'][$i], PDO::PARAM_STR);
					$stmt->bindParam(':course_level', $params['courselevellist'][$i], PDO::PARAM_INT);
					$stmt->bindParam(':updatedby', $params['updatedby'], PDO::PARAM_INT);
					
					$stmt->execute();
					
					$lastInsertId = $db->lastInsertId();
					$success[] = " vcn course ". $lastInsertId ;
					// Insert into vcn_provider_prereq_course
					$sql = "INSERT INTO vcn_provider_prereq_course
										(unitid, course_id, min_gpa, created_by, created_time)
								 VALUES (:unitid, :courseid, :min_gpa, :updatedby, Now())";
						
					$stmt = $db->prepare($sql);

					$stmt->bindParam(':unitid', $params['unitid'], PDO::PARAM_INT);
					$stmt->bindParam(':courseid', $lastInsertId, PDO::PARAM_INT);
					$stmt->bindParam(':min_gpa', $params['coursemingpalist'][$i], PDO::PARAM_STR);
					$stmt->bindParam(':updatedby', $params['updatedby'], PDO::PARAM_INT);
						
					$stmt->execute();
					
					$lastInsertId = $db->lastInsertId();
					$success[] = " vcn provider entrance course ". $lastInsertId ;
					
					$success[] = "Insert success.";
				}
			}
			
			$this->setResult($success);
		
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
		
		return $this->result;
		
	}
	
	// Gives provider's list based on state
	public function getProviderList($params) {
				
		try {
	
			$db = Resources_PdoMysql::getConnection();
	
			$sql = "SELECT p.unitid AS unitid, p.instnm AS name, p.stabbr AS state, p.webaddr AS webaddr, p.webaddr_flag AS webaddr_flag,
					 p.verified_yn AS verified_yn, p.verified_by AS verified_by, p.vhs_yn AS vhs_yn
					FROM vcn_provider p 
					WHERE 1 = 1
					";

			$binds = array();	

			if(isset($params['state'])) {
				$sql .= " AND p.stabbr LIKE :state  ";
				$binds[':state'] = $params['state'].'%';	
			}		
			
			if(isset($params['vhs_yn'])) {
				$sql .= " AND p.vhs_yn = :vhs_yn "; 
				$binds[':vhs_yn'] = $params['vhs_yn'];
			}			
			
			$sql .= " ORDER BY p.stabbr, p.instnm ";
			
			$stmt = $db->prepare($sql);
			$stmt->execute($binds);
	
			$result = $stmt->fetchAll();
			
			$data = array();
			
			foreach ($result as $row) {
				$data[] = array(
						'unitid' => $row['unitid'],
						'name' => $row['name'],
						'state' => $row['state'],
						'webaddr' => $row['webaddr'],
						'webaddrflag' => $row['webaddr_flag'],
						'verifiedyn' => $row['verified_yn'],
						'verifiedby' => $row['verified_by'],
						'vhsyn' => $row['vhs_yn'],							
				);
			}
			
			$this->setResult($data);	
			
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
	
		return $this->result;
			
	}
	
}
 