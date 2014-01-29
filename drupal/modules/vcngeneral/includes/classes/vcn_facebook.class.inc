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
* vcnFacebookMetatag()
* This class is used to hold and control the Facebook metatag information
*/
class vcnFacebookMetatag {
	
	public $key = '';
	public $title = '';
	public $url = '';
	public $imgUrl = '';
	public $description = '';
	public $type = 'website';
	public $descriptionMaxLength = 200;
	
	public function  __construct($_title, $_url, $_description='', $_imgUrl='', $_key='facebook') {
		$this->title = $_title;
		$this->url = $_url;
		$this->description = $_description;
		$this->imgUrl = $_imgUrl;
		$this->key = $_key;
	}
	
	/**
	* getTags()
	* Gets all the Facebook metatags and returns them as a string of HTML
	*/
	public function getTags() {
		
		$_desc = $this->description;
		
		if (strlen($this->description) > $this->descriptionMaxLength) {
			$_desc = $this->description;
			$_desc = wordwrap($_desc, $this->descriptionMaxLength);
			$_desc = substr($_desc, 0, strpos($_desc, "\n"));			
			$_desc = strip_tags($_desc);		
			$_desc = str_replace('"', '', $_desc);		
			$_desc = $_desc . ' ...';
		}
	
		if (!strlen($this->imgUrl)) {
			$this->imgUrl = "https://" . $_SERVER['SERVER_NAME'] . vcn_drupal7_base_path() . vcn_image_path() . "vcn_logo_without_text.png";
		}
	
		drupal_add_html_head(array(
				'#tag' => 'meta', 
				'#attributes' => array(
				'property' => 'og:title',
			  'content' => $this->title,
		  ),
		), $this->key . '_og_title');
		
		drupal_add_html_head(array(
				'#tag' => 'meta', 
				'#attributes' => array(
				'property' => 'og:type',
			  'content' => $this->type,
		  ),
		), $this->key . '_og_type');
		
		drupal_add_html_head(array(
				'#tag' => 'meta', 
				'#attributes' => array(
				'property' => 'og:url',
			  'content' => $this->url,
		  ),
		), $this->key . '_og_url');
		
		drupal_add_html_head(array(
				'#tag' => 'meta', 
				'#attributes' => array(
				'property' => 'og:image',
				'content' => $this->imgUrl,
		  ),
		), $this->key . '_og_image');
		
		drupal_add_html_head(array(
				'#tag' => 'meta', 
				'#attributes' => array(
				'property' => 'og:site_name',
				'content' => "Virtual Career Network",
		  ),
		), $this->key . '_og_site_name');
		
		drupal_add_html_head(array(
			  '#tag' => 'meta', 
			  '#attributes' => array(
			  'property' => 'fb:admins',
			  'content' => $GLOBALS['vcn_config_facebook_user_id'],
		  ),
		), $this->key . '_fb_admins');
		
		drupal_add_html_head(array(
		    '#tag' => 'meta', 
		    '#attributes' => array(
		    'property' => 'og:description',
		    'content' => $_desc,
		  ),
		), $this->key . '_og_description');
		
	}
	
}

/**
* vcnFacebookLike()
* This class is used to hold and control the Facebook like button
*/
class vcnFacebookLike {
	
	public $url = '';
	public $shiftTop = '15';
	public $shiftLeft = '5';
	public $width = '90';
	public $height = '21';
	
	public function  __construct($_url) {
		$this->url = urlencode($_url);
	}
	
	/**
	* getButton()
	* Generates the Facebook like button iframe code
	*/
	function getButton() {
	
		$_html = '<!-- Facebook IFRAME for Like button -->' . PHP_EOL .
			         '<iframe ' .
					 'src="//www.facebook.com/plugins/like.php?' .
					 'href=' . $this->url .
					 '&amp;send=false' .
					 '&amp;layout=button_count' .
					 '&amp;width=' . $this->width .
					 '&amp;show_faces=false' .
					 '&amp;action=like' .
					 '&amp;colorscheme=light' .
					 '&amp;font' .
					 '&amp;height=' . $this->height . '" ' .
					 'scrolling="no" ' .
					 'frameborder="0" ' .
					 'style="border:none; ' .
					 'overflow:hidden; ' .
					 'width:' . $this->width . 'px; ' .
					 'height:' . $this->height . 'px; ' .
					 'position:relative; ' .
					 'top:' . $this->shiftTop . 'px; ' .
					 'right:' . $this->shiftLeft . 'px;" ' .
					 'allowTransparency="true"' .
					 'longdesc="Facebook Like Button iFrame"' .
           'title="Facebook Like Button iFrame">' .
           'Facebook IFRAME for Like button.  Your browser does not support inline frames or is currently configured not to display inline frames' .
           '</iframe>' . PHP_EOL;
	
		return $_html;
	}
}
