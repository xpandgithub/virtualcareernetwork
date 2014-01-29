/*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ 


(function($) {
	Drupal.behaviors.vcnpla_national_exam_details = {
		attach: function(context, settings) {
			var basepath = vcn_get_drupal7_base_path();
			var ace_id = Drupal.settings.vcnpla.ace_id;
			var start_date = Drupal.settings.vcnpla.start_date;
			var end_date = Drupal.settings.vcnpla.end_date;
			var user_id = Drupal.settings.vcnpla.user_id;
			var isUserLoggedIn = Drupal.settings.vcnpla.isUserLoggedIn;
			
			var save_courses_ajax_link = basepath+'pla/save-courses/'+ace_id+'/'+start_date+'/'+end_date+'/national-exams';

			jQuery('#vcnpla-details-page-save-button button#save-to-wishlist').click(function(){
				vcnSaveTarget(save_courses_ajax_link,'national_exams_course','save', user_id, isUserLoggedIn, 0); 
				return false;
			});
		}
	};
})(jQuery);