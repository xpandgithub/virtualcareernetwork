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
class VCN_Model_VCNCmaNotebook extends VCN_Model_Base_VCNBase {	
	
	public function getNotebookItemByItemid($params) {
		 
		$requiredParams = array('userid', 'industry', 'itemtype', 'itemid');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
		
		$itemtype = $params['itemtype'] = strtoupper($params['itemtype']);
	
		if (!in_array($params['itemtype'], array('OCCUPATION', 'PROGRAM', 'CERTIFICATE', 'LICENSE'))) {
			$this->setResult('failed', 'Invalid value for Parameter(s): itemtype' ,$params);
			return $this->result;
		}
	
		try {
			$db = Resources_PdoMysql::getConnection();					
			
			$where = '';
			$binds = array();
			
			 if (in_array($params['itemtype'], array('PROGRAM', 'LICENSE'))) {
				$where = ' AND n.secondary_info = :secondaryinfo ';
				$binds[':secondaryinfo'] = $params['secondaryinfo'];				
			}
			 
	
			$sql = " SELECT n.notebook_id AS notebookid, n.item_rank AS itemrank					
				FROM vcn_cma_user_notebook n					
				WHERE n.user_id = :user_id
					AND n.industry_id = :industry					
					AND UPPER(n.item_type) = :itemtype 
					AND n.item_id = :itemid
					$where ";
								 
			$binds[':user_id'] = $params['userid'];
        	$binds[':industry'] = $params['industry'];        	
        	$binds[':itemtype'] = $itemtype;
        	$binds[':itemid'] = $params['itemid'];
	
	        $stmt = $db->prepare($sql);
        	$stmt->execute($binds);
	
			$result = $stmt->fetchAll();
					
			$this->setResult($result);

			} catch (Exception $e) {
				$this->setResult(NULL, $e->getMessage()); error_log($e->getMessage(), 3, ini_get('error_log'));
			}

			return $this->result;
  }

