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
abstract class VCN_Model_Base_VCNBase {
	
	const STATUS_SUCCESS = 'success';
	const STATUS_FAIL = 'failed';
	
	const STATUS_CODE_SUCCESS = '0';
	const STATUS_CODE_MISSING_PARAMETER_ID = '9999';
	const STATUS_CODE_UNKNOWN_ERROR_ID = '8888';
  
	/*
	 * set the result to return
	* @param string $code    status code
	* @param string $msg     status message
	* @param array  $params  array of params
	* @param array  $data    data array to return
	* @return array $select
	*/
	protected function setResult($data, $msg=NULL, $params=NULL) {
    
    $status = $this::STATUS_FAIL;
    $code = $this::STATUS_CODE_UNKNOWN_ERROR_ID;
    
    if (isset($data) && is_array($data)) {
      $status = $this::STATUS_SUCCESS;
      $code = $this::STATUS_CODE_SUCCESS;
      $msg = 'data returned';
		} else {
      $data = array();
      $msg = 'ERROR: ' . $msg;
    }
    
		$this->data = $data;
		$this->status['status'] = $status;
		$this->status['code'] = (string)$code;
		$this->status['message'] = $msg;
		$this->status['rowcount'] = ($data && is_array($data)) ? sizeof($data): 0;
		$this->params = $params;
	
		$this->result['status'] = $this->status;
		$this->result['params'] = $this->params;
		$this->result['data'] = $this->data;
	}
	
	protected function checkParams($inputParams, $requiredParams) {
		
		$missingParams = '';

		foreach ($requiredParams as $requiredParam) {
		  if (!array_key_exists($requiredParam, $inputParams)) {
		    $missingParams .= $requiredParam . ' ';
		  }
		}
		
		if ($missingParams) {
			$this->setResult(self::STATUS_FAIL, self::STATUS_CODE_MISSING_PARAMETER_ID, 'Missing Parameter(s): ' . $missingParams, $inputParams, false);
			return false;
		}
		
		return true;
		
	}
	
	protected function parseParams($params) {
		
		if (!$params) {
			return false;
		}
		
		$where = array();
		
		foreach ($params as $key => $value) {
			switch (strtoupper($key)) {
				case 'MODULE':
				case 'ACTION':
				case 'CONTROLLER':
					//unset($params[$key]);
					break;
				case 'ONETCODE':
				case 'ZIPCODE':
					//$where[$key] = isset($value) ? $key.'='.$value : "";
					$where[$key] = $value;
					break;
				case 'SORT':
					//$sort = isset($value) ? $value : "";
					$sort = $value;
					break;
				case 'LIMIT':
					//$limit = isset($value) ? $value : "";
					$limit = $value;
					break;
				case 'ORDERBY':
					//$order = isset($value) ? $value : "";
					$order = $value;
				default:
					break;
			}
		}
		
		return array(
			'where' => $where,
			'sort' => isset($sort) ? $sort : "",
			'limit' => isset($limit) ? $limit : "",
			'orderby' => isset($order) ? $order : ""		
		);
	}
	
	protected function generateFilterQuery($parsed_query_params) {
		
		$substitution_params= array();
		
		foreach ($parsed_query_params as $key => $value) {
			switch ($key) {
				case 'where':
					if (!empty($parsed_query_params[$key])) {
						$whereclause = "";
						$i = 0;
						foreach ($parsed_query_params[$key] as $k => $v) {
							if ($i == 0) {
								$whereclause .= " WHERE $k = '%s' ";
							} else {
								$whereclause .= " AND $k = '%s' ";
							}
							$substitution_params[] = $v;
							$i++;
						}
					} else {
						$whereclause = "";
					}
					break;
				default:
					break;		
			}
		}
		
		$filter_query = $whereclause;
		return array('filter_query' => $filter_query, 'substitution_params' => $substitution_params);
		
	}
	
}
 