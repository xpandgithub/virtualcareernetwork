<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php

class VCN_Model_VCNCmaLocalJobs extends VCN_Model_Base_VCNBase {
	
	public function addCmaLocalJob($params) {
		
		$requiredParams = array('userid');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
		
		try {
			
			$success = array(false);
			
			$db = Resources_PdoMysql::getConnection();
			
			if (strlen($params['userid'])) {
				$sql = "INSERT INTO vcn_cma_user_job 
						(user_job_id, user_id, job_title, job_url, job_url_flag, employer_name, employer_url, employer_url_flag, contact_name, contact_phone, contact_email, note ) 
						VALUES(NULL, :userid, :job_title, :job_url, 1, :employer_name, :employer_url, 1, :contact_name, :contact_phone, :contact_email, :note)";
				
				$stmt = $db->prepare($sql);
				
				$stmt->bindParam(':userid', $params['userid'], PDO::PARAM_INT);
				$stmt->bindParam(':job_title', $params['jobtitle'], PDO::PARAM_STR);
				$stmt->bindParam(':job_url', $params['joburl'], PDO::PARAM_STR);
				$stmt->bindParam(':employer_name', $params['employername'], PDO::PARAM_STR);
				$stmt->bindParam(':employer_url', $params['employerurl'], PDO::PARAM_STR);
				$stmt->bindParam(':contact_name', $params['contactname'], PDO::PARAM_STR);
				$stmt->bindParam(':contact_phone', $params['contactphone'], PDO::PARAM_STR);
				$stmt->bindParam(':contact_email', $params['contactemail'], PDO::PARAM_STR);
				$stmt->bindParam(':note', $params['note'], PDO::PARAM_STR);
				
				$exec = $stmt->execute();
				
				if ($exec) {
					$success = array(true);
				}
				
				$this->setResult($success);
			}
			
		} catch (Exception $e) {
      
			$this->setResult(NULL, $e->getMessage());
		}
	
		return $this->result;
		
	}
	
	
	public function getCmaLocalJobs($params) {
		
		$requiredParams = array('userid');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
		
		try {
				
			$success = array(false);
				
			$db = Resources_PdoMysql::getConnection();
				
			if (strlen($params['userid'])) {
				$sql = "SELECT user_job_id, user_id, job_title, job_url, employer_name, employer_url, contact_name, contact_phone, contact_email, note 
						FROM vcn_cma_user_job WHERE user_id = :userid";
		
				$stmt = $db->prepare($sql);
				$stmt->bindParam(':userid', $params['userid'], PDO::PARAM_INT);
				$stmt->execute();
				$result = $stmt->fetchAll();
				
				$data = array();
				foreach($result as $row) {
					$data[] = $row;
				}
			} else {
				$data = array();
			}
			
			$this->setResult($data);
				
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
		
		return $this->result;
		
	}
	
	public function getCmaLocalJobById($params) {
		
		$requiredParams = array('userid', 'localjobid');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
		
		try {
				
			$success = array(false);
				
			$db = Resources_PdoMysql::getConnection();
				
			if (strlen($params['userid'])) {
				$sql = "SELECT user_job_id, user_id, job_title, job_url, employer_name, employer_url, contact_name, contact_phone, contact_email, note FROM vcn_cma_user_job 
						WHERE user_id = :userid AND user_job_id = :local_job_id";
		
				$stmt = $db->prepare($sql);
				$stmt->bindParam(':userid', $params['userid'], PDO::PARAM_INT);
				$stmt->bindParam(':local_job_id', $params['localjobid'], PDO::PARAM_INT);
				$stmt->execute();
				$result = $stmt->fetchAll();
				
				$data = array();
				foreach($result as $row) {
					$data[] = $row;
				}
			} else {
				$data = array();
			}
			
			$this->setResult($data);
				
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
		
		return $this->result;
		
	}
	
	public function deleteFromCmaLocalJobs($params) {
	
		$requiredParams = array('localjobid', 'userid');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
	
		try {
			$success = array(false);
	
			$db = Resources_PdoMysql::getConnection();
	
			$sql = "DELETE from vcn_cma_user_job
					WHERE user_job_id = :local_job_id AND user_id = :user_id";
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':local_job_id', $params['localjobid'], PDO::PARAM_INT);
			$stmt->bindParam(':user_id', $params['userid'], PDO::PARAM_INT);
			$stmt->execute();
	
			$success = array(true);
			$this->setResult($success);
	
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
	
		return $this->result;
			
	}
	
	
	public function updateCmaLocalJob($params) {
		
		$requiredParams = array('userid', 'localjobid');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
		
		try {
		
		$success = array(false);
			
			$db = Resources_PdoMysql::getConnection();
			
			if (strlen($params['userid'])) {
				$sql = "UPDATE vcn_cma_user_job SET job_title = :job_title, job_url = :job_url, job_url_flag = 1,
						employer_name = :employer_name, employer_url = :employer_url, employer_url_flag = 1,
						contact_name = :contact_name, contact_phone = :contact_phone, contact_email = :contact_email, note = :note 
						WHERE user_job_id = :local_job_id AND user_id = :userid";
				
				$stmt = $db->prepare($sql);
				
				$stmt->bindParam(':userid', $params['userid'], PDO::PARAM_INT);
				$stmt->bindParam(':local_job_id', $params['localjobid'], PDO::PARAM_INT);
				$stmt->bindParam(':job_title', $params['jobtitle'], PDO::PARAM_STR);
				$stmt->bindParam(':job_url', $params['joburl'], PDO::PARAM_STR);
				$stmt->bindParam(':employer_name', $params['employername'], PDO::PARAM_STR);
				$stmt->bindParam(':employer_url', $params['employerurl'], PDO::PARAM_STR);
				$stmt->bindParam(':contact_name', $params['contactname'], PDO::PARAM_STR);
				$stmt->bindParam(':contact_phone', $params['contactphone'], PDO::PARAM_STR);
				$stmt->bindParam(':contact_email', $params['contactemail'], PDO::PARAM_STR);
				$stmt->bindParam(':note', $params['note'], PDO::PARAM_STR);
				
				$exec = $stmt->execute();
				
				if ($exec) {
					$success = array(true);
				}
				
				$this->setResult($success);
			}
		
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
		
		return $this->result;
		
	}
	
	
}