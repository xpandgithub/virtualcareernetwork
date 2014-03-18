/*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ 


(function($) {
	Drupal.behaviors.vcncma_careers = {
		attach: function(context, settings) {
			var basepath = vcn_get_drupal7_base_path();
            var imagepath = vcn_get_images_basepath();
			var careers_data = Drupal.settings.vcncma_careers.careers_data;
            var councelor_viewing_student_data = Drupal.settings.vcncma_careers.councelor_viewing_student_data;
            var user_not_in_save_mode = Drupal.settings.vcncma_careers.user_not_in_save_mode;
			var aaData = $.parseJSON(careers_data);
			isUserLoggedIn = Drupal.settings.vcncma.isUserLoggedIn;
			userid = Drupal.settings.vcncma.userid;
			
			oTable = $('#cma-careers-listing').dataTable({
				"sPaginationType": "full_numbers",
				"bFilter": false,
				"bInfo": false,
				"bLengthChange": false,
				"aaData": aaData,
				"aaSorting": [[4, "asc"],[1, "asc"]],
				"aoColumns": [
				              {"sWidth": "3%","bSortable": false,"mData":null},
				              {"sWidth": "41%","mData":"title"},
				              {"sWidth": "10%","mData":"pct25_annual", "sClass": "center"},
				              {"sWidth": "9%","mData":"pct25_hourly", "sClass": "center"},
				              {"sWidth": "20%","mData":"typicaleduid"},
				              {"sWidth": "10%", "bSortable": false,"mData":null},
				              {"sWidth": "7%", "bSortable": false,"mData":null}
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
					
					var annual_salary_pct25 = (aData.pct25_annual) ? accounting.formatMoney(aData.pct25_annual, '$', 0) : 'N/A';
					var annual_salary_pct75 = (aData.pct75_annual) ? accounting.formatMoney(aData.pct75_annual, '$', 0) : 'N/A';
					
					var hourly_wage_pct25 = (aData.pct25_hourly) ? accounting.formatMoney(aData.pct25_hourly, '$', 0) : 'N/A';
					var hourly_wage_pct75 = (aData.pct75_hourly) ? accounting.formatMoney(aData.pct75_hourly, '$', 0) : 'N/A';
										
					var findworkbutton;
                                        var programs_in_title = '';
					if (aData.zipcode) {
						programs_in_title = ' in '+ aData.zipcode;
					}					
					
					var deletebutton;
					var cma_careers_count = $('#cma-careers-count').val(); //defined as hidden element in vcncma-careers.tpl.php
		
                    if((cma_careers_count > 2 && aData.itemrank == 1) || (councelor_viewing_student_data == true && user_not_in_save_mode == true)){
                    	deletebutton = '<input type="button" title="Delete" value="Delete" class="cma-careers-delete-this-row vcn-button grid-action-button vcn-button-disable" name="'+aData.notebookid+'" />';
					} else {
						deletebutton = '<input type="button" title="Delete" value="Delete" class="cma-careers-delete-this-row vcn-button grid-action-button" name="'+aData.notebookid+'" />';
					}
					
					var targeted_career = '';
					var targetbutton = '';
                    if (aData.itemrank == 1 || cma_careers_count == 1 || (councelor_viewing_student_data == true && user_not_in_save_mode == true)) {
                      targetbutton = '<span class="datatable-save-target">'+			            		
                                         '<button title=\"Targeted Career\" class="vcn-button grid-action-button vcn-button-disable" >Target</button>'+			            		
                                         '</span>' ;
                    } else {
                      targetbutton = '<span class="datatable-save-target">'+			            		
                                         '<button title=\"Make this My Target\" class="vcn-button grid-action-button vcn-red-button" onclick="targetUserCareer(\''+aData.onetcode+'\',\''+isUserLoggedIn+'\',\''+userid+'\'); return false;" onkeypress="targetUserCareer(\''+aData.onetcode+'\',\''+isUserLoggedIn+'\',\''+userid+'\'); return false;" >Target</button>'+			            		
                                         '</span>' ;
                    }
                    
                    var programsbutton_name = basepath+'get-qualified/programs/onetcode/'+aData.onetcode; 
                    var findworkbutton_name = basepath+'findwork-results/career/'+aData.onetcode;
                    
                    if (aData.zipcode) {
                  	  programsbutton_name += '/zip/'+aData.zipcode;
                  	  findworkbutton_name += '/zip/'+aData.zipcode;
                    }
                    
                    var careerTitle = '<strong>'+aData.title+'</strong>';
                    if (!councelor_viewing_student_data || !user_not_in_save_mode) {
                      careerTitle = '<a href="'+basepath+'careers/'+aData.onetcode+'">'+careerTitle+'</a>';
                      programsbutton = '<input type="button" title="Education'+programs_in_title+'" value="Education" class="cma-careers-programs vcn-button grid-action-button" name="'+programsbutton_name+'" />';
                      findworkbutton = '<input type="button" title="Jobs" value="Jobs" class="cma-careers-jobs vcn-button grid-action-button" name="'+findworkbutton_name+'" />';
                    } else {
                      programsbutton = '<input type="button" title="Education'+programs_in_title+'" value="Education" class="cma-careers-programs vcn-button grid-action-button vcn-button-disable" name="'+programsbutton_name+'" />';
                      findworkbutton = '<input type="button" title="Jobs" value="Jobs" class="cma-careers-jobs vcn-button grid-action-button vcn-button-disable" name="'+findworkbutton_name+'" />';
                    }
                    
                    var targeticon = '';
					if (aData.itemrank == 1 || cma_careers_count == 1) {
						targeticon = ' <img src="'+imagepath+'buttons/target_icon.png" style="vertical-align:middle;" title="Targeted Career" alt="Targeted Career"/> ';						
					}
					
					if (cma_careers_count == 1) { // updating session onetcode when a career becomes a target because the user deletes the previously selected targeted career and this is the only career
						// left in the CMA
						$.ajax({url: basepath+'vcnuser-onetcode/update/'+aData.onetcode});
					}

                    var laytitle = '';
                    if (aData.laytitle.length > 0) {
                    	laytitle = '('+aData.laytitle+') ';
                    }
                                        
					$('td:eq(0)', nRow).html('<center><nobr>'+targeticon+'</nobr></center>');
					$('td:eq(1)', nRow).html(careerTitle+'<br/>'+laytitle+aData.short_desc);
					$('td:eq(2)', nRow).html(annual_salary_pct25 +' - '+ annual_salary_pct75);
					$('td:eq(3)', nRow).html(hourly_wage_pct25 +' - '+ hourly_wage_pct75);
					$('td:eq(4)', nRow).html(aData.typicaledutext);
					$('td:eq(5)', nRow).html('<center><nobr>'+programsbutton+' '+findworkbutton+'</nobr></center>');
					$('td:eq(6)', nRow).html('<center>'+targetbutton+deletebutton+'</center>');
					
				}
			});
			
			$('#cma-careers-listing .cma-careers-programs').live('click', function(e) {
                          
				var btnflag = $(this).hasClass("vcn-button-disable");
				
				if(btnflag == false) {
					var programsurl = $(this).attr("name");
					document.location.href = programsurl;	
                }
			});
			
			$('#cma-careers-listing .cma-careers-jobs').live('click', function(e) {
                          
				var btnflag = $(this).hasClass("vcn-button-disable");
				
				if(btnflag == false) {
					var joburl = $(this).attr("name");
					document.location.href = joburl;	
                }
			});
			
			$('#cma-careers-listing .cma-careers-delete-this-row').live('click', function(e) {
				
			  var btnflag = $(this).hasClass("vcn-button-disable");
				
			  if(btnflag == false) {			
				
				//e.preventDefault();				
				var dtCurrentRow = $(this).parents('tr')[0];
				var itemNotebookId = $(this).attr("name");
							
				cmaOptions = new Object();
				cmaOptions.dtCurrentRow = dtCurrentRow;
				cmaOptions.itemNotebookId = itemNotebookId;				
				cmaOptions.itemType = 'career';
				cmaOptions.itemTypeText = 'Career';				
				
				vcnDeleteNotebookItemFromCMA(cmaOptions);
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