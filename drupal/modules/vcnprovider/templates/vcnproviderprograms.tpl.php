<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>



<h1 class="title">List of Programs for <?php print $school_name; ?></h1>

<div id="provider-programs-results-div">
  <div class="floatleft">
    <?php print $program_count; ?> program(s) found
  </div>
  <div class="floatright">
    <input type="button" title="Add New Program" value=" Add New Program " class="vcn-button" onclick="javascript:location.href = '<?php echo $vcn_base_path; ?>provider/<?php echo $unitid; ?>/program';" />
  </div>
  <div class="clearall"></div>
</div>

<div class="clearall"></div>

<div>Hint: Arrows to the right of a heading allow users to sort the column below, e.g., lowest to highest under the "Award level" column.</div>

<div id="provider-programs-data-table" class="clearall">
  <?php print $rendered_table; ?>
</div>
    
<br/>
