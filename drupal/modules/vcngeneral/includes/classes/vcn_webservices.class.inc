<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php
class vcnWebServices {
	
	protected $_secret = '';
	protected $_secretId = '';
	protected $_baseurl = 'http://x.x.1.139/rest/public/';
	protected $_method = 'POST';
	protected $_service;
	protected $_module;
	protected $_action;
	protected $_request = array();

	protected $status;

	public function getSecret() {
		return $this->_secret;
	}
	
	public function setSecret($val) {
		$this->_secret = $val;
	}

	public function getSecretId() {
		return $this->_secretId;
	}
	
	public function setSecretId($val) {
		$this->_secretId = $val;
	}

	public function getBaseurl() {
		return $this->_baseurl;
	}
	
	public function setBaseurl($val) {
		$this->_baseurl = $val;
	}

	public function getMethod() {
		return $this->_method;
	}
	
	public function setMethod($val) {
		$this->_method = $val;
	}

	public function getService() {
		return $this->_service;
	}
	
	public function setService($val) {
		$this->_service = $val;
	}

	public function getModule() {
		return $this->_module;
	}
	
	public function setModule($val) {
		$this->_module = $val;
	}

	public function getAction() {
		return $this->_action;
	}
	
	public function setAction($val) {
		$this->_action = $val;
	}

	public function getRequest() {
		return $this->_request;
	}
	
	public function setRequest($val = array()) {
		$this->_request = $val;
	}

	public function getRequestKey($key) {
		return $this->_request[$key];
	}
	
	public function setRequestKey($key, $val, $append = false) {
		if ($append) {
			$this->_request[$key][] = $val;
		} else {
			$this->_request[$key] = $val;
		}
	}

	public function call() {
		$auth = $this->_signArgs($this->_request, $this->_secret);
		$this->setRequestKey('auth', $auth);
		$url = $this->_baseurl . $this->_service . '/' . $this->_module . '/' . $this->_action . '/';

		$options = array(
		  'method' => strtoupper($this->_method),
		  'data' => drupal_http_build_query($this->_request),
		  'headers' => array('Content-Type' => 'application/x-www-form-urlencoded'),
		);
		
		$content = drupal_http_request($url, $options);

		global $user;
		
		if (isset($_GET['debug']) && array_search("administrator", $user->roles) == true) {
      $debugIndex = rand(100, 1000);
			$html = "<div id=\"debugdiv$debugIndex\" class=\"rest-debugger\">" .
			        "<p><strong>REST DEBUG FOR:</strong> $url &nbsp;&nbsp;&nbsp; <a id=\"debuglink$debugIndex\" href=\"javascript:void(0);\" onclick=\"vcn_show_hide_debug_div('$debugIndex');\" style=\"color:blue;\">[show details]</a></p>" .
              "<p><pre>" .
              "<strong>Parameters:</strong> <br>" .
			        "<strong>Url:</strong> $url <br>" .
			        "<strong>Request:</strong> " . print_r($this->_request, true) . "<br>" .
			        "<strong>Raw Results:</strong> " . print_r($content, true) . "<br>" .
			        "</pre></p>" .
              "</div><p/>";
              
      print $html;
		}
		
		if ($content->data) {
			$data = $content->data;
		} else {
			if (empty($this->request)) {
	  			$data['NODATA'] = 'No data found for request: ' . $url;
	  		} else {
	  			$data['NODATA'] = 'No data found for request: ' . $url . http_build_query($this->request,'','&');
	  		}
		}

		return $data;
	}

	private function _signArgs($args, $secret) {

		ksort($args);
		$a = '';
		foreach($args as $k => $v) {
			$a .= $k . $v;
		}
		return md5($secret.$a);
	}

	protected function buildUrl($withParams = true) {

		if (!strlen($this->getBaseurl())) {
			return false;
		}
		if (!strlen($this->getService())) {
			return false;
		}
		if (!strlen($this->getModule())) {
			return false;
		}
		if (!strlen($this->getSecretId())) {
			return false;
		}
		if (!strlen($this->getSecret())) {
			return false;
		}
		
		$url = $this->getBaseurl() . '/' .
				$this->getService() . '/' .
				$this->getModule() . '/' .
				$this->getSecretId() . '/' .
				$this->getSecret();

		if ($withParams) {
			$params = $this->getRequest();
			
			foreach ($params as $key => $value) {
				if (strlen($key) && strlen($value)) {
					$url .= '/' . $key . '/' . $value;
				}
			}
			
		}

		return $url;

	}

	protected function getParamQuery() {

		$params = '';

		if (is_array($this->getRequest()) && count($this->getRequest()) > 0) {
			$params = http_build_query($this->getRequest(), '', '&');
		}

		return $params;

	}

	public function getData() {

		try {
			
			$this->setMethod('GET');
			$url = $this->buildUrl();

			$options = array(
			  'method' => strtoupper($this->_method),
			  'request' => drupal_http_build_query($this->_request),
			  'headers' => array('Content-Type' => 'application/x-www-form-urlencoded'),
			);
			
			$content = drupal_http_request($url, $options);
	
			global $user;
			
			if (isset($_GET['debug']) && array_search("administrator", $user->roles) == true) {
        $debugIndex = rand(100, 1000);
        $html = "<div id=\"debugdiv$debugIndex\" class=\"rest-debugger\">" .
                "<p><strong>REST DEBUG FOR:</strong> $url &nbsp;&nbsp;&nbsp; <a id=\"debuglink$debugIndex\" href=\"javascript:void(0);\" onclick=\"vcn_show_hide_debug_div('$debugIndex');\" style=\"color:blue;\">[show details]</a></p>" .
                "<p><pre>" .
                "<strong>Url:</strong> $url <br>" .
                "<strong>Request:</strong> " . print_r($this->getRequest(), true) . "<br>" .
                "<strong>Options:</strong> " . print_r($options, true) . "<br>" .
                "<strong>Raw Results:</strong> " . print_r($content, true) . "<br>" .
                "</pre></p>" .
                "</div><p/>";

        print $html;
      }
    
			if ($content->data) {
				$data = $content->data;
			} else {
				$data['NODATA'] = 'No data found for request: ' . $url;
			}

			$result = new SimpleXMLElement($data);
     
      if (isset($result->ErrorMessage) && strtoupper($result->ErrorMessage) != 'SUCCESS') {
        $this->status = new vcnStatus();
        $this->status->message = $result->ErrorMessage;
        $this->status->code = $result->ErrorNumber;
      }
      
		} catch (Exception $e) {

			// need to log the error
			
			if (isset($_GET['debug'])) {
				echo "<div style='border: 1px black solid;'><p><pre>";
				echo "Exception: " . $e->getTraceAsString() . "<br>";
				echo "</pre></p></div>";
			}
			
			$this->status = new vcnStatus();
			$this->status->message = $e->getMessage();
			$this->status->code = $e->getMessage();
			$result = new SimpleXMLElement('<data/>');
			
		}
		
		return $result;

	}

	public function getDataAsDOM() {

		$objDOM = false;

		if ($url = $this->buildUrl()) {

			try {

				$ch = curl_init();

				curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_REFERER, $url);
				curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
				curl_setopt($ch, CURLOPT_FAILONERROR, true);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_NOBODY, false);
				curl_setopt($ch, CURLOPT_TIMEOUT, 30);
				curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');

				$xml = curl_exec($ch);

				curl_close($ch);

				$objDOM = DOMDocument::loadXML($xml);

			} catch (Exception $e) {

				$objDOM = false;

			}

		}

		return $objDOM;

	}

}
?>
