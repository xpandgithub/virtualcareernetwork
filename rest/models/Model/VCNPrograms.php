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
class VCN_Model_VCNPrograms extends VCN_Model_Base_VCNBase {
	
	public function getPrograms($params) {
		
		try {
			$requiredParams = array('onetcode', 'industry');
			if (!$this->checkParams($params, $requiredParams)) {
				return $this->result;
			}
				
			$db = Resources_PdoMysql::getConnection();
				
			//protecting the XML from breaking because of the notices, so need to check if isset
			if (isset($params['is_dataTable']) && $params['is_dataTable']) {
				$datatableUtil = Resources_DatatableUtilities::getDatatableCommon($params);
				$sortDir = $datatableUtil['sSortDir'];
				$orderByCol = $datatableUtil['sOrderColumn'];				
				$sortDir_1 = $datatableUtil['sSortDir_1']; // Additional (2nd) column for sorting direction param
				$orderByCol_1 = $datatableUtil['sOrderColumn_1']; // Additional (2nd) column for sorting param
				$iSortingCols = $datatableUtil['iSortingCols']; // Count of sorting columns
				$limitIndex = $datatableUtil['iDisplayStart'];
				$displayLength = $datatableUtil['iDisplayLength'];
				$column_display_order_datatable = $datatableUtil['column_display_order_datatable'];
			}
				
			$sql = "SELECT DISTINCT ";
				
			//columns
			$columns = "p.awlevel AS award_level,
								pc.program_id AS program_id,
								c.ciptitle AS ciptitle,
								c.cipcode AS cipcode,
								o.onetcode AS onetcode,
								v.unitid AS unitid,
								v.instnm AS inst_name,
								v.addr AS inst_address,
								v.city AS inst_city,
								v.stabbr AS inst_state_abbrev,
								v.zip AS inst_zip,
								v.gentele AS inst_general_telephone,
								ec.education_category_name AS education_category_name,
								ec.education_category_id AS education_category_id,
                pa.association_id,
								(CASE WHEN TRIM(p.program_name) IS NOT NULL THEN TRIM(p.program_name) ELSE c.ciptitle END) AS program_name ";
				
			//this variable should be used in the count query instead of $columns
			$count_column = "COUNT(DISTINCT pc.program_id) AS count_programs ";
				
			//from
			//do not change the order of the joins when we are trying to build the query. this is the best optimized query
			$from = "FROM vcn_program p
              INNER JOIN vcn_program_cipcode pc ON p.program_id = pc.program_id 
              INNER JOIN vcn_cipcode c ON pc.cipcode = c.cipcode
              INNER JOIN vcn_onetxcip oxc ON pc.cipcode = oxc.cipcode
              INNER JOIN vcn_occupation o ON oxc.onetcode = o.onetcode
              INNER JOIN vcn_occupation_industry oi ON o.onetcode = oi.onetcode AND (oi.industry_id = :industry_id)
              INNER JOIN vcn_provider v ON p.unitid = v.unitid AND v.zip IS NOT NULL 
              LEFT JOIN vcn_provider_association pa ON p.unitid = pa.unitid AND pa.industry_id = :industry_id ";
				
			//where
			$where = " WHERE o.onetcode = :onetcode ";
				
			if (isset($params['award_level']) && $params['award_level'] != 'all') {
				$where .= " AND ec.education_category_id <= :award_level ";
			}
				
			if (isset($params['zipcode'])) {
        
				// no need to hardcode 25 here, if no distance is provided, the vcn application will always pick the default distance defined in vcn.config file. this is just in case
				$distance = isset($params['distance']) ? (!is_numeric($params['distance']) ? 25 : $params['distance']) : 25;
	
				$lat_long_sql = "SELECT latitude, longitude FROM vcn_master_zipcode WHERE zip = :zip";
				$stmt_lat_long = $db->prepare($lat_long_sql);
				$stmt_lat_long->bindParam(':zip', $params['zipcode'], PDO::PARAM_INT);
				$stmt_lat_long->execute();
				$result_lat_long = $stmt_lat_long->fetch(); //since we expect only one row back
	
				$latitude = $result_lat_long['latitude'];
				$longitude = $result_lat_long['longitude'];

        $geoLoc = Resources_GeoLocation::fromDegrees($latitude, $longitude);
        $coordinates = $geoLoc->boundingCoordinates($distance, 'miles');

        $minLat = $coordinates[0]->getLatitudeInDegrees();
        $minLon = $coordinates[0]->getLongitudeInDegrees();
        $maxLat = $coordinates[1]->getLatitudeInDegrees();
        $maxLon = $coordinates[1]->getLongitudeInDegrees();        

				$columns .= ",VCNGetDistanceBetweenTwoPoints($latitude, $longitude, v.latitude, v.longitude) AS distance ";
        
        // This will get appended to the last line of the FROM clause which is: INNER JOIN vcn_provider
				$from .= "         AND ( v.latitude > " . $minLat .
                 "           AND v.latitude < " . $maxLat .
                 "           AND v.longitude > " . $minLon .
                 "           AND v.longitude < " . $maxLon . " ) ";
        
				$where .= " AND VCNGetDistanceBetweenTwoPoints($latitude, $longitude, v.latitude, v.longitude) <= :distance ";
				//this is ideal way rather than doing the calculations all over again, but for some reason, the having clause is not working with PDO, works fine when query is directly run on the Db
				//$where .= " HAVING distance <= 25 ";
			}

			//do not change the order of the joins when we are trying to build the query. this is the best optimized query
			$from .= " LEFT JOIN vcn_edu_category_iped eci ON p.awlevel = eci.iped_lookup_code
								LEFT JOIN vcn_edu_category ec ON eci.education_category_id = ec.education_category_id ";
				
			//build query from the above variables with the order by and limit
			$sql_with_filters = $sql.$columns.$from.$where;
			if (isset($params['is_dataTable']) && $params['is_dataTable']) {				
				if($iSortingCols > 1) {
					$sql_with_filters .= " ORDER BY $column_display_order_datatable[$orderByCol] $sortDir , $column_display_order_datatable[$orderByCol_1] $sortDir_1";
				}else {
					$sql_with_filters .= " ORDER BY $column_display_order_datatable[$orderByCol] $sortDir ";
				}
				$sql_with_filters .= " LIMIT $limitIndex, $displayLength";
			}
			
			//to see the query and the parameters, uncomment the below 2 lines and look for the query in your log file as defined by error_log in your php.ini
			//error_log(print_r($params, true), 3, ini_get('error_log')); //log any variable like param or query to error log file if necessary
			//error_log($sql_with_filters, 3, ini_get('error_log'));

			$stmt = $db->prepare($sql_with_filters);
			$stmt->bindParam(':onetcode', $params['onetcode'], PDO::PARAM_STR);
			$stmt->bindParam(':industry_id', $params['industry'], PDO::PARAM_INT);
			if (isset($params['zipcode'])) {
				$stmt->bindParam(':distance', $distance, PDO::PARAM_INT);
			}
			if (isset($params['award_level']) && $params['award_level'] != 'all') {
				$stmt->bindParam(':award_level', $params['award_level'], PDO::PARAM_INT);
			}
			$stmt->execute();
			$result = $stmt->fetchAll();
				
			//build query without the order by and limit to get the toal number of result sets for data table pagination
			$sql_count = $sql.$count_column.$from.$where;
			$stmt_count = $db->prepare($sql_count);
			$stmt_count->bindParam(':onetcode', $params['onetcode'], PDO::PARAM_STR);
			$stmt_count->bindParam(':industry_id', $params['industry'], PDO::PARAM_INT);
			if (isset($params['zipcode'])) {
				$stmt_count->bindParam(':distance', $distance, PDO::PARAM_INT);
			}
			if (isset($params['award_level']) && $params['award_level'] != 'all') {
				$stmt_count->bindParam(':award_level', $params['award_level'], PDO::PARAM_INT);
			}
			$stmt_count->execute();
			$number_of_rows = $stmt_count->fetchColumn();
				
			$data = array();
			if ($number_of_rows != 0) {
				foreach ($result as $row) {
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

  public function getProgramsByUnitid($params) {
		
		try {
			$requiredParams = array('unitid');
			if (!$this->checkParams($params, $requiredParams)) {
				return $this->result;
			}
			
			$db = Resources_PdoMysql::getConnection();

      $sql = " SELECT v1.program_id AS programid,
                      (CASE WHEN v1.program_name IS NOT NULL THEN v1.program_name ELSE v3.ciptitle END) AS programname, 
                      program_description AS programdesc, v1.duration AS duration, 
                      v4.education_category_id AS awardlevelid, v4.iped_category_name AS awardlevel,
      				        v2.cipcode AS cipcode
               FROM vcn_program v1 
               INNER JOIN vcn_program_cipcode v2 ON v1.program_id = v2.program_id 
               INNER JOIN vcn_cipcode v3 ON v2.cipcode = v3.cipcode 
               LEFT JOIN vcn_edu_category_iped v4 ON v1.awlevel = v4.iped_lookup_code
               WHERE v1.unitid = :unitid";

      if (isset($params['limit'])) {
        $sql .= " LIMIT " . $params['limit'];
      }
      
      if (isset($params['orderby'])) {
        $sql .= " ORDER BY " . $params['orderby'];
        
        if (isset($params['orderdir'])) {
          $sql .= " " . $params['orderdir'];
        }
      }
      
      $binds = array(
          ':unitid' => $params['unitid'],
      );

      $stmt = $db->prepare($sql);
      $stmt->execute($binds);

      $result = $stmt->fetchAll();

      $data = array();

      foreach ($result as $row) {
        
        // return a shortened description of 100 words
        $words = explode(" ", $row['programdesc']);
        $description = implode(" ", array_splice($words, 0, 50));
          
        $data[] = array(
          'id' => $row['programid'],
          'name' => $row['programname'],
          'desc' => $description,
          'duration' => $row['duration'],
          'awardlevelid' => $row['awardlevelid'],
          'awardlevel' => $row['awardlevel'],
        	'cipcode' => $row['cipcode'],
        );
      }

      $this->setResult($data);
			
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
		
		return $this->result;
	}
	
	// Gives Program's Detail based on Programid AND Cipcode
	public function getProgramDetail($params) {
	
		$requiredParams = array('programid', 'cipcode');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
	
		try {
	
			$db = Resources_PdoMysql::getConnection();
	
      		$binds = array(
					':programid' => $params['programid'],
					':cipcode' => $params['cipcode'],
			);
      		
			
			$sql = "SELECT v.awlevel AS awlevel,						
						v.other_cost AS othercost,
						v.other_req AS otherrequirements,
						v.how_to_apply AS howtoapply,					
						v.hs_grad_req AS hsgradreq,					
						v.min_gpa AS mingpa,					
						v.ged_accepted AS gedaccepted,
						v.medical_req AS medicalreq,
						v.immunization_req AS immunizationreq,
						v.legal_req AS legalreq,					
						v.program_contact_email AS programcontactemail,
						v.program_contact_name AS programcontactname,
						v.program_contact_phone AS programcontactphone,
						v.program_id AS programid,
						v.program_url AS programurl,
            			v.program_url_flag AS programurlflag,
						v.admission_url AS admissionurl,
            			v.admission_url_flag AS admissionurlflag,
						v.status AS status,						
						v.total_credits AS totalcredits,	
						v.tuition_in_state_in_district AS tuitioninstateindistrict,
						v.tuition_in_state_out_district AS tuitioninstateoutdistrict,		
						v.tuition_online AS tuitiononline,
						v.tuition_out_state AS tuitionoutstate,	
						v.online AS online,					
						i.iped_category_name AS ipedcatname,
						e.education_category_name AS ipedsdesc,
						v.duration AS duration,
						(case when v.program_description is not null then v.program_description else v3.cipdesc end) AS programdescription,
						(case when v.program_name is not null then v.program_name else v3.ciptitle end) AS programname,
						:cipcode AS cipcode
					FROM vcn_program v
					INNER JOIN vcn_program_cipcode v2 ON v.program_id = v2.program_id
					INNER JOIN vcn_cipcode v3 ON v2.cipcode = v3.cipcode
					LEFT JOIN vcn_edu_category_iped i ON v.awlevel = i.iped_lookup_code
        	LEFT JOIN vcn_edu_category e ON i.education_category_id = e.education_category_id
					WHERE v.program_id = :programid";
      
      if (isset($params['unitid']) && strlen($params['unitid'])) {
        $sql .= " AND v.unitid = :unitid ";
        $binds[':unitid'] = $params['unitid'];
      }
      
      $sql .= " AND v3.cipcode = :cipcode ";
	
			$stmt = $db->prepare($sql);
			$stmt->execute($binds);
	
			$result = $stmt->fetchAll();
	
			$data = array('programdetail' => $result);
	
			$this->setResult($data);
	
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
	
		return $this->result;
			
	}
	
	// Gives Program's Required Courses based on Programid AND Cipcode
	public function getProgramRequiredCourses($params) {
	
		$requiredParams = array('programid', 'cipcode');		
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
	
		try {
	
			$db = Resources_PdoMysql::getConnection();
				
			$sql = "SELECT v6.course_id AS courseid,
						v11.description AS deliverymodedescription,
						v8.course_info_url AS courseinfourl,
            v8.course_info_url_flag AS courseinfourlflag,
						v8.course_level AS courselevel,
						v8.course_title AS coursetitle,
						v8.course_id AS courseid,
						v8.course_type AS coursetype,
						v8.delivery_mode AS deliverymode,
						v8.description AS description,
						v8.online_course_url AS onlinecourseurl,
            v8.online_course_url_flag AS onlinecourseurlflag,
						v10.description AS subjectareadescription,
						v6.min_gpa AS mingpa
					FROM vcn_program v
					INNER JOIN vcn_program_cipcode v2 ON v.program_id = v2.program_id
					INNER JOIN vcn_cipcode v3 ON v2.cipcode = v3.cipcode
					INNER JOIN vcn_program_prereq_course v6 ON v.program_id = v6.program_id
					LEFT JOIN vcn_course v8 ON v6.course_id = v8.course_id
					LEFT JOIN vcn_subject_area v10 ON v8.subject_area = v10.subject_area
					LEFT JOIN vcn_course_delivery_mode v11 ON v8.delivery_mode = v11.delivery_mode
					WHERE v.program_id = :programid
					AND v3.cipcode = :cipcode ";			
	
			$binds = array(
					':programid' => $params['programid'],
					':cipcode' => $params['cipcode'],
			);
	
			$stmt = $db->prepare($sql);
			$stmt->execute($binds);
	
			$result = $stmt->fetchAll();
	
			$data = array('programrequiredcourses' => $result);
	
			$this->setResult($data);
	
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
	
		return $this->result;
			
	}
	
	// Gives Program's Required Education based on Programid
	public function getProgramRequiredEducation($params) {
	
		$requiredParams = array('programid');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
	
		try {
	
			$db = Resources_PdoMysql::getConnection();
				
			$sql = "SELECT v13.education_level AS educationlevel,
						v13.min_gpa AS mingpa,
						v14.name AS name
					FROM vcn_program v
					INNER JOIN vcn_program_education_req v13 ON v.program_id = v13.program_id
					LEFT JOIN vcn_education_level v14 ON v13.education_level = v14.education_level
					WHERE v.program_id = :programid ";
	
	
			$binds = array(
					':programid' => $params['programid'],
			);
	
			$stmt = $db->prepare($sql);
			$stmt->execute($binds);
	
			$result = $stmt->fetchAll();
	
			$data = array('programrequirededucation' => $result);
	
			$this->setResult($data);
	
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
	
		return $this->result;
			
	}
	
	// Gives Program's Entrance Tests based on Programid
	public function getProgramEntranceTests($params) {
	
		$requiredParams = array('programid');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
	
		try {
	
			$db = Resources_PdoMysql::getConnection();
				
			$sql = "SELECT v15.min_score AS minscore,
						v15.hs_grad_or_transfer_student as hsgradortransferstudent,
						v15.test_id as testid,
						v16.test_name AS testname,
						v16.test_description AS testdescription,
						v16.test_url AS testurl
					FROM vcn_program v
					INNER JOIN vcn_program_entrance_test v15 ON v.program_id = v15.program_id
					LEFT JOIN vcn_test v16 ON v15.test_id = v16.test_id
					WHERE v.program_id = :programid ";
	
			$binds = array(
					':programid' => $params['programid'],
			);
	
			$stmt = $db->prepare($sql);
			$stmt->execute($binds);
	
			$result = $stmt->fetchAll();
	
			$data = array('programentrancetests' => $result);
	
			$this->setResult($data);
	
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
	
		return $this->result;
			
	}
	
	// Gives Program's Accredited based on Programid
	public function getProgramAccredited($params) {
	
		$requiredParams = array('programid');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
	
		try {
	
			$db = Resources_PdoMysql::getConnection();
				
			$sql = "SELECT v18.name AS name,
						v18.description AS description,
						v18.address_1 AS address_1,
						v18.address_2 AS address_2,
						v18.city AS city,
						v18.state AS state,
						v18.zipcode AS zipcode,
						v18.phone AS phone,
						v18.fax AS fax,
						v18.email_address AS email_address,
						v18.website_url AS website_url,
            v18.website_url_flag AS website_url_flag
					FROM vcn_program v
					LEFT JOIN vcn_program_accreditor v17 ON v.program_id = v17.program_id
					INNER JOIN vcn_school_accreditor v18 ON v17.accreditor_id = v18.accreditor_id
					WHERE v.program_id = :programid ";
	
			$binds = array(
					':programid' => $params['programid'],
			);
	
			$stmt = $db->prepare($sql);
			$stmt->execute($binds);
	
			$result = $stmt->fetchAll();
	
			$data = array('programaccredited' => $result);
	
			$this->setResult($data);
	
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
	
		return $this->result;
			
	}
	
	// Gives Cipcode list with title
	public function getCipcodeList($params) {
		
		try {
	
			$db = Resources_PdoMysql::getConnection();
	
			/*$sql = "select oxc.cipcode as cipcode, c.ciptitle as ciptitle 
					from vcn_onetxcip oxc 
					left join cipcode c on oxc.cipcode = c.cipcode 
					order by cipcode asc";*/

			$sql = "select distinct(oxc.cipcode) as cipcode, c.ciptitle as ciptitle
					from  vcn_cipcode c , vcn_onetxcip oxc
					where oxc.cipcode = c.cipcode 
					order by cipcode asc";
	
			$stmt = $db->prepare($sql);
			$stmt->execute();
	
			$result = $stmt->fetchAll();
	
			$data = array('cipcodelist' => $result);
	
			$this->setResult($data);
	
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
	
		return $this->result;
			
	}
	
	// Delete program
	public function deleteProgram($params) {
	
		$requiredParams = array('programid');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
	
		try {	
			$db = Resources_PdoMysql::getConnection();
			
			$sql = " DELETE FROM vcn_program_cipcode WHERE program_id = :programid";			
			$stmt = $db->prepare($sql);				
			$stmt->bindParam(':programid', $params['programid'], PDO::PARAM_INT);				
			$stmt->execute();
					
			$sql = " DELETE FROM vcn_program_entrance_test WHERE program_id = :programid";
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':programid', $params['programid'], PDO::PARAM_INT);
			$stmt->execute();
						
			$sql = " DELETE FROM vcn_program_prereq_course WHERE program_id = :programid";
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':programid', $params['programid'], PDO::PARAM_INT);
			$stmt->execute();
	
			$sql = " DELETE FROM vcn_program WHERE program_id = :programid";
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':programid', $params['programid'], PDO::PARAM_INT);
			$stmt->execute();			
			
			$this->setResult(array(true));
	
		} catch (Exception $e) {
		$this->setResult(NULL, $e->getMessage());
		}
	
		return $this->result;
			
	}
	
	// Delete program
	public function updateProgramInfo($params) {
		
		$requiredParams = array('task' ,'updatedby', 'unitid', 'programid', 'cipcode', 'programname', 'awlevel');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
	
		try {
			$db = Resources_PdoMysql::getConnection();
				
			if($params['task'] == "add") {
				$sql = "INSERT INTO vcn_program 
						(created_by,created_time,unitid,program_name,awlevel,total_credits,duration,program_url,program_description,program_contact_name,program_contact_email,program_contact_phone,other_req,how_to_apply,medical_req,immunization_req,legal_req,hs_grad_req,min_gpa,ged_accepted,tuition_in_state_in_district,tuition_in_state_out_district,tuition_online,tuition_out_state,other_cost,admission_url) 
					    VALUES (:updatedby,Now(),:unitid,:programname,:awlevel,:totalcredits,:programlength,:programurl,:programdesc,:programcontactname,:programcontactemail,:programcontactphone,:programotherrequirements,:programhowtoapply,:programmedicalreq,:programimmunizationreq,:programlegalreq,:programhsgradreq,:programmingpa,:programgedaccepted,:programtuitioninstateindistrict,:programtuitioninstateoutdistrict,:programtuitiononline,:programtuitionoutstate,:programothercost,:programadmissionurl)";
			
					
				$stmt = $db->prepare($sql);
				
				$stmt->bindParam(':updatedby', $params['updatedby'], PDO::PARAM_INT);
				$stmt->bindParam(':unitid', $params['unitid'], PDO::PARAM_INT);				
				$stmt->bindParam(':programname', $params['programname'], PDO::PARAM_STR);		
				$stmt->bindParam(':awlevel', $params['awlevel'], PDO::PARAM_INT);
				$stmt->bindParam(':totalcredits', $params['totalcredits'], PDO::PARAM_STR);
				$stmt->bindParam(':programlength', $params['programlength'], PDO::PARAM_STR);
				$stmt->bindParam(':programurl', $params['programurl'], PDO::PARAM_STR);
				$stmt->bindParam(':programdesc', $params['programdesc'], PDO::PARAM_STR);
				$stmt->bindParam(':programcontactname', $params['programcontactname'], PDO::PARAM_STR);
				$stmt->bindParam(':programcontactemail', $params['programcontactemail'], PDO::PARAM_STR);
				$stmt->bindParam(':programcontactphone', $params['programcontactphone'], PDO::PARAM_STR);
				$stmt->bindParam(':programotherrequirements', $params['programotherrequirements'], PDO::PARAM_STR);
				$stmt->bindParam(':programhowtoapply', $params['programhowtoapply'], PDO::PARAM_STR);
				$stmt->bindParam(':programmedicalreq', $params['programmedicalreq'], PDO::PARAM_STR);
				$stmt->bindParam(':programimmunizationreq', $params['programimmunizationreq'], PDO::PARAM_STR);
				$stmt->bindParam(':programlegalreq', $params['programlegalreq'], PDO::PARAM_STR);				
				$stmt->bindParam(':programhsgradreq', $params['programhsgradreq'], PDO::PARAM_STR);
				$stmt->bindParam(':programmingpa', $params['programmingpa'], PDO::PARAM_STR);
				$stmt->bindParam(':programgedaccepted', $params['programgedaccepted'], PDO::PARAM_STR);
				$stmt->bindParam(':programtuitioninstateindistrict', $params['programtuitioninstateindistrict'], PDO::PARAM_STR);
				$stmt->bindParam(':programtuitioninstateoutdistrict', $params['programtuitioninstateoutdistrict'], PDO::PARAM_STR);
				$stmt->bindParam(':programtuitiononline', $params['programtuitiononline'], PDO::PARAM_STR);
				$stmt->bindParam(':programtuitionoutstate', $params['programtuitionoutstate'], PDO::PARAM_STR);
				$stmt->bindParam(':programothercost', $params['programothercost'], PDO::PARAM_STR);
				$stmt->bindParam(':programadmissionurl', $params['programadmissionurl'], PDO::PARAM_STR);

				$stmt->execute();
					
				$programid = $db->lastInsertId();				
				
				$sql = "INSERT INTO vcn_program_cipcode 
						(cipcode, cipcode_year, program_id, Created_by, Created_time)
					    VALUES (:cipcode, '2010', :programid , :updatedby, Now())";
				
				$stmt = $db->prepare($sql);
				
				$stmt->bindParam(':updatedby', $params['updatedby'], PDO::PARAM_INT);
				$stmt->bindParam(':programid', $programid, PDO::PARAM_INT);
				$stmt->bindParam(':cipcode', $params['cipcode'], PDO::PARAM_STR);
									
				$stmt->execute();				
			}else {
				$programid = $params['programid'];
				
				$sql = "UPDATE vcn_program
							SET updated_by = :updatedby, updated_time = Now(),					
								program_name = :programname, awlevel = :awlevel, total_credits = :totalcredits, duration = :programlength, 
								program_url = :programurl, program_description = :programdesc, program_contact_name = :programcontactname, 
								program_contact_email = :programcontactemail, program_contact_phone = :programcontactphone, other_req = :programotherrequirements,
								how_to_apply = :programhowtoapply, medical_req = :programmedicalreq, 
								immunization_req = :programimmunizationreq, legal_req = :programlegalreq, 
								hs_grad_req = :programhsgradreq, min_gpa = :programmingpa, ged_accepted = :programgedaccepted, 
								tuition_in_state_in_district = :programtuitioninstateindistrict, tuition_in_state_out_district = :programtuitioninstateoutdistrict, 
								tuition_online = :programtuitiononline, tuition_out_state = :programtuitionoutstate, other_cost = :programothercost,
								admission_url = :programadmissionurl
						  WHERE program_id =:programid ";			
					
				$stmt = $db->prepare($sql);
													
				$stmt->bindParam(':updatedby', $params['updatedby'], PDO::PARAM_INT);
				$stmt->bindParam(':programname', $params['programname'], PDO::PARAM_STR);		
				$stmt->bindParam(':awlevel', $params['awlevel'], PDO::PARAM_INT);
				$stmt->bindParam(':totalcredits', $params['totalcredits'], PDO::PARAM_STR);
				$stmt->bindParam(':programlength', $params['programlength'], PDO::PARAM_STR);
				$stmt->bindParam(':programurl', $params['programurl'], PDO::PARAM_STR);
				$stmt->bindParam(':programdesc', $params['programdesc'], PDO::PARAM_STR);
				$stmt->bindParam(':programcontactname', $params['programcontactname'], PDO::PARAM_STR);
				$stmt->bindParam(':programcontactemail', $params['programcontactemail'], PDO::PARAM_STR);
				$stmt->bindParam(':programcontactphone', $params['programcontactphone'], PDO::PARAM_STR);
				$stmt->bindParam(':programotherrequirements', $params['programotherrequirements'], PDO::PARAM_STR);
				$stmt->bindParam(':programhowtoapply', $params['programhowtoapply'], PDO::PARAM_STR);
				$stmt->bindParam(':programmedicalreq', $params['programmedicalreq'], PDO::PARAM_STR);
				$stmt->bindParam(':programimmunizationreq', $params['programimmunizationreq'], PDO::PARAM_STR);
				$stmt->bindParam(':programlegalreq', $params['programlegalreq'], PDO::PARAM_STR);
				$stmt->bindParam(':programhsgradreq', $params['programhsgradreq'], PDO::PARAM_STR);
				$stmt->bindParam(':programmingpa', $params['programmingpa'], PDO::PARAM_STR);
				$stmt->bindParam(':programgedaccepted', $params['programgedaccepted'], PDO::PARAM_STR);
				$stmt->bindParam(':programtuitioninstateindistrict', $params['programtuitioninstateindistrict'], PDO::PARAM_STR);
				$stmt->bindParam(':programtuitioninstateoutdistrict', $params['programtuitioninstateoutdistrict'], PDO::PARAM_STR);
				$stmt->bindParam(':programtuitiononline', $params['programtuitiononline'], PDO::PARAM_STR);
				$stmt->bindParam(':programtuitionoutstate', $params['programtuitionoutstate'], PDO::PARAM_STR);
				$stmt->bindParam(':programothercost', $params['programothercost'], PDO::PARAM_STR);
				$stmt->bindParam(':programadmissionurl', $params['programadmissionurl'], PDO::PARAM_STR);
				$stmt->bindParam(':programid', $programid, PDO::PARAM_INT);
					
				$stmt->execute();
				
				$sql = " UPDATE vcn_program_cipcode
						   SET cipcode = :cipcode, updated_by = :updatedby, updated_time = Now()					
						   WHERE program_id = :programid ";
				
				$stmt = $db->prepare($sql);
				
				$stmt->bindParam(':updatedby', $params['updatedby'], PDO::PARAM_INT);
				$stmt->bindParam(':programid', $programid, PDO::PARAM_INT);
				$stmt->bindParam(':cipcode', $params['cipcode'], PDO::PARAM_STR);
  
				$stmt->execute();
			}			
			
			$this->setResult(array($programid));
	
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
			//error_log(print_r($params, true), 3, ini_get('error_log'));
			error_log($e, 3, ini_get('error_log'));					
		}
	
		return $this->result;
			
	}
	
	// Updates Program's Entrance Tests 
	public function updateProgramEntranceTests($params) {
	
		$requiredParams = array('task','programid','updatedby','oldtestidlist','newtestidcount');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
	
		try {
			$success = array(true);
				
			$db = Resources_PdoMysql::getConnection();
	
			if(isset($params['oldtestidlist']) &&  $params['oldtestidlist'] != "") {
				// Delete from vcn_program_entrance_test
				$sql = "DELETE FROM vcn_program_entrance_test
						WHERE program_id = :programid ";
	
				$stmt = $db->prepare($sql);
	
				$stmt->bindParam(':programid', $params['programid'], PDO::PARAM_INT);
				//$stmt->bindParam(':oldtestidlist', $params['oldtestidlist'], PDO::PARAM_STR);
	
				$stmt->execute();
	
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
					// Insert into vcn_program_entrance_test
					$sql = "INSERT INTO vcn_program_entrance_test
										(program_id, test_id, min_score, created_by, created_time)
								 VALUES (:programid, :testid, :minscore, :updatedby, Now())";
	
					$stmt = $db->prepare($sql);
	
					$stmt->bindParam(':programid', $params['programid'], PDO::PARAM_INT);
					$stmt->bindParam(':testid', $lastInsertId, PDO::PARAM_INT);
					$stmt->bindParam(':minscore', $params['testminscorelist'][$i], PDO::PARAM_STR);
					$stmt->bindParam(':updatedby', $params['updatedby'], PDO::PARAM_INT);
	
					$stmt->execute();
						
					$lastInsertId = $db->lastInsertId();
					$success[] = " vcn program entrance test ". $lastInsertId ;
						
					$success[] = "Insert success.";
				}
			}
				
			$this->setResult($success);
	
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
	
		return $this->result;
			
	}
	
	// Updates Program's Required Courses
	public function updateProgramRequiredCourses($params) {
	
		$requiredParams = array('task','unitid','programid','updatedby','oldcourseidlist','newcourseidcount');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
	
		try {
			$success = array(true);
				
			$db = Resources_PdoMysql::getConnection();
	
			if(isset($params['oldcourseidlist']) &&  $params['oldcourseidlist'] != "") {
				// Delete from vcn_program_prereq_course
				$sql = "DELETE FROM vcn_program_prereq_course
						WHERE program_id = :programid ";
	
				$stmt = $db->prepare($sql);
	
				$stmt->bindParam(':programid', $params['programid'], PDO::PARAM_INT);
				//$stmt->bindParam(':oldcourseidlist', $params['oldcourseidlist'], PDO::PARAM_STR);
	
				$stmt->execute();
				
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
					
					// Insert into vcn_program_prereq_course
					$sql = "INSERT INTO vcn_program_prereq_course
										(program_id, course_id, min_gpa, created_by, created_time)
								 VALUES (:programid, :courseid, :min_gpa, :updatedby, Now())";
	
					$stmt = $db->prepare($sql);
	
					$stmt->bindParam(':programid', $params['programid'], PDO::PARAM_INT);
					$stmt->bindParam(':courseid', $lastInsertId, PDO::PARAM_INT);
					$stmt->bindParam(':min_gpa', $params['coursemingpalist'][$i], PDO::PARAM_STR);
					$stmt->bindParam(':updatedby', $params['updatedby'], PDO::PARAM_INT);
	
					$stmt->execute();
						
					$lastInsertId = $db->lastInsertId();
					$success[] = " vcn program entrance course ". $lastInsertId ;
						
					$success[] = "Insert success.";
				}
			}
				
			$this->setResult($success);
	
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
	
		return $this->result;
	
	}
	
	public function updateProgramCurriculumCourses($params) {
		
		$requiredParams = array('task','unitid','programid','updatedby','oldcurrcourseidlist','newcurrcourseidcount');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
		
		try {
			
			$success = array(true);
			
			$db = Resources_PdoMysql::getConnection();
			
			if(isset($params['oldcurrcourseidlist']) &&  $params['oldcurrcourseidlist'] != "") {
				// Delete from vcn_program_prereq_course
				$sql = "DELETE FROM vcn_program_curriculum_course WHERE program_id = :programid ";
			
				$stmt = $db->prepare($sql);
			
				$stmt->bindParam(':programid', $params['programid'], PDO::PARAM_INT);
				//$stmt->bindParam(':oldcourseidlist', $params['oldcourseidlist'], PDO::PARAM_STR);
			
				$stmt->execute();
			
				$success[] = "Delete success.";
			}
			
			if(isset($params['newcurrcourseidcount']) && $params['newcurrcourseidcount'] > 0) {
				for($i=0;$i<$params['newcurrcourseidcount'];$i++) {
					$sql = "INSERT INTO vcn_course
										(unitid, course_title, description, duration, total_credits, created_by, created_time, updated_time)
								 VALUES (:unitid, :course_name, :course_description, :course_duration, :course_total_credits, :updatedby, Now(), Now())";
				
					$stmt = $db->prepare($sql);
				
					$stmt->bindParam(':unitid', $params['unitid'], PDO::PARAM_INT);
					$stmt->bindParam(':course_name', $params['currcoursenamelist'][$i], PDO::PARAM_INT);
					$stmt->bindParam(':course_description', $params['currcoursedesclist'][$i], PDO::PARAM_STR);
					$stmt->bindParam(':course_duration', $params['currcourseduration'][$i], PDO::PARAM_INT);
					$stmt->bindParam(':course_total_credits', $params['currcoursetotalcredits'][$i], PDO::PARAM_INT);
					$stmt->bindParam(':updatedby', $params['updatedby'], PDO::PARAM_INT);
				
					$stmt->execute();
				
					$lastInsertId = $db->lastInsertId();
					$success[] = " vcn curriculum course ". $lastInsertId ;
						
					// Insert into vcn_program_curriculum_course
					$sql = "INSERT INTO vcn_program_curriculum_course (program_id, course_id) VALUES (:programid, :courseid)";
					
					$stmt = $db->prepare($sql);
				
					$stmt->bindParam(':programid', $params['programid'], PDO::PARAM_INT);
					$stmt->bindParam(':courseid', $lastInsertId, PDO::PARAM_INT);
				
					$stmt->execute();
				
					$lastInsertId = $db->lastInsertId();
					$success[] = " vcn program curriculum course ". $lastInsertId ;
				
					$success[] = "Insert success.";
				}
			}
			
			$this->setResult($success);
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
		return $this->result;
	}
	
	
	public function getProgramCurriculumCourses($params) {
	
		try {
			$db = Resources_PdoMysql::getConnection();
				
			$sql = "SELECT v1.course_id AS course_id, v1.course_title AS course_title, v1.description AS course_description, v1.duration AS course_duration, v1.total_credits AS course_total_credits
							FROM vcn_course v1 JOIN vcn_program_curriculum_course  v2
							ON v1.course_id = v2.course_id
							WHERE v2.program_id = :programid";
			
			$program_id = $params['programid'];//800000044;
				
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':programid', $program_id, PDO::PARAM_INT);
			$stmt->execute();
				
			$result = $stmt->fetchAll();
			$data = array();
			foreach($result as $row) {
				$data[] = array(
						'course_id' => $row['course_id'],
						'course_title' => $row['course_title'],
						'course_description' => $row['course_description'],
						'course_duration' => $row['course_duration'],
						'course_total_credits' => $row['course_total_credits'],
				);
			}
				
			$this->setResult($data);
				
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
			
		return $this->result;
	}
  
}