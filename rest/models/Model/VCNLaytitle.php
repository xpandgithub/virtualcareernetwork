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
class VCN_Model_VCNLaytitle extends VCN_Model_Base_VCNBase {
	
	public function getActiveLaytitlesForOccupation($params) {
		
    $requiredParams = array('industry', 'onetcode');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
    
    try {
      
      $db = Resources_PdoMysql::getConnection();

      $limit = 10; //need to limit the laytitles, so that the query string sent over the api.us.jobs would have lower probability of exceeding 1500 chars
                   //which is the threshold at which it starts to break
      
      if (isset($params['restriction'])) {
      	if ($params['restriction'] == 'front_end') {
      		$sql = "SELECT DISTINCT o.onetcode AS onetcode, o.laytitle AS laytitle FROM onetsoc_laytitle o
      		JOIN vcn_occupation_industry i ON o.onetcode = i.onetcode
      		WHERE i.industry_id = :industry_id AND UPPER(o.frontend_visible_yn) = 'Y' AND o.onetcode = :onetcode";
      	}
      } else {
      	$sql = "SELECT DISTINCT o.onetcode AS onetcode, o.laytitle AS laytitle FROM onetsoc_laytitle o
      	JOIN vcn_occupation_industry i ON o.onetcode = i.onetcode
      	WHERE i.industry_id = :industry_id AND UPPER(o.job_search_yn) = 'Y' AND o.onetcode = :onetcode LIMIT $limit";
      }
      
      
      $binds = array(':onetcode' => $params['onetcode'], ':industry_id' => $params['industry']);
      
      $stmt = $db->prepare($sql);
      $stmt->bindParam(':onetcode', $params['onetcode'], PDO::PARAM_STR);
      $stmt->bindParam(':industry_id', $params['industry'], PDO::PARAM_INT);
      $stmt->execute();
      $result = $stmt->fetchAll();

      $data = array();
      foreach ($result as $row) {
        $data[] = array('laytitle' => $row['laytitle'], 'onetcode' => $row['onetcode']);
      }

      $this->setResult($data);  
    
    } catch (Exception $e) {
      $this->setResult(NULL, $e->getMessage());
    }
		
		return $this->result;
	}
	
	
	public function getAllActiveLaytitles() {
	
    try {
      
      $db = Resources_PdoMysql::getConnection();

      $sql = "SELECT DISTINCT o.onetcode AS onetcode, o.laytitle AS laytitle FROM onetsoc_laytitle o 
              JOIN vcn_occupation_industry i ON o.onetcode = i.onetcode 
              WHERE i.industry_id = :industry_id AND UPPER(o.job_search_yn) = 'Y'";


      $stmt = $db->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetchAll();

      $data = array();
      foreach ($result as $row) {
        $data[] = $row['laytitle'];
      }

      $this->setResult($data);  
    
    } catch (Exception $e) {
      $this->setResult(NULL, $e->getMessage());
    }
	
		return $this->result;
	}
	
	public function getAllActiveLaytitleAutosuggest($params) {
		
    try {
      
      $searchTerm = strtolower($params['search_term']);

      $db = Resources_PdoMysql::getConnection();

      $sql = "SELECT DISTINCT(laytitle) AS laytitle FROM 
              (SELECT DISTINCT(o.display_title) AS laytitle FROM vcn_occupation o JOIN vcn_occupation_industry i ON o.onetcode = i.onetcode WHERE industry_id = :industry_id AND LOWER(o.display_title) LIKE :searchTerm 
              UNION 
              SELECT DISTINCT(o.laytitle) AS laytitle FROM onetsoc_laytitle o JOIN vcn_occupation_industry i ON o.onetcode = i.onetcode WHERE industry_id = :industry_id AND UPPER(job_search_yn) = 'Y' AND LOWER(laytitle) LIKE :searchTerm ) detail_lay 
              ORDER BY laytitle ASC LIMIT 15";

      $binds = array(':searchTerm' => '%'.$searchTerm.'%', ':industry_id' => $params['industry_id']);

      $stmt = $db->prepare($sql);
      $stmt->execute($binds);
      $result = $stmt->fetchAll();

      $data = array();
      foreach ($result as $row) {
        $data[] = $row['laytitle'];
      }

      $this->setResult($data);  
    
    } catch (Exception $e) {
      $this->setResult(NULL, $e->getMessage());
    }
		
		return $this->result;
	}
		
}
 