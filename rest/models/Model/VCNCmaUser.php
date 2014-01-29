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
 * VCNCmaNotebook Class
 * 
 * 
 * @package    VCN
 * @subpackage
 * @author     
 * @version    SVN: $Id:$
 */
class VCN_Model_VCNCmaUser extends VCN_Model_Base_VCNBase {	

  public function getUserInfo($params) {
  	
  	$requiredParams = array('userid');
  	if (!$this->checkParams($params, $requiredParams)) {
  		return $this->result;
  	}
  			
    try {			
        $db = Resources_PdoMysql::getConnection();
        
        $sql = " SELECT first_name, last_name, zipcode, user_session_id
                 FROM vcn_cma_user cu                 
                 WHERE cu.user_id = :userid ";
        
        $binds = array();	      
        $binds[':userid'] = $params['userid'];      
        
        $stmt = $db->prepare($sql);
        $stmt->execute($binds);

        $result = $stmt->fetch();        

        if (isset($params['extended']) && strlen($params['extended'])) {
          $sql2 = " SELECT name AS username, mail AS email
                    FROM drupal.users u                 
                    WHERE u.uid = :usersessionid ";

          $binds2 = array();	      
          $binds2[':usersessionid'] = $result['user_session_id'];      

          $stmt2 = $db->prepare($sql2);
          $stmt2->execute($binds2);

          $result2 = $stmt2->fetch();
          
          $result = array_merge((array)$result, (array)$result2);
        }
        
        $this->setResult($result);

    } catch (Exception $e) {
      $this->setResult(NULL, $e->getMessage());
    }
	
    return $this->result;
  }  

  public function updateUserInfo($params) {
  	 
  	$requiredParams = array('userid');
  	if (!$this->checkParams($params, $requiredParams)) {
  		return $this->result;
  	}
  		
  	try {
  		$db = Resources_PdoMysql::getConnection();
  
  		$sql = "UPDATE vcn_cma_user
						SET first_name = :firstname,
							last_name = :lastname,
							zipcode = :zipcode,									
							updated_time = Now()				
	   			    WHERE user_id = :userid";			
			
			$stmt = $db->prepare($sql);
			
			$stmt->bindParam(':firstname', $params['firstname'], PDO::PARAM_STR);		
			$stmt->bindParam(':lastname', $params['lastname'], PDO::PARAM_STR);
			$stmt->bindParam(':zipcode', $params['zipcode'], PDO::PARAM_INT);			
			$stmt->bindParam(':userid', $params['userid'], PDO::PARAM_INT);  
  
  		$stmt->execute();
			
		$this->setResult(array(true));
  
  	} catch (Exception $e) {
  		$this->setResult(NULL, $e->getMessage());
  	}
  
  	return $this->result;
  }
	
  public function updateUserCourseCompleted($params) {
  	 
  	$requiredParams = array('userid', 'courseid');
  	if (!$this->checkParams($params, $requiredParams)) {
  		return $this->result;
  	}
  	
    $success = false;
    
  	try {
  		$db = Resources_PdoMysql::getConnection();
  
      // get the vcn_course_id from the moodle_id
  		$sql = " SELECT course_id AS vcn_course_id
               FROM vcn_course 
               WHERE online_course_url = :course_url ";			
			
      $courseUrl = 'online-courses?id='.$params['courseid'];
      
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':course_url', $courseUrl, PDO::PARAM_STR);			  
  		$stmt->execute();
			
      $row = $stmt->fetch();
      $vcnCourseId = $row['vcn_course_id'];
      
      if ($vcnCourseId) {
        // see if the user has already completed the course 
        $sql = " SELECT COUNT(*) AS xcount 
                 FROM vcn_cma_user_course 
                 WHERE user_id = :user_id 
                 AND course_id = :vcn_course_id 
                 AND military_yn = 'V' ";			

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':user_id', $params['userid'], PDO::PARAM_INT);
        $stmt->bindParam(':vcn_course_id', $vcnCourseId, PDO::PARAM_INT);
        $stmt->execute();

        $row2 = $stmt->fetch(); 
        
        if ($row2['xcount'] > 0) {
          $sql = " UPDATE vcn_cma_user_course 
                   SET date_completed = now()
                   WHERE user_id = :user_id
                   AND course_id = :vcn_course_id ";
        } else {
          $sql = " INSERT INTO vcn_cma_user_course (user_id, course_id, unitid, military_yn, course_credit, date_completed, created_time, updated_time) 
                   VALUES ( :user_id, :vcn_course_id, NULL, 'V', 0.0, now(), now(), now() ) ";
        }
        
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':user_id', $params['userid'], PDO::PARAM_INT);
        $stmt->bindParam(':vcn_course_id', $vcnCourseId, PDO::PARAM_INT);
        $stmt->execute();
        
