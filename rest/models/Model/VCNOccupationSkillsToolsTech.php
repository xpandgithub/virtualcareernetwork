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

class VCN_Model_VCNOccupationSkillsToolsTech extends VCN_Model_Base_VCNBase {

	public function getOccupationSkillsToolsTech($params) {
		
		$requiredParams = array('onetcode');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
	
		try {
	
			$db = Resources_PdoMysql::getConnection();
	
			$sql = "SELECT t2_example AS name, t2_category AS category FROM onet_tools_technology WHERE onetcode = :onetcode 
							UNION 
							SELECT skills.name AS name, skills.category 
							FROM (SELECT DISTINCT(elementname) AS name, datavalue, 'skills' AS category FROM skills WHERE onetcode = :onetcode ORDER BY datavalue DESC) skills";
	
	
			$stmt = $db->prepare($sql);
			$stmt->bindParam(':onetcode', $params['onetcode'], PDO::PARAM_STR);
			$stmt->execute();
			$result = $stmt->fetchAll();
			
			$data = array();
			foreach ($result as $row) {
				$data[$row['category']][] = $row['name'];
			}
			
			$this->setResult($data);
	
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
	
		return $this->result;
			
	}

}