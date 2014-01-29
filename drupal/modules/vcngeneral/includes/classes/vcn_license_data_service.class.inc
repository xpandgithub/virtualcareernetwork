<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php
abstract class vcnLicenseDataService  {
	
	public static function getLicensesCount( $onetcode, $location ) {
	
		$licensesArr = self::getLicensesByOnetcode( $onetcode, $location, vcnCosWebService::OUTPUT_COUNT );
	
		$count = 0;
		
		if ( is_array( $licensesArr ) ) {
			
			$count = count( $licensesArr );
			
		}
		
		return $count;
	
	}
	
	public static function getLicensesByOnetcode($onetcode, $location, $outputMode=vcnCosWebService::OUTPUT_XML, $offset=null, $limit=200, $orderBy=null, $direction=null) {
	
		$cosWS = new vcnCosWebService();
		$cosWS->getLicenses($onetcode, $location);
	
		$output = '';
     
    $licenseItems = !$cosWS->data->LICENSEID ? array() : $cosWS->data;

    $licenseSuppressedArr = array();
    if (strlen($location)) {
      $licenseSuppressedArr = self::getSuppressedLicenseIds( $onetcode, $location );
    }

    $licensesArr = array();

    $stfips = vcnStateUtil::getFipsByUnknown($location);

    // if no data coming from COS then check to see if VCN has any data
    $usingVcnData = false;
    if (!$licenseItems->LICENSEID) {       
      $usingVcnData = true;

      $params = array('onetcode' => $onetcode, 'stfips' => $stfips);
      $licenseData = vcn_rest_wrapper('vcntrainingsvc', 'vcnlicense', 'get-licenses', $params);       
      $licenseItems = $licenseData->licenses->item;
    }

    foreach ($licenseItems AS $items) {          
      $id = !$usingVcnData ? trim((string)$items->LICENSEID) : trim((string)$items->licenseid);

      if ($id) {
        if (!in_array($id, $licenseSuppressedArr)) {
          if ($outputMode != vcnCosWebService::OUTPUT_COUNT) {           
            $license = self::populate($items, $usingVcnData);
            $licensesArr[] = $license;
          } else {
            $licensesArr[] = $id;
          }
        }
      }
    }

    if ($outputMode != vcnCosWebService::OUTPUT_COUNT && count($licensesArr) > 0) {
      $licensesArr = vcnUtilities::transformArray( $licensesArr, $offset, $limit, $orderBy, $direction );
    }

    $output = $licensesArr;

    if ( $outputMode == vcnCosWebService::OUTPUT_XML ) {

      $xml = new SimpleXMLElement( '<root/>' );

      $cosWS->addStatusToXml( $xml );

      $data = $xml->addChild( 'data' );

      foreach ( $licensesArr AS $license ) {

        if ( $license ) {

          $licenses = $data->addChild( 'licenses' );

          $license->toXml( $licenses );

        }

      }

      $output = $xml;
    }
	
		return $output;
	
	}

	public static function getLicenseById($onetcode, $licenseId, $location, $outputMode=vcnCosWebService::OUTPUT_XML) {
			
		$cosWS = new vcnCosWebService();
		$cosWS->getLicense( $onetcode, $licenseId, $location );

		$output = '';	
    $usingVcnData = false;
    
		if (!isset($cosWS->data->LICENSEID)) {
      // if no data coming from COS then check to see if VCN has any data
      $usingVcnData = true;
      $params = array('licenseid' => $licenseId);
      $licenseData = vcn_rest_wrapper('vcntrainingsvc', 'vcnlicense', 'get-license', $params);
      $licenseItem = $licenseData->license->item;
    } else {
      $licenseItem = $cosWS->data;
    }
  
    $license = self::populate($licenseItem, $usingVcnData);

    $output = $license;

    if ( $outputMode == vcnCosWebService::OUTPUT_XML ) {

      $xml = new SimpleXMLElement( '<root/>' );

      $cosWS->addStatusToXml( $xml );

      $data = $xml->addChild( 'data' );

      $licenses = $data->addChild( 'licenses' );

      $license->toXml( $licenses );

      $output = $xml;
    }
		
		return $output;
	
	}
	
	private function getSuppressedLicenseIds($onetcode, $location) {
	
		// this function will be used to suppress licenses that we've 
		// defined in VCN db to suppress
		
		$outputArr = array();
	
		$conn = vcn_connect_to_db();
		
		$onetcode = mysql_real_escape_string($onetcode);
		$stfips = vcnStateUtil::getFipsByUnknown($location);
		
		$sql = " SELECT license_id AS id
		         FROM vcn_blacklist_license
		         WHERE onetcode = '$onetcode'
		         AND stfips = '$stfips'
		         ORDER BY license_id ";
		
		try {
			
			$result = mysql_query($sql) or die("Error getting licenses: " . mysql_error());
	
		  while ($row = mysql_fetch_object($result)) {
				$outputArr[] = (string)$row->id;
			}
		
		} catch (Exception $e) {
			
			$outputArr = $inputArr;
			
		}
		
		return $outputArr;
	
	}
	
	public static function populate($data, $usingVcnData=false) {

		$license = new vcnLicense();
		
		$license->id = !$usingVcnData ? trim((string)$data->LICENSEID) : trim((string)$data->licenseid);
		$license->title = !$usingVcnData ? trim((string)$data->LICTITLE) : trim((string)$data->lictitle);
		$license->description = !$usingVcnData ? trim((string)$data->DESCRIPTION) : trim((string)$data->description);
		
		$agency = new vcnLicenseAgency();
		$agency->name = !$usingVcnData ? trim((string)$data->LICENSINGAGENCY) : trim((string)$data->licensingagency);
		$agency->email = !$usingVcnData ? trim((string)$data->EMAIL) : trim((string)$data->email);
		$agency->phone = !$usingVcnData ? trim((string)$data->TELEPHONE) : trim((string)$data->telephone);
		$agency->phoneExt = !$usingVcnData ? trim((string)$data->TELEEXT) : trim((string)$data->teleext);
		$agency->url = !$usingVcnData ? trim((string)$data->URL) : trim((string)$data->url);
		
		$address = new vcnAddress();
		$address->address1 = !$usingVcnData ? trim((string)$data->ADDRESS1) : trim((string)$data->address1);
		$address->address2 = !$usingVcnData ? trim((string)$data->ADDRESS2) : trim((string)$data->address2);
		$address->city = !$usingVcnData ? trim((string)$data->CITY) : trim((string)$data->city);
		$address->zip = !$usingVcnData ? trim((string)$data->ZIP) : trim((string)$data->zip);
		$address->zipExt = !$usingVcnData ? trim((string)$data->ZIPEXT) : trim((string)$data->zipext);
		
		$state = new vcnState();
		$state->fips = !$usingVcnData ? trim((string)$data->STFIPS) : trim((string)$data->stfips);
		
		$address->state = $state;
		
		$agency->address = $address;
		
		$license->agency = $agency;
		
		// need to manually set this property because its dynamic
		$license->setAgencyName();
		
		return $license;
		
	}
	
}

?>
