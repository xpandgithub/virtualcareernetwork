/*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ 


(function($) {
	Drupal.behaviors.vcncma_job_scouts = {
		attach: function(context, settings) {
			
			var basepath = vcn_get_drupal7_base_path();
			var job_scouts_data = Drupal.settings.vcncma_job_scouts.job_scouts_data;
                        var councelor_viewing_student_data = Drupal.settings.vcncma_job_scouts.councelor_viewing_student_data;
                        var aaData = $.parseJSON(job_scouts_data);
			
			oTable = $('#cma-job-scouts-listing').dataTable({
				"sPaginationType": "full_numbers",
				"bFilter": false,
				"bInfo": false,
				"bLengthChange": false,
				"aaData": aaData,
				"aaSorting": [[ 3, "desc" ]],
				"bAutoWidth": false,
				"bDeferRender": true,
				"aoColumns": [
				              {"sWidth": "45%", "sType": "html", "mData":"sort_title"},
				              {"sWidth": "10%", "sType": "numeric", "mData":"zipcode"},
				              {"sWidth": "5%", "sType": "numeric", "mData":"distance"},
				              {"sWidth": "15%", "mData":"datesaved"},
				              {"sWidth": "10%", "bSortable": false,"mData":null},
				              {"sWidth": "15%", "bSortable": false, "mData":null}
				             ],
				"fnDrawCallback": function(oSettings) {
	                if (oSettings._iDisplayLength >= oSettings.fnRecordsDisplay()) {
	                   $(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
	                }
	           },
	           "fnRowCallback": function( nRow, aData, iDisplayIndex ) {
	        	   var job_search_title = '';
	        	   var jobsbutton = '';
	        	   var jobslink = '';
	        	   if (aData.onetcode) {
	        		   jobslink = basepath+'findwork-results/career/'+ aData.onetcode;
	        		   if (aData.zipcode) {
	        			   jobslink += '/zip/' + aData.zipcode;
	        			   if (aData.distance) {
	        				   jobslink += '/distance/' + aData.distance;
	        			   }
	        		   }	        		   
	        		   
	        		   job_search_title = aData.industry_name+' Career: <span class="strong">'+ aData.title + '</span>';
						
	        	   } else {	        		   
	        		   jobslink = basepath+'findwork-results/search-term/'+ aData.keyword_url;
	        		   if (aData.zipcode) {
	        			   jobslink += '/zip/' + aData.zipcode;
	        			   if (aData.distance) {
	        				   jobslink += '/distance/' + aData.distance;
	        			   }
	        		   }
	        		   	        		   
	        		   job_search_title = 'Job Search keyword: <span class="strong">'+ aData.keyword + '</span>';
	        	   }
	        	   
                           if (councelor_viewing_student_data) {
                             jobsbutton = '<input type="button" title="Jobs" value="Jobs" class="cma-jobscouts-jobs vcn-button grid-action-button vcn-button-disable" name="'+jobslink+'" />';
                           } else {
                             jobsbutton = '<input type="button" title="Jobs" value="Jobs" class="cma-jobscouts-jobs vcn-button grid-action-button" name="'+jobslink+'" />';
                           }
                           
	        	   var zipcode = '';
	        	   if (aData.zipcode) {
	        		   zipcode = aData.zipcode;
	        	   } else {
	        		   zipcode = '-';
	        	   }
	        	   
	        	   var distance = '';
	        	   if (aData.distance) {
	        		   distance = aData.distance;
	        	   } else {
	        		   distance = '-';
	        	   }
	        	   
                           if (councelor_viewing_student_data == true) {
                             var action_button = '<input type="button" class="cma-delete-this-row vcn-button grid-action-button vcn-button-disable" value="Delete" title="Delete" name="'+aData.jobscoutid+'"/>'+
                                                 '<input type="button" class="cma-subscribe-unsubscribe vcn-button grid-action-button vcn-button-disable" value="'+aData.subscribe_unsubscribe+'" title="'+aData.subscribe_unsubscribe+'" name="'+aData.jobscoutid+'" />';
                           } else {
                             var action_button = '<input type="button" class="cma-delete-this-row vcn-button grid-action-button" value="Delete" title="Delete" name="'+aData.jobscoutid+'"/>'+
                                                 '<input type="button" class="cma-subscribe-unsubscribe vcn-button grid-action-button" value="'+aData.subscribe_unsubscribe+'" title="'+aData.subscribe_unsubscribe+'" name="'+aData.jobscoutid+'" />';
                           }
	        	   
	        	   $('td:eq(0)', nRow).html(job_search_title);
	        	   $('td:eq(1)', nRow).html('<center><nobr>'+zipcode+'</nobr></center>');
	        	   $('td:eq(2)', nRow).html('<center><nobr>'+distance+'</nobr></center>');
	        	   $('td:eq(3)', nRow).html('<center><nobr>'+aData.datesaved+'</nobr></center>');
	        	   $('td:eq(4)', nRow).html('<center><nobr>'+jobsbutton+'</nobr></center>');
	        	   $('td:eq(5)', nRow).html('<center><nobr>'+action_button+'</nobr></center>');
	           }
			});
			
			$('#cma-job-scouts-listing .cma-jobscouts-jobs').live('click', function(e) {
                          
                          var btnflag = $(this).hasClass("vcn-button-disable");
				
			  if(btnflag == false) {
				var jobsurl = $(this).attr("name");
				document.location.href = jobsurl;
                          }
			});
			
			$('#cma-job-scouts-listing input.cma-delete-this-row').live('click', function(e) {
                          if (!$(this).hasClass('vcn-button-disable')) {
				var job_scout_id = $(this).attr('name');
				
				var isUserLoggedIn = Drupal.settings.vcncma.isUserLoggedIn;
				var drupal7_basepath = vcn_get_drupal7_base_path();
				
				var nRow = $(this).parents('tr')[0];
				
				var modalDiv = $(document.createElement('div')); 
				var title = 'Remove Job';
				var imgHtml = '<img src="' + vcn_get_images_basepath() + 'buttons/info-icon.png" width="42" alt="info icon" id="simple-modal-icon-info">';
				var msg = 'Are you sure you want to remove selected job from your VCN Account?';
				$(modalDiv).html('<p>' + imgHtml + msg + '</p>');
				$(modalDiv).dialog({
				    resizable: false,
				    title: title,
				    modal: true,
				    buttons: {
				      "Ok": {
				        "class": "vcn-button",
				        id: "btn-ok",
				        text: "Yes",
				        click: function() { 
				        	$(this).dialog( "close" );				          
				          	oTable.fnDeleteRow(nRow);							
							$.ajax({
						      url: drupal7_basepath+'cma/remove-job/ajax/'+job_scout_id, 
						      cache: false,
						      //async: false,
						      dataType: "text",
						      success: function(data) {					    	  
						    	  if (!isUserLoggedIn) {	
							          //displayNotLoggedInModal('Career Saved temporarily in your wish list.', 'Save Career');
						    		  //displaySimpleModal('Selected job is removed from your wish list.', 'info', 'Remove Job');
							      } else {
							          //displaySimpleModal('Selected job is removed from Career Management Account.', 'info', 'Remove Job');
							      }					    	  
						      },
						      error: function(data) { 
						    	// Log to file
						      }
						    });	//End of AJAX call			          
				        }
				      },
				      "Cancel": {
				        "class": "vcn-button",
				        text: "No",
				        click: function() { 
				          $(this).dialog( "close" );					              
				        }
				      }
				    },
				    open: function(event, ui) {
				      //jQuery(":button:contains('Ok')").focus();
				      $("#btn-ok").focus();
				    }
				  });
                          }
			});
			
			$('#cma-job-scouts-listing input.cma-subscribe-unsubscribe').live('click', function(e) {
                          if (!$(this).hasClass('vcn-button-disable')) {
                                var job_scout_id = $(this).attr('name');
				var drupal7_basepath = vcn_get_drupal7_base_path();
				var clicked_button = $(this);
				
				var subscription_status = clicked_button.attr('value');
				if (subscription_status == 'Unsubscribe') {
					subscription_status = 'Subscribe';
				} else {
					subscription_status = 'Unsubscribe';
				}
				
				$.ajax({
					url: drupal7_basepath+'cma/jobscout-update-subscription/ajax/'+job_scout_id,
					cache: false,
					dataType: "text",
					success: function(data) {
						if (subscription_status == 'Unsubscribe') {
							displaySimpleModal('You have successfully subscribed to getting emails for this job.', 'info', 'Update Job subscription');
						} else {
							displaySimpleModal('You have successfully unsubscribed from getting emails for this job.', 'info', 'Update Job subscription');
						}
						
						clicked_button.val(subscription_status);
					},
					error: function(data) {
						
					}
				});
                          }
			});			
		}
	};
})(jQuery);