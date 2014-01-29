<?php /*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ ?>


<?php
 //
 // theme template for VCN Open Source Portal pages
 //
?>

<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>

  <?php if (!drupal_is_front_page()): ?>
     <?php print render($title_prefix); ?>
     <?php if (!$page): ?>
       <h2<?php print $title_attributes; ?>><a href="<?php print $node_url; ?>"><?php print $title; ?></a></h2>
     <?php endif; ?>
     <?php print render($title_suffix); ?>
  <?php endif; ?>

  <div class="content"<?php print $content_attributes; ?>>
    <div id="osp-content">
      <?php
        // We hide the comments and links now so that we can render them later.
        hide($content['comments']);
        hide($content['links']);
        hide($content['field_industry_search_filter']);
        print render($content);
      ?>
	</div>
	<div id="osp-main-image">
      <img src="<?php print vcn_image_path(); ?>miscellaneous/ospimage.jpg" alt="ospimage" width="300" />
      <br/><br/>
	  <div id="osp-forum-activity-list">
	    <?php 	  
	      // show Recent Forum Activity block
		
		  //$block = module_invoke('forum', 'block_view', 'active');
          //print render($block['content']);
	    ?>
      </div>
    </div>
  </div>

  <?php //print render($content['links']); ?>
  <?php //print render($content['comments']); ?>

</div>
