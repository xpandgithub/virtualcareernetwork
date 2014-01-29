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
 Import nodes of type "occupation".
**/

function vcn_import_occupations() { 
	
  $industry = vcn_get_industry();
  $industry_code = vcn_get_industry_code();
  
  error_log("#######VCN cron : Add drupal OCCUPATION nodes. ".$industry_code." ".date("M-d-Y H:i:s")."\n", 3, ini_get('error_log'));
  
  $params = array('industry' => $industry, 'additionalinfo' => 'true', 'restriction' => 'front_end', 'ignoreworktype' => 'Y');
  $content = vcn_rest_wrapper('vcnoccupationsvc', 'vcncareer', 'listcareers', $params, 'xml', 'post', false);
  
  $careers = $content->careerdata;
  $i=0;
  foreach ($careers as $career) {
	$i++;
    $args = array(
      ':title' => $career->title, 
      ':type' => 'occupation'
    );
    $num_rows = db_query(" SELECT COUNT(nid) FROM {node} WHERE title = :title AND type = :type ", $args)
                   ->fetchField();
    
    if ($num_rows == 0) {  
      $params = array('industry' => $industry, 'onetcode' => $career->onetcode, 'restriction' => 'front_end');
      $content = vcn_rest_wrapper('vcnoccupationsvc', 'vcnlaytitle', 'getactivelaytitlesforoccupation', $params, 'xml', 'post', false);

      $laytitlesArr = $content->laytitles;

      $laytitles = '';
      foreach ($laytitlesArr as $laytitle) {
        $laytitles .= ' ' . $laytitle->laytitle;
      }

      $blob = $career->title . PHP_EOL . PHP_EOL .
              $career->dayinlife . PHP_EOL . PHP_EOL .
              $career->academicreq . PHP_EOL . PHP_EOL .
              $career->physicalreq . PHP_EOL . PHP_EOL .
              $career->healthreq . PHP_EOL . PHP_EOL .
              $career->detaileddesc . PHP_EOL . PHP_EOL .
              $laytitles;
      
      error_log($i.": ADDING OCCUPATION: " .$career->title ."\n", 3, ini_get('error_log')); //print "ADDING OCCUPATION: " . $career->title . "<br/>" . PHP_EOL;
      
      try {

	      $newnode = new StdClass();
	      $newnode->type = 'occupation'; 
	      $newnode->title = (string) $career->title; // convert to string to avoide pathauto module warnings.
	      $newnode->uid = 1; // NOTE: admin user should always have uid=1 on this system
	      $newnode->status = 1; // published
	      $newnode->language = 'und';
	      $newnode->body['und'][0]['value'] = strip_tags($blob);
	      $newnode->field_onet_code['und'][0]['value'] = $career->onetcode;
	      $newnode->field_target_path['und'][0]['value'] = "careers/" . $career->onetcode;
	      $newnode->field_salary_range['und'][0]['value'] = 'x';
	      $newnode->field_hourly_range['und'][0]['value'] = 'x';
	      //$newnode->path['pathauto'] = FALSE;
	      //$newnode->path['alias'] = "careers/" . $career->onetcode;
	      
	      // add new node to drupal
	      node_save($newnode);
	      
      } catch (Exception $e) {
      	error_log("\n ---- ".$e->getMessage()."\n\n", 3, ini_get('error_log'));
      	drupal_set_message(t("Error occurred for OCCUPATION node : " .$e->getMessage()), 'error');
      	//watchdog('my_error', $e->getMessage());
      }
      
    }else {	
		error_log($i.": OCCUPATION Node: " .$career->title. " Already Exists. \n", 3, ini_get('error_log')); 
	}
  }
  error_log("#######VCN cron : END of add drupal OCCUPATION nodes. ".date("M-d-Y H:i:s")." \n", 3, ini_get('error_log'));
}

/**
 Import nodes of type "basic page/vcn text".
**/

