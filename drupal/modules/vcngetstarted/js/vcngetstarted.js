/*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ 


(function($) {$(function() {	 
	var zipcode_share_cookie_name = "d6_d7_zipcode_share";
	var edu_level_share_cookie_name = "user_edu_level";
	
	$("#edit-zipcode").keydown(function(event) {
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
	
	$('#vcngetstarted-form').submit(function() {
		
		var validated = true;
		var errormsg = "";
	    var zipcode = $('#edit-zipcode').val();
	    var edu_level = $('#edit-edu-level').val();
	   	
	    validated = vcn_zipcode_validation(zipcode);
	   	
	   	if (!validated) { 		   
	   		validated = false;
	   		errormsg = errormsg + "<ul><li>Please enter a valid US ZIP Code.</li></ul>";
	   		$('#edit-zipcode').addClass('error');		   		 	   
	    }else {	    	
	    	$('#edit-zipcode').removeClass('error');			
			vcn_delete_cookie(zipcode_share_cookie_name, '');
			vcn_set_cookie(zipcode_share_cookie_name, zipcode, '', '');
	    }
  	
	   	if($('#edit-edu-level').val() < 1){
	   		validated = false;
	   		errormsg = errormsg + "<ul><li>Please select Education Level.</li></ul>";
	   		$('#edit-edu-level').addClass('error');	
	    }else {
	    	$('#edit-edu-level').removeClass('error');
	    	vcn_delete_cookie(edu_level_share_cookie_name, '');
			vcn_set_cookie(edu_level_share_cookie_name, edu_level, '', '');
	   	}
	   	
	    if(validated == false){		  	
	    	custom_error_display(errormsg);	    
	    }else{
	    	custom_error_hide();
	    }   	
	   	
	    return validated;   	
	  
	});
});


})(jQuery);


jQuery(document).ready(function(){  
	
	var drupal7_basepath = vcn_get_drupal7_base_path(); //Drupal.settings.drupal_basepaths.drupal7_basepath;
	//var ajaxTime= Date.now();
	
	var jobcountbyonetandzipurl = "";
	jQuery('.get-started-jobs-count').each(function() {  
		var selecteddiv = jQuery(this);
		
		jobcountbyonetandzipurl = drupal7_basepath+'jobcountbyonetandzip/ajax/'+selecteddiv.attr("id"); 	//alert(jobcountbyonetandzipurl);
		 
		jQuery.ajax({
	      url: jobcountbyonetandzipurl, 
	      cache: false,
	      //async: false,
	      timeout: 10000,
	      dataType: "text",
	      success: function(response, textStatus, jqXHR) { //var totalTime = Date.now()-ajaxTime; alert(totalTime+textStatus+response);	    	
	    	selecteddiv.html(response);
	      },
	      error: function(jqXHR, textStatus, errorThrown) {  // textStatus can be null, "timeout", "error", "abort", and "parsererror"	    	      
	    	selecteddiv.html("NA");
	      }
	    });	//End of AJAX call
		 		
    });
	
});