  public function getNotebookItems($params) {
  	
  	$requiredParams = array('userid', 'industry', 'type');
  	if (!$this->checkParams($params, $requiredParams)) {
  		return $this->result;
  	}
  	 
  	if (!in_array($params['type'], array('OCCUPATION', 'PROGRAM', 'CERTIFICATE', 'LICENSE', 'ALL'))) {
	  	$this->setResult('failed', 'Invalid value for Parameter(s): type' ,$params);
  		return $this->result;
  	}
  	  			
    try {			
        $db = Resources_PdoMysql::getConnection();

        $type = strtoupper($params['type']);

        $columns = '';
        $joins = '';
        $where = '';
        $binds = array();	
         
        if ($type == 'ALL') {
        	$columns = ' , n.item_type as itemtype ';        	
        }else {
        	$where = ' AND UPPER(n.item_type) = :type ';
        	$binds[':type'] = $type;
        }

        if (strtoupper($type) == 'OCCUPATION') {
          $columns = ' , o.display_title as displaytitle, o.title as title ';
          
          if (isset($params['details']) && $params['details'] == true) {
          	$columns .= ' , o.career_image_yn as careerimageyn ';
          	$columns .= ' , SUBSTRING_INDEX(o.detailed_description, " ", 60) as description ';
          }       
          
          $joins = ' LEFT JOIN vcn_occupation o ON o.ONETCODE = n.item_id ';
        }
                
        if (strtoupper($type) == 'PROGRAM' && isset($params['details']) && $params['details'] == true) {        	
        	$columns = ' , (CASE WHEN TRIM(p.program_name) IS NOT NULL THEN TRIM(p.program_name) ELSE c.ciptitle END) AS programname, 
        				  (CASE WHEN p.program_description IS NOT NULL THEN SUBSTRING_INDEX(p.program_description, " ", 60) ELSE SUBSTRING_INDEX(c.cipdesc, " ", 60) END) AS programdescription,        				  
        				  v.unitid AS unitid,
	                      v.instnm AS inst_name,
	                      v.addr AS inst_address,
	                      v.city AS inst_city,
	                      v.stabbr AS inst_state_abbrev,
	                      v.zip AS inst_zip,
	                      v.gentele AS inst_general_telephone '; 
        	
        	$joins = ' LEFT JOIN vcn_program p ON p.program_id = n.item_id 
        	 		   INNER JOIN vcn_program_cipcode pc ON p.program_id = pc.program_id
        			   INNER JOIN vcn_cipcode c ON pc.cipcode = c.cipcode AND c.cipcode = n.secondary_info
        			   INNER JOIN vcn_provider v ON p.unitid = v.unitid AND v.zip IS NOT NULL';
        }

        $sql = " SELECT n.notebook_id AS notebookid, n.user_id AS userid, n.item_type AS itemtype, n.secondary_info AS secondaryinfo, n.item_id AS onetcode, n.item_id AS item_id, 
                        n.item_rank AS itemrank, n.item_notes AS itemnotes, n.active_yn AS activeyn, n.sub_item_id AS subitemid  
                        $columns
                 FROM vcn_cma_user_notebook n
                 $joins
                 WHERE n.user_id = :user_id                  
                 AND n.industry_id = :industry
        		 AND n.item_id IS NOT NULL 
        		 $where ";

        if (isset($params['targeted']) && $params['targeted'] == 1) {
          $sql .= " AND n.item_rank = 1 ";
        }  	      
        $sql .= " ORDER BY n.item_type, n.item_rank DESC, n.updated_time DESC ";
             
        $binds[':user_id'] = $params['userid'];
        $binds[':industry'] = $params['industry'];        
        
        $stmt = $db->prepare($sql);
        $stmt->execute($binds);

        $result = $stmt->fetchAll();

        $data = array();

        foreach ($result as $row) {	          
          $rowdata = array();
          
          $rowdata['itemrank'] = $row['itemrank'];	                
          
          if (strtoupper($type) == 'OCCUPATION') {
          	$rowdata['notebookid'] = $row['notebookid'];
            $rowdata['onetcode'] = $row['onetcode'];
            $rowdata['displaytitle'] = $row['displaytitle'];
            $rowdata['title'] = $row['title'];
            
            if (isset($params['details']) && $params['details'] == true) {
            	$rowdata['description'] = $row['description'];
            	$rowdata['careerimageyn'] = $row['careerimageyn'];
            }
            
          } else if (strtoupper($type) == 'LICENSE') {
          	$rowdata['notebookid'] = $row['notebookid'];
            $rowdata['itemid'] = $row['item_id'];	
            $rowdata['onetcode'] = $row['subitemid'];	
            $rowdata['stfips'] = $row['secondaryinfo'];
          } else if (strtoupper($type) == 'CERTIFICATE') {
          	$rowdata['notebookid'] = $row['notebookid'];
            $rowdata['itemid'] = $row['item_id'];	
            $rowdata['onetcode'] = $row['subitemid'];
          } else if (strtoupper($type) == 'PROGRAM') {
          	$rowdata['notebookid'] = $row['notebookid'];
            $rowdata['itemid'] = $row['item_id'];	
            $rowdata['onetcode'] = $row['subitemid'];	
            $rowdata['cipcode'] = $row['secondaryinfo'];
            
            if (isset($params['details']) && $params['details'] == true) {
            	$rowdata['programname'] = $row['programname'];
            	$rowdata['programdescription'] = $row['programdescription'];
            	$rowdata['unitid'] = $row['unitid'];            	
            	$rowdata['instname'] = $row['inst_name'];
            	$rowdata['instaddress'] = $row['inst_address'];
            	$rowdata['instcity'] = $row['inst_city'];
            	$rowdata['inststateabbrev'] = $row['inst_state_abbrev'];
            	$rowdata['instzip'] = $row['inst_zip'];
            	$rowdata['instgeneraltelephone'] = $row['inst_general_telephone']; 
            }
            
          } else { //       if (strtoupper($type) == 'ALL')
          	$rowdata['notebookid'] = $row['notebookid'];
          	$rowdata['itemid'] = $row['item_id'];
          	$rowdata['onetcode'] = $row['subitemid'];              	
          	$rowdata['itemrank'] = $row['itemrank'];
          	$rowdata['itemtype'] = $row['itemtype'];
          	$rowdata['secondaryinfo'] = $row['secondaryinfo'];
          }
          
          $data[] = $rowdata; 
        }
        
        $this->setResult($data);

    } catch (Exception $e) {
      $this->setResult(NULL, $e->getMessage());
    }
	
    return $this->result;
  }  
  
  
  public function getTargetedCareer($params) {
  	
  	$requiredParams = array('user_id','industry');
  	if (!$this->checkParams($params, $requiredParams)) {
  		return $this->result;
  	}
  	
  	try {
  		
  		$db = Resources_PdoMysql::getConnection();
  		
  		$sql = "SELECT item_id FROM vcn_cma_user_notebook WHERE user_id = :user_id AND industry_id = :industry AND item_rank = 1 AND item_type = 'occupation' ORDER BY created_time DESC LIMIT 1";
  		
  		$stmt = $db->prepare($sql);
  		$stmt->bindParam(':user_id', $params['user_id'], PDO::PARAM_INT);
  		$stmt->bindParam(':industry', $params['industry'], PDO::PARAM_INT);
  		$stmt->execute();
  		
  		$result = $stmt->fetchAll();
  		$this->setResult($result);
  		
  	} catch (Exception $e) {
  		$this->setResult(NULL, $e->getMessage());
  	}
  	
  	return $this->result;
  }
  
  
  public function getNotebookCareersInDetail($params) {
  
  	$requiredParams = array('industry', 'userid');
  	if (!$this->checkParams($params, $requiredParams)) {
  		return $this->result;
  	}
  
  	try {
  
  		$db = Resources_PdoMysql::getConnection();
  
  		$sql = "SELECT o.display_title AS title,
							CAST(GROUP_CONCAT(IF(ratetype = 4, IF(pct25 = 999999, NULL, pct25), NULL)) AS UNSIGNED INTEGER) AS pct25_annual,
							CAST(GROUP_CONCAT(IF(ratetype = 1, IF(pct25 = 9999.99, NULL, pct25), NULL)) AS DECIMAL(4,2)) AS pct25_hourly,
						  CAST(GROUP_CONCAT(IF(ratetype = 4, IF(pct75 = 999999, NULL, pct75), NULL)) AS UNSIGNED INTEGER) AS pct75_annual,
						  CAST(GROUP_CONCAT(IF(ratetype = 1, IF(pct75 = 9999.99, NULL, pct75), NULL)) AS DECIMAL(4,2)) AS pct75_hourly,
							voed.education_category_id AS typical_edu_id, vec.education_category_name AS typical_edu_text,
							SUBSTRING_INDEX(o.detailed_description, ' ', 15) AS short_description,o.onetcode AS onetcode,
  						vcun.notebook_id AS notebook_id, vcun.item_rank as item_rank,
							(SELECT laytitle FROM onetsoc_laytitle ol  WHERE ol.onetcode = o.onetcode LIMIT 1 ) as laytitle 
							FROM vcn_cma_user_notebook vcun 
							JOIN vcn_occupation o ON o.onetcode = vcun.item_id AND vcun.ITEM_TYPE = 'occupation' AND vcun.user_id = :userid AND vcun.industry_id = :industry 
              JOIN vcn_occupation_industry oxi ON o.onetcode = oxi.onetcode AND oxi.industry_id = :industry
							JOIN vw_vcn_onet_education_distribution voed ON o.onetcode = voed.onetcode 
							JOIN vcn_edu_category vec ON voed.education_category_id = vec.education_category_id 
							LEFT JOIN socxonet AS sxo ON o.onetcode = sxo.onetcode 
							LEFT JOIN socxsocwage AS sxsw ON sxo.soccode = sxsw.soccode 
							LEFT JOIN wage_occ wo ON wo.occcode = sxsw.socwage AND wo.stfips = '00' 
  						GROUP BY onetcode";
  
  		$binds = array(
  				':industry' => $params['industry'],
  				':userid' => $params['userid'],
  		);
  
  		$stmt = $db->prepare($sql);
  		$stmt->execute($binds);
  
  		$result = $stmt->fetchAll();
  
  		$data = array();
  
  		foreach ($result as $row) {
  			$data[] = array(
  					'notebook_id' => $row['notebook_id'],
  					'item_rank' => $row['item_rank'],
  					'title' => $row['title'],
  					'onetcode' => $row['onetcode'],
  					'shortdesc' => strip_tags($row['short_description']),
  					'typical_edu_id' => $row['typical_edu_id'],
  					'typical_edu_text' => $row['typical_edu_text'],
  					'laytitle' => $row['laytitle'],
  					'pct25_hourly' =>  $row['pct25_hourly'],
  					'pct75_hourly' =>  $row['pct75_hourly'],
  					'pct25_annual' =>  $row['pct25_annual'],
  					'pct75_annual' =>  $row['pct75_annual'],
  			);
  		}
  
  		$this->setResult($data);
  
  	} catch (Exception $e) {
  		$this->setResult(NULL, $e->getMessage());
  	}
  
  	return $this->result;
  		
  }
  
  
  public function getNotebookProgramsInDetail($params) {
  
  	$requiredParams = array('industry', 'userid');
  	if (!$this->checkParams($params, $requiredParams)) {
  		return $this->result;
  	}
  
  	try {
  
  		$db = Resources_PdoMysql::getConnection();
  
  		$sql = " SELECT n.notebook_id,
                      n.user_id,
                      n.item_rank,
                      n.item_id,
                      n.secondary_info AS secondaryinfo,
                      n.sub_item_id,
                      p.awlevel AS award_level,                     
                      pc.program_id AS program_id,
                      c.ciptitle AS ciptitle,
                      c.cipcode AS cipcode,
                      v.unitid AS unitid,
                      v.instnm AS inst_name,
                      v.addr AS inst_address,
                      v.city AS inst_city,
                      v.stabbr AS inst_state_abbrev,
                      v.zip AS inst_zip,
                      v.gentele AS inst_general_telephone,
                      ec.education_category_name AS education_category_name,
                      ec.education_category_id AS education_category_id,
                      (CASE WHEN TRIM(p.program_name) IS NOT NULL THEN TRIM(p.program_name) ELSE c.ciptitle END) AS program_name
              FROM vcn_cma_user_notebook n
              LEFT JOIN vcn_program p ON p.program_id = n.item_id
              INNER JOIN vcn_program_cipcode pc ON p.program_id = pc.program_id
              INNER JOIN vcn_cipcode c ON pc.cipcode = c.cipcode
              INNER JOIN vcn_provider v ON p.unitid = v.unitid AND v.zip IS NOT NULL
              LEFT JOIN vcn_edu_category_iped eci ON p.awlevel = eci.iped_lookup_code
              LEFT JOIN vcn_edu_category ec ON eci.education_category_id = ec.education_category_id	  	
              WHERE n.item_type = 'program' 
              AND n.user_id = :userid 
              AND n.industry_id = :industry				  	
              AND n.item_id =  p.program_id 
              AND n.secondary_info = c.cipcode
              ORDER BY n.item_rank desc, program_name ";
  
  		$binds = array(
  				':industry' => $params['industry'],
  				':userid' => $params['userid'],
  		);
  
  		$stmt = $db->prepare($sql);
  		$stmt->execute($binds);
  
  		$result = $stmt->fetchAll();
  
  		$data = array('cmaprograms' => $result);  
  		
  		$this->setResult($data);
  
  	} catch (Exception $e) {
  		$this->setResult(NULL, $e->getMessage());
  	}
  
  	return $this->result;
  
  }
  
