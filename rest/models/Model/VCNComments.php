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
 * @author     waltonr
 * @version    SVN: $Id:$
 */
class VCN_Model_VCNComments extends VCN_Model_Base_VCNBase {
	public function insertJobSearchFeedback($params) {
		
    try {
      
      $db = Resources_PdoMysql::getConnection();

      $sql = "INSERT INTO vcn_comment 
              (comment_id, page_url, sender_email, subject, sender_comment, created_by, created_time, updated_by, updated_time, comment_data, comment_type_id) 
              VALUES(NULL, :page_url, :sender_email, :subject, :sender_comment, :created_by, NULL, :updated_by, NULL, :comment_data, 2)";
      
      $stmt = $db->prepare($sql);
      
      $stmt->bindParam(':page_url', $params['page_url'], PDO::PARAM_STR);
      $stmt->bindParam(':sender_email', $params['vcn_user_drupal_email'], PDO::PARAM_STR);
      $stmt->bindParam(':subject', $params['subject'], PDO::PARAM_STR);
      $stmt->bindParam(':sender_comment', $params['sender_comment'], PDO::PARAM_STR);
      $stmt->bindParam(':created_by', $params['vcn_user_id'], PDO::PARAM_INT);
      $stmt->bindParam(':updated_by', $params['vcn_user_id'], PDO::PARAM_INT);
      $stmt->bindParam(':comment_data', $params['job_search_data'], PDO::PARAM_STR);
      
      $exec = $stmt->execute();
      if ($exec) {
      	$feeback = TRUE;
      } else {
      	$feeback = FALSE;
      }
			
      $this->setResult(array('feedback' => $feeback));  
    
    } catch (Exception $e) {
      $this->setResult(NULL, $e->getMessage());
    }
    
		return $this->result;
		
	}
	
	public function insertTellUsComment($params) {
		
		$requiredParams = array('page_url', 'sender_email', 'sender_comment');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		} 
	
		try {
	
			$db = Resources_PdoMysql::getConnection();
	
			$sql = "INSERT INTO vcn_comment
              (page_url, sender_email, subject, sender_comment, created_by, created_time)
              VALUES(:page_url, :sender_email, :subject, :sender_comment, :created_by, Now())";
	
			$stmt = $db->prepare($sql);

			$stmt->bindParam(':page_url', $params['page_url'], PDO::PARAM_STR);
			$stmt->bindParam(':sender_email', $params['sender_email'], PDO::PARAM_STR);
			$stmt->bindParam(':subject', $params['subject'], PDO::PARAM_STR);
			$stmt->bindParam(':sender_comment', $params['sender_comment'], PDO::PARAM_STR);
			$stmt->bindParam(':created_by', $params['created_by'], PDO::PARAM_INT);			
	
			$exec = $stmt->execute();
			if ($exec) {
				$feeback = TRUE;
			} else {
				$feeback = FALSE;
			} 
				
			$this->setResult(array('feedback' => $feeback));
	
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
	
		return $this->result;
	
	}
	

}
 