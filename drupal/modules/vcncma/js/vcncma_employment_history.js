/*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ 


(function($) {
	Drupal.behaviors.vcncma_employment_history = {
		attach: function(context, settings) {
			
			var basepath = vcn_get_drupal7_base_path();
			var employment_history_data = Drupal.settings.vcncma_employment_history.employment_history_data;
			var councelor_viewing_student_data = Drupal.settings.vcncma_employment_history.councelor_viewing_student_data;
                        var aaData = $.parseJSON(employment_history_data);
			
			oTable = $('#cma-employment-history-listing').dataTable({
				"sPaginationType": "full_numbers",
				"bFilter": false,
				"bInfo": false,
				"bLengthChange": false,
				"aaData": aaData,
				"aaSorting": [[3,"desc"],[2,"desc"]],
				"aoColumns": [
				              {"sWidth": "30%","mData":"employername"},
				              {"sWidth": "30%","mData":"jobtitle"},
				              {"sWidth": "13%","mData":"startdate", "sClass": "center"},
				              {"sWidth": "13%","mData":"enddate_sorting", "sClass": "center"},
				              {"sWidth": "14%", "bSortable": false,"mData":"useremploymentid"}
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
							
					
                                        if (councelor_viewing_student_data == true) {
                                          var deletebutton = '<input type="button" title="Delete" value="Delete" class="vcn-button grid-action-button vcn-button-disable" name="'+aData.useremploymentid+'" />';
                                          var editbutton = '<input type="button" title="Edit" value="Edit" class="vcn-button grid-action-button vcn-button-disable" name="'+basepath+'cma/employment-history/'+aData.useremploymentid+'/edit" />';
                                        } else {
                                          var deletebutton = '<input type="button" title="Delete" value="Delete" class="cma-employment-history-delete-this-row vcn-button grid-action-button" name="'+aData.useremploymentid+'" />';
                                          var editbutton = '<input type="button" title="Edit" value="Edit" class="cma-employment-history-edit vcn-button grid-action-button" name="'+basepath+'cma/employment-history/'+aData.useremploymentid+'/edit" />';
                                        }
                                        
					$('td:eq(3)', nRow).html(aData.enddate_display);
					$('td:eq(4)', nRow).html('<center><nobr>' + editbutton + ' ' + deletebutton + '</nobr></center>');
				}
			});
			
			
			
			$('.cma-employment-history-add').live('click', function(e) {
				var addurl = $(this).attr("name");
				document.location.href = addurl;							
			});
			
			$('#cma-employment-history-listing .cma-employment-history-edit').live('click', function(e) {
                          if (!$(this).hasClass('vcn-button-disable')) {
				var editurl = $(this).attr("name");
				document.location.href = editurl;	
                          }
			});		
			
			$('#cma-employment-history-listing .cma-employment-history-delete-this-row').live('click', function(e) {
			  if (!$(this).hasClass('vcn-button-disable')) {
				var useremploymentid = $(this).attr("name");
				var drupal7_basepath = vcn_get_drupal7_base_path(); //Drupal.settings.drupal_basepaths.drupal7_basepath;
							
                                //e.preventDefault();				
				var dtCurrentRow = $(this).parents('tr')[0];
				
				var modalDiv = jQuery(document.createElement('div')); 
				var title = 'Remove Employment History';
				var imgHtml = '<img src="' + vcn_get_images_basepath() + 'buttons/info-icon.png" width="42" alt="info icon" id="simple-modal-icon-info">';
				var msg = 'Are you sure you want to remove selected employment history from your VCN Account?';				
				
				jQuery(modalDiv).html('<p>' + imgHtml + msg + '</p>');
			  	jQuery(modalDiv).dialog({
				    resizable: false,
				    title: title,
				    modal: true,
				    buttons: {
				      "Ok": {
				        //"class": "vcn-button",
				        id: "btn-ok",
				        text: "Yes",
				        click: function() { 
				        	jQuery(this).dialog( "close" );				          
				          	
							oTable.fnDeleteRow(dtCurrentRow);							
							
						    jQuery.ajax({
						      url: drupal7_basepath+'cma/remove-employment-history/ajax/'+useremploymentid, 
						      cache: false,
						      //async: false,
						      dataType: "text",
						      success: function(data) {						    	  
							        //displaySimpleModal('Selected Employment history is removed from Career Management Account.', 'info', 'Remove Employment History');							      				    	  
						      },
						      error: function(data) { 
						    	// Log to file
						      }
						    });	//End of AJAX call			          
				        }
				      },
				      "Cancel": {
				        //"class": "vcn-button",
				        text: "No",
				        click: function() { 
				          jQuery(this).dialog( "close" );					              
				        }
				      }
				    },
				    open: function(event, ui) {
				      //jQuery(":button:contains('Ok')").focus();
				      jQuery("#btn-ok").focus();
					  					  
					  jQuery('.ui-dialog').each(function() {  
						jQuery(this).addClass("vcn-dialog");      
					  });
					 
				      if (window.PIE) {
				    	jQuery('.ui-button').each(function() {	    			
				            PIE.attach(this);		            
				        });
				      }  	
					},
					close: function() {        
				      jQuery('.ui-dialog').each(function() {  
						jQuery(this).removeClass("vcn-dialog");      
					  });
				    }
				  });	
                          }
			});
			
			$(".dataTable tbody").click(function(event) {
				$(oTable.fnSettings().aoData).each(function (){
					$(this.nTr).removeClass('row_selected');
				});
				$(event.target.parentNode).addClass('row_selected'); // table.dataTable tr.row_selected  table.dataTable tr.row_selected td.sorting_1
			});	
			
		}
	};  
	 
})(jQuery);