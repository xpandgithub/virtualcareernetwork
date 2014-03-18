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
 * VCN_Model_VCNAdmin Class
 * 
 * 
 * @package    VCN
 * @subpackage
 * @author     
 * @version    SVN: $Id:$
 */
class VCN_Model_VCNAdmin extends VCN_Model_Base_VCNBase {
	
	public function verifyClientApiKey($params) {

		$requiredParams = array('apikey');
		if (!$this->checkParams($params, $requiredParams)) {
			return false;
		}
		
    try {
      
      $db = Resources_PdoMysql::getConnection();

      $sql = " SELECT client_id AS id
               FROM vcn_client 
               WHERE MD5(CONCAT(client_id, client_signature)) = :apikey ";

      $binds = array(
        ':apikey' => $params['apikey'],
      );

      $stmt = $db->prepare($sql);
      $stmt->execute($binds);

      $result = $stmt->fetch();

      if ($result && $result['id'] && $result['id'] > 0) {
        return true;
      }  
    
    } catch (Exception $e) {
      error_log(print_r($e->getMessage(), true), 0);
      return false;
    }
	  
	}
  
  public function insertClient($params) {
    
    $requiredParams = array('clientname', 'contactname', 'contactemail');
  	if (!$this->checkParams($params, $requiredParams)) {
  		return $this->result;
  	}
  		
  	try {
  		$db = Resources_PdoMysql::getConnection();
  
  		$sql = " INSERT INTO vcn_client (client_name, client_contact_name, client_contact_email, client_signature, created_date)
						   VALUES (:clientname, :contactname, :contactemail, :signature, NOW()) ";			
			
			$stmt = $db->prepare($sql);
			
      $random = substr(md5(rand()), 5, 10) . strtoupper(substr(md5(rand()), 5, 10));
      
			$stmt->bindParam(':clientname', $params['clientname'], PDO::PARAM_STR);					
			$stmt->bindParam(':contactname', $params['contactname'], PDO::PARAM_STR);
      $stmt->bindParam(':contactemail', $params['contactemail'], PDO::PARAM_STR);
      $stmt->bindParam(':signature', $random, PDO::PARAM_STR);
  
  		$stmt->execute();
			
      $this->setResult(array(true));
  
  	} catch (Exception $e) {
  		$this->setResult(NULL, $e->getMessage());
  	}
  
  	return $this->result;
  }
  
  public function generateApiKey($params) {

		$requiredParams = array('clientid');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
		
    try {
      
      $db = Resources_PdoMysql::getConnection();

      $sql = " SELECT MD5(CONCAT(client_id, client_signature)) AS apikey
               FROM vcn_client
               WHERE client_id = :clientid ";

      $binds = array(
        ':clientid' => $params['clientid'],
      );

      $stmt = $db->prepare($sql);
      $stmt->execute($binds);

      $result = $stmt->fetch();

      $data = array('items' => $result);

      $this->setResult($data); 
    
    } catch (Exception $e) {
      $this->setResult(NULL, $e->getMessage());
    }
	
		return $this->result;
	  
	}
}
 