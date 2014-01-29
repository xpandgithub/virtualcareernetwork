/*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ 


(function($) {
	Drupal.behaviors.vcn_getqualified_programs = {
		attach: function(context, settings) {
			var basepath = vcn_get_drupal7_base_path();
			
			var onetcode;
			var zipcode;
			var distance;
			var award_level;
			var education_category_name;
			var aoColumns;
			var aaSorting;
			
			onetcode = Drupal.settings.vcngetqualified.onetcode;
			zipcode = Drupal.settings.vcngetqualified.zipcode;
			distance = Drupal.settings.vcngetqualified.distance;
			award_level = Drupal.settings.vcngetqualified.award_level;
			career_title = Drupal.settings.vcngetqualified.career_title;
			career_title_link = Drupal.settings.vcngetqualified.career_title_link;
            preceding_career_title = Drupal.settings.vcngetqualified.preceding_career_title;
            preceding_career_title_link = Drupal.settings.vcngetqualified.preceding_career_title_link;
			education_category_name = Drupal.settings.vcngetqualified.education_category_name;
			
			$('#vcnfindlearning-programs-form').submit(function(){
				$('#edit-zipcode').removeClass('error');
				var zipcode_textbox_val = $.trim($('#edit-zipcode').val());
				if (zipcode_textbox_val != '') {
					var zip_validate = vcn_zipcode_validation(zipcode_textbox_val);
					if (!zip_validate) {
						custom_error_display('Please enter a valid US ZIP Code.');
						$('#edit-zipcode').addClass('error');
						return false;
					}
				}
			});
			
			$('#edit-zipcode').keydown(function(event) {
				var isnumeric = vcn_textbox_allow_only_numbers(event);
				if (isnumeric == true) {
					return isnumeric;
				} else {
					event.preventDefault();
				}
		    });
			
			var sAjaxSource = basepath;
			sAjaxSource += 'rest-dt-getqualified-programs';
			
			if (onetcode !== null) {
				sAjaxSource += '/onetcode/'+onetcode;
			}
			if (award_level !== null) {
				sAjaxSource += '/awlevel/'+award_level;
			}
			if (zipcode !== null) {
				sAjaxSource += '/zip/'+zipcode;
			} else {
				zipcode = null;
			}
			if (distance !== null) {
				sAjaxSource += '/distance/'+distance;
			}
			
			if (zipcode === null) {
				aoColumns = [
				             {"sWidth": "36%", "bSortable": true, "mData": null}, 
				             {"sWidth": "42%", "bSortable": true, "mData": null}, 
				             {"sWidth": "22%", "bSortable": true, "mData": null}
				            ];
				aaSorting = [[ 2, "asc" ],[ 0, "asc" ]] ;
			} else {
				aoColumns = [
				             {"sWidth": "33%", "bSortable": true, "mData": null}, 
				             {"swidth": "40%", "bSortable": true, "mData": null}, 
				             {"sWidth": "22%", "bSortable": true, "mData": null}, 
				             {"sWidth": "5%", "bSortable": true, "mData": null}
				            ];
				aaSorting = [[ 2, "asc" ],[ 3, "asc" ]] ;
			}
			
			$('#vcngetqualified-programs-table').dataTable({
				"bServerSide": true,
				"bProcessing": true,
				"bStateSave": false,
				"sAjaxSource": sAjaxSource,
				"sPaginationType": "full_numbers",
				"bAutoWidth": false,
				"aaSorting": aaSorting,
				"aoColumns": aoColumns,
				"bLengthChange": false,
				"bFilter" : false,
				"bInfo": false,
				"iDisplayLength": 10,
				"bRetrieve": true,
				"bDestroy": true,
				"fnRowCallback": function( nRow, aData, iDisplayIndex ) {
                                        var highlightedProvider = aData.association_id.length > 0 ? '<img src="' + vcn_get_images_basepath() + 'miscellaneous/star.png" width="12" alt="info icon" title="Participating Institution"> ' : '';
					$('td:eq(0)', nRow).html('<a href="'+basepath+'get-qualified/program/'+aData.program_id+'/cipcode/'+aData.cipcode+'/onetcode/'+aData.onetcode+'">'+aData.program_name+'</a>');
					
					$('td:eq(1)', nRow).html(highlightedProvider+'<a class="programs-boldfont" href="'+basepath+'get-qualified/school/'+aData.unitid+'">'+aData.inst_name+'</a>'+
		            		'<br/>'+aData.inst_address+'<br/>'+aData.inst_city+', '+aData.inst_state_abbrev+' '+aData.inst_zip+'<br/>'+vcn_telephone_number_format(aData.inst_general_telephone, true)+
		            		'<br/><a href="'+basepath+'get-qualified/financialaid/'+aData.unitid+'"><span class="programs-smallfont">Financial Aid</span></a>');
		            
		           $('td:eq(2)', nRow).html(aData.education_category_name);
		            
		            if (zipcode !== null) {
		            	var distance = new Number(aData.distance);
			            if (isNaN(distance) || distance === null) {
			            	distance = null;
			            } else {
			            	distance = distance.toFixed(1)+ ' m';
			            }
			            $('td:eq(3)', nRow).html(distance);
		            }
		            
		            return nRow;
		        },
		        "fnDrawCallback": function(oSettings) {
		        	var num_records = oSettings.fnRecordsDisplay();

		        	if (num_records == 0) {
		        		$('#vcngetqualified-programs-table').hide();
		        		$(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
		        	} else {
		        		if (oSettings._iDisplayLength >= num_records) {
		                   $(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
		                   $('#vcn-getqualified-next-step-div').css({"margin":"25px -10px 0px -10px"});
		                }
		        	}
		        	var programs_info_text = "";
		        	var zip_info_text = "";
		        	var awards_info_text = "";
		        	var programs = "";
		        	
	                if (zipcode != null) {
	                	if (award_level == null) {// need this logic so that the fullstop doesn't come in the middle of a sentence
                                        zip_info_text = ' within '+distance+' miles of '+zipcode+'.';
	                	} else {
                                        zip_info_text = ' within '+distance+' miles of '+zipcode+',';
	                	}
	                }
	                if (award_level != null) {
	                	if (award_level == 99) {
	                		awards_info_text = ' for All award levels.';
	                	} else {
	                		awards_info_text = ' for '+education_category_name+' and <span style="text-decoration:underline;">below</span>.';
	                	}
	                }
	                
	                if (num_records == 0) {
	                	if (award_level == 99 && (zipcode == null)) {
	                		programs_info_text = 'For ' + career_title +  ' no additional education may be necessary, or no program is available at this time.';
	                	} else {
	                		programs_info_text = 'No education programs were found for the given criteria. You may find more programs by increasing the <span class="strong">Distance</span> or by choosing a more advanced <span class="strong">Highest Education Level to Pursue<span>.';
	                	}
	                } else {
	                	programs_info_text = "<p><span class=\"vcngetqualified-results-heading\">The following is a list of Education Programs associated with " +
	                			"<a href='"+career_title_link+"'>"+career_title+"</a> in the locality of your choice. ";

	                	if (preceding_career_title_link.length) {
                          programs_info_text += 'Note that this does not include the associated training and educational programs for the required prior career, <a href="'+preceding_career_title_link+'">'+preceding_career_title+'</a>.';
                        }
                        
                        if (num_records > 100) {
                        	if (!zipcode) {
                            	programs_info_text += ' (You may narrow down your result by choosing a <span class="strong">Preferred Location</span>.)';
                            } else {
                            	programs_info_text += ' (You may narrow down your result by making the <span class="strong">Distance</span> shorter.)';
                            }
                        }
                         
                        programs_info_text += "</span></p>";
                                
                        if (num_records == 1) {
                        	programs = num_records + " Program ";
	                	} else {
	                		programs = num_records + " Programs ";
	                	}
	                	programs_info_text += "<div id=\"vcngetqualified-programs-info-text\"><span class=\"vcngetqualified-results-heading\">"+programs+"found for <a href='"+career_title_link+"'>"+career_title+"</a>" + zip_info_text + awards_info_text+"</span></p>";
	                }
	                
	                $('#vcngetqualified-programs-data-info').html(programs_info_text);
	           }
			});
		}
	};
})(jQuery);