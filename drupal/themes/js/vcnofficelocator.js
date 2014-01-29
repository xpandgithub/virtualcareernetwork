/*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ 


(function($) {$(function() {
	var defaultzipvalue = $('#edit-zip').val();
	var zipcode = "";
	var zipcode_share_cookie_name = "d6_d7_zipcode_share";
	
	$("#edit-zip").keydown(function(event) {
        // Allow: backspace, delete, tab, escape, and enter
        if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || 
             // Allow: Ctrl+A
            (event.keyCode == 65 && event.ctrlKey === true) || 
             // Allow: home, end, left, right
            (event.keyCode >= 35 && event.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        else {
            // Ensure that it is a number and stop the keypress
            if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
                event.preventDefault(); 
            }   
        }
    });
	
	$('#vcnofficelocator-form').submit(function() {
		
	    var validated = false; 
	    var zipcode = $('#edit-zip').val();
	   	
	    validated = vcn_zipcode_validation(zipcode);
	   	
	   	if (!validated) { 		   
	   		field_error_display("edit-zip", "Please enter a valid US ZIP Code.");		   
	    }else {	    	
	    	field_error_hide("edit-zip");
	    	vcn_delete_zipcode_cookie(zipcode_share_cookie_name);
			vcn_set_zipcode_cookie(zipcode_share_cookie_name, zipcode);
	    }
  	
	    return validated;   	
	  
	});
});

function vcn_set_zipcode_cookie(key, value) {
	$.cookie(key, value, { path: '/'});
}


function vcn_delete_zipcode_cookie(key) {
	$.cookie(key, null, { path: '/'});
}


})(jQuery);