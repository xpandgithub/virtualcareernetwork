/*
The Virtual Career Network (VCN) is an "open source, open content" workforce services and online learning delivery platform built and operated by the American Association of Community Colleges (AACC) under a grant from the Employment and Training Administration (ETA) of the United States Department of Labor (DOL).

Copyright (C) 2012 American Association of Community Colleges

This file is part of the source code for the Virtual Career Network.

The Virtual Career Network is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

The Virtual Career Network is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/ 


(function($) {
	Drupal.behaviors.vcncma_employment_history_form = {
		attach: function(context, settings) {
			
			$("#edit-employer-name, #edit-contact-name, #edit-job-title, #edit-state").limitkeypress({ rexp: /^[A-Za-z\s\'\-]*$/ });	
			$("#edit-contact-phone").limitkeypress({ rexp: /^[0-9]*$/ });
			$("#edit-zipcode").limitkeypress({ rexp: /^[0-9-]*$/ });
			$('#edit-start-date, #edit-end-date').limitkeypress({ rexp: /^[0-9\-]*$/ });
		}
	};  
	 
})(jQuery);