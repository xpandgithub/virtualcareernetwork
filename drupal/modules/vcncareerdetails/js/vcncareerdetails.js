/*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ 


function onamesmorelink() {
	jQuery("#onames").toggle();
	
	var txt = jQuery("#onames").is(':visible') ? 'Less Names' : 'More Names';
	jQuery("#onamesmorelink").text(txt);
	jQuery("#onamesmorelink").attr("title",txt);
};

(function($) {
	var moretext = "More Details";
	var lesstext = "Less Details";
	var zipcode_share_cookie_name = "d6_d7_zipcode_share";
		
	Drupal.behaviors.careerdetails = {
		attach: function(context, settings) {
			/* toggle functionality for Show More / Less */
			$('.morelink').click(function() {
				if ($(this).hasClass("less")) {
					$(this).removeClass("less");
					$(this).html(moretext);
					$(this).attr("title",moretext);
				} else {
			        $(this).addClass("less");
			        $(this).html(lesstext);
			        $(this).attr("title",lesstext);
				}
				$(this).prev().toggle();
				return false;
			});
			 /* End of toggle functionality for Show More / Less */			
		   
		    $("#zipcode-textbox").keydown(function(event) {
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
		    
		    $('#zipcode-form').submit(function() {
		    	var zipcode = $('#zipcode-textbox').val();
		    	if (zipcode == "ZIP Code") {
		    		zipcode = "";
		    	}
		    	if (zipcode == "") {
		    		vcn_clear_zipcode_cookie(zipcode_share_cookie_name);
		    		window.location.href = vcn_set_zipcode_and_reload(zipcode);
		    	} else {
		    		var zip_validation_url = vcn_get_drupal7_base_path()+'zipcode-validation/ajax?zipcode='+zipcode;
		    		$.getJSON(zip_validation_url, function(data) {
		    			if (data) {
		    				$('#career-details-error-messages').remove();
		    				vcn_delete_zipcode_cookie(zipcode_share_cookie_name);
		    				vcn_set_zipcode_cookie(zipcode_share_cookie_name, zipcode);
		    				window.location.href = vcn_set_zipcode_and_reload(zipcode);
			    		} else {
			    			$('#career-details-error-messages').remove();
			    			custom_error_display('Please enter a valid US ZIP Code.');
			    			$('#zipcode-textbox').css("border","2px solid red");
			    		}
			    	});
		    	}
		    	return false;
		    });
		}
	};
	
	function vcn_set_zipcode_and_reload(zipcode) {
		//var current_url = String(window.location); //alert(self.location.pathname);  alert(window.location);  
		var current_url = String(self.location.pathname); 
	    var url_array = current_url.split("/"); //alert(current_url); 
	    //var url_array_length = url_array.length; //alert(url_array_length);
		
	    if(!url_array[4])
	    {
	    	url_array[4] = "overview";
	    }
	    url_array[5] = zipcode;	   
	    
	    return url_array.join('/');
	}
	
	function vcn_set_zipcode_cookie(key, value) {
		$.cookie(key, value, { path: '/'});
	}
	
	
	function vcn_delete_zipcode_cookie(key) {
		$.cookie(key, null, { path: '/'});
	}
	
	function vcn_clear_zipcode_cookie(key) {
		$.cookie(key, "cleared", { path: '/'});
	}
	
})(jQuery);