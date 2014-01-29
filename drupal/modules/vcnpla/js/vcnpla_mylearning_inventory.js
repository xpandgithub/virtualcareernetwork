/*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ 


(function($) {
	Drupal.behaviors.vcnpla_mylearning_inventory = {
		attach: function(context, settings) {
			
			oTable = $('#mylearning-inventory-listing').dataTable({
				"sPaginationType": "full_numbers",
				"bFilter": false,
				"bInfo": false,
				"bLengthChange": false,
				"aaSorting": [[ 1, "asc" ]],
				"bAutoWidth": false,
				"aoColumns": [{"sWidth": "72%", "sType": "html"}, {"swidth": "20%"}, {"sWidth": "8%", "bSortable": false}],
				"fnDrawCallback": function(oSettings) {
	                if (oSettings._iDisplayLength >= oSettings.fnRecordsDisplay()) {
	                   jQuery(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
	                }
	           }
			});	
			
			$('#mylearning-inventory-listing .mylearning-inventory-delete-this-row').live('click', function(e) {
				
				var usercourseid = $(this).attr("name");
				var drupal7_basepath = vcn_get_drupal7_base_path(); //Drupal.settings.drupal_basepaths.drupal7_basepath;
				isUserLoggedIn = Drupal.settings.vcnpla.isUserLoggedIn;
				userid = Drupal.settings.vcnpla.userid;
			
	        	//e.preventDefault();				
				var nRow = $(this).parents('tr')[0];
				
				var modalDiv = jQuery(document.createElement('div')); 
				var title = 'Remove Course';
				var imgHtml = '<img src="' + vcn_get_images_basepath() + 'buttons/info-icon.png" width="42" alt="info icon" id="simple-modal-icon-info">';
				var msg = 'Are you sure you want to remove selected course from your Learning Inventory?';
				
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
				          	
							oTable.fnDeleteRow(nRow);							
							
						    jQuery.ajax({
						      url: drupal7_basepath+'pla/remove-courses/ajax/'+usercourseid, 
						      cache: false,
						      //async: false,
						      dataType: "text",
						      success: function(data) {					    	  
						    	  if (!isUserLoggedIn) {	
							          //displayNotLoggedInModal('Career Saved temporarily in your wish list.', 'Save Career');
						    		  //displaySimpleModal('Selected Course is removed from your wish list.', 'info', 'Remove Course');
							      } else {
							          //displaySimpleModal('Selected Course is removed from Career Management Account.', 'info', 'Remove Course');
							      }					    	  
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