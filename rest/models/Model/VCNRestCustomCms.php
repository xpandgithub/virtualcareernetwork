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
 * @author     
 * @version    SVN: $Id:$
 */

class VCN_Model_VCNRestCustomCms extends VCN_Model_Base_VCNBase {
	
	public function updateCareerDetails($params) {

    try {
      
      $db = Resources_PdoMysql::getConnection();

      $sql = "UPDATE vcn_occupation
            SET detailed_description = :description,
              academic_requirement = :edutraining,
              health_requirement = :medrequirements,
              physical_requirement = :phyrequirements,
              day_in_life = :dayinlife
              WHERE onetcode = :onetcode";				


      $binds = array(
        ':description' => $params['description'], 
        ':edutraining' => $params['edutraining'],
        ':medrequirements' => $params['medrequirements'],
        ':phyrequirements' => $params['phyrequirements'],
        ':dayinlife' => $params['dayinlife'],
        ':onetcode' => $params['onetcode']
      );

      $stmt = $db->prepare($sql);
      $stmt->execute($binds);

      $this->setResult(array(true));  
    
    } catch (Exception $e) {
      $this->setResult(NULL, $e->getMessage());
    }
    
	  return $this->result;
	  
	}
	
	public function insertResourceDetails($params) {
	
	  $requiredParams = array('category_id', 'onetcode', 'resource_name', 'resource_link');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
		
    try {
      
      $db = Resources_PdoMysql::getConnection();

      $sql = "INSERT INTO vcn_occupation_resource(category_id,
                            onetcode,
                            resource_name,
                            resource_link,
                            resource_link_flag,
                            active_yn,
                            created_time)
              VALUES (:category_id,
                      :onetcode,
                      :resource_name,
                      :resource_link,
                      TRUE,
                      'Y',
                      Now())";				


      $binds = array(
              ':category_id' => $params['category_id'], 
              ':onetcode' => $params['onetcode'],
              ':resource_name' => $params['resource_name'],
              ':resource_link' => $params['resource_link']

      );

      $stmt = $db->prepare($sql);
      $stmt->execute($binds);

      $this->setResult(array(true));  
    
    } catch (Exception $e) {
      $this->setResult(NULL, $e->getMessage());
    }
    
	  return $this->result;
	  
	}
	
	public function updateResourceDetails($params) {
		
	  $requiredParams = array('resource_id');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
		
    try {
      
      $db = Resources_PdoMysql::getConnection();

      $sql = "UPDATE vcn_occupation_resource
            SET category_id = :category_id,
              resource_name = :resource_name,
              resource_link = :resource_link,
              resource_link_flag = TRUE,
              active_yn = 'Y',
              updated_time = Now()
          WHERE  resource_id = :resource_id";

      $binds = array(
              ':category_id' => $params['category_id'], 
              ':resource_name' => $params['resource_name'],
              ':resource_link' => $params['resource_link'],
              ':resource_id' => $params['resource_id'],
      );

      $stmt = $db->prepare($sql);
      $stmt->execute($binds);

      $this->setResult(array(true));  
    
    } catch (Exception $e) {
      $this->setResult(NULL, $e->getMessage());
    }
    
	  return $this->result;
	  
	}
	
	public function deleteResourceDetails($params) {

	  $requiredParams = array('resource_id');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
	
    try { 
      
      $db = Resources_PdoMysql::getConnection();

      $sql = "DELETE FROM vcn_occupation_resource
          WHERE resource_id = :resource_id";				

      $binds = array(
              ':resource_id' => $params['resource_id'] 
      );

      $stmt = $db->prepare($sql);
      $stmt->execute($binds);

      $this->setResult(array(true));  
    
    } catch (Exception $e) {
      $this->setResult(NULL, $e->getMessage());
    }
    
	  return $this->result;
	  
	}
}
 