<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<h1 class="title">Match Your Interests to Careers</h1>

<div class="profiler-details-outer-container">
  <div class="profiler-details-inner-container-left">
    <h1 class="title-top"><?php print $career_title; ?></h1>
    
    <strong>Other Names:</strong><br/>
    <p><?php print $other_names; ?></p><br/>
    
    <strong>Detailed Description:</strong><br/>
    <p><?php print $detailed_description; ?></p><br/>
    
    <strong>Physical/Medical/Health Requirements:</strong><br/>
    <p><?php print $physical_health_requirements; ?></p><br/>
        
    <script type="text/javascript">
      google.load('visualization', '1.0', {'packages':['corechart']});
  
      google.setOnLoadCallback(function() { 
        drawWageChart('wage-chart-hourly-div', 
	    	            	'<?php print round($hourly_wages_pct90_for_national); ?>', '<?php print round($hourly_wages_median_for_national); ?>', '<?php print round($hourly_wages_pct10_for_national); ?>');

        drawWageChart('wage-chart-annual-div', 
	    	            	'<?php print round($annual_salary_pct90_for_national); ?>', '<?php print round($annual_salary_median_for_national); ?>', '<?php print round($annual_salary_pct10_for_national); ?>');
      });
    </script>
         
    <strong>Wages:</strong><br/><br/>  	
    <table id="wage-table" summary="this table displays national and state wage information for the selected occupation">
      <thead>
				<tr>
					<th class="text noresize" id="location-header" rowspan="2" scope="col">Location</th>
					<th class="text noresize" id="pay-period" rowspan="2" scope="col">Pay<br />Period</th>
					<th class="text noresize" colspan="5" id="year" scope="colgroup"><?php print $period_year; ?></th>
				</tr>
				<tr>
					<th class="text noresize percent-median" scope="colgroup">10%</th>
					<th class="text noresize percent-median" scope="colgroup">25%</th>
					<th class="text noresize percent-median" scope="colgroup">Median</th>
					<th class="text noresize percent-median" scope="colgroup">75%</th>
					<th class="text noresize percent-median" scope="colgroup">90%</th>
				</tr>
			</thead>
			<tbody>
				<tr>
				  <td class="text noresize location" rowspan="2" scope="row">United States</td>
				  <td class="text noresize hourly-rates-header">Hourly</td>
				  <td class="text noresize hourly-rates"><?php print ($hourly_wages_pct10_for_national) ? vcn_generic_number_formatter($hourly_wages_pct10_for_national) : 'NA'; ?></td>
			    <td class="text noresize hourly-rates"><?php print ($hourly_wages_pct25_for_national) ? vcn_generic_number_formatter($hourly_wages_pct25_for_national) : 'NA'; ?></td>
					<td class="text noresize hourly-rates"><?php print ($hourly_wages_median_for_national) ? vcn_generic_number_formatter($hourly_wages_median_for_national) : 'NA'; ?></td>
					<td class="text noresize hourly-rates"><?php print ($hourly_wages_pct75_for_national) ? vcn_generic_number_formatter($hourly_wages_pct75_for_national) : 'NA'; ?></td>
					<td class="text noresize hourly-rates"><?php print ($hourly_wages_pct90_for_national) ? vcn_generic_number_formatter($hourly_wages_pct90_for_national) : 'NA'; ?></td>
				</tr>
				<tr>
					<td class="text noresize yearly-salary-header">Yearly</td>
					<td class="text noresize yearly-salary"><?php print ($annual_salary_pct10_for_national) ? vcn_generic_number_formatter($annual_salary_pct10_for_national) : 'NA'; ?></td>
					<td class="text noresize yearly-salary"><?php print ($annual_salary_pct25_for_national) ? vcn_generic_number_formatter($annual_salary_pct25_for_national) : 'NA'; ?></td>
					<td class="text noresize yearly-salary"><?php print ($annual_salary_median_for_national) ? vcn_generic_number_formatter($annual_salary_median_for_national) : 'NA'; ?></td>
					<td class="text noresize yearly-salary"><?php print ($annual_salary_pct75_for_national) ? vcn_generic_number_formatter($annual_salary_pct75_for_national) : 'NA'; ?></td>
					<td class="text noresize yearly-salary"><?php print ($annual_salary_pct90_for_national) ? vcn_generic_number_formatter($annual_salary_pct90_for_national) : 'NA'; ?></td>
				</tr>
			</tbody>
		</table>
				
		<div id="wage-charts">
		  <strong>Hourly Wage:</strong><br/><br/>
			<div id="wage-chart-hourly-div" class="wage-chart"></div>
			<p/>
			<strong>Yearly Wage:</strong><br/>
			<div id="wage-chart-annual-div" class="wage-chart"></div>
		</div>
		
		<div class="wage-bracket-explaination">
			<ul>
				<li>High is the wage at which 90% of workers earn less and 10% earn more.</li>
				<li>Median is the wage at which 50% of workers earn less and 50% earn more.</li>
				<li>Low is the wage at which 10% of workers earn less and 90% earn more.</li>
			</ul>
		</div>
						
		<strong>Trends:</strong><br/><br/>
		<table id="trends-table" summary="this table displays national and state trends information for the selected occupation">
			<thead>
				<tr>
					<th class="text noresize" id="location-header" rowspan="2" scope="col">Location</th>
					<th class="text noresize" id="employment-header" align="center" scope="colgroup" colspan="2">Employment</th>
					<th class="text noresize" id="percent-change-header" rowspan="2" scope="col">Percent Change</th>
					<th class="text noresize" id="job-openings-header" rowspan="2" scope="col">Job Openings</th>
				</tr>
				<tr bgcolor="#CCCCCC">
					<th id="year-from-header" scope="col">2008</th>
					<th id="year-to-header" scope="col">2018</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="text noresize location">United States</td>
					<td class="text noresize year-from-numbers"><?php print ($num_present_jobs_for_national) ? vcn_generic_number_formatter($num_present_jobs_for_national, 0, '') : 'NA'; ?></td>
					<td class="text noresize year-to-numbers"><?php print ($num_projected_jobs_for_national) ? vcn_generic_number_formatter($num_projected_jobs_for_national, 0, '') : 'NA'; ?></td>
					<td class="text noresize percent-change"><?php print ($percent_job_growth_value_for_national) ? $percent_job_growth_value_for_national.'%' : 'NA'; ?></td>
					<td class="text noresize job-openings"><?php print ($num_job_openings_for_national) ? vcn_generic_number_formatter($num_job_openings_for_national, 0, '') : 'NA'; ?></td>
				</tr>
			</tbody>
		</table>       
             
    <strong>A Day In The Life:</strong>
		<p><?php print $day_in_life_description; ?></p>
		<?php if (isset($day_in_life_url)): ?>
			<p>To see more, <?php vcn_build_link_window_opener($day_in_life_url, 'Click here'); ?></p>
		<?php endif; ?>
		<?php if(isset($interview_description) || isset($additional_interview_array)): ?>
			<p><span class="lineunder">Interview</span></p>
			<?php if(isset($interview_url)): ?>
				<p><?php print $interview_description; ?><?php vcn_build_link_window_opener($interview_url, 'Click here'); ?></p>
			<?php endif; ?>
			<?php if(isset($additional_interview_array)): ?>
				<?php foreach($additional_interview_array as $additional_interview): ?>
					<p><?php print (string)$additional_interview['interviewurldescription'][0]; ?><?php if($additional_interview['interviewurlflag'][0] == 1){ vcn_build_link_window_opener((string)$additional_interview['interviewurl'][0], 'Click here'); }?></p>
				<?php endforeach; ?>
			<?php endif; ?>
		<?php endif; ?>
						
    <script type="text/javascript">
      google.load('visualization', '1.0', {'packages':['corechart']});
  
      google.setOnLoadCallback(function() { 
        drawCommonEducationLevelsChart('common-education-chart-div', new Array("<?php print vcn_get_shortened_training_name($first_highest_education_name); ?>", "<?php print $first_highest_education_value; ?>", "<?php print vcn_get_shortened_training_name($second_highest_education_name); ?>", "<?php print $second_highest_education_value; ?>", "<?php print vcn_get_shortened_training_name($third_highest_education_name); ?>", "<?php print $third_highest_education_value; ?>"));
      });
    </script>
          
    <strong>Most common education levels:</strong><br/>
    <div id="education-and-training">
      <div id="common-education-chart">
        <div id="common-education-chart-div" class="common-education-chart"></div>
      </div>
						
			<p>** This graph is based on U.S. Bureau of Labor Statistics (BLS) data and offers a snapshot (based on a statistical sample) of the actual education and training levels 
						of those persons who are currently working in this career. It does not necessarily reflect the education or training that an employer may require of a new hire. **</p>
			<div id="academic-requirement-regular-text">
			  <?php print $academic_requirements; ?>
			</div>
    </div>
					
    <strong>Skills:</strong><br/>
    <p><?php print $skills_list; ?></p><br/>
    
    <strong>Tools:</strong><br/>
    <p><?php print $tools_list; ?></p><br/>
    
    <strong>Technology:</strong><br/>
    <p><?php print $technology_list; ?></p><br/>
    
    <span class="lineunder">Resources related to the Career</span>
    <?php 
    $resourcesExist = false;
    if(isset($resources) && count($resources) > 0): 
      $resourcesExist = true;
    ?>
      <?php foreach($resources as $key => $value): ?>
        <div class="resources-data">
          <strong><?php print $key; ?>:</strong>
          <?php foreach($value as $v): ?>
            <p><?php vcn_build_link_window_opener($v['resourcelink'], $v['resourcename'], TRUE, TRUE, '', 'extlink', TRUE); ?> </p>
          <?php endforeach; ?>
				</div>
      <?php endforeach;?>
    <?php endif; ?>
		
		<?php 
    if(isset($fincancialaid_resources) && count($fincancialaid_resources) > 0): 
      $resourcesExist = true;
    ?>
      <span class="lineunder">Financial Aid</span>
      <div id="financial-resources-data">
        <?php foreach($fincancialaid_resources as $key => $value): ?>
          <p><?php vcn_build_link_window_opener($value['financialaidurl'], $value['financialaidname'], TRUE, TRUE, '', 'extlink', TRUE); ?></p>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
	  <?php if (!$resourcesExist) : ?>
      No resources available
    <?php endif; ?>		    
  </div>
  <div class="profiler-details-inner-container-right">
    <img src="<?php print $image_base_path . 'career_images/' . $image_name; ?>" alt="Career image" />
  </div>
  <div class="allclear"></div>
</div>

<?php if (vcn_external_client_calling_interest_profiler()) : ?>
  <br/><br/>
  <button class="vcn-button <?php print $alt_color_class; ?>" onclick="history.back();">Go Back</button>
<?php endif; ?>
    
<br/>

<div class="profiler-onet-logo">
  <a href="http://www.onetonline.org" target="_blank"><img src="<?php print vcn_image_path(); ?>site_logo_images/onet.png" height="60" alt="ONet logo" /></a>
</div>

