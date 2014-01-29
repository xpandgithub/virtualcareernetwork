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
class VCN_Model_VCNPartners extends VCN_Model_Base_VCNBase {
	
	public function getPartners($params) {
		
		$requiredParams = array('zipcode','latitude','longitude');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
		
    try {
      
      $db = Resources_PdoMysql::getConnection();

      $sql = " SELECT oz.office_id, 
                   op.office_name,  
                   op.address,  
                   op.city,  
                   op.state,  
                   op.zipcode as zip,  
                   op.phone, 
                   op.contact_person,
                   op.email,
                   op.preferred_contact_method, 
                   op.url,
                 vlpc.partner_category_name,
                   VCNGetDistanceBetweenTwoPoints( :latitude, :longitude, mz.latitude, mz.longitude ) as distance   
            FROM vcn_office_zipcode oz  
            JOIN vcn_office_partners op ON (op.office_id = oz.office_id) 
          JOIN vcn_lookup_partner_category vlpc ON (op.partner_category_id = vlpc.partner_category_id) 
              LEFT OUTER JOIN vcn_master_zipcode mz ON (mz.zip = op.zipcode)
            WHERE oz.zipcode = :zipcode  	     
          ";

      $binds = array(
          ':latitude' => $params['latitude'],
          ':longitude' => $params['longitude'],
          ':zipcode' => $params['zipcode'],
      );

      if (isset($params['category'])  && is_array($params['category']) === true ) {
        $cat = implode(",",$params['category']);
        $sql .= " AND op.partner_category_id IN ( ".$cat." ) ";
      } else if (isset($params['category']) && is_array($params['category']) === false ) {
        $sql .= " AND op.partner_category_id = ".$params['category']." ";
      }

      $sql .= " ORDER BY distance ";

      $stmt = $db->prepare($sql);
      $stmt->execute($binds);

      $result = $stmt->fetchAll();

      if(empty($result)) {
        $sql = " SELECT op.office_id, 
                     op.office_name,  
                     op.address,  
                     op.city,  
                     op.state,  
                     op.zipcode as zip,  
                     op.phone, 
                     op.contact_person, 
                     op.email,
                     op.preferred_contact_method, 
                     op.url,
                 vlpc.partner_category_name,
                     VCNGetDistanceBetweenTwoPoints( :latitude, :longitude, mz.latitude, mz.longitude ) as distance 
              FROM vcn_office_partners op 
            JOIN vcn_lookup_partner_category vlpc ON (op.partner_category_id = vlpc.partner_category_id) 
              JOIN vcn_master_zipcode mz ON (mz.zip = op.zipcode)  
              WHERE zip IS NOT NULL 
              AND mz.latitude IS NOT NULL 
              ";	

        $binds = array(
            ':latitude' => $params['latitude'],
            ':longitude' => $params['longitude'],
        );

        if (isset($params['category'])  && is_array($params['category']) === true ) {
          $cat = implode(",",$params['category']);
          $sql .= " AND op.partner_category_id IN ( ".$cat." ) ";
        } else if (isset($params['category']) && is_array($params['category']) === false ) {
          $sql .= " AND op.partner_category_id = ".$params['category']." ";
        }

        $sql .= " ORDER BY distance ASC 
            LIMIT 1  ";


        $stmt = $db->prepare($sql);
        $stmt->execute($binds);

        $result = $stmt->fetchAll();
      }

      $data = array('partners' => $result);

      $this->setResult($data);  
    
    } catch (Exception $e) {
      $this->setResult(NULL, $e->getMessage());
    }
	
		return $this->result;
		 
	}
	
}
 