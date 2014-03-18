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
class VCN_Model_VCNResources extends VCN_Model_Base_VCNBase {
	
	public function getList($params) {

		$requiredParams = array('industry');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
		
    try {
      
      $db = Resources_PdoMysql::getConnection();

      $sql = " SELECT vsr.*, vlrc.*
               FROM vcn_site_resource vsr
           JOIN vcn_lookup_resource_category vlrc ON vlrc.resource_category_id = vsr.resource_category_id
               WHERE vsr.active_yn = 'Y' AND vsr.resource_link_flag = 1   
           AND vsr.industry_id IN ( 9999 , :industry ) ";

      $binds = array();
      $binds[':industry'] = $params['industry'];

      if (isset($params['category'])  && is_array($params['category']) === true ) {
        $cat = implode(",",$params['category']);
        $sql .= "AND vsr.resource_category_id IN ( ".$cat." ) ";
      } else if (isset($params['category']) && is_array($params['category']) === false ) {
        $sql .= "AND vsr.resource_category_id = ".$params['category']." ";
      }

      if (isset($params['order'])  && is_array($params['order']) === true ) {
        $cat = implode(" , vsr.",$params['order']);
        $sql .= " ORDER BY vlrc.resource_category_order, vsr.".$cat;
      } else if (isset($params['order']) && is_array($params['order']) === false ) {
        $sql .= " ORDER BY vlrc.resource_category_order, vsr.".$params['order'];
      }else {	
        $sql .= " ORDER BY vlrc.resource_category_order, vsr.resource_id";
      }

      $stmt = $db->prepare($sql); 
      $stmt->execute($binds);
      $result = $stmt->fetchAll();

      $data = array('resourceslist' => $result);

      $this->setResult($data);  
    
    } catch (Exception $e) {
      $this->setResult(NULL, $e->getMessage());
    }
		
		return $this->result;
		 
	}
	
}
 