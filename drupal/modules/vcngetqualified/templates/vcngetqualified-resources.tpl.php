<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<div id="vcn-getqualified-resources-top-button">
	<a href="<?php print vcn_drupal7_base_path();?>get-qualified/programs" class="vcn-button">Back to<br/>Training Programs</a>
</div>

<p id="vcn-getqualified-resources-intro" class="vcn-getqualified-resources-primary-header allclear">The following explains various aspects of choosing a college and applying for admission.</p>
<div class="vcn-getqualified-resources-prerequisite-tests-inner-indent-div">
	<div id="vcn-getqualified-resources-college-search">
		<p class="vcn-getqualified-resources-primary-header">How to choose a College</p>
		<div class="vcn-getqualified-resources-prerequisite-tests-inner-indent-div">
			Find out what various colleges offer and learn how to select the college most suitable for you. Click on one or more of the resources below.
			<ul>
				<li><?php vcn_build_link_window_opener('http://collegesearch.collegeboard.com/search/index.jsp', 'College Search'); ?>
				<li><?php vcn_build_link_window_opener('http://www.collegebound.net/college', 'All About Four-Year Colleges and Universities'); ?>
			</ul>
		</div><!-- vcn-getqualified-resources-prerequisite-tests-inner-indent-div -->
	</div><!-- vcn-getqualified-resources-college-search -->
	
	<div id="vcn-getqualified-resources-apply-now">
		<p class="vcn-getqualified-resources-primary-header">How to Apply to College</p>
		<div class="vcn-getqualified-resources-prerequisite-tests-inner-indent-div">
			When you are ready, the following websites can help you complete your college application. Click on one or more of the resources below.
			<ul>
				<li><?php vcn_build_link_window_opener('http://bigfuture.collegeboard.org/get-in/applying-101/college-application-checklist', 'College Board'); ?>
				<li><?php vcn_build_link_window_opener('http://admissionpossible.com/Completing_Applications.html', 'Admission Possible'); ?>
				<li><?php vcn_build_link_window_opener('http://www.drexel.com/tools/transcript.aspx?process=alpha&letter=A', 'Where to get Transcripts'); ?>
			</ul>
		</div><!-- vcn-getqualified-resources-prerequisite-tests-inner-indent-div -->
	</div><!-- vcn-getqualified-resources-apply-now -->
</div><!-- vcn-getqualified-resources-prerequisite-tests-inner-indent-div -->

<div id="vcn-getqualified-resources-prerequisite-tests">
	<p class="vcn-getqualified-resources-primary-header">How to be Admitted to College</p>
	<div class="vcn-getqualified-resources-prerequisite-tests-inner-indent-div">
		<p class="vcn-getqualified-resources-primary-header">Minimum Education Requirement</p>
		<div class="vcn-getqualified-resources-prerequisite-tests-inner-indent-div">
			A High School diploma or GED is required for applying to a college. 
			If you do not have one, you may like to find a <a href="<?php print vcn_drupal7_base_path();?>online-courses/take-online?state=A">Virtual High School</a> to fulfill the requirement online.
		</div>
		<p class="vcn-getqualified-resources-primary-header">Prerequisite Tests for College Admission</p>
		<ul>
			<li>
				<div class="vcn-getqualified-resources-secondary-header">SAT</div>
				<div class="vcn-getqualified-resources-prerequisite-tests-inner-indent-div">
					The SAT is one of the most common college admission tests. It lets you show colleges what you know and how well you can apply that knowledge.
					<p><a href="<?php print $GLOBALS['vcn_config_default_base_path_drupal7']; ?>sat-information">See More</a></p>
				</div>
			</li>
			<li>
				<div class="vcn-getqualified-resources-secondary-header">ACT</div>
				<div class="vcn-getqualified-resources-prerequisite-tests-inner-indent-div">
					The ACT test is a national college admission and placement examination. It is accepted by all four-year colleges and universities in the United States.
					<p><a href="<?php print $GLOBALS['vcn_config_default_base_path_drupal7']; ?>actinformation">See More</a></p>
				</div>
			</li>
			<li>
				<div class="vcn-getqualified-resources-secondary-header">COMPASS</div>
				<div class="vcn-getqualified-resources-prerequisite-tests-inner-indent-div">
					COMPASS is an untimed, computerized "success planning" set of tests that helps your college to evaluate your skills, identify the levels of courses you are ready to enroll in, 
					and give you recommendations about additional resources and services you can use to succeed in the college and the program of your choice.
					<p><a href="<?php print $GLOBALS['vcn_config_default_base_path_drupal7']; ?>compass-description">See More</a></p>
				</div>
			</li>
			<li>
				<div class="vcn-getqualified-resources-secondary-header">ACCUPLACER</div>
				<div class="vcn-getqualified-resources-prerequisite-tests-inner-indent-div">
					The purpose of ACCUPLACER tests is to provide you with useful information about your academic skills in math, English, and reading.
					<p><a href="<?php print $GLOBALS['vcn_config_default_base_path_drupal7']; ?>accuplacer">See More</a></p>
				</div>
			</li>
		</ul>
	</div><!-- vcn-getqualified-resources-prerequisite-tests-inner-indent-div -->
</div><!-- vcn-getqualified-resources-prerequisite-tests -->

<div id="vcn-getqualified-resources-financial-aid">
	<p class="vcn-getqualified-resources-primary-header">How to get Financial Aid</p>
	<div class="vcn-getqualified-resources-prerequisite-tests-inner-indent-div">
		Find out how much college costs and how you can get help paying for your education and training.<br/>
		Need help paying for education and training? <a href="<?php print $GLOBALS['vcn_config_default_base_path_drupal7']; ?>get-qualified/financialaid">Learn about financial aid options</a>
	</div><!-- vcn-getqualified-resources-prerequisite-tests-inner-indent-div -->
</div><!-- vcn-getqualified-resources-financial-aid -->

<div id="vcn-getqualified-resources-bottom-button">
	<a href="<?php print vcn_drupal7_base_path();?>get-qualified/programs" class="vcn-button">Back to<br/>Training Programs</a>
</div>
