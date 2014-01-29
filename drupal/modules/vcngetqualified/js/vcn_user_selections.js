/*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ 


(function($) {
	Drupal.behaviors.vcn_user_selections = {
		attach: function(context, settings) {
			
			$('.vcn-user-selections-show-on-change').hide();
			
			$('#vcn-user-selections-submit #edit-vcn-user-selections-edit').click(function(){
				$('.vcn-user-selections-show-on-change').show();
				$('.vcn-user-selections-show-on-load').hide();
				return false; //prevent form from submiting
			});
			
			var onetcode = $('#edit-vcn-user-selections-career').val();
			
			// function call to compute minimum education and typical education on page load
			vcn_user_selection_common(onetcode, 'load');
			
			$('#edit-vcn-user-selections-career').change(function() {
				// function call to compute minimum education and typical education on select list change
				vcn_user_selection_common($(this).val(), 'change');
			});
			
			$('#vcn-user-selections-edu-level-note').hide();
			$("#edit-vcn-user-selections-zipcode").limitkeypress({ rexp: /^[0-9]*$/ });
			
			$('#vcn-user-selections-form').submit(function(){
				var zipcode = $.trim($('#edit-vcn-user-selections-zipcode').val());
				var career = $.trim($('#edit-vcn-user-selections-career').val());
				
				$('#edit-vcn-user-selections-zipcode').removeClass('error');
				$('#edit-vcn-user-selections-career').removeClass('error');
				
				var error_message = '';
				var career_error = false;
				var zip_error = false;
				
				if (zipcode != '') {
					var zip_validate = vcn_zipcode_validation(zipcode);
					if (!zip_validate) {
						zip_error = true;
					}
				}
				if (career == '') {
					career_error = true;
				}
				
				if (career_error && zip_error) {
					error_message = 'Please select a desired career from the list.<br/>Please enter a valid US ZIP Code.';
					$('#edit-vcn-user-selections-zipcode').addClass('error');
					$('#edit-vcn-user-selections-career').addClass('error');
				} else if (career_error) {
					error_message = 'Please select a desired career from the list.';
					$('#edit-vcn-user-selections-career').addClass('error');
				} else if(zip_error) {
					error_message = 'Please enter a valid US ZIP Code.';
					$('#edit-vcn-user-selections-zipcode').addClass('error');
				}
				
				if (zip_error || career_error) {
					custom_error_display(error_message);
					return false;
				}
			});
			
			$('#vcn-user-selections-edu-level-help-image').hover().css({'cursor': 'pointer'});
			
			$('img#vcn-user-selections-edu-level-help').click(function(){
				$('#vcn-user-selections-edu-level-note').toggle();
			});
			
		}
	};
	
	
	function vcn_user_selection_common(onetcode, event) {
		if (onetcode != '') {
			$('#vcn-user-selections-edu-level-help-image').show();
			$('#edit-vcn-user-selections-edu-level').attr('disabled', false);
			
			var min_edu_result; 
			var min_edu_note;
			
			min_edu_result = vcn_calculate_min_education_for_career(onetcode);
			
			$('#edit-vcn-user-selections-edu-level > option').each(function(){
				if (this.value < min_edu_result.min_education_id) {
					$(this).attr('disabled', true);
				} else {
					$(this).attr('disabled', false);
				}
			});
			
			var sel_val;
			if (event == 'change') {
				// on change event of career list value, set the edcuation level select list to the minimum education required for that particular career
				//sel_val = min_edu_result.min_education_id;
				// previous line is commented out and below line of code is added to comply with redmine ticket #11092
				sel_val = 99; // 99 == 'Highest Available'
			} else {
				// event == 'load'
				// if award level cookie is set and if it is greater than minimum education required for a particular career, make the education lists selected value = cookie
				// for eg: If user selects "Acute Care Nurse" and edu level of "Masters Degree" and submits the form, we get the results for "Masters Degree" 
				//but the edu level gets set back to "Associated Degree" on page load since it is the min education for "Acute Care Nurse". hence this step is important to keep "Masters Degree" selected
				
				if (($.cookie('vcnuser_awlevel')) && ($.cookie('vcnuser_awlevel') >= min_edu_result.min_education_id)) {
					sel_val = $.cookie('vcnuser_awlevel');
				} else {
					//sel_val = min_edu_result.min_education_id;
					// previous line is commented out and below line of code is added to comply with redmine ticket #11092
					sel_val = 99; // 99 == 'Highest Available'
				}
			}
			$('#edit-vcn-user-selections-edu-level').val(sel_val);
			
			min_edu_note = vcn_edit_minimum_education_note(min_edu_result);
			$('#vcn-user-selections-edu-level-note').html(min_edu_note);
		} else {
			$('#vcn-user-selections-edu-level-help-image').hide();
			$('#edit-vcn-user-selections-edu-level').attr('disabled', true);
		}
	}
	
	
	function vcn_calculate_min_education_for_career(onetcode) {
		var min_edu_result = null; 
		$.ajax({
			url: vcn_get_drupal7_base_path()+'calculate-min-education/onetcode/'+onetcode,
			success: function(result) { 
				min_edu_result = result;											
			},
			error: function() {
				min_edu_result = null;
			},
			async: false
		});
		
		if (min_edu_result) {
			min_edu_result = $.parseJSON(min_edu_result);
		}
		return min_edu_result;
	}
	
	function vcn_edit_minimum_education_note(min_edu_result) {
		var note;
		if (min_edu_result && min_edu_result.min_education_id) {
			note = 'Most people in this career have <span class="strong">' + min_edu_result.typical_education_name + '</span>. Set or change your Highest Education Level to Pursue to fit the time (and money) that you can afford to spend on education and training.';
		} else {
			note = 'No data found for the career.';
		} 
		
		return note;
	}
	
	
})(jQuery);