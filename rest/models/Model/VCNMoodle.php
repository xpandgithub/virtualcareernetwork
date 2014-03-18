<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php

class VCN_Model_VCNMoodle extends VCN_Model_Base_VCNBase {
	
	public function insertMoodleUser($params) {
			
		$requiredParams = array('userid', 'username', 'password', 'email');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
		
		try {
			$db = Resources_PdoMysql::getConnection('moodle');
	
			$binds = array();			
	
      $sql = " INSERT mdl_user (auth, confirmed, policyagreed, deleted, mnethostid, username, password, idnumber, firstname, lastname, email, emailstop, lang, timezone, firstaccess, lastaccess, lastlogin, currentlogin, picture, mailformat, maildigest, maildisplay, htmleditor, ajax, autosubscribe, trackforums, timemodified, trustbitmask, screenreader) 
               VALUES ('manual', 1, 1, 0, 1, :username, :password, :userid, 'First', 'Last', :email, 0, 'en_us', 99, unix_timestamp(), unix_timestamp(), unix_timestamp(), unix_timestamp(), 0, 1, 0, 2, 1, 1, 1, 0, unix_timestamp(), 0, 0) ";
      
			$binds[':userid'] = $params['userid'];			 
      $binds[':username'] = $params['username'];
      $binds[':password'] = $params['password'];
      $binds[':email'] = $params['email'];
      
			$stmt = $db->prepare($sql);
			$stmt->execute($binds);
	
			$success = array(true);
			$this->setResult($success);
	
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage()); error_log($e->getMessage(), 3, ini_get('error_log'));
    }
	
    return $this->result;
	}

	public function updateMoodleUser($params) {
			
		$requiredParams = array('userid', 'username', 'password', 'email');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
		
		try {
			$db = Resources_PdoMysql::getConnection('moodle');
	
			$binds = array();			
	
			$sql = " UPDATE mdl_user SET password=:password, email=:email, idnumber=:userid WHERE username=:username ";
      		 
      $binds[':userid'] = $params['userid'];
      $binds[':username'] = $params['username'];
      $binds[':password'] = $params['password'];
      $binds[':email'] = $params['email'];
      
			$stmt = $db->prepare($sql);
			$stmt->execute($binds);
	
			$success = array(true);
			$this->setResult($success);
        
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage()); error_log($e->getMessage(), 3, ini_get('error_log'));
    }
	
    return $this->result;
	}
}
 