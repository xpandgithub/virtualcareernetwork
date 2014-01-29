/*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ 


(function($) {
	Drupal.behaviors.vcncma_network_contacts = {
		attach: function(context, settings) {

			var basepath = vcn_get_drupal7_base_path();
			var network_contacts_data = Drupal.settings.vcncma_network_contacts.network_contacts_data;
			var councelor_viewing_student_data = Drupal.settings.vcncma_network_contacts.councelor_viewing_student_data;
                        var aaData = $.parseJSON(network_contacts_data);
			
			oTable = $('#cma-network-contacts-listing').dataTable({
				"sPaginationType": "full_numbers",
				"bFilter": false,
				"bInfo": false,
				"bLengthChange": false,
				"aaData": aaData,
				"aaSorting": [[0,"asc"]],
				"aoColumns": [
				              {"sWidth": "35%", "mData": "first_name"},
				              {"sWidth": "50%", "mData": "company_name"},
				              {"sWidth": "15%", "bSortable": false, "mData": null}
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
                                        var company_name='';
                                        var company_title='';
					var phone_work='';
					var phone_mobile='';
					var email='';
                                        
                                        if (aData.company_name) {
						company_name = aData.company_name;
					}
                                        if (aData.company_title) {
						company_title = '<br/>'+aData.company_title;
					}
                                        if (aData.phone_work) {
						phone_work = '<br/>'+aData.phone_work+' (w)';
					}
					if (aData.phone_mobile) {
						phone_mobile = '<br/>'+aData.phone_mobile+' (m)';
					}
					if (aData.email) {
						email = '<br/>'+aData.email;
					}
                                        
                                        if (councelor_viewing_student_data == true) {
                                          var action_button = '<input type="button" class="cma-network-contacts-edit vcn-button grid-action-button vcn-button-disable" value="Edit" title="Edit" name="'+basepath+'cma/network-contacts/edit/'+aData.user_contact_id+'" />' + 
                                                              '<input type="button" class="cma-delete-this-row vcn-button grid-action-button vcn-button-disable" value="Delete" title="Delete" name="'+aData.user_contact_id+'"/>';
                                        } else {
                                          var action_button = '<input type="button" class="cma-network-contacts-edit vcn-button grid-action-button" value="Edit" title="Edit" name="'+basepath+'cma/network-contacts/edit/'+aData.user_contact_id+'" />' + 
                                                              '<input type="button" class="cma-delete-this-row vcn-button grid-action-button" value="Delete" title="Delete" name="'+aData.user_contact_id+'"/>';
                                        }
                                        
					$('td:eq(0)', nRow).html(aData.first_name + ' ' + aData.last_name + company_title);
					$('td:eq(1)', nRow).html(company_name + phone_work + phone_mobile + email);
					$('td:eq(2)', nRow).html('<center>'+action_button+'</center>');
				}
			});
			
			$('.cma-network-contacts-add').live('click', function(e) {
				var addurl = $(this).attr("name");
				document.location.href = addurl;							
			});
			
			$('#cma-network-contacts-listing .cma-network-contacts-edit').live('click', function(e) {
                          if (!$(this).hasClass('vcn-button-disable')) {
				var editurl = $(this).attr("name");
				document.location.href = editurl;
                          }
			});
			
			$('#cma-network-contacts-listing input.cma-delete-this-row').live('click', function(e) {
                          if (!$(this).hasClass('vcn-button-disable')) {
				var network_contact_id = $(this).attr('name');
				
				var drupal7_basepath = vcn_get_drupal7_base_path();
				var nRow = $(this).parents('tr')[0];
				
				var modalDiv = $(document.createElement('div')); 
				var title = 'Remove Contact';
				var imgHtml = '<img src="' + vcn_get_images_basepath() + 'buttons/info-icon.png" width="42" alt="info icon" id="simple-modal-icon-info">';
				var msg = 'Are you sure you want to remove selected contact from your VCN Account?';
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
						      url: drupal7_basepath+'cma/remove-network-contact/ajax/'+network_contact_id, 
						      cache: false,
						      dataType: "text"
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
				      $("#btn-ok").focus();
				    }
				  });
                          }
			});
			
			
			
		}
	};  
	 
})(jQuery);