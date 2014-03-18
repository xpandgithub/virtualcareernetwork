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
 * Default theme implementation to display a online courses.
 * 
 */
?>

<style type="text/css">
.cke_button_icon.cke_button__cut_icon {
    display:  none;
	}
.cke_button_icon.cke_button__copy_icon {
    display:  none;
	}
.cke_button_icon.cke_button__paste_icon {
    display:  none;
}
</style>

<script src="<?php print vcn_drupal7_base_path(); ?>sites/all/libraries/ckeditor/ckeditor.js"></script>

<script>

var rarray=new Array();

<?php 
	
	$categories = (array)$category_array->categories;
	foreach ($categories['item'] as $value) {	 
		print('rarray.push( new Array("' . $value->categoryid . '", "'. $value->categoryname . '"));' . "\n");
	}
?>	

(function($) {
	Drupal.behaviors.vcnrestcustomcms = {
		attach: function(context, settings) {

      // make sure the dropdown and textbox set the submitchanges hidden value to N when their value changes
      $('.target').change(function() {
        $('#submitchanges').val('N');
      });

		}
	};	
})(jQuery);
</script>

<h1 class="title">VCN Career Information Editor</h1>

<form action="<?php print vcn_drupal7_base_path(); ?>career-data-cms" method="post">
	<div>
		<p>
			The purpose of this tool is to help edit/format the career detail field values.  
			Note, whatever line spacing is displayed in the textarea box will show up on the VCN site. 
		</p>
		<p>
			Select a Career that you want to edit from the drop down and make necessary changes in the appropriate text areas. 
			When you hit Submit all your changes will be saved in the database. You will be able to see the changes on the career detail page for that career.
		</p>
		
		<?php if (strlen($onetcode)) : ?>
      <p>
        To view your changes on the corresponding career detail page please <a href="<?php echo vcn_drupal7_base_path(); ?>careers/<?php echo $onetcode;?>/no-cache" target = "_blank">Click here.</a> 
      </p>
		<?php
		endif;
		?>
	</div>

	<br/>
	<select name="onetcode" id="onetcode" class="target" onchange="<?php if ($is_user_admin) { print "this.form.onetcode_manual.value='';"; } ?>this.form.submit();">
	<option value="">Select a <?php print $GLOBALS['vcn_config_default_industry_name']; ?> Career</option>
	  <?php 
		foreach ($career_list_array->careerdata as $value) :
      $titleValue = (string)$value->title;
      $onetcodeValue = (string)$value->onetcode;
	  ?>
      <option value="<?php print $onetcodeValue; ?>" <?php if ($onetcodeValue == $onetcode){?> selected="selected" <?php }?> ><?php print $onetcodeValue; ?>: <?php print $titleValue; ?></option>
	  <?php 
		endforeach;
	  ?>
	</select>	

  <?php
  if ($is_user_admin) :
  ?>
    <p>Or enter an Onetcode: <input type="text" id="onetcode_manual" class="target" name="onetcode_manual" placeholder="XX-YYYY.ZZ" maxlength="10" value="<?php print $onetcode; ?>" /></p>
  <?php
  endif;
  ?>
  
	<?php
	if (strlen($onetcode)) :
    if (!$no_data_found) :
	?>
    
      <br/><br/>

      <h1><?php print $onetcode . ': ' . $career_title; ?></h1>

      <div>
        <p/>
          <strong>Description: </strong><br/><br/>		
          <textarea id="description" name="description" style="width:100%; height:150px;" ><?php echo $detailed_description; ?></textarea><br/>
        <p/>
          <strong>Physical/Medical/Health Requirements: </strong><br/><br/>
          <textarea id="physhealthrequirements" name="physhealthrequirements" style="width:100%; height:150px;"  ><?php echo $phys_health_requirements; ?></textarea><br/>
        <p/>
          <strong>Physical/Medical/Health Requirement Url: </strong><br/><br/>
          <input type="text" id="physhealthrequirementsurl" name="physhealthrequirementsurl" style="width:100%;" value="<?php echo $phys_health_requirements_url; ?>" /><br/><br/>
        <p/>
          <strong>Nationwide Legal Requirements: </strong><br/><br/>
          <textarea id="nationwidelegalrequirementdesc" name="nationwidelegalrequirementdesc" style="width:100%; height:150px;"  ><?php echo $nationwide_legal_requirement_desc; ?></textarea><br/>
        <p/>
          <strong>Nationwide Legal Requirement Url: </strong><br/><br/>
          <input type="text" id="nationwidelegalrequirementurl" name="nationwidelegalrequirementurl" style="width:100%;" value="<?php echo $nationwide_legal_requirement_url; ?>" /><br/><br/>
        <p/>
          <strong>Day in the Life: </strong><br/><br/>
          <textarea id="dayinlife" name="dayinlife" style="width:100%; height:150px;"  ><?php echo $day_in_life_description; ?></textarea><br/>
        <p/>
          <strong>Education and Training: </strong><br/><br/>
          <textarea id="edutraining" name="edutraining" style="width:100%; height:150px;"  ><?php echo $academic_requirement; ?></textarea><br/>
        <p/>
      </div>

      <br/>

      <input type="hidden" id="updateform" name="updateform" value=""/>

      <strong>Resources:</strong>
      <br/><br/>
      <div id="resourcesdiv">
      <?php
      if (isset($occ_res_array) && is_array($occ_res_array)) {

        $sHtml = '';

        foreach($occ_res_array as $value) {
          $sHtml .= '<input type="hidden" id="resource_delete_' . $value['res_id'] .  '" name="resource_delete_' . $value['res_id'] . '" value=""/>';
          $sHtml .= '<img id="resource_img_' . $value['res_id'] . '" src="'.vcn_image_path().'buttons/delete_on.png" style="vertical-align:middle;" onclick="vcnrestcustomcms_delete_resource(this, ' . $value['res_id'] . ', false);"/> ';
          $sHtml .= '<input type="text" id="resourcename_' . $value['res_id'] . '" name="resourcename_' . $value['res_id'] . '" value="' . $value['res_name'] . '" style="width:315px;"/> <input type="text" id="resourcelink_' . $value['res_id'] . '" name="resourcelink_' . $value['res_id'] . '" value="' . $value['res_link'] . '" style="width:315px;"/> ' . "\n";
          $sHtml .= vcnrestcustomcms_build_career_resources_dropdown($value['cat_id'], $value['res_id'], $category_array) . '<br/>';
        }
        echo $sHtml;
      }
      ?>
      <br/><a href="javascript:void(0);" onclick="vcnrestcustomcms_add_new_resource(rarray)"/>Click here to add a new resource</a><br/>
      </div>

      <p/>
      <br/>
      <input type="hidden" name="submitchanges" id="submitchanges" value="N" />
      <input type="Submit" value="Submit Changes" title="submit changes" alt="submit changes" class="vcn-button" name="submit changes" onmouseup="this.form.submitchanges.value='Y';" />

      <p>Once you hit Submit, to see the changes on the career detail page, for that career please <a href="<?php echo vcn_drupal7_base_path(); ?>careers/<?php echo $onetcode;?>/no-cache" target = "_blank">Click here.</a></p>

    <?php
    else:
    ?>
      <p><strong style="color:red;">The selected career <?php print $onetcode; ?> was not found. Please try again.</strong></p>
  <?php
    endif;
	endif;
	?>
</form>

<p>
  <a href="<?php print vcn_drupal7_base_path(); ?>user/logout?destination=">Logout</a>
</p>