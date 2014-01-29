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

class VCN_Model_VCNOccupationResource extends VCN_Model_Base_VCNBase {

	public function getOccupationResource($params) {
	
		$requiredParams = array('onetcode');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
		
    try {
      
      $db = Resources_PdoMysql::getConnection();

      $sql = " SELECT resource_id, category_id, resource_name, resource_link, resource_link_flag
               FROM vcn_occupation_resource
               WHERE resource_link_flag = 1
               AND onetcode = :onetcode
               ORDER BY category_id, resource_name ";

      $binds = array(
        ':onetcode' => $params['onetcode'], 
      );

      $stmt = $db->prepare($sql);
      $stmt->execute($binds);

      $result = $stmt->fetchAll();

      $data = array('resources' => $result);

      $this->setResult($data);  
    
    } catch (Exception $e) {
      $this->setResult(NULL, $e->getMessage());
    }
	
		return $this->result;
		 
	}
	
	
	public function getOccupationResourcesAndCategoryName($params) {
	
		$requiredParams = array('onetcode');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
	
		try {
	
			$db = Resources_PdoMysql::getConnection();
	
			$sql = "SELECT vcn_occupation_resource.category_id AS category_id, vcn_occupation_resource.resource_name AS resource_name,
      				vcn_occupation_resource.resource_link AS resource_link, vcn_occupation_resource.resource_link_flag AS resource_link_flag, vcn_occ_resource_category.category_name AS category_name
      				FROM vcn_occupation_resource
      				JOIN vcn_occ_resource_category ON vcn_occ_resource_category.category_id = vcn_occupation_resource.category_id
							WHERE vcn_occupation_resource.resource_link_flag = 1
              AND vcn_occupation_resource.onetcode = :onetcode";
	
	
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':onetcode', $params['onetcode'], PDO::PARAM_STR);
			$stmt->execute();
			$result = $stmt->fetchAll();
	
			$data = array('resourceslist' => $result);
	
			$this->setResult($data);
	
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
	
		return $this->result;
			
	}

}