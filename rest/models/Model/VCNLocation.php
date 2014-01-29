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
 * VCN_Model_VCNLocation Class
 * 
 * 
 * @package    VCN
 * @subpackage
 * @author     
 * @version    SVN: $Id:$
 */
class VCN_Model_VCNLocation extends VCN_Model_Base_VCNBase {
	
	public function getLocation($params) {

    $requiredParams = array('zipcode');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
    
    try {
      
      $db = Resources_PdoMysql::getConnection();

      $sql = " SELECT state, county, latitude, longitude
               FROM vcn_master_zipcode
               WHERE zip = :zipcode ";

      $binds = array(
        ':zipcode' => $params['zipcode'],
      );

      $stmt = $db->prepare($sql);
      $stmt->execute($binds);

      $result = $stmt->fetchAll();

      $data = array('location' => $result);

      $this->setResult($data);  
    
    } catch (Exception $e) {
      $this->setResult(NULL, $e->getMessage());
    }

	  return $this->result;
	  
	}
	
  public function getZipCodes($params) {

		$requiredParams = array('state');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
		
    try {
      
      $db = Resources_PdoMysql::getConnection();

      $sql = " SELECT zip
               FROM vcn_master_zipcode
               WHERE UPPER(state) = :state
               ORDER BY zip ";

      $binds = array(
        ':state' => strtoupper($params['state']),
      );

      $stmt = $db->prepare($sql);
      $stmt->execute($binds);

      $result = $stmt->fetchAll();

      $data = array('zipcodes' => $result);

      $this->setResult($data);  
    
    } catch (Exception $e) {
      $this->setResult(NULL, $e->getMessage());
    }

	  return $this->result;
	  
	}
  
}
 