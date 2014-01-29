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
 * Default theme implementation to display the onet assessment (interest profiler).
 * 
 */
?>
<h1 class="title">Match Your Interests to Careers</h1>

<form action="<?php print vcn_drupal7_base_path(); ?>interest-profiler-results/<?php print ($is_using_lightbox) ? 'lightbox' : ''; ?>" method="get">

<div>
<p>
Below is the O*NET Interest Profiler which can help you find out what your interests are and how they relate 
to the world of work. You can find out what you like to do.  The O*NET Interest Profiler helps 
you decide what kinds of careers you might want to explore.
</p>
<p>
<strong>Here's how it works:</strong><br/>
The O*NET Interest Profiler has 60 questions about work activities that some people do on their jobs.
Read each question carefully and decide how you would feel about doing each type of work:
Strongly Dislike, Dislike, Unsure, Like, Strongly Like.
</p>
<p>
<strong>As you answer the questions:</strong><br/>
Try NOT to think about: If you have enough education or training to do the work; or How much money 
you would make doing the work. Just think about if you would like or dislike doing the work.
</p>
<p>
<strong>This is not a test:</strong><br/>
There are no right or wrong answers! Please take your time answering the questions. 
There is no need to rush! You are learning about your interests, so that you can explore work 
you might like and find rewarding!  Please answer each question, in order, before continuing.
You can change your answers at any time.
</p>
</div>

<br/>

<div id="profiler-iptable-outer-container">

	<table id="profiler-iptable" cellspacing="0" cellpadding="0">
	<?php 
	$assessmentType = '';

	for ($i = 0; $i <= 60; $i++) :
	  $rowClass = "profiler-iptable-tr-body-odd";
	  if ($i % 2 == 1) {
	    $rowClass = "profiler-iptable-tr-body-even";
	  }
	
	  $displayHeader = false;
	  if ($i % 12 == 0 || $i == 60) {
	  	$displayHeader = true;
	  }
	  
	  if ($displayHeader) :
	?>
	  <thead>
	    <tr class="profiler-iptable-header <?php print $alt_color_class; ?>">
	      <th class="profiler-iptable-index-head profiler-iptable-td-head"></th>
	      <th class="profiler-iptable-answer-head profiler-iptable-td-head">Strongly<br>Dislike</th>
	      <th class="profiler-iptable-answer-head profiler-iptable-td-head">Dislike</th>
	      <th class="profiler-iptable-answer-head profiler-iptable-td-head">Unsure</th>
	      <th class="profiler-iptable-answer-head profiler-iptable-td-head">Like</th>
	      <th class="profiler-iptable-answer-head profiler-iptable-td-head">Strongly<br>Like</th>
	      <th class="profiler-iptable-td-head"></th>
	    </tr>
	  </thead>
	<?php
	  endif;
	  
	  // if this is the 60th iteration we just wanted to add the header to the last line and then exit loop
	  if ($i == 60) {
	  	break;
	  }
	  
	  $assessmentType = $assessment_types_arr[(string)$questions->question[$i]->type];
	  
	?>
	  <tr id="profiler-iptable-row<?php print $i; ?>" class="<?php print $rowClass; ?>">
	    <?php if ($i % 2 == 0) : ?>
	      <td class="profiler-iptable-type-<?php print strtolower($assessmentType); ?>" rowspan="2"></td>
	    <?php endif; ?>
	
	    <td class="profiler-iptable-answer-body profiler-iptable-td-body">
        <label for="0q<?php print $i; ?>" style="display:none;">0q<?php print $i; ?></label><input type="radio" id="0q<?php print $i; ?>" name="q<?php print $i; ?>" title="Strongly Dislike" value="0" <?php print (isset($answers[$i]) && (string)$answers[$i] === '0' ? 'checked="checked"' : ''); ?>>
	    </td>
	    <td class="profiler-iptable-answer-body profiler-iptable-td-body">
	      <label for="1q<?php print $i; ?>" style="display:none;">1q<?php print $i; ?></label><input type="radio" id="1q<?php print $i; ?>" name="q<?php print $i; ?>" title="Dislike" value="1" <?php print (isset($answers[$i]) && (string)$answers[$i] === '1' ? 'checked="checked"' : ''); ?>>
	    </td>
	    <td class="profiler-iptable-answer-body profiler-iptable-td-body">
	      <label for="2q<?php print $i; ?>" style="display:none;">2q<?php print $i; ?></label><input type="radio" id="2q<?php print $i; ?>" name="q<?php print $i; ?>" title="Unsure" value="2" <?php print (!isset($answers[$i]) || (string)$answers[$i] === '2' ? 'checked="checked"' : ''); ?>>
	    </td>
	    <td class="profiler-iptable-answer-body profiler-iptable-td-body">
	      <label for="3q<?php print $i; ?>" style="display:none;">3q<?php print $i; ?></label><input type="radio" id="3q<?php print $i; ?>" name="q<?php print $i; ?>" title="Like" value="3" <?php print (isset($answers[$i]) && (string)$answers[$i] === '3' ? 'checked="checked"' : ''); ?>>
	    </td>
	    <td class="profiler-iptable-answer-body profiler-iptable-td-body">
	      <label for="4q<?php print $i; ?>" style="display:none;">4q<?php print $i; ?></label><input type="radio" id="4q<?php print $i; ?>" name="q<?php print $i; ?>" title="Strongly Like" value="4" <?php print (isset($answers[$i]) && (string)$answers[$i] === '4' ? 'checked="checked"' : ''); ?>>
	    </td>
	    <td class="profiler-iptable-question-body profiler-iptable-td-body">
	      <?php print (string)$questions->question[$i]->questiontext; ?>
	      <input type="hidden" name="t<?php print $i; ?>" value="<?php print (string)$questions->question[$i]->type; ?>" />
	    </td>
	  </tr>
	<?php
	endfor;
	?>
	</table>
	
	<br/>
	
	<div id="profiler-iptable-submit">
	  <input type="hidden" name="industry" value="<?php print $industry; ?>" />
	  <input type="hidden" name="limit" value="<?php print $limit; ?>" />
	  <input type="submit" value="Find Matching Careers" class="vcn-button <?php print $alt_color_class; ?>" />
	</div>

  <br/>
  
  <?php if (!vcn_external_client_calling_interest_profiler()) : ?>
  <!-- VCN Navigation bar -->
  <div class="vcn-user-navigation-bar allclear">
    <div class="nav-bar-left"><div><a title="Back" href="javascript:history.go(-1);">Go Back</a></div></div>	      			
    <div class="allclear"></div>		      	
  </div>
  <!-- End of VCN Navigation bar -->
  <br/>
  <?php endif; ?>

  <div class="profiler-onet-logo">
    <a href="http://www.onetonline.org" target="_blank"><img src="<?php print vcn_image_path(); ?>site_logo_images/onet.png" height="60" alt="ONet logo" /></a>
  </div>
  
</div>

</form>
	