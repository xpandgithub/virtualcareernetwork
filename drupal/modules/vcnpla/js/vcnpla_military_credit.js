/*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ 


(function($) {
	Drupal.behaviors.vcnpla_military_credits = {
		attach: function(context, settings) {
			var basepath = vcn_get_drupal7_base_path();
			var image_basepath = vcn_get_images_basepath();
			
			var military_credits_branch;
			var military_credits_search_term;
			var sAjaxSource = basepath+"rest-dt-pla-military-credits";
			
			military_credits_branch = Drupal.settings.vcnpla.military_credits_branch;
			military_credits_search_term = Drupal.settings.vcnpla.military_credits_search_term;
			display_result = Drupal.settings.vcnpla.display_result;
			
			if (military_credits_branch !== null) {
				sAjaxSource += '/branch/'+military_credits_branch;
			}
			if (military_credits_search_term !== null) {
				sAjaxSource += '/search-term/'+military_credits_search_term;
			}
			
			if(display_result) {
				
				$('#vcnpla-military-credits-table').dataTable({
					"bProcessing": true,
					"bServerSide": true,
					"sAjaxSource": sAjaxSource,
					"sPaginationType": "full_numbers",
					"bAutoWidth": false,
					"aaSorting": [[ 0, "asc" ]],
					"aoColumns": [{"sWidth": "90%", "bSortable": true, "mData": null}, {"sWidth": "10%", "bSortable": false, "mData": null}],
					"bLengthChange": false,
					"bFilter" : false,
					"bInfo": false,
					"iDisplayLength": 10,
					"bRetrieve": true,
					"bDestroy": true,
					"bStateSave": true,
					"fnRowCallback": function( nRow, aData, iDisplayIndex ) {
						var save_courses_ajax_link = basepath+'pla/save-courses/'+aData.ace_id+'/'+aData.start_date_original+'/'+aData.end_date_original+'/military';
						var link = basepath+'pla/military-credit-details/'+ aData.ace_id +'/' + aData.start_date_original + '/' + aData.end_date_original;
						if (military_credits_branch) {
							link += '/'+military_credits_branch;
							if (military_credits_search_term) {
								link += '/'+military_credits_search_term;
							}
						}
						$('td:eq(0)', nRow).html('<a href="'+ link + '" title="ACE# '+ aData.ace_id +'">'+ aData.first_title + 
												' (Course taken between ' + aData.start_date + ' and '+ aData.end_date + ' )</a>');
						$('td:eq(1)', nRow).html('<span class="datatable-save-target">'+			            		
	        		             '<button title=\"Save\" class="vcn-button save-target-icon" onclick="vcnSaveTarget(\''+save_courses_ajax_link+'\',\'military_course\',\'save\',\''+aData.vcn_user_id+'\',\''+aData.is_user_logged_in+'\',0); return false;" onkeypress="vcnSaveTarget(\''+save_courses_ajax_link+'\',\'military_courses\',\'save\',\''+aData.vcn_user_id+'\',\''+aData.is_user_logged_in+'\',0); return false;" ><img src="'+image_basepath+'buttons/save_icon2.png" title="Save" alt="Save Military Credit Course"></button>'+			            		
	        		             '</span>');
						
						return nRow;
					},
					"fnDrawCallback": function(oSettings) {
		                if (oSettings._iDisplayLength >= oSettings.fnRecordsDisplay()) {
		                   jQuery(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
		                }
		           }
				});
				
				$('html, body').animate({scrollTop: $('#vcn-pla-military-credit-main-content').offset().top}, 1000);
			}
		}
	};
})(jQuery);