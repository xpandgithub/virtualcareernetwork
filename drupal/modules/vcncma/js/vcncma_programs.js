/*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ 


(function($) {
	Drupal.behaviors.vcncma_programs = {
		attach: function(context, settings) {
			var basepath = vcn_get_drupal7_base_path();
			var programs_data = Drupal.settings.vcncma_programs.programs_data;
                        var councelor_viewing_student_data = Drupal.settings.vcncma_programs.councelor_viewing_student_data;
			var user_not_in_save_mode = Drupal.settings.vcncma_programs.user_not_in_save_mode;
                        var aaData = $.parseJSON(programs_data);
			var imagepath = vcn_get_images_basepath();
			isUserLoggedIn = Drupal.settings.vcncma.isUserLoggedIn;
			userid = Drupal.settings.vcncma.userid;
			
			oTable = $('#cma-programs-listing').dataTable({
				"sPaginationType": "full_numbers",
				"bFilter": false,
				"bInfo": false,
				"bLengthChange": false,
				"aaData": aaData,
				"aaSorting": [[ 3, "asc" ]],
				"aoColumns": [{"sWidth":"5%","mData":null,"bSortable": false},{"sWidth":"30%","mData":"programname"},{"sWidth":"35%","mData":"instname"},{"sWidth":"20%","mData":"educationcategoryid"},{"sWidth":"10%","mData":null,"bSortable": false}],
				"bAutoWidth": false,
				"bStateSave": false,
				"bDeferRender": true,
				"fnDrawCallback": function(oSettings) {
	                if (oSettings._iDisplayLength >= oSettings.fnRecordsDisplay()) {
	                   jQuery(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
	                }
				},
				"fnRowCallback": function( nRow, aData, iDisplayIndex ) {
					var targeted_program = '';
					var targetbutton = '';
                    var targeticon = '';
					if (aData.itemrank == 1) {
						targeticon = ' <img src="'+imagepath+'buttons/target_icon.png" style="vertical-align:middle;" title="Targeted Program" alt="Targeted Program"/> ';
					}
                    if (aData.itemrank == 1 || (councelor_viewing_student_data == true && user_not_in_save_mode == true)) {
                      targetbutton = '<span class="datatable-save-target">'+			            		
                                         '<button title=\"Targeted Program\" class="vcn-button grid-action-button vcn-button-disable" >Target</button>'+			            		
                                         '</span>' ;
                    } else {
                      targetbutton = '<span class="datatable-save-target">'+			            		
                                         '<button title=\"Make this My Target\" class="vcn-button grid-action-button vcn-red-button" onclick="vcnSaveTarget(\''+basepath+'cma/ajax/save-target-notebook-item/target/program/'+aData.itemid+'/'+aData.subitemid+'/'+aData.cipcode+'\',\'program\',\'target\',\''+userid+'\',\''+isUserLoggedIn+'\',\''+aData.subitemid+'\'); return false;" onkeypress="vcnSaveTarget(\''+basepath+'cma/ajax/save-target-notebook-item/target/program/'+aData.itemid+'/'+aData.subitemid+'/'+aData.cipcode+'\',\'program\',\'target\',\''+userid+'\',\''+isUserLoggedIn+'\',\''+aData.subitemid+'\'); return false;" >Target</button>'+			            		
                                         '</span>' ;
                    }
					
					$('td:eq(0)', nRow).html('<center><nobr>'+targeticon+'</nobr></center>');
					
					$('td:eq(2)', nRow).html('<a href="'+basepath+'get-qualified/school/'+aData.unitid+'"><strong>'+aData.instname+'</strong></a>'+
							'<br/>'+aData.instaddress+'<br/>'+aData.instcity+', '+aData.inststateabbrev+' '+aData.instzip+'<br/>'+aData.instgeneraltelephone+'<br/>'+
							'<a href="'+basepath+'get-qualified/financialaid/'+aData.unitid+'"><span class="programs-smallfont">Financial Aid</span></a>');
					
					$('td:eq(3)', nRow).html(aData.educationcategoryname);
					
                    if (councelor_viewing_student_data == true && user_not_in_save_mode == true) {
                      $('td:eq(1)', nRow).html('<strong>'+aData.programname+'</strong>');
                      $('td:eq(4)', nRow).html('<center><nobr>'+targetbutton+
                                      '<br/><input type="button" title="Delete" value="Delete" class="vcn-button grid-action-button vcn-button-disable" name="'+aData.notebookid+'" />'+
                                      '</nobr></center>');
                    } else {
                      $('td:eq(1)', nRow).html('<a href="'+basepath+'get-qualified/program/'+aData.itemid+'/cipcode/'+aData.cipcode+'/onetcode/'+aData.subitemid+'">'+targeted_program+'<strong>'+aData.programname+'</strong></a>');
                      $('td:eq(4)', nRow).html('<center><nobr>'+targetbutton+
                                      '<br/><input type="button" title="Delete" value="Delete" class="cma-programs-delete-this-row vcn-button grid-action-button" name="'+aData.notebookid+'" />'+
                                      '</nobr></center>');
                    }
				}
			});
			
			$('#cma-programs-listing .cma-programs-delete-this-row').live('click', function(e) {	 
				
				//e.preventDefault();				
				var dtCurrentRow = $(this).parents('tr')[0];
				var itemNotebookId = $(this).attr("name");				
							
				cmaOptions = new Object();
				cmaOptions.dtCurrentRow = dtCurrentRow;
				cmaOptions.itemNotebookId = itemNotebookId;				
				cmaOptions.itemType = 'program';
				cmaOptions.itemTypeText = 'Program';							
				
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