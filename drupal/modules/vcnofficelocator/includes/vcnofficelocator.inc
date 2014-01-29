<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php

function vcnofficelocator_form_validate($form, &$form_state) {
	if (!is_numeric($form_state['values']['zip'])) {
		form_set_error('zip', t('Please enter a valid US ZIP code.'));
	}

}

function vcnofficelocator_form_submit($form, &$form_state) {
	//drupal_set_message(t('Wow! You don\'t look :zip - have you had work done?', array(':zip' => $form_state['values']['zip'])));
	$form_state['redirect'] = FALSE;
}

function vcn_get_lat_long_byzip($zipcode) {

	/* REST Call */
	// get the Latitude, Longitude for zipcode 
	$params = array('zipcode' => $zipcode);
	$content = vcn_rest_wrapper('vcnlocationsvc', 'vcnlocation', 'get-location', $params);
	$coords = array('latitude' => $content->location->item->latitude , 'longitude' => $content->location->item->longitude); 
	
	return $coords;
	/* End REST Call */
}

function vcn_get_partners(&$variables) {
	
	/* REST Call */
	// get the list of Partners 
	
	$variables['partners_category'] = array("1","2","3"); 
	
	$params = array('zipcode' => $variables['zipcode'], 'latitude' => $variables['coords']['latitude'], 'longitude' => $variables['coords']['longitude'], 'category' => $variables['partners_category']);
	$partners = vcn_rest_wrapper('vcnpartnerssvc', 'vcnpartners', 'get-partners', $params);
	$partnerslist = $partners->partners;
	
	/* End REST Call */
	
	$partners_html = "";
	if (count($partnerslist->item)>0) {
		for ($i = 0; $i < count($partnerslist->item); $i++) {
						
			$name = $partnerslist->item[$i]->officename;
			$street = $partnerslist->item[$i]->address;
			$city = $partnerslist->item[$i]->city;
			$state = $partnerslist->item[$i]->state;
			$zipcode = $partnerslist->item[$i]->zip;
			$phone = $partnerslist->item[$i]->phone;
			$distance = $partnerslist->item[$i]->distance;
			
			$contact = $partnerslist->item[$i]->contactperson;
			$email = $partnerslist->item[$i]->email;
			$contact_method = $partnerslist->item[$i]->preferredcontactmethod;
			$url = $partnerslist->item[$i]->url;
			
			if (strlen($contact_method) > 0) {
				$variables['contact_method_text'] = '<div class="office-locator-contact-method"><br/><br/><br/>** Note: ' . $contact_method . '</div>';
			}
			$partners_html .= vcn_process_office_text($name, $street, $city, $state, $zipcode, $phone, $distance, $contact, $email, $contact_method, $url). "<br/><br/>";
			
			
		}
	}

	return $partners_html;
}

function vcn_get_one_stop_careers_centers(&$variables) {
	
	$objDOM = vcn_call_one_stop_web_service($variables['zipcode']);
	if ($objDOM) {
		$one_stop_careers_centers = vcn_process_one_stop_careers($objDOM);
		return $one_stop_careers_centers;
	}else {
		return 'None found';
	}
	
}

function vcn_get_community_colleges(&$variables) {
	
	/* REST Call */
	// get the list of Provider
		
	$params = array('latitude' => $variables['coords']['latitude'], 'longitude' => $variables['coords']['longitude']);
	$provider = vcn_rest_wrapper('vcnprovidersvc', 'vcnprovider', 'get-provider', $params);
	
	$name = $provider->provider->item->instnm;
	$street = $provider->provider->item->addr;
	$city = $provider->provider->item->city;
	$state = $provider->provider->item->stabbr;
	$zipcode = $provider->provider->item->zip;
	$phone = $provider->provider->item->gentele;
	$distance = $provider->provider->item->distance;
	
	return vcn_process_office_text($name, $street, $city, $state, $zipcode, $phone, $distance);
	/* End REST Call */

}

