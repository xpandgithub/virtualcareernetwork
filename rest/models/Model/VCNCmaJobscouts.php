<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php

class VCN_Model_VCNCmaJobscouts extends VCN_Model_Base_VCNBase {

	public function getCmaJobscouts($params) {
		
		$requiredParams = array('userid', 'industry');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
		
		try {
			$db = Resources_PdoMysql::getConnection();
			
			$sql = "SELECT 
							vcn_cma_user_job_scout.onetcode AS onetcode, 
							vcn_occupation.display_title AS title, 
							zip, distance, keyword, 
							vcn_cma_user_job_scout.created_time AS created_time, 
							vcn_cma_user_job_scout.active_yn AS active_yn,
							job_scout_id 
							FROM vcn_cma_user_job_scout 
							LEFT JOIN vcn_occupation ON vcn_cma_user_job_scout.onetcode = vcn_occupation.onetcode WHERE user_id = :user_id AND industry_id = :industry";
			
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':user_id', $params['userid'], PDO::PARAM_INT);
			$stmt->bindParam(':industry', $params['industry'], PDO::PARAM_INT);
			$stmt->execute();
			$result = $stmt->fetchAll();
			
			$data = array();
			foreach ($result as $row) {
				if (empty($row['keyword'])) {
					$title = $row['title'];
				} else {
					$title = NULL;
				}
				$date_saved = explode(' ', $row['created_time']);
				$data[] = array(
										'onetcode' => $row['onetcode'], 
										'zip' => $row['zip'], 
										'distance' => $row['distance'], 
										'keyword' => $row['keyword'], 
										'title' => $title, 
										'date_saved' => $date_saved[0],
										'active_yn' => $row['active_yn'],
										'job_scout_id' => $row['job_scout_id']
									);
			}
			$this->setResult($data);
			
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
		
		return $this->result;
		
	}
	
	public function deleteFromJobscouts($params) {
	
		$requiredParams = array('jobscoutid', 'userid');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
	
		try {
			$success = array(false);
	
			$db = Resources_PdoMysql::getConnection();
	
			$sql = "DELETE from vcn_cma_user_job_scout
					WHERE job_scout_id = :job_scout_id AND user_id = :user_id";
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':job_scout_id', $params['jobscoutid'], PDO::PARAM_INT);
			$stmt->bindParam(':user_id', $params['userid'], PDO::PARAM_INT);
			$stmt->execute();
	
			$success = array(true);
			$this->setResult($success);
	
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
	
		return $this->result;
			
	}
	
	
	public function updateSubscriptionJobscouts($params) {
	
		$requiredParams = array('jobscoutid', 'userid');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
	
		try {
			$success = array(false);
	
			$db = Resources_PdoMysql::getConnection();
	
			$sql = "UPDATE vcn_cma_user_job_scout SET active_yn = IF(active_yn = 'y', 'n', 'y')
					WHERE job_scout_id = :job_scout_id AND user_id = :user_id";
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':job_scout_id', $params['jobscoutid'], PDO::PARAM_INT);
			$stmt->bindParam(':user_id', $params['userid'], PDO::PARAM_INT);
			$stmt->execute();
	
			$success = array(true);
			$this->setResult($success);
	
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
	
		return $this->result;
			
	}
	
	public function saveJobSearch($params) {
		
		$requiredParams = array('userid', 'industry');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
		
		try {
			
			$success = array(false);
			
			$db = Resources_PdoMysql::getConnection();
			
			$sql_count = "SELECT COUNT(job_scout_id) FROM vcn_cma_user_job_scout WHERE user_id = :user_id AND industry_id = :industry";
			$stmt_count = $db->prepare($sql_count);
			$stmt_count->bindParam(':user_id', $params['userid'], PDO::PARAM_INT);
			$stmt_count->bindParam(':industry', $params['industry'], PDO::PARAM_INT);
			$stmt_count->execute();
			$number_of_rows = $stmt_count->fetchColumn();
			
			if ($number_of_rows < 5) {
				$sql = "INSERT INTO vcn_cma_user_job_scout(job_scout_id, user_id, zip, distance, onetcode, keyword, industry_id, active_yn, created_time )
							VALUES(NULL, :user_id, :zip, :distance, :onetcode, :keyword, :industry, 'y', now())";
					
				$stmt = $db->prepare($sql);
				$stmt->bindParam(':user_id', $params['userid'], PDO::PARAM_INT);
				$stmt->bindParam(':industry', $params['industry'], PDO::PARAM_INT);
				$stmt->bindParam(':zip', $params['zip'], PDO::PARAM_STR);
				$stmt->bindParam(':distance', $params['distance'], PDO::PARAM_INT);
				$stmt->bindParam(':onetcode', $params['onetcode'], PDO::PARAM_STR);
				$stmt->bindParam(':keyword', $params['keyword'], PDO::PARAM_STR);
				$exec = $stmt->execute();
				
				$status = array('execution' => TRUE, 'job_count_status' => TRUE); // if less than 5 records, allow to save and show appropriate message on the front end
			} else {
				$status = array('execution' => TRUE, 'job_count_status' => FALSE); // for 5 or more records, don't allow to save and show appropriate message on the front end
			}
		} catch (Exception $e) {
			$status = array('execution' => FALSE, 'job_count_status' => FALSE);
			$this->setResult($status, $e->getMessage());
		}
		
		$this->setResult($status);
		return $this->result;
		
	}

  public function getAllCmaJobscoutsForToday($params) {
		
    $requiredParams = array('industry');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
    
		try {
			$db = Resources_PdoMysql::getConnection();
			
			$sql = " SELECT job_scout_id, js.user_id, zip, distance, js.onetcode, keyword, industry_id, cu.user_session_id, o.display_title 
               FROM vcn_cma_user_job_scout js 
               JOIN vcn_cma_user cu ON cu.user_id = js.user_id AND js.industry_id = :industry
               LEFT JOIN vcn_occupation o ON js.onetcode = o.onetcode
               WHERE DAYOFWEEK(js.created_time) = DAYOFWEEK(sysdate()) 
               AND UPPER(js.active_yn) = 'Y' ";
			
			$stmt = $db->prepare($sql);
      $stmt->bindParam(':industry', $params['industry'], PDO::PARAM_INT);
			$stmt->execute();
			$result = $stmt->fetchAll();
			
			$data = array();
			foreach ($result as $row) {
				$data[] = array(
          'onetcode' => $row['onetcode'], 
          'careername' => $row['display_title'],
          'zip' => $row['zip'], 
          'distance' => $row['distance'], 
          'keyword' => $row['keyword'], 
          'vcnuserid' => $row['user_id'],
          'drupaluserid' => $row['user_session_id'],
          'jobscoutid' => $row['job_scout_id'],
          'industryid' => $row['industry_id'],
        );
			}
			$this->setResult($data);
			
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
		
		return $this->result;
		
	}
  
}