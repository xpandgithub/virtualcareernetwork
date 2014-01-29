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
 * VCN_Model_VCNLookup Class
 * 
 * 
 * @package    VCN
 * @subpackage
 * @author     
 * @version    SVN: $Id:$
 */
class VCN_Model_VCNLookup extends VCN_Model_Base_VCNBase {
	
	public function getWorkCategories($params) {

		$requiredParams = array('industry');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
		
    try {
      
      $db = Resources_PdoMysql::getConnection();

      $sql = " SELECT DISTINCT lwc.work_category_id, lwc.work_category_code, lwc.work_category_name, lwc.work_category_name_abbrev, lwc.work_category_desc, lwc.work_category_order
               FROM vcn_occupation_industry oi
               JOIN vcn_lookup_work_category lwc ON oi.work_category_id = lwc.work_category_id AND oi.industry_id = :industry 
               ORDER BY lwc.work_category_order ";

      $binds = array(
        ':industry' => $params['industry'],
      );

      $stmt = $db->prepare($sql);
      $stmt->execute($binds);

      $result = $stmt->fetchAll();

      $data = array('categories' => $result);

      $this->setResult($data);  
    
    } catch (Exception $e) {
      $this->setResult(NULL, $e->getMessage());
    }

	  return $this->result;
	  
	}
	
	public function getResourceCategories($params) {
	
    try {
      
      $db = Resources_PdoMysql::getConnection();

      $sql = " SELECT resource_category_id, resource_category_name, resource_category_desc, resource_category_order
               FROM vcn_lookup_resource_category
               ORDER BY resource_category_order ";

      $stmt = $db->prepare($sql);
      $stmt->execute();

      $result = $stmt->fetchAll();

      $data = array('categories' => $result);

      $this->setResult($data);  
    
    } catch (Exception $e) {
      $this->setResult(NULL, $e->getMessage());
    }
	
		return $this->result;
		 
	}
	
	public function getPartnerCategories($params) {
	
    try {
      
      $db = Resources_PdoMysql::getConnection();

      $sql = " SELECT partner_category_id, partner_category_name, partner_category_desc, partner_category_order
               FROM vcn_lookup_partner_category
               ORDER BY partner_category_order ";

      $stmt = $db->prepare($sql);
      $stmt->execute();

      $result = $stmt->fetchAll();

      $data = array('categories' => $result);

      $this->setResult($data);  
    
    } catch (Exception $e) {
      $this->setResult(NULL, $e->getMessage());
    }
	
		return $this->result;
			
	}
	
	public function getOccupationResourceCategories($params) {
	
    try {
      
      $db = Resources_PdoMysql::getConnection();

      $sql = " SELECT category_id, category_name
               FROM vcn_occ_resource_category
               WHERE lower(active_yn) = 'y'
               ORDER BY category_name ";

      $stmt = $db->prepare($sql);
      $stmt->execute();

      $result = $stmt->fetchAll();

      $data = array('categories' => $result);

      $this->setResult($data);  
    
    } catch (Exception $e) {
      $this->setResult(NULL, $e->getMessage());
    }
	
		return $this->result;
			
	}
	
	public function getEducationCategory($params) {
		
		try {
			$db = Resources_PdoMysql::getConnection();
			if (isset($params['no_less_than_hs']) && $params['no_less_than_hs'] == TRUE) {
				$sql = "SELECT education_category_id, education_category_name from vcn_edu_category where education_category_id > 1 ";
			} else {
				$sql = "SELECT education_category_id, education_category_name from vcn_edu_category ";
			}
			
			$stmt = $db->prepare($sql);
			$stmt->execute();
				
			$result = $stmt->fetchAll();
			$data = array('categories' => $result);
				
			$this->setResult($data);  
			
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
		
		return $this->result;
		
	}
	
	public function getEducationIpedCategory($params) {
	
		try {
	
			$db = Resources_PdoMysql::getConnection();
	
			$sql = "select * from vcn_edu_category_iped order by iped_lookup_code asc";
	
			$stmt = $db->prepare($sql);
			$stmt->execute();
	
			$result = $stmt->fetchAll();
	
			$data = array('categories' => $result);
	
			$this->setResult($data);
	
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
	
		return $this->result;
			
	}
	
  public function getWagePeriodYear() {
	
		try {
	
			$db = Resources_PdoMysql::getConnection();
	
			$sql = "SELECT distinct(periodyear) FROM wage_occ";
	
			$stmt = $db->prepare($sql);
			$stmt->execute();
	
			$result = $stmt->fetchAll();
	
			$data = array('wages' => $result);
	
			$this->setResult($data);
	
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
	
		return $this->result;
			
	}
	
	public function getIndustryFactoid($params) {
	
		$requiredParams = array('industry');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
	
		try {
	
			$db = Resources_PdoMysql::getConnection();
	
			$sql = "SELECT factoid_id as factoidid, factoid_text as factoidtext
				   FROM vcn_industry_factoid			
	               WHERE industry_id = :industry ";
		
			$binds = array(
					':industry' => $params['industry'],
			);
	
			$stmt = $db->prepare($sql);
			$stmt->execute($binds);
	
			$result = $stmt->fetchAll();
	
			$data = array('factoid' => $result);
	
			$this->setResult($data);
	
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
	
		return $this->result;
		 
	}
	
	public function getIndustryInfo($params) {
	
		$requiredParams = array('industry');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
	
		try {
	
			$db = Resources_PdoMysql::getConnection();
	
			$sql = "SELECT industry_name as industryname, industry_description as industrydesc, industry_code as industrycode
				   FROM vcn_industry
	               WHERE industry_id = :industry ";
	
			$binds = array(
					':industry' => $params['industry'],
			);
	
			$stmt = $db->prepare($sql);
			$stmt->execute($binds);
	
			$result = $stmt->fetchAll();
	
			$data = array('industryinfo' => $result);
	
			$this->setResult($data);
	
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
	
		return $this->result;
			
	}
	
	public function getIndustryEducationGroupList($params) {
	
		$requiredParams = array('industry');
		if (!$this->checkParams($params, $requiredParams)) {
			return $this->result;
		}
	
		try {
	
			$db = Resources_PdoMysql::getConnection();
	
			$sql = "SELECT group_id as groupid, min_edu_category_id as mineducatid, max_edu_category_id as maxeducatid, group_name as groupname
				   FROM vcn_industry_education_group
	               WHERE industry_id = :industry 
				   ORDER BY group_id";
	
			$binds = array(
					':industry' => $params['industry'],
			);
	
			$stmt = $db->prepare($sql);
			$stmt->execute($binds);
	
			$result = $stmt->fetchAll();
	
			$data = array('educationgroups' => $result);
	
			$this->setResult($data);
	
		} catch (Exception $e) {
			$this->setResult(NULL, $e->getMessage());
		}
	
		return $this->result;
			
	}
  
}
 