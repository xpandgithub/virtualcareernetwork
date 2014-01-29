/*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ 


(function($) {
	Drupal.behaviors.vcncma_local_jobs = {
		attach: function(context, settings) {

			var basepath = vcn_get_drupal7_base_path();
			var local_jobs_data = Drupal.settings.vcncma_local_jobs.local_jobs_data;
			var councelor_viewing_student_data = Drupal.settings.vcncma_local_jobs.councelor_viewing_student_data;
                        var aaData = $.parseJSON(local_jobs_data);
			
			oTable = $('#cma-local-jobs-listing').dataTable({
				"sPaginationType": "full_numbers",
				"bFilter": false,
				"bInfo": false,
				"bLengthChange": false,
				"aaData": aaData,
				"aaSorting": [[0,"asc"]],
				"aoColumns": [
				              {"sWidth": "28%", "mData": "job_title"},
				              {"sWidth": "28%", "mData": "employer_name"},
				              {"sWidth": "28%", "mData": "contact_name"},
				              {"sWidth": "16%", "bSortable": false, "mData": null}
				             ],
				"bAutoWidth": false,
				"bStateSave": false,
				"bDeferRender": true,
				"fnDrawCallback": function(oSettings) {
	                if (oSettings._iDisplayLength >= oSettings.fnRecordsDisplay()) {
	                   jQuery(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
	                }
				},
				"fnRowCallback": function( nRow, aData, iDisplayIndex ) {
					
					var job_title='';
					var employer_name='';
					var contact_phone='';
					var contact_email='';
					
					job_title = aData.job_title;
					employer_name = aData.employer_name;
					
					if (aData.contact_phone) {
						//contact_phone = aData.contact_phone.replace(/(\d\d\d)(\d\d\d)(\d\d\d\d)/, "($1) $2-$3");
						contact_phone = '<br/>'+aData.contact_phone;
					}
					
					if (aData.contact_email) {
						contact_email = '<br/>'+aData.contact_email;
					}
					
                                        if (councelor_viewing_student_data == true) {
                                          var action_button = '<input type="button" class="cma-local-jobs-edit vcn-button grid-action-button vcn-button-disable" value="Edit" title="Edit" name="'+basepath+'cma/local-jobs/edit/'+aData.local_job_id+'" />' + 
                                                              '<input type="button" class="cma-delete-this-row vcn-button grid-action-button vcn-button-disable" value="Delete" title="Delete" name="'+aData.local_job_id+'"/>';
                                        } else {
                                          var action_button = '<input type="button" class="cma-local-jobs-edit vcn-button grid-action-button" value="Edit" title="Edit" name="'+basepath+'cma/local-jobs/edit/'+aData.local_job_id+'" />' + 
                                                              '<input type="button" class="cma-delete-this-row vcn-button grid-action-button" value="Delete" title="Delete" name="'+aData.local_job_id+'"/>';
                                        }
                                        
					$('td:eq(0)', nRow).html(job_title);
					$('td:eq(1)', nRow).html(employer_name);
					$('td:eq(2)', nRow).html(aData.contact_name+contact_phone+contact_email);
					$('td:eq(3)', nRow).html('<center>'+action_button+'</center>');
				}
			});
			
			
			
			$('.cma-local-jobs-add').live('click', function(e) {
				var addurl = $(this).attr("name");
				document.location.href = addurl;
			});
			
			$('#cma-local-jobs-listing .cma-local-jobs-edit').live('click', function(e) {
                          if (!$(this).hasClass('vcn-button-disable')) {
				var editurl = $(this).attr("name");
				document.location.href = editurl;	
                          }
			});
			
			
			/*$(".dataTable tbody").click(function(event) {
				$(oTable.fnSettings().aoData).each(function (){
					$(this.nTr).removeClass('row_selected');
				});
				$(event.target.parentNode).addClass('row_selected'); // table.dataTable tr.row_selected  table.dataTable tr.row_selected td.sorting_1
			});	*/
			
			
			$('#cma-local-jobs-listing input.cma-delete-this-row').live('click', function(e) {
                          if (!$(this).hasClass('vcn-button-disable')) {
				var local_job_id = $(this).attr('name');
				
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
						      url: drupal7_basepath+'cma/remove-local-job/ajax/'+local_job_id, 
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
			
			
			
		}
	};  
	 
})(jQuery);