function vcn_import_nodes() { 

	// clear cache //cache_clear_all(NULL, 'cache_page', '*'); 
	//drupal_flush_all_caches(); // To clear all node alias from url_alias table for deleted nodes.

	$industry = vcn_get_industry();	
	$industry_code = vcn_get_industry_code();
	
	error_log("#######VCN cron : Add drupal content nodes. ".$industry_code." ".date("M-d-Y H:i:s")."\n", 3, ini_get('error_log'));

	module_load_include('php', 'vcncron', '/vcncroncontents/vcncron_nodelist');
	$nodelist = vcn_get_import_nodes_list(); //call function to get nodelist
	
	/*$nodelist[] = array("node_type" => "", "node_title" => "", "node_body" => "", "node_text_format" => "", "node_path_alias" => "", "node_search_industry_filter" => "", "node_target_path" => "", "node_save_only_for_selected_industry" => "");*/
	$i=0;
	foreach ($nodelist as $vcnnode) { //error_log("vcnnode: ".print_r($vcnnode, true), 3, ini_get('error_log')); 
		
		$node_info = $vcnnode['node_type']." : ". $vcnnode['node_title'] ." : ". $vcnnode['node_path_alias'];
		
		if($vcnnode['node_type'] != "vcn_text" 
				&& isset($vcnnode['node_save_only_for_selected_industry']) && is_array($vcnnode['node_save_only_for_selected_industry'])
				&& (count($vcnnode['node_save_only_for_selected_industry']) > 0) && !in_array($industry, $vcnnode['node_save_only_for_selected_industry'])) {
			error_log("\n ---- Skipping Node : " .$node_info."\n\n", 3, ini_get('error_log'));
			drupal_set_message(t('Skipping Node : ' .$node_info), 'warning');
			continue; // skip to save this node for this industry and continue with next item in loop.
		}
		
		// Check for unique node entry by node alias		
		$is_exist = db_query(" SELECT COUNT(pid) FROM {url_alias} WHERE alias = :alias ", array(':alias' => $vcnnode['node_path_alias']))
					->fetchField();			
		
		if ($is_exist == 0) {			
			
			//Validate node type, text format
			if(!in_array($vcnnode['node_type'], array("page", "vcn_text", "osppage")) || !in_array($vcnnode['node_text_format'], array("vcn_text", "filtered_html", "full_html", "plain_text", "php_code"))) {
				error_log("\n ---- Invalid 'node_type' or 'node_text_format' for node : " .$node_info."\n", 3, ini_get('error_log')); //print "Invalid 'node_type' or 'node_text_format' for node : " .$node_info. "<br/>" . PHP_EOL;
				drupal_set_message(t("Invalid 'node_type' or 'node_text_format' for node : " .$node_info), 'error');
			}else {
				 
				$node_body = ""; // Get node body from text file ( check industry folder, if file not exist check vcn folder for same file.
				$filename_industry = "..".vcn_drupal7_base_path().drupal_get_path('module', 'vcncron')."/vcncroncontents/".$industry_code."/".$vcnnode['node_body'].".txt";
				$filename_vcn = "..".vcn_drupal7_base_path().drupal_get_path('module', 'vcncron')."/vcncroncontents/default/".$vcnnode['node_body'].".txt";
				
				if(file_exists($filename_industry)) {
					$node_body = file_get_contents($filename_industry);
				}else if(file_exists($filename_vcn)) {
					$node_body = file_get_contents($filename_vcn);
				}else {					
					error_log("\n ---- 'node_body' file for node : " .$node_info. " not available. \n", 3, ini_get('error_log')); //print "'node_body' file for node : " .$node_info. " not available.<br/>" . PHP_EOL;
					drupal_set_message(t("'node_body' file for node : " .$node_info. " not available."), 'warning');
				}//$node_body = htmlentities($node_body); //var_dump($node_body);				

				$i++;
				error_log($i.": ADDING Node: " .$node_info."\n", 3, ini_get('error_log')); //print "ADDING Node: " .$node_info. "<br/>" . PHP_EOL;				
				try {	
				
					$newnode = new StdClass();
					$newnode->type = $vcnnode['node_type'];
					$newnode->title = utf8_encode($vcnnode['node_title']);
					$newnode->uid = 1; // NOTE: admin user should always have uid=1 on this system
					$newnode->status = 1; // published
					$newnode->language = 'und';
					$newnode->body['und'][0]['value'] = utf8_encode($node_body);
					$newnode->body['und'][0]['format'] = $vcnnode['node_text_format'];		
					$newnode->path['pathauto'] = FALSE;
					$newnode->path['alias'] = $vcnnode['node_path_alias'];
					
					if($vcnnode['node_search_industry_filter'] != "") {
						$newnode->field_industry_search_filter['und'][0]['value'] = $vcnnode['node_search_industry_filter']; // content type specific field ["page", "vcn_text", "osppage"]
					}
					if($vcnnode['node_type'] == "vcn_text" && $vcnnode['node_target_path'] != "") {
						$newnode->field_target_path['und'][0]['value'] = $vcnnode['node_target_path']; // content type specific field ["vcn_text"]	
					}
					// add new node to drupal
					node_save($newnode);
				
				} catch (Exception $e) {
			      	error_log("\n ---- ".$e->getMessage()."\n\n", 3, ini_get('error_log'));  
			      	drupal_set_message(t("Error occurred for node : " .$e->getMessage()), 'error');
			      	//watchdog('my_error', $e->getMessage());
			    }				
				 
				error_log(" Node: " .$node_info. " Added. \n", 3, ini_get('error_log'));  //print "Node: " .$node_info. " Added.<br/>" . PHP_EOL;
				
			}
		}else {	
			error_log(" **** Node: " .$node_info. " Already Exists. \n", 3, ini_get('error_log')); 
		}
	}
	error_log("#######VCN cron : END of add drupal content nodes. ".date("M-d-Y H:i:s")." \n", 3, ini_get('error_log'));
}