  // Save/Target notebook item 
  public function saveTargetNotebookItem($params) {
  
  	//error_log(print_r($params, true), 3, ini_get('error_log'));  	
  	  
  	$requiredParams = array('industry', 'userid', 'itemtype', 'task', 'itemid');
  	if (!$this->checkParams($params, $requiredParams)) {
  		return $this->result;
  	}
 
  	try {
  		$success = array();
  		$success['action'] = '';
  			
  		$db = Resources_PdoMysql::getConnection();  		
  		
  		$itemtype = (($params['itemtype'] == "career") ? "OCCUPATION" : $params['itemtype']);
  		$itemtype = ($params['itemtype'] == "program") ? "PROGRAM" : $itemtype;
  		$itemtype = ($params['itemtype'] == "certification") ? "CERTIFICATE" : $itemtype;
  		$itemtype = ($params['itemtype'] == "license") ? "LICENSE" : $itemtype;
  		
  		//make sure (user_id ind_id item_type item_id [sub_item_id] [sec_info] ) is already not there if there ignore/update else insert
  		$binds = array();
  		$sql = " SELECT n.notebook_id AS notebookid, n.item_rank AS itemrank
  					FROM vcn_cma_user_notebook n
  			 		WHERE n.user_id = :userid
  					AND UPPER(n.item_type) = :itemtype
  					AND n.industry_id = :industry 
  					AND n.item_id = :itemid ";
  		
  		if ($params['itemtype'] != "career") {
  			$sql .= " AND n.sub_item_id = :subitemid ";
  			$binds[':subitemid'] = $params['subitemid'];
  		}
  		
  		if ($params['itemtype'] == "program" || $params['itemtype'] == "license") {
  			$sql .= " AND n.secondary_info = :secondaryinfo ";
  			$binds[':secondaryinfo'] = $params['secondaryinfo'];
  		}
  		
  		$binds[':userid'] = $params['userid'];
  		$binds[':industry'] = $params['industry'];
  		$binds[':itemtype'] = $itemtype;
  		$binds[':itemid'] = $params['itemid'];
  		
  		$stmt = $db->prepare($sql);
  		$stmt->execute($binds);  		 		
  		$result = $stmt->fetchAll();  	
  					
  		$current_notebookid = $current_itemrank = 0 ; 
  		if(isset($result[0]['itemrank'])) {
  			$current_notebookid = $result[0]['notebookid'];
  			$current_itemrank = $result[0]['itemrank'];
  		}  		

  		if($current_notebookid > 0 && $current_itemrank > 0) { //item is already targeted, and request is to target/save again, no need to fire a querry 
  			
  			//Do nothing //current item is already targetd for this user for this industry  	
  			$success[] = "current item is already targetd for this user for this industry.";
  			$success['action'] = 'targeted';
  			 			
  		}else if($current_notebookid > 0 && $params['task'] == "save") { //item is saved and request is save again, no need to fire a querry
  			
  			//Do nothing //current item is already saved for this user for this industry
  			$success[] = "current item is already saved for this user for this industry.";
  			$success['action'] = 'saved';
  			
  		}else if($current_notebookid < 1 && $params['task'] == "save") { // Item is not saved/targeted, save it with insert query

  			$success['action'] = 'saved';
  			$itemrank = 0;
  			if($params['itemtype'] == "career"){ // If career is first for this user, make it targeted career for this user.  				
  				$sql = "SELECT count(*) as careeritems
  						FROM vcn_cma_user_notebook
  						WHERE user_id = :userid AND industry_id = :industry AND item_type = 'occupation' AND item_id IS NOT NULL ";
  					
  				$stmt = $db->prepare($sql);
  				$stmt->bindParam(':userid', $params['userid'], PDO::PARAM_INT);
  				$stmt->bindParam(':industry', $params['industry'], PDO::PARAM_INT);
  				$stmt->execute();  					
  				$result = $stmt->fetchAll();  				
  				$itemrank = 1;
  				$success['action'] = 'targeted';
  				if(isset($result[0]['careeritems']) && $result[0]['careeritems'] >= 1) {
  					$itemrank = 0;
  					$success['action'] = 'saved';
  				}	  				
  			}
  			
  			//Save Notebook item to table
  			$sql = "INSERT INTO vcn_cma_user_notebook
	  					(USER_ID, ITEM_TYPE, SECONDARY_INFO, ITEM_ID, SUB_ITEM_ID, ITEM_RANK, ACTIVE_YN, CREATED_TIME, INDUSTRY_ID)
						VALUES
	  					(:userid, :itemtype, :secondaryinfo, :itemid, :subitemid, :itemrank, 'Y', now(), :industry)
	  					";
  				
  			$stmt = $db->prepare($sql);
  			$stmt->bindParam(':userid', $params['userid'], PDO::PARAM_INT);
  			$stmt->bindParam(':industry', $params['industry'], PDO::PARAM_INT);
  			$stmt->bindParam(':itemtype', $itemtype, PDO::PARAM_STR);
  			$stmt->bindParam(':itemid', $params['itemid'], PDO::PARAM_STR);
  			$stmt->bindParam(':secondaryinfo', $params['secondaryinfo'], PDO::PARAM_STR);
  			$stmt->bindParam(':subitemid', $params['subitemid'], PDO::PARAM_STR);
  			$stmt->bindParam(':itemrank', $itemrank, PDO::PARAM_INT);
  			$stmt->execute();
  			$success[] = "Item saved to cma.";
  			
  		}else {	// Item requested to target			

  			$move_targeted_item_to_saved = 0;
  			$move_targeted_item_to_saved_for_current_itemtype = 0;
  			if($params['itemtype'] == "career") { // Target career item, move all current targeted items for this user+industry to saved 
  				
  				//ref#0000 Move current targeted to saved
  				$move_targeted_item_to_saved = 1;
  				//ref#1111 Target new career. if already in saved list($current_notebookid > 0), update else insert
  				
  			}else { // Target program/certification/license item
  				
  				//check 1)if related career is not targeted, move all targets to saved, and target related career 2)if it's already saved, update else insert
  					
  				// Get current targeted career to compare with requested one
  				//make sure (user_id ind_id item_type item_id [sub_item_id] [sec_info] ) is already not there if there ignore/update else insert
  				$binds = array();
  				$sql = " SELECT n.notebook_id AS notebookid, n.item_rank AS itemrank
	  					FROM vcn_cma_user_notebook n
	  			 		WHERE n.user_id = :userid
	  					AND UPPER(n.item_type) = :itemtype
	  					AND n.industry_id = :industry
	  					AND n.item_id = :itemid ";
  					
  					
  				$binds[':userid'] = $params['userid'];
  				$binds[':industry'] = $params['industry'];
  				$binds[':itemtype'] = "OCCUPATION";
  				$binds[':itemid'] = $params['subitemid'];
  					
  				$stmt = $db->prepare($sql);
  				$stmt->execute($binds);
  				$result = $stmt->fetchAll();  				
  				
  				$requested_career_notebookid = 0 ;
  				$requested_career_is_targeted = 0 ;
  				if(isset($result[0]['itemrank'])) {
  					$requested_career_notebookid = $result[0]['notebookid'];  					
  					if($result[0]['itemrank'] == 1) {
  						$requested_career_is_targeted = 1;
  					}  					
  				}  				 				
  				
  				if($requested_career_is_targeted == 1) { //Requested item is related to current targeted career
  					//ref#0000.01 Move current targeted to saved only for this itemtype
  					$move_targeted_item_to_saved_for_current_itemtype = 1;
  					//ref#1111 Target requested item. if already in saved list($current_notebookid > 0), update else insert 
  				}else {
  					//ref#0000 Move current targeted to saved
  					$move_targeted_item_to_saved = 1;
  					//ref#1111 Target requested item. if already in saved list($current_notebookid > 0), update else insert
  					//ref#2222 Target new career. if already in saved list($requested_career_notebookid > 0), update else insert
  				}  				
  				
  			}	
  			
  			//ref#0000
  			if($move_targeted_item_to_saved == 1) { //Move All current targeted items to saved for this user+industry
  				$sql = "UPDATE vcn_cma_user_notebook
						SET item_rank = 0
	   			    	WHERE user_id = :userid AND industry_id = :industry ";
  					
  				$stmt = $db->prepare($sql);
  				$stmt->bindParam(':userid', $params['userid'], PDO::PARAM_INT);
  				$stmt->bindParam(':industry', $params['industry'], PDO::PARAM_INT);  				
  				$stmt->execute();
  				$success[] = "Items rank updated to 0.";
  			}
  			
  			//ref#0000.01
  			if($move_targeted_item_to_saved_for_current_itemtype == 1) { //Move current targeted item Only in specified item_type to saved for this user+industry
  				$sql = "UPDATE vcn_cma_user_notebook
						SET item_rank = 0
	   			    	WHERE user_id = :userid AND industry_id = :industry AND UPPER(item_type) = :itemtype";
  					
  				$stmt = $db->prepare($sql);
  				$stmt->bindParam(':userid', $params['userid'], PDO::PARAM_INT);
  				$stmt->bindParam(':industry', $params['industry'], PDO::PARAM_INT);
  				$stmt->bindParam(':itemtype', $itemtype, PDO::PARAM_STR);
  				$stmt->execute();
  				$success[] = "Items rank updated to 0 for specific itemtype.";
  			}
  			
  			//ref#1111
  			if($current_notebookid > 0){ // target requested item (update item_rank)
  				$sql = "UPDATE vcn_cma_user_notebook
						SET item_rank = 1
	   			    	WHERE user_id = :userid AND industry_id = :industry AND notebook_id = :notebookid";
  					
  				$stmt = $db->prepare($sql);
  				$stmt->bindParam(':userid', $params['userid'], PDO::PARAM_INT);
  				$stmt->bindParam(':industry', $params['industry'], PDO::PARAM_INT);
  				$stmt->bindParam(':notebookid', $current_notebookid, PDO::PARAM_INT);
  				$stmt->execute();
  				$success[] = "Item targeted with updating itemrank.";
  			}else {  // target requested item with (insert)  				
  				$sql = "INSERT INTO vcn_cma_user_notebook
	  					(USER_ID, ITEM_TYPE, SECONDARY_INFO, ITEM_ID, SUB_ITEM_ID, ITEM_RANK, ACTIVE_YN, CREATED_TIME, INDUSTRY_ID)
						VALUES
	  					(:userid, :itemtype, :secondaryinfo, :itemid, :subitemid, 1, 'Y', now(), :industry)
	  					";
  				
  				$stmt = $db->prepare($sql);
  				$stmt->bindParam(':userid', $params['userid'], PDO::PARAM_INT);
  				$stmt->bindParam(':industry', $params['industry'], PDO::PARAM_INT);
  				$stmt->bindParam(':itemtype', $itemtype, PDO::PARAM_STR);
  				$stmt->bindParam(':itemid', $params['itemid'], PDO::PARAM_STR);
  				$stmt->bindParam(':secondaryinfo', $params['secondaryinfo'], PDO::PARAM_STR);
  				$stmt->bindParam(':subitemid', $params['subitemid'], PDO::PARAM_STR);
  				$stmt->execute();
  				$success[] = "Item targeted to cma.";
  			}
  			
  			//ref#2222
  			if(isset($requested_career_is_targeted) && $requested_career_is_targeted != 1){
  				
  				if(isset($requested_career_notebookid) && $requested_career_notebookid > 0){ // career related to item targeted with updating itemrank
  					$sql = "UPDATE vcn_cma_user_notebook
							SET item_rank = 1
		   			    	WHERE user_id = :userid AND industry_id = :industry AND notebook_id = :notebookid";
  						
  					$stmt = $db->prepare($sql);
  					$stmt->bindParam(':userid', $params['userid'], PDO::PARAM_INT);
  					$stmt->bindParam(':industry', $params['industry'], PDO::PARAM_INT);
  					$stmt->bindParam(':notebookid', $requested_career_notebookid, PDO::PARAM_INT);
  					$stmt->execute();
  					$success[] = "Career related to item targeted with updating itemrank.";
  				}else { // career related to item targeted 
  					$sql = "INSERT INTO vcn_cma_user_notebook
	  					(USER_ID, ITEM_TYPE, SECONDARY_INFO, ITEM_ID, SUB_ITEM_ID, ITEM_RANK, ACTIVE_YN, CREATED_TIME, INDUSTRY_ID)
						VALUES
	  					(:userid, 'OCCUPATION', '', :itemid, :subitemid, 1, 'Y', now(), :industry)
	  					";
  					
  					$stmt = $db->prepare($sql);
  					$stmt->bindParam(':userid', $params['userid'], PDO::PARAM_INT);
  					$stmt->bindParam(':industry', $params['industry'], PDO::PARAM_INT);  					
  					$stmt->bindParam(':itemid', $params['subitemid'], PDO::PARAM_STR);  					
  					$stmt->bindParam(':subitemid', $params['subitemid'], PDO::PARAM_STR);
  					$stmt->execute();
  					$success[] = "career related to item targeted to cma.";
  				}
  				
  			}
  			
  			$success['action'] = 'targeted';
  		}
  			
  		$this->setResult($success); //error_log(print_r($success, true), 3, ini_get('error_log'));
  
  	} catch (Exception $e) { 
  		$this->setResult(NULL, $e->getMessage()); error_log($e->getMessage(), 3, ini_get('error_log'));
  	}
  
  	return $this->result;
  
  }

