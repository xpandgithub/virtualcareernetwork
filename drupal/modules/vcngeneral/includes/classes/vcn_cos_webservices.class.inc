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
* vcnCosWebservice()
* This class is used to call the COS web services
* 
* List of Certifications
* http://webservices.myskillsmyfuture.org/Certification.svc/certifications/userid//onetcode/11911100
* 
* Certification Details
* http://webservices.myskillsmyfuture.org/Certification.svc/certification/userid//certid/2392
* 
* List of Licenses
* http://webservices.myskillsmyfuture.org/License.svc/licenselist/userid//onetcode/29114100/location/GA
* 
* License Details
* http://webservices.myskillsmyfuture.org/License.svc/license/userid//onetcode/29205100/licenseid/67/location/GA
* 
*/
class vcnCosWebService extends vcnWebServices {

	const SERVICE_CERTIFICATION = 'Certification.svc';
	const SERVICE_LICENSE = 'License.svc';
	const MODULE_CERTIFICATION_LIST = 'certifications';
	const MODULE_CERTIFICATION_DETAIL = 'certification';
	const MODULE_LICENSE_LIST = 'licenselist';
	const MODULE_LICENSE_DETAIL = 'license';
	const OUTPUT_OBJECT = 'object';
	const OUTPUT_XML = 'xml';
	const OUTPUT_COUNT = 'count';
	
	public $status;
	public $data;
	
	public function  __construct() {
		
		$this->setBaseurl( $GLOBALS['vcn_config_cos_web_service_url'] );
		$this->setSecretId( $GLOBALS['vcn_config_cos_web_service_id'] );
		$this->setSecret( $GLOBALS['vcn_config_cos_web_service_key'] );
		
	}
	
	public function getCertifications($onetcode, $useCache=true) {
		
    $params = array('onetcode' => $this->formatOnetcode($onetcode));
    
    $cid = $this->getCacheCid($this::SERVICE_CERTIFICATION, $this::MODULE_CERTIFICATION_LIST, $params);
    
    $cachedData = ($useCache) ? cache_get($cid) : false;
    
    if(!$cachedData || isset($_GET['debug'])) {
      $this->setService($this::SERVICE_CERTIFICATION);
      $this->setModule($this::MODULE_CERTIFICATION_LIST);

      $this->setRequest($params);

      $result = $this->getData();

      cache_set($cid, $result->asXML());
    } else {
      $result = new SimpleXMLElement($cachedData->data);
    }
    
    $this->data = $result->CertificationList->Certification;
    
		return $result;
		
	}
	
	public function getCertification($certId, $useCache=true) {
		
    $params = array('certid' => $certId);
    
    $cid = $this->getCacheCid($this::SERVICE_CERTIFICATION, $this::MODULE_CERTIFICATION_DETAIL, $params);
    
    $cachedData = ($useCache) ? cache_get($cid) : false;
    
    if(!$cachedData || isset($_GET['debug'])) {
      $this->setService($this::SERVICE_CERTIFICATION);
      $this->setModule($this::MODULE_CERTIFICATION_DETAIL);

      $this->setRequest($params);

      $result = $this->getData();

      cache_set($cid, $result->asXML());
    } else {
      $result = new SimpleXMLElement($cachedData->data);
    }
    
		$this->data = $result->CertificationDetails;
		
		return $result;
		
	}
	
	public function getLicenses($onetcode, $location, $useCache=true) {
		
    $params = array( 'onetcode' => $this->formatOnetcode( $onetcode ),
                     'location' => $location );
    
    $cid = $this->getCacheCid($this::SERVICE_LICENSE, $this::MODULE_LICENSE_LIST, $params);
    $cachedData = ($useCache) ? cache_get($cid) : false;
    
    if(!$cachedData || isset($_GET['debug'])) {
      $this->setService($this::SERVICE_LICENSE);
      $this->setModule($this::MODULE_LICENSE_LIST);

      $this->setRequest($params);

      $result = $this->getData();	
      cache_set($cid, $result->asXML());
    } else {
      $result = new SimpleXMLElement($cachedData->data);
    }
    
		$this->data = $result->LicenseList->License;
		
		return $result;
		
	}
	
	public function getLicense($onetcode, $licenseId, $location, $useCache=true) {
		
    $params = array( 'onetcode' => $this->formatOnetcode( $onetcode ),
                     'licenseid' => $licenseId,
                     'location' => $location );
    
    $cid = $this->getCacheCid($this::SERVICE_LICENSE, $this::MODULE_LICENSE_DETAIL, $params);
    
    $cachedData = ($useCache) ? cache_get($cid) : false;
    
    if(!$cachedData || isset($_GET['debug'])) {
      $this->setService( $this::SERVICE_LICENSE );
      $this->setModule( $this::MODULE_LICENSE_DETAIL );

      $this->setRequest( $params );

      $result = $this->getData();

      cache_set($cid, $result->asXML());
    } else {
      $result = new SimpleXMLElement($cachedData->data);
    }
   
		$this->data = $result->LicenseDetails;
		
		return $result;
		
	}
	
	protected function formatOnetcode( $onetcode ) {
		
		$onetcode = str_replace( '-', '', $onetcode );
		$onetcode = str_replace( '.', '', $onetcode );
		
		return $onetcode;
		
	}

	public function addStatusToXml( &$node ) {
			
		$statusText = 'success';
		$statusCode = 'success';
		
		if (isset($this->status->message)) {
			$statusText = 'error';
			$statusCode = $this->status->code;
		}
	
		$status = $node->addChild( 'status' );
		$status->addChild( 'value', $statusText );
		$status->addChild( 'code', $statusCode );
		$status->addChild( 'message', isset($this->status->message) ? $this->status->message : '' );
		$status->addChild( 'rowcount', '' );
	
	}
	
  private function getCacheCid($service, $module, $params) {
    $cid = $service.'-'.$module;

    if ($params) {
      foreach ($params as $key => $value) {
        if (isset($value)) {
          $cid .= '-'.$value;
        }
      }
    }

    return $cid;
  }
  
}
