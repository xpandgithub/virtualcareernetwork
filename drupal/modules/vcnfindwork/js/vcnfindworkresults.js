/*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ 


(function($) {
	Drupal.behaviors.vcn_findwork_results = {
		attach: function(context, settings) {
			var image_base_path = vcn_get_images_basepath();
			var base_path = vcn_get_drupal7_base_path();
			
			var job_results = Drupal.settings.vcnfindworkresults.job_results;
			var aaData = $.parseJSON(job_results);

			$('#vcnfindwork-results-table').dataTable({
				"sPaginationType": "full_numbers",
				"bFilter": false,
				"bInfo": false,
				"bLengthChange": false,
				"bDeferRender": true,
				"aaData": aaData,
				"bAutoWidth": false,
				"aoColumns": [
				              {"sWidth": "40%", "mData":"job_title"}, 
				              {"swidth": "35%", "mData": "company"}, 
				              {"sWidth": "20%", "mData": "location"}, 
				              {"sWidth": "5%", "mData":"date_acquired", "sType": "date"}
				             ],
				"aaSorting": [[3, "desc"]],
				"fnRowCallback": function( nRow, aData, iDisplayIndex ) {
					$('td:eq(0)', nRow).html('<a target="_blank" href="'+aData.job_url+'">'+aData.job_title+'</a>');

				},
				"fnDrawCallback": function(oSettings) {
	                if (oSettings._iDisplayLength >= oSettings.fnRecordsDisplay()) {
	                   jQuery(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
	                }
	           }
			});
			
			
			
			$('#vcn-findwork-results-form').submit(function(){
				return vcn_validate_findwork_forms($('#edit-zipcode'), $('#edit-careers'), $('#edit-search-by-job-title'));
			});
			
			$('#vcnfindwork-results-feedback-link').click(function(e){
				
				var input_params = vcn_findwork_results_get_input_params();
				var zipcode = input_params.zipcode;
				var distance =input_params.distance;
				var onetcode = input_params.onetcode;
				var keyword = input_params.keyword;
				var career_title = input_params.career_title;
				var url = base_path+'findwork-results-feedback/';
				
				if (keyword) {
					//this code is used to make sure we preserve the proper data if there are "/" and "\" in keyword, "/" is delimiter for Drupal and for some reason "\" does not work if we do not replace it
					keyword = keyword.replace("/","~");
					keyword = keyword.replace("\\","*");
					url += 'search-term/'+keyword;
				} else {
					url += 'career/'+onetcode+'/career-title/'+career_title;
				}
				
				if (zipcode) {
					url += '/zip/'+zipcode+'/distance/'+distance;
				}
				
				jQuery('.alert').css("display","none");
				//this is needed because otherwise if a user clicks on the feedback link more than once, we start to get multiple divs with the same message
				$('#vcnfindwork-results-user-feedback-message').remove();
				$('#main-wrapper').prepend('<div id="vcnfindwork-results-user-feedback-message" class="alert alert-info"></div>');
				
				var sec = 15;
				$.ajax({
					url: url,
					dataType: 'json',
					beforeSend: function () {
						$('#vcnfindwork-results-user-feedback-message')
						.html('Saving your feedback <img src="'+image_base_path+'miscellaneous/circle-loader.gif" alt="circle loader"/>');
						
						$('html, body').animate({
						    scrollTop: jQuery("body").offset().top
						}, 500);
					},
	 		   		success: function(result) {
	 		   			if (result !== null && result.feedback === true) { 
	 		   				$('#vcnfindwork-results-user-feedback-message').removeClass('alert-error')
	 		   				.html("Thank you for your feedback. " +
	 		   						"The VCN Team is always looking for ways to improve the user experience. " +
	 		   						"We invite you to use the 'Feedback' link at the bottom of the page to let us know if you have any " +
	 		   						"comments or suggestions regarding the VCN's 'Find a Job' feature. Thank You.");
	 		   				sec = 15;
	 		   			} else {
	 		   				$('#vcnfindwork-results-user-feedback-message').removeClass('alert-info').addClass('alert-error').html("Could not save your feedback."); 
	 		   				sec = 5;
	 		   			}
					 },
					 error: function() {
						 $('#vcnfindwork-results-user-feedback-message').removeClass('alert-info').addClass('alert-error').html('Could not save your feedback.'); 
						 sec = 5;
					 },
					 complete: function() {
						 $('#vcnfindwork-results-user-feedback-message').append("<br/><br/>This message will close in <span>"+sec+"</span> secs");
					 }
				});
				var timer = 15;;
				timer = setInterval(function() { 
				   $('#vcnfindwork-results-user-feedback-message span').text(sec--);
				   if (sec == -1) {
				      $('#vcnfindwork-results-user-feedback-message').fadeOut(1000);
				      clearInterval(timer);
				   } 
				}, 1000);
				
				e.preventDefault();
			});
			
			
			$('#vcnfindwork-save-job-image img#save-to-job-scout').click(function(e){			
				
				var input_params = vcn_findwork_results_get_input_params();
				var zipcode = input_params.zipcode;
				var distance =input_params.distance;
				var onetcode = input_params.onetcode;
				var keyword = input_params.keyword;
								
				if (!(keyword  || onetcode)) {	// Do quick check for search input fields.		 
					error_message = 'Please enter a search term OR select a career from the list.';
					$('#edit-careers').addClass('error');
					$('#edit-search-by-job-title').addClass('error');					
					custom_error_display(error_message);
					
					return false;
				}
				
				
				//var user_id = $('input[name="drupalu"]').val(); see if this code is required in the module, else remove it
				var base_path = vcn_get_drupal7_base_path();
				
				jQuery('.alert').css("display","none");
				$('#vcnfindwork-results-user-feedback-message').remove();
				$('#main-wrapper').prepend('<div id="vcnfindwork-results-user-feedback-message" class="alert alert-info"></div>');
				$('#vcnfindwork-results-user-feedback-message')
				.html('Please wait while we save your data <img src="'+image_base_path+'miscellaneous/circle-loader.gif" alt="circle loader"/>');
				
				$('html, body').animate({
				    scrollTop: jQuery("body").offset().top
				}, 500);
				
				//this code is used to make sure we preserve the proper data if there are "/" and "\" in keyword, "/" is delimiter for Drupal and for some reason "\" does not work if we do not replace it
				if (keyword) {
					keyword = keyword.replace("/","~");
					keyword = keyword.replace("\\","*");
				}
				
				var url = base_path+'cma/save-jobsearch/'+zipcode+'/'+distance+'/'+onetcode+'/'+keyword;
				$.ajax({
					type: "POST",
					url: url,
					dataType: "json",
					success: function(data) {
						if (data.execution == true) {
							if (data.job_count_status == true) {
								$('#vcnfindwork-results-user-feedback-message').removeClass('alert-error').addClass('alert-info').html('Your job search criteria has been saved as a Job Scout in your MyVCN Account.');
							} else {
								$('#vcnfindwork-results-user-feedback-message').removeClass('alert-info').addClass('alert-error').html('You already have 5 Job Scouts saved to your MyVCN Account. Please delete one before adding a new one.');
							}
						} else {
							$('#vcnfindwork-results-user-feedback-message').removeClass('alert-info').addClass('alert-error').html('An error occured while saving the data');
						}
					},
					error: function(data) {
						$('#vcnfindwork-results-user-feedback-message').removeClass('alert-info').addClass('alert-error').html('An error occured while saving the data');
					}
				});
			    e.preventDefault();
			});
		}
	};
	
	function vcn_findwork_results_get_input_params() {
		var zip = ($('#edit-zipcode').val() == "") ? null : $('#edit-zipcode').val();
		var distance = $('#edit-distance').val();
		var onetcode = ($('#edit-careers').val() == "") ? null : $('#edit-careers').val();
		var keyword = ($('#edit-search-by-job-title').val() == "") ? null : $('#edit-search-by-job-title').val();
		var career_title = (onetcode == null) ? null : $('#edit-careers option:selected').text();
		
		var params_array = {"zipcode": zip, "distance": distance, "onetcode": onetcode, "keyword": keyword, "career_title": career_title};
		return params_array;
	}
})(jQuery);