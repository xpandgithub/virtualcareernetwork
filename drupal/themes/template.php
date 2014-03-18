<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php


function vcnstark_form_alter(&$form, &$form_state, $form_id) {
  //print "FORM IS " . $form_id . "<br />";
  if ($form_id=="search_block_form") {
  	$form["actions"]['submit']["#value"] = 'GO';
  	$form["actions"]['submit']['#attributes']["title"] = 'Search';
  	$form["actions"]['submit']['#attributes']['class'][] = 'vcn-button header-buttons header-buttons-large-text element-hidden';
  	$form['search_block_form']['#default_value']= ' Search'; 
  	$form['search_block_form']['#attributes']['class'][] = 'vcn-header-search';
    $form['search_block_form']['#style'] = 'font-weight: bold; font-color: #bbbbbb';
    $form['search_block_form']['#attributes']['onblur'] = "if (this.value == '') { this.value = ' Search'; this.style.fontWeight = 'bold'; this.style.color = '#bbbbbb'; }"; 
    $form['search_block_form']['#attributes']['onfocus'] = "if (this.value == ' Search') {this.value = ''; this.style.fontWeight = 'normal'; this.style.color = '#000000'; }";
    $form['#attributes']['onsubmit'] = "if (this.search_block_form.value==' Search'){ return false; }";
    $form['search_block_form']['#attributes']['autocomplete'] = 'off';
  }
  
  if ($form_id=="search_form") {  	
  	$form["basic"]['submit']['#attributes']['class'][] = 'vcn-button form-submit';  
  	$form["basic"]['submit']['#attributes']['title'] = 'Search';
  }
  
}


function vcnstark_preprocess_html(&$variables) {
	$path = vcn_drupal7_base_path() . drupal_get_path('theme', 'vcnstark')."/css";
	$css = ".vcn-round-border,
		.vcn-content-wrapper,
		.vcn-user-navigation-bar,
		.vcn-button,
		.ui-dialog .vcn-button,
		.vcn-dialog .ui-button,
		.rndcrnr,
		#vcn-tabs-blueborder,
		#findwork-submit-form,
		.alert,
		#profiler-box,
		.ctools-modal-content,
		.modal-header,
		.ctools-use-modal,
		.vcn-tabs-on-left,
		.vcn-tabs-on,
		.vcn-tabs-on-right,
		.vcn-tabs-off-left,
		.vcn-tabs-off,
		.vcn-tabs-off-right,
		.vcn-navigation-bar {
		behavior: url($path/PIE-1.0.0/PIE.htc);
	}  ";

	drupal_add_css($css, 'inline');
	//drupal_add_js($path."/PIE-1.0.0/PIE.js");

	//Add industry based id and class to body tag
	$industry_code = vcn_get_industry_code();
	$variables['attributes_array']['id'] = 'vcn-'.$industry_code;
	$variables['classes_array'][] = 'vcn-'.$industry_code;

  $site_baseurl = vcn_base_url();
	$drupal7_basepath = vcn_drupal7_base_path();
	$drupal6_basepath = vcn_drupal6_base_path();
	$drupal7_images_basepath = vcn_image_path();
	$drupal7_videos_basepath = vcn_video_path();
	$moodle_basepath = vcn_moodle_base_path();
  
	$industry_id = vcn_get_industry();
	$industry_name = vcn_get_industry_name();
	
	drupal_add_js(array(
								'drupal_basepaths' => array(
                      'site_baseurl' => $site_baseurl,
											'drupal7_basepath' => $drupal7_basepath, 
											'drupal6_basepath' => $drupal6_basepath,
											'drupal7_images_basepath' => $drupal7_images_basepath,
											'drupal7_videos_basepath' => $drupal7_videos_basepath,
                      'moodle_basepath' => $moodle_basepath
									),
									'industry' => array(
											'industry_id' => $industry_id,
											'industry_name' => $industry_name
									)
							), 
							array('type' => 'setting')
						);
	
	// Construct page title
	$site_name = "VCN ".vcn_get_industry_name();	
	if (drupal_get_title()) {
		$head_title = array(strip_tags(drupal_get_title()), $site_name);
	}
	else {
		$head_title = array($site_name);
	}
	$variables['head_title'] = implode(' | ', $head_title);
}

/*
 * Implementing theme_preprocess_page 
 * @param array $variables
 * this function is used to make sure page--occupation.tpl.php is used to override page.tpl.php for content type occupation
 * 
*/

function vcnstark_preprocess_page(&$variables) {	 //var_dump($variables);
	if (isset($variables['node']->type) && ( $variables['node']->type == 'occupation' ||  $variables['node']->type == 'lightbox')) {
		$variables['theme_hook_suggestions'][] = 'page__'.$variables['node']->type;
	}  
	
	// Display title only if page type is "node" (To hide title for all custom modules with hook_menu)
	if (!isset($variables['node']->type)){
		$variables['title'] = "";
	}
	
	// To redirect text snippets from search result to main page.
	//if (isset($variables['node']->type) &&  $variables['node']->type=="vcn_text") {
	if (isset($variables['node']->type) && ( $variables['node']->type == 'occupation' ||  $variables['node']->type == 'vcn_text')) {
		if (isset($variables['node']->field_target_path['und']) && isset($variables['node']->field_target_path['und'][0]) && isset($variables['node']->field_target_path['und'][0]['value'])) {
			$target_path = $variables['node']->field_target_path['und'][0]['value'];			
			drupal_goto($target_path);
		}
	}
	
	// To hide "No front page content has been created yet" message at home page when there is no article/node assigned to front page.
	if (isset($variables['is_front']) && $variables['is_front']){ //OR if(drupal_is_front_page()) { unset($page['content']['system_main']['default_message']); } at page.tpl.php		
		unset($variables['page']['content']['system_main']['default_message']); // OR css #first-time {display:none;}
	}	
	
}

	

