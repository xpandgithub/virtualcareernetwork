<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php

class VCN_Model_VCNCmaEmployment extends VCN_Model_Base_VCNBase {

	public function getEmploymentHistory($params) {
		
		$requiredParams = array('userid');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
		
		try {
			$db = Resources_PdoMysql::getConnection();
			
			$sql = "SELECT 
						user_employment_id, 
						employer_name, 
						job_title, 
						start_date, 
						end_date
					FROM vcn_cma_user_employment 
					WHERE user_id = :userid ";		
			
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':userid', $params['userid'], PDO::PARAM_INT);			
			$stmt->execute();
			$result = $stmt->fetchAll();		

			$this->setResult($result);
			
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
		
		return $this->result;
		
	}
	
	public function getEmploymentHistoryDetail($params) {
	
		$requiredParams = array('useremploymentid', 'userid');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}

		try {
			$db = Resources_PdoMysql::getConnection();
				
			$sql = "SELECT employer_name, address1, address2, city, state, zipcode, country, contact_name, contact_phone, contact_email, job_title,
					 responsibilities, start_date, end_date, professional_achievements, created_time, updated_time				
					FROM vcn_cma_user_employment
					WHERE user_employment_id = :useremploymentid AND user_id = :userid ";
				
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':useremploymentid', $params['useremploymentid'], PDO::PARAM_INT);
			$stmt->bindParam(':userid', $params['userid'], PDO::PARAM_INT);
			$stmt->execute();
			$result = $stmt->fetchAll();
	
			$this->setResult($result);
				
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
	
		return $this->result;
	
	}
	
	public function removeEmploymentHistory($params) {
	
		$requiredParams = array('useremploymentid', 'userid');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
	
		try {
			$success = array(false);
	
			$db = Resources_PdoMysql::getConnection();
	
			$sql = "DELETE from vcn_cma_user_employment
					WHERE user_employment_id = :useremploymentid AND user_id = :userid";
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':useremploymentid', $params['useremploymentid'], PDO::PARAM_INT);
			$stmt->bindParam(':userid', $params['userid'], PDO::PARAM_INT);
			$stmt->execute();
	
			$success = array(true);
			$this->setResult($success);
	
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
	
		return $this->result;
			
	}
	
	
	public function addUpdateEmploymentHistory($params) {
	
		$requiredParams = array('useremploymentid', 'userid', 'actiontype', 'employername', 'jobtitle', 'startdate');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
	
		try {
			$success = array(false);
	
			$db = Resources_PdoMysql::getConnection();
	
			if($params['actiontype'] == "add" && $params['useremploymentid'] < 1) {
				$sql = "INSERT INTO vcn_cma_user_employment
							(user_employment_id, user_id, employer_name, address1, address2, city, state, zipcode, country, 
							contact_name, contact_phone, contact_email, job_title, responsibilities, start_date, end_date, 
							professional_achievements, created_time )
						
						VALUES(NULL, :userid, :employername, :address1, :address2, :city, :state, :zipcode, :country, 
							  :contactname, :contactphone, :contactemail, :jobtitle, :responsibilities, :startdate, :enddate, 
							  :professionalachievements, now())";
				
				$stmt = $db->prepare($sql);
								
			}else {
				$sql = "UPDATE vcn_cma_user_employment 
						SET employer_name = :employername,
						 address1 = :address1,
						 address2 = :address2,
						 city = :city,
						 state = :state,
						 zipcode = :zipcode,
						 country = :country,
						 contact_name = :contactname,
						 contact_phone = :contactphone,
						 contact_email = :contactemail,
						 job_title = :jobtitle,
						 responsibilities = :responsibilities,
						 start_date = :startdate,
						 end_date = :enddate,
						 professional_achievements = :professionalachievements,
						 updated_time = now()
						WHERE user_employment_id = :useremploymentid AND user_id = :userid";
				
				$stmt = $db->prepare($sql);
				$stmt->bindParam(':useremploymentid', $params['useremploymentid'], PDO::PARAM_INT);
			}
						
			$stmt->bindParam(':userid', $params['userid'], PDO::PARAM_INT);			
			$stmt->bindParam(':employername', $params['employername'], PDO::PARAM_STR);
			$stmt->bindParam(':address1', $params['address1'], PDO::PARAM_STR);
			$stmt->bindParam(':address2', $params['address2'], PDO::PARAM_STR);
			$stmt->bindParam(':city', $params['city'], PDO::PARAM_STR);
			$stmt->bindParam(':state', $params['state'], PDO::PARAM_STR);
			$stmt->bindParam(':zipcode', $params['zipcode'], PDO::PARAM_INT);
			$stmt->bindParam(':country', $params['country'], PDO::PARAM_STR);
			$stmt->bindParam(':contactname', $params['contactname'], PDO::PARAM_STR);
			$stmt->bindParam(':contactphone', $params['contactphone'], PDO::PARAM_STR);
			$stmt->bindParam(':contactemail', $params['contactemail'], PDO::PARAM_STR);
			$stmt->bindParam(':jobtitle', $params['jobtitle'], PDO::PARAM_STR);
			$stmt->bindParam(':responsibilities', $params['responsibilities'], PDO::PARAM_STR);
			$stmt->bindParam(':startdate', $params['startdate'], PDO::PARAM_STR);
			$stmt->bindParam(':enddate', $params['enddate'], PDO::PARAM_STR);
			$stmt->bindParam(':professionalachievements', $params['professionalachievements'], PDO::PARAM_STR);			
			
			$stmt->execute();
	
			$success = array(true);
			$this->setResult($success);
	
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
	
		return $this->result;
			
	}	

}