function vcn_process_office_text($name, $street, $city, $state, $zipcode, $phone, $distance, $contact="", $email="", $contact_method="", $url="") {
	$html = '';
	
	if (strlen($name)) {
		
		if (strlen($url)) {
			$html .= vcn_build_link_window_opener($url, $name, false);
		} else {
			$html .= $name;
		}

		if (strlen($contact_method)) {
			$GLOBALS['contact_method_text'] = $contact_method;
			$html .= ' <span class="office-locator-contact-method">**</span>';
		}
		
		if (strlen($street)) {
			$html .= '<br/>' . $street;
		}
			
		if (strlen($city) && strlen($state)) {
			$html .= '<br/>' . $city . ', ' . $state;
		}
			
		if (strlen($zipcode)) {
			if (!strlen($street) && !strlen($city) && !strlen($state)) {
				$html .= '<br/>';
			}
			$html .= ' ' . $zipcode;
		}
			
		if (strlen($phone)) {
			if (strpos($phone, '-') === false) {
				$phonepre = substr($phone, 0, 3);
				$phonemid = substr($phone, 3, 3);
				$phonesuf = substr($phone, 6, 4);
				$html .= '<br/>' . $phonepre . '-' . $phonemid . '-' . $phonesuf;
			} else {
				$phone = str_replace(',', '<br/>', $phone);
				$html .= '<br/>' . $phone;
			}
			
		}
		
		if (strlen($contact)) {
			$html .= '<br/>' . $contact;
		}
		
		if (strlen($email)) {
			$html .= '<br/><a href="mailto:' . $email . '">' . $email . '</a>';
		}
		
		if (strlen($distance)) {
			$html .= '<br/><br/>Approx. ' . round(floatval($distance), 1) . ' miles';
		}
	}
	
	if (!strlen($html)) {
		$html = 'None found';
	}
	
	return $html;
}


function vcn_get_one_stop_webservice_key() {
	
	/* REST Call */
	// get the Latitude, Longitude for zipcode
	$params = array('prop_id' => 1);
	$content = vcn_rest_wrapper('vcnpropertiessvc', 'vcnproperties', 'get-properties', $params);
	$webservice_key = $content->properties->item->value;
	
	return $webservice_key;
	/* End REST Call */

}

function vcn_call_one_stop_web_service($zip) {

	$webservice_key = vcn_get_one_stop_webservice_key();
	$maxDistance = (string)$GLOBALS['default_distance'];

	$success = false;

	$url = 'http://www.servicelocator.org/wservice2/CVServices.asmx/FindOneStopsByZip?zip=' . $zip . '&state=VA&distance=' . $maxDistance . '&userID=' . $webservice_key;

	$objDOM = new DOMDocument();

	if (!$objDOM->load($url)) {
		for ($j = 0; $j < 3; $j++) {
			sleep(1);
			if ($objDOM->load($url)) {
				$success = true;
				break;
			}
		}
	} else {
		$success = true;
	}

	if (!$success) {
		$objDOM = false;
	}

	
	return $objDOM;
}

function vcn_process_one_stop_careers ($objDOM) {
	$html = '';
		
	$items = $objDOM->getElementsByTagName('Detail');

	$distance = 9999;
	$name = '';
	$street = '';
	$city = '';
	$state = '';
	$zipcode = '';
	$phone = '';

	if ($items) {
		foreach($items as $item) {
			foreach($item->childNodes as $i) {
				switch (strtoupper($i->nodeName)) {
					case 'NAME':
						$name0 = $i->nodeValue;
						break;
					case 'ADDRESS_1':
						$street0 = $i->nodeValue;
						break;
					case 'CITY':
						$city0 = $i->nodeValue;
						break;
					case 'STATE':
						$state0 = $i->nodeValue;
						break;
					case 'ZIP':
						$zip0 = $i->nodeValue;
						break;
					case 'PHONE':
						$phone0 = $i->nodeValue;
						break;
					case 'DIST':
						$dist0 = $i->nodeValue;
						break;
				}
			}
				
			if ($dist0) {
				$dist0 = intval($dist0);

				if ($dist0 < $distance) {
					$distance = $dist0;
					$name = $name0;
					$street = $street0;
					$city = $city0;
					$state = $state0;
					$zipcode = $zip0;
					$phone = $phone0;
				}
			}
		}
	}

	return vcn_process_office_text($name, $street, $city, $state, $zipcode, $phone, $distance);

}
