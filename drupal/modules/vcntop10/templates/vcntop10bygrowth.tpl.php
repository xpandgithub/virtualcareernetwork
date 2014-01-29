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
 * @file
 * Default theme implementation to display Top 10 by jobs.
 * 
 */
// This is temporary condition to open links in new window for drupal 6 career guide pages
$link_target = 'target="_self"';
if(vcn_drupal6_career_guide_D7_popup()) {
	$link_target = 'target="_blank"';
}
?>
<h1 class="title-top">Top 10 Fastest Growing <?php print $industry_name; ?> Careers</h1>
<div>
	<p>As the population ages, there will be an increasing demand for <?php print $industry_name; ?> positions. The following <?php print $industry_name; ?> jobs are among the fastest growing occupations in the nation as shown by their growth rates.</p>
	<table class="top10data">
	<tr>
		<th class="top10td" >Ranking</th>
		<th>Career</th>
		<th class="top10td" >Growth Rate</th>
	</tr>
	<?php  	
	for ($i=0; $i<count($occupationlist->career); $i++) { 
		if ($i%2) {
			$class = "class='top10evenrows'";
		} else {
			$class = "";
		}
		?>
		<tr <?php echo $class; ?> >
			<td class='top10td' ><?php echo $i+1; ?></td>
			<td><a <?php echo $link_target; ?> href="<?php echo $vcn_drupal7_base_path; ?>careers/<?php echo $occupationlist->career[$i]->onetcode; ?>"><?php echo $occupationlist->career[$i]->displaytitle; ?></a></td>
			<td class='top10td' ><?php echo vcn_generic_number_formatter((float)$occupationlist->career[$i]->growth, 0, ''); ?>%</td>
		</tr>		
	<?php  	
	 }	
	?>
	</table>	
	<p class="top10source">(Growth Rates Source: Bureau of Labor Statistics, Office of Occupational Statistics and Employment Projections, 2008-2018 BLS Projections)</p>
</div>