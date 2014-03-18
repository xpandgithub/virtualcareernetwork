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
			<div id="vcn-pla-military-credit-main-content">
		    <span class="tab-header">Military Training</span>
		    
				<?php print vcn_node_text_by_urlalias('military-credit-text'); ?>
		    
		    <div class="pla-form-box">
		      <?php print $military_credit_branch; ?>
		      <?php print $military_credit_search_box; ?>
		      <?php print $military_credit_submit; ?>
		      <br/><br/>
		    </div>
		    
		    <p>Go to <a href="<?php print $vcn_drupal7_base_path; ?>pla/my-learning-inventory">Review Inventory</a> tab to see your saved courses.</p>
		    <?php if ($display_result): ?>
					<div class="vcn-pla-search-result-title"><br/>Search Results: </div>
					<div id="vcnpla-military-credits-div">
						<table id="vcnpla-military-credits-table" class="dttable">
							<thead>
								<tr><th class="dtheader sorting">Course Name</th></tr>
							</thead>
							<tbody>
								<tr><td class="dataTables_empty">Loading data from server</td></tr>
							</tbody>
						</table>
					</div>
				<?php endif; ?>
				<?php print vcn_node_text_by_urlalias('military-credit-resources'); ?>
				<p><span class="strong">Hint:</span> Remember that each college determines how your prior learning might apply to a specific degree program.</p>
			</div>
		</div>
		<?php print $military_credit_children; ?>
	</div>
	<div class="vcn-right-sidebar">
		<?php echo $pla_sidebar;?>
	</div>
</div>