/*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ 


(function($){
	Drupal.behaviors.vcn_careergrid = {
		attach: function(context, settings) {
			var basepath = vcn_get_drupal7_base_path();
			
			var sAjaxSource = basepath+"rest-dt-careergrid";
			var education_level = Drupal.settings.vcncareergrid.education_level;
			var type_of_work = Drupal.settings.vcncareergrid.type_of_work;
			var search_term = Drupal.settings.vcncareergrid.search_term;
			
			var industry_name = vcn_get_industry_name();
			
			if (education_level) {
				sAjaxSource += '/education-level/' + education_level;
			}
			
			if (type_of_work) {
				sAjaxSource += '/work-type/' + type_of_work;
			}
			
			if (search_term) {
				sAjaxSource += '/search-term/' + search_term;
			}
			
			$("#edit-careergrid-search-box").limitkeypress({ rexp: /^[A-Za-z\s\-]*$/ });
			var search_box_text = $.trim($("#edit-careergrid-search-box").val());
			
			$('#vcncareergrid-table').dataTable({
				"bProcessing": true,
				"bServerSide": true,
				"sAjaxSource": sAjaxSource,
				"aoColumns": [
				              {"sWidth": "45%", "mData":null},
				              {"sWidth": "22%", "mData":null},
				              {"sWidth": "15%", "mData":null},
				              {"sWidth": "18%", "mData":null}
				             ],
				"aaSorting": [[3,"asc"],[0,"asc"]],
				"sPaginationType": "full_numbers",
				"bLengthChange": false,
				"bFilter" : false,
				"bInfo": false,
				"iDisplayLength": 10,
				"bRetrieve": true,
				"bDestroy": true,
				"bStateSave": true,
				"oLanguage": {
					"sEmptyTable": "<span class='vcndatatable-empty-message'>0 " +industry_name+" careers found.</span>"
				},
				"fnRowCallback": function( nRow, aData, iDisplayIndex ) {
					
					var onetcode = aData.onetcode;
					var career_detail_link = '<a href="'+basepath+'careers/'+onetcode+'"><strong>'+aData.title+'</strong></a>';
					
					//accounting is the name of library used to format currency. ref: http://josscrowcroft.github.io/accounting.js/
					var annual_salary_pct25 = (aData.pct25_annual) ? accounting.formatMoney(aData.pct25_annual, '$', 0) : null;
					var annual_salary_pct75 = (aData.pct75_annual) ? accounting.formatMoney(aData.pct75_annual, '$', 0) : null;
					
					var hourly_wage_pct25 = (aData.pct25_hourly) ? accounting.formatMoney(aData.pct25_hourly, '$', 0) : null;
					var hourly_wage_pct75 = (aData.pct75_hourly) ? accounting.formatMoney(aData.pct75_hourly, '$', 0) : null;
					
					var annual_salary_text;
					var hourly_wage_text;
					
					//code to make the matching words in laytitle bold
					var final_laytitle = aData.laytitle;
					if (aData.laytitle) {
						if (search_box_text && search_box_text.length > 0) {
							var laytitle = aData.laytitle;
							var laytitle_array = laytitle.split(" ");
							var search_box_text_array = search_box_text.split(" ");
							var search_box_text_array_length = search_box_text_array.length;
							var laytitle_array_length = laytitle_array.length;
							for (var i = 0; i < search_box_text_array_length; i++) {
							   for (var j = 0; j < laytitle_array_length; j++) {
							       if (search_box_text_array[i].toLowerCase() == laytitle_array[j].toLowerCase()) {
							           laytitle_array[j] = '<span class="strong">'+ laytitle_array[j] +'</span>';
							       }
							   }
							}
							final_laytitle = laytitle_array.join(" ");
						} // end of code to make the matching words in laytitle bold
						$('td:eq(0)', nRow).html(career_detail_link+'<br/>('+ final_laytitle +') ' + aData.detailed_description);
					} else {
						$('td:eq(0)', nRow).html(career_detail_link+'<br/>'+aData.detailed_description);
					}
					
					
					if(!annual_salary_pct25 && !annual_salary_pct75) {
						annual_salary_text = 'Not Available';
					} else if(!annual_salary_pct25 && annual_salary_pct75) {
						annual_salary_text = 'N/A - '+ annual_salary_pct75;
					} else if(annual_salary_pct25 && !annual_salary_pct75) {
						annual_salary_text = annual_salary_pct25 +' - N/A';
					} else {
						annual_salary_text = annual_salary_pct25 +' - '+ annual_salary_pct75;
					}
					
					$('td:eq(1)', nRow).html('<center>'+annual_salary_text+'</center>');
					
					
					if(!hourly_wage_pct25 && !hourly_wage_pct75) {
						hourly_wage_text = 'Not Available';
					} else if(!hourly_wage_pct25 && hourly_wage_pct75) {
						hourly_wage_text = 'N/A - '+ hourly_wage_pct75;
					} else if(hourly_wage_pct25 && !hourly_wage_pct75) {
						hourly_wage_text = hourly_wage_pct25 +' - N/A';
					} else {
						hourly_wage_text = hourly_wage_pct25 +' - '+ hourly_wage_pct75;
					}
					
					$('td:eq(2)', nRow).html('<center>'+hourly_wage_text+'</center>');
					
					$('td:eq(3)', nRow).html(aData.education_category_name);

				},
				"fnDrawCallback": function(oSettings) {
					var num_records = oSettings.fnRecordsDisplay();
					var num_records_text = '';
					if (num_records == 1) {
						num_records_text += '1 career found.';
					} else {
						num_records_text += num_records + ' careers found.';
					}
					$('#vcncareergrid-info').html(num_records_text);
					if (oSettings._iDisplayLength >= oSettings.fnRecordsDisplay()) {
			            $(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
			        }
				}
			});
			
		}
	};	
})(jQuery);