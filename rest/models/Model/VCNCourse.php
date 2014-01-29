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
class VCN_Model_VCNCourse extends VCN_Model_Base_VCNBase {	

  public function getCourseSubjectList($params) {
  			
		try {			
			  $db = Resources_PdoMysql::getConnection();

		      $sql = "SELECT subject_area, description, coming_soon_yn, active_yn, accredited_yn 
		      		  FROM vcn_subject_area ORDER BY description ";	    
		      
		      $stmt = $db->prepare($sql);
		      $stmt->execute();
		
		      $result = $stmt->fetchAll();
		
		      $data = array();
		
		      foreach ($result as $row) {	          
		        $data[] = array(
		          'subjectid' => $row['subject_area'],
		          'subjectname' => $row['description'],
		          'comingsoonyn' => $row['coming_soon_yn'],
		          'activeyn' => $row['active_yn'],
		          'accreditedyn' => $row['accredited_yn'],		      
		        );
		      }
		
		      $this->setResult($data);
			
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
		
		return $this->result;
	}
		
	public function getCourseList($params) {	
		
		try {
			$db = Resources_PdoMysql::getConnection();
		
			$sql = "SELECT c.course_id AS course_id, c.course_code AS course_code, c.course_title AS course_title, c.unitid AS unitid, 
					 c.course_type AS course_type, c.course_level AS course_level, c.subject_area AS subject_area, 
					 c.course_info_url AS course_info_url, c.course_info_url_flag AS course_info_url_flag, 
					 c.online_course_url AS online_course_url, c.online_course_url_flag AS online_course_url_flag, 
					 c.accredited_yn AS c_accredited_yn, c.coming_soon_yn AS c_coming_soon_yn, c.active_yn AS c_active_yn, 					
					 vsa.description AS subject_description, vsa.coming_soon_yn AS subject_coming_soon_yn,
					 vsa.active_yn AS subject_active_yn, vsa.accredited_yn AS subject_accredited_yn
					FROM vcn_course c
					LEFT JOIN vcn_subject_area vsa ON c.subject_area = vsa.subject_area
					WHERE c.active_yn = 'Y'				
					";
		
			if (isset($params['type']) && $params['type'] == "noncredit") {
				$sql .= " AND vsa.active_yn = 'Y' AND vsa.accredited_yn = 'N' ";
			}else if(isset($params['type']) && $params['type'] == "noncreditspecialized") {
				$sql .= " AND vsa.active_yn = 'Y' AND vsa.accredited_yn = 'Y' ";
			}
			
			$binds = array();
			if(isset($params['unitid'])) {
				$sql .= " AND c.unitid = :unitid ";
				$binds[':unitid'] = $params['unitid'];
			}	

			if(isset($params['subject_area'])) {
				$sql .= " AND vsa.subject_area = :subject_area ";
				$binds[':subject_area'] = $params['subject_area'];
			}
		
			$sql .= " ORDER BY  vsa.description, c.course_title asc";
			
			$stmt = $db->prepare($sql);
			$stmt->execute($binds);
		
			$result = $stmt->fetchAll();
		
			$data = array();
		
			foreach ($result as $row) {
				$data[] = array(
						'courseid' => $row['course_id'],
						'coursecode' => $row['course_code'],
						'coursetitle' => $row['course_title'],
						'onlinecourseurl' => $row['online_course_url'],
            'onlinecourseurlflag' => $row['online_course_url_flag'],
						'unitid' => $row['unitid'],
						'subjectid' => $row['subject_area'],
						'subjectname' => $row['subject_description'],
						'coursecomingsoonyn' => $row['c_coming_soon_yn'],
						'courseactiveyn' => $row['c_active_yn'],
						'courseaccreditedyn' => $row['c_accredited_yn'],
						'subjectcomingsoonyn' => $row['subject_coming_soon_yn'],
						'subjectactiveyn' => $row['subject_active_yn'],
						'subjectaccreditedyn' => $row['subject_accredited_yn'],
				);
			}
		
			$this->setResult($data);
				
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
		
		return $this->result;
			
	}
  
}
 