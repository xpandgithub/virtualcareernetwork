/*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ 


(function($) {
	Drupal.behaviors.vcn_findwork_common = {
		attach: function(context, settings) {
			
			$('#edit-careers').change(function(){
				$('#edit-search-by-job-title').val("");
			});
			
			$('#edit-search-by-job-title').keypress(function() {
				if ($.trim($(this).val()).length >= 2) {
					$('#edit-careers').val('');
				}
			});
			
			$('#edit-search-by-job-title').keydown(function(event) { 
				var isaphabet = vcn_textbox_allow_only_alphabets(event);
				if (isaphabet == true) {
					return isaphabet;
				} else {
					event.preventDefault();
				}
			});
			
			$('#edit-search-by-job-title').bind('paste', function(event) {
				var regex = new RegExp("^[a-zA-Z ]+$");
				setTimeout(function() {
					var str = $('#edit-search-by-job-title').val();
					if (!regex.test(str)) {
						$('#edit-search-by-job-title').val("");
					}
			    }, 1);
			});
			
			$('#edit-zipcode').keydown(function(event) {
				var isnumeric = vcn_textbox_allow_only_numbers(event);
				if (isnumeric == true) {
					return isnumeric;
				} else {
					event.preventDefault();
				}
		    });

			vcn_monkeyPatchAutocomplete(); // calling this function defined in vcn_general.js for autocomplete highlighting matches
			$('#edit-search-by-job-title').autocomplete({
				minLength: 3,
				delay : 200,
				source: function(request, response) { 
					jQuery.ajax({
						url: vcn_get_drupal7_base_path()+'findwork-autocomplete',
						data: {
							mode : "ajax",
							task : "display",
							limit : 15,
							term : request.term
						},
						dataType: "json",
							success: function(data){
							response(data);
						}	

					});
				}
			});
		}
	};	
})(jQuery);

function vcn_validate_findwork_forms(zipcode_domObj, onetcode_domObj, search_text_domObj) {
	/* first remove the error classes on input fields, start with a clean slate */
	zipcode_domObj.removeClass('error');
	onetcode_domObj.removeClass('error');
	search_text_domObj.removeClass('error');
	
	var error_message = '';
	var input_error = false;
	var zip_error = false;
	
	var zipcode = jQuery.trim(zipcode_domObj.val());
	var onetcode = onetcode_domObj.val();
	var search_text_val = jQuery.trim(search_text_domObj.val());
	
	if (zipcode != '') {
		var zip_validate = vcn_zipcode_validation(zipcode);
		if (!zip_validate) {
			zip_error = true;
		}
	}
	if (search_text_val == '' && onetcode == '') {
		input_error = true;
	}
	
	if (input_error && zip_error) {
		error_message = 'Please enter a valid US ZIP Code.<br/>Please enter a search term OR select a career from the list.';
		zipcode_domObj.addClass('error');
		onetcode_domObj.addClass('error');
		search_text_domObj.addClass('error');
	} else if(input_error) {
		error_message = 'Please enter a search term OR select a career from the list.';
		onetcode_domObj.addClass('error');
		search_text_domObj.addClass('error');
	} else if(zip_error) {
		error_message = 'Please enter a valid US ZIP Code.';
		zipcode_domObj.addClass('error');
	}
	
	if (zip_error || input_error) {
		custom_error_display(error_message);
		return false;
	}
}