function vcnstark_preprocess_node(&$variables) {
	$nodetype = $variables['type'];
	if ($nodetype == 'occupation') {
		// Add the JS file only if page is being displayed for content type 'occupation'
		drupal_add_js(drupal_get_path('theme', 'vcnstark') .'/js/vcnstark_occupation.js', 'file');
		drupal_add_js(drupal_get_path('theme', 'vcnstark') .'/js/vcn_google_charts.js', 'file');
		
		// Add external Google JS file to be used for Google Charts API
		drupal_add_js('https://www.google.com/jsapi', 'external');
	}

}

/*
 * Implementing theme_menu_local_tasks from includes/menu.inc
*  This function is used to  override core function to fix broken menus through out drupal
*
*/


function vcnstark_menu_local_tasks(&$variables) {
	$output = '';

	if (!empty($variables['primary'])) {
		$variables['primary']['#prefix'] = '<h2 class="element-invisible">' . t('Primary tabs') . '</h2>';
		$variables['primary']['#prefix'] .= '<ul class="tabs primary clearfix">'; // Edited
		$variables['primary']['#suffix'] = '</ul>';
		$output .= drupal_render($variables['primary']);
	}
	if (!empty($variables['secondary'])) {
		$variables['secondary']['#prefix'] = '<h2 class="element-invisible">' . t('Secondary tabs') . '</h2>';
		$variables['secondary']['#prefix'] .= '<ul class="tabs secondary">';
		$variables['secondary']['#suffix'] = '</ul>';
		$output .= drupal_render($variables['secondary']);
	}

	return $output;
}

/*
 * Implementing theme_menu_local_task from includes/menu.inc
*  This function is used to  override core function to fix broken menus through out drupal
*
*/

function vcnstark_menu_local_task($variables) {
	$link = $variables['element']['#link'];
	
	$link['title'] = '<span class="tab">'.$link['title'].'</span>'; // Edited
	$link["localized_options"]["html"] = TRUE; // Edited
	
	$link_text = $link['title'];
	
	

	if (!empty($variables['element']['#active'])) {
		// Add text to indicate active tab for non-visual users.
		$active = '<span class="element-invisible">' . t('(active tab)') . '</span>';

		// If the link does not contain HTML already, check_plain() it now.
		// After we set 'html'=TRUE the link will not be sanitized by l().
		if (empty($link['localized_options']['html'])) {
			$link['title'] = check_plain($link['title']);
		}
		$link['localized_options']['html'] = TRUE;
		$link_text = t('!local-task-title!active', array('!local-task-title' => $link['title'], '!active' => $active));
	}

	return '<li' . (!empty($variables['element']['#active']) ? ' class="active"' : '') . '>' . l($link_text, $link['href'], $link['localized_options']) . "</li>\n";
}

/*
 * Implementation of hook_theme where hook = name of currently active theme
 */
function vcnstark_theme() {
	return array (
		'vcnfindwork_form' => array (
			'render element' => 'form',
		),
		'vcnfindworkresults_form'	=> array (
			'render element'	=> 'form',
		),
	);
}

/**
 * Alter metatags before being cached. This hook is invoked prior to the meta tags for a given page are cached.
 * 
 * @param array $output
 *   All of the meta tags to be output for this page in their raw format. This
 *   is a heavily nested array.
 * @param string $instance
 *   An identifier for the current page's page type, typically a combination
 *   of the entity name and bundle name, e.g. "node:story".
 */
function vcnstark_metatag_metatags_view_alter(&$output, $instance) {
  // add the language metatag for WCAG compliance
  if (stristr($instance, 'global')) {
    $output['language']['#attached']['drupal_add_html_head'][0] = array(
      array(
        '#theme' => 'metatag',
        '#tag' => 'meta',
        '#id' => 'language',
        '#name' => 'language',
        '#value' => 'english',
      ),
      'language',
    );
  }
}

function vcnstark_preprocess_search_results(&$variables) {

	global $pager_total_items;	
	$removed_count = 0;
	$variables['search_results'] = '';	
	foreach ($variables['results'] as $result) { 
		
		// To skip industry specific nodes. 
		if(isset($result["node"]->field_industry_search_filter) && !empty($result["node"]->field_industry_search_filter) && $result["node"]->field_industry_search_filter["und"][0]["value"] != 0 && $result["node"]->field_industry_search_filter["und"][0]["value"] != 9999) { // To skip nodes with hidden field true
			
			$ind = vcn_get_industry();
			$node_ind = explode(",", $result["node"]->field_industry_search_filter["und"][0]["value"]);

			if(in_array($ind,$node_ind) == FALSE){	
				$removed_count++;		
				continue;
			}
		}
		
		$variables['search_results'] .= theme('search_result', array('result' => $result, 'module' => $variables['module']));
	}
	
	$variables['search_result_item_count'] =  $pager_total_items[0] - $removed_count;
	
}

function vcnstark_preprocess_search_result(&$variables) {
	// Hide drupal node user info.	
	$variables['info'] = "";
	
	$result = $variables['result'];
	$variables['snippet'] = isset($result['snippet']) ? $result['snippet'] : ''; 
	//$variables['snippet'] = $result['node']->body["und"][0]['value']; // Update result description.
	//echo $variables['snippet'] = drupal_substr($result['node']->body['und'][0]['value'], 160);
}

