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
 * VCN_Model_VCNLicense Class
 * 
 * 
 * @package    VCN
 * @subpackage
 * @author     
 * @version    SVN: $Id:$
 */
class VCN_Model_VCNLicense extends VCN_Model_Base_VCNBase {
	
  public function getLicenses($params) {

		$requiredParams = array('onetcode', 'stfips');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
		
    try {
      
      $db = Resources_PdoMysql::getConnection();

      $sql = " SELECT l.licenseid, l.lictitle, l.licdesc, 
                      CONCAT_WS(', ', (CASE WHEN (la.name1 = '') THEN NULL ELSE la.name1 END), 
                                      (CASE WHEN (la.name2 = '') THEN NULL ELSE la.name2 END), 
                                      (CASE WHEN (la.name3 = '') THEN NULL ELSE la.name3 END)) AS licensingagency
               FROM license l
               LEFT JOIN licxonet lo ON l.licenseid = lo.licenseid AND lo.stfips = :stfips 
               LEFT JOIN licauth la ON l.licauthid = la.licauthid AND la.stfips = :stfips
               WHERE lo.soconetcod = :onetcode
               ORDER BY l.lictitle ";

      $binds = array(
        ':onetcode' => $params['onetcode'],
        ':stfips' => $params['stfips'],
      );

      $stmt = $db->prepare($sql);
      $stmt->execute($binds);

      $result = $stmt->fetchAll();

      $data = array('licenses' => $result);
      
      $this->setResult($data);  

    } catch (Exception $e) {
      $this->setResult(NULL, $e->getMessage());
    }

	  return $this->result;
	  
	}
  
  public function getLicense($params) {

		$requiredParams = array('licenseid');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
		
    try {
      
      $db = Resources_PdoMysql::getConnection();

      $sql = " SELECT l.licenseid, l.lictitle, l.licdesc AS description, 
                      CONCAT_WS(', ', (CASE WHEN (la.name1 = '') THEN NULL ELSE la.name1 END), 
                                      (CASE WHEN (la.name2 = '') THEN NULL ELSE la.name2 END), 
                                      (CASE WHEN (la.name3 = '') THEN NULL ELSE la.name3 END)) AS licensingagency, 
                      la.address1, la.address2, la.city, la.st, la.stfips, la.zip, la.zipext, 
                      la.telephone, la.teleext, la.url, la.email
              FROM license l
              LEFT JOIN licauth la ON l.licauthid = la.licauthid
              WHERE l.licenseid = :license_id ";

      $binds = array(
        ':license_id' => $params['licenseid'],
      );

      $stmt = $db->prepare($sql);
      $stmt->execute($binds);

      $result = $stmt->fetchAll();

      $data = array('license' => $result);
      
      $this->setResult($data);  

    } catch (Exception $e) {
      $this->setResult(NULL, $e->getMessage());
    }

	  return $this->result;
	  
	}
  
	public function getBlacklist($params) {

		$requiredParams = array('onetcode', 'stfips');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
		
    try {
      
      $db = Resources_PdoMysql::getConnection();

      $sql = " SELECT *
               FROM vcn_blacklist_license
               WHERE onetcode = :onetcode
               AND stfips = :stfips
               ORDER BY license_id ";

      $binds = array(
        ':onetcode' => $params['onetcode'],
        ':stfips' => $params['stfips'],
      );

      $stmt = $db->prepare($sql);
      $stmt->execute($binds);

      $result = $stmt->fetchAll();

      $data = array('licenses' => $result);

      $this->setResult($data);  

    } catch (Exception $e) {
      $this->setResult(NULL, $e->getMessage());
    }

	  return $this->result;
	  
	}
	
}
 