  // Remove notebook item based on userid, industry and notebookid
  public function removeNotebookItem($params) {
  
  	$requiredParams = array('industry', 'userid', 'notebookid','itemtype');
  	if (!$this->checkParams($params, $requiredParams)) {
  		return $this->result;
  	}
  
  	try {
  		$success = array(true);
  			
  		$db = Resources_PdoMysql::getConnection();
  		$isTargetedCareer = 0;
  		$autoTargetCareer = 0;
  		if($params['itemtype'] == "career") { 	  			
  			$sql = "SELECT item_rank 
  						FROM vcn_cma_user_notebook 
  						WHERE user_id = :userid AND industry_id = :industry AND item_type = 'occupation' AND notebook_id = :notebookid ";
  			
  			$stmt = $db->prepare($sql);
  			$stmt->bindParam(':userid', $params['userid'], PDO::PARAM_INT);
  			$stmt->bindParam(':industry', $params['industry'], PDO::PARAM_INT);
  			$stmt->bindParam(':notebookid', $params['notebookid'], PDO::PARAM_INT);
  			$stmt->execute();
  			
  			$result = $stmt->fetchAll();
  			$this->setResult($result);  
  			
  			if(isset($result[0]['item_rank']) && $result[0]['item_rank'] == 1) {
  				$isTargetedCareer = 1;
  				$sql = "SELECT count(*) as careeritems
  						FROM vcn_cma_user_notebook
  						WHERE user_id = :userid AND industry_id = :industry AND item_type = 'occupation' ";
  					
  				$stmt = $db->prepare($sql);
  				$stmt->bindParam(':userid', $params['userid'], PDO::PARAM_INT);
  				$stmt->bindParam(':industry', $params['industry'], PDO::PARAM_INT);  				
  				$stmt->execute();
  					
  				$result = $stmt->fetchAll();
  				$this->setResult($result);  				
  				
  				if(isset($result[0]['careeritems']) && $result[0]['careeritems'] == 2) {
  					$autoTargetCareer = 1;
  				} 				
  			}	  			
  		}
  		
  		// Delete item from vcn_cma_user_notebook  		  	
  		$sql = "DELETE FROM vcn_cma_user_notebook
						WHERE user_id = :userid AND industry_id = :industry AND notebook_id = :notebookid";
  		
  		$stmt = $db->prepare($sql);
  		  		
  		$stmt->bindParam(':userid', $params['userid'], PDO::PARAM_INT);
  		$stmt->bindParam(':industry', $params['industry'], PDO::PARAM_INT);
  		$stmt->bindParam(':notebookid', $params['notebookid'], PDO::PARAM_INT);
  		
  		$stmt->execute();
  		$success[] = "Delete success.";  	
  		
  		if($isTargetedCareer == 1) {
  		$sql = "UPDATE vcn_cma_user_notebook
						SET item_rank = 0							
	   			    	WHERE user_id = :userid AND industry_id = :industry ";
  					
  				$stmt = $db->prepare($sql);  					
  				$stmt->bindParam(':userid', $params['userid'], PDO::PARAM_INT);
  				$stmt->bindParam(':industry', $params['industry'], PDO::PARAM_INT);
  				$success[] = "Items rank updated to 0.";
  				$stmt->execute();
  		}	

  		if($autoTargetCareer == 1) {
  			$sql = "UPDATE vcn_cma_user_notebook
						SET item_rank = 1
	   			    	WHERE user_id = :userid AND industry_id = :industry AND item_type = 'occupation'";
  				
  			$stmt = $db->prepare($sql);
  			$stmt->bindParam(':userid', $params['userid'], PDO::PARAM_INT);
  			$stmt->bindParam(':industry', $params['industry'], PDO::PARAM_INT);
  			$success[] = "Auto targeted last career.";
  			$stmt->execute();
  		}
  			
  		$this->setResult($success);
  
  	} catch (Exception $e) {
  		$this->setResult(NULL, $e->getMessage());
  	}
  
  	return $this->result;
  		
  }
  
