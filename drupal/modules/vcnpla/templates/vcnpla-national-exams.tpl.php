<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<h1 class="title">Earn College Credit for Prior Learning</h1>
<div id="vcn-pla">
	<div class="vcn-left-sidebar">
		<?php print $vcn_tabs_header; ?>
		<div id="vcn-tabs-blueborder">
			<div id="vcn-pla-national-exams-main-content">
		    <span class="tab-header">National Exams</span>
		    
				<p>Many colleges and universities recognize and accept national examinations to meet college course requirements. 
				These exams include <?php vcn_build_link_window_opener('http://clep.collegeboard.org/students','CLEP'); ?>, 
				<?php vcn_build_link_window_opener('http://www.getcollegecredit.com/','DSST'); ?> (also known as DANTES), 
				<?php vcn_build_link_window_opener('http://www.excelsior.edu/ecapps/exams/creditByExam.jsf?gw=1','Excelsior'); ?>, and others. 
				Some colleges also accept Advanced Placement (AP) exams (normally taken in high school) for college credit.</p>
				
		    <p style="margin-top: 20px;">To see if any national exams that you have taken have ACE credit recommendations, look up the information below.</p>
				
		    <div class="pla-form-box">
		      <?php print $national_exams_organization; ?>
		      <?php print $national_exams_search_box; ?>
		      <?php print $national_exams_submit; ?>
		      <br/><br/>
		    </div>
		    
		    <p>Go to <a href="<?php print $vcn_drupal7_base_path; ?>pla/my-learning-inventory">Review Inventory</a> tab to see your saved exams.</p>
		    <?php if ($display_result): ?>   
					<div class="vcn-pla-search-result-title"><br/>Search Results: </div>
					<div id="vcnpla-national-exams-div"">
						<table id="vcnpla-national-exams-table" class="dttable">
							<thead>
								<tr><th class="dtheader sorting">Course Name</th><th class="dtheader">Action</th></tr>
							</thead>
							<tbody>
								<tr><td colspan="3" class="dataTables_empty">Loading data from server</td></tr>
							</tbody>
						</table>
					</div>
				<?php endif; ?>
		    <br/>
				<p>Each college requires official transcripts for examination results. You must meet the college's requirement for a passing score in order to transfer it into a degree program. For more information on ordering a transcript for a national exam, <?php vcn_build_link_window_opener('http://www.acenet.edu/higher-education/topics/Pages/Transcript-Services.aspx','click here'); ?>.</p>
				<p><span class="strong">Hint:</span> Remember that each college determines how your prior learning might apply to a specific degree program.</p>
			</div>
		</div>
		<?php print $national_exams_children; ?>
	</div>
	<div class="vcn-right-sidebar">
		<?php echo $pla_sidebar;?>
	</div>
</div>
