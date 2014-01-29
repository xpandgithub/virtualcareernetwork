<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<div class="vcncareerdetails-salary-outlook">	
	
	<fieldset>
		<legend><b>State and National Wages</b></legend>
		<div>
			<div>
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
							<td class="text noresize location" rowspan="2" scope="row">United<br/>States</td>
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
						<?php if ($zipcode) { ?>
						<tr>
						  	<td class="text noresize location" rowspan="2" scope="row"><?php print $state; ?></td>
						  	<td class="text noresize hourly-rates-header">Hourly</td>
						 	<td class="text noresize hourly-rates"><?php print ($hourly_wages_pct10_for_state) ? vcn_generic_number_formatter($hourly_wages_pct10_for_state) : 'NA'; ?></td>
							<td class="text noresize hourly-rates"><?php print ($hourly_wages_pct25_for_state) ? vcn_generic_number_formatter($hourly_wages_pct25_for_state) : 'NA'; ?></td>
							<td class="text noresize hourly-rates"><?php print ($hourly_wages_median_for_state) ? vcn_generic_number_formatter($hourly_wages_median_for_state) : 'NA'; ?></td>
							<td class="text noresize hourly-rates"><?php print ($hourly_wages_pct75_for_state) ? vcn_generic_number_formatter($hourly_wages_pct75_for_state) : 'NA'; ?></td>
							<td class="text noresize hourly-rates"><?php print ($hourly_wages_pct90_for_state) ? vcn_generic_number_formatter($hourly_wages_pct90_for_state) : 'NA'; ?></td>
						</tr>
						<tr>
							<td class="text noresize yearly-salary-header">Yearly</td>
							<td class="text noresize yearly-salary"><?php print ($annual_salary_pct10_for_state) ? vcn_generic_number_formatter($annual_salary_pct10_for_state) : 'NA'; ?></td>
							<td class="text noresize yearly-salary"><?php print ($annual_salary_pct25_for_state) ? vcn_generic_number_formatter($annual_salary_pct25_for_state) : 'NA'; ?></td>
							<td class="text noresize yearly-salary"><?php print ($annual_salary_median_for_state) ? vcn_generic_number_formatter($annual_salary_median_for_state) : 'NA'; ?></td>
							<td class="text noresize yearly-salary"><?php print ($annual_salary_pct75_for_state) ? vcn_generic_number_formatter($annual_salary_pct75_for_state) : 'NA'; ?></td>
							<td class="text noresize yearly-salary"><?php print ($annual_salary_pct90_for_state) ? vcn_generic_number_formatter($annual_salary_pct90_for_state) : 'NA'; ?></td>
						</tr>					
						<tr>
						  	<td class="text noresize location metro" rowspan="2" scope="row"><?php print $metro.'<br/><b>('.$zipcode.')</b>'; ?></td>
						 	<td class="text noresize hourly-rates-header">Hourly</td>
						 	<td class="text noresize hourly-rates"><?php print ($hourly_wages_pct10_for_zipcode) ? vcn_generic_number_formatter($hourly_wages_pct10_for_zipcode) : 'NA'; ?></td>
							<td class="text noresize hourly-rates"><?php print ($hourly_wages_pct25_for_zipcode) ? vcn_generic_number_formatter($hourly_wages_pct25_for_zipcode) : 'NA'; ?></td>
							<td class="text noresize hourly-rates"><?php print ($hourly_wages_median_for_zipcode) ? vcn_generic_number_formatter($hourly_wages_median_for_zipcode) : 'NA'; ?></td>
							<td class="text noresize hourly-rates"><?php print ($hourly_wages_pct75_for_zipcode) ? vcn_generic_number_formatter($hourly_wages_pct75_for_zipcode) : 'NA'; ?></td>
							<td class="text noresize hourly-rates"><?php print ($hourly_wages_pct90_for_zipcode) ? vcn_generic_number_formatter($hourly_wages_pct90_for_zipcode) : 'NA'; ?></td>
						</tr>
						<tr>
							<td class="text noresize yearly-salary-header">Yearly</td>
							<td class="text noresize yearly-salary"><?php print ($annual_salary_pct10_for_zipcode) ? vcn_generic_number_formatter($annual_salary_pct10_for_zipcode) : 'NA'; ?></td>
							<td class="text noresize yearly-salary"><?php print ($annual_salary_pct25_for_zipcode) ? vcn_generic_number_formatter($annual_salary_pct25_for_zipcode) : 'NA'; ?></td>
							<td class="text noresize yearly-salary"><?php print ($annual_salary_median_for_zipcode) ? vcn_generic_number_formatter($annual_salary_median_for_zipcode) : 'NA'; ?></td>
							<td class="text noresize yearly-salary"><?php print ($annual_salary_pct75_for_zipcode) ? vcn_generic_number_formatter($annual_salary_pct75_for_zipcode) : 'NA'; ?></td>
							<td class="text noresize yearly-salary"><?php print ($annual_salary_pct90_for_zipcode) ? vcn_generic_number_formatter($annual_salary_pct90_for_zipcode) : 'NA'; ?></td>
						</tr>									
						<?php } ?>
					</tbody>
				</table>
			</div>			
        </div>
	</fieldset>
	
	<div class="wage-charts">
		<script type="text/javascript">
            google.load('visualization', '1.0', {'packages':['corechart']});
  
            google.setOnLoadCallback(function() { 
	            drawWageChart('wage-chart-hourly-div', 
	    	            				'<?php print round($hourly_wages_pct90_for_national); ?>', '<?php print round($hourly_wages_median_for_national); ?>', '<?php print round($hourly_wages_pct10_for_national); ?>', 
	    	            				'<?php print (isset($hourly_wages_pct90_for_state)) ? round($hourly_wages_pct90_for_state) : NULL; ?>', '<?php print (isset($hourly_wages_median_for_state)) ? round($hourly_wages_median_for_state) : NULL; ?>', '<?php print (isset($hourly_wages_pct10_for_state)) ? round($hourly_wages_pct10_for_state) : NULL; ?>', '<?php print $state; ?>', 
	    	            				'<?php print (isset($hourly_wages_pct90_for_zipcode)) ? round($hourly_wages_pct90_for_zipcode) : NULL; ?>', '<?php print (isset($hourly_wages_median_for_zipcode)) ? round($hourly_wages_median_for_zipcode) : NULL; ?>', '<?php print (isset($hourly_wages_pct10_for_zipcode)) ? round($hourly_wages_pct10_for_zipcode) : NULL; ?>', '<?php print $zipcode; ?>');

	            drawWageChart('wage-chart-annual-div', 
	    	            				'<?php print round($annual_salary_pct90_for_national); ?>', '<?php print round($annual_salary_median_for_national); ?>', '<?php print round($annual_salary_pct10_for_national); ?>', 
	    	            				'<?php print (isset($annual_salary_pct90_for_state)) ? round($annual_salary_pct90_for_state) : NULL; ?>', '<?php print (isset($annual_salary_median_for_state)) ? round($annual_salary_median_for_state) : NULL; ?>', '<?php print (isset($annual_salary_pct10_for_state)) ? round($annual_salary_pct10_for_state) : NULL; ?>', '<?php print $state; ?>', 
	    	            				'<?php print (isset($annual_salary_pct90_for_zipcode)) ? round($annual_salary_pct90_for_zipcode) : NULL; ?>', '<?php print (isset($annual_salary_median_for_zipcode)) ? round($annual_salary_median_for_zipcode) : NULL; ?>', '<?php print (isset($annual_salary_pct10_for_zipcode)) ? round($annual_salary_pct10_for_zipcode) : NULL; ?>', '<?php print $zipcode; ?>');
            });
		</script>
		<div class="floatleft">
			<fieldset>
				<legend><b>Hourly Wage</b></legend>
				<div>	
					<div id="wage-chart-hourly-div" class="wage-chart"></div>			
		        </div>
			</fieldset>			
		</div>
		<div class="floatright">
			<fieldset>
				<legend><b>Yearly Wage</b></legend>
				<div>	
					<div id="wage-chart-annual-div" class="wage-chart"></div>			
		        </div>
			</fieldset>			
		</div>
	</div>		
        
	<div class="wage-bracket-explaination allclear">
		<ul>
			<li>High is the wage at which 90% of workers earn less and 10% earn more.</li>
			<li>Median is the wage at which 50% of workers earn less and 50% earn more.</li>
			<li>Low is the wage at which 10% of workers earn less and 90% earn more.</li>
		</ul>
	</div>
	
	<fieldset>
		<legend><b>State and National Trends</b></legend>
		<div>
			<div>
				<table id="trends-table" summary="this table displays national and state trends information for the selected occupation">
					<thead>
						<tr style="height:27px;">
							<th class="text noresize" id="location-header" scope="col">Location</th>
							<th class="text noresize" id="employment-header" align="center" scope="col" colspan="4">Employment by Year</th>
							<th class="text noresize" id="percent-change-header" scope="col">Percent Change</th>
							<th class="text noresize" id="job-openings-header" scope="col">Job Openings</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="text noresize location">United States</td>
							<td class="text noresize year-from-header">
                2012
              </td>
              <td class="text noresize year-from-numbers">
                <?php print ($num_present_jobs_for_national) ? vcn_generic_number_formatter($num_present_jobs_for_national, 0, '') : 'NA'; ?>
              </td>
							<td class="text noresize year-to-header">
                2022
              </td>
              <td class="text noresize year-to-numbers">
                <?php print ($num_projected_jobs_for_national) ? vcn_generic_number_formatter($num_projected_jobs_for_national, 0, '') : 'NA'; ?>
              </td>
							<td class="text noresize percent-change"><?php print ($percent_job_growth_value_for_national) ? ($percent_job_growth_value_for_national == -99 ? "NA" : $percent_job_growth_value_for_national.'%') : "NA" ?></td>
							<td class="text noresize job-openings"><?php print ($num_job_openings_for_national) ? vcn_generic_number_formatter($num_job_openings_for_national, 0, '') : 'NA'; ?></td>
						</tr>
						<?php if ($zipcode) : ?>
						<tr>
						  <td class="text noresize location"><?php print $state; ?></td>
						  <td class="text noresize year-from-header">
                2010
              </td>
              <td class="text noresize year-from-numbers">
                <?php print ($num_present_jobs_for_zipcode) ? vcn_generic_number_formatter($num_present_jobs_for_zipcode, 0, '') : 'NA'; ?>
              </td>
							<td class="text noresize year-to-header">
                2020
              </td>
              <td class="text noresize year-from-numbers">
                <?php print ($num_projected_jobs_for_zipcode) ? vcn_generic_number_formatter($num_projected_jobs_for_zipcode, 0, '') : 'NA'; ?>
              </td>
							<td class="text noresize percent-change"><?php print ($percent_job_growth_value_for_zipcode) ? ($percent_job_growth_value_for_zipcode == -99 ? "NA" : $percent_job_growth_value_for_zipcode.'%') : "NA" ?></td>
							<td class="text noresize job-openings"><?php print ($num_job_openings_for_zipcode) ? vcn_generic_number_formatter($num_job_openings_for_zipcode, 0, '') : 'NA'; ?></td>
						</tr>		
						<?php endif; ?>
					</tbody>
				</table>				 
			</div>			
        </div>
	</fieldset>	  
	
</div>	

		