        $success = true;
      }
      
      $this->setResult(array($success));
 
  	} catch (Exception $e) {
  		$this->setResult(NULL, $e->getMessage());
  	}
  
  	return $this->result;
  }

  public function resetSessionId($params) {	error_log('resetSessionId called:'); //error_log('params: ' . print_r($params,true));
  
  	$requiredParams = array('old_session_id', 'session_id');
  	if (!$this->checkParams($params, $requiredParams)) {
  		return $this->result;
  	}
  	
  	if (is_array($params['old_session_id']) || is_array($params['session_id'])) {
  		$this->setResult('fail', 'Multiple values for Parameters: user_session OR user_session_id', $params);
  		return $this->result;
  	}
  	
  	try {
  		$db = Resources_PdoMysql::getConnection();
  	
  		$sql = "UPDATE vcn_cma_user
						SET user_session_id = :session_id							
	   			    WHERE user_session_id = :old_session_id AND user_session = 'S'";
  			
  		$stmt = $db->prepare($sql);
  			
  		$stmt->bindParam(':session_id', $params['session_id'], PDO::PARAM_STR);
  		$stmt->bindParam(':old_session_id', $params['old_session_id'], PDO::PARAM_STR);	 
  	
  		$stmt->execute(); 			
  		  		
  		$this->setResult('success', 'data returned', $params); // As per D6 requirement //$data = array('userinfo' => $cma_session->toArray());
  		//$this->setResult(array(true));
  		
  	} catch (Exception $e) {
	 	$this->setResult('fail', 'Can not find CMA record: ', $params);  // As per D6 requirement
	 	error_log(print_r($e, true));	//$this->setResult(NULL, $e->getMessage());
  	}
  	
  	return $this->result;  	
  	 	
  }
  
  public function getCmaUserInfo($params) { error_log('getCmaUserInfo'); //error_log('params: ' . print_r($params,true));
  	
  	$requiredParams = array('drupal_user_id', 'session_id', 'industry');
  	if (!$this->checkParams($params, $requiredParams)) {
  		return $this->result;
  	}
  	 
  	if (is_array($params['drupal_user_id']) || is_array($params['session_id'])) {
  		$this->setResult('fail', 'Multiple values for Parameters: user_session OR user_session_id', $params);
  		return $this->result;
  	}  
  	
  	try {
  		$db = Resources_PdoMysql::getConnection(); 	
  	
  		$cmauserinfo = array();
	  	$cma_user    = false;
	  	$cma_session = false; 
	  	$registering_user = false;
	  
	  	//find Entry User_session And User_session_id ('U',$params['drupal_user_id']);
	  	if ($params['drupal_user_id'] > 0) {		  		
	  		$sql = "SELECT user_id, user_session, user_session_id, zipcode, first_name, last_name FROM vcn_cma_user WHERE user_session_id = :drupal_user_id AND user_session = 'U' ";	  		
	  		$stmt = $db->prepare($sql);
	  		$stmt->bindParam(':drupal_user_id', $params['drupal_user_id'], PDO::PARAM_STR);		 
	  		$stmt->execute();
	  		$cma_user_result = $stmt->fetchAll();	  		 
	  		if(isset($cma_user_result[0]['user_id']) && $cma_user_result[0]['user_id'] >= 1) {
	  			$cma_user = true;
	  			$cma_user_id = $cma_user_result[0]['user_id'];
	  		} 			  		
	  	}
	  	
	  	//find Entry User_session And User_session_id ('S',$params['session_id'])
	  	$sql = "SELECT user_id, user_session, user_session_id, zipcode, first_name, last_name FROM vcn_cma_user WHERE user_session_id = :session_id AND user_session = 'S' ";	  	 
	  	$stmt = $db->prepare($sql);
	  	$stmt->bindParam(':session_id', $params['session_id'], PDO::PARAM_STR);
	  	$stmt->execute();
	  	$cma_session_result = $stmt->fetchAll();	  	
	  	if(isset($cma_session_result[0]['user_id']) && $cma_session_result[0]['user_id'] >= 1) {
	  		$cma_session = true;
	  		$cma_session_user_id = $cma_session_result[0]['user_id'];
	  	}
	  	
	  	// Check, process and return userinfo as per "$cma_session && $cma_user"
	  	if ($cma_session && $cma_user) { error_log('getUserInfo - found both, merging'); // $userinfo = $cma_user; 
	  		  		
	  		$this->vcn_user_session_merge($params, $cma_user_id, $cma_session_user_id, $registering_user); 
	  		
	  		$cmauserinfo['userid'] = $cma_user_id; // $cma_user_result[0]['user_id'];
	  		$cmauserinfo['usersession'] = 'U'; // $cma_user_result[0]['user_session'];
	  		$cmauserinfo['usersessionid'] = $cma_user_result[0]['user_session_id'];
	  		$cmauserinfo['zipcode'] = $cma_user_result[0]['zipcode'];
	  		$cmauserinfo['firstname'] = $cma_user_result[0]['first_name'];
	  		$cmauserinfo['lastname'] = $cma_user_result[0]['last_name'];
	  		 
	  	} elseif ($cma_user) { error_log('getUserInfo - found user only'); // $userinfo = $cma_user; 
	  		
	  		$cmauserinfo['userid'] = $cma_user_id; // $cma_user_result[0]['user_id'];
	  		$cmauserinfo['usersession'] = 'U'; // $cma_user_result[0]['user_session'];
	  		$cmauserinfo['usersessionid'] = $cma_user_result[0]['user_session_id'];
	  		$cmauserinfo['zipcode'] = $cma_user_result[0]['zipcode'];
	  		$cmauserinfo['firstname'] = $cma_user_result[0]['first_name'];
	  		$cmauserinfo['lastname'] = $cma_user_result[0]['last_name'];	  		 		 
	  		
	  	} elseif ($cma_session) { error_log('getUserInfo - found session only'); // $userinfo = $cma_session;
	  		
	  		$cmauserinfo['userid'] = $cma_session_user_id; // $cma_session_result[0]['user_id'];
	  		$cmauserinfo['usersession'] = 'S'; // $cma_session_result[0]['user_session'];
	  		$cmauserinfo['usersessionid'] = $cma_session_result[0]['user_session_id'];
	  		$cmauserinfo['zipcode'] = $cma_session_result[0]['zipcode'];
	  		$cmauserinfo['firstname'] = $cma_session_result[0]['first_name'];
	  		$cmauserinfo['lastname'] = $cma_session_result[0]['last_name'];	  		
	  			  		
	  		//This is to update the updated_time field in the vcn_cma_user table in the database date('Y-m-d H:i:s')
	  		$sql = "UPDATE vcn_cma_user
						SET updated_time = NOW()
	   			    WHERE user_session_id = :session_id";
	  			
	  		$stmt = $db->prepare($sql);	  			
	  		$stmt->bindParam(':session_id', $params['session_id'], PDO::PARAM_STR);		  			
	  		$stmt->execute(); 		
	  		
	  		
	  	} else { error_log('getUserInfo - found neither, setting up new object'); //date('Y-m-d H:i:s')   		 
	  				
	  		$sql = "INSERT INTO vcn_cma_user
						(USER_SESSION, USER_SESSION_ID, CREATED_TIME) 
					VALUES
	  					('S', :session_id, now())
	  					";
	  		
	  		$stmt = $db->prepare($sql);
	  		$stmt->bindParam(':session_id', $params['session_id'], PDO::PARAM_STR);		
	  		$stmt->execute();	
	  		$lastInsertId = $db->lastInsertId();
	  		
	  		$cmauserinfo['userid'] = $lastInsertId; // vcn_cma_user => user_id
	  		$cmauserinfo['usersession'] = 'S';
	  		$cmauserinfo['usersessionid'] = $params['session_id'];
	  		$cmauserinfo['zipcode'] = '';
	  		$cmauserinfo['firstname'] = '';
	  		$cmauserinfo['lastname'] = '';
	  			  		 
	  	}
	  
	  	//error_log('CmaUser Model: ' .print_r($cmauserinfo, true), 3, ini_get('error_log'));
	  	
	  	$data = array('userinfo' => $cmauserinfo);
	  	$this->setResult($data);
	  	
  	} catch (Exception $e) {
  		$this->setResult(NULL, $e->getMessage()); error_log(print_r($e, true));
  	}
  
  	return $this->result;
  }
  
  private function vcn_user_session_merge($params, $cma_user_id, $cma_session_user_id, $registering_user = false)  {  	error_log("vcn_user_session_merge: ");
  	
  	try {
  		$db = Resources_PdoMysql::getConnection();
  		
  		//  #1111 Merge all notebook items from session user to cma user
  		
	  		// #1111.01 Compare targeted career per industry for cma user and session user
	  		// if session user targeted career is diff per industry, updated all targeted to saved for that industry from cma_user_id  
	  		
		  		// Get all targeted career for each industry for cma_user_id
		  		$sql = "SELECT item_id, industry_id FROM vcn_cma_user_notebook WHERE user_id = :cma_user_id AND item_rank = 1 AND item_type = 'occupation' ORDER BY industry_id";
		  		$stmt = $db->prepare($sql);
		  		$stmt->bindParam(':cma_user_id', $cma_user_id, PDO::PARAM_INT);
		  		$stmt->execute();
		  		$cma_user_results = $stmt->fetchAll();
		  		
		  		foreach ($cma_user_results as $cma_user_row) { // find and compare each targeted career per industry with cma_session_user_id
		  			
		  			$sql = "SELECT item_id
			  			FROM vcn_cma_user_notebook
			  			WHERE user_id = :cma_session_user_id AND item_rank = 1 AND item_type = 'occupation' AND industry_id = :industry_id ";
		  			$stmt = $db->prepare($sql);
		  			$stmt->bindParam(':cma_session_user_id', $cma_session_user_id, PDO::PARAM_INT);
		  			$stmt->bindParam(':industry_id', $cma_user_row['industry_id'], PDO::PARAM_INT);
		  			$stmt->execute();
		  			$results = $stmt->fetchAll();
		  			
		  			if(isset($results[0]['item_id']) && $results[0]['item_id'] != $cma_user_row['item_id']) {
		  				
		  				// Update cma_user items item_rank to 0 for specific industry ( session wishlist is having new targeted items for same industry to merge)
		  				$sql = "UPDATE vcn_cma_user_notebook
				  			SET item_rank = 0
				  			WHERE user_id = :cma_user_id AND industry_id = :industry_id "; error_log("Update cma_user items item_rank to 0 for specific industry: ".$cma_user_row['industry_id']);
		  				$stmt = $db->prepare($sql);
		  				$stmt->bindParam(':cma_user_id', $cma_user_id, PDO::PARAM_INT);
		  				$stmt->bindParam(':industry_id', $cma_user_row['industry_id'], PDO::PARAM_INT);
		  				$stmt->execute();
		  		
		  			}
		  		
		  		}
	  		// #1111.01 END
  		
	  		// #1111.02 remove all duplicate entry from table for cma_user_id ( duplicate with cma_session_user_id)
	  		  		
		  		$sql = "DELETE vcun1 from vcn_cma_user_notebook vcun1 , vcn_cma_user_notebook vcun2
						where vcun1.ITEM_ID = vcun2.ITEM_ID 
						AND vcun1.ITEM_TYPE = vcun2.ITEM_TYPE
						AND vcun1.STFIPS = vcun2.STFIPS
						AND vcun1.USER_ID = :cma_user_id
						AND vcun2.USER_ID = :cma_session_user_id ";	error_log("remove all duplicate entry from table for cma_user_id: ");
		  		$stmt = $db->prepare($sql);
		  		$stmt->bindParam(':cma_user_id', $cma_user_id, PDO::PARAM_INT);
		  		$stmt->bindParam(':cma_session_user_id', $cma_session_user_id, PDO::PARAM_INT);
		  		$stmt->execute();		  		
	  		
	  		// #1111.02 END
  		
	  		// #1111.03 Update all cma_session_user_id entry to cma_user_id to merge notebook items, #1111.02 removed duplicate entry from cma_user_id
	  		
		  		$sql = "UPDATE vcn_cma_user_notebook
				  			SET user_id = :cma_user_id
				  			WHERE user_id = :cma_session_user_id "; error_log("Update all cma_session_user_id entry to cma_user_id to merge notebook items: ");
		  		$stmt = $db->prepare($sql);
		  		$stmt->bindParam(':cma_user_id', $cma_user_id, PDO::PARAM_INT);
		  		$stmt->bindParam(':cma_session_user_id', $cma_session_user_id, PDO::PARAM_INT);
		  		$stmt->execute();
	  		
	  		// #1111.03 END 		
  		
  		// #1111 End of Merge all notebook items from session user to cma user
  		
  		// #2222 Merge all courses from session user to cma user
  		
	  		// #2222.01 remove all duplicate entry from vcn_cma_user_course table for cma_user_id ( duplicate with cma_session_user_id)
	  		
		  		$sql = "DELETE vcuc1 from vcn_cma_user_course vcuc1 , vcn_cma_user_course vcuc2
						where vcuc1.COURSE_ID = vcuc2.COURSE_ID
						AND vcuc1.COURSE_CODE = vcuc2.COURSE_CODE
						AND vcuc1.MILITARY_YN = vcuc2.MILITARY_YN
						AND vcuc1.USER_ID = :cma_user_id
						AND vcuc2.USER_ID = :cma_session_user_id "; error_log("remove all duplicate entry from vcn_cma_user_course table for cma_user_id: ");
		  		$stmt = $db->prepare($sql);
		  		$stmt->bindParam(':cma_user_id', $cma_user_id, PDO::PARAM_INT);
		  		$stmt->bindParam(':cma_session_user_id', $cma_session_user_id, PDO::PARAM_INT);
		  		$stmt->execute();
	  		
	  		// #2222.01 END
  		
	  		// #2222.02 Update all cma_session_user_id entry to cma_user_id to merge courses at vcn_cma_user_course, #1111.02 removed duplicate entry from cma_user_id
	  		
		  		$sql = "UPDATE vcn_cma_user_course
				  			SET user_id = :cma_user_id
				  			WHERE user_id = :cma_session_user_id "; error_log("Update all cma_session_user_id entry to cma_user_id to merge courses at vcn_cma_user_course: ");
		  		$stmt = $db->prepare($sql);
		  		$stmt->bindParam(':cma_user_id', $cma_user_id, PDO::PARAM_INT);
		  		$stmt->bindParam(':cma_session_user_id', $cma_session_user_id, PDO::PARAM_INT);
		  		$stmt->execute();
	  		
	  		// #2222.02 END
  		
  		// #2222 End of Merge all courses from session user to cma user
  		
  		// #3333 Once, all notebook and course items merged from session to cma user, remove session entry from vcn_cma_user table
		if (!$registering_user) { // This condition is copied from old logic, need to confirm, why it's required to delete session entry only if it's not register process.
	  		$sql = "Delete FROM vcn_cma_user
			  				WHERE USER_ID = :cma_session_user_id"; error_log("Once, all notebook and course items merged from session to cma user, remove session entry: ");
	  		$stmt = $db->prepare($sql);
	  		$stmt->bindParam(':cma_session_user_id', $cma_session_user_id, PDO::PARAM_INT);
	  		$stmt->execute();
		}
  		// #3333 END
  		
  		
  	} catch (Exception $e) {
  		$this->setResult(NULL, $e->getMessage()); error_log(print_r($e, true));
  	}
  	
  }
  
  public function updateCmaUserInfo($params) {	error_log("updateCmaUserInfo: "); //error_log("params: ".print_r($params, true), 3, ini_get('error_log'));
  	 
  	$requiredParams = array('drupal_user_id', 'session_id', 'industry');
  	if (!$this->checkParams($params, $requiredParams)) {
  		return $this->result;
  	}
  	 
  	if (is_array($params['drupal_user_id']) || is_array($params['session_id'])) {
  		$this->setResult('fail', 'Multiple values for Parameters: user_session OR user_session_id', $params);
  		return $this->result;
  	}  	
  
  	try {
  		$db = Resources_PdoMysql::getConnection();
  		
	  	$cmauserinfo = array();
	  	$cma_user    = false;
	  	$cma_session = false;  	
	  	$registering_user = false;	  
	  	
	  	
	  	//find Entry User_session And User_session_id ('U',$params['drupal_user_id']); 
	  	if ($params['drupal_user_id'] > 0) { 
	  		
	  		$sql = "SELECT user_id, user_session, user_session_id, zipcode, first_name, last_name FROM vcn_cma_user WHERE user_session_id = :drupal_user_id AND user_session = 'U' ";
	  		$stmt = $db->prepare($sql);
	  		$stmt->bindParam(':drupal_user_id', $params['drupal_user_id'], PDO::PARAM_STR);
	  		$stmt->execute();
	  		$cma_user_result = $stmt->fetchAll();
	  		
	  		if(isset($cma_user_result[0]['user_id']) && $cma_user_result[0]['user_id'] >= 1) { error_log('Update user info with drupal user id entry'); 
	  			$cma_user = true;
	  			$cma_user_id = $cma_user_result[0]['user_id'];	  			
	  				
	  			$sql = "UPDATE vcn_cma_user
							SET zipcode = :zipcode
		   			    WHERE user_session_id = :drupal_user_id AND user_session = 'U'";
	  			$stmt = $db->prepare($sql);
	  			$stmt->bindParam(':zipcode', $params['userinfo']['zipcode'], PDO::PARAM_STR);	  			
	  			$stmt->bindParam(':drupal_user_id', $params['drupal_user_id'], PDO::PARAM_STR);
	  			$stmt->execute();
	  				
	  			$cmauserinfo['userid'] = $cma_user_id; // $cma_user_result[0]['user_id'];
	  			$cmauserinfo['usersession'] = 'U'; // $cma_user_result[0]['user_session'];
	  			$cmauserinfo['usersessionid'] = $cma_user_result[0]['user_session_id'];
	  			$cmauserinfo['zipcode'] = $params['userinfo']['zipcode'];
	  			$cmauserinfo['firstname'] = $cma_user_result[0]['first_name'];
	  			$cmauserinfo['lastname'] = $cma_user_result[0]['last_name'];
	  			
	  		} else { error_log('create new user entry with drupal user id and return it (in case of new registration)'); // If not there, create new entry and return it (in case of new registration) 
	  			$sql = "INSERT INTO vcn_cma_user
							(USER_SESSION, USER_SESSION_ID, ZIPCODE, CREATED_TIME)
						VALUES
		  					('U', :drupal_user_id, :zipcode, now())
		  					";
	  			
	  			$stmt = $db->prepare($sql);
	  			$stmt->bindParam(':zipcode', $params['userinfo']['zipcode'], PDO::PARAM_STR);
	  			$stmt->bindParam(':drupal_user_id', $params['drupal_user_id'], PDO::PARAM_STR);
	  			$stmt->execute();
	  			$lastInsertId = $db->lastInsertId();
	  				
	  			$cmauserinfo['userid'] = $lastInsertId; // vcn_cma_user => user_id
	  			$cmauserinfo['usersession'] = 'U';
	  			$cmauserinfo['usersessionid'] = $params['drupal_user_id'];
	  			$cmauserinfo['zipcode'] = $params['userinfo']['zipcode'];
	  			$cmauserinfo['firstname'] = '';
	  			$cmauserinfo['lastname'] = '';
	  			
	  			$cma_user = true;
	  			$cma_user_id = $lastInsertId;
	  			$registering_user = true;
	  		}
	  		  		
	  	}
	  	
	  	//find Entry User_session And User_session_id ('S',$params['session_id'])
	  	$sql = "SELECT user_id, user_session, user_session_id, zipcode, first_name, last_name FROM vcn_cma_user WHERE user_session_id = :session_id AND user_session = 'S' ";
	  	$stmt = $db->prepare($sql);
	  	$stmt->bindParam(':session_id', $params['session_id'], PDO::PARAM_STR);
	  	$stmt->execute();
	  	$cma_session_result = $stmt->fetchAll();
	  	if(isset($cma_session_result[0]['user_id']) && $cma_session_result[0]['user_id'] >= 1) {
	  		$cma_session = true;
	  		$cma_session_user_id = $cma_session_result[0]['user_id'];
	  	}
	  	
	  	//$cma_user will be always there, either old or new created at top of function
	  	if ($cma_session) {	error_log('updateUserInfo - found both, merging');
	  		$this->vcn_user_session_merge($params, $cma_user_id, $cma_session_user_id, $registering_user); 
	  	}
	  	
	  	//error_log('CmaUser Model: ' .print_r($cmauserinfo, true), 3, ini_get('error_log'));
	  	
	  	$data = array('userinfo' => $cmauserinfo);
	  	$this->setResult($data);
   
  	} catch (Exception $e) {
  		$this->setResult(NULL, $e->getMessage()); error_log(print_r($e, true));
  	}
  	
  	return $this->result;
  }   
  
    public function getCmaUserAssessmentValues($params) {
  	
  	$requiredParams = array('userid');
  	if (!$this->checkParams($params, $requiredParams)) {
  		return $this->result;
  	}
  			
    try {			
        $db = Resources_PdoMysql::getConnection();
        
        $sql = " SELECT assessment_values
                 FROM vcn_cma_user cu                 
                 WHERE cu.user_id = :userid ";

        
        $binds = array();	      
        $binds[':userid'] = $params['userid'];      
        
        $stmt = $db->prepare($sql);
        $stmt->execute($binds);

        $result = $stmt->fetchAll();        

        $this->setResult($result);

    } catch (Exception $e) {
      $this->setResult(NULL, $e->getMessage());
    }
	
    return $this->result;
  }  

  public function updateCmaUserAssessmentValues($params) {
  	 
  	$requiredParams = array('userid', 'assessmentvalues');
  	if (!$this->checkParams($params, $requiredParams)) {
  		return $this->result;
  	}
  		
  	try {
  		$db = Resources_PdoMysql::getConnection();
  
  		$sql = "UPDATE vcn_cma_user
						  SET assessment_values = :assessmentvalues,									
							updated_time = Now()				
	   			    WHERE user_id = :userid";			
			
			$stmt = $db->prepare($sql);
			
			$stmt->bindParam(':assessmentvalues', $params['assessmentvalues'], PDO::PARAM_STR);					
			$stmt->bindParam(':userid', $params['userid'], PDO::PARAM_INT);  
  
  		$stmt->execute();
			
		$this->setResult(array(true));
  
  	} catch (Exception $e) {
  		$this->setResult(NULL, $e->getMessage());
  	}
  
  	return $this->result;
  }
  
}
 