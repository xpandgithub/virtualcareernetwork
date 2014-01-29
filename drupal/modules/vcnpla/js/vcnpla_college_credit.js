/*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ 


(function($) {
	Drupal.behaviors.vcnpla_college_credit = {
		attach: function(context, settings) {
			
			/*$('#edit-year-course-completed, #edit-num-credit-hours').keydown(function(event) { 
				var is_numeric = vcn_textbox_allow_only_numbers(event);
				if (is_numeric == true) {
					return is_numeric;
				} else {
					event.preventDefault();
				}
			});*/
			
			$('#edit-year-course-completed, #edit-num-credit-hours').bind('paste', function(event) {
				var regex = new RegExp("^\\d+$");
				setTimeout(function() {
					var focused = $(':focus');
					var str = $(focused).val();
					if (!regex.test(str)) {
						$(focused).val("");
					}
			    }, 1);
			});
			
			
			/*$('#edit-final-grade, #edit-school-name, #edit-program-name, #edit-course-name').keydown(function(event) { 
				var is_alphabet = vcn_textbox_allow_only_alphabets(event);
				if (is_alphabet == true) {
					return is_alphabet;
				} else {
					event.preventDefault();
				}
			});*/
			
			$('#edit-final-grade').bind('paste', function(event) {
				var regex = new RegExp("^[a-zA-Z-+]+$");
				setTimeout(function() {
					var focused = $(':focus');
					var str = $(focused).val();
					if (!regex.test(str)) {
						$(focused).val("");
					}
			    }, 1);
			});
			
			/*$('#edit-course-number').keydown(function(event) {
				var is_alphabet = vcn_textbox_allow_only_alphabets(event);
				var is_numeric = vcn_textbox_allow_only_numbers(event);
				var is_allowed_char = false;
				
				if ((is_alphabet == true) || (is_numeric == true)) {
					is_allowed_char = true;
				}
				
				if (is_allowed_char == true) {
					return is_allowed_char;
				} else {
					event.preventDefault();
				}
				
			});*/
			
		}
	};
})(jQuery);

//jquery document ready
jQuery(document).ready(function() {
	jQuery("#edit-school-name, #edit-program-name, #edit-course-name").limitkeypress({ rexp: /^[A-Za-z\s\'\-]*$/ });	
	jQuery("#edit-final-grade").limitkeypress({ rexp: /^[A-Za-z\+\-]*$/ });
	jQuery("#edit-course-number").limitkeypress({ rexp: /^[A-Za-z0-9\s]*$/ });
	jQuery("#edit-year-course-completed, #edit-num-credit-hours").limitkeypress({ rexp: /^[0-9]*$/ });
});