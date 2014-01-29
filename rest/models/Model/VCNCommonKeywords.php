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
class VCN_Model_VCNCommonKeywords extends VCN_Model_Base_VCNBase {
	
	public function getCommonKeywords() {
		
    try {
      
      $db = Resources_PdoMysql::getConnection();

      $sql = "SELECT keyword FROM vcn_job_common_keyword";

      $stmt = $db->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetchAll();

      foreach ($result as $row) {
        $data[] = strtolower($row['keyword']);
      }

      $this->setResult($data);  
    
    } catch (Exception $e) {
      $this->setResult(NULL, $e->getMessage());
    }
    
		return $this->result;
	}
}
 