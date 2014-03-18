<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php 
/* Return vcn node list array */
function vcn_get_import_nodes_list() {
	/*
	 * $nodelist[] = array(
		"node_type" => "", // Drupal Node type : script only expects basic page and vcn text type ["page", "vcn_text", "osppage"]
						   // Code will reject any other content type. Update vcncron.inc (vcn_import_nodes) to allow new content type.	
		
		"node_title" => "", // Node Title
		
		"node_body" => "", // Node Body text "filename" without extension : looks for "filename".txt at industry specific and vcn folder.
						   // if does not exist, will add blank body to node.
		
		"node_text_format" => "", // Node text format : body[und][0][format] 
								  // Must be one of the ["vcn_text", "filtered_html", "full_html", "plain_text", "php_code"]
								  // vcn_text : to avoide any drupal filter on node body html. (Full html adds line break.)
		
		"node_path_alias" => "", // Node path alias : must be unique, will ignore to add if finds duplicate
		
		"node_search_industry_filter" => "", // Node industry Filter for search [Extra field]
											 //	Leave blank or enter 0 to display for all industry. 
											 // Enter -1 to hide from all industry. 
											 // For specific industry, enter industry id in which node should be available to result. 
											 // For multiple industry, separate industry id with ",(comma) ".
		
		"node_target_path" => "", // Node target path [Extra field] [Only in use for VCN text type]
		
		"node_save_only_for_selected_industry" => "", // [array of selected industry id] [Only in use for basic page type node]
													  // [leave blank if not sure what to assign] [ blank array() Will not filter for any industry ] 
		);
	 *					
	 */
	$nodelist = array();	
	//$nodelist[] = array("node_type" => "", "node_title" => "", "node_body" => "", "node_text_format" => "", "node_path_alias" => "", "node_search_industry_filter" => "", "node_target_path" => "" ,"node_save_only_for_selected_industry" => "");
		
	$nodelist[] = array("node_type" => "page", "node_title" => "About Us", "node_body" => "about-us", "node_text_format" => "php_code", "node_path_alias" => "about-us", "node_search_industry_filter" => "", "node_target_path" => "" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "page", "node_title" => "ACCUPLACER", "node_body" => "accuplacer", "node_text_format" => "php_code", "node_path_alias" => "accuplacer", "node_search_industry_filter" => "", "node_target_path" => "" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "page", "node_title" => "Ace your Interviews", "node_body" => "findwork-interviews", "node_text_format" => "php_code", "node_path_alias" => "findwork/interviews", "node_search_industry_filter" => "", "node_target_path" => "" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "page", "node_title" => "ACT Test Description", "node_body" => "actinformation", "node_text_format" => "php_code", "node_path_alias" => "actinformation", "node_search_industry_filter" => "", "node_target_path" => "" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "page", "node_title" => "Apprenticeship Training Healthcare", "node_body" => "get-qualified-resources-apprenticeship-training", "node_text_format" => "php_code", "node_path_alias" => "get-qualified/resources/apprenticeship-training", "node_search_industry_filter" => "1", "node_target_path" => "" ,"node_save_only_for_selected_industry" => array(1));
	
	$nodelist[] = array("node_type" => "page", "node_title" => "Build a Resume", "node_body" => "findwork-resume", "node_text_format" => "php_code", "node_path_alias" => "findwork/resume", "node_search_industry_filter" => "", "node_target_path" => "" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "page", "node_title" => "Career Gateway", "node_body" => "jobseekers", "node_text_format" => "php_code", "node_path_alias" => "jobseekers", "node_search_industry_filter" => "1", "node_target_path" => "" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "page", "node_title" => "Career Tools", "node_body" => "career-tools", "node_text_format" => "php_code", "node_path_alias" => "career-tools", "node_search_industry_filter" => "", "node_target_path" => "" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "page", "node_title" => "COMPASS Test Description", "node_body" => "compass-description", "node_text_format" => "php_code", "node_path_alias" => "compass-description", "node_search_industry_filter" => "", "node_target_path" => "" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "page", "node_title" => "Financial Aid", "node_body" => "get-qualified-financialaid", "node_text_format" => "php_code", "node_path_alias" => "get-qualified/financialaid", "node_search_industry_filter" => "", "node_target_path" => "" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "page", "node_title" => "Find more Jobs Information", "node_body" => "findwork-jobsinfo", "node_text_format" => "php_code", "node_path_alias" => "findwork/jobsinfo", "node_search_industry_filter" => "", "node_target_path" => "" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "page", "node_title" => "Healthcare Training Programs", "node_body" => "get-qualified-resources-job-corps-training-programs", "node_text_format" => "php_code", "node_path_alias" => "get-qualified/resources/job-corps/training-programs", "node_search_industry_filter" => "1", "node_target_path" => "" ,"node_save_only_for_selected_industry" => array(1));
	
	$nodelist[] = array("node_type" => "page", "node_title" => "Job Corps", "node_body" => "get-qualified-resources-job-corps", "node_text_format" => "php_code", "node_path_alias" => "get-qualified/resources/job-corps", "node_search_industry_filter" => "1", "node_target_path" => "" ,"node_save_only_for_selected_industry" => array(1));
	
	$nodelist[] = array("node_type" => "page", "node_title" => "Job Corps Centers", "node_body" => "get-qualified-resources-job-corps-centers", "node_text_format" => "php_code", "node_path_alias" => "get-qualified/resources/job-corps/centers", "node_search_industry_filter" => "1", "node_target_path" => "" ,"node_save_only_for_selected_industry" => array(1));
	
	$nodelist[] = array("node_type" => "page", "node_title" => "National Health Service Corps", "node_body" => "get-qualified-resources-health-service-corps", "node_text_format" => "php_code", "node_path_alias" => "get-qualified/resources/health-service-corps", "node_search_industry_filter" => "1", "node_target_path" => "" ,"node_save_only_for_selected_industry" => array(1));
	
	$nodelist[] = array("node_type" => "page", "node_title" => "Network Online", "node_body" => "findwork-network_online", "node_text_format" => "php_code", "node_path_alias" => "findwork/network_online", "node_search_industry_filter" => "", "node_target_path" => "" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "page", "node_title" => "New to VCN?", "node_body" => "new-to-vcn", "node_text_format" => "php_code", "node_path_alias" => "new-to-vcn", "node_search_industry_filter" => "", "node_target_path" => "" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "page", "node_title" => "PLA Resources", "node_body" => "pla-resources", "node_text_format" => "php_code", "node_path_alias" => "pla/resources", "node_search_industry_filter" => "", "node_target_path" => "" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "page", "node_title" => "Plan your Job Search", "node_body" => "findwork-job-search-plan", "node_text_format" => "php_code", "node_path_alias" => "findwork/job_search_plan", "node_search_industry_filter" => "", "node_target_path" => "" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "page", "node_title" => "Post Jobs", "node_body" => "findwork-findjobs", "node_text_format" => "php_code", "node_path_alias" => "findwork/findjobs", "node_search_industry_filter" => "", "node_target_path" => "" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "page", "node_title" => "Privacy Policy", "node_body" => "privacy-policy", "node_text_format" => "php_code", "node_path_alias" => "privacy-policy", "node_search_industry_filter" => "", "node_target_path" => "" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "page", "node_title" => "Provider Portal FAQs", "node_body" => "provider-faq", "node_text_format" => "full_html", "node_path_alias" => "provider/faq", "node_search_industry_filter" => "", "node_target_path" => "" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "page", "node_title" => "Research Employers", "node_body" => "findwork-research-employers", "node_text_format" => "php_code", "node_path_alias" => "findwork/research_employers", "node_search_industry_filter" => "", "node_target_path" => "" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "page", "node_title" => "Scholastic Aptitude Test", "node_body" => "sat-information", "node_text_format" => "php_code", "node_path_alias" => "sat-information", "node_search_industry_filter" => "", "node_target_path" => "" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "page", "node_title" => "Tool Kit", "node_body" => "tool-kit", "node_text_format" => "php_code", "node_path_alias" => "tool-kit", "node_search_industry_filter" => "1", "node_target_path" => "" ,"node_save_only_for_selected_industry" => array(1));
	
	$nodelist[] = array("node_type" => "page", "node_title" => "Use Your Network", "node_body" => "findwork-use-network", "node_text_format" => "php_code", "node_path_alias" => "findwork/use_network", "node_search_industry_filter" => "", "node_target_path" => "" ,"node_save_only_for_selected_industry" => "");
	
	// Why-industry, entry for each industry with different title. "node_save_only_for_selected_industry" param value will save node in db as per industry.
	$nodelist[] = array("node_type" => "page", "node_title" => "Why Green", "node_body" => "why-industry", "node_text_format" => "full_html", "node_path_alias" => "why-industry", "node_search_industry_filter" => "3", "node_target_path" => "" ,"node_save_only_for_selected_industry" => array(3));
	$nodelist[] = array("node_type" => "page", "node_title" => "Why Transit", "node_body" => "why-industry", "node_text_format" => "full_html", "node_path_alias" => "why-industry", "node_search_industry_filter" => "4", "node_target_path" => "" ,"node_save_only_for_selected_industry" => array(4));
	$nodelist[] = array("node_type" => "page", "node_title" => "Why Focus on 50+", "node_body" => "why-industry", "node_text_format" => "php_code", "node_path_alias" => "why-industry", "node_search_industry_filter" => "5", "node_target_path" => "" ,"node_save_only_for_selected_industry" => array(5));

	$nodelist[] = array("node_type" => "vcn_text", "node_title" => "Site Map", "node_body" => "site-map-top", "node_text_format" => "php_code", "node_path_alias" => "site-map-top", "node_search_industry_filter" => "", "node_target_path" => "site-map" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "vcn_text", "node_title" => "Site Map", "node_body" => "site-map-bottom", "node_text_format" => "php_code", "node_path_alias" => "site-map-bottom", "node_search_industry_filter" => "", "node_target_path" => "site-map" ,"node_save_only_for_selected_industry" => "");
		
	$nodelist[] = array("node_type" => "vcn_text", "node_title" => "Career Pathway by onetcode text", "node_body" => "careerladder-byonet-text", "node_text_format" => "vcn_text", "node_path_alias" => "careerladder-byonet-text", "node_search_industry_filter" => "-1", "node_target_path" => "" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "vcn_text", "node_title" => "Choose A Career", "node_body" => "explorecareers-text", "node_text_format" => "php_code", "node_path_alias" => "explorecareers-text", "node_search_industry_filter" => "", "node_target_path" => "explorecareers" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "vcn_text", "node_title" => "Earn College Credit for Prior Learning: College Courses", "node_body" => "college-credit-text", "node_text_format" => "php_code", "node_path_alias" => "college-credit-text", "node_search_industry_filter" => "", "node_target_path" => "pla/college-courses" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "vcn_text", "node_title" => "Earn College Credit for Prior Learning: Getting Started", "node_body" => "earn-college-credits-get-started", "node_text_format" => "php_code", "node_path_alias" => "earn-college-credits-get-started", "node_search_industry_filter" => "", "node_target_path" => "pla/getting-started" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "vcn_text", "node_title" => "Earn College Credit for Prior Learning: Military Credit", "node_body" => "military-credit-text", "node_text_format" => "vcn_text", "node_path_alias" => "military-credit-text", "node_search_industry_filter" => "", "node_target_path" => "pla/military-credit" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "vcn_text", "node_title" => "Earn College Credit for Prior Learning: Military Training", "node_body" => "military-credit-resources", "node_text_format" => "php_code", "node_path_alias" => "military-credit-resources", "node_search_industry_filter" => "", "node_target_path" => "pla/military-credit" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "vcn_text", "node_title" => "Earn College Credit for Prior Learning: Review Learning Inventory", "node_body" => "earn-college-credits-my-learning-inventory", "node_text_format" => "php_code", "node_path_alias" => "earn-college-credits-my-learning-inventory", "node_search_industry_filter" => "", "node_target_path" => "pla/my-learning-inventory" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "vcn_text", "node_title" => "Find a Job - Introduction", "node_body" => "find-job-intro", "node_text_format" => "php_code", "node_path_alias" => "find-job-intro", "node_search_industry_filter" => "", "node_target_path" => "findwork" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "vcn_text", "node_title" => "Find a Job - Search for Jobs", "node_body" => "find-job-search-jobs", "node_text_format" => "php_code", "node_path_alias" => "find-job-search-jobs", "node_search_industry_filter" => "", "node_target_path" => "findwork" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "vcn_text", "node_title" => "Find a Job- Before you begin", "node_body" => "find-job-before-you-begin", "node_text_format" => "php_code", "node_path_alias" => "find-job-before-you-begin", "node_search_industry_filter" => "", "node_target_path" => "findwork" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "vcn_text", "node_title" => "Free Online Courses", "node_body" => "take-a-course-online-free-online-courses", "node_text_format" => "vcn_text", "node_path_alias" => "take-a-course-online-free-online-courses", "node_search_industry_filter" => "", "node_target_path" => "online-courses/take-online" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "vcn_text", "node_title" => "Frequently Asked Questions", "node_body" => "help-text", "node_text_format" => "php_code", "node_path_alias" => "help-text", "node_search_industry_filter" => "", "node_target_path" => "help" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "vcn_text", "node_title" => "Healthcare Information Technology (HIT) Instructional Program", "node_body" => "online-courses-hit-top", "node_text_format" => "php_code", "node_path_alias" => "online-courses-hit-top", "node_search_industry_filter" => "1", "node_target_path" => "online-courses/hit" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "vcn_text", "node_title" => "Healthcare Information Technology: Courses of Study", "node_body" => "online-courses-hit-leftside", "node_text_format" => "vcn_text", "node_path_alias" => "online-courses-hit-leftside", "node_search_industry_filter" => "1", "node_target_path" => "online-courses/hit" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "vcn_text", "node_title" => "Healthcare Information Technology: The Exam Blueprint", "node_body" => "online-courses-hit-rightside", "node_text_format" => "php_code", "node_path_alias" => "online-courses-hit-rightside", "node_search_industry_filter" => "1", "node_target_path" => "online-courses/hit" ,"node_save_only_for_selected_industry" => "");
		
	$nodelist[] = array("node_type" => "vcn_text", "node_title" => "Industry Home", "node_body" => "industry-home", "node_text_format" => "vcn_text", "node_path_alias" => "industry-home", "node_search_industry_filter" => "-1", "node_target_path" => "/" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "vcn_text", "node_title" => "Job search results text", "node_body" => "job-search-results-text", "node_text_format" => "php_code", "node_path_alias" => "job-search-results-text", "node_search_industry_filter" => "-1", "node_target_path" => "" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "vcn_text", "node_title" => "Match Your Education hints Text", "node_body" => "match-education-hint", "node_text_format" => "php_code", "node_path_alias" => "match-education-hint", "node_search_industry_filter" => "-1", "node_target_path" => "" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "vcn_text", "node_title" => "NTER's Online Courses", "node_body" => "take-a-course-online-nter-online-courses", "node_text_format" => "php_code", "node_path_alias" => "take-a-course-online-nter-online-courses", "node_search_industry_filter" => "", "node_target_path" => "online-courses/take-online" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "vcn_text", "node_title" => "Resources", "node_body" => "resources-text", "node_text_format" => "php_code", "node_path_alias" => "resources-text", "node_search_industry_filter" => "", "node_target_path" => "resources" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "vcn_text", "node_title" => "Take a Course Online", "node_body" => "take-a-course-online", "node_text_format" => "php_code", "node_path_alias" => "take-a-course-online", "node_search_industry_filter" => "", "node_target_path" => "online-courses/take-online" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "vcn_text", "node_title" => "VCN Learning Exchange", "node_body" => "take-a-course-online-learning-exchange", "node_text_format" => "php_code", "node_path_alias" => "take-a-course-online-learning-exchange", "node_search_industry_filter" => "", "node_target_path" => "online-courses/take-online" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "vcn_text", "node_title" => "VCN Learning Exchange Toolbox", "node_body" => "learning-exchange-toolbox", "node_text_format" => "php_code", "node_path_alias" => "learning-exchange-toolbox", "node_search_industry_filter" => "-1", "node_target_path" => "" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "vcn_text", "node_title" => "Why Industry Summary", "node_body" => "why-industry-summary", "node_text_format" => "vcn_text", "node_path_alias" => "why-industry-summary", "node_search_industry_filter" => "-1", "node_target_path" => "/" ,"node_save_only_for_selected_industry" => "");

	$nodelist[] = array("node_type" => "vcn_text", "node_title" => "How to Get Qualified", "node_body" => "how-to-get-qualified-vocabulary", "node_text_format" => "php_code", "node_path_alias" => "how-to-get-qualified-vocabulary", "node_search_industry_filter" => "", "node_target_path" => "get-qualified" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "vcn_text", "node_title" => "Get Started Intro", "node_body" => "get-started-intro", "node_text_format" => "php_code", "node_path_alias" => "get-started-intro", "node_search_industry_filter" => "-1", "node_target_path" => "get-started" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "vcn_text", "node_title" => "Get Started Sidebar", "node_body" => "get-started-sidebar", "node_text_format" => "php_code", "node_path_alias" => "get-started-sidebar", "node_search_industry_filter" => "-1", "node_target_path" => "get-started" ,"node_save_only_for_selected_industry" => "");
	
	$nodelist[] = array("node_type" => "vcn_text", "node_title" => "Find a Job tools - Employer help", "node_body" => "find-job-tools-employer-help", "node_text_format" => "php_code", "node_path_alias" => "find-job-tools-employer-help", "node_search_industry_filter" => "-1", "node_target_path" => "" ,"node_save_only_for_selected_industry" => "");
	
	// note: Osp page // should be there for only healthcare?
	$nodelist[] = array("node_type" => "osppage", "node_title" => "Welcome", "node_body" => "osp-welcome", "node_text_format" => "php_code", "node_path_alias" => "osp/welcome", "node_search_industry_filter" => "", "node_target_path" => "" ,"node_save_only_for_selected_industry" => array(1));
	
	$nodelist[] = array("node_type" => "osppage", "node_title" => "Contribute", "node_body" => "osp-contribute", "node_text_format" => "php_code", "node_path_alias" => "osp/contribute", "node_search_industry_filter" => "", "node_target_path" => "" ,"node_save_only_for_selected_industry" => array(1));
	
	$nodelist[] = array("node_type" => "osppage", "node_title" => "Download", "node_body" => "osp-download", "node_text_format" => "php_code", "node_path_alias" => "osp/download", "node_search_industry_filter" => "", "node_target_path" => "" ,"node_save_only_for_selected_industry" => array(1));
	
	/* // test content
	 $nodelist[] = array("node_type" => "vcn_text",
	 		"node_title" => "vcn text test node title",
	 		"node_body" => "vcn_text_node_body",
	 		"node_text_format" => "php_code",
	 		"node_path_alias" => "vcn-text-node-body-alias",
	 		"node_search_industry_filter" => "-1",
	 		"node_target_path" => "careergrid",
	 		"node_save_only_for_selected_industry" => array());
	
	$nodelist[] = array("node_type" => "page",
			"node_title" => "basic page test node title",
			"node_body" => "basic_page_node_body",
			"node_text_format" => "vcn_text",
			"node_path_alias" => "basic-page-node-body-alias",
			"node_search_industry_filter" => "0",
			"node_target_path" => "",
			"node_save_only_for_selected_industry" => array(2));*/
	
	return $nodelist;
}
?>