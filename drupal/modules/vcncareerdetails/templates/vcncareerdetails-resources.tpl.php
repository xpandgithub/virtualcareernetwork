<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<div class="vcncareerdetails-resources">

	<?php  $resourcesExist = false; ?>
	
	<?php if(isset($resources) && count($resources) > 0) { $resourcesExist = true; ?>
	<fieldset>
		<legend><b>Resources Related to the Career</b></legend>
		<div class="resources-data">
		<?php  foreach($resources as $key => $value) {?>
			<div>
				<?php print $key; ?>
			</div>
			<?php  foreach($value as $v) {?>
				<p>
				  <?php 
				  if ($v['resourcelinkflag'] == 1 && strlen($v['resourcelink'])) {
					vcn_build_link_window_opener($v['resourcelink'], $v['resourcename'], TRUE, TRUE, '', 'extlink', TRUE); 
				  }
				  ?> 
				</p>
			<?php } ?>	
		<?php } ?>						
        </div>
	</fieldset>
	<?php } ?>
	
	<?php  if(isset($fincancialaid_resources) && count($fincancialaid_resources) > 0) { $resourcesExist = true; ?>
	<fieldset>
		<legend><b>Financial Aid</b></legend>
		<div class="resources-data">
		<?php  foreach($fincancialaid_resources as $key => $value) {?>				
			<p>
			  <?php 
			  if ($value['financialaidurlflag'] == 1 && strlen($value['financialaidurl'])) {
              	  vcn_build_link_window_opener($value['financialaidurl'], $value['financialaidname'], TRUE, TRUE, '', 'extlink', TRUE); 
              }
			  ?> 
			</p>				
		<?php } ?>						
        </div>
	</fieldset>
	<?php } ?>
	
	<?php if (!$resourcesExist) { ?>
    	No resources available.<br/><br/>
    <?php } ?>

</div>	
		