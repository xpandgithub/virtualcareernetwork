/*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ 


(function($) {
	Drupal.behaviors.vcncma_licenses = {
		attach: function(context, settings) {
			var basepath = vcn_get_drupal7_base_path();
			var licenses_data = Drupal.settings.vcncma_licenses.licenses_data;
                        var councelor_viewing_student_data = Drupal.settings.vcncma_licenses.councelor_viewing_student_data;
			var user_not_in_save_mode = Drupal.settings.vcncma_licenses.user_not_in_save_mode;
                        var aaData = $.parseJSON(licenses_data);
			
			oTable = $('#cma-licenses-listing').dataTable({
				"sPaginationType": "full_numbers",
				"bFilter": false,
				"bInfo": false,
				"bLengthChange": false,
				"aaData": aaData,
				"aaSorting": [[ 0, "asc" ]],
				"aoColumns": [{"sWidth":"60%","mData":"license_title"},{"sWidth":"30%","mData":"license_agency"},{"sWidth":"10%","mData":null,"bSortable": false}],
				"bAutoWidth": false,
				"bStateSave": false,
				"bDeferRender": true,
				"fnDrawCallback": function(oSettings) {
	                if (oSettings._iDisplayLength >= oSettings.fnRecordsDisplay()) {
	                   jQuery(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
	                }
				},
				"fnRowCallback": function( nRow, aData, iDisplayIndex ) {
					var targeted_license = '';
					if (aData.itemrank == 1) {
						targeted_license = '<span class="strong"> (Targeted License)</span>';
					}

					$('td:eq(0)', nRow).html('<a href="'+basepath+'get-qualified/license/'+aData.itemid+'/location/'+aData.license_state+'/onetcode/'+aData.onetcode+'"><strong>'+ aData.license_title + '</strong></a>' + 
							targeted_license + '<br/>' + aData.license_description);
					$('td:eq(1)', nRow).html(aData.license_agency);
                                        
                                        if (councelor_viewing_student_data == true && user_not_in_save_mode == true) {
                                          $('td:eq(2)', nRow).html('<center><nobr>'+
                                                          '<input type="button" title="Delete" value="Delete" class="vcn-button grid-action-button vcn-button-disable" name="'+aData.notebookid+'" />'+
                                                          '</nobr></center>');
                                        } else {
                                          $('td:eq(2)', nRow).html('<center><nobr>'+
                                                          '<input type="button" title="Delete" value="Delete" class="cma-licenses-delete-this-row vcn-button grid-action-button" name="'+aData.notebookid+'" />'+
                                                          '</nobr></center>');
                                        }
				}
			});
			
			$('#cma-licenses-listing .cma-licenses-delete-this-row').live('click', function(e) {	 
				
				//e.preventDefault();				
				var dtCurrentRow = $(this).parents('tr')[0];
				var itemNotebookId = $(this).attr("name");				
							
				cmaOptions = new Object();
				cmaOptions.dtCurrentRow = dtCurrentRow;
				cmaOptions.itemNotebookId = itemNotebookId;				
				cmaOptions.itemType = 'license';
				cmaOptions.itemTypeText = 'License';							
				
				vcnDeleteNotebookItemFromCMA(cmaOptions);			  
					
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