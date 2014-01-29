/*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ 


jQuery(document).ready(function() {
	CKEDITOR.replaceAll = function() { 
		var textareas = document.getElementsByTagName( 'textarea' );

		for ( var i = 0; i < textareas.length; i++ ) {
			var config = null,
				textarea = textareas[ i ];

			// The "name" and/or "id" attribute must exist.
			if ( !textarea.name && !textarea.id ) {
				continue;
			}
	 //link: http://nightly.ckeditor.com/13-04-03-14-51/standard/samples/plugins/toolbar/toolbar.html
		config = {toolbar: [{ name: 'basicstyles', items: [ 'Bold', 'Italic', '-', 'Cut', 'Copy', 'Paste'] }, { name: 'links', items: [ 'Link', 'Unlink'] } ]} ;
		this.replace( textarea, config );
		}
	};
});

function vcnrestcustomcms_delete_resource(img, index, isNew) {
	
	if (img) {
		var newStr = '';
		if (isNew) {
			newStr = 'new_';
		} else {
			jQuery('#resource_delete_'+index).val('true');
		}
		
		img.style.display = 'none';
		jQuery('#resourcename_'+newStr+index).css("display", "none");
		jQuery('#resourcelink_'+newStr+index).css("display", "none");
		jQuery('#resourcecategory_'+newStr+index).css("display", "none");
	}
}

function vcnrestcustomcms_add_new_resource(categoryarray) {

	var container = jQuery('#resourcesdiv');
	var inputs = container.find('input');
	var index = (inputs.length) / 3;
			
	var html = '<input type="hidden" id="blank' + index + '" name="blank' + index + '" value=""/>';
	html += '<img id="resource_img_' + index + '" src="' + vcn_get_images_basepath() + '/buttons/delete_on.png" style="vertical-align:middle;" onclick="vcnrestcustomcms_delete_resource(this, ' + index + ', true);"/> ';
	html += '<input type="text" id="resourcename_new_' + index + '" name="resourcename_new_' + index + '" value="" style="width:315px;" /> ';
	html += '<input type="text" id="resourcelink_new_' + index + '" name="resourcelink_new_' + index + '" value="" style="width:315px;" /> ';

	html += '<select id="resourcecategory_new_' + index + '" name="resourcecategory_new_' + index + '">';
	
	for ( var i = 0; i < categoryarray.length; i++ ) {
      html += '<option value="' + categoryarray[i][0] + '">' + categoryarray[i][1] + '</option>';
	}

	html += '</select>';
	html += '<br/>';
		
	container.append(jQuery(html));
}

