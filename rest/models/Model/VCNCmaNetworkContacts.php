<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php

class VCN_Model_VCNCmaNetworkContacts extends VCN_Model_Base_VCNBase {

	public function addCmaNetworkContact($params) {

		$requiredParams = array('userid');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}

		try {
				
			$success = array(false);
				
			$db = Resources_PdoMysql::getConnection();
				
			if (strlen($params['userid'])) {
				$sql = "INSERT INTO vcn_cma_user_contact
						(user_contact_id, user_id, first_name, last_name, company_name, company_title, phone_work, phone_mobile, email, note ) 
						VALUES(NULL, :userid, :firstname, :lastname, :companyname, :companytitle, :phonework, :phonemobile, :email, :note)";

				$stmt = $db->prepare($sql);

				$stmt->bindParam(':userid', $params['userid'], PDO::PARAM_INT);
				$stmt->bindParam(':firstname', $params['firstname'], PDO::PARAM_STR);
				$stmt->bindParam(':lastname', $params['lastname'], PDO::PARAM_STR);
				$stmt->bindParam(':companyname', $params['companyname'], PDO::PARAM_STR);
				$stmt->bindParam(':companytitle', $params['companytitle'], PDO::PARAM_STR);
				$stmt->bindParam(':phonework', $params['phonework'], PDO::PARAM_STR);
				$stmt->bindParam(':phonemobile', $params['phonemobile'], PDO::PARAM_STR);
				$stmt->bindParam(':email', $params['email'], PDO::PARAM_STR);
				$stmt->bindParam(':note', $params['note'], PDO::PARAM_STR);

				$exec = $stmt->execute();

				if ($exec) {
					$success = array(true);
				}
			}
			
			$this->setResult($success);
		
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}

		return $this->result;

	}


	public function getCmaNetworkContacts($params) {

		$requiredParams = array('userid');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}

		try {

			$success = array(false);

			$db = Resources_PdoMysql::getConnection();

			if (strlen($params['userid'])) {
				$sql = "SELECT user_contact_id, user_id, first_name, last_name, company_name, company_title, phone_work, phone_mobile, email, note 
						FROM vcn_cma_user_contact WHERE user_id = :userid";

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

	public function getCmaNetworkContactById($params) {

		$requiredParams = array('userid', 'usercontactid');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}

		try {

			$success = array(false);

			$db = Resources_PdoMysql::getConnection();

			if (strlen($params['userid'])) {
				$sql = "SELECT user_contact_id, user_id, first_name, last_name, company_name, company_title, phone_work, phone_mobile, email, note FROM vcn_cma_user_contact
						WHERE user_id = :userid AND user_contact_id = :user_contact_id";

				$stmt = $db->prepare($sql);
				$stmt->bindParam(':userid', $params['userid'], PDO::PARAM_INT);
				$stmt->bindParam(':user_contact_id', $params['usercontactid'], PDO::PARAM_INT);
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

	public function deleteFromCmaNetworkContacts($params) {

		$requiredParams = array('usercontactid', 'userid');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}

		try {
			$success = array(false);

			$db = Resources_PdoMysql::getConnection();

			$sql = "DELETE from vcn_cma_user_contact
					WHERE user_contact_id = :user_contact_id AND user_id = :user_id";
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':user_contact_id', $params['usercontactid'], PDO::PARAM_INT);
			$stmt->bindParam(':user_id', $params['userid'], PDO::PARAM_INT);
			$stmt->execute();

			$success = array(true);
			$this->setResult($success);

		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}

		return $this->result;
			
	}


	public function updateCmaNetworkContact($params) {

		$requiredParams = array('userid', 'usercontactid');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}

		try {

			$success = array(false);
				
			$db = Resources_PdoMysql::getConnection();
				
			if (strlen($params['userid'])) {
				$sql = "UPDATE vcn_cma_user_contact SET first_name = :firstname, last_name = :lastname, company_name =  :companyname, company_title = :companytitle, phone_work = :phonework, 
						phone_mobile = :phonemobile, email = :email, note = :note 
						WHERE user_contact_id = :user_contact_id AND user_id = :userid";
				
				$stmt = $db->prepare($sql);
				
				$stmt->bindParam(':userid', $params['userid'], PDO::PARAM_INT);
				$stmt->bindParam(':user_contact_id', $params['usercontactid'], PDO::PARAM_INT);
				$stmt->bindParam(':firstname', $params['firstname'], PDO::PARAM_STR);
				$stmt->bindParam(':lastname', $params['lastname'], PDO::PARAM_STR);
				$stmt->bindParam(':companyname', $params['companyname'], PDO::PARAM_STR);
				$stmt->bindParam(':companytitle', $params['companytitle'], PDO::PARAM_STR);
				$stmt->bindParam(':phonework', $params['phonework'], PDO::PARAM_STR);
				$stmt->bindParam(':phonemobile', $params['phonemobile'], PDO::PARAM_STR);
				$stmt->bindParam(':email', $params['email'], PDO::PARAM_STR);
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