  public function getTargetedNotebookItemsDetailByUserid($params) {
  	
  	$requiredParams = array('userid','industry');
  	if (!$this->checkParams($params, $requiredParams)) {
  		return $this->result;
  	}
  	
  	try {
  		
  		$db = Resources_PdoMysql::getConnection();
  		
  		$sql = " SELECT item_type AS itemtype,
					item_id AS itemid,
					o.display_title AS onettitle,
					p.program_name AS programname,
					p.awlevel AS awlevel,
  					eci.iped_category_name AS ipedcategoryname,
  					eci.iped_category_description AS ipedcategorydescription,
          			ec.education_category_name AS programaward,
					p2.instnm AS school
				FROM vcn_cma_user_notebook n
				LEFT OUTER JOIN vcn_occupation o ON ( o.ONETCODE = n.ITEM_ID )
				LEFT OUTER JOIN vcn_program p ON ( p.PROGRAM_ID = n.ITEM_ID )
				LEFT OUTER JOIN vcn_provider p2 ON ( p2.UNITID = p.UNITID )
  				LEFT JOIN vcn_edu_category_iped eci ON p.awlevel = eci.iped_lookup_code
        		LEFT JOIN vcn_edu_category ec ON eci.education_category_id = ec.education_category_id
				WHERE user_id =  :userid
				AND ( ITEM_TYPE = 'PROGRAM' OR ITEM_TYPE = 'OCCUPATION' )
				AND ITEM_RANK = 1 AND INDUSTRY_ID = :industry ORDER BY n.CREATED_TIME "; 		
  		 
  		$stmt = $db->prepare($sql);
  		$stmt->bindParam(':userid', $params['userid'], PDO::PARAM_INT);
  		$stmt->bindParam(':industry', $params['industry'], PDO::PARAM_INT);
  		$stmt->execute();
  		
  		$result = $stmt->fetchAll();
  		$this->setResult($result);
  		
  	} catch (Exception $e) {
  		$this->setResult(NULL, $e->getMessage());
  	}
  	
  	return $this->result;
  }
	
}
 